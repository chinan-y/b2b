<?php
		@header("Content-type: text/html; charset=UTF-8");
		require_once ("config.inc");     //参数文件
		$file_name = "D:\dataV2.log";      //返回信息的日志文件
		date_default_timezone_set('Etc/GMT-8'); //设置默认时区    
		$file_pointer = fopen($file_name, "a");
		fwrite($file_pointer, "\r\n\r\n----------------------[前台".date('y-m-d H:i:s',time())."]------------------\r\n");
        	       
		//获取结果
		$notifyMsg = $_REQUEST["notifyMsg"]; 
		//$notifyMsg = "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";
		fwrite($file_pointer, "[前台".date('y-m-d H:i:s',time())."]密文notifyMsg=".$notifyMsg."\r\n");
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
		fwrite($file_pointer,"[前台".date('y-m-d H:i:s',time())."]明文retMsg=".$retMsg."\r\n");
		
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
		
		fwrite($file_pointer, "[前台".date('y-m-d H:i:s',time())."]交易代码=".$TranCode_value."\r\n");
		fwrite($file_pointer, "[前台".date('y-m-d H:i:s',time())."]交易日期=".$TranDate_value."\r\n");
		fwrite($file_pointer, "[前台".date('y-m-d H:i:s',time())."]交易时间=".$TranTime_value."\r\n");
		echo "交易代码：".$TranCode_value."<br>";
		echo "交易日期：".$TranDate_value."<br>";
		echo "交易时间：".$TranTime_value."<br>";
		
		$Document = new SimpleXMLElement($retMsg);
		fwrite($file_pointer, "[前台".date('y-m-d H:i:s',time())."]交易状态=".$Document->Body->TradeState."\r\n");
		fwrite($file_pointer, "[前台".date('y-m-d H:i:s',time())."]商户流水号=".$Document->Body->MerTranSerialNo."\r\n");
		fwrite($file_pointer, "[前台".date('y-m-d H:i:s',time())."]----------------子单信息开始----------------\r\n");
		echo "交易状态=".$Document->Body->TradeState."<br>";
		echo "商户流水号=".$Document->Body->MerTranSerialNo."<br>";
		echo "----------------------------子单信息开始-------------";

		foreach($Document->Body->MerOrderList->MerOrderInfo as $MerOrderInfo){
			fwrite($file_pointer, "[前台".date('y-m-d H:i:s',time())."]--------\r\n");
			fwrite($file_pointer, "[前台".date('y-m-d H:i:s',time())."]  平台商外部订单号=".$MerOrderInfo->MerOrderNo."\r\n");
			fwrite($file_pointer, "[前台".date('y-m-d H:i:s',time())."]  已支付金额=".$MerOrderInfo->PaiedSum."\r\n");
			fwrite($file_pointer, "[前台".date('y-m-d H:i:s',time())."]  支付币种=".$MerOrderInfo->PaiedSumCry."\r\n");
			echo "--------";
			echo "<br>平台商外部订单号=".$MerOrderInfo->MerOrderNo."<br>";
			echo "已支付金额=".$MerOrderInfo->PaiedSum."<br>";
			echo "支付币种=".$MerOrderInfo->PaiedSumCry."<br>";
		}

    fwrite($file_pointer, "[前台".date('y-m-d H:i:s',time())."]----------------子单信息结束------------------------\r\n");
    fclose($file_pointer);
    echo "----------------------------子单信息结束---------------------<br>";    
?>