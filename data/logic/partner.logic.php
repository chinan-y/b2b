<?php
/**
 * 三方合作伙伴行为行为	by Wen
 *
 */
defined('GcWebShop') or exit('Access Invalid!');
class partnerLogic {
	
	/**
     * 当前三方合作伙伴数据
     * @var array
     */
    private $_partner_info = array();

    /**
     * 三方合作伙伴行数据
     * @var array
     */
    private $_partner_data = array();

    /**
     * xml数据
     * @var array
     */
    public $_xml_data = array();
	
	
    /**
     * xml数据
     * @var array
     */
    public $_result_type = 'xml';
	
    /**
     * xml数据
     * @var array
     */
    public $_result = array(
		'code' => '200',
		'data' => array(
			'status' => '1',
			'description' => '请求成功',
		),
	);

    public function __construct() {
		//验证参数
		$model_partner = Model('partner');
        $this->_partner_data = $model_partner->getPartners();
    }

    /**
     * 三方请求sign检查
     * @param string $sign
     * @param unknown $head
     * @return boolean
     */
    public function checkSign() {
        return true;
    }
	
	/**
     * 验证请求合法性
     * @return boolean
     */
	public function validateRequset() {
		if(!$this->_xml_data->head->APPID){
			$this->_result = array(
				'code' => '500',
				'data' => array(
					'status' => '0',
					'description' => 'APPID为空',
				),
			);
			return false;
		}
		
		if(!$this->_xml_data->head->sign){
			$this->_result = array(
				'code' => '500',
				'data' => array(
					'status' => '0',
					'description' => 'APPID为空',
				),
			);
			return false;
		}
		
		//初始化partner_info
		$this->_partner_info = $this->_partner_data[''.$this->_xml_data->head->APPID.''];
		if(!$this->_partner_info){
			$this->_result = array(
				'code' => '500',
				'data' => array(
					'status' => '0',
					'description' => '当前三方合作伙伴数据不存在',
				),
			);
			return false;
		}
		
		if(!$this->checkSign()){
			$this->_result = array(
				'code' => '500',
				'data' => array(
					'status' => '0',
					'description' => '数据校验错误',
				),
			);
			return false;
		}
		
		return true;
    }
	
	/**
     * 三方请求订单处理
     */
    public function order(){
		$out_trade_no .= $this->_xml_data->body->other->trade_no;
		if(!$out_trade_no){
			throw new Exception('外部订单号为空。');
		}
		$model_order = Model('order');
		$order_exist = $model_order->getOrderInfo(array('out_trade_no'=>$out_trade_no));
		if($order_exist){
			//屏蔽敏感信息
			unset($order_exist['order_distinguish']);
			unset($order_exist['buy_encrypt']);
			
			throw new Exception('当前订单已经存在，订单信息如下：'.$this->arrToXml($order_exist));
		}
		
		//初始化用户信息
		$auth_type .= $this->_xml_data->head->auth_type;
		$auth_value .= $this->_xml_data->head->auth_value;
		$APPID .= $this->_xml_data->head->APPID;
		
		$model_member = Model('member');
		$condition = array();
		if($auth_type == 'mobile'){
			$condition['member_mobile'] = $auth_value;
		}elseif($auth_type == 'email'){
			$condition['member_email'] = $auth_value;
		}elseif($auth_type == 'username'){
			$condition['member_name'] = $auth_value;
		}else{
			throw new Exception('参数注册类型为空。');
		}
		
		$member_info = $model_member->getMemberInfo($condition);
		if(!empty($member_info) && $member_info['saleplat_id'] != $APPID){
			throw new Exception('当前用户在系统中存在，但是当前用户不是平台用户，请用户自行登录后，执行购买操作。');
		}
		
		$is_register = true;	//标记当前用户是否新注册用户
		if($member_info){
			$is_register = false;
			if(!isset($member_info['error'])) {
				$model_member->createSession($member_info,true);
			}else{
				throw new Exception('用户操作异常'.$member_info['error'].'。');
			}
		}else{
			$register_info = array();
			$register_rand = time().rand(10,99);
			if($auth_type == 'mobile'){
				$register_info['username'] = 'gc'.$register_rand;
				$register_info['email'] = $auth_value.'@qqbsmall.com';
				$register_info['mobile'] = $auth_value;
			}elseif($auth_type == 'email'){
				$register_info['username'] = 'gc'.$register_rand;
				$register_info['email'] = $auth_value;
				$register_info['mobile'] = '';
			}elseif($auth_type == 'username'){
				$register_info['username'] = $auth_value;
				$register_info['email'] = $auth_value.'@qqbsmall.com';
				$register_info['mobile'] = '';
			}
			$register_info['password'] = '123456';
			$register_info['ref_url'] = '';
			$register_info['saleplat_id'] = $APPID;
			$register_info['is_membername_modify'] = '1';
			$register_info['refer_id'] = '';
			$register_info['inviter_id'] = '';
			
			$member_info = $model_member->register($register_info);
			if(!isset($member_info['error'])) {
				$model_member->createSession($member_info,true);
			}else{
				throw new Exception('用户操作异常，'.$member_info['error'].'。');
			}

		}
		
		//处理收货地址
		$address_class = Model('address');
		$obj_validate = new Validate();
		
		$address_id .= $this->_xml_data->body->reciver->name;
		$address_id = intval($address_id);
		if($address_id > 0){
			$address_info = $address_class->getDefaultAddressInfo(array('address_id'=>$address_id));
		}
		if(!empty($address_info) && $address_info['member_id'] == $_SESSION['member_id']){
			
		}else{
			
			$true_name .= $this->_xml_data->body->reciver->name;
			$true_idnum .= $this->_xml_data->body->reciver->idnum;
			$mob_phone .= $this->_xml_data->body->reciver->mobile;
			$city_id .= $this->_xml_data->body->reciver->city_id;
			$area_id .= $this->_xml_data->body->reciver->area_id;
			$area_info .= $this->_xml_data->body->reciver->area_info;
			$address .= $this->_xml_data->body->reciver->address;
			
			$tel_phone = $mob_phone;
			$is_default = '';
			$city_id = $area_id = $APPID;	//后期注释此行强制验证
		
			$obj_validate->validateparam = array(
				array("input"=>$true_name,"require"=>"true","message"=>$lang['member_address_receiver_null']),
				array("input"=>$true_idnum,"require"=>"true","message"=>$lang['member_address_true_idnum_null']),
				array("input"=>$area_id,"require"=>"true","validator"=>"Number","message"=>$lang['member_address_wrong_area']),
				array("input"=>$city_id,"require"=>"true","validator"=>"Number","message"=>$lang['member_address_wrong_area']),
				array("input"=>$area_info,"require"=>"true","message"=>$lang['member_address_area_null']),
				array("input"=>$address,"require"=>"true","message"=>$lang['member_address_address_null']),
				array("input"=>$tel_phone.$mob_phone,'require'=>'true','message'=>$lang['member_address_phone_and_mobile'])
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				
				throw new Exception('收货地址异常，'.$error.'。');
			}
			$true_name = str_replace(' ','',$true_name);
			$true_name = trim($true_name);
			$data = array();
			$data['member_id'] = $_SESSION['member_id'];
			$data['true_name'] = $true_name;
			$data['true_idnum'] = $true_idnum;
			$data['area_id'] = intval($area_id);
			$data['city_id'] = intval($city_id);
			$data['area_info'] = $area_info;
			$data['address'] = $address;
			$data['tel_phone'] = $tel_phone;
			$data['mob_phone'] = $mob_phone;
			$data['is_default'] = $is_default ? 1 : 0;
			if ($is_default) {
				$address_class->editAddress(array('is_default'=>0),array('member_id'=>$_SESSION['member_id'],'is_default'=>1));
			}
			
			$address_info = $address_class->getDefaultAddressInfo($data);
			$address_id = $address_info['address_id'];
			
			if (intval($address_id) > 0){
				$rs = $address_class->editAddress($data, array('address_id' => $address_id));
				if (!$rs){
					throw new Exception($lang['member_address_modify_fail']);
				}
			}else {
				$count = $address_class->getAddressCount(array('member_id'=>$_SESSION['member_id']));
				if ($count >= 20) {
					throw new Exception('最多允许添加20个有效地址。');
				}
				$rs = $address_class->addAddress($data);
				if (!$rs){
					showDialog($lang['member_address_add_fail'],'','error');
					throw new Exception($lang['member_address_add_fail'].'。');
				}
				$address_info = $address_class->getDefaultAddressInfo($data);
				$address_id = $address_info['address_id'];
			}
		}
		
		if(!$address_id){
			throw new Exception('添加收货地址异常。');
		}
		
		//订单处理
/* gct=buy
address_id=2410
allow_offpay=0
allow_offpay_batch=2:0
buy_city_id=14
buy_encrypt=
cart_id[]=4319|1
ifcart=
invoice_id=
offpay_hash=DbHrX9Vz5BXqAT80Kjdsm8lMgwSvj6achjteLjK
offpay_hash_batch=jYTONIYKeYxLiY8-ctbTHOqN-30ECV6nliqt6Q4WLhGenXF
gp=buy_step2
pay_message[2]=
pay_name=online
ref_url=http://www.qqbsmall.com/gcshop/goods-index-goods_id-4319-ref-146534.html
vat_hash=v3gyRaUZDp3-Jyig5iC6X74Rlbnjy2piwYe */
		$_xml_goods = $this->_xml_data->body->goods->detail;
		$transports.= $this->_xml_data->body->other->transports;
		$pay.= $this->_xml_data->body->other->pay;
		$pay_sn.= $this->_xml_data->body->other->pay_sn;
		$model_goods = Model('goods');
		$cart_id = array();
		$goods_info_encrypt = array();
		$store_ids = array();
		$encrypt = array();
		$cart_ids = array();
		foreach($_xml_goods as $goods){
			$goods_info = $model_goods->getGoodsInfo(array('goods_serial'=>''.$goods->serial), 'goods_id,goods_serial,store_id');
			if($goods_info['goods_id']){
				if($goods_info['store_id']==7){
				    $cart_id[] = $goods_info['goods_id'].'|'.$goods->num;
					$goods_info_encrypt[$goods_info['goods_id']] = array(	//格式化goods_info生成buy_encrypt
					'i'=>$goods_info['goods_id'],
					'store_id'=>$goods_info['store_id'],
					'n'=>''.$goods->num,
					'pr'=>''.$goods->price,
					's'=>''.$goods->serial,
				);
				$allow_offpay_batch[$goods_info['store_id']] = 0;
				}
			  elseif($goods_info['store_id']==2){
				    $cart_ids[] = $goods_info['goods_id'].'|'.$goods->num;
					$info_encrypt[$goods_info['goods_id']] = array(	//格式化goods_info生成buy_encrypt
					'i'=>$goods_info['goods_id'],
					'store_id'=>$goods_info['store_id'],
					'n'=>''.$goods->num,
					'pr'=>''.$goods->price,
					's'=>''.$goods->serial,
				);
				$allow_offpay_batch[$goods_info['store_id']] = 0;
				}elseif($goods_info['store_id']==6){
				    $ids[] = $goods_info['goods_id'].'|'.$goods->num;
					$encrypt[$goods_info['goods_id']] = array(	//格式化goods_info生成encrypt
					'i'=>$goods_info['goods_id'],
					'store_id'=>$goods_info['store_id'],
					'n'=>''.$goods->num,
					'pr'=>''.$goods->price,
					's'=>''.$goods->serial,
				);
				$allow_offpay_batch[$goods_info['store_id']] = 0;
				}
			}else{
				throw new Exception('商品货号'.$goods->serial.'无效。');
			}
		}
		if(count($cart_id)!=0){
		//加密三方订单订单信息(7店铺)
		$buy_encrypt = array(
			'g'=>$goods_info_encrypt,
			't'=>$out_trade_no,
			'a'=>$APPID,
			'tr'=>$transports,
			'p'=>$pay,
			'ps'=>$pay_sn,
		);
		$_POST = array(
			'gct' => 'buy',
			'address_id' => $address_id,
			'allow_offpay' => 0,
			'allow_offpay_batch' => '2:0',
			'buy_city_id' => $address_info['city_id'],
			'buy_encrypt' => '',
			'cart_id' => $cart_id,
			'ifcart' => '',
			'invoice_id' => '',
			'offpay_hash' => '',
			'offpay_hash_batch' => '',
			'gp' => 'buy_step2',
			'pay_message[2]' => '',
			'pay_name' => 'online',
			'ref_url' => '',
			'vat_hash' => '',
		);
		//PHP验证使用
        $_POST['offpay_hash'] = $this->partnerEncrypt('deny_offpay', $_SESSION['member_id']);
        $_POST['offpay_hash_batch'] = $this->partnerEncrypt($allow_offpay_batch, $_SESSION['member_id']);
		$_POST['vat_hash'] = $this->partnerEncrypt('deny_vat', $_SESSION['member_id']);	//增值税使用默认（不使用）
		$logic_buy = logic('buy');
        $result = $logic_buy->buyStep2($_POST, $_SESSION['member_id'], $_SESSION['member_name'], $_SESSION['member_email'], $buy_encrypt);
        if(!$result['state']) {
			throw new Exception($result['msg']);
        }
		$order_list = $result['data']['order_list'];
		$order_sn = '';
		foreach($order_list as $k=>$v){
			$order_sn .= $v['order_sn'];
		}
		}
		if(count($cart_ids)!=0){
	  //加密三方订单订单信息（2店铺）
		$buy = array(
			'g'=>$info_encrypt,
			't'=>$out_trade_no,
			'a'=>$APPID,
			'tr'=>$transports,
			'p'=>$pay,
			'ps'=>$pay_sn,
		);
		$_POST = array(
			'gct' => 'buy',
			'address_id' => $address_id,
			'allow_offpay' => 0,
			'allow_offpay_batch' => '2:0',
			'buy_city_id' => $address_info['city_id'],
			'buy_encrypt' => '',
			'cart_id' => $cart_ids,
			'ifcart' => '',
			'invoice_id' => '',
			'offpay_hash' => '',
			'offpay_hash_batch' => '',
			'gp' => 'buy_step2',
			'pay_message[2]' => '',
			'pay_name' => 'online',
			'ref_url' => '',
			'vat_hash' => '',
		);
		//PHP验证使用
        $_POST['offpay_hash'] = $this->partnerEncrypt('deny_offpay', $_SESSION['member_id']);
        $_POST['offpay_hash_batch'] = $this->partnerEncrypt($allow_offpay_batch, $_SESSION['member_id']);
		$_POST['vat_hash'] = $this->partnerEncrypt('deny_vat', $_SESSION['member_id']);	//增值税使用默认（不使用）
		$logic_buy = logic('buy');
        $result = $logic_buy->buyStep2($_POST, $_SESSION['member_id'], $_SESSION['member_name'], $_SESSION['member_email'], $buy);
        if(!$result['state']) {
			throw new Exception($result['msg']);
        }
		$order_list = $result['data']['order_list'];
		$order_sn = '';
		foreach($order_list as $k=>$v){
			$order_sn .= $v['order_sn'];
		}
		}
		
        //转向到商城支付页面
		$this->_result['data']['description'] = '订单处理成功。';
		$this->_result['data']['other'] = array(
			'out_auth_type' => $auth_type,
			'out_auth_value' => $auth_value,
			'out_member_id' => $_SESSION['member_id'],
			'out_address_id' => $address_id,
			'out_trade_no' => $order_sn,
			'trade_no' => $out_trade_no,
		);
	}
    /**
     * 加密
     * @param array/string $string
     * @param int $member_id
     * @return mixed arrray/string
     */
    public function partnerEncrypt($string, $member_id) {
        $partner_key = sha1(md5($member_id.'&'.MD5_KEY));
        if (is_array($string)) {
            $string = serialize($string);
        } else {
            $string = strval($string);
        }
        return encrypt(base64_encode($string), $partner_key);
    }

    /**
     * 解密
     * @param string $string
     * @param int $member_id
     * @param number $ttl
     */
    public function partnerDecrypt($string, $member_id, $ttl = 0) {
        $partner_key = sha1(md5($member_id.'&'.MD5_KEY));
        if (empty($string)) return;
        $string = base64_decode(decrypt(strval($string), $partner_key, $ttl));
        return ($tmp = @unserialize($string)) !== false ? $tmp : $string;
    }

    /**
     * 将订单数据存入内存表
     * @param array $data
     * return boolean
     */
    private function _pushCallback($order) {
        $model_pay_callback = Model('pay_callback');
        foreach($order as $k=>$v){
            $data = array();
            $data['times'] = 0;
            $data['next_time'] = TIMESTAMP;
            $data['param'] = serialize(array(
               'o'=>$v['order_sn'],
               't'=>$v['out_trade_no'],
               'p'=>$v['parter_id'],
            ));
            $model_pay_callback->insert($data);
        }
        return true;
    }
	
	
	/**
	 * 输出信息
	 *
	 * @param array $type 返回格式 [xml|json]
	 * return string 字符串XML类型的返回结果
	 */
	function showMessage($type = 'xml'){
		header("Content-type:text/html;charset=utf-8");
		if($type == 'xml'){
			if($this->_result['data']['other']){
				$this->_result['data']['other'] = $this->arrToXml($this->_result['data']['other']);
			}			
			$result_string = '<?xml version="1.0" encoding="utf-8"?><gc><code>%s</code><data><status>%s</status><description>%s</description>%s</data></gc>';
			echo sprintf($result_string, $this->_result['code'], $this->_result['data']['status'], $this->_result['data']['description'], $this->_result['data']['other']);
		}else{
			echo json_encode($this->_result);
		}
		die;
	}
	
	/**
	 * Array To XML
	 *
	 * @param array $arr 数组
	 * @param SimpleXMLElement $dom xml对象
	 * @param int $item SimpleXMLElement深度
	 * return string 字符串XML类型的返回结果
	 */
	function arrToXml($arr,$dom=0,$item=0){
		if (!$dom){
			$dom = new DOMDocument("1.0");
		}
		if(!$item){
			$item = $dom->createElement("root"); 
			$dom->appendChild($item);
		}
		foreach ($arr as $key=>$val){
			$itemx = $dom->createElement(is_string($key)?$key:"item");
			$item->appendChild($itemx);
			if (!is_array($val)){
				$text = $dom->createTextNode($val);
				$itemx->appendChild($text);
				
			}else {
				arrtoxml($val,$dom,$itemx);
			}
		}
		return $dom->saveXML();
	}
}
