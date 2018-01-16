<?php
/**
 * 销售业绩日志
 *
 */
defined('GcWebShop') or exit('Access Invalid!');

class salescreditModel {
	/**
	 * 业绩操作
	 * @author By Ming
	 * @param  bool $if_repeat 是否可以重复记录的信息,true可以重复记录，false不可以重复记录，默认为true
	 * @return bool
	 */
	function saveSalescreditLog($stage,$insertarr,$if_repeat = true){
		if (!$insertarr['sc_memberid']){
			return false;
		}
		//记录原因文字
		switch ($stage){
			case 'regist':
				if (!$insertarr['sc_desc']){
					$insertarr['sc_desc'] = '注册会员';
				}
				// $insertarr['sc_points'] = intval(C('salescredit_reg'));
				$insertarr['sc_points'] = 0;
				break;
			case 'login':
				if (!$insertarr['sc_desc']){
					$insertarr['sc_desc'] = '会员登录';
				}
				// $insertarr['sc_points'] = intval(C('salescredit_login'));
				$insertarr['sc_points'] = 0;
				break;
			case 'comments':
				if (!$insertarr['sc_desc']){
					$insertarr['sc_desc'] = '评论商品';
				}
				// $insertarr['sc_points'] = intval(C('salescredit_comments'));
				$insertarr['sc_points'] = 0;
				break;
			case 'system':
				break;
			case 'pointorder':
				if (!$insertarr['sc_desc']){
					$insertarr['sc_desc'] = '直接消费信息'.$insertarr['salescredit_ordersn'].'扣除金额';
				}
				break;

			//邀请注册返利
			case 'inviter':
				if (!$insertarr['sc_desc']){
					$insertarr['sc_desc'] = '邀请新会员['.$insertarr['invited'].']注册';
				}
				// $insertarr['sc_points'] = intval($GLOBALS['setting_config']['salescredit_invite']);
				$insertarr['sc_points'] = 0;
				break;
			//会员自身消费返利
			case 'order':
				if (!$insertarr['sc_desc']){
					$insertarr['sc_desc'] = '用户 '.$insertarr['buyer_name'].' 完成订单交易 [消费奖励]';
				}
				$insertarr['sc_points'] = $insertarr['rebate_amount'];
				break;
			//会员推广下级用户返利
			case 'rebate':
				if (!$insertarr['sc_desc']){
					$insertarr['sc_desc'] = '用户 '.$insertarr['buyer_name'].' 完成订单交易 [推广奖励]';
				}
				$insertarr['sc_points'] = $insertarr['rebate_amount'];
				break;
			//会员订单退款取消返利
			case 'refund':
				if (!$insertarr['sc_desc']){
					$insertarr['sc_desc'] = '用户 '.$insertarr['buyer_name'].' 订单退款，取消返利';
				}
				$insertarr['sc_points'] = (-1)*$insertarr['rebate_amount'];
				break;
			//销售返利提现
			case 'salerebate':
				if (!$insertarr['sc_desc']){
					$insertarr['sc_desc'] = '用户['.$_SESSION['member_name'].']返利提现'.(-1)*$insertarr['fanli_amount'].'元充入个人账户余额';
				}
				$insertarr['sc_points'] = $insertarr['fanli_amount'];
				break;
			case 'other':
				break;
		}
		$save_sign = true;
		if ($if_repeat == false){
			//检测是否有相关信息存在，如果没有，入库
			$condition['sc_memberid'] = $insertarr['sc_memberid'];
			$condition['sc_stage'] = $stage;
			$log_array = self::getSalescreditInfo($condition,$page);
			if (!empty($log_array)){
				$save_sign = false;
			}
		}
		if ($save_sign == false){
			return true;
		}
		//新增业绩
		$value_array = array();
		$value_array['sc_memberid'] = $insertarr['sc_memberid'];
		$value_array['sc_membername'] = $insertarr['sc_membername'];
		if ($insertarr['sc_adminid']){
			$value_array['sc_adminid'] = $insertarr['sc_adminid'];
		}
		if ($insertarr['sc_adminname']){
			$value_array['sc_adminname'] = $insertarr['sc_adminname'];
		}
		$value_array['sc_points'] = $insertarr['sc_points'];
		$value_array['sc_addtime'] = time();
		$value_array['sc_order_sn'] = $insertarr['order_sn'];
		$value_array['sc_desc'] = $insertarr['sc_desc'];
		$value_array['sc_stage'] = $stage;
		$result = false;
		if($value_array['sc_points'] != '0'){
			$result = self::addSalescreditLog($value_array);
		}
		if ($result){
			//更新member内容
			$obj_member = Model('member');
			$upmember_array = array();
			$upmember_array['member_salescredit'] = array('exp','member_salescredit+'.$insertarr['sc_points']);
			$upmember_array['available_predeposit'] = array('exp','available_predeposit+'.$insertarr['sc_points']);
			$obj_member->editMember(array('member_id'=>$insertarr['sc_memberid']),$upmember_array);
			
			//更新订单表是否已返利为是
			$is_rebate=array();
			$is_rebate['is_rebate']= 1;
			$isrebate=Model()->table('order')->where(array('order_sn'=>$insertarr['order_sn']))->update($is_rebate);
			
			return true;
		}else {
			return false;
		}

	}
	/**
	 * 添加业绩记录日志信息
	 *
	 * @param array $param 添加信息数组
	 */
	public function addSalescreditLog($param) {
		if(empty($param)) {
			return false;
		}
		$result	= Db::insert('salescredit_log',$param);
		return $result;
	}
	
	/**
	 * 业绩记录列表(getSalescreditLogList分页有问题)
	 *
	 */
	public function getSalescreditList($condition,$page='',$field='*',$order = 'sc_id desc', $limit = ''){
		return Model()->table('salescredit_log')->where($condition)->page($page)->order($order)->field($field)->limit($limit)->select();
	}
	
	/**
	 * 业绩记录列表
	 *
	 * @param array $condition 条件数组
	 * @param array $page   分页
	 * @param array $field   查询字段
	 * @param array $page   分页
	 */
	public function getSalescreditLogList($condition,$page='',$field='*'){
		$condition_str	= $this->getCondition($condition);

		$param	= array();
		$param['table']	= 'salescredit_log';
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'salescredit_log.sc_id desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		
		return Db::select($param,$page);
	}
	/**
	 * 详细信息
	 *
	 * @param array $condition 条件数组
	 * @param array $field   查询字段
	 */
	public function getSalescreditInfo($condition,$field='*'){
		//得到条件语句
		$condition_str	= $this->getCondition($condition);
		$array			= array();
		$array['table']	= 'salescredit_log';
		$array['where']	= $condition_str;
		$array['field']	= $field;
		$list		= Db::select($array);
		return $list[0];
	}
	
	/**
     * 取得数量
     * @param unknown $condition
     */
    public function getSalescreditCount($condition) {
        return Model()->table('salescredit_log')->where($condition)->count();
    }
	
	/**
	 * 将条件数组组合为SQL语句的条件部分
	 *
	 * @param	array $condition_array
	 * @return	string
	 */
	private function getCondition($condition_array){
		$condition_sql = '';
		//会员编号
		if ($condition_array['sc_memberid']) {
			$condition_sql	.= " and `salescredit_log`.sc_memberid = '{$condition_array['sc_memberid']}'";
		}
		//销售员编号
		if ($condition_array['sc_memberid_in']) {
			$condition_sql	.= " and `salescredit_log`.sc_memberid in ({$condition_array['sc_memberid_in']})";
		}
		//操作阶段
		if ($condition_array['sc_stage']) {
			$condition_sql	.= " and `salescredit_log`.sc_stage = '{$condition_array['sc_stage']}'";
		}
		//会员名称
		if ($condition_array['sc_membername_like']) {
			$condition_sql	.= " and `salescredit_log`.sc_membername like '%{$condition_array['sc_membername_like']}%'";
		}
		//管理员名称
		if ($condition_array['sc_adminname_like']) {
			$condition_sql	.= " and `salescredit_log`.sc_adminname like '%{$condition_array['sc_adminname_like']}%'";
		}
		//添加时间
		if ($condition_array['saddtime']){
			$condition_sql	.= " and `salescredit_log`.sc_addtime >= '{$condition_array['saddtime']}'";
		}
		if ($condition_array['eaddtime']){
			$condition_sql	.= " and `salescredit_log`.sc_addtime <= '{$condition_array['eaddtime']}'";
		}
		//描述
		if ($condition_array['sc_desc_like']){
			$condition_sql	.= " and `salescredit_log`.sc_desc like '%{$condition_array['sc_desc_like']}%'";
		}
		//订单编号
		if ($condition_array['sc_order_sn']){
			$condition_sql	.= " and `salescredit_log`.sc_order_sn = '{$condition_array['sc_order_sn']}'";
		}
		return $condition_sql;
	}
}
