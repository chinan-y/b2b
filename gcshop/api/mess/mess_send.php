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
	'MES_ASK_INFO'		//入库回执模拟
	);

if(!in_array($MessageType,$AllowType) ) die('E02');


//验证通过后引入基础文件
include(__DIR__.'/common.php');
	
class BuildXML {
	
    private $MessageTime;		//消息时间
    private $MessageId;	//消息ID
	private $Password;		//密码
	
    function __construct(){

		$this->MessageTime  = date( 'Y-m-d H:i:s', time() );
        $this->MessageId    = guid();
        $this->Password     = md5( $this->MessageId.Password );
    }
	
    /*构建XML 商品备案 */
    function SKU_INFO(){
		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<DTC_Message>
			<MessageHead>
				<MessageType>'.__FUNCTION__.'</MessageType>
				<MessageId>'.$this->MessageId.'</MessageId>
				<ActionType>'.$_POST['ActionType'].'</ActionType>
				<MessageTime>'.$this->MessageTime.'</MessageTime>
				<SenderId>'.UserNo.'</SenderId>
				<ReceiverId>CQITC</ReceiverId>
				<UserNo>'.UserNo.'</UserNo>
				<Password>'.$this->Password.'</Password>
			</MessageHead>
			<MessageBody>
				<DTCFlow>
					<SKU_INFO>
						<ESHOP_ENT_CODE>'.UserNo.'</ESHOP_ENT_CODE>
						<ESHOP_ENT_NAME>'.ESHOP_ENT_NAME.'</ESHOP_ENT_NAME>
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
				<ActionType>'.$_POST['ActionType'].'</ActionType>
				<MessageTime>'.$this->MessageTime.'</MessageTime>
				<SenderId>'.UserNo.'</SenderId>
				<ReceiverId>CQITC</ReceiverId>
				<SenderAddress/>
				<ReceiverAddress/>
				<PlatFormNo/>
				<CustomCode/>
				<SeqNo/>
				<Note/>
				<UserNo>'.UserNo.'</UserNo>
				<Password>'.$this->Password.'</Password>
			</MessageHead>
			<MessageBody>
				<DTCFlow>
					<PAYMENT_INFO>
						<CUSTOMS_CODE>8012</CUSTOMS_CODE>
						<BIZ_TYPE_CODE>'.$_POST['BIZ_TYPE_CODE'].'</BIZ_TYPE_CODE>
						<ESHOP_ENT_CODE>'.UserNo.'</ESHOP_ENT_CODE>
                        <ESHOP_ENT_NAME>'.ESHOP_ENT_NAME.'</ESHOP_ENT_NAME>
                        <PAYMENT_ENT_CODE>'.UserNo.'</PAYMENT_ENT_CODE>
                        <PAYMENT_ENT_NAME>'.ESHOP_ENT_NAME.'</PAYMENT_ENT_NAME>
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
                <ActionType>'.$_POST['ActionType'].'</ActionType>
                <MessageTime>'.$this->MessageTime.'</MessageTime>
                <SenderId>'.UserNo.'</SenderId>
                <ReceiverId>CQITC</ReceiverId>
				<SenderAddress/>
				<ReceiverAddress/>
				<PlatFormNo/>
				<CustomCode/>
				<SeqNo/>
				<Note/>
                <UserNo>'.UserNo.'</UserNo>
                <Password>'.$this->Password.'</Password>
              </MessageHead>
              <MessageBody>
                <DTCFlow>
                  <ORDER_HEAD>
                    <CUSTOMS_CODE>8012</CUSTOMS_CODE>
					<SORTLINE_ID>'.$_POST['SORTLINE_ID'].'</SORTLINE_ID>
                    <BIZ_TYPE_CODE>'.$_POST['BIZ_TYPE_CODE'].'</BIZ_TYPE_CODE>
                    <ORIGINAL_ORDER_NO>'.$_POST['ORIGINAL_ORDER_NO'].'</ORIGINAL_ORDER_NO>
                    <ESHOP_ENT_CODE>'.UserNo.'</ESHOP_ENT_CODE>
                    <ESHOP_ENT_NAME>'.ESHOP_ENT_NAME.'</ESHOP_ENT_NAME>
                    <DESP_ARRI_COUNTRY_CODE>'.$_POST['DESP_ARRI_COUNTRY_CODE'].'</DESP_ARRI_COUNTRY_CODE>
                    <SHIP_TOOL_CODE>'.$_POST['SHIP_TOOL_CODE'].'</SHIP_TOOL_CODE>
                    <RECEIVER_ID_NO>'.$_POST['RECEIVER_ID_NO'].'</RECEIVER_ID_NO>
                    <RECEIVER_NAME>'.$_POST['RECEIVER_NAME'].'</RECEIVER_NAME>
                    <RECEIVER_ADDRESS>'.$_POST['RECEIVER_ADDRESS'].'</RECEIVER_ADDRESS>
                    <RECEIVER_TEL>'.$_POST['RECEIVER_TEL'].'</RECEIVER_TEL>
                    <GOODS_FEE>'.$_POST['GOODS_FEE'].'</GOODS_FEE>
                    <TAX_FEE>'.$_POST['TAX_FEE'].'</TAX_FEE>
                    <GROSS_WEIGHT/>
					<PROXY_ENT_CODE/>
					<PROXY_ENT_NAME/>
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
                <ActionType>'.$_POST['ActionType'].'</ActionType>
                <MessageTime>'.$this->MessageTime.'</MessageTime>
                <SenderId>'.UserNo.'</SenderId>
                <ReceiverId>CQITC</ReceiverId>
                <UserNo>'.UserNo.'</UserNo>
                <Password>'.$this->Password.'</Password>
            </MessageHead>
			<MessageBody>
				<DTCFlow>
					<ORDER_RETURN_INFO>
                        <ORIGINAL_ORDER_NO>'.$_POST['ORIGINAL_ORDER_NO'].'</ORIGINAL_ORDER_NO>
                        <ESHOP_ENT_CODE>'.ESHOP_ENT_CODE.'</ESHOP_ENT_CODE>
                        <RETURN_REASON>多拍/拍错/不想要</RETURN_REASON>
                        <QUALITY_REPORT />
                     </ORDER_RETURN_INFO>
                </DTCFlow>
            </MessageBody>
        </DTC_Message>';
    return $xml;
	}
	
	//模拟入库回执
    function MES_ASK_INFO(){
		$xml = '<?xml version="1.0" encoding="utf-8"?>
			<DTC_Message>
				<MessageHead>
					<MessageType>'.__FUNCTION__.'</MessageType>
					<MessageId>'.$this->MessageId.'</MessageId>
					<MessageTime>'.$this->MessageTime.'</MessageTime>
					<SenderId>CQITC</SenderId>
					<SenderAddress />
					<ReceiverId>50052602G5</ReceiverId>
					<ReceiverAddress />
					<PlatFormNo />
					<CustomCode />
					<SeqNo>'.$this->MessageId.'</SeqNo>
					<Note />
				</MessageHead>
				<MessageBody>
					<DTCFlow>
						<MES_ASK_INFO>
							<MESSAGE_TYPE>'.$_POST['MESSAGE_TYPE'].'</MESSAGE_TYPE>
							<WORK_NO>'.$_POST['WORK_NO'].'</WORK_NO>
							<CREATED_NO>'.$this->MessageId.'</CREATED_NO>
							<OP_DATE>'.$this->MessageTime.'</OP_DATE>
							<SUCCESS>'.$_POST['SUCCESS'].'</SUCCESS>
							<MEMO>'.$_POST['MEMO'].'</MEMO>
							<UDF1 />
							<UDF2 />
							<UDF3 />
							<UDF4 />
							<UDF5 />
						</MES_ASK_INFO>
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
                <MessageTime>'.$this->MessageTime.'</MessageTime>
                <SenderId>'.UserNo.'</SenderId>
				<SenderAddress/>
                <ReceiverId>CQITC</ReceiverId>
				<ReceiverAddress/>
				<PlatFormNo />
				<CustomCode />
                <SeqNo />
				<Note />
            </MessageHead>
			<MessageBody>
				<DTCFlow>
					<BILL_INFO>
                        <ORIGINAL_ORDER_NO>'.$_POST['ORIGINAL_ORDER_NO'].'</ORIGINAL_ORDER_NO>
						<BIZ_TYPE_CODE>'.$_POST['BIZ_TYPE_CODE'].'</BIZ_TYPE_CODE>
						<BIZ_TYPE_NAME>'.$_POST['BIZ_TYPE_NAME'].'</BIZ_TYPE_NAME>
						<TRANSPORT_BILL_NO>'.$_POST['TRANSPORT_BILL_NO'].'</TRANSPORT_BILL_NO>
						<ESHOP_ENT_CODE>'.UserNo.'</ESHOP_ENT_CODE>
						<ESHOP_ENT_NAME>'.ESHOP_ENT_NAME.'</ESHOP_ENT_NAME>
						<CUSTOMS_CODE>8012</CUSTOMS_CODE>
						<CUSTOMS_NAME>重庆保税</CUSTOMS_NAME>
						<LOGISTICS_ENT_CODE>'.$_POST['LOGISTICS_ENT_CODE'].'</LOGISTICS_ENT_CODE>
						<LOGISTICS_ENT_NAME>'.$_POST['LOGISTICS_ENT_NAME'].'</LOGISTICS_ENT_NAME>
						<QTY>'.$_POST['QTY'].'</QTY>
						<RECEIVER_ID_NO>'.$_POST['RECEIVER_ID_NO'].'</RECEIVER_ID_NO>
						<RECEIVER_NAME>'.$_POST['RECEIVER_NAME'].'</RECEIVER_NAME>
						<RECEIVER_ADDRESS>'.$_POST['RECEIVER_ADDRESS'].'</RECEIVER_ADDRESS>
						<RECEIVER_TEL>'.$_POST['RECEIVER_TEL'].'</RECEIVER_TEL>
						<MEMO />   
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
	echo $xml."<br/>";
	
	//base64加密
	$data = base64_encode($xml);
		
	//发送数据
	$res = _curl_post( APIURL, array('data'=>$data) );
		
	//输出res以回执
	header("Content-type: text/html; charset=utf-8");
	echo  $res."<br/>";
