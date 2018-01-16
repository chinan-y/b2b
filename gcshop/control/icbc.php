<?php

/**
 * 工商银行系统抓取订单
 *
 */
defined('GcWebShop') or exit('Access Invalid!');

class icbcControl extends BaseApiControl{
	
	public function indexOp(){
		
	
	$req_data='<?xml version="1.0" encoding="UTF-8"?><body><create_start_time></create_start_time><create_end_time></create_end_time><modify_time_from>2017-07-31 10:00:00</modify_time_from><modify_time_to>2013-08-01 10:00:00</modify_time_to><order_status>01</order_status></body>';

	$content = "app_key=gsDLw4eJ&auth_code=wugmZmk4gD8hojnWe9AIaWKEmaZklew1sTmhGBpuWZqy2E7rmlco3s7bZDZLW5Qu&req_data=".$req_data;
	$signMsg = base64_encode(hash("sha256", $content));
	
	
	/*https://ops.mall.icbc.com.cn/icbcrouter?*/
	$icbc_url = "https://ops.mall.icbc.com.cn/icbcrouter?sign=".$signMsg."&timestamp=".date('Y-m-d H:i:s', time())."&version=1.0&app_key=gsDLw4eJ&method=icbcb2c.order.list&format=xml&req_sid=20171513219900&auth_code=wugmZmk4gD8hojnWe9AIaWKEmaZklew1sTmhGBpuWZqy2E7rmlco3s7bZDZLW5Qu&";
	var_dump($icbc_url); 
	// var_dump($req_data); 
	var_dump($signMsg); 
	die;
	
	
	
	/*https://ops.mall.icbc.com.cn/icbcrouter?sign=MjFhMmNjY2I3NDk1NWMyYzBhNTNiMjZjMmFiZTk2YjA4NjE1OGEzMTQ4OTA5MTFjYjI4ZDkzZGJjNGQ5MGI4ZA==&timestamp=2017-12-14+13%3A52%3A03&version=1.0&app_key=gsDLw4eJ&method=icbcb2c.order.list&format=xml&req_sid=20171513219900&auth_code=wugmZmk4gD8hojnWe9AIaWKEmaZklew1sTmhGBpuWZqy2E7rmlco3s7bZDZLW5Qu&req_data=<?xml version="1.0" encoding="UTF-8"?><body><create_start_time></create_start_time><create_end_time></create_end_time><modify_time_from>2017-07-31 10:00:00</modify_time_from><modify_time_to>2013-08-01 10:00:00</modify_time_to><order_status>01</order_status></body>*/
	
	/*$tongXML = '<body>';
	$tongXML .= $xml;
	$tongXML .= "<signMsg>".$signMsg."</signMsg>";
	$tongXML .= '</body>';
	$data_list  = 'verificationText='.urlencode(base64_encode($tongXML));
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $icbc_url.'?verificationText='.$data_list);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:text/xml; charset=utf-8'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_list);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$result = curl_exec($ch);
	curl_close($ch);
	
	file_put_contents('D:/log/tonglian/'.$orderNo.'.xml', base64_decode($result));
	$doc = new DOMDocument();
	$doc->loadXML(base64_decode($result));
	$re = array();
	$re['retCode'] = $doc->getElementsByTagName('retCode')->item(0)->nodeValue;
	$re['retMsg'] = $doc->getElementsByTagName('retMsg')->item(0)->nodeValue;
	
	return $re;*/
		
    }
}
