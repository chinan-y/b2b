<?php
  @header("Content-type: text/html; charset=utf-8");
  require_once("config.inc");		
	
	$MerCertID=$_REQUEST["MerCertID"];
	//$MerCertID=$_REQUEST["MerPtcId"];
	
	$TranCode=$_REQUEST["TranCode"];   
	$MerPtcId=$_REQUEST["MerPtcId"];   
	$TranDate=$_REQUEST["TranDate"];   
	$TranTime=$_REQUEST["TranTime"];
	$TotalCount=$_REQUEST["TotalCount"];   
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
		$in  = "0000010XML".$MerCertID;
		$in .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Document><Head>";
		$in .= "<MerPtcId>".$MerPtcId."</MerPtcId>";
		$in .= "<TranTime>".$TranTime."</TranTime>";
		$in .= "<TranCode>".$TranCode."</TranCode>";
		$in .= "<TranDate>".$TranDate."</TranDate></Head>";
		$in .= "<Body>";
		$in .= "<MerTranSerialNo>".$MerTranSerialNo."</MerTranSerialNo>";
		$in .= "<SafeReserved>".$SafeReserved."</SafeReserved>";
		$in .= "<TotalCount>".$TotalCount."</TotalCount>";
		$in .= "<ChannelApi>".$ChannelApi."</ChannelApi>";
		$in .= "<ChannelInst>".$ChannelInst."</ChannelInst>";
		$in .= "<OrderList>";
		for($i=0;$i<$TotalCount;$i++)
		{
		$in .= "<OrderInfo>";
		$in .= "<PtcInfo><SubMerPtcId>".$SubMerPtcId."</SubMerPtcId></PtcInfo>";
		$in .= "<BusiInfo><MerOrderNo>".substr($MerOrderNo,0,16).str_pad($i+1,2,0,STR_PAD_LEFT)."</MerOrderNo></BusiInfo>";
		$in .= "<UserInfo><BuyerId>".$BuyerId."</BuyerId><BuyerName>".$BuyerName."</BuyerName><SellerId>".$SellerId."</SellerId><SellerName>".$SellerName."</SellerName></UserInfo>";
		$in .= "<GoodsInfo><GoodsName>".$GoodsName."</GoodsName><GoodsTxt>".$GoodsTxt."</GoodsTxt><GoodsDesc>".$GoodsDesc."</GoodsDesc></GoodsInfo>";
		$in .= "<TranInfo><TranModeId>".$TranModeId."</TranModeId><TranAmt>".$TranAmt."</TranAmt><TranCry>".$TranCry."</TranCry></TranInfo>";
		$in .= "<MemoInfo><BuyerMemo>".$BuyerMemo."</BuyerMemo><SellerMemo>".$SellerMemo."</SellerMemo><PlatMemo>".$PlatMemo."</PlatMemo></MemoInfo>";
		$in .= "</OrderInfo>";
		}
		$in .= "</OrderList>";
		$in .= "<PayMemo>".$PayMemo."</PayMemo>";
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
?> 

<html xmlns="http://www.w3.org/1999/xhtml" >
<head runat="server">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>交易创建并付款</title>
</head>
<body bgcolor = "#FFFFFF" text = "#000000" onload="form1.submit()">
<form name = "form1" method = "post" action = "<?php echo($OrderURL);?>">
		<input type = "hidden" name = "signData" value = "<?php echo($signData); ?>"/>
</form>
</body>
</html> 
