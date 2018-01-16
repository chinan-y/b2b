<?php
/**
 * 会员管理
 *
 *
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');

class member_batchControl extends SystemControl{
	const EXPORT_SIZE = 1000;
	const BATCH_SIZE = 1000;
	public function __construct(){
		parent::__construct();
		Language::read('member');
		Language::read('member_batch');
	}

	/**
	 * 会员管理
	 */
	public function memberOp(){
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
					$type = 'sendEmailMsg';
					if($_POST['type'] == 'message'){
						$type = 'sendMessageMsg';
					}elseif($_POST['type'] == 'short'){
						$type = 'sendSmsMsg';
					}
					$code = $_POST['code'];
					$model_message = Model('member_msg_tpl');
					$message = $model_message->getMemberMsgTplInfo(array('mmt_code'=>$code));
					if(empty($message)){
						showMessage($lang['invalid_request']);
					}
					if($_POST['type'] == 'message' && !$message['mmt_message_switch']){
						showMessage('当前模版操作被关闭，请联系超级管理员');
					}
					if($_POST['type'] == 'short' && !$message['mmt_short_switch']){
						showMessage('当前模版操作被关闭，请联系超级管理员');
					}
					if($_POST['type'] == 'mail' && !$message['mmt_mail_switch']){
						showMessage('当前模版操作被关闭，请联系超级管理员');
					}
					$del_ids = join(',',$_POST['del_id']);
					$member_list =  $model_member->getMemberList(array(	'member_id'=>array('IN', $del_ids)));
					foreach ($member_list as $k => $member){
						if ($member){
							if($type == 'sendMessageMsg'){
								if(empty($member['member_email']))continue;
								$param = array();
								$param['member_id'] = $member['member_id'];
								$param['code'] = $code;
								$param['acount'] = $member['member_email'];
								$param['subject'] = $model_message->compileMemberMsgTpl($message['mmt_mail_subject'],$member);
								$param['message'] = $model_message->compileMemberMsgTpl($message['mmt_mail_content'],$member);
							}elseif($type == 'sendSmsMsg'){
								if(empty($member['member_mobile']))continue;
								$param = array();
								$param['member_id'] = $member['member_id'];
								$param['code'] = $code;
								$param['acount'] = $member['member_mobile'];
								$param['message'] = $model_message->compileMemberMsgTpl($message['mmt_short_content'],$member);;
							}elseif($type == 'sendEmailMsg'){
								if(empty($member['member_email']))continue;
								$param = array();
								$param['member_id'] = $member['member_id'];
								$param['code'] = $code;
								$param['acount'] = $member['member_email'];
								$param['subject'] = $model_message->compileMemberMsgTpl($message['mmt_mail_subject'],$member);
								$param['message'] = $model_message->compileMemberMsgTpl($message['mmt_mail_content'],$member);
							}
							QueueClient::push($type, $param);
						}
					}
				}
				showMessage($lang['nc_common_op_succ']);
			}else {
				showMessage($lang['nc_common_op_fail']);
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

		//会员来源
		$_GET['search_saleplat'] = isset($_GET['search_saleplat']) ? $_GET['search_saleplat'] : -1;
		$search_saleplat = intval($_GET['search_saleplat']);
		if ($search_saleplat >= 0){
			$condition['saleplat_id'] = $search_saleplat;
			Tpl::output('search_saleplat',$search_saleplat);
		}
		Tpl::output('member_saleplat',$model_member->getMemberSaleplatArr());
		//会员消息模版模版
		$mmtpl_list = Model('member_msg_tpl')->getMemberMsgTplList(array('mmt_type'=>1));
		Tpl::output('mmtpl_list', $mmtpl_list);

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
			}
		}
		Tpl::output('member_grade',$member_grade);
		Tpl::output('search_sort',trim($_GET['search_sort']));
		Tpl::output('search_field_name',trim($_GET['search_field_name']));
		Tpl::output('search_field_value',trim($_GET['search_field_value']));
		Tpl::output('member_list',$member_list);
		Tpl::output('page',$model_member->showpage());
		Tpl::showpage('member_batch.index');
	}

	/**
	 * 批量发信
	 */
	public function batch_resultOp(){
		set_time_limit(0);
		$model_member = Model('member');

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

		//会员来源
		$_GET['search_saleplat'] = isset($_GET['search_saleplat']) ? $_GET['search_saleplat'] : -1;
		$search_saleplat = intval($_GET['search_saleplat']);
		if ($search_saleplat >= 0){
			$condition['saleplat_id'] = $search_saleplat;
		}

		$rcount = $model_member->getMemberCount($condition);
		if($rcount > 0){
			$type = 'sendEmailMsg';
			if($_GET['type'] == 'message'){
				$type = 'sendMessageMsg';
			}elseif($_GET['type'] == 'short'){
				$type = 'sendSmsMsg';
			}
			$code = $_GET['code'];
			$model_message = Model('member_msg_tpl');
			$message = $model_message->getMemberMsgTplInfo(array('mmt_code'=>$_GET['code']));
			if(empty($message)){
				showMessage($lang['invalid_request']);
			}
			if($_GET['type'] == 'message' && !$message['mmt_message_switch']){
				showMessage('当前模版操作被关闭，请联系超级管理员');
			}
			if($_GET['type'] == 'short' && !$message['mmt_short_switch']){
				showMessage('当前模版操作被关闭，请联系超级管理员');
			}
			if($_GET['type'] == 'mail' && !$message['mmt_mail_switch']){
				showMessage('当前模版操作被关闭，请联系超级管理员');
			}

			$count = ceil($rcount / self::BATCH_SIZE);
			for($i=1;$i<=$count;$i++){
				$_GET['curpage'] = $i;
				$member_list = $model_member->getMemberList($condition, '*', self::BATCH_SIZE);
				foreach($member_list as $member){
					if ($member){
						if($type == 'sendMessageMsg'){
							if(empty($member['member_email']))continue;
							$param = array();
							$param['member_id'] = $member['member_id'];
							$param['code'] = $code;
							$param['acount'] = $member['member_email'];
							$param['subject'] = $model_message->compileMemberMsgTpl($message['mmt_mail_subject'],$member);
							$param['message'] = $model_message->compileMemberMsgTpl($message['mmt_mail_content'],$member);
						}elseif($type == 'sendSmsMsg'){
							if(empty($member['member_mobile']))continue;
							$param = array();
							$param['member_id'] = $member['member_id'];
							$param['code'] = $code;
							$param['acount'] = $member['member_mobile'];
							$param['message'] = $model_message->compileMemberMsgTpl($message['mmt_short_content'],$member);;
						}elseif($type == 'sendEmailMsg'){
							if(empty($member['member_email']))continue;
							$param = array();
							$param['member_id'] = $member['member_id'];
							$param['code'] = $code;
							$param['acount'] = $member['member_email'];
							$param['subject'] = $model_message->compileMemberMsgTpl($message['mmt_mail_subject'],$member);
							$param['message'] = $model_message->compileMemberMsgTpl($message['mmt_mail_content'],$member);
						}
						QueueClient::push($type, $param);
					}
				}
			}
			showMessage('当前发送结果集已经添加消息务器中，如果发送数量过大，发送过程可能比较缓慢');
		}else{
			showMessage('结果集为空');
		}

	}


}
