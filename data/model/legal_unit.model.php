<?php
/**
 * 申报的计量单位
 *
 */
defined('GcWebShop') or exit('Access Invalid!');

class legal_unitModel extends Model {
	
	protected $cachedData;
	
	public function __construct() {
		parent::__construct('legal_unit');
	}

	/**
	 * 获取计量单位缓存
	 *
	 * @return array
	 */
	public function getLegalUnit() {
		return $this->getCache();
	}

	/**
	 * 获取计量单位数组 格式如下
	 *
	 * @return array
	 */
	protected function getCache() {
		// 对象属性中有数据则返回
		if ($this->cachedData !== null)
			return $this->cachedData;

		// 缓存中有数据则返回
		if ($data = rkcache('legal_unit')) {
			$this->cachedData = $data;
			return $data;
		}

		// 查库
		$data = array();
		$mess_legal_unit_array = $this->limit(false)->select();
		foreach ((array) $mess_legal_unit_array as $a) {
			$data[$a['CODE']] = $a;
		}

		wkcache('legal_unit', $data);
		$this->cachedData = $data;

		return $data;
	}


}
