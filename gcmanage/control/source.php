<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/7
 * Time: 10:07
 */
defined('GcWebShop') or exit('Access Invalid!');

class sourceControl extends SystemControl
{
    public function __construct()
    {
        Language::read('source');
        parent::__construct();
    }
	/**
     *商品来源列表
     */
    public function listsOp(){
		$model_source = Model('source');
        $source_list =$model_source->getGoodsSourceList($condition);
		Tpl::output('page',$model_source->showpage());
		Tpl::output('source_list',$source_list);
        Tpl::showpage('goods_source');
    }

    /**
     *添加商品来源
     */
    public function  addSourceOp(){
        $message=array();
        $message['source_code'] =$_POST['source_code'];
        $message['source_name'] =$_POST['source_name'];
        $message['description'] =$_POST['description'];
        $model_source = Model('source');
        $result =$model_source->addGoodsSource($message);
        if($result){
            showMessage(Language::get('add_success'));
        }
    }
	
	/**
     *删除商品来源
     */
    public function  del_sourceOp(){
        $condition=array();
        $condition['source_code'] =$_GET['source_code'];
        $model_source = Model('source');
        $result =$model_source->delGoodsSource($condition);
        if($result){
            showMessage(Language::get('nc_common_del_succ'));
        }
    }

}