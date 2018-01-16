<?php
defined('GcWebShop') or exit('Access Invalid!');

class mess_order_infoControl extends SystemControl{
	public function __construct(){
		include(BASE_PATH.'/api/mess/common.php');
		$this->guid = guid();
		$this->appTime  = date('YmdHis', time());
		$this->declTime = date('Ymd', time());
		$this->ieDate 	 = date('Ymd', strtotime('-10 days'));
		parent::__construct();
		Language::read('mess');
		$this->search_arr = $_REQUEST;
		/**
		 * 判断系统是否开启海关接口管理
		 */
		if ($GLOBALS['setting_config']['mess_isuse'] != 1 ){
			showMessage(Language::get('mess_api_unable'),'index.php?gct=dashboard&gp=welcome');
		}
	}
	
	/**
	 * 订单列表
	 */

	public function indexOp(){
		$lang	= Language::getLangContent();
		$model_orderlist = Model('mess_order_info');
		
		//删除
		if (chksubmit()){
			if (!empty($_POST['check_ORDER_ID']) && is_array($_POST['check_ORDER_ID']) ){
			    $result = $model_orderlist->delMessOrder(array('ORDER_ID'=>array('in',$_POST['ORDER_ID'])));
				if ($result) {
			        $this->log(L('nc_del,mess_order').'[ID:'.implode(',',$_POST['check_ORDER_ID']).']',1);
				    showMessage($lang['nc_common_del_succ']);
				}
			}
		    showMessage($lang['nc_common_del_fail']);
		}
		
		if ($_GET['search_field_value'] != '') {
    		switch ($_GET['search_field_name']){
    			case 'ORDER_SN':
    				$condition['ORDER_SN'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    			case 'PAY_SN':
    				$condition['PAY_SN'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
				case 'list_invtNo':
    				$condition['list_invtNo'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    			case 'shipping_code':
    				$condition['shipping_code'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
				case 'BUYER_NAME':
    				$condition['RECEIVER_NAME'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
				case 'BUYER_IDNUM':
    				$condition['RECEIVER_ID_NO'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    		}
		}
		
		$mess_order_list = $model_orderlist->getMessOrderList($condition,'20','ORDER_ID DESC','distinct ORDER_ID,ORDER_SN,PAY_SN,ORDER_AMOUNT,RECEIVER_NAME,RECEIVER_ID_NO,list_invtNo,list_info,list_status,MAKE_CSV,order_status,order_info,LI_MEMO,OI_MEMO,OI_SUCCESS,LI_SUCCESS,OIF_MEMO,OIF_ORDER_NO,OIF_STATUS_CODE,ERP_MEMO,shipping_code');
		//var_dump($mess_order_list);
		Tpl::output('search_field_name',trim($_GET['search_field_name']));
		Tpl::output('search_field_value',trim($_GET['search_field_value']));

		Tpl::output('mess_order_list',$mess_order_list);
		Tpl::output('page',$model_orderlist->showpage());
		
		Tpl::showpage('mess_order_info.index');
	}
	
	/**
	 * 订单 详情查看
	 */

	public function mess_order_infoviewOp(){
		$lang	= Language::getLangContent();
		$model_orderlist = Model('mess_order_info');
		$model_goods = Model('goods');
		$model_countrycode = Model('mess_country');
		
		if ($_POST['order_id'] != '') {
			$result = $model_orderlist->editMessOrder(array('ERP_MEMO'=>trim($_POST['noteinfo'])),array('ORDER_ID'=>intval($_POST['order_id'])));
			if($result){
				showMessage('订单加急成功！');
			}
		}
		
		if ($_GET['ORDER_ID'] != '') {
			$result = $model_orderlist->editMessOrder(array('ERP_MEMO'=>trim($_GET['ERP_MEMO'])),array('ORDER_ID'=>intval($_GET['ORDER_ID'])));
			if($result){
				showMessage('订单加急成功！');
			}
		}
		
		if ($_GET['order_id'] != '') {
			$condition = array();
			$condition['ORDER_ID'] = 	trim($_GET['order_id']);
		}
		
		$mess_order_list = $model_orderlist->getMessOrderList($condition,'10','ORDER_ID DESC','*');
		foreach($mess_order_list as $k=>$v){
			$goods_list = $model_goods->getGoodsInfo(array('goods_id'=>$v['GOODS_ID']));
			$mess_order_list[$k]['goods_name'] = $goods_list['goods_name'];
			$mess_order_list[$k]['hs_code'] = $goods_list['goods_hscode'];
			$country = $model_countrycode->getMessCountryInfo(array('code'=>$v['mess_country_code']));
			$mess_order_list[$k]['country_name'] = $country['name'];
		}
		
		
		$receiver_info = unserialize($mess_order_list[0]['RECEIVER_INFO']);

		
		Tpl::output('mess_order_list',$mess_order_list);
		Tpl::output('receiver_info',$receiver_info);
		Tpl::output('page',$model_orderlist->showpage());
		
		Tpl::showpage('mess_order_info.view');
	}

	/**
	 * 添加
	 */

	public function mess_order_addOp(){
		$lang	= Language::getLangContent();
		$model_addorder = Model('mess_order');
		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["CUSTOMS_CODE"], "require"=>"true", "message"=>'申报海关代码不能为空'),
			array("input"=>$_POST["BIZ_TYPE_CODE"], "require"=>"true", "message"=>'业务类型不能为空'),
			array("input"=>$_POST["ORIGINAL_ORDER_NO"], "require"=>"true", "message"=>'原始订单编号不能为空'),
			array("input"=>$_POST["RECEIVER_ID_NO"], "require"=>"true", "message"=>'收货人身份证号码不能为空'),
			array("input"=>$_POST["RECEIVER_NAME"], "require"=>"true", "message"=>'收货人姓名不能为空'),
			array("input"=>$_POST["RECEIVER_ADDRESS"], "require"=>"true", "message"=>'收货人地址不能为空'),
			array("input"=>$_POST["RECEIVER_TEL"], "require"=>"true", "message"=>'收货人电话号码不能为空'),
			array("input"=>$_POST["GOODS_FEE"], "require"=>"true", "message"=>'货款总额不能为空'),
			array("input"=>$_POST["SORTLINE_ID"], "require"=>"true", "message"=>'分拣线不能为空')
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$insert_array = array();
				$insert_array['CUSTOMS_CODE'] = trim($_POST['CUSTOMS_CODE']);
				$insert_array['BIZ_TYPE_CODE'] = trim($_POST['BIZ_TYPE_CODE']);
				$insert_array['ORIGINAL_ORDER_NO'] = trim($_POST['ORIGINAL_ORDER_NO']);
				$insert_array['DESP_ARRI_COUNTRY_CODE'] = trim($_POST['DESP_ARRI_COUNTRY_CODE']);
				$insert_array['SHIP_TOOL_CODE'] = trim($_POST['SHIP_TOOL_CODE']);
				$insert_array['RECEIVER_ID_NO'] = trim($_POST['RECEIVER_ID_NO']);
				$insert_array['RECEIVER_NAME'] = trim($_POST['RECEIVER_NAME']);
				$insert_array['RECEIVER_ADDRESS'] = trim($_POST['RECEIVER_ADDRESS']);
				$insert_array['RECEIVER_TEL'] = trim($_POST['RECEIVER_TEL']);
				$insert_array['GOODS_FEE'] = round(floatval($_POST['GOODS_FEE']),2);
				$insert_array['TAX_FEE'] = round(floatval($_POST['TAX_FEE']),2);
				$insert_array['GROSS_WEIGHT'] = round(floatval($_POST['GROSS_WEIGHT']),3);
				$insert_array['SORTLINE_ID'] = trim($_POST['SORTLINE_ID']);
				
				$result = $model_addorder->addMessOrder($insert_array);
				if ($result){
					$url = array(
					array(
					'url'=>'index.php?gct=mess_order&gp=mess_order_add',
					'msg'=>$lang['continue_add_mess_order'],
					),
					array(
					'url'=>'index.php?gct=mess_order&gp=mess_order',
					'msg'=>$lang['back_mess_order_list'],
					)
					);
					$this->log(L('nc_add,mess_order').'['.$_POST['ORIGINAL_ORDER_NO'].']',1);
					showMessage($lang['nc_common_save_succ'],$url,'html','succ',1,5000);
				}else {
					showMessage($lang['nc_common_save_fail']);
				}
			}
		}

		Tpl::showpage('mess.order.add');
	}

	/**
	 * 编辑
	 */
	public function mess_order_editOp(){
		$lang	= Language::getLangContent();

		$model_orderinfo = Model('mess_order_info');

		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["CUSTOMS_CODE"], "require"=>"true", "message"=>'申报海关代码不能为空'),
			array("input"=>$_POST["BIZ_TYPE_CODE"], "require"=>"true", "message"=>'业务类型不能为空'),
			array("input"=>$_POST["ORIGINAL_ORDER_NO"], "require"=>"true", "message"=>'原始订单编号不能为空'),
			array("input"=>$_POST["RECEIVER_ID_NO"], "require"=>"true", "message"=>'收货人身份证号码不能为空'),
			array("input"=>$_POST["RECEIVER_NAME"], "require"=>"true", "message"=>'收货人姓名不能为空'),
			array("input"=>$_POST["RECEIVER_ADDRESS"], "require"=>"true", "message"=>'收货人地址不能为空'),
			array("input"=>$_POST["RECEIVER_TEL"], "require"=>"true", "message"=>'收货人电话号码不能为空'),
			array("input"=>$_POST["GOODS_FEE"], "require"=>"true", "message"=>'货款总额不能为空'),
			array("input"=>$_POST["SORTLINE_ID"], "require"=>"true", "message"=>'分拣线不能为空')
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$update_array = array();
				$update_array['CUSTOMS_CODE'] = trim($_POST['CUSTOMS_CODE']);
				$update_array['BIZ_TYPE_CODE'] = trim($_POST['BIZ_TYPE_CODE']);
				$update_array['ORIGINAL_ORDER_NO'] = trim($_POST['ORIGINAL_ORDER_NO']);
				$update_array['DESP_ARRI_COUNTRY_CODE'] = trim($_POST['DESP_ARRI_COUNTRY_CODE']);
				$update_array['SHIP_TOOL_CODE'] = trim($_POST['SHIP_TOOL_CODE']);
				$update_array['RECEIVER_ID_NO'] = trim($_POST['RECEIVER_ID_NO']);
				$update_array['RECEIVER_NAME'] = trim($_POST['RECEIVER_NAME']);
				$update_array['RECEIVER_ADDRESS'] = trim($_POST['RECEIVER_ADDRESS']);
				$update_array['RECEIVER_TEL'] = trim($_POST['RECEIVER_TEL']);
				$update_array['GOODS_FEE'] = round(floatval($_POST['GOODS_FEE']),2);
				$update_array['TAX_FEE'] = round(floatval($_POST['TAX_FEE']),2);
				$update_array['GROSS_WEIGHT'] = round(floatval($_POST['GROSS_WEIGHT']),3);
				$update_array['SORTLINE_ID'] = trim($_POST['SORTLINE_ID']);
				
				$result = $model_orderinfo->editMessOrder($update_array,array('ORDER_ID'=>intval($_POST['ORDER_ID'])));
				if ($result){
					$this->log(L('nc_edit,mess_order').'['.$_POST['ORIGINAL_ORDER_NO'].']',1);
					showMessage($lang['nc_common_save_succ'],'index.php?gct=mess_order&gp=mess_order');
				}else {
					showMessage($lang['nc_common_save_fail']);
				}
			}
		}

		$orderinfo_array = $model_orderinfo->getMessOrderInfo(array('ORDER_ID'=>intval($_GET['ORDER_ID'])));
		if (empty($orderinfo_array)){
			showMessage($lang['illegal_parameter']);
		}

		Tpl::output('orderinfo_array',$orderinfo_array);
		Tpl::showpage('mess.order.edit');
	}

	/**
	 * 删除
	 */
	public function mess_order_delOp(){
		$lang	= Language::getLangContent();
		$model_orderinfo = Model('mess_order_info');
		if (intval($_GET['ORDER_ID']) > 0){
			$array = array(intval($_GET['ORDER_ID']));
			$result = $model_orderinfo->delMessOrder(array('ORDER_ID'=>intval($_GET['ORDER_ID'])));
			if ($result) {
			     $this->log(L('nc_del,mess_order').'[ID:'.$_GET['ORDER_ID'].']',1);
			     showMessage($lang['nc_common_del_succ'],getReferer());
			}
		}
		showMessage($lang['nc_common_del_fail'],'index.php?gct=mess_order&gp=mess_order');
	}
	
	public function ajaxOp(){
		switch ($_GET['branch']){
			/**
			 * 验证是否重复
			 */
			case 'check_mess_order':
				$model_mess = Model('mess_order');
				$condition['ORIGINAL_ORDER_NO']	= $_GET['ORIGINAL_ORDER_NO'];
				$condition['ORDER_ID']	= array('neq',intval($_GET['ORDER_ID']));
				$order_list = $model_mess->getMessOrderInfo($condition);
				if (empty($order_list)){
					echo 'true';exit;
				}else {
					echo 'false';exit;
				}
				break;
		}
	}
	
	public function mess_sendOp(){
	/*	$MessageType = 'PAYMENT_INFO';
		$ActionType = '1';
		$SKU = '';
		$GOODS_NAME = '';
		$GOODS_SPEC = '';
		$DECLARE_UNIT = '';
		$POST_TAX_NO = '';
		$LEGAL_UNIT = '';
		$CONV_LEGAL_UNIT_NUM = '';
		$HS_CODE = '';
		$IN_AREA_UNIT = '';
		$CONV_IN_AREA_UNIT_NUM = '';
		$IS_EXPERIMENT_GOODS = '';
	*/	
		showMessage('送检报文发送成功！','index.php?gct=mess_order&gp=mess_order');
	}
	
	/**
	 * 重推订单
	 */
	public function repeat_orderOp(){
		$res_order = $this->repeat_order($_GET['order_id']);
		if($res_order == 'True'){
			Model('mess_order_info')->editMessOrder(array('OI_SUCCESS'=>1),array('ORDER_ID'=>$_GET['order_id']));
			showMessage('成功');
		}else{
			$res_order = $this->repeat_order($_GET['order_id']);
			if($res_order == 'True'){
				Model('mess_order_info')->editMessOrder(array('OI_SUCCESS'=>1),array('ORDER_ID'=>$_GET['order_id']));
				showMessage('成功');
			}else{
				$res_order = $this->repeat_order($_GET['order_id']);
				if($res_order == 'True'){
					Model('mess_order_info')->editMessOrder(array('OI_SUCCESS'=>1),array('ORDER_ID'=>$_GET['order_id']));
					showMessage('成功');
				}else{
					$res_order = $this->repeat_order($_GET['order_id']);
					if($res_order == 'True'){
						Model('mess_order_info')->editMessOrder(array('OI_SUCCESS'=>1),array('ORDER_ID'=>$_GET['order_id']));
						showMessage('成功');
					}else{
						$res_order = $this->repeat_order($_GET['order_id']);
					}
				}
			}
		}
	}
	
	/**
	 * 重推清单
	 */
	public function repeat_listOp(){
		$res_list = $this->repeat_list($_GET['order_id']);
		if($res_list == 'True'){
			Model('mess_order_info')->editMessOrder(array('LI_SUCCESS'=>1),array('ORDER_ID'=>$_GET['order_id']));
			showMessage('成功');
		}else{
			$res_list = $this->repeat_list($_GET['order_id']);
			if($res_list == 'True'){
				Model('mess_order_info')->editMessOrder(array('LI_SUCCESS'=>1),array('ORDER_ID'=>$_GET['order_id']));
				showMessage('成功');
			}else{
				$res_list = $this->repeat_list($_GET['order_id']);
				if($res_list == 'True'){
					Model('mess_order_info')->editMessOrder(array('LI_SUCCESS'=>1),array('ORDER_ID'=>$_GET['order_id']));
					showMessage('成功');
				}else{
					$res_list = $this->repeat_list($_GET['order_id']);
					if($res_list == 'True'){
						Model('mess_order_info')->editMessOrder(array('LI_SUCCESS'=>1),array('ORDER_ID'=>$_GET['order_id']));
						showMessage('成功');
					}else{
						$res_list = $this->repeat_list($_GET['order_id']);
					}
				}
			}
		}
	}
	
	public function repeat_order($order_id){
		$customs_code = Model('setting')->getListSetting();
		$order_info = Model('order')->getOrderInfo(array('order_id'=>$order_id),array('order_goods','order_common'));
		//订单报文
		$orderxml = '<ceb:CEB311Message guid="'.$this->guid.'" version="1.0" xmlns:ceb="http://www.chinaport.gov.cn/ceb" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">	
	<ceb:Order>
		<ceb:OrderHead>
			<ceb:guid>'.$this->guid.'</ceb:guid>
			<ceb:appType>1</ceb:appType>
			<ceb:appTime>'.$this->appTime.'</ceb:appTime>
			<ceb:appStatus>2</ceb:appStatus>
			<ceb:orderType>I</ceb:orderType>
			<ceb:orderNo>'.$order_info['pay_sn'].'</ceb:orderNo>'."\n\t\t\t";
			if($order_info['payment_code'] == 'ccbpay'){
				$orderxml .= '<ceb:ebpCode>'.$customs_code['api_customs_payCode_c'].'</ceb:ebpCode>'."\n\t\t\t";
				$orderxml .= '<ceb:ebpName>'.$customs_code['api_customs_payName_c'].'</ceb:ebpName>'."\n\t\t\t";
			}else{
				$orderxml .= '<ceb:ebpCode>'.$customs_code['api_customs_ebpCode'].'</ceb:ebpCode>'."\n\t\t\t";
				$orderxml .= '<ceb:ebpName>'.$customs_code['api_customs_ebpName'].'</ceb:ebpName>'."\n\t\t\t";
			}
			foreach($order_info['extend_order_goods'] as $val) {
				$discount += $val['discount_total'];
				$goodsValue = $order_info['goods_amount'] + $discount;
			}
			$orderxml .= '<ceb:ebcCode>'.$customs_code['api_customs_ebcCode'].'</ceb:ebcCode>
			<ceb:ebcName>'.$customs_code['api_customs_ebcName'].'</ceb:ebcName>
			<ceb:goodsValue>'.$goodsValue.'</ceb:goodsValue>
			<ceb:freight>'.intval($order_info['shipping_fee']).'</ceb:freight>
			<ceb:discount>'.$discount.'</ceb:discount>
			<ceb:taxTotal>'.$order_info['order_tax'].'</ceb:taxTotal>
			<ceb:acturalPaid>'.$order_info['order_amount'].'</ceb:acturalPaid>
			<ceb:currency>142</ceb:currency>
			<ceb:buyerRegNo>'.$order_info['buyer_name'].'</ceb:buyerRegNo>
			<ceb:buyerName>'.$order_info['extend_order_common']['order_name'].'</ceb:buyerName>
			<ceb:buyerIdType>1</ceb:buyerIdType>
			<ceb:buyerIdNumber>'.strtoupper($order_info['extend_order_common']['reciver_idnum']).'</ceb:buyerIdNumber>'."\n\t\t\t";
			if($order_info['payment_code'] == 'alipay'){
				$orderxml .= '<ceb:payCode>'.$customs_code['api_customs_payCode_a'].'</ceb:payCode>'."\n\t\t\t";
				$orderxml .= '<ceb:payName>'.$customs_code['api_customs_payName_a'].'</ceb:payName>'."\n\t\t\t";	
			}else if($order_info['payment_code'] == 'wxpay'){
				$orderxml .= '<ceb:payCode>'.$customs_code['api_customs_payCode_w'].'</ceb:payCode>'."\n\t\t\t";	
				$orderxml .= '<ceb:payName>'.$customs_code['api_customs_payName_w'].'</ceb:payName>'."\n\t\t\t";	
			}else if($order_info['payment_code'] == 'gzbank'){
				$orderxml .= '<ceb:payCode>'.$customs_code['api_customs_payCode_g'].'</ceb:payCode>'."\n\t\t\t";	
				$orderxml .= '<ceb:payName>'.$customs_code['api_customs_payName_g'].'</ceb:payName>'."\n\t\t\t";
			}else if($order_info['payment_code'] == 'ccbpay'){
				$orderxml .= '<ceb:payCode>'.$customs_code['api_customs_payCode_c'].'</ceb:payCode>'."\n\t\t\t";	
				$orderxml .= '<ceb:payName>'.$customs_code['api_customs_payName_c'].'</ceb:payName>'."\n\t\t\t";	
			}else if($order_info['payment_code'] == 'tonglian'){
				$orderxml .= '<ceb:payCode>'.$customs_code['api_customs_payCode_t'].'</ceb:payCode>'."\n\t\t\t";	
				$orderxml .= '<ceb:payName>'.$customs_code['api_customs_payName_t'].'</ceb:payName>'."\n\t\t\t";	
			}
			$orderxml .= '<ceb:payTransactionId></ceb:payTransactionId>
			<ceb:batchNumbers></ceb:batchNumbers>
			<ceb:consignee>'.$order_info['extend_order_common']['reciver_name'].'</ceb:consignee>
			<ceb:consigneeTelephone>'.$order_info['extend_order_common']['reciver_info']['mob_phone'].'</ceb:consigneeTelephone>
			<ceb:consigneeAddress>'.$order_info['extend_order_common']['reciver_info']['address'].'</ceb:consigneeAddress>
			<ceb:note>'.$order_info['extend_order_common']['promotion_info'].'</ceb:note>
		</ceb:OrderHead>'."\n\t\t";
	
	foreach($order_info['extend_order_goods'] as $key =>$val) {
		$goods_info = Model('goods')->getGoodsInfo(array('goods_id'=>$val['goods_id']),'goods_commonid,goods_serial,sku_spec,pack_units');
		$price = round($val['goods_pay_price'] / $val['goods_num'], 2);
		$totalPrice = $val['goods_pay_price'] ;
		if (preg_match('/^(\d+)$/',$goods_info['goods_serial'])){
			$barCode = $goods_info['goods_serial'];
		}else{
			$barCode = substr($goods_info['goods_serial'],2);
			if (preg_match('/^(\d+)$/',$barCode)){
				$barCode = $barCode;
			}else{
				$barCode= substr($barCode , 0 ,strpos($barCode,'-'));
			}
		}
		$key += 1;
		$orderxml .= '<ceb:OrderList>
			<ceb:gnum>'.$key.'</ceb:gnum>
			<ceb:itemNo>'.$goods_info['goods_serial'].'</ceb:itemNo>
			<ceb:itemName>'.htmlspecialchars($val['goods_name']).'</ceb:itemName>
			<ceb:itemDescribe>'.htmlspecialchars($goods_info['sku_spec']).'</ceb:itemDescribe>
			<ceb:barCode>'.$barCode.'</ceb:barCode>
			<ceb:unit>'.$goods_info['pack_units'].'</ceb:unit>
			<ceb:qty>'.$val['goods_num'].'</ceb:qty>
			<ceb:price>'.$price.'</ceb:price>
			<ceb:totalPrice>'.$totalPrice.'</ceb:totalPrice>
			<ceb:currency>142</ceb:currency>
			<ceb:country>'.$val['mess_country_code'].'</ceb:country>
			<ceb:note></ceb:note>
		</ceb:OrderList>'."\n\t";
	}
	$orderxml .= '</ceb:Order>
	<ceb:BaseTransfer>
		<ceb:copCode>'.$customs_code['api_customs_copCode'].'</ceb:copCode>
		<ceb:copName>'.$customs_code['api_customs_copName'].'</ceb:copName>
		<ceb:dxpMode>DXP</ceb:dxpMode>
		<ceb:dxpId>'.$customs_code['api_customs_dxpId'].'</ceb:dxpId>
		<ceb:note></ceb:note>
	</ceb:BaseTransfer>
</ceb:CEB311Message>';
		$orderxml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".$orderxml;
		
		$data_order = base64_encode($orderxml);
		return _curl_post( APIURL, array('data'=>$data_order));
		
	}

	/**
	 * 重推清单
	 */
	public function repeat_list($order_id){
		$customs_code = Model('setting')->getListSetting();
		$order_info = Model('order')->getOrderInfo(array('order_id'=>$order_id),array('order_goods','order_common'));
		//清单报文
		$listxml = "<ceb:CEB621Message guid='".$this->guid."' version='1.0'  xmlns:ceb='http://www.chinaport.gov.cn/ceb' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'>
	<ceb:Inventory>
		<ceb:InventoryHead>
			<ceb:guid>".$this->guid."</ceb:guid>
			<ceb:appType>1</ceb:appType>
			<ceb:appTime>".$this->appTime."</ceb:appTime>
			<ceb:appStatus>2</ceb:appStatus>
			<ceb:orderNo>".$order_info['pay_sn']."</ceb:orderNo>\n\t\t\t";
			if($order_info['payment_code'] == 'ccbpay'){
				$listxml .= "<ceb:ebpCode>".$customs_code['api_customs_payCode_c']."</ceb:ebpCode>\n\t\t\t";
				$listxml .= "<ceb:ebpName>".$customs_code['api_customs_payName_c']."</ceb:ebpName>\n\t\t\t";
			}else{
				$listxml .= "<ceb:ebpCode>".$customs_code['api_customs_ebpCode']."</ceb:ebpCode>\n\t\t\t";
				$listxml .= "<ceb:ebpName>".$customs_code['api_customs_ebpName']."</ceb:ebpName>\n\t\t\t";
			}
			$listxml .= "<ceb:ebcCode>".$customs_code['api_customs_ebcCode']."</ceb:ebcCode>
			<ceb:ebcName>".$customs_code['api_customs_ebcName']."</ceb:ebcName>
			<ceb:logisticsNo>".$order_info['shipping_code']."</ceb:logisticsNo>
			<ceb:logisticsCode>".$customs_code['api_customs_logisticsCode']."</ceb:logisticsCode>
			<ceb:logisticsName>".$customs_code['api_customs_logisticsName']."</ceb:logisticsName>
			<ceb:copNo>".$order_info['order_sn']."</ceb:copNo>
			<ceb:preNo></ceb:preNo>
			<ceb:assureCode>".$customs_code['api_customs_assureCode']."</ceb:assureCode>\n\t\t\t";
			if($order_info['store_id'] == 2){
				$customs_id = 8012 ;
				$sortline_id = 'SORTLINE01';
				$listxml .= "<ceb:emsNo>".$customs_code['api_customs_emsNo_2']."</ceb:emsNo>\n\t\t\t";
			}else if($order_info['store_id'] == 7){
				$customs_id = 8013 ;
				$sortline_id = 'SORTLINE02';
				$listxml .= "<ceb:emsNo>".$customs_code['api_customs_emsNo_7']."</ceb:emsNo>\n\t\t\t";
			}
			$listxml .= "<ceb:invtNo></ceb:invtNo>
			<ceb:ieFlag>I</ceb:ieFlag>
			<ceb:declTime>".$this->declTime."</ceb:declTime>
			<ceb:customsCode>".$customs_id."</ceb:customsCode>
			<ceb:portCode>".$customs_id."</ceb:portCode>
			<ceb:ieDate>".$this->ieDate."</ceb:ieDate>
			<ceb:buyerIdType>1</ceb:buyerIdType>
			<ceb:buyerIdNumber>".strtoupper($order_info['extend_order_common']['reciver_idnum'])."</ceb:buyerIdNumber>
			<ceb:buyerName>".$order_info['extend_order_common']['order_name']."</ceb:buyerName>
			<ceb:buyerTelephone>".$order_info['extend_order_common']['reciver_info']['mob_phone']."</ceb:buyerTelephone>
			<ceb:consigneeAddress>".$order_info['extend_order_common']['reciver_info']['address']."</ceb:consigneeAddress>
			<ceb:agentCode>".$customs_code['api_customs_agentCode']."</ceb:agentCode>
			<ceb:agentName>".$customs_code['api_customs_agentName']."</ceb:agentName>\n\t\t\t";
			if($order_info['store_id'] == 2){
				$listxml .= "<ceb:areaCode>".$customs_code['api_customs_areaCode_2']."</ceb:areaCode>\n\t\t\t";
				$listxml .= "<ceb:areaName>".$customs_code['api_customs_areaName_2']."</ceb:areaName>\n\t\t\t";
			}else if($order_info['store_id'] == 7){
				$listxml .= "<ceb:areaCode>".$customs_code['api_customs_areaCode_7']."</ceb:areaCode>\n\t\t\t";
				$listxml .= "<ceb:areaName>".$customs_code['api_customs_areaName_7']."</ceb:areaName>\n\t\t\t";
			}
			foreach($order_info['extend_order_goods'] as $val) {
				$netWeight += $val['goods_weight'] * $val['goods_num'];
				$grossWeight = $netWeight * 1.2;
			}
			$listxml .= "<ceb:tradeMode>1210</ceb:tradeMode>
			<ceb:trafMode>Y</ceb:trafMode>
			<ceb:trafNo></ceb:trafNo>
			<ceb:voyageNo></ceb:voyageNo>
			<ceb:billNo></ceb:billNo>
			<ceb:loctNo></ceb:loctNo>
			<ceb:licenseNo></ceb:licenseNo>
			<ceb:country>142</ceb:country>
			<ceb:freight>".intval($order_info['shipping_fee'])."</ceb:freight>
			<ceb:insuredFee>0</ceb:insuredFee>
			<ceb:currency>142</ceb:currency>
			<ceb:packNo>1</ceb:packNo>
			<ceb:grossWeight>".$grossWeight."</ceb:grossWeight>
			<ceb:netWeight>".$netWeight."</ceb:netWeight>
			<ceb:note></ceb:note>
			<ceb:sortlineId>".$sortline_id."</ceb:sortlineId>\n\t\t\t";
			if($order_info['store_id'] == 2){
				$listxml .= "<ceb:orgCode>".$customs_code['api_customs_orgCode_2']."</ceb:orgCode>\n\t\t\t";
			}else if($order_info['store_id'] == 7){
				$listxml .= "<ceb:orgCode>".$customs_code['api_customs_orgCode_7']."</ceb:orgCode>\n\t\t";
			}
		$listxml .= "</ceb:InventoryHead>\n\t\t";	
				
	foreach($order_info['extend_order_goods'] as $key =>$val) {
		$goods_info = Model('goods')->getGoodsInfo(array('goods_id'=>$val['goods_id']));
		$goods_com  = Model('goods')->getGoodeCommonInfo(array('goods_commonid'=>$goods_info['goods_commonid']),'pack_units,goods_reduced,unit1,qty2,unit2');
		$price = round($val['goods_pay_price'] / $val['goods_num'], 2);
		$totalPrice = $val['goods_pay_price'] ;
		$key += 1;
		$listxml .= "<ceb:InventoryList>
			<ceb:gnum>".$key."</ceb:gnum>
			<ceb:itemRecordNo>".$goods_info['goods_serial']."</ceb:itemRecordNo>
			<ceb:itemNo>".$goods_info['goods_serial']."</ceb:itemNo>
			<ceb:itemName>".htmlspecialchars($val['goods_name'])."</ceb:itemName>
			<ceb:gcode>".htmlspecialchars($goods_info['goods_hscode'])."</ceb:gcode>
			<ceb:gname>".htmlspecialchars($goods_info['records_name'])."</ceb:gname>
			<ceb:gmodel>".htmlspecialchars($goods_info['sku_spec'])."</ceb:gmodel>
			<ceb:barCode></ceb:barCode>
			<ceb:country>".$val['mess_country_code']."</ceb:country>
			<ceb:currency>142</ceb:currency>
			<ceb:qty>".$val['goods_num']."</ceb:qty>
			<ceb:unit>".$goods_com['pack_units']."</ceb:unit>
			<ceb:qty1>".$goods_com['goods_reduced']."</ceb:qty1>
			<ceb:unit1>".$goods_com['unit1']."</ceb:unit1>
			<ceb:qty2>".$goods_com['qty2']."</ceb:qty2>
			<ceb:unit2>".$goods_com['unit2']."</ceb:unit2>
			<ceb:price>".$price."</ceb:price>
			<ceb:totalPrice>".$totalPrice."</ceb:totalPrice>
			<ceb:note></ceb:note>
		</ceb:InventoryList>\n\t";
	}
		$listxml .= "</ceb:Inventory>
	<ceb:BaseTransfer>
		<ceb:copCode>".$customs_code['api_customs_copCode']."</ceb:copCode>
		<ceb:copName>".$customs_code['api_customs_copName']."</ceb:copName>
		<ceb:dxpMode>DXP</ceb:dxpMode>
		<ceb:dxpId>".$customs_code['api_customs_dxpId']."</ceb:dxpId>
		<ceb:note></ceb:note>
	</ceb:BaseTransfer>
</ceb:CEB621Message>";
		$listxml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".$listxml;
		
		$data_list  = base64_encode($listxml);			
		return _curl_post( APIURL, array('data'=>$data_list)); 
	}	
}