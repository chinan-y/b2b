<?php
	$_GET['gct']	= 'payment';
	$_GET['gp']		= 'return';
	$_GET['payment_code'] = 'bocomm';

	$tranCode = "cb2200_verify";
	$notifyMsg = $_REQUEST["notifyMsg"];   
	$lastIndex = strripos($notifyMsg,"|");
	$signMsg = substr($notifyMsg,$lastIndex+1); //签名信息
	$srcMsg = substr($notifyMsg,0,$lastIndex+1);//原文
	$MerCertID = "301500053119990";
	//连接地址
	$fp = stream_socket_client($socketUrl, $errno, $errstr, 30);
	$retMsg="";
	$in = "<SignData>".$notifyMsg."</SignData>";
	$in = "0000020XML".$MerCertID.$in;
	$inLen = strlen($in);
	$tranType = substr("000000",0,8-strlen(strval($inLen))).$inLen;
	$inall = $tranType.$in;
	
		fwrite($fp, $inall);
	  while (!feof($fp)) {
	     $retMsg =$retMsg.fgets($fp, 1024);
	  }
		fclose($fp);

     //echo $retMsg."<br>";

	//解析返回xml
	$retMsg = substr($retMsg,33);
	$dom = new DOMDocument();
	$dom->loadXML($retMsg);
		$TradeState = ''.$Document->Body->TradeState;
	//echo "retCode=".$retCode_value."  "."errMsg=".$errMsg_value;
	if($TradeState = 'Paied')
       {
       else
       {
require_once(dirname(__FILE__).'/../../../index.php');
?>