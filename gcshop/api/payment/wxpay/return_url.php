<?php
/**
 * author:fulijun
 */
//接受微信返回的数据
$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
$xmlObj=simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA); 
$xmlArr=json_decode(json_encode($xmlObj),true);

//处理微信的数据
$_GET['gct']	= 'payment';
$_GET['gp']		= 'return';
$_GET['payment_code'] = 'wxpay';
$_GET['body'] = strval($xmlArr['title']);
$_GET['out_trade_no'] = strval($xmlArr['out_trade_no']);
$_GET['trade_no'] = strval($xmlArr['transaction_id']);
$_GET['sign'] = strval($xmlArr['sign']);
$_GET['trade_status'] = strval($trade_status == 'SUCCESS' ?'TRADE_FAIL':'TRADE_SUCCESS');
$_GET['total_fee'] = number_format($xmlArr['total_fee']/100,2);
$_GET['seller_id'] = intval($xmlArr['mch_id']);
$_GET['extra_common_param'] = strval($xmlArr['attach']);
require_once(dirname(__FILE__).'/../../../index.php');
?>


