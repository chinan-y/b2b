<?php
/**
 * 二维码会员管理
 平台销售员管理
 *
 *
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');

class erwei_memberControl extends SystemControl{
	const EXPORT_SIZE = 1000;
	public function __construct(){
		parent::__construct();
		Language::read('member');
	}

	/**
	 * 二维码销售员会员管理
	 */
	public function memberOp(){
		$lang	= Language::getLangContent();
		$model_member = Model('member');
		$model_partner = Model('partner');
		$model_salesarea = Model('sales_area');
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
		$condition['is_seller'] = '1';	

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
    			case 'member_truename':
    				$condition['member_truename'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    		}
		}
		
		switch ($_GET['search_state']){
			case 'no_informallow':
				$condition['inform_allow'] = '2';
				break;
			case 'no_isbuy':
				$condition['is_buy'] = '0';
				break;
			case 'no_isallowtalk':
				$condition['is_allowtalk'] = '0';
				break;
			case 'no_memberstate':
				$condition['member_state'] = '0';
				break;
		}
		//会员等级
		$search_grade = intval($_GET['search_grade']);
		if ($search_grade >= 0 && $member_grade){
		    $condition['member_exppoints'] = array(array('egt',$member_grade[$search_grade]['exppoints']),array('lt',$member_grade[$search_grade+1]['exppoints']),'and');
		}
		//排序
		$order = trim($_GET['search_sort']);
		if (empty($order)) {
		    $order = 'member_id desc';
		}

		$member_list = $model_member->getMemberList($condition, '*', 20, $order);
	
		//整理会员信息
		if (is_array($member_list)){
			foreach ($member_list as $k=> $v){
				$member_list[$k]['member_time'] = $v['member_time']?date('Y-m-d H:i:s',$v['member_time']):'';
				$member_list[$k]['member_login_time'] = $v['member_login_time']?date('Y-m-d H:i:s',$v['member_login_time']):'';
				$member_list[$k]['member_grade'] = ($t = $model_member->getOneMemberGrade($v['member_exppoints'], false, $member_grade))?$t['level_name']:'';
				$member_list[$k]['partner_name'] = $model_partner->getPartnerInfo(array('partner_id'=>$v['saleplat_id']),'partner_name');
			}
		}
		//var_dump($member_list);
		Tpl::output('member_grade',$member_grade);
		Tpl::output('search_sort',trim($_GET['search_sort']));
		Tpl::output('search_field_name',trim($_GET['search_field_name']));
		Tpl::output('search_field_value',trim($_GET['search_field_value']));
		Tpl::output('member_list',$member_list);
		Tpl::output('page',$model_member->showpage());
		Tpl::showpage('erwei_member');
	}

	public function salesman_profitOp(){
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
		$page->setEachNum(15);
		$page->setStyle('admin');
		//查询
		$salescredit_model = Model('salescredit');
		$list_log = $salescredit_model->getsalescreditlogList($condition_arr,$page,'*','');

		//信息输出
		Tpl::output('show_page',$page->show());
		Tpl::output('list_log',$list_log);
		Tpl::showpage('salesman_profit');
	}

	public function yonghuOp(){
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
    			case 'member_truename':
    				$condition['member_truename'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    		}
		}
		switch ($_GET['search_state']){
			case 'no_informallow':
				$condition['inform_allow'] = '2';
				break;
			case 'no_isbuy':
				$condition['is_buy'] = '0';
				break;
			case 'no_isallowtalk':
				$condition['is_allowtalk'] = '0';
				break;
			case 'no_memberstate':
				$condition['member_state'] = '0';
				break;
		}
		//会员等级
		$search_grade = intval($_GET['search_grade']);
		if ($search_grade >= 0 && $member_grade){
		    $condition['member_exppoints'] = array(array('egt',$member_grade[$search_grade]['exppoints']),array('lt',$member_grade[$search_grade+1]['exppoints']),'and');
		}
		//排序
		$order = trim($_GET['search_sort']);
		if (empty($order)) {
		    $order = 'member_id desc';
		}
		
		$condition['is_seller'] = 1;
		
		//查询用户的下一级用户-A级用户
		if($_GET['usertype'] == A){
		$condition['refer_id'] = trim($_GET['member_id']);
		$member_list = $model_member->getMemberList($condition, '*', 10, $order);
		//var_dump($member_list['']['member_id']);
		}
		
		//查询用户的下二级用户-B级用户
		if($_GET['usertype'] == B){
		$condition['refer_id'] = trim($_GET['member_id']);
		$member_list = $model_member->getMemberList($condition, '*', '', $order);
			if(!empty(array_column($member_list,'member_id'))){
			$condition['refer_id'] = array('in',array_column($member_list,'member_id'));
			$member_list = $model_member->getMemberList($condition, '*', 10, $order);
			}
		}
		
		//查询用户的下三级用户-C级用户
		if($_GET['usertype'] == C){
		$condition['refer_id'] = trim($_GET['member_id']);
		$member_list = $model_member->getMemberList($condition, '*', '', $order);
			if(!empty(array_column($member_list,'member_id'))){
			$condition['refer_id'] = array('in',array_column($member_list,'member_id'));
			$member_list = $model_member->getMemberList($condition, '*', '', $order);
				if(!empty(array_column($member_list,'member_id'))){
				$condition['refer_id'] = array('in',array_column($member_list,'member_id'));
				$member_list = $model_member->getMemberList($condition, '*', 10, $order);
				}
			}
		}
		
		//整理会员信息
		if (is_array($member_list)){
			foreach ($member_list as $k=> $v){
				$member_list[$k]['member_time'] = $v['member_time']?date('Y-m-d H:i:s',$v['member_time']):'';
				$member_list[$k]['member_login_time'] = $v['member_login_time']?date('Y-m-d H:i:s',$v['member_login_time']):'';
				$member_list[$k]['member_grade'] = ($t = $model_member->getOneMemberGrade($v['member_exppoints'], false, $member_grade))?$t['level_name']:'';
			}
		}
		$master_member = $model_member->getMemberInfo(array('member_id'=>$_GET['member_id']),'member_name');
		Tpl::output('master_member',$master_member);
		Tpl::output('member_grade',$member_grade);
		Tpl::output('search_sort',trim($_GET['search_sort']));
		Tpl::output('search_field_name',trim($_GET['search_field_name']));
		Tpl::output('search_field_value',trim($_GET['search_field_value']));
		Tpl::output('member_list',$member_list);
		Tpl::output('page',$model_member->showpage());
		Tpl::showpage('erwei_member.index');
	}
	
	/**
	 * 导出
	 */
	public function export_step1Op(){
		$salescredit_model = Model('salescredit');
		$condition_arr = array();
		if($_GET['mname']){
			$condition_arr['sc_membername'] = array('like', '%' . $_GET['mname'] . '%');
		}
		if($_GET['aname']){
			$condition_arr['sc_adminname'] = array('like', '%' . $_GET['aname'] . '%');
		}
		$if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['stime']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['etime']);
        $start_unixtime = $if_start_time ? strtotime($_GET['stime']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['etime']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition_arr['sc_addtime'] = array('time',array($start_unixtime,$end_unixtime));
        }
		if($_GET['description']){
			$condition_arr['sc_desc'] = array('like', '%' . $_GET['description'] . '%');
		}
		//查询
		if (!is_numeric($_GET['curpage'])){
			$count = $salescredit_model->getSalescreditCount($condition_arr);
			$array = array();
			if ($count > self::EXPORT_SIZE ){	
				$page = ceil($count/self::EXPORT_SIZE);
				for ($i=1;$i<=$page;$i++){
					$limit1 = ($i-1)*self::EXPORT_SIZE + 1;
					$limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
					$array[$i] = $limit1.' ~ '.$limit2 ;
				}
				Tpl::output('list',$array);
				Tpl::output('murl','index.php?gct=order&gp=index');
				Tpl::showpage('export.excel');
			}else{
				$data = $salescredit_model->getSalescreditList($condition_arr,'','*','',self::EXPORT_SIZE);
				$this->createExcel($data);
			}
		}else{
			$limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
			$limit2 = self::EXPORT_SIZE;
			$data = $salescredit_model->getSalescreditList($condition_arr,'','*','',"{$limit1},{$limit2}");
			$this->createExcel($data);
		}
	}
	
	/**
	 * 生成excel
	 */
	private function createExcel($data = array()){
		import('libraries.excel');
		$excel_obj = new Excel();
		$excel_data = array();
		//设置样式
		$excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
		//header
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'会员名称');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'订单编号');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'收益金额');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'描述');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'收益时间');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'管理员名称');
		//data
		foreach ((array)$data as $k=>$v){
			$tmp = array();
			$tmp[] = array('data'=>$v['sc_membername']);
			$tmp[] = array('data'=>$v['sc_order_sn']);
			$tmp[] = array('data'=>$v['sc_points']);
			$tmp[] = array('data'=>$v['sc_desc']);
			$tmp[] = array('data'=>date('Y-m-d H:i:s',$v['sc_addtime']));
			$tmp[] = array('data'=>$v['sc_adminname']);
			
			$excel_data[] = $tmp;
		}
		$excel_data = $excel_obj->charset($excel_data,CHARSET);
		$excel_obj->addArray($excel_data);
		$excel_obj->addWorksheet($excel_obj->charset('收益明细表',CHARSET));
		$excel_obj->generateXML($excel_obj->charset('收益明细表',CHARSET).$_GET['curpage'].'-'.date('Y-m-d H:i:s',time()));
	}

}
