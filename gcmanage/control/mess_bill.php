<?php
defined('GcWebShop') or exit('Access Invalid!');

class mess_billControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('mess');
	}
	
	/**
	 * 运单管理
	 */
	public function mess_billOp(){
		$lang	= Language::getLangContent();
		$model_mess = Model('mess');
		/**
		 * 运单管理
		 */
		Tpl::showpage('mess.bill');
	}

	
}
