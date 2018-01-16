<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/30
 * Time: 11:36
 * 供货商代码模型
 */
defined('GcWebShop') or exit('Access Invalid!');

class mess_supplierModel extends Model {

    protected $cachedData;

    public function __construct() {
        parent::__construct('mess_supplier_code');
    }

    /**
     * 获取供货商列表
     *
     * @return mixed
     */
    public function getMessSupplierList($condition = array(), $fields = '*', $group = '') {
        return $this->where($condition)->field($fields)->limit(false)->group($group)->select();
    }

    /**
     * 获取供货商详情
     *
     * @return mixed
     */
    public function getMessSupplierInfo($condition = array(), $fileds = '*') {
        return $this->where($condition)->field($fileds)->find();
    }

    /**
     * 获取供货商缓存
     *
     * @return array
     */
    public function getMessSupplier() {
        return $this->getCache();
    }

    /**
     * 获取供货商数组 格式如下
     *
     * @return array
     */
    protected function getCache() {
        // 对象属性中有数据则返回
        if ($this->cachedData !== null)
            return $this->cachedData;

        // 缓存中有数据则返回(供货商在增加 暂时不用缓存)
        // if ($data = rkcache('mess_supplier')) {
            // $this->cachedData = $data;
            // return $data;
        // }

        // 查库
        $data = array();
        $mess_supplier_all_array = $this->limit(false)->select();
        foreach ((array) $mess_supplier_all_array as $a) {
            $data[$a['code_id']] = $a;
        }

        wkcache('mess_supplier', $data);
        $this->cachedData = $data;
        return $data;
    }


}
