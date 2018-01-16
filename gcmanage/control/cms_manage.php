<?php
/**
 * cms管理
 *
 *
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');
class cms_manageControl extends SystemControl{

	public function __construct(){
		parent::__construct();
		Language::read('cms');
	}

	public function indexOp() {
        $this->cms_settingOp();
	}

	/**
	 * cms设置
	 */
	public function cms_manageOp() {
        $model_setting = Model('setting');
        $setting_list = $model_setting->getListSetting();
        Tpl::output('setting',$setting_list);
        $this->show_menu('cms_manage');
        Tpl::showpage('cms_manage');
	}

	/**
	 * cms设置保存
	 */
	public function cms_manage_saveOp() {
        $model_setting = Model('setting');
        $update_array = array();
        $update_array['cms_isuse'] = intval($_POST['cms_isuse']);
        if(!empty($_FILES['cms_logo']['name'])) {
			if(C(OSS_IS_STORAGE) == 0){
				$upload	= new UploadFile();
				$upload->set('default_dir',ATTACH_CMS);
				$result = $upload->upfile('cms_logo');
				if(!$result) {
					showMessage($upload->error);
				}
				$update_array['cms_logo'] = $upload->file_name;
			}

			if(C(OSS_IS_STORAGE) == 1){
				//AliyunOSS上传图片
				$oss = Logic('oss');
				$bucketname = IMAGE_BUCKET_NAME;
				$extend 	= pathinfo($_FILES['cms_logo']['name']); 
				$extend 	= strtolower($extend["extension"]); 
				$imgname	= 'cl_'.date('His').rand(0,99);
				$imgfullname= $imgname . '.' . $extend;
				$objectname = DIR_UPLOAD.DS.ATTACH_CMS.DS.$imgfullname;
				$pathname 	= $_FILES['cms_logo']['tmp_name'];
				$result = $oss->uploadFile($bucketname, $objectname, $pathname, $options);
				if ($result){
					$update_array['cms_logo'] = $imgfullname;
				}else{
					showMessage('CMSlogo upload error!', '', '', 'error');
				}
			}

            $old_image = BASE_UPLOAD_PATH.DS.ATTACH_CMS.DS.C('microshop_logo');
            if(is_file($old_image)) {
                unlink($old_image);
            }
        }
        $update_array['cms_submit_verify_flag'] = intval($_POST['cms_submit_verify_flag']);
        $update_array['cms_comment_flag'] = intval($_POST['cms_comment_flag']);
        $update_array['cms_attitude_flag'] = intval($_POST['cms_attitude_flag']);
        $update_array['taobao_api_isuse'] = intval($_POST['taobao_api_isuse']);
        $update_array['taobao_app_key'] = $_POST['taobao_app_key'];
        $update_array['taobao_secret_key'] = $_POST['taobao_secret_key'];
        $update_array['cms_seo_title'] = $_POST['cms_seo_title'];
        $update_array['cms_seo_keywords'] = $_POST['cms_seo_keywords'];
        $update_array['cms_seo_description'] = $_POST['cms_seo_description'];

        $result = $model_setting->updateSetting($update_array);
        if ($result === true){
            $this->log(Language::get('cms_log_manage_save'), 0);
            showMessage(Language::get('nc_common_save_succ'));
        }else {
            $this->log(Language::get('cms_log_manage_save'), 0);
            showMessage(Language::get('nc_common_save_fail'));
        }
	}

    private function show_menu($menu_key) {
        $menu_array = array(
            'cms_manage'=>array('menu_type'=>'link','menu_name'=>Language::get('nc_manage'),'menu_url'=>'index.php?gct=cms_manage&gp=cms_manage'),
        );
        $menu_array[$menu_key]['menu_type'] = 'text';
        Tpl::output('menu',$menu_array);
    }


}
