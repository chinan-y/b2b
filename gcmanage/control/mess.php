<?php

/**
 *	海关接口 基础数据设置		BY MING
 **/

defined('GcWebShop') or exit('Access Invalid!');

class messControl extends SystemControl{
	
	public function __construct(){
		parent::__construct();
		Language::read('mess');
		/**
		 * 判断系统是否开启海关接口管理
		 */
		if ($GLOBALS['setting_config']['mess_isuse'] != 1 ){
			showMessage(Language::get('mess_api_unable'),'index.php?gct=dashboard&gp=welcome');
		}
	}
	
	/**
	 * 基础设置
	 */
	public function messOp(){
		$lang	= Language::getLangContent();
		$model_mess = Model('mess');
		/**
		 * 设置基础参数，十位电商代码、电商企业名称、报文发送地址
		 */
		if (chksubmit()){
			$mess_list = $model_mess->getMessList();
			
			$update_array = array();
			$update_array['api_customs_ebpCode'] = $_POST['ebpCode'];
			$update_array['api_customs_ebpName'] = $_POST['ebpName'];
			$update_array['api_customs_ebcCode'] = $_POST['ebcCode'];
			$update_array['api_customs_ebcName'] = $_POST['ebcName'];
			$update_array['api_customs_copCode'] = $_POST['copCode'];
			$update_array['api_customs_copName'] = $_POST['copName'];
			$update_array['api_customs_agentCode'] = $_POST['agentCode'];
			$update_array['api_customs_agentName'] = $_POST['agentName'];
			$update_array['api_customs_logisticsCode'] = $_POST['logisticsCode'];
			$update_array['api_customs_logisticsName'] = $_POST['logisticsName'];
			$update_array['api_customs_areaCode_2'] = $_POST['areaCode_2'];
			$update_array['api_customs_areaName_2'] = $_POST['areaName_2'];
			$update_array['api_customs_areaCode_7'] = $_POST['areaCode_7'];
			$update_array['api_customs_areaName_7'] = $_POST['areaName_7'];
			$update_array['api_customs_orgCode_2'] = $_POST['orgCode_2'];
			$update_array['api_customs_orgCode_7'] = $_POST['orgCode_7'];
			$update_array['api_customs_payCode_a'] = $_POST['payCode_a'];
			$update_array['api_customs_payName_a'] = $_POST['payName_a'];
			$update_array['api_customs_payCode_w'] = $_POST['payCode_w'];
			$update_array['api_customs_payName_w'] = $_POST['payName_w'];
			$update_array['api_customs_emsNo_2']   = $_POST['emsNo_2'];
			$update_array['api_customs_emsNo_7']   = $_POST['emsNo_7'];
			$update_array['api_customs_dxpId'] = $_POST['dxpId'];
			$update_array['api_customs_assureCode'] = $_POST['assureCode'];
			$update_array['api_customs_URL'] = $_POST['mess_APIURL'];
			$update_array['api_customs_ReceiverId'] = $_POST['mess_ReceiverId'];
			$result = $model_mess->updateMessSetting($update_array);
			
			if ($result === true){
				showMessage(Language::get('nc_common_save_succ'));
			}else {
				showMessage(Language::get('nc_common_save_fail'));
			}
			
			}
			
		$mess_list = $model_mess->getMessList();
		Tpl::output('mess_list',$mess_list);
		Tpl::showpage('mess.index');
	}
	
	
}
