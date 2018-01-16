<?php
/**
 * 快递公司
 *
 *
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');
class expressControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('express');
		Language::read('admin_log');
	}

	public function indexOp(){
		$lang	= Language::getLangContent();
		$model = Model('express');
		if (preg_match('/^[A-Z]$/',$_GET['letter'])){
			$model->where(array('e_letter'=>$_GET['letter']));
		}
		$list = $model->page(10)->order('e_order,e_state desc,id')->select();
		Tpl::output('page',$model->showpage());
		Tpl::output('list',$list);
		Tpl::showpage('express.index');
	}

	/**
	 * 设置kuaidi100帐号和爱查快递帐号
	 */
	public function acount_settingOp(){
		if (chksubmit()){
			$lang	= Language::getLangContent();
			$model_setting = Model('setting');
			$result = $model_setting->updateSetting(array(
					'acount_kd100_id'=>trim($_POST['acount_kd100_id']),
					'acount_kd100_key'=>trim($_POST['acount_kd100_key']),
					'acount_ickd_id'=>trim($_POST['acount_ickd_id']),
					'acount_ickd_key'=>trim($_POST['acount_ickd_key']),
			));
			if ($result){
				showMessage($lang['nc_common_save_succ']);
			}else {
				showMessage($lang['nc_common_save_fail']);
			}
		}
		$model_setting = Model('setting');
		$list_setting = $model_setting->getListSetting();
		Tpl::output('list_setting',$list_setting);

		Tpl::showpage('express.setting');
	}

	/**
	 * ajax操作
	 */
	public function ajaxOp(){
		switch ($_GET['branch']){
			case 'state':
				$model_brand = Model('express');
				$update_array = array();
				$update_array['id'] = intval($_GET['id']);
				$update_array[$_GET['column']] = trim($_GET['value']);
				$model_brand->update($update_array);
				dkcache('express');
				$this->log(L('nc_edit,express_name,express_state').'[ID:'.intval($_GET['id']).']',1);
				echo 'true';exit;
				break;
			case 'order':
				$_GET['value'] = $_GET['value'] == 0? 2:1;
				$model_brand = Model('express');
				$update_array = array();
				$update_array['id'] = intval($_GET['id']);
				$update_array[$_GET['column']] = trim($_GET['value']);
				$model_brand->update($update_array);
				dkcache('express');
				$this->log(L('nc_edit,express_name,express_state').'[ID:'.intval($_GET['id']).']',1);
				echo 'true';exit;
				break;
    		case 'e_zt_state':
    		    $model_brand = Model('express');
    		    $update_array = array();
    		    $update_array['id'] = intval($_GET['id']);
    		    $update_array[$_GET['column']] = trim($_GET['value']);
    		    $model_brand->update($update_array);
				dkcache('express');
    		    $this->log(L('nc_edit,express_name,express_state').'[ID:'.intval($_GET['id']).']',1);
    		    echo 'true';exit;
    		    break;
		}
		dkcache('express');
	}

}
