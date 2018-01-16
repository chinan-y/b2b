<?php
/**
 * 三方订单录入
 *
 *
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');

class thirdfaceControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		// Language::read('adv');
	}
	public function indexOp(){
		TPL::showpage('thirdface');
	}
	public function joinOp(){
		
		$data=array();
		$data['head']['APPID']=$_POST['APPID'];
		$data['head']['action']="order";
		$data['head']['payMethod']="0000";
		
		for($i=0;$i<999;$i++){
			if(isset($_POST['serial']) && isset($_POST['price']) && isset($_POST['num'])){
				$data['body']['goods']['detail'][$i]['serial']=$_POST['serial'];//货号
				$data['body']['goods']['detail'][$i]['price']=$_POST['price'];//商品价格
				$data['body']['goods']['detail'][$i]['num']=$_POST['num'];//商品数量
			}
			if($i>0 && isset($_POST['serial'.$i]) && isset($_POST['price'.$i]) && isset($_POST['num'.$i]) ){
				$data['body']['goods']['detail'][$i]['serial']=$_POST['serial'.$i];//货号
				$data['body']['goods']['detail'][$i]['price']=$_POST['price'.$i];//商品价格
				$data['body']['goods']['detail'][$i]['num']=$_POST['num'.$i];//商品数量
				
			}else{
				break;
			}
		}
		
		$data['body']['reciver']['name']=$_POST['name'];
		$data['body']['reciver']['idnum']=$_POST['idnum'];
		$data['body']['reciver']['mobile']=$_POST['mobile'];
		$data['body']['reciver']['province_id']=$_POST['province'];
		$data['body']['reciver']['city_id']=$_POST['city'];
		$data['body']['reciver']['area_id']=$_POST['area'];
		$data['body']['reciver']['area_info']=$_POST['area-info'];
		$data['body']['reciver']['address']=$_POST['address'];
		$data['body']['other']['message']=$_POST['message'];
		$data['body']['other']['out_trade_no']=$_POST['out_trade_no'];
		
		$emptyArray=$this->emptyArray();
		if(!empty($data)){
			self::checkEmptyXml($data,$emptyArray);
			$result=$this->order($data);
		}else{
			showMessage('数据为空！', 'index.php?gct=thirdface', 'html', 'error');
			
		}
		// TPL::output('order',$result);
		// TPL::showpage('thirdface');
		
		
		header("Content-type: text/html; charset=utf-8");echo '<pre>';print_r($result);exit;
		
	}
	public function excelOp(){
		// require_once BASE_PATH.'/phpexcel/excel_class.php';
		// import('phpexcel.classes.PHPExcel');
		include(BASE_PATH.'/excel/reader.php');
		$tmp = $_FILES['file']['tmp_name'];
		$xls = new Spreadsheet_Excel_Reader();
		$xls->setOutputEncoding('utf-8');
		$file_name = $save_path.date('Ymd') . ".xls";
		if (copy($tmp, $file_name)) {
			// $PHPReader = new PHPExcel_Reader_Excel2007(); 
			// Read_Excel_File($file_name,$return);
			$result=array();
			$xls = new Spreadsheet_Excel_Reader();
			$xls->setOutputEncoding('utf-8');
			$xls->read($file_name);
			$code=0;
			$num=0;
			$data=array();
			foreach($xls->sheets[0]['cells'] as $key=>$value){
			if($key>=2){
				if(isset($xls->sheets[0]['cells'][$key][1]) && $xls->sheets[0]['cells'][$key][1]!=""){
					$partner_id=$xls->sheets[0]['cells'][$key][1];
					unset($data['body']['goods']);
					unset($data['head']);
					unset($data['body']['other']['out_trade_no']);
					$code++;
				}
				foreach($value as $k=>$v){
					if(isset($xls->sheets[0]['cells'][$key][1]) && $xls->sheets[0]['cells'][$key][1]!=""){
						$data['head']['action']="order";
						$data['head']['payMethod']="0000";
					switch($k){
						case 1:
						if(!isset($data['head']['APPID']))
						$data['head']['APPID']=$v;
						break;
						case 2:
						$data['body']['goods']['detail'][$num]['serial']=$v;//货号
						break;
						case 3:
						$data['body']['goods']['detail'][$num]['price']=$v;//商品价格
						break;
						case 4;
						$data['body']['goods']['detail'][$num]['num']=$v;//商品数量
						break;
						case 5:
						$data['body']['reciver']['name']=$v;
						break;
						case 6:
						$data['body']['reciver']['idnum']=$v;
						break;
						case 7:
						$data['body']['reciver']['mobile']=$v;
						break;
						case 8:
						$data['body']['reciver']['province_id']=$v;
						break;
						case 9:
						$data['body']['reciver']['city_id']=$v;
						break;
						case 10:
						$data['body']['reciver']['area_id']=$v;
						break;
						case 11:
						$data['body']['reciver']['area_info']=$v;
						break;
						case 12:
						$data['body']['reciver']['address']=$v;
						break;
						case 13:
						$data['body']['other']['message']=$v;
						break;
						case 14:
						$data['body']['other']['out_trade_no']=$v;
						break;
						
					}	
					}else{
						switch($k){
							case 2:
							$data['body']['goods']['detail'][$num]['serial']=$v;//货号
							break;
							case 3:
							$data['body']['goods']['detail'][$num]['price']=$v;//商品价格
							break;
							case 4;
							$data['body']['goods']['detail'][$num]['num']=$v;//商品数量
							break;
						}
					}
					
				}
				$num++;
				$result[$code]=$data;
			}
			}
			$emptyArray=$this->emptyArray();
			$ouput=array();
			foreach($result as $value){
				if(!empty($value)){
					self::checkEmptyXml($value,$emptyArray);
					$result=$this->order($value);
					$output[]=$result;
				}else{
					showMessage('EXCEL数据为空！', 'index.php?gct=thirdface', 'html', 'error');
					
				}
				
				
			}
			
			$partnerOrder=Model()->table('order')->where(array('partner_id'=>$partner_id,'order_state'=>20))->select();
			
			TPL::output('result',$partnerOrder);
			TPL::showpage('thirdface');
			// header("Content-type: text/html; charset=utf-8");echo '<pre>';print_r($xls->sheets[0]);exit;
			// header("Content-type: text/html; charset=utf-8");echo '<pre>';print_r($partnerOrder);exit;
			// for ($i=2; $i<=$xls->sheets[0]['numRows']; $i++) {
		}
	}
	private function order($xmlArray){
		//判断该外部订单是否存在，存在直接返回
		$order=Model()->table('order')->where(array('out_trade_no'=>$xmlArray['body']['other']['out_trade_no']))->find();
		if($order){
			return true;
		}
		
		$mobile=$xmlArray['body']['reciver']['mobile'];
		$patner_id=$xmlArray['head']['APPID'];
		$member_info=$this->getUserInfoForMobile($mobile,$patner_id);
		
		$goods=$xmlArray['body']['goods']['detail'];
		
		// $goods=$this->tax($goods);
		
		$cartArray=$this->addcart($goods);
		
		
		
		$addressArray=$xmlArray['body']['reciver'];
		// echo json_encode($addressArray);die;
		$addressId=$this->generateAddress($addressArray,$_SESSION['member_id']);
		 
		foreach($goods as $key=>$value){
			$goods_info = Model('goods')->getGoodsInfo(array('goods_serial'=>''.$value['serial']), 'goods_id,goods_serial,store_id');
			if($goods_info['goods_id']){
				
				$allow_offpay_batch[$goods_info['store_id']] = 0;
				
			}elseif($goods_info['store_id']==2){
				  
				$allow_offpay_batch[$goods_info['store_id']] = 0;
			}elseif($goods_info['store_id']==6){
				   
				$allow_offpay_batch[$goods_info['store_id']] = 0;
				
			}else{
				showMessage('商品货号'.$goods['serial'].'无效。', 'index.php?gct=thirdface&gp=thirdface', 'html', 'error');
			}
		}	
		// die;
		$_POST=array();
		$buy_encrypt=array();
		$_POST['cart_id']=$cartArray;
		$_POST['ifcart'] = 1;
		$_POST['pay_name'] = "online";
		$_POST['vat_hash'] =  $this->partnerEncrypt('deny_vat', $_SESSION['member_id']);
		$_POST['address_id'] = $addressId;
		$_POST['buy_city_id'] = $xmlArray['body']['reciver']['city_id'];
		$_POST['allow_offpay'] = 1;
		$_POST['allow_offpay_batch'] = "2:0";
		$_POST['offpay_hash'] = $this->partnerEncrypt('deny_offpay', $_SESSION['member_id']);
		$_POST['offpay_hash_batch'] =  $this->partnerEncrypt($allow_offpay_batch, $_SESSION['member_id']);
		$_POST['third']=1;
		$_POST['invoice_id'] ="" ;
		$_POST['ref_url'] = "http://www.qqbsmall.com/gcshop/index.php?gct=cart";
		$logic_buy = logic('buy');
		$result = $logic_buy->buyStep2($_POST, $_SESSION['member_id'], $_SESSION['member_name'], $_SESSION['member_email'], $buy_encrypt);
		foreach($result['data']['order_list'] as $key=>$value){
			Model()->table('order')->where(array('order_id'=>$key))->update(array('partner_id'=>$xmlArray['head']['APPID'],'out_trade_no'=>$xmlArray['body']['other']['out_trade_no']));
		}
		if($result){
			
			return $result;
		}else{
			return false;
		}
	}
	private function generateAddress($addressArray,$member_id){
			$address_class=Model('address');
			$is_default='';
			$condition=array();
			$condition['member_id']=$member_id;
			$condition['true_name']=trim($addressArray['name']);
			$condition['true_idnum']=trim($addressArray['idnum']);
			$condition['mob_phone']=trim($addressArray['mobile']);
			$addressInfo=Model()->table('address')->where($condition)->find();
			
			$data = array();
			$data['true_name'] = trim($addressArray['name']);
			$data['order_name'] = "";
			$data['true_idnum'] = trim($addressArray['idnum']);
			$data['area_id'] = intval($addressArray['area_id']);
			$data['city_id'] = intval($addressArray['city_id']);
			$data['area_info'] = $addressArray['area_info'];
			$data['address'] = $addressArray['address'];
			$data['tel_phone'] = $addressArray['mobile'];
			$data['mob_phone'] = $addressArray['mobile'];
			$data['is_default'] = $is_default ? 1 : 0;
			//
			if($addressInfo){
				$editCondition=array();
				$editCondition['member_id']=$member_id;
				$editCondition['address_id']=$addressInfo['address_id'];
				$result=$address_class->editAddress($data,$editCondition);
				// echo json_encode($result);die;
				return $addressInfo['address_id'];
			}else{
				$data['member_id']=$member_id;
				
				$result=$address_class->addAddress($data);
				// echo json_encode($result);die;
				return $result;
			}
			
	}
	private function getUserInfoForMobile($mobile,$patner_id){
		if(!isset($mobile)){
			showMessage('订购人手机不能为空', 'index.php?gct=thirdface', 'html', 'error');
			
		}
		$member_info=Model('member')->getMemberInfo(array('member_mobile'=>$mobile));
		if(!$member_info){
			$register_info = array();
			$register_info['username'] = 'gc'.$mobile ;
			$register_info['password'] = 'qqbsmall';
			$register_info['is_membername_modify'] = 0;
			$register_info['mobile'] = $mobile;
			$register_info['verify_code'] = $mobile;
			$register_info['member_mobile_bind'] = 1;
			$register_info['saleplat_id'] = $patner_id;
			
			$member_info=Model('member')->registerm($register_info);
		}
		if($member_info){
			//创建回话session,保证订单生成
			Model('member')->createSession($member_info);
			return $member_info;
		}
		return false;
		
	}
	private function addcart($goods){
		$cartModel=Model('cart');
		$goods_info=array();
		$cartArray=array();
		foreach($goods as $key=>$value){
			$goodsInfo=Model('goods')->getGoodsStoreList(array('goods_serial'=>$value['serial']));
			if(empty($goodsInfo)){
				showMessage('购买的商品无效', 'index.php?gct=thirdface', 'html', 'error');
				
			}
			// self::showMessage('Error',$goodsInfo[0]['goods_id']);
			$goodsNum                  =$value['num'];
			$goods_info['buyer_id']    =$_SESSION['member_id'];
			$goods_info['store_id']    =$goodsInfo[0]['store_id'];
			$goods_info['store_from']  =$goodsInfo[0]['store_from'];
			$goods_info['goods_id']    =$goodsInfo[0]['goods_id'];
			$goods_info['goods_name']  =$goodsInfo[0]['goods_name'];
			// if($value['istax']){
				// if(in_array($goodsInfo[0]['goods_hscode'],$this->returnArray())){
					
					// $goods_info['goods_price']=	
				// }else{
					// $goods_info['goods_price'] =sprintf("%.2f",$value['price']/1.119);
				// }
			// }else{
				$goods_info['goods_price'] =$value['price'];
			// }
			$goods_info['goods_weight']=$goodsInfo[0]['goods_weight'];
			$goods_info['goods_taxes'] =$goodsInfo[0]['goods_taxes'];
			$goods_info['goods_image'] =$goodsInfo[0]['goods_image'];
			$goods_info['store_name']  =$goodsInfo[0]['store_name'];
			$goods_info['bl_id'] = isset($goodsInfo['bl_id']) ? $goodsInfo['bl_id'] : 0;
			// echo json_encode($goodsInfo);die;
			$result=$cartModel->checkCart(array('goods_id'=>$goodsInfo[0]['goods_id'],'buyer_id'=>$_SESSION['member_id']));
			$cartId=$result['cart_id'];
			if(!$cartId){
				$cartId=$cartModel->addCart($goods_info,'db',$goodsNum);
			}
			$cartArray[$key]=$cartId.'|'.$goodsNum;
		}
		return $cartArray;
		
	}
	private static function checkEmptyXml($array,$emptyArray){
		
		// $checkArray=$emptyArray[$array['head']['action']];
		
		// echo json_encode($checkArray);die;
		foreach($array as $k=>$v){
			if(is_array($array[$k]) && !empty($array[$k])){
				self::checkEmptyXml($array[$k],$emptyArray);
			}else{
				if(empty($array[$k]) && !in_array($k,$emptyArray)){
					showMessage('数据错误,'.$k.'不能为空', 'index.php?gct=thirdface', 'html', 'error');
					
				}
			}
		}
	}
	private function partnerEncrypt($string, $member_id) {
        $partner_key = sha1(md5($member_id.'&'.MD5_KEY));
        if (is_array($string)) {
            $string = serialize($string);
        } else {
            $string = strval($string);
        }
        return encrypt(base64_encode($string), $partner_key);
    }
	private function emptyArray(){
		$array=array();
		$array['order']=array();
		
		
		
		return $array;
	}
	 
}


