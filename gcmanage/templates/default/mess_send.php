<?php

date_default_timezone_get("Etc/GMT+8");

//从URL获取发送消息类型参数
isset($_REQUEST['MessageType']) or die('E01');
$MessageType = $_REQUEST['MessageType'];

//操作类型必须在许可范围
$AllowType = array(
    'SKU_INFO',         //商品备案
    'PAYMENT_INFO',     //支付单
    'ORDER_INFO',       //订单
    'ORDER_RETURN_INFO',    //订单退货
	'BILL_INFO',		//运单
);

if(!in_array($MessageType,$AllowType) ) die('E02');

//验证通过后引入基础文件
include(__DIR__.'/function.php');
//include(__DIR__.'/common.php');
	
class BuildXML {
	
    private $MessageTime;		//消息时间
    private $MessageId;		//消息ID
	private $Password;			//密码
	private $UserNo;			//电商用户名
	private $APIURL;			//报文发送地址
	private $ESHOP_ENT_NAME;	//电商企业名称
	private $ReceiverId;		//接收者ID
	
    function __construct(){

		$this->MessageTime  = date( 'Y-m-d H:i:s', time() );
        $this->MessageId    = guid();
        $this->Password     = md5( $this->MessageId.$_POST['mess_Password'] );
		$this->UserNo		= $_POST['mess_UserNo'];
		$this->APIURL		= $_POST['mess_APIURL'];
		$this->ESHOP_ENT_NAME	=$_POST['mess_ESHOP_ENT_NAME'];
		$this->ReceiverId	=$_POST['mess_ReceiverId'];
    }
	
    /*构建XML 商品备案 */
    function SKU_INFO(){
		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<DTC_Message>
			<MessageHead>
				<MessageType>'.__FUNCTION__.'</MessageType>
				<MessageId>'.$this->MessageId.'</MessageId>
				<ActionType>1</ActionType>
				<MessageTime>'.$this->MessageTime.'</MessageTime>
				<SenderId>'.$this->UserNo.'</SenderId>
				<ReceiverId>CQITC</ReceiverId>
				<UserNo>'.$this->UserNo.'</UserNo>
				<Password>'.$this->Password.'</Password>
			</MessageHead>
			<MessageBody>
				<DTCFlow>
					<SKU_INFO>
						<ESHOP_ENT_CODE>'.$this->UserNo.'</ESHOP_ENT_CODE>
						<ESHOP_ENT_NAME>'.$this->ESHOP_ENT_NAME.'</ESHOP_ENT_NAME>
						<SKU>'.$_POST['SKU'].'</SKU>
						<GOODS_NAME>'.$_POST['GOODS_NAME'].'</GOODS_NAME>
						<GOODS_SPEC>'.$_POST['GOODS_SPEC'].'</GOODS_SPEC>
						<DECLARE_UNIT>'.$_POST['DECLARE_UNIT'].'</DECLARE_UNIT>
						<POST_TAX_NO>'.$_POST['POST_TAX_NO'].'</POST_TAX_NO>
						<LEGAL_UNIT>'.$_POST['LEGAL_UNIT'].'</LEGAL_UNIT>
						<CONV_LEGAL_UNIT_NUM>'.$_POST['CONV_LEGAL_UNIT_NUM'].'</CONV_LEGAL_UNIT_NUM>
						<HS_CODE>'.$_POST['HS_CODE'].'</HS_CODE>
						<IN_AREA_UNIT>'.$_POST['IN_AREA_UNIT'].'</IN_AREA_UNIT>
						<CONV_IN_AREA_UNIT_NUM>'.$_POST['CONV_IN_AREA_UNIT_NUM'].'</CONV_IN_AREA_UNIT_NUM>
						<IS_EXPERIMENT_GOODS>'.$_POST['IS_EXPERIMENT_GOODS'].'</IS_EXPERIMENT_GOODS>
					</SKU_INFO>
				</DTCFlow>
			</MessageBody>
		</DTC_Message>
	';
		return $xml;
	}

    /*构建XML 支付单 */
    function PAYMENT_INFO(){
		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<DTC_Message>
			<MessageHead>
				<MessageType>'.__FUNCTION__.'</MessageType>
				<MessageId>'.$this->MessageId.'</MessageId>
				<ActionType>1</ActionType>
				<MessageTime>'.$this->MessageTime.'</MessageTime>
				<SenderId>'.$this->UserNo.'</SenderId>
				<ReceiverId>CQITC</ReceiverId>
				<UserNo>'.$this->UserNo.'</UserNo>
				<Password>'.$this->Password.'</Password>
			</MessageHead>
			<MessageBody>
				<DTCFlow>
					<PAYMENT_INFO>
						<CUSTOMS_CODE>8012</CUSTOMS_CODE>
						<BIZ_TYPE_CODE>'.$_POST['BIZ_TYPE_CODE'].'</BIZ_TYPE_CODE>
						<ESHOP_ENT_CODE>'.$this->UserNo.'</ESHOP_ENT_CODE>
                        <ESHOP_ENT_NAME>'.$this->ESHOP_ENT_NAME.'</ESHOP_ENT_NAME>
                        <PAYMENT_ENT_CODE>'.$this->UserNo.'</PAYMENT_ENT_CODE>
                        <PAYMENT_ENT_NAME>'.$this->ESHOP_ENT_NAME.'</PAYMENT_ENT_NAME>
                        <PAYMENT_NO>'.$_POST['PAYMENT_NO'].'</PAYMENT_NO>
                        <ORIGINAL_ORDER_NO>'.$_POST['ORIGINAL_ORDER_NO'].'</ORIGINAL_ORDER_NO>
                        <PAY_AMOUNT>'.$_POST['PAY_AMOUNT'].'</PAY_AMOUNT>
                        <GOODS_FEE>'.$_POST['GOODS_FEE'].'</GOODS_FEE>
                        <TAX_FEE>'.$_POST['TAX_FEE'].'</TAX_FEE>
                        <CURRENCY_CODE>142</CURRENCY_CODE>
                        <MEMO />
					</PAYMENT_INFO>
				</DTCFlow>
			</MessageBody>
		</DTC_Message>
	';
		return $xml;
	}
	
    /*构建XML 订单 */
    function ORDER_INFO(){
        $xml = '<?xml version="1.0" encoding="utf-8"?>
			<DTC_Message>
              <MessageHead>
				<MessageType>'.__FUNCTION__.'</MessageType>
				<MessageId>'.$this->MessageId.'</MessageId>
				<ActionType>1</ActionType>
				<MessageTime>'.$this->MessageTime.'</MessageTime>
				<SenderId>'.$this->UserNo.'</SenderId>
				<ReceiverId>CQITC</ReceiverId>
				<UserNo>'.$this->UserNo.'</UserNo>
				<Password>'.$this->Password.'</Password>
			</MessageHead>
              <MessageBody>
                <DTCFlow>
                  <ORDER_HEAD>
                    <CUSTOMS_CODE>'.$_POST['CUSTOMS_CODE'].'</CUSTOMS_CODE>
                    <BIZ_TYPE_CODE>'.$_POST['BIZ_TYPE_CODE'].'</BIZ_TYPE_CODE>
                    <ORIGINAL_ORDER_NO>'.$_POST['ORIGINAL_ORDER_NO'].'</ORIGINAL_ORDER_NO>
                    <ESHOP_ENT_CODE>'.$this->UserNo.'</ESHOP_ENT_CODE>
                    <ESHOP_ENT_NAME>'.$this->ESHOP_ENT_NAME.'</ESHOP_ENT_NAME>
                    <DESP_ARRI_COUNTRY_CODE>'.$_POST['DESP_ARRI_COUNTRY_CODE'].'</DESP_ARRI_COUNTRY_CODE>
                    <SHIP_TOOL_CODE>'.$_POST['SHIP_TOOL_CODE'].'</SHIP_TOOL_CODE>
                    <RECEIVER_ID_NO>'.$_POST['RECEIVER_ID_NO'].'</RECEIVER_ID_NO>
                    <RECEIVER_NAME>'.$_POST['RECEIVER_NAME'].'</RECEIVER_NAME>
                    <RECEIVER_ADDRESS>'.$_POST['RECEIVER_ADDRESS'].'</RECEIVER_ADDRESS>
                    <RECEIVER_TEL>'.$_POST['RECEIVER_TEL'].'</RECEIVER_TEL>
                    <GOODS_FEE>'.$_POST['GOODS_FEE'].'</GOODS_FEE>
                    <TAX_FEE>'.$_POST['TAX_FEE'].'</TAX_FEE>
                    <GROSS_WEIGHT>'.$_POST['GROSS_WEIGHT'].'</GROSS_WEIGHT>
                    <SORTLINE_ID>'.$_POST['SORTLINE_ID'].'</SORTLINE_ID>
                    <ORDER_DETAIL>
                      <SKU>'.$_POST['SKU'].'</SKU>
                      <GOODS_SPEC>'.$_POST['GOODS_SPEC'].'</GOODS_SPEC>
                      <CURRENCY_CODE>142</CURRENCY_CODE>
                      <PRICE>'.$_POST['PRICE'].'</PRICE>
                      <QTY>'.$_POST['QTY'].'</QTY>
                      <GOODS_FEE>'.$_POST['GOODS_FEE'].'</GOODS_FEE>
                      <TAX_FEE>'.$_POST['TAX_FEE'].'</TAX_FEE>
                    </ORDER_DETAIL>
                  </ORDER_HEAD>
                </DTCFlow>
              </MessageBody>
            </DTC_Message>';
        return $xml;
    }
	
    /*构建XML 订单退货 */
    function ORDER_RETURN_INFO(){
        $xml = '<?xml version="1.0" encoding="utf-8"?>
		<DTC_Message>
			<MessageHead>
				<MessageType>'.__FUNCTION__.'</MessageType>
				<MessageId>'.$this->MessageId.'</MessageId>
				<ActionType>1</ActionType>
				<MessageTime>'.$this->MessageTime.'</MessageTime>
				<SenderId>'.$this->UserNo.'</SenderId>
				<ReceiverId>CQITC</ReceiverId>
				<UserNo>'.$this->UserNo.'</UserNo>
				<Password>'.$this->Password.'</Password>
			</MessageHead>
			<MessageBody>
				<DTCFlow>
					<ORDER_RETURN_INFO>
                        <ORIGINAL_ORDER_NO>'.$_POST['ORIGINAL_ORDER_NO'].'</ORIGINAL_ORDER_NO>
                        <ESHOP_ENT_CODE>'.$this->ESHOP_ENT_CODE.'</ESHOP_ENT_CODE>
                        <RETURN_REASON>多拍/拍错/不想要</RETURN_REASON>
                        <QUALITY_REPORT />
                     </ORDER_RETURN_INFO>
                </DTCFlow>
            </MessageBody>
        </DTC_Message>';
    return $xml;
	}
	
    /*构建XML 运单 */
    function BILL_INFO(){
        $xml = '<?xml version="1.0" encoding="utf-8"?>
		<DTC_Message>
			<MessageHead>
				<MessageType>'.__FUNCTION__.'</MessageType>
				<MessageId>'.$this->MessageId.'</MessageId>
                <ActionType>1</ActionType>
                <MessageTime>'.$this->MessageTime.'</MessageTime>
                <SenderId>'.$this->UserNo.'</SenderId>
                <ReceiverId>CQITC</ReceiverId>
                <UserNo>'.$this->UserNo.'</UserNo>
                <Password>'.$this->Password.'</Password>
            </MessageHead>
			<MessageBody>
				<DTCFlow>
					<BILL_INFO>
                        <ORIGINAL_ORDER_NO>'.$_POST['ORIGINAL_ORDER_NO'].'</ORIGINAL_ORDER_NO>
						<BIZ_TYPE_CODE>'.$_POST['BIZ_TYPE_CODE'].'</BIZ_TYPE_CODE>
						<BIZ_TYPE_NAME>'.$_POST['BIZ_TYPE_NAME'].'</BIZ_TYPE_NAME>
						<ESHOP_ENT_CODE>'.UserNo.'</ESHOP_ENT_CODE>
						<ESHOP_ENT_NAME>'.$this->ESHOP_ENT_NAME.'</ESHOP_ENT_NAME>
						<CUSTOMS_CODE>'.$_POST['CUSTOMS_CODE'].'</CUSTOMS_CODE>
						<CUSTOMS_NAME>'.$_POST['CUSTOMS_NAME'].'</CUSTOMS_NAME>
						<LOGISTICS_ENT_CODE>'.$_POST['LOGISTICS_ENT_CODE'].'</LOGISTICS_ENT_CODE>
						<LOGISTICS_ENT_NAME>'.$_POST['LOGISTICS_ENT_NAME'].'</LOGISTICS_ENT_NAME>
						<QTY>'.$_POST['QTY'].'</QTY>
						<RECEIVER_ID_NO>'.$_POST['RECEIVER_ID_NO'].'</RECEIVER_ID_NO>
						<RECEIVER_NAME>'.$_POST['RECEIVER_NAME'].'</RECEIVER_NAME>
						<RECEIVER_ADDRESS>'.$_POST['RECEIVER_ADDRESS'].'</RECEIVER_ADDRESS>
						<RECEIVER_TEL>'.$_POST['RECEIVER_TEL'].'</RECEIVER_TEL>
                     </BILL_INFO>
                </DTCFlow>
            </MessageBody>
        </DTC_Message>';
    return $xml;
	}
	
	

}	//BuildXML OVER
		
	//实例化xml数据
	$BuildXML = new BuildXML();
	$xml = $BuildXML->$MessageType();
	echo $xml;
	
	//base64加密
	$data = base64_encode($xml);
		
	//发送数据
	$res = _curl_post( APIURL, array('data'=>$data) );
		
	//输出res以回执
	header("Content-type: text/html; charset=utf-8");
	echo  $res;
