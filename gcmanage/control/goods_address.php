<?php
/**
 * 相册管理
 *
 *
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');

class goods_addressControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('goods_address');
	}
	/**
	 * 相册列表
	 */
	public function listOp(){
		$model = Model();
		$condiiton = array();
		if (is_numeric($_GET['keyword'])){

			$condiiton['id'] = $_GET['keyword'];
			$store_name = $model->table('region')->getfby_store_id($_GET['keyword'],'address_name');
		}elseif (!empty($_GET['keyword'])){

			$store_name = $_GET['keyword'];
			$store_id = $model->table('region')->getfby_store_name($_GET['keyword'],'id');
			if (is_numeric($store_id)){
				$condiiton['id'] = $store_id;
			}else{
				$condiiton['id'] = 0;
			}
		}elseif (is_numeric($_GET['aclass_id'])){

			$condiiton['aclass_id'] = $_GET['aclass_id'];
		}
		$list = $model->table('region')->where($condiiton)->order('id desc')->page(40)->select();
		$show_page = $model->showpage();

		Tpl::output('pic_count',$show_page);
		Tpl::output('list',$list);
		Tpl::output('store_name',$store_name);
		Tpl::showpage('goods_address');
	}

	/**
	 * 图片列表
	 */
	public function pic_listOp(){

		$model = Model();
		$condiiton = array();
		if (is_numeric($_GET['keyword'])){

			$condiiton['id'] = $_GET['keyword'];
			$store_name = $model->table('region')->getfby_store_id($_GET['keyword'],'address_name');
		}elseif (!empty($_GET['keyword'])){

			$store_name = $_GET['keyword'];
			$store_id = $model->table('region')->getfby_store_name($_GET['keyword'],'id');
			if (is_numeric($store_id)){
				$condiiton['id'] = $store_id;
			}else{
				$condiiton['id'] = 0;
			}
		}elseif (is_numeric($_GET['aclass_id'])){

			$condiiton['aclass_id'] = $_GET['aclass_id'];
		}
		$list = $model->table('region')->where($condiiton)->order('id desc')->page(40)->select();
		$show_page = $model->showpage();
		Tpl::output('page',$show_page);
		Tpl::output('list',$list);
		Tpl::output('store_name',$store_name);
		Tpl::showpage('goods_address.pic_list');
	}
	/**
	 * 增加保税地区
	 */
	public function pic_tistOp(){

		$model = Model();
		$data = array('address_name' =>$_POST['keyword']);
		$add = $model ->table('region')->insert($data);
		if($add){
			showMessage('添加成功。');
		}
		
	}

	/**
	 * 删除相册
	 */
	public function aclass_delOp(){
		$id = $_GET['aclass_id'];
		$cha = Model()->table('region')->where(array('id' =>$id))->delete();
		if($cha){
			showMessage('删除成功。');
		}
	}

	/**
	 * 删除一张图片及其对应记录
	 *
	 */
	public function del_album_picOp(){
		list($apic_id,$filename) = @explode('|',$_GET['key']);
		if (!is_numeric($apic_id) || empty($filename)) exit('0');
		$this->del_file($filename);
		Model('album_pic')->where(array('apic_id'=>$apic_id))->delete();
		$this->log(L('nc_delete,g_album_pic_one').'[ID:'.$apic_id.']',1);
		exit('1');
	}

	/**
	 * 删除多张图片
	 *
	 */
	public function del_more_picOp(){
		$model= Model('album_pic');
		$list = $model->where(array('apic_id'=>array('in',$_POST['delbox'])))->select();
		if (is_array($list)){
			foreach ($list as $v) {
				$this->del_file($v['apic_cover']);
			}
		}
		$model->where(array('apic_id'=>array('in',$_POST['delbox'])))->delete();
		$this->log(L('nc_delete,g_album_pic_one').'[ID:'.implode(',',$_POST['delbox']).']',1);
		redirect();
	}

	/**
	 * 删除图片文件
	 *
	 */
	private function del_file($filename){
		//取店铺ID
		if (preg_match('/^(\d+_)/',$filename)){
			$store_id = substr($filename,0,strpos($filename,'_'));
		}else{
			$store_id = Model()->cls()->table('album_pic')->getfby_apic_cover($filename,'store_id');
		}
		$path = BASE_UPLOAD_PATH.'/'.ATTACH_GOODS.'/'.$store_id.'/'.$filename;

		$ext = strrchr($path, '.');
		$type = array('_tiny','_small','_mid','_max','_240x240');
		foreach ($type as $v) {
			if (is_file($fpath = $path.$v.$ext)){
				unlink($fpath);
			}
		}
		if (is_file($path)) unlink($path);
	}
}
