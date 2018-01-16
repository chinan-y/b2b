<?php
/**
 * 或作伙伴模型
 *
 */
defined('GcWebShop') or exit('Access Invalid!');

class partnerModel extends Model {
	
	protected $cachedData;
	
	public function __construct() {
		parent::__construct('partner');
	}
	
	 /**
     * 添加合作平台
     * @return boolean
     */
    public function addPartner($insert) {
        return $this->insert($insert);
    }
    
    /**
     * 编辑合作平台
     * @param array $condition
     * @param array $update
     * @return boolean
     */
    public function editPartner($condition, $update) {
        return $this->where($condition)->update($update);
    }

	/**
	 * 获取或作伙伴列表
	 *
	 * @return mixed
	 */
	public function getPartnerList($condition = array(), $fields = '*',$page = 0, $group = '') {
		return $this->where($condition)->field($fields)->page($page)->limit(false)->group($group)->select();
	}

	/**
	 * 获取或作伙伴详情
	 *
	 * @return mixed
	 */
	public function getPartnerInfo($condition = array(), $fileds = '*') {
		return $this->where($condition)->field($fileds)->find();
	}

	/**
	 * 获取或作伙伴缓存
	 *
	 * @return array
	 */
	public function getPartners() {
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
		if ($data = rkcache('partner')) {
			$this->cachedData = $data;
			return $data;
		}

		// 查库
		$data = array();
		$partner_all_array = $this->limit(false)->select();
		foreach ((array) $partner_all_array as $a) {
			$data[$a['partner_id']] = $a;
		}

		wkcache('partner', $data);
		$this->cachedData = $data;

		return $data;
	}


}
