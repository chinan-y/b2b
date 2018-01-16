<?php

@header("Content-type: text/html; charset=utf-8");


defined('GcWebShop') or exit('Access Invalid!');



class bocomm{

	/**
	 * 交通银行网关
	 * @var string
	 */

	private $gateway   = '';

	/**
	 * 支付接口标识
	 * @var string
	 */
    private $code      = 'bocomm';

    /**
	 * 支付接口配置信息
	 * @var array
	 */
    private $payment;

     /**
	 * 订单信息
	 * @var array
	 */
    private $order;

    /**
	 * 发送至交通银行的参数
	 * @var array
	 */
    private $parameter;

    /**
     * 订单类型 product_buy商品购买,predeposit预存款充值
     * @var unknown
     */
    //private $order_type;
    /**
     * 支付状态
     * @var unknown
     */
    private $pay_result;

    

    public function __construct($payment_info,$order_info){
    	$this->bocomm($payment_info,$order_info);
    	//交通银行银行支付配置
		$this->pay_url     		= "https://mapi.95559.com.cn/mapi/pay.htm";//生产环境
		$this->MerCertID     	= "301500053119990";//协议号
		$this->MerPtcId     	= "301500053119990";//一级商户协议号
		$this->socket_url     	= "tcp://127.0.0.1:8891";//socket服务URL
		$this->return_url     	= SHOP_SITE_URL.'/api/payment/bocomm/return_url.php';//回调地址
		$this->notify_url     	= SHOP_SITE_URL.'/api/payment/bocomm/notify_url.php';//通知地址
    }

    public function bocomm($payment_info = array(),$order_info = array()){
    	if(!empty($payment_info) and !empty($order_info)){
    		$this->payment	= $payment_info;
    		$this->order	= $order_info;

    	}

    }

		/**
		 * 支付表单
		 */

	public function submit(){
		//取得回调地址
		$this->getNotice();
		$TranCode='BPAYPY4101';    
		$TranDate=date('Ymd', time());   
		$TranTime=date('His', time());   
		$MerOrderNo=$this->order['pay_sn']; 
		$BuyerId='';    
		$BuyerName='';  
		$SellerId='';   
		$SellerName=''; 
		$GoodsName='glass';  
		$GoodsTxt='glass';   
		$GoodsDesc='glass';  
		$TranModeId='D'; 
		$TranAmt=number_format($this->order['api_pay_amount'], 2, '.', '');      
		$TranCry='CNY';    
		//$ChannelApi='3010001010';
		$ChannelApi='0000001010';  //跨行b2c
		$ChannelInst='netpay';
		
		$MerTranSerialNo=$this->order['pay_sn'];
		
		
		$SafeReserved='';
		$SubMerPtcId='';
		$BuyerMemo='';

		//取得关区号、商品货款金额和税额
		$store_id = intval($this->order['order_list'][0]['store_id']);
		$customs_id= Model()->table('region')->getfby_store_id($store_id,'customs_id');
		$order_amount = number_format($this->order['order_list'][0]['order_amount'],2);
		$order_tax = number_format($this->order['order_list'][0]['order_tax'],2);
		
		$SellerMemo=$customs_id.'+I20+'.$order_amount.'+'.$order_tax.'+';
		$PlatMemo='HG+50052602G5+光彩国际重庆电子商务有限公司+';
		$PayMemo='';
		//连接地址
		$fp = stream_socket_client($this->socket_url, $errno, $errstr, 30);

		$retMsg="";
		if (!$fp) {
			echo "$errstr ($errno)<br />\n";
		} else {
			$in  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Document><Head>";
			$in .= "<MerPtcId>".$this->MerPtcId."</MerPtcId>";
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
			$in  = "0000010XML".$this->MerCertID.$in;

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
			//echo $signData.'<br>';
		}



        /* 交易参数 */

        $parameter = array(

            'signData' => $signData,

        );

        $button  = '<html><head></head><body><form action="'.$this->pay_url.'" target="_parent" method="post" name="E_FORM">';

        foreach ($parameter AS $key=>$val)

        {

            $button  .= '<input type="hidden" name="'.$key.'" value="'.$val.'" />';

        }

        $button  .= ' </form><script type="text/javascript">document.E_FORM.submit();</script>';

		$button .= '</body></html>';

		echo $button;

		exit;       

	}


	/**
	 * [发起订单通知，调用4020接口]
	 * @author fulijun
	 * @dateTime 2016-08-06T16:15:26+0800
	 * @return   [type]                   [description]
	 */
	public  function getNotice(){
	    //head报文
		$TranCode='BPAYPY4020';   
		$TranDate=date('Ymd', time());   
		$TranTime=date('His', time()); 

		//连接地址
		$fp = stream_socket_client($this->socket_url, $errno, $errstr, 30);
		$retMsg="";
		if (!$fp) {
			echo "$errstr ($errno)<br />\n";
		} else {
			$in  = "0000030XML".$this->MerCertID;
			$in .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Document><Head>";
			$in .= "<MerPtcId>".$this->MerPtcId."</MerPtcId>";
			$in .= "<TranTime>".$TranTime."</TranTime>";
			$in .= "<TranCode>".$TranCode."</TranCode>";
			$in .= "<TranDate>".$TranDate."</TranDate></Head>";
			$in .= "<Body>";
			$in .= "<ReturnURL>".$this->return_url."</ReturnURL>";
			$in .= "<NotifyURL>".$this->notify_url."</NotifyURL>";
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

	}


	/**
	 * 返回地址验证(同步)
	 * @param 
	 * @return boolean
	 */
	public function return_verify(){		

        if($_GET['extra_common_param']){

			// $this->order_type = $_GET['extra_common_param'];

			 $this->pay_result = true;            

			return true;
		}
        else
        {
            return false;
        }
	}
	
	/**
	 * 返回地址验证(异步)
	 * @return boolean
	 */
	public function notify_verify() {
		return $this->return_verify();
	}



	/**

	 * 取得订单支付状态，成功或失败

	 *

	 * @param array $param

	 * @return array

	 */

	public function getPayResult($param){

	    return $this->pay_result;

	}



	public function __get($name){

	    return $this->$name;

	}

	function logstr($orderid,$str,$hmac)

	{

		$logName="log.txt";

		$james=fopen($logName,"a+");

		fwrite($james,"\r\n".date("Y-m-d H:i:s")."|orderid[".$orderid."]|str[".$str."]|hmac[".$hmac."]");

		fclose($james);

	}

}

