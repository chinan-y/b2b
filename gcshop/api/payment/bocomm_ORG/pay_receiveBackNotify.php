<?php
		@header("Content-type: text/html; charset=UTF-8");
		require_once ("config.inc");     //参数文件
		$file_name = "D:\dataV2.log";      //返回信息的日志文件
		date_default_timezone_set('Etc/GMT-8'); //设置默认时区    
		$file_pointer = fopen($file_name, "a");
		fwrite($file_pointer, "\r\n\r\n----------------------[后台".date('y-m-d H:i:s',time())."]------------------\r\n");
        	       
		//获取结果并验签
		$notifyMsg = $_REQUEST["notifyMsg"]; 
		//$notifyMsg = "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";
  	fwrite($file_pointer, "[后台".date('y-m-d H:i:s',time())."]密文notifyMsg=".$notifyMsg."\r\n");
		$retMsg="";

		//组织报文
    $in = "<SignData>".$notifyMsg."</SignData>";
		$in = "0000020XML".$MerCertID.$in;
		$inLen = strlen($in);
		$tranType = substr("000000",0,8-strlen(strval($inLen))).$inLen;
		$inall = $tranType.$in;

	  //送往jar包解密
		$fp = stream_socket_client($socketUrl, $errno, $errstr, 30);
		fwrite($fp, $inall);
	  	while (!feof($fp)) {
	     		$retMsg =$retMsg.fgets($fp, 1024);
	  	}
		fclose($fp);
		fwrite($file_pointer,"[后台".date('y-m-d H:i:s',time())."]明文retMsg=".$retMsg."\r\n");
		
		//商户处理返回结果，解析通知报文....  
		$retMsg = substr($retMsg,33);
		$dom = new DOMDocument();
		$dom->loadXML($retMsg);
		$TranCode = $dom->getElementsByTagName('TranCode');
		$TranCode_value = $TranCode->item(0)->nodeValue;
		$TranDate = $dom->getElementsByTagName('TranDate');
		$TranDate_value = $TranDate->item(0)->nodeValue;
		$TranTime = $dom->getElementsByTagName('TranTime');
		$TranTime_value = $TranTime->item(0)->nodeValue;
		
		fwrite($file_pointer, "[后台".date('y-m-d H:i:s',time())."]交易代码=".$TranCode_value."\r\n");
		fwrite($file_pointer, "[后台".date('y-m-d H:i:s',time())."]交易日期=".$TranDate_value."\r\n");
		fwrite($file_pointer, "[后台".date('y-m-d H:i:s',time())."]交易时间=".$TranTime_value."\r\n");
		
		$Document = new SimpleXMLElement($retMsg);
		fwrite($file_pointer, "[后台".date('y-m-d H:i:s',time())."]交易状态=".$Document->Body->TradeState."\r\n");
		fwrite($file_pointer, "[后台".date('y-m-d H:i:s',time())."]商户流水号=".$Document->Body->MerTranSerialNo."\r\n");
		fwrite($file_pointer, "[后台".date('y-m-d H:i:s',time())."]----------------子单信息开始----------------\r\n");
		foreach($Document->Body->MerOrderList->MerOrderInfo as $MerOrderInfo)
		{
			fwrite($file_pointer, "[后台".date('y-m-d H:i:s',time())."]--------\r\n");
			fwrite($file_pointer, "[后台".date('y-m-d H:i:s',time())."]  平台商外部订单号=".$MerOrderInfo->MerOrderNo."\r\n");
			fwrite($file_pointer, "[后台".date('y-m-d H:i:s',time())."]  已支付金额=".$MerOrderInfo->PaiedSum."\r\n");
			fwrite($file_pointer, "[后台".date('y-m-d H:i:s',time())."]  支付币种=".$MerOrderInfo->PaiedSumCry."\r\n");
		}
    fwrite($file_pointer, "[后台".date('y-m-d H:i:s',time())."]----------------子单信息结束-----------------\r\n");
    fclose($file_pointer);
?>