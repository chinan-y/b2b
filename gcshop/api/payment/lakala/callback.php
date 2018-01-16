<?php

/**
 * 模拟用户支付后同步回调页面
 * @author chunkuan <urcn@qq.com>
 */
include('/common/common.php');

if($post['retCode'] != '0000'){
  echo $post['retMsg']; //支付失败 & 输出支付失败原因
}else{
    $res = decryptReqData($post, $config['pk'], $config['rk']);
    //处理拉卡拉的数据
	$_GET['gct']	= 'payment';
	$_GET['gp']		= 'return';
	$_GET['payment_code'] = 'lakala';
	$_GET['out_trade_no'] = strval($res['data']['merOrderId']);
	$_GET['trade_no'] = strval($res['data']['transactionId']);
	//$_GET['sign'] = strval($xmlArr['sign']);
	$_GET['trade_status'] = strval($res['data']['payResult'] == '1' ?'TRADE_SUCCESS':'TRADE_FAIL');
	$_GET['total_fee'] = floatval($res['data']['settleAmount']);
	$_GET['seller_id'] = intval($res['data']['ext2']);
	$_GET['extra_common_param'] = strval($res['data']['ext1']);
	require_once(dirname(__FILE__).'/../../../index.php');
}

?>
