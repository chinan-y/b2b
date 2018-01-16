<?php
/**
 * 延迟队列模型
 *
 */
defined('GcWebShop') or exit('Access Invalid!');

class delyModel extends Model {
	
	protected $limit = 20;
	
	protected $cachedData;
	
	public function __construct() {
		parent::__construct('dely');
	}

	/**
	 * 添加延迟队列
	 *
	 * @return mixed
	 */
	public function addDely($action, $param) {
		$data = array(
			'action'=>$action,
			'times'=> 0,
			'next_time'=> time(),
			'param'=> serialize($param),
		);
		return $this->insert($data);
	}
	
	/**
	 * 扫描延迟队列
	 *
	 * @return mixed
	 */
	public function scan() {
		$condition = array(
			'next_time' => array('elt', time()),
		);
		return $this->where($condition)->field('*')->limit($this->limit)->order('id ASC')->select();
	}
}
