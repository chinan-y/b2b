<?php

/**
 * 工商银行系统抓取订单
 *
 */
defined('GcWebShop') or exit('Access Invalid!');

class omsControl extends BaseApiControl{
	
	public function indexOp(){
		
	//接口地址
	$url='http://116.228.72.130:8080/oms/interface.php';

	//秘钥
	$appSecret='123456';
	
	//xml数据
$xmlData = "<beans>
	<req_type>create_order</req_type>
	<hawbs>
		<hawb>
			<mail_no></mail_no>
			<hawbno>790568663120244856</hawbno>
			<order_id></order_id>
			<mawb></mawb>
			<piece>1</piece>
			<weight>0.3</weight>
			<freight>0</freight>
			<pre_express></pre_express>
			<next_express></next_express>
			<fcountry>CN</fcountry>  
			<tcountry>CN</tcountry>  
			<infor_origin></infor_origin>
			<receiver>
				<company></company>
				<contacts>张三</contacts>
				<city></city>  
				<postal_code></postal_code>
				<address>重庆重庆市渝北区黄山大道中段64号13栋</address>
				<rec_tele>15923235222</rec_tele>
				<e_mail></e_mail>
			</receiver>
			<sender>
				<company>光彩国际重庆电子商务有限公司</company>
				<city></city> 
				<contacts>光彩全球</contacts>
				<address>重庆市西永保税仓</address>
				<sender_tele>4000893123</sender_tele>
				<postal_code></postal_code>
				<e_mail></e_mail>
			</sender>
			<insurance_fee>0</insurance_fee>
			<goods_money>0</goods_money> 
			<certificate_type>zj01</certificate_type>
			<certificate_id></certificate_id>
			<currency></currency>
			<request></request>
			<remark></remark>
			<vat_service></vat_service> 
			<goods_list>
 				<goods>
					<name>韩国Prettian蜗牛霜 80g/盒 网购保税 蜗牛霜</name>
					<hs_code>3304990019</hs_code>
					<unit_price>169</unit_price>
					<act_weight>0</act_weight>
					<dim_weight>0</dim_weight>
					<quantity>1</quantity>
				</goods >
			</goods_list>
 		</hawb>
	</hawbs>
</beans>";

	$sysData = array(
			'app_key'=>'fjldt',
			'tradeId'=>'1612140001,360588EE1A5F2720B58C50DF9B3AAE58',
			'buz_type'=>'partner',
			'method'=>'global_order_create',
			'version'=>'1.0',
			'format'=>'xml',
			'data'=>$xmlData,
	);

	//生成签名
	ksort($sysData);
	$signStr = '';
	foreach ($sysData as $key => $val){
		$signStr .= $key . $val;
	}
	$signStr .= $appSecret;
	$sign = base64_encode(md5($signStr));
	$sysData['sign'] = $sign;

	//发送数据
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $sysData);
	$result = curl_exec($ch);
	if ($error = curl_error($ch)) {
		echo $error;
	}
	
	$doc = new DOMDocument();
	$doc->loadXML($result);
	$re = array();
	$re['hawbno'] = $doc->getElementsByTagName('hawbno')->item(0)->nodeValue;
	$re['mail_no'] = $doc->getElementsByTagName('mail_no')->item(0)->nodeValue;
	$re['msg'] = $doc->getElementsByTagName('msg')->item(0)->nodeValue;
	var_dump($result); die;
	return $re;
		
    }
}
