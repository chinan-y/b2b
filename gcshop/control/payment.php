<?php
/**
 * 支付入口
 *
 *
 ***/


defined('GcWebShop') or exit('Access Invalid!');

class paymentControl extends BaseHomeControl{

    public function __construct() {
		//矫正交通银行订单类型
		if(isset($_GET['payment_code']) && isset($_GET['out_trade_no'])){
			if($_GET['payment_code'] == 'bocomm' && !empty($_GET['out_trade_no'])){
				$model_pd = Model('predeposit');
				$condition = array();
				$condition['pdr_sn'] = trim($_GET['out_trade_no']);
				$info = $model_pd->getPdRechargeInfo($condition);
				if ($info){
					$_GET['extra_common_param'] = 'pd_order';
				}
			}
		}
		
        //向前兼容
        $_GET['extra_common_param'] = str_replace(array('predeposit','product_buy'),array('pd_order','real_order'),$_GET['extra_common_param']);
        $_POST['extra_common_param'] = str_replace(array('predeposit','product_buy'),array('pd_order','real_order'),$_POST['extra_common_param']);
    }

	/**
	 * 实物商品订单
	 */
	public function real_orderOp(){
	    $pay_sn = $_POST['pay_sn'] ? $_POST['pay_sn'] : $_GET['pay_sn'];
		$payment_code = $_POST['payment_code'] ? $_POST['payment_code'] : $_GET['payment_code'];
        $url = 'index.php?gct=member_order';

        if(!preg_match('/^\d{18}$/',$pay_sn)){
            showMessage('参数错误','','html','error');
        }

        $logic_payment = Logic('payment');
        $result = $logic_payment->getPaymentInfo($payment_code);
        if(!$result['state']) {
            showMessage($result['msg'], $url, 'html', 'error');
        }
        $payment_info = $result['data'];
		$payment_info['cardNo']=$_POST['cardNo'];
        //计算所需支付金额等支付单信息
        $result = $logic_payment->getRealOrderInfo($pay_sn, $_SESSION['member_id']);
        if(!$result['state']) {
            showMessage($result['msg'], $url, 'html', 'error');
        }

        if ($result['data']['api_pay_state'] || empty($result['data']['api_pay_amount'])) {
            showMessage('该订单不需要支付', $url, 'html', 'error');
        }

        //转到第三方API支付
        $this->_api_pay($result['data'], $payment_info);
	}

	/**
	 * 虚拟商品购买
	 */
	public function vr_orderOp(){
	    $order_sn = $_POST['order_sn'];
	    $payment_code = $_POST['payment_code'];
	    $url = 'index.php?gct=member_vr_order';

	    if(!preg_match('/^\d{18}$/',$order_sn)){
            showMessage('参数错误','','html','error');
        }

        $logic_payment = Logic('payment');
        $result = $logic_payment->getPaymentInfo($payment_code);
        if(!$result['state']) {
            showMessage($result['msg'], $url, 'html', 'error');
        }
        $payment_info = $result['data'];

        //计算所需支付金额等支付单信息
        $result = $logic_payment->getVrOrderInfo($order_sn, $_SESSION['member_id']);
        if(!$result['state']) {
            showMessage($result['msg'], $url, 'html', 'error');
        }

        if ($result['data']['order_state'] != ORDER_STATE_NEW || empty($result['data']['api_pay_amount'])) {
            showMessage('该订单不需要支付', $url, 'html', 'error');
        }

        //转到第三方API支付
        $this->_api_pay($result['data'], $payment_info);
	}

	/**
	 * 预存款充值
	 */
	public function pd_orderOp(){
	    $pdr_sn = $_POST['pdr_sn'];
	    $payment_code = $_POST['payment_code'];
	    $url = 'index.php?gct=predeposit';

	    if(!preg_match('/^\d{18}$/',$pdr_sn)){
	        showMessage('参数错误',$url,'html','error');
	    }

	    $logic_payment = Logic('payment');
	    $result = $logic_payment->getPaymentInfo($payment_code);
	    if(!$result['state']) {
	        showMessage($result['msg'], $url, 'html', 'error');
	    }
	    $payment_info = $result['data'];

        $result = $logic_payment->getPdOrderInfo($pdr_sn,$_SESSION['member_id']);
        if(!$result['state']) {
            showMessage($result['msg'], $url, 'html', 'error');
        }
        if ($result['data']['pdr_payment_state'] || empty($result['data']['api_pay_amount'])) {
            showMessage('该充值单不需要支付', $url, 'html', 'error');
        }

	    //转到第三方API支付
	    $this->_api_pay($result['data'], $payment_info);
	}

	/**
	 * 第三方在线支付接口
	 *
	 */
	private function _api_pay($order_info, $payment_info) {
		
    	$payment_api = new $payment_info['payment_code']($payment_info,$order_info);
    	if($payment_info['payment_code'] == 'chinabank' || $payment_info['payment_code'] == 'bocomm') {
    		$payment_api->submit();
    	} elseif($payment_info['payment_code'] == 'wxpay'){
    		$data = $payment_api->get_payurl();
    		//加密参数
			$crypt_data = array(
				'url'=>$data['url'],
				'pay_sn'=>$data['pay_sn'],
				'buyer_id'=>$data['buyer_id'],
				'price'=>$data['price']
			);
			$seri_data = serialize($crypt_data);
			$logic_buy = Logic('buy');
			$crypt_str = $logic_buy->buyEncrypt($seri_data, $_SESSION['member_id']);
    		//跳到微信支付页面
    		redirect('index.php?gct=buy&gp=wxqrcode&crypt_str='.$crypt_str);
    	}elseif($payment_info['payment_code'] == 'gzbank'){
			$result=$payment_api->submit($payment_info);
			if($result['state']==200){
				$crypt_data = array(
					'pay_sn'=>$order_info['pay_sn'],
					'buyer_id'=>$order_info['buyer_id'],
					'price'=>$order_info['api_pay_amount'],
					'cardNo'=>$payment_info['cardNo']
				);
				$seri_data = serialize($crypt_data);
				$logic_buy = Logic('buy');
				$crypt_str = $logic_buy->buyEncrypt($seri_data, $_SESSION['member_id']);
				redirect('index.php?gct=buy&gp=gzpay&crypt_str='.$crypt_str);
			}else{
				showMessage($result['message'], 'index.php?gct=member_order', 'html', 'success');
			}
		}
    	else {
    		@header("Location: ".$payment_api->get_payurl());
    	}
    	exit();
	}
	
	//支付完成跳转（贵州银行）
	public function gz_payOp(){
		$order = Model('order')->getOrderList(array('order_id'=>$_POST['order_id']));
		//支付成功更改订单状态、获取运单号-推单
		$result = Logic('payment')->updateRealOrder('', 'gzbank', $order, '');
		$payment_api = $this->payment_api();
		$result=$payment_api->pay($_POST);
		if($result['state']==200){
			showMessage($result['message'], 'index.php?gct=member_order', 'html', 'success');
		}elseif($result['state']==400){
			showMessage($result['message'], 'index.php?gct=member_order', 'html', 'error');
		}
	}
	
	//单笔订单查询（贵州银行）
	public function refundOp(){
		$payment_api = $this->payment_api();
		$array=rcache('refundArray','refund');
		foreach($array as $key=>$value){
			$payment_api->STQReq(unserialize($value));
		}
		exit;
	}
	
	//申请绑定银行卡（贵州银行）
	public function bindOp(){
		$payment_api = $this->payment_api();
		$result=$payment_api->GZBind($_POST);
		echo json_encode($result);
	}
	
	//提交申请绑定（贵州银行）
	public function bindTwoOp(){
		$payment_api = $this->payment_api();
		$result=$payment_api->GZBindTwo($_POST);
		echo json_encode($result);
	}
	
	//退款（贵州银行）
	public function exitM(){
		$payment_api = $this->payment_api();
		$result=$payment_api->SRReq($_POST);
		if($_POST['step']=='synchronous'){
			if($result['state']==200){
			showMessage($result['message'], 'index.php?gct=member_order', 'html', 'success');
			}elseif($result['state']==400){
			showMessage($result['message'], 'index.php?gct=member_order', 'html', 'error');
			}
		}elseif($_POST['step']=='asynchronous'){
			exit(json_decode($result));
		}
	}
	
	public function payment_api(){
		require_once BASE_PATH.'/api/payment/gzbank/gzbank.php';
		$payment_api = new gzbank();
		return $payment_api;
	}
	
	/**
	 * 通知处理(支付宝异步通知和网银在线自动对账)
	 *
	 */
	public function notifyOp(){
        switch ($_GET['payment_code']) {
            case 'alipay':
                $success = 'success'; $fail = 'fail'; break;
			case 'bocomm':
				$success = 'ok'; $fail = 'error'; break;
            case 'chinabank':
                $success = 'ok'; $fail = 'error'; break;
            default: 
                exit();
        }

        $order_type = $_POST['extra_common_param'];
        $out_trade_no = $_POST['out_trade_no'];
        $trade_no = $_POST['trade_no'];

		//参数判断
		if(!preg_match('/^\d{18}$/',$out_trade_no)) exit($fail);

		$model_pd = Model('predeposit');
		$logic_payment = Logic('payment');

		if ($order_type == 'real_order') {

		    $result = $logic_payment->getRealOrderInfo($out_trade_no);
		    if (intval($result['data']['api_pay_state'])) {
		        exit($success);
		    }
		    $order_list = $result['data']['order_list'];

	    } elseif ($order_type == 'vr_order'){

	        $result = $logic_payment->getVrOrderInfo($out_trade_no);
	        if ($result['data']['order_state'] != ORDER_STATE_NEW) {
	            exit($success);
	        }

		} elseif ($order_type == 'pd_order') {

		    $result = $logic_payment->getPdOrderInfo($out_trade_no);
		    if ($result['data']['pdr_payment_state'] == 1) {
		        exit($success);
		    }

		} else {
		    exit();
		}
		$order_pay_info = $result['data'];

		//取得支付方式
		$result = $logic_payment->getPaymentInfo($_GET['payment_code']);
		if (!$result['state']) {
		    exit($fail);
		}
		$payment_info = $result['data'];

		//创建支付接口对象
		$payment_api	= new $payment_info['payment_code']($payment_info,$order_pay_info);

		//对进入的参数进行远程数据判断
		$verify = $payment_api->notify_verify();
		if (!$verify) {
		    exit($fail);
		}

        //购买商品
		if ($order_type == 'real_order') {
            $result = $logic_payment->updateRealOrder($out_trade_no, $payment_info['payment_code'], $order_list, $trade_no);
		} elseif($order_type == 'vr_order'){
		    $result = $logic_payment->updateVrOrder($out_trade_no, $payment_info['payment_code'], $order_pay_info, $trade_no);
		} elseif ($order_type == 'pd_order') {
		    $result = $logic_payment->updatePdOrder($out_trade_no,$trade_no,$payment_info,$order_pay_info);
		}

		exit($result['state'] ? $success : $fail);
	}

	/**
	 * 支付接口返回
	 *
	 */
	public function returnOp(){
	    $order_type = $_GET['extra_common_param'];
		if ($order_type == 'real_order') {
		    $gct = 'member_order';
		} elseif($order_type == 'vr_order') {
			$gct = 'member_vr_order';
		} elseif($order_type == 'pd_order') {
		    $gct = 'predeposit';
		} else {
		    exit();
		}

		$out_trade_no = $_GET['out_trade_no'];
		$trade_no = $_GET['trade_no'];
		$url = SHOP_SITE_URL.'/index.php?gct='.$gct;

		//对外部交易编号进行非空判断
		if(!preg_match('/^\d{18}$/',$out_trade_no)) {
		    showMessage('参数错误',$url,'','html','error');
		}

		$logic_payment = Logic('payment');

		if ($order_type == 'real_order') {

		    $result = $logic_payment->getRealOrderInfo($out_trade_no);
		    if(!$result['state']) {
		        showMessage($result['msg'], $url, 'html', 'error');
		    }
		    if ($result['data']['api_pay_state']) {
		        $payment_state = 'success';
		    }
		    $order_list = $result['data']['order_list'];

	    }elseif ($order_type == 'vr_order') {

	        $result = $logic_payment->getVrOrderInfo($out_trade_no);
	        if(!$result['state']) {
	            showMessage($result['msg'], $url, 'html', 'error');
	        }
	        if ($result['data']['order_state'] != ORDER_STATE_NEW) {
	            $payment_state = 'success';
	        }

		} elseif ($order_type == 'pd_order') {

		    $result = $logic_payment->getPdOrderInfo($out_trade_no);
		    if(!$result['state']) {
		        showMessage($result['msg'], $url, 'html', 'error');
		    }
		    if ($result['data']['pdr_payment_state'] == 1) {
		        $payment_state = 'success';
		    }
		}

		$order_pay_info = $result['data'];
		$api_pay_amount = $result['data']['api_pay_amount'];

		if ($payment_state != 'success') {
		    //取得支付方式
		    $result = $logic_payment->getPaymentInfo($_GET['payment_code']);
		    if (!$result['state']) {
		        showMessage($result['msg'],$url,'html','error');
		    }
		    $payment_info = $result['data'];

		    //创建支付接口对象
		    $payment_api	= new $payment_info['payment_code']($payment_info,$order_pay_info);
		    if($payment_info['payment_code'] !='wxpay'){
			    //返回参数判断
			    $verify = $payment_api->return_verify();
			    //if(!$verify) {
			    if(0) {
			        showMessage('支付数据验证失败',$url,'html','error');
			    }
			}
		    //取得支付结果
		    $pay_result	= $payment_api->getPayResult($_GET);
		    if (!$pay_result) {
		        showMessage('非常抱歉，您的订单支付没有成功，请您后尝试',$url,'html','error');
		    }
			
            //更改订单支付状态
		    if ($order_type == 'real_order') {
		        $result = $logic_payment->updateRealOrder($out_trade_no, $payment_info['payment_code'], $order_list, $trade_no);
		    } else if($order_type == 'vr_order') {
		        $result = $logic_payment->updateVrOrder($out_trade_no, $payment_info['payment_code'], $order_pay_info, $trade_no);
		    } else if ($order_type == 'pd_order') {
		        $result = $logic_payment->updatePdOrder($out_trade_no, $trade_no, $payment_info, $order_pay_info);
		    }
		    if (!$result['state']) {
		        showMessage('支付状态更新失败',$url,'html','error');
		    }
		}
	
		

		//支付成功后跳转
		if ($order_type == 'real_order') {
		    $pay_ok_url = SHOP_SITE_URL.'/index.php?gct=buy&gp=pay_ok&pay_sn='.$out_trade_no.'&pay_amount='.ncPriceFormat($api_pay_amount);
		} elseif ($order_type == 'vr_order') {
		    $pay_ok_url = SHOP_SITE_URL.'/index.php?gct=buy_virtual&gp=pay_ok&order_sn='.$out_trade_no.'&order_id='.$order_pay_info['order_id'].'&order_amount='.ncPriceFormat($api_pay_amount);
		} elseif ($order_type == 'pd_order') {
		    $pay_ok_url = SHOP_SITE_URL.'/index.php?gct=predeposit';
		}
        if ($payment_info['payment_code'] == 'tenpay') {
            showMessage('',$pay_ok_url,'tenpay');
        } else {
			//三方平台订单回跳
			if($order_list[0]['partner_id'] > 0){
				require_once(BASE_CORE_PATH.DS.'framework/function/gcclient.php');

				$APPID = $order_list[0]['partner_id'];
				$out_auth_type = 'mobile';
				if($_SESSION['member_id']){
					$out_auth_value = $_SESSION['member_mobile'];
					$out_member_id = $_SESSION['member_id'];
				}else{
					$out_member_id = $order_list[0]['buyer_id'];
					$model_member = Model('member');
					$buyer_info = $model_member->getMemberInfoByID($out_member_id);
					$out_auth_value = $buyer_info['member_mobile'];
				}
				$tempSNs = array();
				foreach($order_list as $order_info){
					$tempSNs[] = $order_info['order_sn'];
				}
				$tempSNs = join(';', $tempSNs);
				$out_trade_state = 'WAIT_SELLER_SEND_GOODS';
				$out_pay_no = $out_trade_no;
				$trade_no = $order_list[0]['out_trade_no'];
	
				$parameter = array(
					'out_auth_type'=>$out_auth_type,
					'out_auth_value'=>$out_auth_value,
					'out_member_id'=>$out_member_id,
					'out_trade_no'=>$tempSNs,
					'out_trade_state'=>$out_trade_state,
					'out_pay_no'=>$out_pay_no,
					'trade_no'=>$trade_no,
				);
				
				//验证参数
				$model_partner = Model('partner');
				$partner_info = $model_partner->getPartnerInfo(array('APPID'=>$APPID));

				//计算参数加密结果
				$client = new gcclient();
				$client->parameter['APPKEY'] = $partner_info['APPKEY'];
				$client->parameter['sign_type'] = 'MD5';
				$sign_string = $client->sign($parameter);
				
				$parameter_string = sprintf("out_auth_type=%s&out_auth_value=%s&out_member_id=%s&out_trade_no=%s&out_trade_state=%s&out_pay_no=%s&trade_no=%s&sign=%s", $out_auth_type, $out_auth_value, $out_member_id, $tempSNs, $out_trade_state, $out_pay_no, $trade_no, $sign_string);
				if(strpos('?', $partner_info['notify_url'] > -1)){
					$linkTag = '&';
				}else{
					$linkTag = '?';
				}
				$pay_ok_url = $partner_info['notify_url'].$linkTag.$parameter_string;
				$parameter_queue = array_merge($parameter, array('APPID'=>$APPID, 'sign'=>$sign_string, 'url'=>$partner_info['notify_url_1']));
				//加入回调队列
				$model_dely = Model('dely');
				$model_dely->addDely('payCallback',$parameter_queue);
			}
            redirect($pay_ok_url);
        }
	}
}