<?php
/**
 * 邀请返利页面
 */
 
 
defined('GcWebShop') or exit('Access Invalid!');

		
class member_qcodeControl extends BaseMemberControl {
	
	    public function __construct() {
        parent::__construct();
		Language::read('member_home_member');
		//验证该会员是否能生成二维码
		if($_SESSION['is_seller'] <> 1){
			showMessage(Language::get('ref_make_qcode_ban'),'','html','error');
		}
		Tpl::showpage('apply_seller');
		}
	}
		
	
		public function indexOp(){
		Tpl::showpage('Member_qcode');
		
		//Ming
		//生成个人二维码
		require_once(BASE_RESOURCE_PATH.DS.'phpqrcode'.DS.'index.php');
		$PhpQRCode = new PhpQRCode();
		$PhpQRCode->set('pngTempDir',BASE_UPLOAD_PATH.DS.ATTACH_AVATAR.DS);
		$PhpQRCode->set('date',WAP_SITE_URL . '/index.html?ref='.$member_id);
        $PhpQRCode->set('pngTempName', $member_id . '_qcode.png');
        $PhpQRCode->init();
		//zmr<<<
        $model_class->editStore($param, array('store_id' => $store_id));
        showDialog(Language::get('nc_common_save_succ'),'index.php?gct=store_setting&gp=store_setting','succ');
	}
}
