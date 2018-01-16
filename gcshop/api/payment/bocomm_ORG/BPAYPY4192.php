<?php
  @header("Content-type: text/html; charset=utf-8");
  require_once("config.inc");

	$MerCertID=$_REQUEST["MerCertID"];
	//$MerCertID=$_REQUEST["MerPtcId"];
	
	$TranCode=$_REQUEST["TranCode"];   
	$MerPtcId=$_REQUEST["MerPtcId"];   
	$TranDate=$_REQUEST["TranDate"];   
	$TranTime=$_REQUEST["TranTime"]; 

	$MerOrderNo=$_REQUEST["MerOrderNo"]; 

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
		$in .= "<MerOrderNo>".$MerOrderNo."</MerOrderNo>";
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
		
		if($RspType_value == "N"){
				$Document = new SimpleXMLElement($retMsg);
				echo "交易订单号：".$Document->Body->TradeOrderNo."<br>";
				echo "付款流水号：".$Document->Body->PayOrderNo."<br>";
				echo "交易状态：".$Document->Body->TradeState."<br>";
				echo "订单状态：".$Document->Body->OrderState."<br>";
				echo "交易模式：".$Document->Body->TranModeId."<br>";
				echo "订单创建时间：".$Document->Body->CreateDateTime."<br>";
				echo "订单关闭时间：".$Document->Body->GmtCloseDateTime."<br>";
				echo "一级商户签约协议号：".$Document->Body->MerPtcId."<br>";
				echo "一级商户简称：".$Document->Body->MerNameCN."<br>";
				echo "一级商户所属分行：".$Document->Body->MerOpenBranch."<br>";
				//echo "二级商户签约协议：".$Document->Body->SubMerPtcId."<br>";
				//echo "二级商户名称：".$Document->Body->SubMerNameCN."<br>";
				echo "一级商户（外部）订单号：".$Document->Body->MerOrderNo."<br>";
				echo "一级商户订单备注：".$Document->Body->PlatMemo."<br>";
				echo "电子回单校验码：".$Document->Body->ReceiptCode."<br>";
				echo "银行订单备注：".$Document->Body->BankMemo."<br>";
				echo "订单金额：".$Document->Body->TranAmt."<br>";
				//echo "订单币种：".$Document->Body->TranCry."<br>";
				echo "实际付款金额：".$Document->Body->PayAmt."<br>";
				//echo "实际付款币种：".$Document->Body->PayCry."<br>";
				echo "已支付总金额：".$Document->Body->PaiedSum."<br>";
				echo "已退款总金额：".$Document->Body->RefundSum."<br>";
				echo "已交付金额：".$Document->Body->ConfirmSum."<br>";
				echo "商品简称：".$Document->Body->GoodsTxt."<br>";
				echo "订单名称：".$Document->Body->GoodsName."<br>";
				echo "商品详情：".$Document->Body->GoodsDesc."<br>";
				echo "买方会员ID：".$Document->Body->BuyerId."<br>";
				echo "买方名称：".$Document->Body->BuyerName."<br>";
				echo "买方备注：".$Document->Body->BuyerMemo."<br>";
				echo "卖方会员ID：".$Document->Body->SellerId."<br>";
				echo "卖方名称：".$Document->Body->SellerName."<br>";
				echo "卖方备注：".$Document->Body->SellerMemo."<br>";
				//echo "安全域：".$Document->Body->SafeReserved."<br>";
				echo "订单有效期：".$Document->Body->ValidPeriod."<br>";
				
				foreach($Document->Body->SettingList->SettingStream as $SettingStream){
					echo "--------<br>";
					echo "商户流水号：".$SettingStream->MerTranSerialNo."<br>";
					echo "银行交易流水号：".$SettingStream->PayNo."<br>";
					echo "流水日期：".$SettingStream->PayDate."<br>";
					echo "流水类型：".$SettingStream->PayType."<br>";
					echo "流水金额：".$SettingStream->PayAmt."<br>";
					echo "流水状态：".$SettingStream->PayState."<br>";
					echo "商户流水备注：".$SettingStream->PayMemo."<br>";
					echo "银行流水备注：".$SettingStream->PayBankMemo."<br>";
					echo "通道类型：".$SettingStream->ChannelType."<br>";
					echo "付款银行名称：".$SettingStream->PayBankName."<br>";
				}
		}
}
?>