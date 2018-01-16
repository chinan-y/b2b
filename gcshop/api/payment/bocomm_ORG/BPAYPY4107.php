<?php
  @header("Content-type: text/html; charset=utf-8");
  require_once("config.inc");		
	$MerCertID=$_REQUEST["MerCertID"];
	$TranCode=$_REQUEST["TranCode"];
	$MerPtcId=$_REQUEST["MerPtcId"];
	$TranDate=$_REQUEST["TranDate"];
	$TranTime=$_REQUEST["TranTime"];
	$MerTranSerialNo=$_REQUEST["MerTranSerialNo"];

	$MerOrderNo=$_REQUEST["MerOrderNo"];
	$RefundAmt=$_REQUEST["RefundAmt"];
	$RefundCry=$_REQUEST["RefundCry"];
	$RefundMemo=$_REQUEST["RefundMemo"];

	//连接地址
	$fp = stream_socket_client($socketUrl, $errno, $errstr, 30);
	$retMsg="";
	if (!$fp) {
		echo "$errstr ($errno)<br/>\n";
	} else {
		$in  = "0000030XML".$MerCertID;
		$in .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Document><Head>";
		$in .= "<TranCode>".$TranCode."</TranCode>";
		$in .= "<MerPtcId>".$MerPtcId."</MerPtcId>";
		$in .= "<TranDate>".$TranDate."</TranDate>";
		$in .= "<TranTime>".$TranTime."</TranTime></Head>";
		$in .= "<Body>";
		$in .= "<MerTranSerialNo>".$MerTranSerialNo."</MerTranSerialNo>";
		$in .= "<BusiInfo>";
		$in .= "<MerOrderNo>".$MerOrderNo."</MerOrderNo>";
		$in .= "<RefundAmt>".$RefundAmt."</RefundAmt>";
		$in .= "<RefundCry>".$RefundCry."</RefundCry>";
		$in .= "<RefundMemo>".$RefundMemo."</RefundMemo>";
		$in .= "</BusiInfo>";
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


	$Document = new SimpleXMLElement($retMsg);
	echo "--------------------------------------------------------<br>";
	echo "交易标志：".$Document->Head->RspType."<br>";
	echo "交易代码：".$Document->Head->RspCode."<br>";
	echo "交易信息：".$Document->Head->RspMsg."<br>";
	echo "交易日期：".$Document->Head->RspDate."<br>";
	echo "交易时间：".$Document->Head->RspTime."<br>";
	echo "--------------------------------------------------------<br>";		
	if($RspType_value == "N"){
		echo "交易状态：".$Document->Body->TranStt."<br>";
		echo "商户订单号：".$Document->Body->MerOrderNo."<br>";
		echo "退款单据号：".$Document->Body->RefundOrderNo."<br>";
		echo "退款金额：".$Document->Body->RefundAmt."<br>";
		echo "退款币种：".$Document->Body->RefundCry."<br>";
		echo "商户流水号：".$Document->Body->MerTranSerialNo."<br>";
		echo "--------------------------------------------------------<br>";
		}
?>