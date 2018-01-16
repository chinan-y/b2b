<?php
	header("Content-type: text/html; charset=utf-8");

	//用户反馈地址
	$POST_URL = 'http://www.qqbsmall.com/gcshop/api/mess/mess_return.php';
	
	//接收传送的数据
	$fileContent = file_get_contents("php://input");
	
	$strDate = date("Y"."-"."m"."-"."d");
	$strTime = date("H".":"."i".":"."s");


	//连接数据库
	$dbconn = mysql_connect("localhost","root","Gcgj@mysql@20161111")  or die ('数据库连接失败！');
	mysql_query("set names 'utf8'",$dbconn); 
	mysql_select_db("message",$dbconn)  or die ('数据库选定失败！');
	
	//base64解密
	$xml_decode = base64_decode($fileContent);
	
	//用DOMDocument方法读取XML
	$dom = new DOMDocument();
	$dom->loadXML($xml_decode);
	$MessageType = $dom->getElementsByTagName('MessageType');
	$MessageType_value = $MessageType->item(0)->nodeValue;
	$UserNo = $dom->getElementsByTagName('UserNo');
	$UserNo_value = $UserNo->item(0)->nodeValue;
	$Password = $dom->getElementsByTagName('Password');
	$Password_value = $Password->item(0)->nodeValue;

	$sql = mysql_query("select count(*) as total from `user` WHERE `user`.UserNo = '50052602G5' AND `user`.`Password` = '50052602G5'");
	$res = mysql_fetch_array($sql);
	$count = $res['total'];
	mysql_close($dbconn);
	if($count > 0)
		{
			$Document = new SimpleXMLElement($xml_decode);
			$MessageId = $Document->MessageHead->MessageId;
			$MessageTime = $Document->MessageHead->MessageTime;
			$ReceiverId = $Document->MessageHead->SenderId;
			$SKU = $Document->MessageBody->DTCFlow->SKU_INFO->SKU;
			$STATUS_CODE = '30';
			$OP_DATE = $strDate . "T" . $strTime;
			$MEMO = '';
			$MessageType = 'SKU_INFO_FB';		//报文类型
			$SenderId = 'CQITC';		//发送者ID
		}
		else
		{
			echo"用户验证失败";
		}

	
	//检测是否支持 curl
	if(!extension_loaded("curl")){
		trigger_error("不对起，请先开启服务器curl功能模块", E_USER_ERROR);
	}
	
		//构造XML
	
		$in  = "<?xml version='1.0' encoding='UTF-8' ?>";
		$in .= "<DTC_Message><MessageHead>";
		$in .= "<MessageType>".$MessageType."</MessageType>";
		$in .= "<MessageId>".$MessageId."</MessageId>";
		$in .= "<MessageTime>".$MessageTime."</MessageTime>";
		$in .= "<SenderId>".$SenderId."</SenderId>";
		$in .= "<ReceiverId>".$ReceiverId."</ReceiverId>";
		$in .= "</MessageHead><MessageBody><DTCFlow><SKU_INFO_FB>";
		$in .= "<SKU>".$SKU."</SKU>";
		$in .= "<STATUS_CODE>".$STATUS_CODE."</STATUS_CODE>";
		$in .= "<OP_DATE>".$OP_DATE."</OP_DATE>";
		$in .= "<MEMO>".$MEMO."</MEMO>";
		$in .= "</SKU_INFO_FB></DTCFlow></MessageBody></DTC_Message>";
		
		//echo $in;
		//base64加密
		$xml_data = base64_encode($in);
		
		//初始一个curl会话
		$ch = curl_init();
		
		//设置url
		curl_setopt($ch, CURLOPT_URL, $POST_URL);
				
		//设置发送方式：POST
		curl_setopt($ch, CURLOPT_POST, true);
		
		//设置文件头为 Content-Type: text/xml
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml')); 
		
		//设置发送数据
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
		
		//设置RETURN方式
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		
		//抓取URL并把它传递给浏览器
		$output = curl_exec($ch);
		
		//关闭curl资源，并且释放系统资源
		curl_close($ch);
		
		echo $output;
		
?>