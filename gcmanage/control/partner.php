<?php
/**
 * 合作平台管理
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');
class partnerControl extends SystemControl{
	const EXPORT_SIZE = 5000;
	public function __construct(){
		parent::__construct();
		Language::read('salescredit');
		//判断系统是否开启销售提成

	}

	
	/**
	 * 合作平台列表
	 */
	public function indexOp(){
		$condition_arr = array();
		if($_GET['partner_name']){
		$condition_arr['partner_name'] = trim($_GET['partner_name']);
		}
		//$condition_arr['saddtime'] = strtotime($_GET['stime']);
		//$condition_arr['eaddtime'] = strtotime($_GET['etime']);
        //if($condition_arr['eaddtime'] > 0) {
        //    $condition_arr['eaddtime'] += 86400;
        //}
		
		//查询
		$partner_model = Model('partner');
		$list_partner = $partner_model->getPartnerList($condition_arr,'*',10,'');

		//信息输出
		Tpl::output('show_page',$partner_model->showpage());
		Tpl::output('list_partner',$list_partner);
		Tpl::showpage('partner_index');
		
	}
	
	/**
	 * 合作平台添加
	 */
	public function addpartnerOp(){
		if (chksubmit()){

			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_POST["partner_name"], "require"=>"true", "message"=>"合作平台名称必填"),
				array("input"=>$_POST["appkey"], "require"=>"true","message"=>"appkey必填")
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error,'','','error');
			}

			$obj_partner = Model('partner');
			$insert_arr['partner_name'] = 	$_POST['partner_name'];
			$insert_arr['type'] 		= 	$_POST['type'];
			$insert_arr['appkey'] 		= 	$_POST['appkey'];
			$insert_arr['notify_url'] 	= 	trim($_POST['notify_url']);
			$insert_arr['notify_url_1'] = 	trim($_POST['notify_url_1']);
			$insert_arr['member_id'] 	= 	trim($_POST['member_id']);
			$insert_arr['add_time'] 	= 	TIMESTAMP;
			
			$admininfo = $this->getAdminInfo();

			$result = $obj_partner->addPartner($insert_arr);
			if ($result){
				//取新增partner_id值更新为appid值
				$condition_appid = array();
				$condition_appid['appkey'] = $_POST['appkey'];
				$update_appid = $obj_partner->getPartnerInfo($condition_appid);
				$update_arr = array();
				$update_arr['appid']=$update_appid['partner_id'];
				$obj_partner->editPartner($condition_appid,$update_arr);
				//取parter_id值更新member表的saleplat_id
				$update_saleplatid = array();
				$update_saleplatid['saleplat_id'] = $update_appid['partner_id'];
				$update_saleplatid['is_manager'] = 1;
				$update_saleplatid['is_rebate'] = 0;
				$update_saleplatid['is_member_rebate'] = 1;
				$update_saleplatid['inviter_id'] = 0;
				$update_saleplatid['refer_id'] = 0;//将上级用户置空
				$model_member = Model('member');
				$member_saleplat = $model_member->editMember(array('member_id'=>intval($_POST['member_id'])),$update_saleplatid);
				if($obj_partner && $member_saleplat ){
					showMessage(Language::get('nc_common_save_succ'),'index.php?gct=partner&gp=index');
				}
			}else {
				showMessage(Language::get('nc_common_save_fail'),'index.php?gct=partner&gp=addpartner','','error');
			}
		}
		
		else{
			Tpl::showpage('partner.add');
		}
	}
	
	

}
