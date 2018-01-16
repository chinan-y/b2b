<?php
/**
 * 接口
 *
 *
 *
 ***/


defined('GcWebShop') or exit('Access Invalid!');

class apiControl extends BaseApiControl {
	/**
	 * 接口
	 */
    public function indexOp() {
		require_once(BASE_CORE_PATH.DS.'framework/function/gcclient.php');
		//output_error('访问接口不存在',array('code'=>'400'));
		Tpl::showpage('partner');
    }
}
