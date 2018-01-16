<?php

/**
 * 支付宝批量付款到支付宝账户有密接口入口
 *
 */
defined('GcWebShop') or exit('Access Invalid!');

class alipay_batchControl extends SystemControl{
	
	public function __construct(){
		parent::__construct();
		Language::read('predeposit');
	}
	
	public function indexOp(){
		$condition = array();
		$condition['pdc_payment_state'] = 2;
		
		$model_pd = Model('predeposit');
		$cash_list = $model_pd->getPdCashList($condition,5,'*','pdc_id desc');
		Tpl::output('list',$cash_list);
		Tpl::output('show_page',$model_pd->showpage());
		Tpl::showpage('alipay_batch.add');	
	}
	
	public function payment_stateOp(){
		$data = array();
		$data['pdc_payment_state'] = 1;
		$data['pdc_payment_time'] = TIMESTAMP;
		
		$model_pd = Model('predeposit');
		$re = $model_pd->editPdCash($data, array('pdc_id'=>$_GET['pdc_id']));
		
		if($re){
			$condition = array();
			$condition['member_id'] = $_GET['member_id'];
			$condition['member_name'] = $_GET['name'];
			$condition['admin_name'] = $_GET['admin'];
			$condition['amount'] = $_GET['amount'];
			$condition['order_sn'] = $_GET['pdc_sn'];
		$model_pd->changePd('cash_pay', $condition);
		showMessage('更改成功');
		}
	}
	
	public function batchOp(){
		
		include(BASE_PATH.'/api/batch_trans_notify/alipayapi.php');
		$alipay = new withdrawDeposit();
		$alipay->index($_POST['serial_num'], $_POST['account'], $_POST['name'], $_POST['amount']);
	}

}
