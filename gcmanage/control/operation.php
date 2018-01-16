<?php
/**
 * 网站设置
 *
 *
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');
class operationControl extends SystemControl{
	private $links = array(
		array('url'=>'gct=operation&gp=setting','lang'=>'nc_operation_set'),
	);
	public function __construct(){
		parent::__construct();
		Language::read('setting');
	}

	/**
	 * 基本设置
	 */
	public function settingOp(){
		$model_setting = Model('setting');
		if (chksubmit()){
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(

			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$update_array = array();
				$update_array['salescredit_isuse'] = $_POST['salescredit_isuse'];
				$update_array['salescredit_rebate'] = intval($_POST['salescredit_rebate'])?$_POST['salescredit_rebate']:0;
				$update_array['one_rank_rebate'] = $_POST['one_rank_rebate'];
				$update_array['one_rebate_rate'] = intval($_POST['one_rebate_rate'])?$_POST['one_rebate_rate']:0;
				$update_array['two_rank_rebate'] = $_POST['two_rank_rebate'];
				$update_array['two_rebate_rate'] = intval($_POST['two_rebate_rate'])?$_POST['two_rebate_rate']:0;
				$update_array['three_rank_rebate'] = $_POST['three_rank_rebate'];
				$update_array['three_rebate_rate'] = intval($_POST['three_rebate_rate'])?$_POST['three_rebate_rate']:0;
                $update_array['promotion_allow'] = $_POST['promotion_allow'];
                $update_array['groupbuy_allow'] = $_POST['groupbuy_allow'];
                $update_array['points_isuse'] = $_POST['points_isuse'];
                $update_array['pointshop_isuse'] = $_POST['pointshop_isuse'];
                $update_array['browse_goods'] = $_POST['browse_goods'];
                $update_array['browse_goods_up'] = $_POST['browse_goods_up'];
                $update_array['exchange_isuse'] = $_POST['exchange_isuse'];
                $update_array['exchange_rate'] = $_POST['exchange_rate'];
                $update_array['voucher_allow'] = $_POST['voucher_allow'];
                $update_array['pointprod_isuse'] = $_POST['pointprod_isuse'];
                $update_array['points_reg'] = intval($_POST['points_reg'])?$_POST['points_reg']:0;
                $update_array['points_sign'] = intval($_POST['points_sign'])?$_POST['points_sign']:0;
                $update_array['points_login'] = intval($_POST['points_login'])?$_POST['points_login']:0;
                $update_array['points_comments'] = intval($_POST['points_comments'])?$_POST['points_comments']:0;
                $update_array['points_orderrate'] = intval($_POST['points_orderrate'])?$_POST['points_orderrate']:0;
                $update_array['points_ordermax'] = intval($_POST['points_ordermax'])?$_POST['points_ordermax']:0;
				$update_array['points_invite'] = intval($_POST['points_invite'])?$_POST['points_invite']:0;
				$update_array['points_rebate'] = intval($_POST['points_rebate'])?$_POST['points_rebate']:0;
				$update_array['GZ_RETURN'] = intval($_POST['GZ_RETURN_allow'])?$_POST['GZ_RETURN_allow']:0;
				$update_array['GZ_RETURN_discount'] = intval($_POST['GZ_RETURN_discount'])?$_POST['GZ_RETURN_discount']:0;
				$result = $model_setting->updateSetting($update_array);
				if ($result === true){
					$this->log(L('nc_edit,nc_operation,nc_operation_set'),1);
					showMessage(L('nc_common_save_succ'));
				}else {
					showMessage(L('nc_common_save_fail'));
				}
			}
		}
		$list_setting = $model_setting->getListSetting();
		Tpl::output('list_setting',$list_setting);
		Tpl::output('top_link',$this->sublink($this->links,'setting'));
		Tpl::showpage('operation.setting');
	}
}
