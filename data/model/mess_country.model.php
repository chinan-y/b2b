<?php
/**
 * 海关国家代码模型
 *
 */
defined('GcWebShop') or exit('Access Invalid!');

class mess_countryModel extends Model {
	
	protected $cachedData;
	
	public function __construct() {
		parent::__construct('mess_country_code');
	}

	/**
	 * 获取或作伙伴列表
	 *
	 * @return mixed
	 */
	public function getMessCountryList($condition = array(), $fields = '*', $group = '') {
		return $this->where($condition)->field($fields)->limit(false)->group($group)->select();
	}

	/**
	 * 获取或作伙伴详情
	 *
	 * @return mixed
	 */
	public function getMessCountryInfo($condition = array(), $fileds = '*') {
		return $this->where($condition)->field($fileds)->find();
	}

	/**
	 * 获取或作伙伴缓存
	 *
	 * @return array
	 */
	public function getMessCountrys() {
		return $this->getCache();
	}

	/**
	 * 获取或作伙伴数组 格式如下
	 *
	 * @return array
	 */
	protected function getCache() {
		// 对象属性中有数据则返回
		if ($this->cachedData !== null)
			return $this->cachedData;

		// 缓存中有数据则返回
		if ($data = rkcache('mess_country')) {
			$this->cachedData = $data;
			return $data;
		}

		// 查库
		$data = array();
		$mess_country_all_array = $this->limit(false)->select();
		foreach ((array) $mess_country_all_array as $a) {
			$data[$a['code']] = $a;
		}

		wkcache('mess_country', $data);
		$this->cachedData = $data;

		return $data;
	}


}
