<?php
/**
 * 我的销售业绩	BY MING
 ***/


defined('GcWebShop') or exit('Access Invalid!');
class member_salescreditControl extends BaseMemberControl {
	public function indexOp(){
		$this->salescredit_logOp();
		exit;
	}
	public function __construct() {
		parent::__construct();
		/**
		 * 读取语言包
		 */
		Language::read('member_home_member');
		/**
		 * 判断系统是否开启积分功能
		 */
		if (C('salescredit_isuse') != 1){
			showMessage('专属二维码功能已经关闭，请联系网站管理员。'),urlShop('member', 'home'),'html','error');
		}
	}
	/**
	 * 积分日志列表
	 */
	public function salescredit_logOp(){
		$condition_arr = array();
		$condition_arr['sc_memberid'] = $output['member_info']['member_id'];
		if ($_GET['stage']){
			$condition_arr['sc_stage'] = $_GET['stage'];
		}
		$condition_arr['saddtime'] = strtotime($_GET['stime']);
		$condition_arr['eaddtime'] = strtotime($_GET['etime']);
        if($condition_arr['eaddtime'] > 0) {
            $condition_arr['eaddtime'] += 86400;
        }
		$condition_arr['sc_desc_like'] = $_GET['description'];
		//分页
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		//查询积分日志列表
		$salescredit_model = Model('salescredit');
		$list_log = $salescredit_model->getSalescreditLogList($condition_arr,$page,'*','');
		//信息输出
		self::profile_menu('member','excqcode');
		Tpl::output('menu_sign','profile');
		Tpl::output('menu_sign_url','index.php?gct=member_information&gp=member');
		Tpl::output('menu_sign1','excqcode');
		Tpl::output('show_page',$page->show());
		Tpl::output('list_log',$list_log);
		Tpl::showpage('member_profile.excqcode');
		}
}
