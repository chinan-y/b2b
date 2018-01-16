<?php
/**
 * 销售业绩管理
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');
class salescreditControl extends SystemControl{
	const EXPORT_SIZE = 5000;
	public function __construct(){
		parent::__construct();
		Language::read('points');
		//判断系统是否开启积分功能
		if (C('salescredit_isuse') != 1){
			showMessage(Language::get('admin_salescredit_unavailable'),'index.php?gct=dashboard&gp=welcome','','error');
		}
	}

	/**
	 * 业绩添加
	 */
	public function addsalescreditOp(){
		if (chksubmit()){

			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_POST["member_id"], "require"=>"true", "message"=>Language::get('admin_salescredit_member_error_again')),
				array("input"=>$_POST["pointsnum"], "require"=>"true",'validator'=>'Compare','operator'=>' >= ','to'=>1,"message"=>Language::get('admin_salescredit_points_min_error'))
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error,'','','error');
			}
			//查询会员信息
			$obj_member = Model('member');
			$member_id = intval($_POST['member_id']);
			$member_info = $obj_member->getMemberInfo(array('member_id'=>$member_id));

			if (!is_array($member_info) || count($member_info)<=0){
				showMessage(Language::get('admin_salescredit_userrecord_error'),'index.php?gct=salescredit&gp=addsalescredit','','error');
			}

			$pointsnum = intval($_POST['salescreditnum']);
			if ($_POST['operatetype'] == 2 && $salescreditnum > intval($member_info['member_points'])){
				showMessage(Language::get('admin_salescredit_points_short_error').$member_info['member_points'],'index.php?gct=salescredit&gp=addsalescredit','','error');
			}

			$obj_points = Model('salescredit');
			$insert_arr['sc_memberid'] = $member_info['member_id'];
			$insert_arr['sc_membername'] = $member_info['member_name'];
			$admininfo = $this->getAdminInfo();
			$insert_arr['sc_adminid'] = $admininfo['id'];
			$insert_arr['sc_adminname'] = $admininfo['name'];
			if ($_POST['operatetype'] == 2){
				$insert_arr['sc_points'] = -$_POST['pointsnum'];
			}else {
				$insert_arr['sc_points'] = $_POST['pointsnum'];
			}
			if ($_POST['salescreditdesc']){
				$insert_arr['sc_desc'] = trim($_POST['salescreditdesc']);
			} else {
				$insert_arr['sc_desc'] = Language::get('admin_salescredit_system_desc');
			}
			$result = $obj_salescredit->saveSalescreditLog('system',$insert_arr,true);
			if ($result){
				$this->log(L('admin_salescredit_mod_tip').$member_info['member_name'].'['.(($_POST['operatetype'] == 2)?'':'+').strval($insert_arr['sc_points']).']',null);
				showMessage(Language::get('nc_common_save_succ'),'index.php?gct=salescredit&gp=addsalescredit');
			}else {
				showMessage(Language::get('nc_common_save_fail'),'index.php?gct=salescredit&gp=addsalescredit','','error');
			}
		}else {
			Tpl::showpage('salescredit.add');
		}
	}
	public function checkmemberOp(){
		$name = trim($_GET['name']);
		if (!$name){
			echo ''; die;
		}
		/**
		 * 转码
		 */
		if(strtoupper(CHARSET) == 'GBK'){
			$name = Language::getGBK($name);
		}
		$obj_member = Model('member');
		$member_info = $obj_member->getMemberInfo(array('member_name'=>$name));
		if (is_array($member_info) && count($member_info)>0){
			if(strtoupper(CHARSET) == 'GBK'){
				$member_info['member_name'] = Language::getUTF8($member_info['member_name']);
			}
			echo json_encode(array('id'=>$member_info['member_id'],'name'=>$member_info['member_name'],'salescredit'=>$member_info['member_salescredit']));
		}else {
			echo ''; die;
		}
	}
	/**
	 * 业绩记录列表
	 */
	public function salescreditlogOp(){
		$condition_arr = array();
		$condition_arr['sc_membername_like'] = trim($_GET['mname']);
		$condition_arr['sc_adminname_like'] = trim($_GET['aname']);
		if ($_GET['stage']){
			$condition_arr['sc_stage'] = trim($_GET['stage']);
		}
		$condition_arr['saddtime'] = strtotime($_GET['stime']);
		$condition_arr['eaddtime'] = strtotime($_GET['etime']);
        if($condition_arr['eaddtime'] > 0) {
            $condition_arr['eaddtime'] += 86400;
        }
		$condition_arr['sc_desc_like'] = trim($_GET['description']);
		//分页
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		//查询业绩记录列表
		$salescredit_model = Model('salescredit');
		$list_log = $salescredit_model->getSalescreditLogList($condition_arr,$page,'*','');
		//信息输出
		Tpl::output('show_page',$page->show());
		Tpl::output('list_log',$list_log);
		Tpl::showpage('salescredit');
	}

	/**
	 * 业绩列表导出
	 */
	public function export_step1Op(){
		$condition_arr = array();
		$condition_arr['sc_membername_like'] = trim($_GET['mname']);
		$condition_arr['sc_adminname_like'] = trim($_GET['aname']);
		if ($_GET['stage']){
			$condition_arr['sc_stage'] = trim($_GET['stage']);
		}
		$condition_arr['saddtime'] = strtotime($_GET['stime']);
		$condition_arr['eaddtime'] = strtotime($_GET['etime']);
        if($condition_arr['eaddtime'] > 0) {
            $condition_arr['eaddtime'] += 86400;
        }
		$condition_arr['sc_desc_like'] = trim($_GET['description']);
		$page	= new Page();
		$page->setEachNum(self::EXPORT_SIZE);
		$points_model = Model('salescredit');
		$list_log = $salescredit_model->getSalescreditLogList($condition_arr,$page,'*','');
		if (!is_numeric($_GET['curpage'])){
			$count = $page->getTotalNum();
			$array = array();
			if ($count > self::EXPORT_SIZE ){	//显示下载链接
				$page = ceil($count/self::EXPORT_SIZE);
				for ($i=1;$i<=$page;$i++){
					$limit1 = ($i-1)*self::EXPORT_SIZE + 1;
					$limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
					$array[$i] = $limit1.' ~ '.$limit2 ;
				}
				Tpl::output('list',$array);
				Tpl::output('murl','index.php?gct=salescredit&gp=salescreditlog');
				Tpl::showpage('export.excel');
			}else{	//如果数量小，直接下载
				$this->createExcel($list_log);
			}
		}else{	//下载
			$this->createExcel($list_log);
		}
	}

	/**
	 * 生成excel
	 *
	 * @param array $data
	 */
	private function createExcel($data = array()){
		Language::read('export');
		import('libraries.excel');
		$excel_obj = new Excel();
		$excel_data = array();
		//设置样式
		$excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
		//header
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_pi_member'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_pi_system'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_pi_point'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_pi_time'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_pi_jd'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_pi_ms'));
		$state_cn = array(Language::get('admin_salescredit_stage_regist'),Language::get('admin_salescredit_stage_login'),Language::get('admin_salescredit_stage_comments'),Language::get('admin_salescredit_stage_order'),Language::get('admin_salescredit_stage_system'),Language::get('admin_salescredit_stage_pointorder'),Language::get('admin_salescredit_stage_app'));
		foreach ((array)$data as $k=>$v){
			$tmp = array();
			$tmp[] = array('data'=>$v['sc_membername']);
			$tmp[] = array('data'=>$v['sc_adminname']);
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['sc_points']));
			$tmp[] = array('data'=>date('Y-m-d H:i:s',$v['sc_addtime']));
			$tmp[] = array('data'=>str_replace(array('regist','login','comments','order','system','pointorder','app'),$state_cn,$v['sc_stage']));
			$tmp[] = array('data'=>$v['sc_desc']);

			$excel_data[] = $tmp;
		}
		$excel_data = $excel_obj->charset($excel_data,CHARSET);
		$excel_obj->addArray($excel_data);
		$excel_obj->addWorksheet($excel_obj->charset(L('exp_pi_jfmx'),CHARSET));
		$excel_obj->generateXML($excel_obj->charset(L('exp_pi_jfmx'),CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
	}
}
