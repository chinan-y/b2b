<?php
defined('GcWebShop') or exit('Access Invalid!');

class mess_orderControl extends SystemControl{
	public function __construct(){
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

	public function mess_orderOp(){
		$lang	= Language::getLangContent();
		$model_orderlist = Model('mess_order');
		
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
    			case 'ORIGINAL_ORDER_NO':
    				$condition['ORIGINAL_ORDER_NO'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    			case 'RECEIVER_ID_NO':
    				$condition['RECEIVER_ID_NO'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
				case 'RECEIVER_NAME':
    				$condition['RECEIVER_NAME'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    			case 'RECEIVER_TEL':
    				$condition['RECEIVER_TEL'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    		}
		}
		
		$mess_order_list = $model_orderlist->getMessOrderList($condition, 20, '*');
		Tpl::output('search_field_name',trim($_GET['search_field_name']));
		Tpl::output('search_field_value',trim($_GET['search_field_value']));

		Tpl::output('mess_order_list',$mess_order_list);
		Tpl::output('page',$model_orderlist->showpage());
		
		Tpl::showpage('mess.order');
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

		$model_orderinfo = Model('mess_order');

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
		$model_orderinfo = Model('mess_order');
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
	 	 
}
