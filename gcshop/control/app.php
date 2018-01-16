<?php
/**
 * APP下载页面
 */
defined('GcWebShop') or exit('Access Invalid!');
class appControl extends BaseHomeControl{
	public function indexOp(){
		Tpl::showpage('app');
	}
}