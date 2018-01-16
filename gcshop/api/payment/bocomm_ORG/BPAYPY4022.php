<?php
  @header("Content-type: text/html; charset=utf-8");
  require_once("config.inc");		
	$MerCertID=$_REQUEST["MerCertID"];
	$MerPtcId=$_REQUEST["MerPtcId"];   	
	$TranCode=$_REQUEST["TranCode"];   
	$TranDate=$_REQUEST["TranDate"];   
	$TranTime=$_REQUEST["TranTime"];   
	@$ReturnURL=$_REQUEST["ReturnURL"]; 
	@$NotifyURL=$_REQUEST["NotifyURL"]; 

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
		echo "--------------------交易包头----------------------------<br>";
		echo "交易标志：".$Document->Head->RspType."<br>";
		echo "交易代码：".$Document->Head->RspCode."<br>";
		echo "交易信息：".$Document->Head->RspMsg."<br>";
		echo "交易日期：".$Document->Head->RspDate."<br>";
		echo "交易时间：".$Document->Head->RspTime."<br>";
		echo "--------------------------------------------------------<br>";
		echo "-------------------商户基本信息-------------------------<br>";
		echo "商户编号：".$Document->Body->MerBaseInfo->MerId."<br>";
		echo "企业网银客户号：".$Document->Body->MerBaseInfo->CstNo."<br>";
		echo "核心客户号：".$Document->Body->MerBaseInfo->HostNo."<br>";
		echo "企业中文名称：".$Document->Body->MerBaseInfo->EnterNameCN."<br>";
		echo "企业英文名称：".$Document->Body->MerBaseInfo->EnterNameEN."<br>";
		echo "证件类型：".$Document->Body->MerBaseInfo->CertType."<br>";
		echo "证件号码：".$Document->Body->MerBaseInfo->CertNo."<br>";
		echo "商户开户分行：".$Document->Body->MerBaseInfo->OpenBra."<br>";
		echo "商户开户网点：".$Document->Body->MerBaseInfo->OpenNode."<br>";
		echo "--------------------------------------------------------------<br>";
		echo "-------------------商户业务信息-------------------------<br>";
		echo "商户中文名称：".$Document->Body->MerBusInfo->MerchNameCN."<br>";
		echo "商户英文名称：".$Document->Body->MerBusInfo->MerchNameEN."<br>";
		echo "ICP号：".$Document->Body->MerBusInfo->ICP."<br>";
		echo "商户类别：".$Document->Body->MerBusInfo->MerchType."<br>";
		echo "商户网站域名：".$Document->Body->MerBusInfo->WebsiteURL."<br>";
		echo "商户地址：".$Document->Body->MerBusInfo->MerchAddr."<br>";
		echo "联系人姓名：".$Document->Body->MerBusInfo->ContacterName."<br>";
		echo "联系电话：".$Document->Body->MerBusInfo->PhoneNo."<br>";
		echo "手机号码：".$Document->Body->MerBusInfo->MobileNo."<br>";
		echo "电子邮箱地址：".$Document->Body->MerBusInfo->EmailAddr."<br>";
		echo "商户情况说明：".$Document->Body->MerBusInfo->MerchDetailInfo."<br>";
		echo "二级商户会员编号：".$Document->Body->MerBusInfo->SubMerMemId."<br>";
		echo "商户备注：".$Document->Body->MerBusInfo->MerchMemo."<br>";
		echo "--------------------------------------------------------------<br>";
		echo "-------------------商户协议信息-------------------------<br>";
		echo "商户协议号：".$Document->Body->MerPtcInfo->MerPtcId."<br>";
		echo "交易类型：".$Document->Body->MerPtcInfo->TranType."<br>";
		echo "商户协议总层级：".$Document->Body->MerPtcInfo->TotalLayer."<br>";
		echo "交易模式：".$Document->Body->MerPtcInfo->TranMode."<br>";
		echo "商城开通二级商户：".$Document->Body->MerPtcInfo->SubPtcCreMod."<br>";
		echo "手续费收取对象：".$Document->Body->MerPtcInfo->FeeChgObj."<br>";
		echo "手续费收取周期：".$Document->Body->MerPtcInfo->FeePeriod."<br>";
		echo "退货返还手续费：".$Document->Body->MerPtcInfo->ReturnFeeFlg."<br>";
		echo "手续费分类代码：".$Document->Body->MerPtcInfo->FeeGroupId."<br>";
		echo "前台通知地址：".$Document->Body->MerPtcInfo->ReturnURL."<br>";
		echo "后台通知地址：".$Document->Body->MerPtcInfo->NotifyURL."<br>";
		echo "商户协议状态：".$Document->Body->MerPtcInfo->PtcStatus."<br>";
		echo "商户协议备注：".$Document->Body->MerPtcInfo->PtcMemo."<br>";
		echo "--------------------------------------------------------------<br>";
		}
	else{echo "<br>取回传数据失败！<br>";}
?> 
