<?php
/**
 * 自定义二维码管理
 *
 *
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');

class custom_qrcodeControl extends SystemControl{
	const EXPORT_SIZE = 1000;
	public function __construct(){
		parent::__construct();
		Language::read('member');
	}

	/**
	 * 自定义二维码生成器管理
	 */
	public function qrcodeOp(){
		$lang	= Language::getLangContent();
		$model_member = Model('member');
		/**
		 * 删除
		 */
		if (chksubmit()){
			/**
			 * 判断是否是管理员，如果是，则不能删除
			 */
			/**
			 * 删除
			 */
			if (!empty($_POST['del_id'])){
				if (is_array($_POST['del_id'])){
					foreach ($_POST['del_id'] as $k => $v){
						$v = intval($v);
						$rs = true;//$model_member->del($v);
						if ($rs){
							//删除该会员商品,店铺
							//获得该会员店铺信息
							$member = $model_member->getMemberInfo(array(
								'member_id'=>$v
							));
							//删除用户
							$model_member->del($v);
						}
					}
				}
				showMessage($lang['nc_common_del_succ']);
			}else {
				showMessage($lang['nc_common_del_fail']);
			}
		}
		//会员级别
		$member_grade = $model_member->getMemberGradeArr();
		$condition = array();

		if ($_GET['search_field_value'] != '') {
    		switch ($_GET['search_field_name']){
    			case 'member_name':
    				$condition['member_name'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
				case 'member_id':
				    $condition['member_id'] = trim($_GET['search_field_value']);
				    break;
    			case 'member_email':
    				$condition['member_email'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
				case 'member_mobile':
    				$condition['member_mobile'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    		}
		}
		
				
		Tpl::output('search_sort',trim($_GET['search_sort']));
		Tpl::output('search_field_name',trim($_GET['search_field_name']));
		Tpl::output('search_field_value',trim($_GET['search_field_value']));
		Tpl::output('search_field_width',trim($_GET['search_field_width']));
		Tpl::output('search_field_bgcolor',trim($_GET['search_field_bgcolor']));
		Tpl::output('search_field_fgcolor',trim($_GET['search_field_fgcolor']));
		Tpl::output('search_field_px',trim($_GET['search_field_px']));
		Tpl::output('search_field_logo',trim($_GET['search_field_logo']));
		Tpl::output('search_field_api',trim($_GET['search_field_api']));
		Tpl::showpage('custom_qrcode');
	}

}
