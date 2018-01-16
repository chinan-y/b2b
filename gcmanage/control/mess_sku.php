<?php
defined('GcWebShop') or exit('Access Invalid!');

class mess_skuControl extends SystemControl{
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
	 * 商品备案列表
	 */

	public function mess_skuOp(){
		$lang	= Language::getLangContent();
		$model_skulist = Model('mess_sku');
		
		//删除
		if (chksubmit()){
			if (!empty($_POST['check_SKU_INFO_ID']) && is_array($_POST['check_SKU_INFO_ID']) ){
			    $result = $model_skulist->delMessSku(array('SKU_INFO_ID'=>array('in',$_POST['SKU_INFO_ID'])));
				if ($result) {
			        $this->log(L('nc_del,mess_sku').'[ID:'.implode(',',$_POST['check_SKU_INFO_ID']).']',1);
				    showMessage($lang['nc_common_del_succ']);
				}
			}
		    showMessage($lang['nc_common_del_fail']);
		}
		
		if ($_GET['search_field_value'] != '') {
    		switch ($_GET['search_field_name']){
    			case 'SKU':
    				$condition['SKU'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    			case 'GOODS_NAME':
    				$condition['GOODS_NAME'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
				case 'POST_TAX_NO':
    				$condition['POST_TAX_NO'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    			case 'HS_CODE':
    				$condition['HS_CODE'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    		}
		}
		
		$mess_sku_list = $model_skulist->getMessSkuList($condition, 20, '*');
		Tpl::output('search_field_name',trim($_GET['search_field_name']));
		Tpl::output('search_field_value',trim($_GET['search_field_value']));

		Tpl::output('mess_sku_list',$mess_sku_list);
		Tpl::output('page',$model_skulist->showpage());
		
		Tpl::showpage('mess.sku');
	}

	/**
	 * 添加商品备案
	 */

	public function mess_sku_addOp(){
		$lang	= Language::getLangContent();
		$model_addsku = Model('mess_sku');
		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["SKU"], "require"=>"true", "message"=>$lang['mess_sku_name_no_null']),
			array("input"=>$_POST["GOODS_NAME"], "require"=>"true", "message"=>'商品名称不能为空'),
			array("input"=>$_POST["GOODS_SPEC"], "require"=>"true", "message"=>'规格型号不能为空'),
			array("input"=>$_POST["DECLARE_UNIT"], "require"=>"true", "message"=>'申报计量单位不能为空'),
			array("input"=>$_POST["POST_TAX_NO"], "require"=>"true", "message"=>'行邮税号不能为空')
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$insert_array = array();
				$insert_array['SKU'] = trim($_POST['SKU']);
				$insert_array['GOODS_NAME'] = trim($_POST['GOODS_NAME']);
				$insert_array['GOODS_SPEC'] = trim($_POST['GOODS_SPEC']);
				$insert_array['DECLARE_UNIT'] = trim($_POST['DECLARE_UNIT']);
				$insert_array['POST_TAX_NO'] = trim($_POST['POST_TAX_NO']);
				$insert_array['LEGAL_UNIT'] = trim($_POST['LEGAL_UNIT']);
				$insert_array['CONV_LEGAL_UNIT_NUM'] = round(floatval($_POST['CONV_LEGAL_UNIT_NUM']),4);
				$insert_array['HS_CODE'] = trim($_POST['HS_CODE']);
				$insert_array['IN_AREA_UNIT'] = trim($_POST['IN_AREA_UNIT']);
				$insert_array['CONV_IN_AREA_UNIT_NUM'] = round(floatval($_POST['CONV_IN_AREA_UNIT_NUM']),4);
				$insert_array['IS_EXPERIMENT_GOODS'] = intval($_POST['IS_EXPERIMENT_GOODS']);
				
				$result = $model_addsku->addMessSku($insert_array);
				if ($result){
					$url = array(
					array(
					'url'=>'index.php?gct=mess_sku&gp=mess_sku_add',
					'msg'=>$lang['continue_add_mess_sku'],
					),
					array(
					'url'=>'index.php?gct=mess_sku&gp=mess_sku',
					'msg'=>$lang['back_mess_sku_list'],
					)
					);
					$this->log(L('nc_add,mess_sku').'['.$_POST['SKU'].']',1);
					showMessage($lang['nc_common_save_succ'],$url,'html','succ',1,5000);
				}else {
					showMessage($lang['nc_common_save_fail']);
				}
			}
		}

		Tpl::showpage('mess.sku.add');
	}

	/**
	 * 编辑
	 */
	public function mess_sku_editOp(){
		$lang	= Language::getLangContent();

		$model_skuinfo = Model('mess_sku');

		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["SKU"], "require"=>"true", "message"=>$lang['mess_sku_name_no_null']),
			array("input"=>$_POST["GOODS_NAME"], "require"=>"true", "message"=>'商品名称不能为空'),
			array("input"=>$_POST["GOODS_SPEC"], "require"=>"true", "message"=>'规格型号不能为空'),
			array("input"=>$_POST["DECLARE_UNIT"], "require"=>"true", "message"=>'申报计量单位不能为空'),
			array("input"=>$_POST["POST_TAX_NO"], "require"=>"true", "message"=>'行邮税号不能为空')
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$update_array = array();
				$update_array['SKU'] = trim($_POST['SKU']);
				$update_array['GOODS_NAME'] = trim($_POST['GOODS_NAME']);
				$update_array['GOODS_SPEC'] = trim($_POST['GOODS_SPEC']);
				$update_array['DECLARE_UNIT'] = trim($_POST['DECLARE_UNIT']);
				$update_array['POST_TAX_NO'] = trim($_POST['POST_TAX_NO']);
				$update_array['LEGAL_UNIT'] = trim($_POST['LEGAL_UNIT']);
				$update_array['LEGAL_UNIT'] = trim($_POST['LEGAL_UNIT']);
				$update_array['CONV_LEGAL_UNIT_NUM'] = round(floatval($_POST['CONV_LEGAL_UNIT_NUM']),4);
				$update_array['HS_CODE'] = trim($_POST['HS_CODE']);
				$update_array['IN_AREA_UNIT'] = trim($_POST['IN_AREA_UNIT']);
				$update_array['CONV_IN_AREA_UNIT_NUM'] = round(floatval($_POST['CONV_IN_AREA_UNIT_NUM']),4);
				$update_array['IS_EXPERIMENT_GOODS'] = intval($_POST['IS_EXPERIMENT_GOODS']);
				
				$result = $model_skuinfo->editMessSku($update_array,array('SKU_INFO_ID'=>intval($_POST['SKU_INFO_ID'])));
				if ($result){
					$this->log(L('nc_edit,mess_sku').'['.$_POST['SKU'].']',1);
					showMessage($lang['nc_common_save_succ'],'index.php?gct=mess_sku&gp=mess_sku');
				}else {
					showMessage($lang['nc_common_save_fail']);
				}
			}
		}

		$skuinfo_array = $model_skuinfo->getMessSkuInfo(array('SKU_INFO_ID'=>intval($_GET['SKU_INFO_ID'])));
		if (empty($skuinfo_array)){
			showMessage($lang['illegal_parameter']);
		}

		Tpl::output('skuinfo_array',$skuinfo_array);
		Tpl::showpage('mess.sku.edit');
	}

	/**
	 * 删除
	 */
	public function mess_sku_delOp(){
		$lang	= Language::getLangContent();
		$model_skuinfo = Model('mess_sku');
		if (intval($_GET['SKU_INFO_ID']) > 0){
			$array = array(intval($_GET['SKU_INFO_ID']));
			$result = $model_skuinfo->delMessSku(array('SKU_INFO_ID'=>intval($_GET['SKU_INFO_ID'])));
			if ($result) {
			     $this->log(L('nc_del,mess_sku').'[ID:'.$_GET['SKU_INFO_ID'].']',1);
			     showMessage($lang['nc_common_del_succ'],getReferer());
			}
		}
		showMessage($lang['nc_common_del_fail'],'index.php?gct=mess_sku&gp=mess_sku');
	}
	
	public function ajaxOp(){
		switch ($_GET['branch']){
			/**
			 * 验证是否重复
			 */
			case 'check_mess_sku':
				$model_mess = Model('mess_sku');
				$condition['SKU']	= $_GET['SKU'];
				$condition['SKU_INFO_ID']	= array('neq',intval($_GET['SKU_INFO_ID']));
				$sku_list = $model_mess->getMessSkuInfo($condition);
				if (empty($sku_list)){
					echo 'true';exit;
				}else {
					echo 'false';exit;
				}
				break;
		}
	}
	
	//mess_send
	public function mess_sendOp(){
	/*	$MessageType = 'SKU_INFO';
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
		showMessage('送检报文发送成功！','index.php?gct=mess_sku&gp=mess_sku');
	}
	 	 
}
