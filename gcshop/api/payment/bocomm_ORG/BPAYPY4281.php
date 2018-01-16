<?php
  @header("Content-type: text/html; charset=utf-8");
  require_once("config.inc");

	$MerCertID=$_REQUEST["MerCertID"];
	$TranCode=$_REQUEST["TranCode"];   
	$MerPtcId=$_REQUEST["MerPtcId"];   
	$TranDate=$_REQUEST["TranDate"];   
	$TranTime=$_REQUEST["TranTime"]; 

	$FileType=$_REQUEST["FileType"];
	$FileFormat=$_REQUEST["FileFormat"]; 
	$SettDate=$_REQUEST["SettDate"]; 

	$fp = stream_socket_client($socketUrl, $errno, $errstr, 30);
	$retMsg="";
	if (!$fp) {
		echo "$errstr ($errno)<br />\n";
	} else {
		$in  = "0000040XML".$MerCertID;
		$in .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Document><Head>";
		$in .= "<MerPtcId>".$MerPtcId."</MerPtcId>";
		$in .= "<TranTime>".$TranTime."</TranTime>";
		$in .= "<TranCode>".$TranCode."</TranCode>";
		$in .= "<TranDate>".$TranDate."</TranDate></Head>";
		$in .= "<Body>";
		$in .= "<FileType>".$FileType."</FileType>";
		$in .= "<FileFormat>".$FileFormat."</FileFormat>";
		$in .= "<SettDate>".$SettDate."</SettDate>";
		$in .= "</Body></Document>";
		
		$inLen = strlen($in);
		$tranType = substr("000000",0,8-strlen(strval($inLen))).$inLen;
		$inall = $tranType.$in;	

		fwrite($fp, $inall);
    while (!feof($fp)) {
       $retMsg =$retMsg.fgets($fp, 1024);
    }
    fclose($fp);

		//$retCode = substr($retMsg,8,6);
		$retMsg = substr($retMsg,33);
		$dom = new DOMDocument();
		$dom->loadXML($retMsg);
		$RspType = $dom->getElementsByTagName('RspType');
		$RspType_value = $RspType->item(0)->nodeValue;
	
		$RspCode = $dom->getElementsByTagName('RspCode');
		$RspCode_value = $RspCode->item(0)->nodeValue;
	
		$RspMsg = $dom->getElementsByTagName('RspMsg');
		$RspMsg_value = $RspMsg->item(0)->nodeValue;
	
		$RspDate = $dom->getElementsByTagName('RspDate');
		$RspDate_value = $RspDate->item(0)->nodeValue;
	
		$RspTime = $dom->getElementsByTagName('RspTime');
		$RspTime_value = $RspTime->item(0)->nodeValue;
		
		echo "交易标志：".$RspType_value."<br>";
		echo "交易代码：".$RspCode_value."<br>";
		echo "交易信息：".$RspMsg_value."<br>";
		echo "交易日期：".$RspDate_value."<br>";
		echo "交易时间：".$RspTime_value."<br>";
		echo "--------------------------------------------------------<br>";
		if($RspType_value == "N")
		 {echo "报表生成成功！<br>";}
		else
		 {echo "失败！原因".$RspMsg_value."<br>";}
}
?>