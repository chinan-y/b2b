<?php
/**
 * 账号同步
 *
 *
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');
class accountControl extends SystemControl{
	private $links = array(
		array('url'=>'gct=account&gp=qq','lang'=>'qqSettings'),
		array('url'=>'gct=account&gp=sina','lang'=>'sinaSettings')
	);
	public function __construct(){
		parent::__construct();
		Language::read('setting');
	}

	/**
	 * QQ互联
	 */
	public function qqOp(){
		$model_setting = Model('setting');
		if (chksubmit()){
			$obj_validate = new Validate();
			if (trim($_POST['qq_isuse']) == '1'){
				$obj_validate->validateparam = array(
					array("input"=>$_POST["qq_appid"], "require"=>"true","message"=>Language::get('qq_appid_error')),
					array("input"=>$_POST["qq_appkey"], "require"=>"true","message"=>Language::get('qq_appkey_error'))
				);
			}
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$update_array = array();
				$update_array['qq_isuse'] 	= $_POST['qq_isuse'];
				$update_array['qq_appcode'] = $_POST['qq_appcode'];
				$update_array['qq_appid'] 	= $_POST['qq_appid'];
				$update_array['qq_appkey'] 	= $_POST['qq_appkey'];
				$result = $model_setting->updateSetting($update_array);
				if ($result === true){
					$this->log(L('nc_edit,qqSettings'),1);
					showMessage(Language::get('nc_common_save_succ'));
				}else {
					$this->log(L('nc_edit,qqSettings'),0);
					showMessage(Language::get('nc_common_save_fail'));
				}
			}
		}

		$list_setting = $model_setting->getListSetting();
		Tpl::output('list_setting',$list_setting);

		//输出子菜单
		Tpl::output('top_link',$this->sublink($this->links,'qq'));
		Tpl::showpage('setting.qq_setting');
	}

	/**
	 * sina微博设置
	 */
	public function sinaOp(){
		$model_setting = Model('setting');
		if (chksubmit()){
			$obj_validate = new Validate();
			if (trim($_POST['sina_isuse']) == '1'){
				$obj_validate->validateparam = array(
					array("input"=>$_POST["sina_wb_akey"], "require"=>"true","message"=>Language::get('sina_wb_akey_error')),
					array("input"=>$_POST["sina_wb_skey"], "require"=>"true","message"=>Language::get('sina_wb_skey_error'))
				);
			}
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$update_array = array();
				$update_array['sina_isuse'] 	= $_POST['sina_isuse'];
				$update_array['sina_wb_akey'] 	= $_POST['sina_wb_akey'];
				$update_array['sina_wb_skey'] 	= $_POST['sina_wb_skey'];
				$update_array['sina_appcode'] 	= $_POST['sina_appcode'];
				$result = $model_setting->updateSetting($update_array);
				if ($result === true){
					$this->log(L('nc_edit,sinaSettings'),1);
					showMessage(Language::get('nc_common_save_succ'));
				}else {
					$this->log(L('nc_edit,sinaSettings'),0);
					showMessage(Language::get('nc_common_save_fail'));
				}
			}
		}
		$is_exist = function_exists('curl_init');
		if ($is_exist){
			$list_setting = $model_setting->getListSetting();
			Tpl::output('list_setting',$list_setting);
		}
		Tpl::output('is_exist',$is_exist);

		//输出子菜单
		Tpl::output('top_link',$this->sublink($this->links,'sina'));

		Tpl::showpage('setting.sina_setting');
	}
	 /**
     * 微信登陆设置
     */
    public function wechatOp(){
        /**
         * 读取语言包
         */
        $lang  = Language::getLangContent();
        /**
         * 实例化模型
         */
        $model_setting = Model('setting');
        /**
         * 保存信息
         */
        if (chksubmit()){
            /**
             * 验证
             */
            $obj_validate = new Validate();
            if (trim($_POST['wechat_isuse']) == '1'){
                $obj_validate->validateparam = array(
                    array("input"=>$_POST["wechat_appid"], "require"=>"true","message"=>'AppID错误'),
                    array("input"=>$_POST["wechat_appkey"], "require"=>"true","message"=>'AppSecret错误')
                );
            }
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                /*
                 * 构造更新数据数组
                 */
                $update_array = array();
                $update_array['wechat_isuse'] = trim($_POST['wechat_isuse']);
                $update_array['wechat_appcode'] = trim($_POST['wechat_appcode']);
                $update_array['wechat_appid'] = trim($_POST['wechat_appid']);
                $update_array['wechat_appkey'] = trim($_POST['wechat_appkey']);
                $result = $model_setting->updateSetting($update_array);
                if ($result === true){
                    showMessage('更新成功');
                }else {
                    showMessage('更新失败');
                }
            }
        }
        /**
         * 读取设置内容 $list_setting
         */
        $list_setting = $model_setting->getListSetting();
        /**
         * 模板输出
         */
        Tpl::output('list_setting',$list_setting);
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'wechat'));
        Tpl::showpage('setting.wechat_setting');
    }

    /**
     * pc微信登陆设置
     */
    public function wechatpcOp(){
        /**
         * 读取语言包
         */
        $lang   = Language::getLangContent();
        /**
         * 实例化模型
         */
        $model_setting = Model('setting');
        /**
         * 保存信息
         */
        if (chksubmit()){
            /**
             * 验证
             */
            $obj_validate = new Validate();
            if (trim($_POST['wechatpc_isuse']) == '1'){
                $obj_validate->validateparam = array(
                    array("input"=>$_POST["wechatpc_appid"], "require"=>"true","message"=>'AppID错误'),
                    array("input"=>$_POST["wechatpc_appkey"], "require"=>"true","message"=>'AppSecret错误')
                );
            }
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                /*
                 * 构造更新数据数组
                 */
                $update_array = array();
                $update_array['wechatpc_isuse'] = trim($_POST['wechatpc_isuse']);
                $update_array['wechatpc_appcode'] = trim($_POST['wechatpc_appcode']);
                $update_array['wechatpc_appid'] = trim($_POST['wechatpc_appid']);
                $update_array['wechatpc_appkey'] = trim($_POST['wechatpc_appkey']);
                $result = $model_setting->updateSetting($update_array);
                if ($result === true){
                    showMessage('更新成功');
                }else {
                    showMessage('更新失败');
                }
            }
        }
        /**
         * 读取设置内容 $list_setting
         */
        $list_setting = $model_setting->getListSetting();
        /**
         * 模板输出
         */
        Tpl::output('list_setting',$list_setting);
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'wechatpc'));
        Tpl::showpage('setting.wechatpc_setting');
    }
}
