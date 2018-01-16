<?php
defined('GcWebShop') or exit('Access Invalid!');

class mess_paymentControl extends SystemControl{
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
	 * 支付单列表
	 */

	public function mess_paymentOp(){
		$lang	= Language::getLangContent();
		$model_paymentlist = Model('mess_payment');
		
		//删除
		if (chksubmit()){
			if (!empty($_POST['check_PAYMENT_INFO_ID']) && is_array($_POST['check_PAYMENT_INFO_ID']) ){
			    $result = $model_paymentlist->delMessPayment(array('PAYMENT_INFO_ID'=>array('in',$_POST['PAYMENT_INFO_ID'])));
				if ($result) {
			        $this->log(L('nc_del,mess_payment').'[ID:'.implode(',',$_POST['check_PAYMENT_INFO_ID']).']',1);
				    showMessage($lang['nc_common_del_succ']);
				}
			}
		    showMessage($lang['nc_common_del_fail']);
		}
		
		if ($_GET['search_field_value'] != '') {
    		switch ($_GET['search_field_name']){
    			case 'PAYMENT_NO':
    				$condition['PAYMENT_NO'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    			case 'ORIGINAL_ORDER_NO':
    				$condition['ORIGINAL_ORDER_NO'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
				case 'PAY_AMOUNT':
    				$condition['PAY_AMOUNT'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    			case 'MEMO':
    				$condition['MEMO'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    		}
		}
		
		$mess_payment_list = $model_paymentlist->getMessPaymentList($condition, 20, '*');
		Tpl::output('search_field_name',trim($_GET['search_field_name']));
		Tpl::output('search_field_value',trim($_GET['search_field_value']));

		Tpl::output('mess_payment_list',$mess_payment_list);
		Tpl::output('page',$model_paymentlist->showpage());
		
		Tpl::showpage('mess.payment');
	}

	/**
	 * 添加
	 */

	public function mess_payment_addOp(){
		$lang	= Language::getLangContent();
		$model_addpayment = Model('mess_payment');
		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["CUSTOMS_CODE"], "require"=>"true", "message"=>'申报海关代码不能为空'),
			array("input"=>$_POST["BIZ_TYPE_CODE"], "require"=>"true", "message"=>'业务类型不能为空'),
			array("input"=>$_POST["ORIGINAL_ORDER_NO"], "require"=>"true", "message"=>'原始订单编号不能为空'),
			array("input"=>$_POST["PAYMENT_NO"], "require"=>"true", "message"=>'支付单号不能为空'),
			array("input"=>$_POST["PAY_AMOUNT"], "require"=>"true", "message"=>'支付总额不能为空'),
			array("input"=>$_POST["GOODS_FEE"], "require"=>"true", "message"=>'商品货款金额不能为空'),
			array("input"=>$_POST["TAX_FEE"], "require"=>"true", "message"=>'税款金额不能为空'),
			array("input"=>$_POST["CURRENCY_CODE"], "require"=>"true", "message"=>'支付币制不能为空')
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$insert_array = array();
				$insert_array['CUSTOMS_CODE'] = trim($_POST['CUSTOMS_CODE']);
				$insert_array['BIZ_TYPE_CODE'] = trim($_POST['BIZ_TYPE_CODE']);
				$insert_array['ORIGINAL_ORDER_NO'] = trim($_POST['ORIGINAL_ORDER_NO']);
				$insert_array['PAYMENT_NO'] = trim($_POST['PAYMENT_NO']);
				$insert_array['PAY_AMOUNT'] = round(floatval($_POST['PAY_AMOUNT']),2);
				$insert_array['GOODS_FEE'] = round(floatval($_POST['GOODS_FEE']),2);
				$insert_array['TAX_FEE'] = round(floatval($_POST['TAX_FEE']),2);
				$insert_array['CURRENCY_CODE'] = trim($_POST['CURRENCY_CODE']);
				$insert_array['MEMO'] = $_POST['MEMO'];
				
				$result = $model_addpayment->addMessPayment($insert_array);
				if ($result){
					$url = array(
					array(
					'url'=>'index.php?gct=mess_payment&gp=mess_payment_add',
					'msg'=>$lang['continue_add_mess_payment'],
					),
					array(
					'url'=>'index.php?gct=mess_payment&gp=mess_payment',
					'msg'=>$lang['back_mess_payment_list'],
					)
					);
					$this->log(L('nc_add,mess_payment').'['.$_POST['PAYMENT_NO'].']',1);
					showMessage($lang['nc_common_save_succ'],$url,'html','succ',1,5000);
				}else {
					showMessage($lang['nc_common_save_fail']);
				}
			}
		}

		Tpl::showpage('mess.payment.add');
	}

	/**
	 * 编辑
	 */
	public function mess_payment_editOp(){
		$lang	= Language::getLangContent();

		$model_paymentinfo = Model('mess_payment');

		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["CUSTOMS_CODE"], "require"=>"true", "message"=>'申报海关代码不能为空'),
			array("input"=>$_POST["BIZ_TYPE_CODE"], "require"=>"true", "message"=>'业务类型不能为空'),
			array("input"=>$_POST["ORIGINAL_ORDER_NO"], "require"=>"true", "message"=>'原始订单编号不能为空'),
			array("input"=>$_POST["PAYMENT_NO"], "require"=>"true", "message"=>'支付单号不能为空'),
			array("input"=>$_POST["PAY_AMOUNT"], "require"=>"true", "message"=>'支付总额不能为空'),
			array("input"=>$_POST["GOODS_FEE"], "require"=>"true", "message"=>'商品货款金额不能为空'),
			array("input"=>$_POST["TAX_FEE"], "require"=>"true", "message"=>'税款金额不能为空'),
			array("input"=>$_POST["CURRENCY_CODE"], "require"=>"true", "message"=>'支付币制不能为空')
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$update_array = array();
				$update_array['CUSTOMS_CODE'] = trim($_POST['CUSTOMS_CODE']);
				$update_array['BIZ_TYPE_CODE'] = trim($_POST['BIZ_TYPE_CODE']);
				$update_array['ORIGINAL_ORDER_NO'] = trim($_POST['ORIGINAL_ORDER_NO']);
				$update_array['PAYMENT_NO'] = trim($_POST['PAYMENT_NO']);
				$update_array['PAY_AMOUNT'] = round(floatval($_POST['PAY_AMOUNT']),2);
				$update_array['GOODS_FEE'] = round(floatval($_POST['GOODS_FEE']),2);
				$update_array['TAX_FEE'] = round(floatval($_POST['TAX_FEE']),2);
				$update_array['CURRENCY_CODE'] = trim($_POST['CURRENCY_CODE']);
				$update_array['MEMO'] = trim($_POST['MEMO']);
				
				$result = $model_paymentinfo->editMessPayment($update_array,array('PAYMENT_INFO_ID'=>intval($_POST['PAYMENT_INFO_ID'])));
				if ($result){
					$this->log(L('nc_edit,mess_payment').'['.$_POST['PAYMENT_NO'].']',1);
					showMessage($lang['nc_common_save_succ'],'index.php?gct=mess_payment&gp=mess_payment');
				}else {
					showMessage($lang['nc_common_save_fail']);
				}
			}
		}

		$paymentinfo_array = $model_paymentinfo->getMessPaymentInfo(array('PAYMENT_INFO_ID'=>intval($_GET['PAYMENT_INFO_ID'])));
		if (empty($paymentinfo_array)){
			showMessage($lang['illegal_parameter']);
		}

		Tpl::output('paymentinfo_array',$paymentinfo_array);
		Tpl::showpage('mess.payment.edit');
	}

	/**
	 * 删除
	 */
	public function mess_payment_delOp(){
		$lang	= Language::getLangContent();
		$model_paymentinfo = Model('mess_payment');
		if (intval($_GET['PAYMENT_INFO_ID']) > 0){
			$array = array(intval($_GET['PAYMENT_INFO_ID']));
			$result = $model_paymentinfo->delMessPayment(array('PAYMENT_INFO_ID'=>intval($_GET['PAYMENT_INFO_ID'])));
			if ($result) {
			     $this->log(L('nc_del,mess_payment').'[ID:'.$_GET['PAYMENT_INFO_ID'].']',1);
			     showMessage($lang['nc_common_del_succ'],getReferer());
			}
		}
		showMessage($lang['nc_common_del_fail'],'index.php?gct=mess_payment&gp=mess_payment');
	}
	
	public function ajaxOp(){
		switch ($_GET['branch']){
			/**
			 * 验证是否重复
			 */
			case 'check_mess_payment':
				$model_mess = Model('mess_payment');
				$condition['PAYMENT_NO']	= $_GET['PAYMENT_NO'];
				$condition['PAYMENT_INFO_ID']	= array('neq',intval($_GET['PAYMENT_INFO_ID']));
				$payment_list = $model_mess->getMessPaymentInfo($condition);
				if (empty($payment_list)){
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
		showMessage('送检报文发送成功！','index.php?gct=mess_payment&gp=mess_payment');
	}
	 	 
}
