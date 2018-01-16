<?php
/**  添加商品来源
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/6
 * Time: 11:17
 */

defined('GcWebShop') or exit('Access Invalid!');
class sourceModel extends Model{
    public function __construct() {
        parent::__construct('source');
    }

    /**
     * 新增商品来源数据
     * * @param	array	$source	数组
     */
    public function addGoodsSource($source){
        return $this->table('goods_source')->insert($source);
    }
	
	/**
     * 商品来源列表
     */
    public function getGoodsSourceList($condition, $field = '*', $page = 10){
        return $this->table('goods_source')->field($field)->where($condition)->page($page)->select();
    }
	
	/**
     * 删除商品来源
     */
    public function delGoodsSource($condition){
        return $this->table('goods_source')->where($condition)->delete();
    }
	
	/**
	* 获取商品来源缓存
	* @return array
	*/
    public function getGoodsSource() {
        return $this->getCache();
    }

    /**
     * 获取商品来源数组 格式如下
     * @return array
     */
    protected function getCache() {
        // 对象属性中有数据则返回
        if ($this->cachedData !== null)
            return $this->cachedData;

        // 缓存中有数据则返回(商品来源在增加 暂时不用缓存)
        // if ($data = rkcache('mess_supplier')) {
            // $this->cachedData = $data;
            // return $data;
        // }

        // 查库
        $data = array();
        $goods_source_list = $this->table('goods_source')->limit(false)->select();
        foreach ((array) $goods_source_list as $val) {
            $data[$val['source_code']] = $val;
        }

        wkcache('mess_supplier', $data);
        $this->cachedData = $data;
        return $data;
    }

}