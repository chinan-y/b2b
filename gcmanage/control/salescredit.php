<?php
/**
 * 积分管理
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');
class salescreditControl extends SystemControl{
	const EXPORT_SIZE = 1000;
	public function __construct(){
		parent::__construct();
		Language::read('trade');
		Language::read('salescredit');
		//判断系统是否开启销售提成
		if (C('salescredit_isuse') != 1){
			showMessage(Language::get('admin_salescredit_unavailable'),'index.php?gct=dashboard&gp=welcome','','error');
		}
	}
	
	//销售员订单
	public function indexOp(){
	    $model_order = Model('order');
        $condition	= array();
        if($_GET['order_sn']) {
        	$condition['order_sn'] = $_GET['order_sn'];
        }
        if($_GET['store_name']) {
            $condition['store_name'] = $_GET['store_name'];
        }
        if(in_array($_GET['order_state'],array('0','10','20','30','40'))){
        	$condition['order_state'] = $_GET['order_state'];
        }
        if($_GET['payment_code']) {
            $condition['payment_code'] = $_GET['payment_code'];
        }
        if($_GET['buyer_name']) {
            $condition['buyer_name'] = $_GET['buyer_name'];
        }
		if($_GET['buyer_id']) {
            $condition['buyer_id'] = $_GET['buyer_id'];
        }
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_time']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_time']);
        $start_unixtime = $if_start_time ? strtotime($_GET['query_start_time']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['query_end_time']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        $order_list	= $model_order->getOrderList($condition,30);

        foreach ($order_list as $order_id => $order_info) {
			$orders = DB::getAll("select  MAKE_CSV, OIF_MEMO from 33hao_mess_order_info where ORDER_ID = '".$order_info['order_id']."'" );
			if($orders != null){
				$order_list[$order_id]['lib_state'] = $orders[0];
			}else{
				$order_list[$order_id]['lib_state'] = '';
			}
            //显示取消订单
            $order_list[$order_id]['if_cancel'] = $model_order->getOrderOperateState('system_cancel',$order_info);
            //显示收到货款
            $order_list[$order_id]['if_system_receive_pay'] = $model_order->getOrderOperateState('system_receive_pay',$order_info);
			$condition_uper =array();
			$condition_uper['member_id'] =	$order_info['buyer_id'];
			$uper_id = Model('member')->getMemberSuperior($condition_uper);
			$order_list[$order_id]['one_id'] = $uper_id['one_id'];
			$order_list[$order_id]['two_id'] = $uper_id['two_id'];
			$order_list[$order_id]['three_id'] = $uper_id['three_id'];
			$onename = Model('member')->getMemberInfoByID($uper_id['one_id'],'member_name');
			$twoname = Model('member')->getMemberInfoByID($uper_id['two_id'],'member_name');
			$threename = Model('member')->getMemberInfoByID($uper_id['three_id'],'member_name');
			$order_list[$order_id]['one_name'] = $onename['member_name'];
			$order_list[$order_id]['two_name'] = $twoname['member_name'];
			$order_list[$order_id]['three_name'] = $threename['member_name'];
        }
		
        //显示支付接口列表(搜索)
        $payment_list = Model('payment')->getPaymentOpenList();
        Tpl::output('payment_list',$payment_list);

        Tpl::output('order_list',$order_list);
        Tpl::output('show_page',$model_order->showpage());
        Tpl::showpage('saleman_order.index');
	}

	/**
	 * 销售业绩添加
	 */
	public function addsalescreditOp(){
		if (chksubmit()){

			$obj_validate = new Validate(); 
			$obj_validate->validateparam = array(
				array("input"=>$_POST["member_id"], "require"=>"true", "message"=>Language::get('admin_salescredit_member_error_again')),
				array("input"=>$_POST["salescreditnum"], "require"=>"true",'validator'=>'Compare','operator'=>' >= ','to'=>0.01,"message"=>Language::get('admin_salescredit_points_min_error')),
				array("input"=>$_POST["order_sn"], "require"=>"true", "message"=>'订单编号不能为空')
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

			$salescreditnum = intval($_POST['salescreditnum']);
			if ($_POST['operatetype'] == 2 && $salescreditnum > intval($member_info['member_salescredit'])){
				showMessage(Language::get('admin_salescredit_points_short_error').$member_info['member_salescredit'],'index.php?gct=salescredit&gp=addsalescredit','','error');
			}
			$available_predeposit=floatval($member_info['available_predeposit']);
			if ($_POST['operatetype'] == 2 && $salescreditnum > $available_predeposit){
				showMessage(('预存款不足，会员当前预存款').$available_predeposit,'index.php?gct=salescredit&gp=addsalescredit','','error');
			}

			$obj_salescredit = Model('salescredit');
			$insert_arr['sc_memberid'] = $member_info['member_id'];
			$insert_arr['sc_membername'] = $member_info['member_name'];
			$admininfo = $this->getAdminInfo();
			$insert_arr['sc_adminid'] = $admininfo['id'];
			$insert_arr['sc_adminname'] = $admininfo['name'];
			if ($_POST['operatetype'] == 2){
				$insert_arr['sc_points'] = -$_POST['salescreditnum'];
			}else {
				$insert_arr['sc_points'] = $_POST['salescreditnum'];
			}
			$insert_arr['order_sn'] = trim($_POST['order_sn']);
			if ($_POST['salescreditdesc']){
				$insert_arr['sc_desc'] = trim($_POST['salescreditdesc']);
			} else {
				$insert_arr['sc_desc'] = Language::get('admin_salescredit_system_desc');
			}
			$result = $obj_salescredit->saveSalescreditLog('system',$insert_arr,true);
			if ($result){
				$this->log(L('admin_salescredit_mod_tip').$member_info['member_name'].'['.(($_POST['operatetype'] == 2)?'':'+').strval($insert_arr['sc_points']).']',null);
				/**
				* 收益同时添加至个人余额，并记录个人余额日志
				*/		
				$admininfo = $this->getAdminInfo();
				$money 		= abs(floatval($_POST['salescreditnum']));
				if ($money <= 0) {showMessage('输入的金额必需大于0','','html','error');}
				
				$tordersn	= trim($_POST['order_sn']);
				$salesdesc		= trim($_POST['salescreditdesc']);
				
				$data = array();
				$data['lg_member_id'] = $member_info['member_id'];
				$data['lg_member_name'] = $member_info['member_name'];
				$data['lg_admin_name'] = $admininfo['name'];
				$data['lg_add_time'] = TIMESTAMP;
				if($_POST['operatetype']==1){
					$data['lg_av_amount'] = $money;
					$data['lg_type'] = 'sman_add_money';
					$data['lg_desc'] = "管理员【".$admininfo['name']."】操作会员【".$member_info['member_name']."】预存款【增加】金额为".$money.",对应订单号为".$tordersn.",收益变更描述为：".$salesdesc;
				}
				elseif($_POST['operatetype']==2){
					$data['lg_av_amount'] = -$money;
					$data['lg_type'] = 'sman_del_money';
					$data['lg_desc'] = "管理员【".$admininfo['name']."】操作会员【".$member_info['member_name']."】预存款【减少】金额为".$money.",对应订单号为".$tordersn.",收益变更描述为：".$salesdesc;
				}else{
						showMessage('操作失败','index.php?gct=salescredit&gp=addsalescredit');
				}
				
				$model_pdlog = Model('pd_log');
				$insert_pd	=$model_pdlog ->insert($data);
				if(!$insert_pd){showMessage('变更余额日志表操作失败','index.php?gct=salescredit&gp=addsalescredit');}
				
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
		$is_seller = intval($_GET['is_seller']);
		if ($is_seller == 1){
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
	 * 销售业绩列表
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
		$condition_arr['sc_order_sn'] = trim($_GET['order_sn']);
		//分页
		$page	= new Page();
		$page->setEachNum(15);
		$page->setStyle('admin');
		//查询
		$salescredit_model = Model('salescredit');
		$list_log = $salescredit_model->getsalescreditlogList($condition_arr,$page,'*','');
		if(empty($list_log)){showMessage('无查询结果','index.php?gct=salescredit&gp=salescreditlog');}
		foreach($list_log as $k=>$val){
			$re = Model('member')->getMemberInfo(array('member_id'=>$val['sc_memberid']),'sa_id');
			$sa_name = Model('sales_area')->getSalesAreaInfoByID($re['sa_id'] ,'sa_name');
			$list_log[$k]['sa_name'] = $sa_name['sa_name'];
		}
		//var_dump($list_log);
		//信息输出
		Tpl::output('show_page',$page->show());
		Tpl::output('list_log',$list_log);
		Tpl::showpage('salescreditlog');
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
		$salescredit_model = Model('salescredit');
		$list_log = $salescredit_model->getsalescreditlogList($condition_arr,$page,'*','');
		if(!empty($list_log)){
			foreach($list_log as $k=>$val){
				$re = Model('member')->getMemberInfo(array('member_id'=>$val['sc_memberid']),'sa_id');
				$sa_name = Model('sales_area')->getSalesAreaInfoByID($re['sa_id'] ,'sa_name');
				$list_log[$k]['sa_name'] = $sa_name['sa_name'];
			}
		}
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
				Tpl::output('murl','index.php?gct=salescreditlog&gp=salescreditlog');
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
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'团队名称');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_sc_member'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'订单编号');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_sc_point'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_sc_ms'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_sc_time'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_sc_system'));
		
		foreach ((array)$data as $k=>$v){
			$tmp = array();
			$tmp[] = array('data'=>$v['sa_name']);
			$tmp[] = array('data'=>$v['sc_membername']);
			$tmp[] = array('data'=>$v['sc_order_sn']);
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['sc_points']));
			$tmp[] = array('data'=>$v['sc_desc']);
			$tmp[] = array('data'=>date('Y-m-d H:i:s',$v['sc_addtime']));
			$tmp[] = array('data'=>$v['sc_adminname']);

			$excel_data[] = $tmp;
		}
		$excel_data = $excel_obj->charset($excel_data,CHARSET);
		$excel_obj->addArray($excel_data);
		$excel_obj->addWorksheet($excel_obj->charset(L('exp_sc_jfmx'),CHARSET));
		$excel_obj->generateXML($excel_obj->charset(L('exp_sc_jfmx'),CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
	}
}
