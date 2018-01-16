<?php
/**
 * 发货仓模型
 *
 */
defined('GcWebShop') or exit('Access Invalid!');

class regionModel extends Model {
	
	protected $cachedData;
	
	public function __construct() {
		parent::__construct('region');
	}

	/**
	 * 获取发货仓列表
	 *
	 * @return mixed
	 */
	public function getRegionList($condition = array(), $fields = '*', $group = '') {
		return $this->where($condition)->field($fields)->limit(false)->group($group)->select();
	}

	/**
	 * 获取发货仓详情
	 *
	 * @return mixed
	 */
	public function getRegionInfo($condition = array(), $fileds = '*') {
		return $this->where($condition)->field($fileds)->find();
	}

	/**
	 * 获取发货仓缓存
	 *
	 * @return array
	 */
	public function getRegions() {
		return $this->getCache();
	}

	/**
	 * 获取发货仓数组 格式如下
	 *
	 * @return array
	 */
	protected function getCache() {
		// 对象属性中有数据则返回
		if ($this->cachedData !== null)
			return $this->cachedData;

		// 缓存中有数据则返回
		if ($data = rkcache('region')) {
			$this->cachedData = $data;
			return $data;
		}

		// 查库
		$data = array();
		$region_all_array = $this->limit(false)->select();
		foreach ((array) $region_all_array as $a) {
			$data[$a['id']] = $a;
		}

		wkcache('region', $data);
		$this->cachedData = $data;

		return $data;
	}


}
