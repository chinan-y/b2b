<?php
/**
 * 网站阿里云相关参数设置
 ***/

defined('GcWebShop') or exit('Access Invalid!');
class aliyunsetControl extends SystemControl{
	private $links = array(
		array('url'=>'gct=aliyunset&gp=index','lang'=>'aliyun_oss_set'),
		array('url'=>'gct=aliyunset&gp=imageoss','lang'=>'aliyun_other_set'),
	);
	public function __construct(){
		parent::__construct();
		Language::read('setting');
	}

	/**
	 * 基本信息
	 */
	public function indexOp(){
		$model_setting = Model('setting');
		if(!empty($_POST['OSS_ACCESS_ID'])&&!empty($_POST['OSS_ACCESS_KEY'])&&!empty($_POST['OSS_ENDPOINT'])&&!empty($_POST['OSS_TEST_BUCKET'])){
		$update_array = array();
		$update_array['OSS_ACCESS_ID'] 	= $_POST['OSS_ACCESS_ID'];
		$update_array['OSS_ACCESS_KEY'] = $_POST['OSS_ACCESS_KEY'];
		$update_array['OSS_ENDPOINT'] 	= $_POST['OSS_ENDPOINT'];
		$update_array['OSS_TEST_BUCKET'] = $_POST['OSS_TEST_BUCKET'];

		$result = $model_setting->updateSetting($update_array);
		if($result){
			showMessage('数据更新成功！');
		}else{
			showMessage('数据更新失败！');
		}
	}
		$list_setting = $model_setting->getListSetting();
		Tpl::output('list_setting',$list_setting);

		//输出子菜单
		Tpl::output('top_link',$this->sublink($this->links,'base'));

		Tpl::showpage('aliyunset.index');
	}

	/**
	 * imageqqbsmall-oss 基本信息
	 */
	public function imageossOp(){
		$model_setting = Model('setting');
		if(!empty($_POST['form_submit'])){
			$update_array = array();
			$update_array['OSS_IS_STORAGE'] 	= $_POST['OSS_IS_STORAGE'];

			$result = $model_setting->updateSetting($update_array);
			if($result){
				showMessage('更新成功！');
			}else{
				showMessage('更新失败！');
			}
		}
		
		$list_setting = $model_setting->getListSetting();
		Tpl::output('list_setting',$list_setting);

		//输出子菜单
		Tpl::output('top_link',$this->sublink($this->links,'base'));

		Tpl::showpage('aliyunset.imageoss');
	}

	/**
	 * 防灌水设置
	 */
	public function dumpOp(){
		$model_setting = Model('setting');
		if (chksubmit()){
			$update_array = array();
			$update_array['guest_comment'] = $_POST['guest_comment'];
			$update_array['captcha_status_login'] = $_POST['captcha_status_login'];
			$update_array['captcha_status_register'] = $_POST['captcha_status_register'];
			$update_array['captcha_status_goodsqa'] = $_POST['captcha_status_goodsqa'];
			$result = $model_setting->updateSetting($update_array);
			if ($result === true){
				$this->log(L('nc_edit,dis_dump'),1);
				showMessage(L('nc_common_save_succ'));
			}else {
				$this->log(L('nc_edit,dis_dump'),0);
				showMessage(L('nc_common_save_fail'));
			}
		}
		$list_setting = $model_setting->getListSetting();
		Tpl::output('list_setting',$list_setting);
		Tpl::output('top_link',$this->sublink($this->links,'dump'));
		Tpl::showpage('setting.dump');
	}
	
	/**
	 * 网站PC端和手机端立即购买、加入购物车、注册方式开关
	 */
	public function buyOp(){
		$model_setting = Model('setting');
		if (chksubmit()){
			$update_array = array();
			$update_array['pc_buy'] = $_POST['pc_buy'];
			$update_array['pc_cart'] = $_POST['pc_cart'];
			$update_array['wap_buy'] = $_POST['wap_buy'];
			$update_array['wap_cart'] = $_POST['wap_cart'];
			$update_array['register_way'] = $_POST['register_way'];
			$update_array['weixin_logon'] = $_POST['weixin_logon'];
			$result = $model_setting->updateSetting($update_array);
			if ($result === true){
				$this->log(L('nc_edit,buy_cart'),1);
				showMessage(L('nc_common_save_succ'));
			}else {
				$this->log(L('nc_edit,buy_cart'),0);
				showMessage(L('nc_common_save_fail'));
			}
		}
		$list_setting = $model_setting->getListSetting();
		Tpl::output('list_setting',$list_setting);
		Tpl::output('top_link',$this->sublink($this->links,'buy'));
		Tpl::showpage('setting.buy');
	}

	/**
	 * SEO与rewrite设置
	 */
	public function seoOp(){
		$model_setting = Model('setting');
		if (chksubmit()){
			$update_array = array();
			$update_array['rewrite_enabled'] = $_POST['rewrite_enabled'];
			$result = $model_setting->updateSetting($update_array);
			if ($result === true){
				$this->log(L('nc_edit,nc_seo_set'),1);
				showMessage(L('nc_common_save_succ'));
			}else {
				$this->log(L('nc_edit,nc_seo_set'),0);
				showMessage(L('nc_common_save_fail'));
			}
		}
		$list_setting = $model_setting->getListSetting();

		//读取SEO信息
		$list = Model('seo')->select();
		$seo = array();
		foreach ((array)$list as $value) {
			$seo[$value['type']] = $value;
		}

		Tpl::output('list_setting',$list_setting);
		Tpl::output('seo',$seo);

		$category = Model('goods_class')->getGoodsClassForCacheModel();
		Tpl::output('category',$category);

		Tpl::showpage('setting.seo_setting');
	}

	public function ajax_categoryOp(){
		$model = Model('goods_class');
		$list = $model->field('gc_title,gc_keywords,gc_description')->find(intval($_GET['id']));
		//转码
		if (strtoupper(CHARSET) == 'GBK'){
			$list = Language::getUTF8($list);//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
		}
		echo json_encode($list);exit();
	}






}
