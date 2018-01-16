<?php
/**
  * 海关统一版
  *
  **/
 
defined('GcWebShop') or exit('Access Invalid!');

class customsControl extends BaseApiControl{


	// 仓储出库单
	public function godownOp(){
		
		//获取计量单位
		$model_unit = Model('legal_unit');
		$unit = $model_unit->getLegalUnit();
		$model_order = Model('order');
		$odo = new Customs();
		$sms = new Sms();
		$data = array();
		$data_h = array();
		$orders = DB::getAll("select ORDER_SN, ERP_MEMO, SKU, GOODS_ID, GOODS_NUM, GOODS_PRICE, GOODS_TAX, ORDER_AMOUNT, shipping_code, list_invtNo, GOODS_UNITS, RECEIVER_ID_NO from 33hao_mess_order_info where MAKE_CSV = '10' and list_invtNo<>'' and list_status = '800' order by ORDER_SN asc"); 
		if(!empty($orders) && is_array($orders)){
		foreach($orders as $k=>$val){
			$order_info = Model('order')->getOrderInfo(array('ORDER_SN'=>$val['ORDER_SN']),array('order_goods','order_common'));
			
			if($order_info['store_id'] == 2){
				preg_match('/([a-zA-Z]*)([0-9]*)(\-?)([0-9]*)/', $val['SKU'],  $match);
				$val['SKU'] = $match[2] ;
			}
			$sql="SELECT 
				d.area_name province,
				d1.area_name city,
				d2.area_name area
				From  33hao_order_common b 
				LEFT JOIN 33hao_area d ON b.reciver_province_id = d.area_id 
				LEFT JOIN 33hao_area d1 ON b.reciver_city_id = d1.area_id 
				LEFT JOIN 33hao_area d2 ON b.reciver_area_id= d2.area_id 
				where b.reciver_province_id='".$order_info['extend_order_common']['reciver_province_id']."' and b.reciver_city_id ='".$order_info['extend_order_common']['reciver_city_id']."' and b.reciver_area_id='".$order_info['extend_order_common']['reciver_area_id']."' and b.order_id='".$order_info['order_id']."'";
				
			$area = DB::getAll($sql);
			
			foreach($unit as $v){
				if($val['GOODS_UNITS'] == $v['CODE']){
					$val['GOODS_UNITS'] = $v['NAME'];
				}
			}
			
			$val['goods_price'] = ($val['GOODS_PRICE'] / $val['GOODS_NUM']);
			foreach($order_info['extend_order_goods'] as $key=>$v){
				if($v['goods_id'] == $val['GOODS_ID']){
					$data[$order_info['order_id']][$order_info['store_id']][$key]['ORDER_SN'] = $val['ORDER_SN'];
					$data[$order_info['order_id']][$order_info['store_id']][$key]['SKU'] = $val['SKU'];
					$data[$order_info['order_id']][$order_info['store_id']][$key]['GOODS_NUM'] = $val['GOODS_NUM'];
					$data[$order_info['order_id']][$order_info['store_id']][$key]['goods_price'] = $val['goods_price'];
					$data[$order_info['order_id']][$order_info['store_id']][$key]['GOODS_PRICE'] = $val['GOODS_PRICE'];
					$data[$order_info['order_id']][$order_info['store_id']][$key]['GOODS_TAX'] = $val['GOODS_TAX'];
					$data[$order_info['order_id']][$order_info['store_id']][$key]['GOODS_UNITS'] = $val['GOODS_UNITS'];
				}
			}
			$data[$order_info['order_id']]['ORDER_SN'] = $order_info['order_sn'];
			$data[$order_info['order_id']]['ERP_MEMO'] = $val['ERP_MEMO'];
			$data[$order_info['order_id']]['RECEIVER_ID_NO'] = $val['RECEIVER_ID_NO'];
			$data[$order_info['order_id']]['RECEIVER_NAME'] = $order_info['extend_order_common']['reciver_name'];
			$data[$order_info['order_id']]['shipping_code'] = $val['shipping_code'];
			$data[$order_info['order_id']]['list_invtNo'] = $val['list_invtNo'];
			$data[$order_info['order_id']]['store_name'] = $order_info['store_name'];
			$data[$order_info['order_id']]['ORDER_AMOUNT'] = $val['ORDER_AMOUNT'];
			$data[$order_info['order_id']]['order_tax'] = $val['GOODS_TAX'];
			$data[$order_info['order_id']]['address'] = $order_info['extend_order_common']['reciver_info']['address'];
			$data[$order_info['order_id']]['province'] = $area[0]['province'];
			$data[$order_info['order_id']]['city'] = $area[0]['city'];
			$data[$order_info['order_id']]['area'] = $area[0]['area'];
			$data[$order_info['order_id']]['street'] = $order_info['extend_order_common']['reciver_info']['street'];
			$data[$order_info['order_id']]['phone'] = $order_info['extend_order_common']['reciver_info']['mob_phone'];
			$data[$order_info['order_id']]['order_id'] = $order_info['order_id'];
			
		}
		}else{
			echo '暂时没有推送的订单！';
		}
		foreach($data as $val){
			foreach($val as $k =>$v){
				if($k == 2){
					$result = $odo->wms_odo($val);
					
					$res_order = urldecode($result);
					file_put_contents('D:/log/wms/'.$val['ORDER_SN'].'.xml', $res_order);
					$doc = new DOMDocument();
					$doc->loadXML($res_order);
					$re = array();
					$re['returnCode'] = $doc->getElementsByTagName('returnCode')->item(0)->nodeValue;
					$re['returnDesc'] = $doc->getElementsByTagName('returnDesc')->item(0)->nodeValue;
					$re['returnFlag'] = $doc->getElementsByTagName('returnFlag')->item(0)->nodeValue;
					
					if($re['returnCode'] == '0000' && $re['returnFlag'] == '1'){
						echo '及时达出库单信息推送成功! 单号：'.$val['ORDER_SN'].'<br/>';
						DB::getAll("update 33hao_mess_order_info set MAKE_CSV='20' where ORDER_SN='".$val['ORDER_SN']."'"); 
						
						$sms->send($val['phone'],'您的订单'.$val['ORDER_SN'].'，包裹已整装待发，成功清关后即可查询物流信息。如有疑问，请致电4000893123，退订回TD');
		
						$data_h['order_id'] = $val['order_id'];
						$data_h['log_time'] = TIMESTAMP ;
						$data_h['log_role'] = 'system';
						$data_h['log_user'] = '系统';
						$data_h['log_msg'] = '推送订单到仓库';
						$data_h['log_orderstate'] = ORDER_STATE_SEND;
						$model_order->addOrderLog($data_h);
						
					}else if($re['returnCode'] == '0001' && $re['returnFlag'] == '0'){
						echo '<p style="color:red;">及时达出库单错误信息：'.$re['returnDesc'].'! 单号：'.$val['ORDER_SN'].'</p>';
					}
					
				}else if($k == 7){
					$result = $odo->erp_odo($val);
					
					$res_order = base64_decode($result);
					file_put_contents('D:/log/erp/'.$val['ORDER_SN'].'.xml', $res_order);
					$doc = new DOMDocument();
					$doc->loadXML($res_order);
					$re = array();
					$re['result'] = $doc->getElementsByTagName('result')->item(0)->nodeValue;
					$re['errmessage'] = $doc->getElementsByTagName('errmessage')->item(0)->nodeValue;
					
					if($re['result'] == '0'){
						echo '玛斯特出库单信息推送成功! 单号：'.$val['ORDER_SN'].'<br/>';
						DB::getAll("update 33hao_mess_order_info set MAKE_CSV='20' where ORDER_SN='".$val['ORDER_SN']."'"); 
						
						$sms->send($val['phone'],'您的订单'.$val['ORDER_SN'].'，包裹已整装待发，成功清关后即可查询物流信息。如有疑问，请致电4000893123，退订回TD');
		
						$data_h['order_id'] = $val['order_id'];
						$data_h['log_time'] = TIMESTAMP ;
						$data_h['log_role'] = 'system';
						$data_h['log_user'] = '系统';
						$data_h['log_msg'] = '推送订单到仓库';
						$data_h['log_orderstate'] = ORDER_STATE_SEND;
						$model_order->addOrderLog($data_h);
						
					}else if($re['result'] == '-1' && $re['errmessage']){
						echo '<p style="color:red;">玛斯特出库单错误信息：'.$re['errmessage'].'! 单号：'.$val['ORDER_SN'].'</p>';
					}
				}
			}
		}
	}
	
	// 撤销申请单表单
	public function re_inputOp(){
		Tpl::showpage('re_input');
	}
	
	// 撤销申请单
	public function repealOp(){
		$data = array();
		$order_info = Model('order')->getOrderInfo(array('order_sn'=>$_POST['order_sn']),array('order_goods','order_common'));
		
		if($order_info){
			$orders = DB::getAll("select list_invtNo from 33hao_mess_order_info where ORDER_SN = '".$_POST['order_sn']."'");
			
			if($order_info['store_id'] == 2){
				$data['customsCode'] = '8012' ;
			}else if($order_info['store_id'] == 7){
				$data['customsCode'] = '8013' ;
			}
			$data['invtNo'] = $orders[0]['list_invtNo'];
			$data['order_sn'] = $_POST['order_sn'];
			$data['orderNo'] = $order_info['pay_sn'];
			$data['logisticsNo'] = $order_info['shipping_code'];
			$data['buyerIdNumber'] = $order_info['extend_order_common']['reciver_idnum'];
			$data['buyerName'] = $order_info['extend_order_common']['order_name'];
			$data['buyerTelephone'] = $order_info['extend_order_common']['reciver_info']['mob_phone'];
			$data['reason'] = $_POST['cause'];
			
			$cus = new Customs();
			$result = $cus->repeal_list($data);
			echo '撤销申请单信息推送成功! 单号：'.$_POST['order_sn'].'<br/>';
		}else{
			echo '订单错误，请查看订单号是否正确';
		}
	}
	
    //退货申请单
	public function order_returnOp(){
		$re = new Customs();
		$r = $re->order_return(123);
    }
	
	//请求回执
	public function request_infoOp(){
		$mes = new Customs();
		$orders = DB::getAll("select ORDER_SN from 33hao_mess_order_info where MAKE_CSV = '10' and list_invtNo<>'' and list_status = '120' and list_info = '[Code:1800;Desc:逻辑校验通过]'");
		$data = array();
		$data['op_date'] = date('Y-m-d' ,time()).'T'.date('H:i:s' ,time());
		$data['senderid'] = '501226053A';
		if($_POST['order_sn_c']){
			$data['order_sn'] = $_POST['order_sn_c'];
			$data['message_type'] = 'CEB622Message';
			$result = $mes->request_receipt($data);
		}else if($_POST['order_sn']){
			$data['order_sn'] = $_POST['order_sn'];
			$data['message_type'] = 'CEB1000Message';
			$result = $mes->request_receipt($data);
		}else{
			foreach($orders as $val){
				$data['order_sn'] = $val['ORDER_SN'];
				$data['message_type'] = 'CEB622Message';
				$result = $mes->request_receipt($data);
			}
		}
		if($result == 'True'){
			echo '请求成功';
		}else{
			echo '请求失败';
		}
    }
	
	//获取EMS运单号
	public function getEmsOp(){
		$order = Logic('order');
		$brand_c_list = Model()->table('brand')->where(array('brand_apply'=>'1'))->select();
		var_dump($brand_c_list);
	}
	
	//重推统一版EMS运单
	public function emsSendOp(){
		$order_sn = $_POST['order_sn'];
		$re_info = Model('order')->getOrderInfo(array('order_sn'=>$order_sn),array('order_goods','order_common'));
		if($re_info['store_id'] == 2){
			$ems_code = '50011201326727';
		}else if($re_info['store_id'] == 7){
			$ems_code = '50010713784000';
		}
		foreach($re_info['extend_order_goods'] as $val){
			$goods_weight += $val['goods_weight'] * $val['goods_num'];
		}
		$weight = $goods_weight * 1.2;
		$re_name = $re_info['extend_order_common']['reciver_name'];
		$re_no = $re_info['extend_order_common']['reciver_idnum'];
		$address = $re_info['extend_order_common']['reciver_info']['address'];
		$phone = $re_info['extend_order_common']['reciver_info']['mob_phone'];
		$ems = Logic('order')->ems_send($ems_code,$order_sn,$re_info['shipping_code'],$re_no,$re_name,$address,$phone,$re_info['shipping_fee'],$weight);
		if($ems['result'] == '0'){
			$sql = "UPDATE 33hao_order SET MESS_STATE = 20 WHERE 33hao_order.order_sn = '".$order_sn."'";
			$result = DB::query($sql);
			echo '推送成功';
		}else{
			echo '推送失败';
		}
	}
	
	//统一版韵达运单
	public function yundaSendOp(){
		$order_sn = $_POST['order_sn'];
		$yundaNO = Logic('order')->getYundaBillno($order_sn);
		var_dump($yundaNO);
	}
	
}
