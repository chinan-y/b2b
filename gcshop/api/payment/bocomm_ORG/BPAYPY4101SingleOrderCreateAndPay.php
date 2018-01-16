<?php
	@header("Content-type: text/html; charset=utf-8");
  require_once("config.inc");

	$MerCertID=$_REQUEST["MerCertID"];
	
	$TranCode=$_REQUEST["TranCode"];   
	$MerPtcId=$_REQUEST["MerPtcId"];   
	$TranDate=$_REQUEST["TranDate"];   
	$TranTime=$_REQUEST["TranTime"];   
	$MerOrderNo=$_REQUEST["MerOrderNo"]; 
	$BuyerId=$_REQUEST["BuyerId"];    
	$BuyerName=$_REQUEST["BuyerName"];  
	$SellerId=$_REQUEST["SellerId"];   
	$SellerName=$_REQUEST["SellerName"]; 
	$GoodsName=$_REQUEST["GoodsName"];  
	$GoodsTxt=$_REQUEST["GoodsTxt"];   
	$GoodsDesc=$_REQUEST["GoodsDesc"];  
	$TranModeId=$_REQUEST["TranModeId"]; 
	$TranAmt=$_REQUEST["TranAmt"];    
	$TranCry=$_REQUEST["TranCry"];    
	$ChannelApi=$_REQUEST["ChannelApi"]; 
	$ChannelInst=$_REQUEST["ChannelInst"];
	
	$MerTranSerialNo=$_REQUEST["MerTranSerialNo"];
	$SafeReserved=$_REQUEST["SafeReserved"];
	$SubMerPtcId=$_REQUEST["SubMerPtcId"];
	$BuyerMemo=$_REQUEST["BuyerMemo"];
	$SellerMemo=$_REQUEST["SellerMemo"];
	$PlatMemo=$_REQUEST["PlatMemo"];
	$PayMemo=$_REQUEST["PayMemo"];

	//连接地址
	$fp = stream_socket_client($socketUrl, $errno, $errstr, 30);
	$retMsg="";
	if (!$fp) {
		echo "$errstr ($errno)<br />\n";
	} else {
		$in  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Document><Head>";
		$in .= "<MerPtcId>".$MerPtcId."</MerPtcId>";
		$in .= "<TranTime>".$TranTime."</TranTime>";
		$in .= "<TranCode>".$TranCode."</TranCode>";
		$in .= "<TranDate>".$TranDate."</TranDate></Head>";
		$in .= "<Body>";
		$in .= "<MerTranSerialNo>".$MerTranSerialNo."</MerTranSerialNo>";
		$in .= "<SafeReserved>".$SafeReserved."</SafeReserved>";
		$in .= "<PtcInfo><SubMerPtcId>".$SubMerPtcId."</SubMerPtcId></PtcInfo>";
		$in .= "<BusiInfo><MerOrderNo>".$MerOrderNo."</MerOrderNo></BusiInfo>";
		$in .= "<UserInfo><BuyerId>".$BuyerId."</BuyerId>";
		$in .= "<BuyerName>".$BuyerName."</BuyerName>";
		$in .= "<SellerId>".$SellerId."</SellerId>";
		$in .= "<SellerName>".$SellerName."</SellerName></UserInfo>";
		$in .= "<GoodsInfo><GoodsName>".$GoodsName."</GoodsName>";
		$in .= "<GoodsTxt>".$GoodsTxt."</GoodsTxt>";
		$in .= "<GoodsDesc>".$GoodsDesc."</GoodsDesc></GoodsInfo>";
		$in .= "<TranInfo><TranModeId>".$TranModeId."</TranModeId>";
		$in .= "<TranAmt>".$TranAmt."</TranAmt>";
		$in .= "<TranCry>".$TranCry."</TranCry></TranInfo>";
		$in .= "<ChannelInfo><ChannelApi>".$ChannelApi."</ChannelApi>";
		$in .= "<ChannelInst>".$ChannelInst."</ChannelInst></ChannelInfo>";
		$in .= "<MemoInfo><BuyerMemo>".$BuyerMemo."</BuyerMemo>";
		$in .= "<SellerMemo>".$SellerMemo."</SellerMemo>";
		$in .= "<PlatMemo>".$PlatMemo."</PlatMemo>";
		$in .= "<PayMemo>".$PayMemo."</PayMemo></MemoInfo>";
		$in .= "</Body></Document>";
		$in  = "0000010XML".$MerCertID.$in;
		
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
?> 

<html xmlns="http://www.w3.org/1999/xhtml" >
<head runat="server">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>交易创建并付款</title>
</head>
<body bgcolor = "#FFFFFF" text = "#000000" onload="form1.submit()">
<form name = "form1" method = "post" action = "<?php echo($OrderURL);?>">
		<input type = "hidden" name = "signData" value = "<?php echo($signData); ?>%>"/>
</form>


</body>
</html> 
