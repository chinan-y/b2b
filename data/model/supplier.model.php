<?php
/** 添加供货商
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/7
 * Time: 13:51
 */
defined('GcWebShop') or exit('Access Invalid!');
class supplierModel extends Model{

    public function __construct() {
        parent::__construct('supplier');
    }

    /**
     * 新增供货商数据
     * * @param	array	$in_supplier	数组
     */
    public function addGoodsSupplier($in_supplier){
        return $this->table('mess_supplier_code')->insert($in_supplier);
    }

	/**
     * 供货商列表
     */
    public function getGoodsSupplierList($condition, $field = '*', $page = 10){
        return $this->table('mess_supplier_code')->field($field)->where($condition)->page($page)->select();
    }
	
	/**
     * 删除供货商
     */
    public function delGoodsSupplier($condition){
        return $this->table('mess_supplier_code')->where($condition)->delete();
    }
}