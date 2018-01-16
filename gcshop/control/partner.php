<?php

/**
 * 光彩全球三方平台合作对接
 *
 */
defined('GcWebShop') or exit('Access Invalid!');

class partnerControl extends BaseBuyControl {

	/**
	 * 接口允许的行为名称
	 */
	private $partner_action = array('create_order','select_order','goods_receipt','select_tax');

	public function __construct() {
		parent::__construct();
	}
	
	public function indexOp(){
		$postxml = file_get_contents('php://input');
		$data    = base64_decode(urldecode($postxml));
		
		$xmlArray = self::xmlToArray($data);
		$return_type = $xmlArray['gc']['head']['return_type'];
		if(empty($xmlArray) || !is_array($xmlArray)){
			self::showMessage('error','F','非法数据请求',$return_type);
		}
		if(empty($xmlArray['gc']['head']['action'])){
			self::showMessage('error','F','接口名称不能为空',$return_type);
		}
		// 验证必填项
		$emptyArray=$this->emptyArray();
		$checkArray=$emptyArray[$xmlArray['gc']['head']['action']];
		self::checkEmptyXml($xmlArray,$checkArray,$return_type);
		
		$doc = new DOMDocument();
		$doc->loadXML($data);
		$type=$doc->getElementsByTagName('action')->item(0)->nodeValue;
		$appid=$doc->getElementsByTagName('partner_id')->item(0)->nodeValue;
		$partnerInfo=Model('partner')->getPartnerInfo(array('appid'=>$appid));
		if(!$partnerInfo){
			self::showMessage('error','F','验证合作方信息失败',$return_type);
		}
		$re_xml = substr($data,strpos( $data, '<gc>'));
		$sign_xml = substr($re_xml,0,strpos($re_xml ,'<sign>'));
		$appkey = '<appkey>'.$partnerInfo['appkey'].'</appkey>';
		if(empty($xmlArray['sign']) || $xmlArray['sign']!=md5($sign_xml.$appkey)){ 
			self::showMessage('error','F','签名错误',$return_type);
		}
		if(!in_array($type,array('create_order', 'select_order', 'goods_receipt', 'select_tax'))){
			self::showMessage('error','F','请求方法不存在',$return_type);
		}
		$this->$type($xmlArray);
	}
	
	/**
	 *  生成订单接口
	 */
	private function create_order($xmlArray){
		$mobile = $xmlArray['gc']['body']['reciver']['mobile'];
		$patner_id = $xmlArray['gc']['head']['partner_id'];
		$return_type = $xmlArray['gc']['head']['return_type'];
		$member_info = $this->getUserInfoForMobile($xmlArray['gc'],$return_type);
		if(!$member_info){
			self::showMessage('error','F','用户信息保存失败',$return_type);
		}
		$array=$xmlArray['gc']['body']['goods']['detail'];
		if(!is_array($array[0])){
			$goods[0]=$array;
		}else{
			$goods=$array;
		}
		$cart_id=$this->addcart($goods, $return_type);
		if(empty($cart_id)){
			self::showMessage('error','F','操作失败',$return_type);
		}
		$addressArray=$xmlArray['gc']['body']['reciver'];
		$addressId=$this->generateAddress($addressArray,$_SESSION['member_id']);
		
		$data=array();
		foreach($goods as $key=>$value){
			$goods_info = Model('goods')->getGoodsInfo(array('goods_serial'=>$value['serial']), 'goods_id,goods_serial,store_id,goods_costprice');
			
			$store_id .= $goods_info['store_id'].',';
			$a =rtrim($store_id , ",");
			$store_id_array = explode(',',$a); 
			
			if($value['price'] < $goods_info['goods_costprice']){
				self::showMessage('error','F','商品'.$goods_info['goods_serial'].'的价格低于了限价',$return_type);
			}
			if($goods_info['goods_id']){
				$allow_offpay_batch[$goods_info['store_id']] = 0;
			}else{
				self::showMessage('error','F','商品货号'.$goods_info['goods_serial'].'无效',$return_type);
			}
		}
		if(count(array_unique($store_id_array)) != 1){
			self::showMessage('error','F','单个订单的商品不在同一个仓库',$return_type);
		}
		$buy_encrypt=array();
		$data['cart_id']=$cart_id;
		$data['partner_id']=$patner_id;
		$data['out_trade_no']=$xmlArray['gc']['body']['other']['trade_no'];
		$data['pay_message']= $xmlArray['gc']['body']['other']['note'];
		$data['ifcart'] = '1';
		$data['pay_name'] = "online";
		$data['vat_hash'] =  Logic('buy')->buyEncrypt('deny_vat', $_SESSION['member_id']);
		$data['address_id'] = $addressId;
		$data['buy_city_id'] = $xmlArray['gc']['body']['reciver']['city_id'];
		$data['allow_offpay'] = '1';
		$data['allow_offpay_batch'] = "2:0";
		$data['offpay_hash'] = Logic('buy')->buyEncrypt('allow_offpay', $_SESSION['member_id']);
		$data['offpay_hash_batch'] =  Logic('buy')->buyEncrypt($allow_offpay_batch, $_SESSION['member_id']);
		$data['third']=1;
		$data['invoice_id'] ="" ;
		//三方订单是否存在
		$is_repetition = Model('order')->getOrderInfo(array('out_trade_no'=>$data['out_trade_no']), '', 'order_sn,order_amount,out_trade_no');
		if(is_array($is_repetition) && !empty($is_repetition)){
			self::showMessage('error','F','当前订单已经存在,三方订单号为'.$is_repetition['out_trade_no'],$return_type,array('order_sn'=>$is_repetition['order_sn'],'order_amount'=>$is_repetition['order_amount']));
		}else{
			$result = logic('buy')->buyStep2($data, $_SESSION['member_id'], $_SESSION['member_name'], $_SESSION['member_email'], $buy_encrypt);
		}
		if(!$result){
			self::showMessage('error','F','操作失败',$return_type);
		}else if($result['state'] == false){
			self::showMessage('error','F',$result['msg'],$return_type);
		}else{
			$orderXml ="<?xml version='1.0' encoding='utf-8'?>"; 
			$orderXml.="<gc>";
			$orderXml.="<head>";
			$orderXml.="<action>create_order</action>";
			$orderXml.="<time>".date('YmdHis', time())."</time>";
			$orderXml.="<result_code>success</result_code>";
			$orderXml.="<key>".$member_info['token']."</key>";
			$orderXml.="</head>";
			$orderXml.="<body>";
			$orderXml.="<order>";
			foreach($result['data']['order_list'] as $key=>$value){
				$orderXml.="<order_sn>".$value['order_sn']."</order_sn>";
				$orderXml.="<pay_sn>".$value['pay_sn']."</pay_sn>";
				$orderXml.="<trade_no>".$value['out_trade_no']."</trade_no>";
				$orderXml.="<order_tax>".$value['order_tax']."</order_tax>";
				$orderXml.="<order_amount>".$value['order_amount']."</order_amount>";
			}
			$orderXml.="</order>";
			$orderXml.="</body>";
			$orderXml.="</gc>";
			
			if($return_type == 'xml'){
				echo base64_encode($orderXml);
				// echo $orderXml;
			}else if($return_type == 'json'){
				$json = self::xmlToJson($orderXml);
				echo base64_encode($json);
				// echo $json;
			}
		}
		exit;
	}
	
	/**
	 *	订单查询接口
	 */
	private function select_order($xmlArray){
		$return_type = $xmlArray['gc']['head']['return_type'];
		$condition=array();
		$condition['order_sn'] =$xmlArray['gc']['body']['order']['order_sn'];
		$condition['out_trade_no'] =$xmlArray['gc']['body']['order']['trade_no'];
		$order_info = Model('order')->getOrderInfo($condition, array('order_common','order_goods'));
		if(!$order_info){
			self::showMessage('error','F','该订单不存在',$return_type);
		}else{
			if($order_info['order_state'] == 10){
				$state = 10;
				$message = '订单还未付款';
			}else if($order_info['order_state'] == 40){
				$state = 90;
				$message = '订单已收货';
			}
			$store_from = $order_info['extend_order_goods'][0]['store_from'] ;
			if($store_from == 1 || $store_from == 2){
				$orders = DB::getAll("select list_status, MAKE_CSV, OIF_MEMO from 33hao_mess_order_info where ORDER_SN =".$order_info['order_sn']);
				if($order_info['order_state'] == 30 && $order_info['mess_state'] == 10){
					$state = 30;
					$message = '订单还未申报海关';
				}else if($order_info['order_state'] == 30 && $order_info['mess_state'] == 20 && $orders[0]['list_status'] != 800){
					$state = 40;
					$message = '订单已申报海关';
				}else if($orders[0]['list_status'] == 800 && $orders[0]['MAKE_CSV'] == 10){
					$state = 50;
					$message = '订单申报海关成功';
				}else if($orders[0]['MAKE_CSV'] == 20 && $orders[0]['OIF_MEMO'] == NULL){
					$state = 60;
					$message = '订单已推送到保税仓库';
				}else if($orders[0]['MAKE_CSV'] == 20 && $orders[0]['OIF_MEMO']){
					$state = 70;
					$message = '保税仓库已出货';
				}
			}else{
				if($order_info['order_state'] == 20){
					$state = 20;
					$message = '订单还未发货';
				}else if($order_info['order_state'] == 30){
					$state = 80;
					$message = '订单已设置发货';
				}
			}
			$payment_time = $order_info['payment_time'] ? date('YmdHis', $order_info['payment_time']) : '';
			$finished_time = $order_info['finnshed_time'] ? date('YmdHis', $order_info['finnshed_time']) : '';
			$logistics = Model('express')->getExpressInfo($order_info['extend_order_common']['shipping_express_id']);
			$selectXml ="<?xml version='1.0' encoding='utf-8'?>";
			$selectXml.="<gc>";
			$selectXml.="<head>";
			$selectXml.="<action>select_order</action>";
			$selectXml.="<time>".date('YmdHis', time())."</time>";
			$selectXml.="<result_code>success</result_code>";
			$selectXml.="</head>";
			$selectXml.="<body>";
			$selectXml.="<order>";
			$selectXml.="<order_sn>".$order_info['order_sn']."</order_sn>";
			$selectXml.="<trade_no>".$order_info['out_trade_no']."</trade_no>";
			$selectXml.="<pay_sn>".$order_info['pay_sn']."</pay_sn>";
			$selectXml.="<freight_name>".$logistics['e_name']."</freight_name>";
			$selectXml.="<freight_no>".$order_info['shipping_code']."</freight_no>";
			$selectXml.="<add_time>".date('YmdHis', $order_info['add_time'])."</add_time>";
			$selectXml.="<payment_time>".$payment_time."</payment_time>";
			$selectXml.="<finished_time>".$finished_time."</finished_time>";
			$selectXml.="<goods_amount>".$order_info['goods_amount']."</goods_amount>";
			$selectXml.="<shipping_fee>".$order_info['shipping_fee']."</shipping_fee>";
			$selectXml.="<order_tax>".$order_info['order_tax']."</order_tax>";
			$selectXml.="<order_amount>".$order_info['order_amount']."</order_amount>";
			$selectXml.="<order_state>".$state."</order_state>";
			$selectXml.="<message>".$message."</message>";
			$selectXml.="</order>";
			$selectXml.="<consignee>";
			$selectXml.="<name>".$order_info['extend_order_common']['reciver_name']."</name>";
			$selectXml.="<address>".$order_info['extend_order_common']['reciver_info']['address']."</address>";
			$selectXml.="</consignee>";
			$selectXml.="</body>";
			$selectXml.="</gc>";
			
			if($return_type == 'xml'){
				echo base64_encode($selectXml);
				// echo $selectXml;
			}else if($return_type == 'json'){
				$json = self::xmlToJson($selectXml);
				echo base64_encode($json);
				// echo $json;
			}
		}
		exit;
	}
	
	/**
	 *	订单收货接口
	 */
	private function goods_receipt($xmlArray){
		$return_type = $xmlArray['gc']['head']['return_type'];
		$condition = array();
		$condition['order_sn'] =$xmlArray['gc']['body']['order']['order_sn'];
		$condition['out_trade_no'] =$xmlArray['gc']['body']['order']['trade_no'];
		$order_info = Model('order')->getOrderInfo($condition);
		if(!$order_info){
			self::showMessage('error','F','该订单不存在',$return_type);
		}
		
		if($order_info['order_state'] == 30){
			$result = Logic('order')->changeOrderStateReceive($order_info,'buyer');
		}else if($order_info['order_state'] == 40){
			self::showMessage('error','F','该订单已经收货',$return_type);
		}else{
			self::showMessage('error','F','该订单还未发货',$return_type);
		}
		
		if($result['state']){
			self::showMessage('success','T','操作成功',$return_type);
		}else{
			self::showMessage('error','F','操作失败',$return_type);
		}
	}
	
	/**
	 *	查询商品税金接口（仅做参考，没包含运费、优惠等）
	 */
	private function select_tax($xmlArray){
		$goods_array = $xmlArray['gc']['body']['goods']['detail'];
		$taxXml ="<?xml version='1.0' encoding='utf-8'?>";
		$taxXml.="<gc>";
		$taxXml.="<head>";
		$taxXml.="<action>select_tax</action>";
		$taxXml.="<time>".date('YmdHis', time())."</time>";
		$taxXml.="<result_code>success</result_code>";
		$taxXml.="</head>";
		$taxXml.="<body>";
		foreach($goods_array as $key=>$val){
			$goods_info = Model('goods')->getGoodsInfo(array('goods_serial'=>$val['serial']), 'goods_id,goods_hscode,goods_commonid');
			if(!$goods_info){
				$taxXml.="<message>商品".$val['serial']."不存在</message>";
			}else{
				$goods_info['goods_price'] = $val['price'];
				$tax = Logic('tax')->single_times_allow_2000($goods_info);
				$goods_tax = ncPriceFormat($tax);
				$taxXml.="<detail>";
				$taxXml.="<serial>".$val['serial']."</serial>";
				$taxXml.="<price>".$val['price']."</price>";
				$taxXml.="<goods_tax>".$goods_tax."</goods_tax>";
				$taxXml.="</detail>";
			}
		}
		$taxXml.="</body>";
		$taxXml.="</gc>";
		
		if($return_type == 'xml'){
			echo base64_encode($taxXml);
			// echo $taxXml;
		}else if($return_type == 'json'){
			$json = self::xmlToJson($taxXml);
			echo base64_encode($json);
			// echo $json;
		}
		exit;
	}
	
	//提交订单生成会员信息（gc加订购人手机号为用户名）
	private function getUserInfoForMobile($param, $return_type){
		$mobile = $param['body']['reciver']['buy_mobile'];
		if(!isset($mobile)){
			self::showMessage('error','F','手机号码不能为空',$return_type);
		}
		$member_info=Model('member')->getMemberInfo(array('member_mobile'=>$mobile));
		if(!$member_info){
			$register_info = array();
			$register_info['username'] = 'gc'.$mobile ;
			$register_info['member_nickname'] = $param['body']['other']['name'];
			$register_info['member_truename'] = $param['body']['reciver']['buy_name'];
			$register_info['member_code'] = $param['body']['reciver']['idnum'];
			$register_info['password'] = 'qqbsmall';
			$register_info['mobile']   = $mobile;
			$register_info['member_mobile_bind'] = 1;
			$register_info['saleplat_id'] = $param['head']['partner_id'];
			
			$member_info=Model('member')->registerm($register_info);
		}
        //用于移动端支付生成的登录令牌
        $token_info = array();
        $token = md5($member_name . strval(TIMESTAMP) . strval(rand(0,999999)));
        $token_info['member_id'] = $member_info['member_id'];
        $token_info['member_name'] = $member_info['member_name'];
        $token_info['token'] = $token;
        $token_info['login_time'] = TIMESTAMP;
        $token_info['client_type'] = 'partner' ;
		Model('mb_user_token')->addMbUserToken($token_info);
		
		$member_info['token'] = $token;
		if($member_info){
			//创建回话session,保证订单生成
			Model('member')->createSession($member_info);
			return $member_info;
		}
	}
	
	//添加商品到购物车
	private function addcart($goods, $return_type){
		$cartModel=Model('cart');
		$goods_info=array();
		$cartArray=array();
		foreach($goods as $key=>$value){
			$where = array();
			$where['goods_state'] = 1;
			$where['goods_serial'] = $value['serial'];
			$goodsInfo=Model('goods')->getGoodsStoreList($where);
			if(empty($goodsInfo)){
				self::showMessage('error','F','商品无效',$return_type);
			}
			if($goodsInfo[0]['goods_storage'] < 1){
				self::showMessage('error','F','商品'.$goodsInfo[0]['goods_serial'].'库存不足',$return_type);
			}
			$goodsNum                  =$value['num'];
			$goods_info['goods_price'] =$value['price'];
			$goods_info['buyer_id']    =$_SESSION['member_id'];
			$goods_info['store_id']    =$goodsInfo[0]['store_id'];
			$goods_info['store_name']  =$goodsInfo[0]['store_name'];
			$goods_info['store_from']  =$goodsInfo[0]['store_from'];
			$goods_info['goods_id']    =$goodsInfo[0]['goods_id'];
			$goods_info['goods_name']  =$goodsInfo[0]['goods_name'];
			$goods_info['goods_weight']=$goodsInfo[0]['goods_weight'];
			$goods_info['goods_taxes'] =$goodsInfo[0]['goods_taxes'];
			$goods_info['goods_image'] =$goodsInfo[0]['goods_image'];
			$goods_info['bl_id'] = isset($goodsInfo['bl_id']) ? $goodsInfo['bl_id'] : 0;
			$result=$cartModel->checkCart(array('goods_id'=>$goodsInfo[0]['goods_id'],'buyer_id'=>$_SESSION['member_id']));
			$cartId=$result['cart_id'];
			if(!$cartId){
				$cartId=$cartModel->addCart($goods_info,'db',$goodsNum);
			}
			$cartArray[$key]=$cartId.'|'.$goodsNum;
		}
		return $cartArray;
	}
	
	//增加更新收货地址
	private function generateAddress($addressArray,$member_id){
		$address_class=Model('address');
		$condition=array();
		$condition['member_id']=$member_id;
		$condition['true_name']=trim($addressArray['take_name']);
		$condition['mob_phone']=trim($addressArray['take_mobile']);
		$addressInfo=Model()->table('address')->where($condition)->find();
		
		$data = array();
		$data['true_name'] = trim($addressArray['take_name']);
		$data['order_name'] = '';
		$data['true_idnum'] = '';
		$data['area_id'] = intval($addressArray['area_id']);
		$data['city_id'] = intval($addressArray['city_id']);
		$data['area_info'] = $addressArray['area_info'];
		$data['address'] = $addressArray['address'];
		$data['tel_phone'] = '';
		$data['mob_phone'] = $addressArray['take_mobile'];
		$data['is_default'] = 1;
		
		if($addressInfo){
			$editCondition=array();
			$editCondition['member_id']=$member_id;
			$editCondition['address_id']=$addressInfo['address_id'];
			$result=$address_class->editAddress($data,$editCondition);
			return $addressInfo['address_id'];
		}else{
			$data['member_id']=$member_id;
			$result=$address_class->addAddress($data);
			return $result;
		}
	}
	
	//接收数据可为空项
	private function emptyArray(){
		$array=array();
		$array['create_order']=array('note');
		$array['goods_receipt']=array();
		$array['select_order']=array();
		$array['select_tax']=array();
		return $array;
	}
	
	//验证接收数据的必填项
	private static function checkEmptyXml($array,$emptyArray,$return_type){
		foreach($array as $k=>$v){
			if(is_array($array[$k]) && !empty($array[$k])){
				self::checkEmptyXml($array[$k],$emptyArray,$return_type);
			}else{
				if(empty($array[$k]) && !in_array($k,$emptyArray)){
					self::showMessage('error',"F",'数据错误,'.$k.'不能为空',$return_type);
				}
			}
		}
	}
	
	//回执常规模板
	private static function showMessage($type,$code,$message,$return_type,$data=array()){
		$errorXml='<?xml version="1.0" encoding="utf-8"?>';
		$errorXml.='<gc>';
		if($data['order_sn']){
			$errorXml.='<order_sn>'.$data['order_sn'].'</order_sn>';
		}
		if($data['order_amount']){
			$errorXml.='<order_amount>'.$data['order_amount'].'</order_amount>';
		}
		$errorXml.='<time>'.date('YmdHis', time()).'</time>';
		$errorXml.='<is_success>'.$code.'</is_success>';
		$errorXml.='<'.$type.'Message>'.$message.'</'.$type.'Message>';
		$errorXml.='</gc>';
		
		if($return_type == 'xml'){
			echo base64_encode($errorXml);
			// echo $errorXml;
		}else if($return_type == 'json'){
			$json = self::xmlToJson($errorXml); 
			echo base64_encode($json);
			// echo $json;
		}
		exit;
	}
	
	//xml转换成数组
	private static function xmlToArray($xml){ 
		libxml_disable_entity_loader(true); 
		$xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA); 
		$array = json_decode(json_encode($xmlstring),true); 
		return $array;
	}
	
	//xml转换成json
	private static function xmlToJson($xml) { 
		if(is_file($xml)){ 
			$xml_array=simplexml_load_file($xml); 
		}else{ 
			$xml_array=simplexml_load_string($xml); 
		} 
		$json = json_encode($xml_array); 
		return $json; 
	}
	
}
