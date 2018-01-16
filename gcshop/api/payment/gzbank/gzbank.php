<?php
/**
 * 贵州银行在线接口类
 *
 * 
 */
defined('GcWebShop') or exit('Access Invalid!');
use \RobRichards\XMLSecLibs\XMLSecurityDSig;
use \RobRichards\XMLSecLibs\XMLSecurityKey;
use \RobRichards\XMLSecLibs\XMLSecEnc;
class gzbank{
	private $payment_info;
	private $order;
	private $mid='584715053990180'; 
	// private $mid='584703050940482'; //测试
	private $url='https://ebank.bgzchina.com/paygate/agreement';
	// private $url='http://222.85.178.210:8081/paygate/agreement'; //测试
	public function __construct($payment_info=array(),$order=array()){
		require '/xmlseclibs/xmlseclibs.php';
		if(!empty($payment_info) && !empty($order)){
			$this->payment_info=$payment_info;
			$this->order=$order;
		}
	}
	/**
	 * 申请绑定银行卡
	 *
	 */
	public function GZBind($post){
		$id = $this->createId();
		$txSNBinding=sprintf('%03d', rand(0, 999)).sprintf('%03d', rand(0, 999)).sprintf('%03d', rand(0, 999)).sprintf('%03d', rand(0, 999));
		
		$notify_url = SHOP_SITE_URL."/api/payment/gzbank/return_url.php";
		//构建原始XML
		$messageXML  = "<AgreementFinance>";
		$messageXML .= "<Message id=\"".$id."\">";
		$messageXML .= "<CSBSMSReq id=\"CSBSMSReq\">";
		$messageXML .= "<version>6.0.1</version>";
		$messageXML .= "<instId>Agreement</instId>";
		$messageXML .= "<mid>".$this->mid."</mid>";
		$messageXML .= "<merchantName>光彩国际</merchantName>";
		$messageXML .= "<merchanturl>".$notify_url."</merchanturl>";
		$messageXML .= "<txSNBinding>".$txSNBinding."</txSNBinding>";
		$messageXML .= "<bankID>".$post['cardName']."</bankID>";
		$messageXML .= "<bankCardType>".$post['bankCardType']."</bankCardType>";
		$messageXML .= "<bankCardNo>".$post['bankCard']."</bankCardNo>";
		$messageXML .= "<mobilePhone>".$post['mobile']."</mobilePhone>";
		$messageXML .= "<accountName>".$post['chinaName']."</accountName>";
		$messageXML .= "<certType>".$post['codeType']."</certType>";
		$messageXML .= "<certNo>".$post['idCard']."</certNo>";
		$messageXML .= "<cvn2>".$post['cnv2']."</cvn2>";
		$messageXML .= "<validDate>".$post['validDate']."</validDate>";
		$messageXML .= "<date>".date('Ymd H:i:s',time())."</date>";
		$messageXML .= "</CSBSMSReq>";
		$messageXML .= "</Message>";
		$messageXML .= "</AgreementFinance>";
		//签名
		$requestXml=$this->Gsign('CSBSMSReq',$messageXML);
		//请求				
		$opts = array('http' =>
		  array(
			'method'  => 'POST',
			'header'  => "Content-Type: text/xml\r\n",
			'content' => $requestXml,
			'timeout' => 60
		  )
		);                        
		$context  = stream_context_create($opts);
		$url = $this->url;
		$respondXml = file_get_contents($url, false, $context, -1, 40000);
		$result=$this->attestation('CSBSMSRes',$respondXml);
		$doc=new DOMDocument();
		$doc->loadXML($respondXml);
		switch($result){
			case 1  :
			if(!empty($doc->getElementsByTagName('CSBSMSRes')->item(0))){
				//存入数据
				$insert=array();
				$insert['memberId']      = $_SESSION['member_id'];
				$insert['mobile']        = $doc->getElementsByTagName('mobilePhone')->item(0)->nodeValue;
				$insert['chinaName']     = $doc->getElementsByTagName('accountName')->item(0)->nodeValue;
				$insert['cardBankId']    = $doc->getElementsByTagName('bankID')->item(0)->nodeValue;
				$insert['cardNo']        = $doc->getElementsByTagName('bankCardNo')->item(0)->nodeValue;
				$insert['certificate']   = $doc->getElementsByTagName('certType')->item(0)->nodeValue;
				$insert['certificateNo'] = $doc->getElementsByTagName('certNo')->item(0)->nodeValue;
				$insert['txsNBinding']   = $doc->getElementsByTagName('txSNBinding')->item(0)->nodeValue;
				$insert['cnv2']          = $doc->getElementsByTagName('cvn2')->item(0)->nodeValue;
				$insert['validDate']     = $doc->getElementsByTagName('validDate')->item(0)->nodeValue;
				Model()->table('gzbankinfo')->insert($insert);
				return array('state'=>200,'message'=>$doc->getElementsByTagName('txSNBinding')->item(0)->nodeValue);
			}else{
				return array('state'=>400,'message'=>$doc->getElementsByTagName('errorMessage')->item(0)->nodeValue);
			}
			case 0  :
			return array('state'=>400,'message'=>'验证不成功，绑定失败');
			case -1 :
			return array('state'=>400,'message'=>'交易发生未知错误，绑定失败');
		}
	}
	
	/*
	*
	*提交绑定银行卡接口
	*
	*/
	public function GZBindTwo($post){
		$id = $this->createId();
		//构建原始XML
		$messageXML  = "<AgreementFinance>";
		$messageXML .= "<Message id=\"".$id."\">";
		$messageXML .= "<CSBReq id=\"CSBReq\">";
		$messageXML .= "<version>6.0.1</version>";
		$messageXML .= "<instId>Agreement</instId>";
		$messageXML .= "<mid>".$this->mid."</mid>";
		$messageXML .= "<txSNBinding>".$post['txSNBinding']."</txSNBinding>";
		$messageXML .= "<smsValidationCode>".$post['code']."</smsValidationCode>";
		$messageXML .= "</CSBReq>";
		$messageXML .= "</Message>";
		$messageXML .= "</AgreementFinance>";
		//签名
		$requestXml=$this->Gsign('CSBReq',$messageXML);
		//请求				
		$opts = array('http' =>
		  array(
			'method'  => 'POST',
			'header'  => "Content-Type: text/xml\r\n",
			'content' => $requestXml,
			'timeout' => 60
		  )
		);                        
		$context  = stream_context_create($opts);
		$url = $this->url;
		$respondXml = file_get_contents($url, false, $context, -1, 40000);
		$result=$this->attestation('CSBRes',$respondXml);
		$doc=new DOMDocument();
		$doc->loadXML($respondXml);
		switch($result){
			case 1  :
			if(!empty($doc->getElementsByTagName('CSBRes')->item(0))){
				$a=Model()->table('gzbankinfo')->where(array('txsNBinding'=>$post['txSNBinding']))->update(array('success'=>1));
				return array('state'=>200,'message'=>$doc->getElementsByTagName('txSNBinding')->item(0)->nodeValue);
			}else{
				return array('state'=>400,'message'=>$doc->getElementsByTagName('errorMessage')->item(0)->nodeValue);
			}
			case 0  :
			return array('state'=>400,'message'=>'验证不成功，绑定失败');
			case -1 :
			return array('state'=>400,'message'=>'交易发生未知错误，绑定失败');
		}
	}
	
	/**
	 * 发起支付 
	 *
	 */
	public	function submit(){
		$id = $this->createId();
		$serialNo=$this->order['pay_sn'].sprintf('%d', rand(10, 99));
		$cardNo=$this->payment_info['cardNo'];
		$txSNBinding=Model()->table('gzbankinfo')->where(array('cardNo'=>$cardNo,'success'=>1))->field('txsNBinding')->find();
		if(empty($txSNBinding)){
			return array('state'=>400,'message'=>'数据错误，请重新输入');
		}
		$messageXML  = "<AgreementFinance>";
		$messageXML .= "<Message id=\"".$id."\">";
		$messageXML .= "<CPSMSReq id=\"CPSMSReq\">";
		$messageXML .= "<version>6.0.1</version>";
		$messageXML .= "<instId>Agreement</instId>";
		$messageXML .= "<mid>".$this->mid."</mid>";
		$messageXML .= "<txSNBinding>".$txSNBinding['txsNBinding']."</txSNBinding>";
		$messageXML .= "<serialNo>".$serialNo."</serialNo>";
		$messageXML .= "<currency>156</currency>";
		$messageXML .= "<amount>".$this->order['api_pay_amount']."</amount>";
		$messageXML .= "<date>".date('Ymd H:i:s',time())."</date>";
		$messageXML .= "</CPSMSReq>";
		$messageXML .= "</Message>";
		$messageXML .= "</AgreementFinance>";
		$requestXml=$this->Gsign('CPSMSReq',$messageXML);
		$opts = array('http' =>
		  array(
			'method'  => 'POST',
			'header'  => "Content-Type: text/xml\r\n",
			'content' => $requestXml,
			'timeout' => 60
		  )
		);                        
		$context  = stream_context_create($opts);
		$url = $this->url;
		$respondXml = file_get_contents($url, false, $context, -1, 40000);
		$result=$this->attestation('CPSMSRes',$respondXml);
		$doc=new DOMDocument();
		$doc->loadXML($respondXml);
		switch($result){
			case 1 :
			if(!empty($doc->getElementsByTagName('CPSMSRes')->item(0))){
				$re = Model()->table('order')->where(array('pay_sn'=>$this->order['pay_sn']))->update(array('gzSerialNo'=>$serialNo));
				return array('state'=>200,'message'=>'发起支付成功，你可以继续交易啦！');
			}else{
				return array('state'=>400,'message'=>$doc->getElementsByTagName('errorMessage')->item(0)->nodeValue);
			}
			case 0 :
			return array('state'=>400,'message'=>'验证失败');
			case -1:
			return array('state'=>400,'message'=>'交易时发生未知错误，终止交易');
		}
	}
	
	/**
	 * 支付表单。。。实际支付
	 *
	 */
	public	function pay($post){
		$payment_logic=Logic('payment');
		$id = $this->createId();
		$bankID=Model()->table('gzbankinfo')->where(array('cardNo'=>$post['cardNo']))->field('cardBankId')->find();
		$serialNo=Model()->table('order')->where(array('pay_sn'=>$post['pay_sn']))->field('gzSerialNo')->find();
		if(empty($serialNo)){
			return array('state'=>400,'message'=>'数据错误，请重新输入');
		}
		$messageXML  = "<AgreementFinance>";
		$messageXML .= "<Message id=\"".$id."\">";
		$messageXML .= "<CPReq id=\"CPReq\">";
		$messageXML .= "<version>6.0.1</version>";
		$messageXML .= "<instId>Agreement</instId>";
		$messageXML .= "<mid>".$this->mid."</mid>";
		$messageXML .= "<smsValidationCode>".$post['code']."</smsValidationCode>";
		$messageXML .= "<serialNo>".$serialNo['gzSerialNo']."</serialNo>";
		$messageXML .= "</CPReq>";
		$messageXML .= "</Message>";
		$messageXML .= "</AgreementFinance>";
		$requestXml=$this->Gsign('CPReq',$messageXML);
		//请求				
		$opts = array('http' =>
		  array(
			'method'  => 'POST',
			'header'  => "Content-Type: text/xml\r\n",
			'content' => $requestXml,
			'timeout' => 60
		  )
		);                        
		$context  = stream_context_create($opts);
		$url = $this->url;
		$respondXml = file_get_contents($url, false, $context, -1, 40000);
		$result=$this->attestation('CPRes',$respondXml);
		$doc=new DOMDocument();
		$doc->loadXML($respondXml);
		switch($result){
			case 1 :
			if(!empty($doc->getElementsByTagName('CPRes')->item(0)) && $doc->getElementsByTagName('result')->item(0)->nodeValue=='000000'){
				Model()->table('order')->where(array('pay_sn'=>$post['pay_sn']))->update(array('checkSerialNo'=>$serialNo['gzSerialNo']));
				if($bankID['cardBankId']=='1569'){
					$returnPost=$payment_logic->returnGzBank($post['pay_sn']);
					$discount=$this->SRReq($returnPost,true);
					
					if($discount['state']==200){
						$logArray['lg_member_id']=$_SESSION['member_id'];
						$logArray['lg_member_name']=$_SESSION['member_name'];
						$logArray['lg_type']='cash_pay';
						$logArray['lg_av_amount']=$returnPost['amount'];
						$logArray['lg_add_time']=time();
						$logArray['lg_desc']='贵州银行卡打折返现';
						$a=Model()->table('pd_log')->insert($logArray);
					}
				}
				return array('state'=>200,'message'=>'交易成功');
			}else{
				return array('state'=>400,'message'=>$doc->getElementsByTagName('errorMessage')->item(0)->nodeValue);
			}
			case 0 :
			return array('state'=>400,'message'=>'验证失败');
			case -1:
			return array('state'=>400,'message'=>'交易时发生未知错误，终止交易');
		}
	}
	
	/*
	* STQReq 单笔订单查询 
	* @parms
	* return XML
	*/
	public function STQReq($post){
		$id = $this->createId();
		$serialNo=$post['pay_sn'].sprintf('%d', rand(10, 99));
		$check=Model()->table('order')->where(array('pay_sn'=>$post['pay_sn']))->field('gzSerialNo')->find();
		$messageXML  = "<AgreementFinance>";
		$messageXML .= "<Message id=\"".$id."\">";
		$messageXML .= "<STQReq id=\"STQReq\">";
		$messageXML .= "<version>6.0.1</version>";
		$messageXML .= "<instId>Agreement</instId>";
		$messageXML .= "<mid>".$this->mid."</mid>";
		$messageXML .= "<merchantName>光彩国际</merchantName>";
		$messageXML .= "<date>".date('Ymd H:i:s',time())."</date>";
		$messageXML .= "<serialNo>".$serialNo."</serialNo>";
		$messageXML .= "<checkSerialNo>".$check['gzSerialNo']."</checkSerialNo>";
		$messageXML .= "</STQReq>";
		$messageXML .= "</Message>";
		$messageXML .= "</AgreementFinance>";
		$requestXml=$this->Gsign('STQReq',$messageXML);
		$opts = array('http' =>
		  array(
			'method'  => 'POST',
			'header'  => "Content-Type: text/xml\r\n",
			'content' => $requestXml,
			'timeout' => 60
		  )
		);
		$context  = stream_context_create($opts);
		$url = $this->url;
		$respondXml = file_get_contents($url, false, $context, -1, 40000);
		file_put_contents('E:pay.txt',$respondXml);
		$result=$this->attestation('STQRes',$respondXml);
		$doc=new DOMDocument();
		$doc->loadXML($respondXml);
		switch($result){
			case 1 :
			if(!empty($doc->getElementsByTagName('STQRes')->item(0)) && $doc->getElementsByTagName('retCode')->item(0)->nodeValue=='000000'){
				$array=rcache('refundArray','refund');
				if(!empty($array[$post['pay_sn']])){
					unset($array[$post['pay_sn']]);
					wcache('refundArray',$array,'refund');
				}
			}
			elseif(!empty($doc->getElementsByTagName('STQRes')->item(0)) && $doc->getElementsByTagName('retCode')->item(0)->nodeValue=='S00000'){
				$array=rcache('refundArray','refund');
				if(empty($array[$post['pay_sn']])){
					$refund=array($post['pay_sn']=>serialize(array('pay_sn'=>$post['pay_sn'],'checkSerialNo'=>$post['checkSerialNo'])));
					wcache('refundArray',$refund,'refund');
				}
				
			}
			elseif(!empty($doc->getElementsByTagName('STQRes')->item(0)) && $doc->getElementsByTagName('retCode')->item(0)->nodeValue=='S00024'){
				$array=rcache('refundArray','refund');
				if(empty($array[$post['pay_sn']])){
					$refund=array($post['pay_sn']=>serialize(array('pay_sn'=>$post['pay_sn'],'checkSerialNo'=>$post['checkSerialNo'])));
					wcache('refundArray',$refund,'refund');
				}
			}
			else{
				return array('state'=>400,'message'=>$doc->getElementsByTagName('errorMessage')->item(0)->nodeValue);
			}
			break;
			case 0 :
			return array('state'=>400,'message'=>'验证失败');
			case -1:
			return array('state'=>400,'message'=>'交易时发生未知错误，终止交易');
		}
	}
	
	/*
	* SRReq 退款 
	* @parms array($serialNo,$originalSerialNo,$originalDate,$amount,$currency)
	* return state
	*/
	public function SRReq($post,$discount=false){
		$id = $this->createId();
		$orderInfo=Model()->table('order')->where(array('pay_sn'=>$post['pay_sn']))->field('payment_time,gzSerialNo')->find();
		if(empty($orderInfo)){
			return array('state'=>400,'message'=>'数据错误，请重新输入');
		}
		$messageXML  = "<AgreementFinance>";
		$messageXML .= "<Message id=\"".$id."\">";
		$messageXML .= "<SRReq id=\"SRReq\">";
		$messageXML .= "<version>6.0.1</version>";
		$messageXML .= "<instId>Agreement</instId>";
		$messageXML .= "<mid>".$this->mid."</mid>";
		$messageXML .= "<date>".date('Ymd H:i:s',time())."</date>";
		$messageXML .= "<serialNo>".$orderInfo['gzSerialNo']."</serialNo>";
		$messageXML .= "<originalSerialNo>".$orderInfo['gzSerialNo']."</originalSerialNo>";
		$messageXML .= "<originalDate>".date('Ymd',$orderInfo['payment_time'])."</originalDate>";
		$messageXML .= "<amount>".$post['amount']."</amount>";
		$messageXML .= "<currency>156</currency>";
		$messageXML .= "</SRReq>";
		$messageXML .= "</Message>";
		$messageXML .= "</AgreementFinance>";
		$requestXml=$this->Gsign('SRReq',$messageXML);
		$opts = array('http' =>
		  array(
			'method'  => 'POST',
			'header'  => "Content-Type: text/xml\r\n",
			'content' => $requestXml,
			'timeout' => 60
		  )
		);
		$context  = stream_context_create($opts);
		$url = $this->url;
		$respondXml = file_get_contents($url, false, $context, -1, 40000);
		file_put_contents('E:log.txt',$respondXml);
		$result=$this->attestation('SRRes',$respondXml);
		$doc=new DOMDocument();
		$doc->loadXML($respondXml);
		switch($result){
			case 1 :
			if(!empty($doc->getElementsByTagName('SRRes')->item(0)) && $doc->getElementsByTagName('result')->item(0)->nodeValue=='000000'){
				Model()->table('order')->where(array('pay_sn'=>$post['pay_sn']))->update(array('refund_state'=>2,'refund_amount'=>$post['amount'],'checkSerialNo'=>$orderInfo['gzSerialNo']));
				return array('state'=>200,'message'=>'退款成功');
			}
			elseif(!empty($doc->getElementsByTagName('SRRes')->item(0)) && $doc->getElementsByTagName('result')->item(0)->nodeValue=='S00000'){
				Model()->table('order')->where(array('pay_sn'=>$post['pay_sn']))->update(array('refund_state'=>2,'refund_amount'=>$post['amount'],'checkSerialNo'=>$orderInfo['gzSerialNo']));
				return array('state'=>200,'message'=>'申请退款成功，预计一个工作日内到账');
			}
			else{
				return array('state'=>400,'message'=>$doc->getElementsByTagName('errorMessage')->item(0)->nodeValue);
			}
			case 0 :
			return array('state'=>400,'message'=>'验证失败');
			case -1:
			return array('state'=>400,'message'=>'交易时发生未知错误，终止交易');
		}
	}
	
	/*
	* attestation 
	* @parms $TagName
	* return XML
	*/
	private function attestation($TagName,$respondXml){
		$doc = new DOMDocument();
		$doc->loadXML($respondXml);
		$objXMLSecDSig = new XMLSecurityDSig();
		$objDSig = $objXMLSecDSig->locateSignature($doc);
		if (! $objDSig) {
			throw new Exception("Cannot locate Signature Node");
		}
		if(!empty($doc->getElementsByTagName($TagName)->item(0))){
			$item=$doc->getElementsByTagName($TagName)->item(0);
		}else{
			$item=$doc->getElementsByTagName('Error')->item(0);
		}
		$objXMLSecDSig->canonicalizeSignedInfo();
		$objXMLSecDSig->idKeys = array('wsu:Id');
		$objXMLSecDSig->idNS = array('wsu'=>'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd');

		$objKey = $objXMLSecDSig->locateKey();
		$objKeyInfo = XMLSecEnc::staticLocateKeyInfo($objKey, $objDSig);
		$objKey->loadKey(BASE_PATH.'/api/payment/gzbank/keys/public.pem', TRUE);

		$options['id_name'] = 'id';
		$options['overwrite'] = false;
		$objXMLSecDSig->addReference(
			$item,
			XMLSecurityDSig::SHA1, 
			array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'),
			$options
		);
		// $doc->getElementsByTagName('errorMessage')->item(0)->nodevalue
		return $objXMLSecDSig->verify($objKey);
	}
	
	private function Gsign($TagName,$xml){
		$objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type'=>'private'));
		$objKey->passphrase = '111111'; //证书密码, 如无密码， 则注释掉此行
		$objKey->loadKey(BASE_PATH.'/api/payment/gzbank/keys/private.pem', TRUE);
		$doc = new DOMDocument();
		$doc->loadXML($xml);
		$objDSig = new XMLSecurityDSig();
		$objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);
		$options['id_name'] = 'id';
		$options['overwrite'] = false;
		$objDSig->addReference(
		$doc->getElementsByTagName($TagName)->item(0),
		XMLSecurityDSig::SHA1, 
		array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'),
		$options
		);
		
		$signature=$objDSig->sign($objKey);
		$objDSig->appendSignature($doc->getElementsByTagName('Message')->item(0));
		$requestXml = $doc->saveXML();
		return $requestXml;
	}
	
	//日志
	private function log(){
		
		
	}
	
	/**
     * 创建ID号
     */
	 
    private function createId()
    {
        $id= date('y') . sprintf('%03d', date('z')) . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%03d', rand(0, 999));
        return $id;
    }

}