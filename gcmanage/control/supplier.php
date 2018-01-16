<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/3
 * Time: 15:48
 */
defined('GcWebShop') or exit('Access Invalid!');
class supplierControl extends SystemControl
{
    public function __construct()
    {
        Language::read('supplier');
        parent::__construct();
    }
	
	/**
     *供货商列表
     */
    public function listOp(){
		$model_supplier = Model('supplier');
        $supplier_list =$model_supplier->getGoodsSupplierList($condition);
		Tpl::output('page',$model_supplier->showpage());
		Tpl::output('supplier_list',$supplier_list);
        Tpl::showpage('goods_supplier');
    }
    /**
     *添加供货商
     */
    public function  addSupplierOp(){
        $message=array();
        $message['supplier_name'] =$_POST['name'];
        $model_supplier = Model('supplier');
        $result =$model_supplier->addGoodsSupplier($message);
        if($result){
            showMessage(Language::get('add_suc'));
        }
    }
	
	/**
     *删除供货商
     */
    public function  del_supplierOp(){
        $condition=array();
        $condition['code_id'] =$_GET['code_id'];
        $model_supplier = Model('supplier');
        $result =$model_supplier->delGoodsSupplier($condition);
        if($result){
            showMessage(Language::get('nc_common_del_succ'));
        }
    }

}