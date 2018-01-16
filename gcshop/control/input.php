<?php

/**
 * 统一版推单
 *
 */
defined('GcWebShop') or exit('Access Invalid!');

class inputControl extends BaseApiControl{
	
	public function indexOp(){
		
		set_time_limit(300);
		header("Content-type: text/html; charset=UTF-8");

		//获取发货仓
		$model_region = Model('region');
		$regions = $model_region->getRegions();
		
		//获取模版
		$model_transport = Model('transport');
		$transports = $model_transport->getTransports();
	
		//获取海关相关编号 名称
		$model_setting = Model('setting');
		$customs_code = $model_setting->getListSetting();
		
		//写推单日志
		$log = '';
		
		//实例化 调用接口
		// include(BASE_PATH.'/api/customs/EMS/ems.php');
		// $EMS = new EMS();
		include(BASE_PATH.'/api/customs/wxpay/weixin.php');
		$Weixin = new Weixin();
		include(BASE_PATH.'/api/customs/alipay/alipayapi.php');
		$alipay = new Alipay();
		include(BASE_PATH.'/api/customs/tonglian/tonglian.php');
		$tonglian = new Tonglian();
		
        try {
            $model = Model('order');
            //$model->beginTransaction();
			
			//清空临时表
			DB::query("delete from `33hao_mess_order_info_temp`");

			//将已付款且未提取的订单数据插入临时表中
			$sql = "INSERT INTO 33hao_mess_order_info_temp(`ORDER_ID`,`GOODS_ID`,`ORDER_SN`,`PAY_SN`,`TRADE_NO`,`ORDER_AMOUNT`,`SKU`,`GOODS_SPEC`,`GOODS_PRICE`,`GOODS_RATE`,`GOODS_NUM`,`GOODS_TAX`,`goods_total`,`shipping_total`,`insurance_total`,`discount_total`,`taxes_total`,`mess_country_code`,`shipping_code`,`GOODS_WEIGHT`,`GOODS_UNITS`,`RECEIVER_NAME`,`RECEIVER_ID_NO`,`RECEIVER_INFO`) 
				SELECT
				33hao_order_goods.order_id,
				33hao_order_goods.goods_id,
				33hao_order.order_sn,
				33hao_order.pay_sn,
				33hao_order.trade_no,
				33hao_order.order_amount,
				33hao_goods.goods_serial,
				33hao_goods.sku_spec,
				33hao_order_goods.goods_pay_price,
				33hao_order_goods.taxes_total/(33hao_order_goods.goods_total+33hao_order_goods.shipping_total+33hao_order_goods.insurance_total+33hao_order_goods.discount_total),
				33hao_order_goods.goods_num,
				33hao_order.order_tax,
				33hao_order_goods.goods_total,
				33hao_order.shipping_fee,
				33hao_order_goods.insurance_total,
				33hao_order_goods.discount_total,
				33hao_order.order_tax,
				33hao_order_goods.mess_country_code,
				33hao_order.shipping_code,
				33hao_goods.goods_weight,
				33hao_goods.pack_units,
				33hao_order_common.order_name,
				33hao_order_common.reciver_idnum, 
				33hao_order_common.reciver_info
				FROM 33hao_order
				INNER JOIN 33hao_order_goods ON 33hao_order_goods.order_id = 33hao_order.order_id
				INNER JOIN 33hao_goods ON 33hao_goods.goods_id = 33hao_order_goods.goods_id
				INNER JOIN 33hao_order_common ON 33hao_order_common.order_id = 33hao_order.order_id
				WHERE
				33hao_order.order_state = 30 AND 33hao_order.MESS_STATE = 10 AND 33hao_goods.store_from = '1' AND shipping_code <> ''";
				//满足推单的条件：1、order_state = 30 已付款已存运单号 2、MESS_STATE = 10 未推送 3、store_from=1 保税进口 4、运单号不为空

			$result = DB::query($sql);

			if($result){
				// echo "临时表数据同步成功!<br/>";
				
				//将临时表的订单数据插入正式订单表
				$sql = "INSERT INTO 33hao_mess_order_info(`ORDER_ID`,`GOODS_ID`,`ORDER_SN`,`PAY_SN`,`ORDER_AMOUNT`,`SKU`,`GOODS_SPEC`,`GOODS_PRICE`,`GOODS_RATE`,`GOODS_NUM`,`GOODS_TAX`,`goods_total`,`shipping_total`,`insurance_total`,`discount_total`,`taxes_total`,`mess_country_code`,`shipping_code`,`GOODS_WEIGHT`,`GOODS_UNITS`,`RECEIVER_NAME`,`RECEIVER_ID_NO`,`RECEIVER_INFO`) 
					SELECT ORDER_ID,
					GOODS_ID,
					ORDER_SN,
					PAY_SN,
					ORDER_AMOUNT,
					SKU,
					GOODS_SPEC,
					GOODS_PRICE,
					GOODS_RATE,
					GOODS_NUM,
					GOODS_TAX,
					goods_total,
					shipping_total,
					insurance_total,
					discount_total,
					taxes_total,
					mess_country_code,
					shipping_code,
					GOODS_WEIGHT,
					GOODS_UNITS,
					RECEIVER_NAME,
					RECEIVER_ID_NO,
					RECEIVER_INFO
					FROM 33hao_mess_order_info_temp";
				$result = DB::query($sql);
				// if($result){
					// echo "订单数据保存成功!<br/>";
				// }else{
					// echo "订单数据保存失败 或 临时表内无数据!<br/>";
				// }
				
				//构建XML
				include(BASE_PATH.'/api/mess/common.php');
				
				//订单列表
				$sql = "SELECT 
						t.ORDER_SN,
						t.PAY_SN,
						t.TRADE_NO,
						t.RECEIVER_ID_NO,
						t.RECEIVER_NAME,
						t.RECEIVER_INFO,
						CAST(SUM(t.GOODS_PRICE*t.GOODS_NUM) AS DECIMAL(10,2)) AS GOODS_FEE,
						CAST(SUM(t.GOODS_RATE*t.GOODS_PRICE*t.GOODS_NUM) AS DECIMAL(10,2)) AS TAX_FEE,
						t.ORDER_AMOUNT,
						t.GOODS_ID,
						t.GOODS_PRICE,
						CAST(SUM(t.goods_total) AS DECIMAL(10,2)) AS all_goods_total,
						CAST(SUM(t.shipping_total) AS DECIMAL(10,2)) AS all_shipping_total,
						CAST(SUM(t.insurance_total) AS DECIMAL(10,2)) AS all_insurance_total,
						CAST(SUM(t.discount_total) AS DECIMAL(10,2)) AS all_discount_total,
						CAST(SUM(t.taxes_total) AS DECIMAL(10,2)) AS all_taxes_total,
						t.goods_total,
						t.insurance_total,
						t.discount_total,
						t.taxes_total,
						t.mess_country_code,
						t.shipping_code,
						o.shipping_fee,
						o.goods_amount,
						o.order_id
						FROM 33hao_mess_order_info_temp AS t
						LEFT JOIN 33hao_order AS o ON t.ORDER_ID = o.order_id
						GROUP BY t.ORDER_SN";
				$orderlists = DB::getAll($sql);
				$orderlists = !empty($orderlists) ? $orderlists  : array();
				$model_goods = Model('goods');
				foreach($orderlists as $orderlist) {
					//查询发货仓信息
					$region_info = $transport_info = array();
					$goods_info = $model_goods->getGoodsInfo(array('goods_id'=>$orderlist['GOODS_ID']), 'transport_id');
					
					//检查商品是否是优惠套装
					$re = Model()->table('p_bundling_goods')->where(array('goods_id'=>$orderlist['GOODS_ID'], 'bl_goods_price'=>$orderlist['GOODS_PRICE']))->select();
					if(isset($goods_info['transport_id'])){
						$transport_info = $transports[$goods_info['transport_id']];
						if(isset($regions[$transport_info['region_id']])){
							$region_info = $regions[$transport_info['region_id']];
						}
					}
					$region_info['customs_id'] = isset($region_info['customs_id']) ? $region_info['customs_id'] : '8012';
					$region_info['customs_name'] = isset($region_info['customs_name']) ? $region_info['customs_name'] : '重庆保税';
					$region_info['sortline_id'] = isset($region_info['sortline_id']) ? $region_info['sortline_id'] : 'SORTLINE01';

					$model_order = Model('order');
					$condition = array();
					$condition['ORDER_SN'] = $orderlist['ORDER_SN'];
					$order_info = $model_order->getOrderInfo($condition,array('order_goods','order_common','store'));
					
					$OrderGuid   = guid();
					$ListGuid    = guid();
					$appTime  	 = date('YmdHis', time());//报送时间
					$declTime 	 = date('Ymd', time());   //申报日期
					$ieDate 	 = date('Ymd', strtotime('-10 days')); //进口日期
					$RECEIVER_INFO = unserialize($orderlist['RECEIVER_INFO']);
					$reciver_name = $order_info['extend_order_common']['reciver_name']; //收货人
					$Telephone 	 = $RECEIVER_INFO['mob_phone'];
					$Address 	 = $RECEIVER_INFO['address'];
					$goodsValue  = $orderlist['goods_amount']+$orderlist['all_discount_total'];
					$acturalPaid = $orderlist['ORDER_AMOUNT'];
					$discount = $orderlist['all_discount_total'];
					// if(!empty($re) && is_array($re)){
						$taxTotal = floatval($orderlist['taxes_total']);
					// }else{
						// $taxTotal = floatval($orderlist['all_taxes_total']);
					// }
					
					//订单报文
					$orderxml = "<ceb:CEB311Message guid='".$OrderGuid."' version='1.0' xmlns:ceb='http://www.chinaport.gov.cn/ceb' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'>\n\t";	
					$orderxml .= "<ceb:Order>\n\t\t";	
						$orderxml .= "<ceb:OrderHead>\n\t\t\t";		
							$orderxml .= "<ceb:guid>".$OrderGuid."</ceb:guid>\n\t\t\t";	
							$orderxml .= "<ceb:appType>1</ceb:appType>\n\t\t\t";	
							$orderxml .= "<ceb:appTime>".$appTime."</ceb:appTime>\n\t\t\t";	
							$orderxml .= "<ceb:appStatus>2</ceb:appStatus>\n\t\t\t";	
							$orderxml .= "<ceb:orderType>I</ceb:orderType>\n\t\t\t";	
							$orderxml .= "<ceb:orderNo>".$orderlist['PAY_SN']."</ceb:orderNo>\n\t\t\t";
							if($order_info['payment_code'] == 'ccbpay'){
								$orderxml .= "<ceb:ebpCode>".$customs_code['api_customs_payCode_c']."</ceb:ebpCode>\n\t\t\t";	
								$orderxml .= "<ceb:ebpName>".$customs_code['api_customs_payName_c']."</ceb:ebpName>\n\t\t\t";
							}else{
								$orderxml .= "<ceb:ebpCode>".$customs_code['api_customs_ebpCode']."</ceb:ebpCode>\n\t\t\t";	
								$orderxml .= "<ceb:ebpName>".$customs_code['api_customs_ebpName']."</ceb:ebpName>\n\t\t\t";
							}
							$orderxml .= "<ceb:ebcCode>".$customs_code['api_customs_ebcCode']."</ceb:ebcCode>\n\t\t\t";	
							$orderxml .= "<ceb:ebcName>".$customs_code['api_customs_ebcName']."</ceb:ebcName>\n\t\t\t";	
							$orderxml .= "<ceb:goodsValue>".$goodsValue."</ceb:goodsValue>\n\t\t\t";
							// if(!empty($re) && is_array($re)){
								$orderxml .= "<ceb:freight>".intval($orderlist['shipping_fee'])."</ceb:freight>\n\t\t\t";
							// }else{
								// $orderxml .= "<ceb:freight>".intval($orderlist['all_shipping_total'])."</ceb:freight>\n\t\t\t";
							// }		
							$orderxml .= "<ceb:discount>".$discount."</ceb:discount>\n\t\t\t";	
							$orderxml .= "<ceb:taxTotal>".$taxTotal."</ceb:taxTotal>\n\t\t\t";	
							$orderxml .= "<ceb:acturalPaid>".$acturalPaid."</ceb:acturalPaid>\n\t\t\t";	
							$orderxml .= "<ceb:currency>142</ceb:currency>\n\t\t\t";	
							$orderxml .= "<ceb:buyerRegNo>".$order_info['buyer_name']."</ceb:buyerRegNo>\n\t\t\t";	
							$orderxml .= "<ceb:buyerName>".$orderlist['RECEIVER_NAME']."</ceb:buyerName>\n\t\t\t";	
							$orderxml .= "<ceb:buyerIdType>1</ceb:buyerIdType>\n\t\t\t";	
							$orderxml .= "<ceb:buyerIdNumber>".strtoupper($orderlist['RECEIVER_ID_NO'])."</ceb:buyerIdNumber>\n\t\t\t";	
							if($order_info['payment_code'] == 'alipay'){
								$orderxml .= "<ceb:payCode>".$customs_code['api_customs_payCode_a']."</ceb:payCode>\n\t\t\t";	
								$orderxml .= "<ceb:payName>".$customs_code['api_customs_payName_a']."</ceb:payName>\n\t\t\t";	
							}else if($order_info['payment_code'] == 'wxpay'){
								$orderxml .= "<ceb:payCode>".$customs_code['api_customs_payCode_w']."</ceb:payCode>\n\t\t\t";	
								$orderxml .= "<ceb:payName>".$customs_code['api_customs_payName_w']."</ceb:payName>\n\t\t\t";	
							}else if($order_info['payment_code'] == 'gzbank'){
								$orderxml .= "<ceb:payCode>".$customs_code['api_customs_payCode_g']."</ceb:payCode>\n\t\t\t";	
								$orderxml .= "<ceb:payName>".$customs_code['api_customs_payName_g']."</ceb:payName>\n\t\t\t";	
							}else if($order_info['payment_code'] == 'ccbpay'){
								$orderxml .= "<ceb:payCode>".$customs_code['api_customs_payCode_c']."</ceb:payCode>\n\t\t\t";	
								$orderxml .= "<ceb:payName>".$customs_code['api_customs_payName_c']."</ceb:payName>\n\t\t\t";	
							}else if($order_info['payment_code'] == 'tonglian'){
								$orderxml .= "<ceb:payCode>".$customs_code['api_customs_payCode_t']."</ceb:payCode>\n\t\t\t";	
								$orderxml .= "<ceb:payName>".$customs_code['api_customs_payName_t']."</ceb:payName>\n\t\t\t";	
							}
							$orderxml .= "<ceb:payTransactionId></ceb:payTransactionId>\n\t\t\t"; //支付交易编号
							$orderxml .= "<ceb:batchNumbers></ceb:batchNumbers>\n\t\t\t";	//商品批次号
							$orderxml .= "<ceb:consignee>".$reciver_name."</ceb:consignee>\n\t\t\t";	
							$orderxml .= "<ceb:consigneeTelephone>".$Telephone."</ceb:consigneeTelephone>\n\t\t\t";	
							$orderxml .= "<ceb:consigneeAddress>".$Address."</ceb:consigneeAddress>\n\t\t\t";	
							//$orderxml .= "<ceb:consigneeDistrict></ceb:consigneeDistrict>\n";	收货地址行政区划代码
							$orderxml .= "<ceb:note>".$order_info['extend_order_common']['promotion_info']."</ceb:note>\n\t\t";	//备注
						$orderxml .= "</ceb:OrderHead>\n\t\t";	
					
						$sql = "SELECT
								t.ORDER_SN,
								t.SKU,
								t.GOODS_SPEC,
								t.GOODS_WEIGHT,
								t.GOODS_NUM,
								t.goods_total AS GOODS_AMOUNT,
								t.taxes_total AS GOODS_TAX,
								t.mess_country_code,
								t.shipping_code,
								t.GOODS_PRICE,
								g.goods_hscode,
								g.goods_name,
								g.records_name,
								g.sku_spec,
								c.pack_units,
								c.goods_reduced,
								c.qty2,
								c.unit1,
								c.unit2
								FROM 33hao_mess_order_info_temp AS t
								LEFT JOIN 33hao_goods AS g ON t.GOODS_ID = g.goods_id
								LEFT JOIN 33hao_goods_common AS c ON c.goods_commonid = g.goods_commonid
								WHERE t.ORDER_SN = '".$orderlist['ORDER_SN']."'";
						$skulists2 = $skulists = DB::getAll($sql);
						
					foreach($skulists as $key =>$skulist) {
						$skulist['GOODS_NUM'] = intval($skulist['GOODS_NUM']);
						$netWeight += $skulist['GOODS_WEIGHT'] * $skulist['GOODS_NUM']; //净重
						$grossWeight = $netWeight * 1.2; //毛重 *1.2的系数
						$price = round($skulist['GOODS_PRICE'] / $skulist['GOODS_NUM'], 2);
						$totalPrice = $skulist['GOODS_PRICE'] ;
						$goods_serial = $skulist['SKU'];
						if (preg_match('/^(\d+)$/',$goods_serial)){
							$barCode = $goods_serial;
						}else{
							$barCode = substr($goods_serial,2);
							if (preg_match('/^(\d+)$/',$barCode)){
								$barCode = $barCode;
							}else{
								$barCode= substr($barCode , 0 ,strpos($barCode,'-'));
							}
						}
						$key += 1;
						$orderxml .= "<ceb:OrderList>\n\t\t\t";
							$orderxml .= "<ceb:gnum>".$key."</ceb:gnum>\n\t\t\t";
							$orderxml .= "<ceb:itemNo>".$skulist['SKU']."</ceb:itemNo>\n\t\t\t";
							$orderxml .= "<ceb:itemName>".htmlspecialchars($skulist['goods_name'])."</ceb:itemName>\n\t\t\t";
							$orderxml .= "<ceb:itemDescribe>".htmlspecialchars($skulist['GOODS_SPEC'])."</ceb:itemDescribe>\n\t\t\t";
							$orderxml .= "<ceb:barCode>".$barCode."</ceb:barCode>\n\t\t\t";
							$orderxml .= "<ceb:unit>".$skulist['pack_units']."</ceb:unit>\n\t\t\t";
							$orderxml .= "<ceb:qty>".$skulist['GOODS_NUM']."</ceb:qty>\n\t\t\t";
							$orderxml .= "<ceb:price>".$price."</ceb:price>\n\t\t\t";
							$orderxml .= "<ceb:totalPrice>".$totalPrice."</ceb:totalPrice>\n\t\t\t";
							$orderxml .= "<ceb:currency>142</ceb:currency>\n\t\t\t";
							$orderxml .= "<ceb:country>".$skulist['mess_country_code']."</ceb:country>\n\t\t\t";
							$orderxml .= "<ceb:note></ceb:note>\n\t\t"; //备注
						$orderxml .= "</ceb:OrderList>\n\t";
					}
					$orderxml .= "</ceb:Order>\n\t";
						$orderxml .= "<ceb:BaseTransfer>\n\t\t"; 
							$orderxml .= "<ceb:copCode>".$customs_code['api_customs_copCode']."</ceb:copCode>\n\t\t";
							$orderxml .= "<ceb:copName>".$customs_code['api_customs_copName']."</ceb:copName>\n\t\t";
							$orderxml .= "<ceb:dxpMode>DXP</ceb:dxpMode>\n\t\t";
							$orderxml .= "<ceb:dxpId>".$customs_code['api_customs_dxpId']."</ceb:dxpId>\n\t\t"; //电子口岸数据中心申请数据交换平台的用户编号
							$orderxml .= "<ceb:note></ceb:note>\n\t"; //备注
						$orderxml .= "</ceb:BaseTransfer>\n";
					$orderxml .= "</ceb:CEB311Message>\n";
					$orderxml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".$orderxml;
					
					//清单报文
					$listxml = "<ceb:CEB621Message guid='".$ListGuid."' version='1.0'  xmlns:ceb='http://www.chinaport.gov.cn/ceb' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'>\n\t";
					$listxml .= "<ceb:Inventory>\n\t\t";
						$listxml .= "<ceb:InventoryHead>\n\t\t\t";
							$listxml .= "<ceb:guid>".$ListGuid."</ceb:guid>\n\t\t\t";
							$listxml .= "<ceb:appType>1</ceb:appType>\n\t\t\t";
							$listxml .= "<ceb:appTime>".$appTime."</ceb:appTime>\n\t\t\t";
							$listxml .= "<ceb:appStatus>2</ceb:appStatus>\n\t\t\t";
							$listxml .= "<ceb:orderNo>".$orderlist['PAY_SN']."</ceb:orderNo>\n\t\t\t";
							if($order_info['payment_code'] == 'ccbpay'){
								$listxml .= "<ceb:ebpCode>".$customs_code['api_customs_payCode_c']."</ceb:ebpCode>\n\t\t\t";
								$listxml .= "<ceb:ebpName>".$customs_code['api_customs_payName_c']."</ceb:ebpName>\n\t\t\t";
							}else{
								$listxml .= "<ceb:ebpCode>".$customs_code['api_customs_ebpCode']."</ceb:ebpCode>\n\t\t\t";
								$listxml .= "<ceb:ebpName>".$customs_code['api_customs_ebpName']."</ceb:ebpName>\n\t\t\t";
							}
							$listxml .= "<ceb:ebcCode>".$customs_code['api_customs_ebcCode']."</ceb:ebcCode>\n\t\t\t";
							$listxml .= "<ceb:ebcName>".$customs_code['api_customs_ebcName']."</ceb:ebcName>\n\t\t\t";
							$listxml .= "<ceb:logisticsNo>".$order_info['shipping_code']."</ceb:logisticsNo>\n\t\t\t";
							$listxml .= "<ceb:logisticsCode>".$customs_code['api_customs_logisticsCode']."</ceb:logisticsCode>\n\t\t\t";
							$listxml .= "<ceb:logisticsName>".$customs_code['api_customs_logisticsName']."</ceb:logisticsName>\n\t\t\t";
							$listxml .= "<ceb:copNo>".$orderlist['ORDER_SN']."</ceb:copNo>\n\t\t\t";//企业内部标识单证的编号就是内部订单号
							$listxml .= "<ceb:preNo></ceb:preNo>\n\t\t\t";//电子口岸标识单证的编号
							$listxml .= "<ceb:assureCode>".$customs_code['api_customs_assureCode']."</ceb:assureCode>\n\t\t\t";
							if($order_info['store_id'] == 2){
								$listxml .= "<ceb:emsNo>".$customs_code['api_customs_emsNo_2']."</ceb:emsNo>\n\t\t\t";//账册编号
							}else if($order_info['store_id'] == 7){
								$listxml .= "<ceb:emsNo>".$customs_code['api_customs_emsNo_7']."</ceb:emsNo>\n\t\t\t";
							}
							$listxml .= "<ceb:invtNo></ceb:invtNo>\n\t\t\t";//清单编号
							$listxml .= "<ceb:ieFlag>I</ceb:ieFlag>\n\t\t\t";
							$listxml .= "<ceb:declTime>".$declTime."</ceb:declTime>\n\t\t\t";
							$listxml .= "<ceb:customsCode>".$region_info['customs_id']."</ceb:customsCode>\n\t\t\t";
							$listxml .= "<ceb:portCode>".$region_info['customs_id']."</ceb:portCode>\n\t\t\t";
							$listxml .= "<ceb:ieDate>".$ieDate."</ceb:ieDate>\n\t\t\t";//进口日期
							$listxml .= "<ceb:buyerIdType>1</ceb:buyerIdType>\n\t\t\t";
							$listxml .= "<ceb:buyerIdNumber>".strtoupper($orderlist['RECEIVER_ID_NO'])."</ceb:buyerIdNumber>\n\t\t\t";
							$listxml .= "<ceb:buyerName>".$orderlist['RECEIVER_NAME']."</ceb:buyerName>\n\t\t\t";
							$listxml .= "<ceb:buyerTelephone>".$order_info['extend_order_common']['reciver_info']['mob_phone']."</ceb:buyerTelephone>\n\t\t\t";
							$listxml .= "<ceb:consigneeAddress>".$Address."</ceb:consigneeAddress>\n\t\t\t";
							$listxml .= "<ceb:agentCode>".$customs_code['api_customs_agentCode']."</ceb:agentCode>\n\t\t\t";
							$listxml .= "<ceb:agentName>".$customs_code['api_customs_agentName']."</ceb:agentName>\n\t\t\t";
							if($order_info['store_id'] == 2){
								$listxml .= "<ceb:areaCode>".$customs_code['api_customs_areaCode_2']."</ceb:areaCode>\n\t\t\t";
								$listxml .= "<ceb:areaName>".$customs_code['api_customs_areaName_2']."</ceb:areaName>\n\t\t\t";
							}else if($order_info['store_id'] == 7){
								$listxml .= "<ceb:areaCode>".$customs_code['api_customs_areaCode_7']."</ceb:areaCode>\n\t\t\t";
								$listxml .= "<ceb:areaName>".$customs_code['api_customs_areaName_7']."</ceb:areaName>\n\t\t\t";
							}
							$listxml .= "<ceb:tradeMode>1210</ceb:tradeMode>\n\t\t\t";//贸易方式
							$listxml .= "<ceb:trafMode>Y</ceb:trafMode>\n\t\t\t";//Y是保税港区
							$listxml .= "<ceb:trafNo></ceb:trafNo>\n\t\t\t";//运输工具编号  直购进口必填
							$listxml .= "<ceb:voyageNo></ceb:voyageNo>\n\t\t\t";//航班航次号  直购进口必填
							$listxml .= "<ceb:billNo></ceb:billNo>\n\t\t\t";//提运单号  直购进口必填
							$listxml .= "<ceb:loctNo></ceb:loctNo>\n\t\t\t";//监管场所代码
							$listxml .= "<ceb:licenseNo></ceb:licenseNo>\n\t\t\t";//许可证件号
							$listxml .= "<ceb:country>142</ceb:country>\n\t\t\t";//起运国（地区）
							// if(!empty($re) && is_array($re)){
								$listxml .= "<ceb:freight>".intval($orderlist['shipping_fee'])."</ceb:freight>\n\t\t\t";
							// }else{
								// $listxml .= "<ceb:freight>".intval($orderlist['all_shipping_total'])."</ceb:freight>\n\t\t\t";
							// }
							$listxml .= "<ceb:insuredFee>0</ceb:insuredFee>\n\t\t\t";	
							$listxml .= "<ceb:currency>142</ceb:currency>\n\t\t\t";	
							//$listxml .= "<ceb:wrapType></ceb:wrapType>\n\t\t\t";	//包装种类代码
							$listxml .= "<ceb:packNo>1</ceb:packNo>\n\t\t\t";	//件数
							$listxml .= "<ceb:grossWeight>".$grossWeight."</ceb:grossWeight>\n\t\t\t";//毛重（公斤）
							$listxml .= "<ceb:netWeight>".$netWeight."</ceb:netWeight>\n\t\t\t";	//净重（公斤）
							$listxml .= "<ceb:note></ceb:note>\n\t\t\t";	//备注
							$listxml .= "<ceb:sortlineId>".$region_info['sortline_id']."</ceb:sortlineId>\n\t\t\t";
							if($order_info['store_id'] == 2){
								$listxml .= "<ceb:orgCode>".$customs_code['api_customs_orgCode_2']."</ceb:orgCode>\n\t\t";
							}else if($order_info['store_id'] == 7){
								$listxml .= "<ceb:orgCode>".$customs_code['api_customs_orgCode_7']."</ceb:orgCode>\n\t\t";
							}
						$listxml .= "</ceb:InventoryHead>\n\t\t";	
								
					foreach($skulists2 as $key =>$skulist) {
						$listxml .= "<ceb:InventoryList>\n\t\t\t";
							$skulist['GOODS_NUM'] = intval($skulist['GOODS_NUM']);
							$price = round($skulist['GOODS_PRICE'] / $skulist['GOODS_NUM'], 2);
							$totalPrice = $skulist['GOODS_PRICE'] ;
							$key += 1;
							$listxml .= "<ceb:gnum>".$key."</ceb:gnum>\n\t\t\t";
							$listxml .= "<ceb:itemRecordNo>".$skulist['SKU']."</ceb:itemRecordNo>\n\t\t\t"; //账册备案料号就是商品货号
							$listxml .= "<ceb:itemNo>".$skulist['SKU']."</ceb:itemNo>\n\t\t\t"; 
							$listxml .= "<ceb:itemName>".htmlspecialchars($skulist['goods_name'])."</ceb:itemName>\n\t\t\t"; 
							$listxml .= "<ceb:gcode>".htmlspecialchars($skulist['goods_hscode'])."</ceb:gcode>\n\t\t\t";  
							$listxml .= "<ceb:gname>".htmlspecialchars($skulist['records_name'])."</ceb:gname>\n\t\t\t";  
							$listxml .= "<ceb:gmodel>".htmlspecialchars($skulist['GOODS_SPEC'])."</ceb:gmodel>\n\t\t\t";  
							$listxml .= "<ceb:barCode></ceb:barCode>\n\t\t\t";  //条码
							$listxml .= "<ceb:country>".$skulist['mess_country_code']."</ceb:country>\n\t\t\t"; 
							$listxml .= "<ceb:currency>142</ceb:currency>\n\t\t\t"; 
							$listxml .= "<ceb:qty>".$skulist['GOODS_NUM']."</ceb:qty>\n\t\t\t"; 
							$listxml .= "<ceb:unit>".$skulist['pack_units']."</ceb:unit>\n\t\t\t"; //计量单位
							$listxml .= "<ceb:qty1>".$skulist['goods_reduced']."</ceb:qty1>\n\t\t\t"; //法定数量
							$listxml .= "<ceb:unit1>".$skulist['unit1']."</ceb:unit1>\n\t\t\t"; //法定计量单位
							if($skulist['pack_units'] == $skulist['unit2']){
								$listxml .= "<ceb:qty2>".$skulist['GOODS_NUM']."</ceb:qty2>\n\t\t\t"; //第二数量
							}else{
								$listxml .= "<ceb:qty2>".$skulist['qty2']."</ceb:qty2>\n\t\t\t"; //第二数量
							}
							$listxml .= "<ceb:unit2>".$skulist['unit2']."</ceb:unit2>\n\t\t\t"; //第二计量单位
							$listxml .= "<ceb:price>".$price."</ceb:price>\n\t\t\t"; 
							$listxml .= "<ceb:totalPrice>".$totalPrice."</ceb:totalPrice>\n\t\t\t"; 
							$listxml .= "<ceb:note></ceb:note>\n\t\t";  //备注
						$listxml .= "</ceb:InventoryList>\n\t";  
					}
						$listxml .= "</ceb:Inventory>\n\t";
						$listxml .= "<ceb:BaseTransfer>\n\t\t"; 
							$listxml .= "<ceb:copCode>".$customs_code['api_customs_copCode']."</ceb:copCode>\n\t\t";
							$listxml .= "<ceb:copName>".$customs_code['api_customs_copName']."</ceb:copName>\n\t\t";
							$listxml .= "<ceb:dxpMode>DXP</ceb:dxpMode>\n\t\t";
							$listxml .= "<ceb:dxpId>".$customs_code['api_customs_dxpId']."</ceb:dxpId>\n\t\t";
							$listxml .= "<ceb:note></ceb:note>\n\t"; //备注
						$listxml .= "</ceb:BaseTransfer>\n";
					$listxml .= "</ceb:CEB621Message>\n";
					$listxml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".$listxml;
					
					$return_result = true; //判断返回信息是否错误 如果错误就不更改订单状态
					
					$data_order = base64_encode($orderxml);
					$data_list  = base64_encode($listxml);
					
					$res_list   = _curl_post( APIURL, array('data'=>$data_list) ); 
					if($res_list == 'True'){
						$log.= "清单报文: ".$orderlist['ORDER_SN']. " 推送成功".PHP_EOL;
					}else{
						$return_result = false;
						Model('mess_order_info')->editMessOrder(array('LI_SUCCESS'=>2),array('ORDER_SN'=>$orderlist['ORDER_SN']));
						$log.= "<p style='color:red;'>清单报文: ".$orderlist['ORDER_SN']. " 推送失败</p>".PHP_EOL;
					}
					
					$res_order  = _curl_post( APIURL, array('data'=>$data_order) );
					if($res_order == 'True'){
						$log.= "订单报文: ".$orderlist['ORDER_SN']. " 推送成功".PHP_EOL;
					}else{
						$return_result = false;
						Model('mess_order_info')->editMessOrder(array('OI_SUCCESS'=>2),array('ORDER_SN'=>$orderlist['ORDER_SN']));
						$log.= "<p style='color:red;'>订单报文: ".$orderlist['ORDER_SN']. " 推送失败</p>".PHP_EOL;
					}
					
					//支付单报文
					if($order_info['payment_code'] == 'alipay'){
						//支付宝海关报关
						$result = $alipay->get_parameter($orderlist['ORDER_SN'], $order_info['trade_no'], $customs_code['api_customs_ebpCode'], $customs_code['api_customs_ebpName'], $acturalPaid, 'ZONGSHU');
						if($result['is_success']== 'T' && $result['result_code']== 'SUCCESS'){
							$log.= '支付宝海关报关信息推送成功 单号：'.$orderlist['ORDER_SN'].PHP_EOL;
						}else if($result['result_code']== 'FAIL' && $result['detail_error_code']){
							$return_result = false;
							$log.= '<p style="color:red;">支付宝海关报关错误信息：'.$result['detail_error_des'].'! 单号：'.$orderlist['ORDER_SN'].'</p>'.PHP_EOL;
						}
						// if($result['re_code'] == 'SUCCESS'){
							// echo '支付宝 '.$result['customs_info'].'<br/>';
						// }
					}else if($order_info['payment_code'] == 'wxpay' || $order_info['payment_code'] == 'wxminipay'){
						//微信海关报关（公众号支付）
						$appid    = 'wx204e281a1204e460';
						$mch_id   = '1247438301';
						$key   = 'q2uh4tio2lh3tlgabjdgh9pqo28yhtq1';
						$order_sn = strval($orderlist['ORDER_SN']);
						$trade_no = strval($order_info['trade_no']);
						$result = $Weixin->sendRequest($appid,$mch_id,$order_sn,$trade_no,$taxTotal,$orderlist['RECEIVER_NAME'],$orderlist['RECEIVER_ID_NO'],$key);
						if($result['return_code']== 'SUCCESS' && $result['result_code']== 'SUCCESS'){
							$log.= '微信海关报关信息推送成功 状态：'.$result['state'].' 单号：'.$orderlist['ORDER_SN'].PHP_EOL;
						}else if($result['return_code']== 'FAIL'){
							$return_result = false;
							$log.= '<p style="color:red;">微信海关报关错误信息：'.$result['return_msg'].'! 单号：'.$orderlist['ORDER_SN'].'</p>'.PHP_EOL;
						}else if($result['result_code']== 'FAIL' && $result['err_code']){
							$return_result = false;
							$log.= '<p style="color:red;">微信海关报关错误信息：'.$result['err_code_des'].'! 单号：'.$orderlist['ORDER_SN'].'</p>'.PHP_EOL;
						}
					}else if($order_info['payment_code'] == 'wxapppay'){
						//微信海关报关（APP支付）
						$appid    = 'wx985791864b6c35e3';
						$mch_id   = '1413236402';
						$key   = 'guangcaiguojichongqingdianzi1313';
						$order_sn = strval($orderlist['ORDER_SN']);
						$trade_no = strval($order_info['trade_no']);
						$result = $Weixin->sendRequest($appid,$mch_id,$order_sn,$trade_no,$taxTotal,$orderlist['RECEIVER_NAME'],$orderlist['RECEIVER_ID_NO'],$key);
						if($result['return_code']== 'SUCCESS' && $result['result_code']== 'SUCCESS'){
							$log.= '微信海关报关信息推送成功 状态：'.$result['state'].' 单号：'.$orderlist['ORDER_SN'].PHP_EOL;
						}else if($result['return_code']== 'FAIL'){
							$return_result = false;
							$log.= '<p style="color:red;">微信海关报关错误信息：'.$result['return_msg'].'! 单号：'.$orderlist['ORDER_SN'].'</p>'.PHP_EOL;
						}else if($result['result_code']== 'FAIL' && $result['err_code']){
							$return_result = false;
							$log.= '<p style="color:red;">微信海关报关错误信息：'.$result['err_code_des'].'! 单号：'.$orderlist['ORDER_SN'].'</p>'.PHP_EOL;
						}
					}else if($order_info['payment_code'] == 'wxapp_ys'){
						//微信海关报关（原生APP）
						$appid    = 'wx8fce27302ed747d8';
						$mch_id   = '1489570722';
						$key   = 'iOjmY4aNVWYyjF7WIfMy2JbnJxiqpZu0';
						$order_sn = strval($orderlist['ORDER_SN']);
						$trade_no = strval($order_info['trade_no']);
						$result = $Weixin->sendRequest($appid,$mch_id,$order_sn,$trade_no,$taxTotal,$orderlist['RECEIVER_NAME'],$orderlist['RECEIVER_ID_NO'],$key);
						if($result['return_code']== 'SUCCESS' && $result['result_code']== 'SUCCESS'){
							$log.= '微信海关报关信息推送成功 状态：'.$result['state'].' 单号：'.$orderlist['ORDER_SN'].PHP_EOL;
						}else if($result['return_code']== 'FAIL'){
							$return_result = false;
							$log.= '<p style="color:red;">微信海关报关错误信息：'.$result['return_msg'].'! 单号：'.$orderlist['ORDER_SN'].'</p>'.PHP_EOL;
						}else if($result['result_code']== 'FAIL' && $result['err_code']){
							$return_result = false;
							$log.= '<p style="color:red;">微信海关报关错误信息：'.$result['err_code_des'].'! 单号：'.$orderlist['ORDER_SN'].'</p>'.PHP_EOL;
						}
					}else if($order_info['payment_code'] == 'wxpaytian'){
						//微信海关报关（公众号支付-天下一家）
						$appid    = 'wx829ff48dab1d59f9';
						$mch_id   = '1491398682';
						$key   = '49dcb7fcbf6900d9e16a72d9399a611f';
						$order_sn = strval($orderlist['ORDER_SN']);
						$trade_no = strval($order_info['trade_no']);
						$result = $Weixin->sendRequest($appid,$mch_id,$order_sn,$trade_no,$taxTotal,$orderlist['RECEIVER_NAME'],$orderlist['RECEIVER_ID_NO'],$key);
						if($result['return_code']== 'SUCCESS' && $result['result_code']== 'SUCCESS'){
							$log.= '微信海关报关信息推送成功 状态：'.$result['state'].' 单号：'.$orderlist['ORDER_SN'].PHP_EOL;
						}else if($result['return_code']== 'FAIL'){
							$return_result = false;
							$log.= '<p style="color:red;">微信海关报关错误信息：'.$result['return_msg'].'! 单号：'.$orderlist['ORDER_SN'].'</p>'.PHP_EOL;
						}else if($result['result_code']== 'FAIL' && $result['err_code']){
							$return_result = false;
							$log.= '<p style="color:red;">微信海关报关错误信息：'.$result['err_code_des'].'! 单号：'.$orderlist['ORDER_SN'].'</p>'.PHP_EOL;
						}
					}else if($order_info['payment_code'] == 'lakala'){
						$log.= '拉卡拉支付单的信息会单独去海关! 单号：'.$orderlist['ORDER_SN'].'<br/>'.PHP_EOL;
						
					}else if($order_info['payment_code'] == 'bocomm'){
						//银联海关报关
						
					}else if($order_info['payment_code'] == 'tonglian'){
						$TOTAL_FEE = $order_info['order_amount'] * 100;
						$result = $tonglian->sendRequest($order_info['pay_sn'], $order_info['add_time'], $TOTAL_FEE, $order_info['payment_time'], $order_info['extend_order_common']['order_name'], $order_info['extend_order_common']['reciver_idnum']);
						if($result['retCode'] == '0000'){
							$log.= '通联海关报关信息推送成功 单号：'.$orderlist['ORDER_SN'].PHP_EOL;
						}else{
							$return_result = false;
							$log.= '通联海关报关错误信息：'.$result['retMsg'].'! 单号：'.$orderlist['ORDER_SN'].PHP_EOL;
						}
					}
					
					//EMS运单报文
					/*$EMS->RECEIVER_ID_NO = $orderlist['RECEIVER_ID_NO'];
					$EMS->RECEIVER_NAME = $reciver_name;
					$EMS->ORDER_SN = $orderlist['ORDER_SN'];
					$EMS->address = $order_info['extend_order_common']['reciver_info']['address'];
					$EMS->phone = $order_info['extend_order_common']['reciver_info']['mob_phone'];
					if($order_info['store_id'] == 2){
						$EMS->EMS_CODE = '50011201326727';
					}else if($order_info['store_id'] == 7){
						$EMS->EMS_CODE = '50010713784000';
					}
					// if(!empty($re) && is_array($re)){
						$EMS->freight = intval($orderlist['shipping_fee']);
					// }else{
						// $EMS->freight = intval($orderlist['all_shipping_total']);
					// }
					$EMS->weight = $grossWeight;
					$EMS->goods_list = $skulists2;
					$result = $EMS->ems_send();
					
					if($result['result'] == '0'){
						$log.= 'EMS海关报关运单信息推送成功 单号：'.$orderlist['ORDER_SN'].PHP_EOL;
					}else {
						$return_result = false;
						$log.= '<p style="color:red;">EMS海关报关运单信息有错误 单号：'.$orderlist['ORDER_SN'].'</p>'.PHP_EOL;
					}*/
					
					//更改订单状态
					// if($return_result){
						$sql = "UPDATE 33hao_order SET MESS_STATE = 20 WHERE 33hao_order.order_sn = '".$orderlist['ORDER_SN']."'";
						$result = DB::query($sql);
						if($result){
							$log.= "订单".$orderlist['ORDER_SN']."状态更改成功".PHP_EOL;
							$log.=''.PHP_EOL;
						}else{
							$log.= "<p style='color:red;'>订单".$orderlist['ORDER_SN']."状态更改失败!</p>".PHP_EOL;
							$log.=''.PHP_EOL;
						}
						
						$model_order = Model('order');
						$data = array();
						$data['shipping_time'] = TIMESTAMP ;
						$condition = array();
						$condition['order_id'] = $orderlist['order_id'];
						$model_order->editOrderCommon($data,$condition);
						
						$data_h = array();
						$data_h['order_id'] = $orderlist['order_id'];
						$data_h['log_time'] = TIMESTAMP ;
						$data_h['log_role'] = 'system';
						$data_h['log_user'] = '系统';
						$data_h['log_msg'] = '推送订单到海关';
						$data_h['log_orderstate'] = ORDER_STATE_SEND;
						$model_order->addOrderLog($data_h);
						
					// }else{
						// $sql = "UPDATE 33hao_order SET MESS_STATE = 30 WHERE 33hao_order.order_sn = '".$orderlist['ORDER_SN']."'";
						// $result = DB::query($sql);
						// $log.= "".PHP_EOL;
						// $sms = new Sms();
						// $sms->send('15923235201','推单信息有错误，订单编号'.$orderlist['ORDER_SN']);
					// }
					
					// 导出xml文件
					// file_put_contents('D:/log/'.'CEB311'.$orderlist['ORDER_SN'].'.xml',$orderxml);
					// file_put_contents('D:/log/'.'CEB621'.$orderlist['ORDER_SN'].'.xml',$listxml);
				}

			}else{
				// echo "临时表数据同步失败!</br>";
			}
			
			file_put_contents('D:/log/input/input.txt', $log, FILE_APPEND);
			if($return_result){ echo '成功'; }
			
            $model->commit();
        }catch (Exception $e){
            $model->rollback();
            die($e->getMessage());
        }
    }
}
