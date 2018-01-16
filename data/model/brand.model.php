<?php
/**
 * 商品品牌模型
 *
 * 
 *
 *
 
 */
defined('GcWebShop') or exit('Access Invalid!');

class brandModel extends Model {
    public function __construct() {
        parent::__construct('brand');
    }
    
    /**
     * 添加品牌
     * @param array $insert
     * @return boolean
     */
    public function addBrand($insert) {
        return $this->insert($insert);
    }
    
    /**
     * 编辑品牌
     * @param array $condition
     * @param array $update
     * @return boolean
     */
    public function editBrand($condition, $update) {
        return $this->where($condition)->update($update);
    }
    
    /**
     * 删除品牌
     * @param unknown $condition
     * @return boolean
     */
    public function delBrand($condition) {
        $brand_array = $this->getBrandList($condition, 'brand_id,brand_pic');
        $brandid_array = array();
        foreach ($brand_array as $value) {
            $brandid_array[] = $value['brand_id'];
            @unlink(BASE_UPLOAD_PATH.DS.ATTACH_BRAND.DS.$value['brand_pic']);
        }
        return $this->where(array('brand_id' => array('in', $brandid_array)))->delete();
    }
    
    /**
     * 查询品牌数量
     * @param array $condition
     * @return array
     */
    public function getBrandCount($condition) {
        return $this->where($condition)->count();
    }
    
    /**
     * 品牌列表
     * @param array $condition
     * @param string $field
     * @param string $order
     * @param number $page
     * @param string $limit
     * @return array
     */
    public function getBrandList($condition, $field = '*', $page = 0, $order = 'brand_sort asc, brand_id desc', $limit = '') {
        return $this->where($condition)->field($field)->order($order)->page($page)->limit($limit)->select();
    }
    
    /**
     * 通过的品牌列表
     * 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getBrandPassedList($condition, $field = '*', $page = 0, $order = 'brand_sort asc, brand_id desc', $limit = '') {
        $condition['brand_apply'] = 1;
        return $this->getBrandList($condition, $field, $page, $order, $limit);
    }
    
    /**
     * 未通过的品牌列表
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getBrandNoPassedList($condition, $field = '*', $page = 0) {
        $condition['brand_apply'] = 0;
        return $this->getBrandList($condition, $field, $page);
    }
    
    /**
     * 取单个品牌内容
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getBrandInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 首页获取推荐商品
     * @param int $count 推荐数量
     * @return array 推荐商品列表
     *
     */
    public function getIndexBrandList($count = 1000) {
        $prefix = 'home_index_brand';

        $item_list = rcache($count, $prefix);
        //缓存有效
        if(!empty($item_list)) {
            return unserialize($item_list['brand']);
        }

        //缓存无效查库并缓存
        $condition = array();
        $condition['brand_apply'] = 1;
        $item_list = $this->getBrandList($condition, '*', '', 'brand_class DESC', $count);

        $cache = array('brand' => serialize($item_list));
        wcache($count, $cache, $prefix);
        return $item_list;
    }
}