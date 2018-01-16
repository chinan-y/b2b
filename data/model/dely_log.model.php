<?php
/**
 * 延迟队列日志模型
 *
 */
defined('GcWebShop') or exit('Access Invalid!');

class dely_logModel extends Model {
	
	protected $cachedData;
	
	public function __construct() {
		parent::__construct('dely_log');
	}

	/**
	 * 获取延迟队列日志
	 *
	 * @return mixed
	 */
	public function getDelyLogList($condition = array(), $fields = '*', $group = '') {
		return $this->where($condition)->field($fields)->limit(false)->group($group)->select();
	}

	/**
	 * 获取延迟队列日志详情
	 *
	 * @return mixed
	 */
	public function getDelyLogInfo($condition = array(), $fileds = '*') {
		return $this->where($condition)->field($fileds)->find();
	}
	
	/**
	 * 修改延迟队列日志详情
	 *
	 * @return mixed
	 */
	public function addDelyLogInfo($data) {
		return $this->insert($data);
	}
	
}
