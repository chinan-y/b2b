<?php
  @header("Content-type: text/html; charset=utf-8");
  require_once("config.inc");		
	$MerCertID=$_REQUEST["MerCertID"];
	$MerPtcId=$_REQUEST["MerPtcId"];   	
	$TranCode=$_REQUEST["TranCode"];   
	$TranDate=$_REQUEST["TranDate"];   
	$TranTime=$_REQUEST["TranTime"];   
	$ReturnURL=$_REQUEST["ReturnURL"]; 
	$NotifyURL=$_REQUEST["NotifyURL"]; 

	//连接地址
	$fp = stream_socket_client($socketUrl, $errno, $errstr, 30);
	$retMsg="";
	if (!$fp) {
		echo "$errstr ($errno)<br />\n";
	} else {
		$in  = "0000030XML".$MerCertID;
		$in .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Document><Head>";
		$in .= "<MerPtcId>".$MerPtcId."</MerPtcId>";
		$in .= "<TranTime>".$TranTime."</TranTime>";
		$in .= "<TranCode>".$TranCode."</TranCode>";
		$in .= "<TranDate>".$TranDate."</TranDate></Head>";
		$in .= "<Body>";
		$in .= "<ReturnURL>".$ReturnURL."</ReturnURL>";
		$in .= "<NotifyURL>".$NotifyURL."</NotifyURL>";
		$in .= "</Body></Document>";

		$inLen = strlen($in);
		$tranType = substr("000000",0,8-strlen(strval($inLen))).$inLen;
		$inall = $tranType.$in;

    fwrite($fp, $inall);
    while (!feof($fp)) {
       $retMsg =$retMsg.fgets($fp, 1024);
    }
    fclose($fp);

	//解析返回xml
	$retCode = substr($retMsg,8,6);
	if($retCode == "S00000"){
		$signStart = strripos($retMsg,"<SignData>")+10;
		$signEnd = strripos($retMsg,"</SignData>");
	}else{
		echo "sign error!";	
	}
	$signData = substr($retMsg,$signStart,$signEnd-$signStart);
}
	//回显
	echo "银行返回retMsg原始数据=".$retMsg."<br>";
	$retMsg = substr($retMsg,33);
	$dom = new DOMDocument();
	$dom->loadXML($retMsg);
	$RspType = $dom->getElementsByTagName('RspType');
	$RspType_value = $RspType->item(0)->nodeValue;
	
	if($RspType_value == "N"){
		$Document = new SimpleXMLElement($retMsg);
		echo "--------------------------------------------------------<br>";
		echo "交易标志：".$Document->Head->RspType."<br>";
		echo "交易代码：".$Document->Head->RspCode."<br>";
		echo "交易信息：".$Document->Head->RspMsg."<br>";
		echo "交易日期：".$Document->Head->RspDate."<br>";
		echo "交易时间：".$Document->Head->RspTime."<br>";
		echo "--------------------------------------------------------<br>";
		echo "一级商户协议号：".$Document->Body->MerPtcId."<br>";
		echo "前台通知地址：".$Document->Body->ReturnURL."<br>";
		echo "后台通知地址：".$Document->Body->NotifyURL."<br>";
		echo "--------------------------------------------------------<br>";
		}
	else{echo "<br>取回传数据失败！<br>";}
?> 
