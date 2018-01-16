<?php	@header("Content-type: text/html; charset=utf-8");
	$_GET['gct']	= 'payment';
	$_GET['gp']		= 'return';
	$_GET['payment_code'] = 'bocomm';

	$tranCode = "cb2200_verify";
	$notifyMsg = $_REQUEST["notifyMsg"];   
	$lastIndex = strripos($notifyMsg,"|");
	$signMsg = substr($notifyMsg,$lastIndex+1); //签名信息
	$srcMsg = substr($notifyMsg,0,$lastIndex+1);//原文
	$MerCertID = "301500053119990";    //echo $notifyMsg."<br>";	
	//连接地址	$socketUrl = "tcp://127.0.0.1:8891";
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
	$dom->loadXML($retMsg);			$TranCode = $dom->getElementsByTagName('TranCode');		$TranCode_value = $TranCode->item(0)->nodeValue;		$TranDate = $dom->getElementsByTagName('TranDate');		$TranDate_value = $TranDate->item(0)->nodeValue;		$TranTime = $dom->getElementsByTagName('TranTime');		$TranTime_value = $TranTime->item(0)->nodeValue;		$Document = new SimpleXMLElement($retMsg);	
		$TradeState = ''.$Document->Body->TradeState;		$MerOrderNo = ''.$Document->Body->MerOrderList->MerOrderInfo->MerOrderNo;
	//echo "retCode=".$retCode_value."  "."errMsg=".$errMsg_value;
	if($TradeState = 'Paied')
       {			//赋值，方便后面合并使用支付宝验证方法					   	$_GET['out_trade_no'] = $MerOrderNo;			$_GET['extra_common_param'] ='real_order';			$_GET['trade_no'] = $MerOrderNo;       }
       else
       {		   echo "交易返回码：".$TranCode_value."<br>";           echo "支付失败，请联系网站客服。<br>";	   }
require_once(dirname(__FILE__).'/../../../index.php');
?>