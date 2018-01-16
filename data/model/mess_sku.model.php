<?php
/**
 * 海关接口管理	by ming
 */
defined('GcWebShop') or exit('Access Invalid!');

class mess_skuModel extends Model{
	public function __construct(){
		parent::__construct('mess_sku_info');
	}
	
    /**
     * 读取商品备案列表
     * @param unknown $condition
     * @param string $pagesize
     * @param string $order
     */
	 public function getMessSkuList($condition, $page='', $field='*'){
		 $result = $this->field($field)->where($condition)->page($page)->select();
		 return $result;
	 }
	 
    /**
     * 取得单条信息
     * @param unknown $condition
     */
    public function getMessSkuInfo($condition) {
        $result = $this->where($condition)->find();
        return $result;
    }
	 
	/**
     * SKU找查商品备案列表
     */
    public function getMessSkuInfoBySKU($SKU, $fields = '*') {
            $mess_sku_info = $this->getMessSkuInfo(array('SKU'=>$SKU),'*',true);
        return $mess_sku_info;
    }
	 
    /**
     * 增加商品备案
     * @param unknown $data
     * @return boolean
     */
    public function addMessSku($data){
        return $this->insert($data);	
    }

    /**
     * 删除商品备案
     * @param unknown $condition
     */
    public function delMessSku($condition){
        return $this->where($condition)->delete();
    }
    /**
     * 更新商品备案
     * @param unknown $data
     * @param unknown $condition
     */
    public function editMessSku($update, $condition){
        return $this->where($condition)->update($update);
    }
	
}
