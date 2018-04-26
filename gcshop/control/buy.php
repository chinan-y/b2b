<?php
/**
 * 购买流程
 ***/


defined('GcWebShop') or exit('Access Invalid!');
class buyControl extends BaseBuyControl {

    public function __construct() {
        parent::__construct();
        Language::read('home_cart_index');
        if (!$_SESSION['member_id']){
            redirect('index.php?gct=login&ref_url='.urlencode('index.php?gct=cart'));
        }
        //验证该会员是否禁止购买
        if(!$_SESSION['is_buy']){
            showMessage(Language::get('cart_buy_noallow'),'','html','error');
        }
        Tpl::output('hidden_rtoolbar_cart', 1);

		require_once BASE_PATH.'/config/returnLang.php';
		Tpl::output('lang', $lang);
    }

    /**
     * 实物商品 购物车、直接购买第一步:选择收获地址和配送方式
     */
    public function buy_step1Op() {
        //虚拟商品购买分流
        $this->_buy_branch($_POST);

        //得到购买数据
        $logic_buy = Logic('buy');
        $result = $logic_buy->buyStep1($_POST['cart_id'], $_POST['ifcart'], $_SESSION['member_id'], $_SESSION['store_id'], 1);
        if(!$result['state']) {
            showMessage($result['msg'], '', 'html', 'error');
        } else {
            $result = $result['data'];
        }

        //商品金额计算(分别对每个商品/优惠套装小计、每个店铺小计)
        Tpl::output('store_cart_list', $result['store_cart_list']);
        Tpl::output('store_goods_total', $result['store_goods_total']);
		Tpl::output('store_taxes_total', $result['store_taxes_total']);

        //取得店铺优惠 - 满即送(赠品列表，店铺满送规则列表)
        Tpl::output('store_premiums_list', $result['store_premiums_list']);
        Tpl::output('store_mansong_rule_list', $result['store_mansong_rule_list']);

        //返回店铺可用的代金券
        Tpl::output('store_voucher_list', $result['store_voucher_list']);

        //返回需要计算运费的店铺ID数组 和 不需要计算运费(满免运费活动的)店铺ID及描述
        Tpl::output('need_calc_sid_list', $result['need_calc_sid_list']);
        Tpl::output('cancel_calc_sid_list', $result['cancel_calc_sid_list']);

        //将商品ID、数量、运费模板、运费序列化，加密，输出到模板，选择地区AJAX计算运费时作为参数使用
        Tpl::output('freight_hash', $result['freight_list']);

        //输出用户默认收货地址
        Tpl::output('address_info', $result['address_info']);

        //输出有货到付款时，在线支付和货到付款及每种支付下商品数量和详细列表
        Tpl::output('pay_goods_list', $result['pay_goods_list']);
        Tpl::output('ifshow_offpay', $result['ifshow_offpay']);
        Tpl::output('deny_edit_payment', $result['deny_edit_payment']);

        //不提供增值税发票时抛出true(模板使用)
        Tpl::output('vat_deny', $result['vat_deny']);

        //增值税发票哈希值(php验证使用)
        Tpl::output('vat_hash', $result['vat_hash']);

        //输出默认使用的发票信息
        Tpl::output('inv_info', $result['inv_info']);

        //显示预存款、支付密码、充值卡
        Tpl::output('available_pd_amount', $result['available_predeposit']);
        Tpl::output('member_paypwd', $result['member_paypwd']);
        Tpl::output('available_rcb_amount', $result['available_rc_balance']);
		
		//显示用户积分，用于订单抵扣
		foreach($result['member_points'] as $store_id=>$val){
			$member_points = $val;
			Tpl::output('points_store_id', $store_id);
		}
        Tpl::output('member_points', $member_points);
        
        //删除购物车无效商品
        $logic_buy->delCart($_POST['ifcart'], $_SESSION['member_id'], $_POST['invalid_cart']);

        //标识购买流程执行步骤
        Tpl::output('buy_step','step2');

        Tpl::output('ifcart', $_POST['ifcart']);

        //店铺信息
        $store_list = Model('store')->getStoreMemberIDList(array_keys($result['store_cart_list']));
        Tpl::output('store_list',$store_list);
        Tpl::output('store_id',$_POST['store_id']);

		//判断是否是三方订单
		if(isset($_POST['buy_encrypt']) && !empty($_POST['buy_encrypt'])){
			Tpl::output('buy_encrypt',$_POST['buy_encrypt']);
		}
		
        Tpl::showpage('buy_step1');
    }

    /**
     * 生成订单
     *
     */
    public function buy_step2Op() {
		
        $logic_buy = logic('buy');
		
		//判断是否是三方订单
		$buy_encrypt = array();
		if(isset($_POST['buy_encrypt']) && !empty($_POST['buy_encrypt'])){
			$buy_encrypt = $logic_buy->buyDecrypt($_POST['buy_encrypt'], $_SESSION['member_id']);
		}

        $result = $logic_buy->buyStep2($_POST, $_SESSION['member_id'], $_SESSION['member_name'], $_SESSION['member_email'], $buy_encrypt);
        if(!$result['state']) {
            showMessage($result['msg'], 'index.php?gct=cart', 'html', 'error');
        }

        //转向到商城支付页面
		$pay_sn='';
		if(is_array($result['data']['pay_sn'])){
			foreach($result['data']['pay_sn'] as $value){
				$pay_sn.=$value.",";
			}
		}else{
			$pay_sn=$result['data']['pay_sn'];
		}
		
        redirect('index.php?gct=buy&gp=pay&store_id='.$_POST['store_id'].'&pay_sn='.$pay_sn);
    }

    /**
     * 下单时支付页面
     */
    public function payOp() {
		if(strlen($_GET['pay_sn'])>18){
			$pay_sn=explode(",",substr($_GET['pay_sn'],0,-1));
			
		}else{
			$pay_sn	= $_GET['pay_sn'].",";
			$pay_sn=explode(",",substr($pay_sn,0,-1));
		}
		
		$order=array();
		
		foreach($pay_sn as $k=>$pay_sn){
			$pay_sn=trim($pay_sn);
        if (!preg_match('/^\d{18}$/',$pay_sn)){
            showMessage(Language::get('cart_order_pay_not_exists'),'index.php?gct=member_order','html','error');
        }

        //查询支付单信息
        $model_order= Model('order');
        $pay_info = $model_order->getOrderPayInfo(array('pay_sn'=>$pay_sn,'buyer_id'=>$_SESSION['member_id']),true);
		
        if(empty($pay_info)){
            showMessage(Language::get('cart_order_pay_not_exists'),'index.php?gct=member_order','html','error');
        }
        Tpl::output('pay_info',explode(",",substr( $_GET['pay_sn'],0,-1)));
        
        //取子订单列表
		
        $condition = array();
        $condition['pay_sn'] = $pay_sn;
        $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
        $order[$k]= $model_order->getOrderList($condition,'','order_id,order_state,payment_code,order_amount,order_tax,rcb_amount,pd_amount,order_sn','','',array(),true);
        if (empty($order[$k])) {
            showMessage('未找到需要支付的订单','index.php?gct=member_order','html','error');
        }

        //重新计算在线支付金额
        $pay_amount_online = 0;
        $pay_amount_offline = 0;
        //订单总支付金额(不包含货到付款)
        $pay_amount = 0;

        foreach ($order[$k] as $key => $order_info) {
            $order[$k][$key]['pay_sn']=$pay_sn;
            $payed_amount = floatval($order_info['rcb_amount'])+floatval($order_info['pd_amount']);
            //计算相关支付金额
            if ($order_info['payment_code'] != 'offline') {
                if ($order_info['order_state'] == ORDER_STATE_NEW) {
                    $pay_amount_online += ncPriceFormat(floatval($order_info['order_amount'])-$payed_amount);
                }
                $pay_amount += floatval($order_info['order_amount']);
            } else {
                $pay_amount_offline += floatval($order_info['order_amount']);
            }

            //显示支付方式与支付结果
            if ($order_info['payment_code'] == 'offline') {
                $order[$k][$key]['payment_state'] = '货到付款';
				
			//站内余额支付使用通联支付报关推单，更改状态order_state为30
            }else if($order_info['payment_code'] == 'tonglian'){
				$model_order->editOrder(array('order_state'=>ORDER_STATE_SEND),array('order_id'=>$order_info['order_id'],'order_state'=>ORDER_STATE_PAY));
				
			}else {
                $order[$k][$key]['payment_state'] = '在线支付';
                if ($payed_amount > 0) {
                    $payed_tips = '';
                    if (floatval($order_info['rcb_amount']) > 0) {
                        $payed_tips = '充值卡已支付：￥'.$order_info['rcb_amount'];
                    }
                    if (floatval($order_info['pd_amount']) > 0) {
                        $payed_tips .= ' 预存款已支付：￥'.$order_info['pd_amount'];
                    }
                   $order[$k][$key]['order_amount'] .= " ( {$payed_tips} )";
				  
                }
            }
			 $order[$k][$key]['pay_amount_online']=$pay_amount_online;
        }
		
		}
		// header("Content-type: text/html; charset=utf-8");echo '<pre>';print_r($order);exit;
        Tpl::output('order_list',$order);

        //如果线上线下支付金额都为0，转到支付成功页
        if (empty($pay_amount_online) && empty($pay_amount_offline)) {
            redirect('index.php?gct=buy&gp=pay_ok&pay_sn='.$pay_sn.'&pay_amount='.ncPriceFormat($pay_amount));
        }

        //输出订单描述
        if (empty($pay_amount_online)) {
            $order_remind = '下单成功，我们会尽快为您发货，请保持电话畅通！';
        } elseif (empty($pay_amount_offline)) {
            $order_remind = '请您及时付款，以便订单尽快处理！';
        } else {
            $order_remind = '部分商品需要在线支付，请尽快付款！';
        }
        Tpl::output('order_remind',$order_remind);
        Tpl::output('pay_amount_online',ncPriceFormat($pay_amount_online));
        Tpl::output('pd_amount',ncPriceFormat($pd_amount));

        //显示支付接口列表
        if ($pay_amount_online > 0) {
            $model_payment = Model('payment');
            $condition = array();
            $payment_list = $model_payment->getPaymentOpenList($condition);
            if (!empty($payment_list)) {
                unset($payment_list['predeposit']);
                unset($payment_list['offline']);
            }
            if (empty($payment_list)) {
                showMessage('暂未找到合适的支付方式','index.php?gct=member_order','html','error');
            }
            Tpl::output('payment_list',$payment_list);
        }
		//贵州支付绑定标识
		$bind=Model()->table('GzBankInfo')->where(array('memberId'=>$_SESSION['member_id'],'success'=>1))->select();
		
		if($bind){
			foreach($bind as $key=>$value){
				switch($value['cardBankId']){
					case 1569 :
					$bind[$key]['cardBankId']='贵州银行';break;
					case 100 :
					$bind[$key]['cardBankId']='邮政储蓄';break;
					case 102 :
					$bind[$key]['cardBankId']='工商银行';break;
					case 103 :
					$bind[$key]['cardBankId']='农业银行';break;
					case 104 :
					$bind[$key]['cardBankId']='中国银行';break;
					case 105 :
					$bind[$key]['cardBankId']='建设银行';break;
					case 201 :
					$bind[$key]['cardBankId']='国家开发银行';break;
					case 301 :
					$bind[$key]['cardBankId']='交通银行';break;
					case 302 :
					$bind[$key]['cardBankId']='中信银行';break;
					case 303 :
					$bind[$key]['cardBankId']='光大银行';break;
					case 304 :
					$bind[$key]['cardBankId']='华夏银行';break;
					case 305 :
					$bind[$key]['cardBankId']='民生银行';break;
					case 306 :
					$bind[$key]['cardBankId']='广发银行';break;
					case 307 :
					$bind[$key]['cardBankId']='平安银行';break;
					case 308 :
					$bind[$key]['cardBankId']='招商银行';break;
					case 309 :
					$bind[$key]['cardBankId']='兴业银行';break;
					case 310 :
					$bind[$key]['cardBankId']='浦发银行';break;
					case 311 :
					$bind[$key]['cardBankId']='恒丰银行';break;
					case 313 :
					$bind[$key]['cardBankId']='华融湘江银行';break;
					
				}
			}
			Tpl::output('bind',$bind);
		}
        //标识 购买流程执行第几步
        Tpl::output('buy_step','step3');
        Tpl::showpage('buy_step2');
    }

    /**
     * 预存款充值下单时支付页面
     */
    public function pd_payOp() {
        $pay_sn	= $_GET['pay_sn'];
        if (!preg_match('/^\d{18}$/',$pay_sn)){
            showMessage(Language::get('para_error'),'index.php?gct=predeposit','html','error');
        }

        //查询支付单信息
        $model_order= Model('predeposit');
        $pd_info = $model_order->getPdRechargeInfo(array('pdr_sn'=>$pay_sn,'pdr_member_id'=>$_SESSION['member_id']));
        if(empty($pd_info)){
            showMessage(Language::get('para_error'),'','html','error');
        }
        if (intval($pd_info['pdr_payment_state'])) {
            showMessage('您的订单已经支付，请勿重复支付','index.php?gct=predeposit','html','error');
        }
        Tpl::output('pdr_info',$pd_info);

        //显示支付接口列表
		$model_payment = Model('payment');
        $condition = array();
        $condition['payment_code'] = array('not in',array('offline','predeposit'));
        $condition['payment_state'] = 1;
        $payment_list = $model_payment->getPaymentList($condition);
        if (empty($payment_list)) {
            showMessage('暂未找到合适的支付方式','index.php?gct=predeposit&gp=index','html','error');
        }
        Tpl::output('payment_list',$payment_list);

        //标识 购买流程执行第几步
        Tpl::output('buy_step','step3');
        Tpl::showpage('predeposit_pay');
    }

	/**
	 * 支付成功页面
	 */
	public function pay_okOp() {
	    $pay_sn	= $_GET['pay_sn'];
	    if (!preg_match('/^\d{18}$/',$pay_sn)){
	        showMessage(Language::get('cart_order_pay_not_exists'),'index.php?gct=member_order','html','error');
	    }

	    //查询支付单信息
	    $model_order= Model('order');
	    $pay_info = $model_order->getOrderPayInfo(array('pay_sn'=>$pay_sn,'buyer_id'=>$_SESSION['member_id']));
	    if(empty($pay_info)){
	        showMessage(Language::get('cart_order_pay_not_exists'),'index.php?gct=member_order','html','error');
	    }
		$order=$model_order->getOrderList(array('buyer_id'=>$_SESSION['member_id'],'order_state'=>'10'),'pay_sn');
		if(!empty($order)){
		$pay_sn='';
		foreach($order as $value){
			$pay_sn.=$value['pay_sn'].",";
		}
         
		$return_url="index.php?gct=buy&gp=pay&pay_sn=".$pay_sn;
		}else{
		  $return_url="index.php?gct=member_order";	
		}
	    Tpl::output('pay_info',$pay_info);
	    Tpl::output('return_url',$return_url);

	    Tpl::output('buy_step','step4');
	    Tpl::showpage('buy_step3');
	}
	public function gzpayOp(){
		$logic_buy = logic('buy');
        $buy_encrypt = array();
        if(isset($_GET['crypt_str']) && !empty($_GET['crypt_str'])){
            $data = $logic_buy->buyDecrypt($_GET['crypt_str'], $_SESSION['member_id']);
            
        }
		$model_order= Model('order');
        $condition = array();
        $condition['pay_sn'] = strval($data['pay_sn']);
        $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
        $order_list = $model_order->getOrderList($condition,'','order_id,payment_code,order_state,order_amount,order_sn','','',array(),true);
		// header("Content-type: text/html; charset=utf-8");echo '<pre>';print_r($order_list);exit;
	   if (empty($order_list)) {
            showMessage('未找到需要支付的订单','index.php?gct=member_order','html','error');
        }
		
		Tpl::output('data',$data);
		Tpl::output('order_list',$order_list);
		Tpl::showpage('buy_step2gz');
	}
     /**
     * [微信扫码页面]
     * @author fulijun
     * @dateTime 2016-07-23T17:46:50+0800
     * @return   [type]                   [description]
     */
    public function wxqrcodeOp(){  

        //解密参数
        $logic_buy = logic('buy');
        $buy_encrypt = array();
        if(isset($_GET['crypt_str']) && !empty($_GET['crypt_str'])){
            $data = $logic_buy->buyDecrypt($_GET['crypt_str'], $_SESSION['member_id']);
            $url = strval($data['url']);
            $pay_sn = strval($data['pay_sn']);
            $buyer_id=strval($data['buyer_id']);
            $price  = floatval($data['price']);
        }
  
        //取得订单信息,显示在微信二维码支付页面
        $model_order= Model('order');
        $condition = array();
        $condition['pay_sn'] = $pay_sn;
        $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
        $order_list = $model_order->getOrderList($condition,'','order_id,payment_code,order_state,order_amount,order_sn','','',array(),true);
        if (empty($order_list)) {
            showMessage('未找到需要支付的订单','index.php?gct=member_order','html','error');
        }
        //赋值   
        Tpl::output('url',$url);
        Tpl::output('pay_sn',$pay_sn);
        Tpl::output('order_sn',$order_sn);
        Tpl::output('buyer_id',$buyer_id);
        Tpl::output('price',$price);
        Tpl::output('order_list',$order_list);
        Tpl::showpage('buy_step2wx');
    }
	
     /**
     * [AJAX检测是否付款]
     * @author fulijun
     * @dateTime 2016-07-25T16:07:19+0800
     * @return   [type]                   [description]
     */
    public function check_payOp(){
        $model_order = Model('order');
        $condition = array();
        if ($_POST['pay_sn'] != '') {
            $condition['pay_sn'] = $_POST['pay_sn'];
        }
        if ($_POST['buyer_id'] != '') {
            $condition['buyer_id'] = $_POST['buyer_id'];
        }
        $order_info = $model_order->getOrderInfo($condition);
        echo $order_info['order_state'] != 10 ?'1':'0';
        exit;

    }

	/**
	 * 加载买家收货地址
	 *
	 */
	public function load_addrOp() {
	    $model_addr = Model('address');
	    //如果传入ID，先删除再查询
	    if (!empty($_GET['id']) && intval($_GET['id']) > 0) {
            $model_addr->delAddress(array('address_id'=>intval($_GET['id']),'member_id'=>$_SESSION['member_id']));
	    }
	    $condition = array();
	    $condition['member_id'] = $_SESSION['member_id'];
	    if (!C('delivery_isuse')) {
	        $condition['dlyp_id'] = 0;
	        $order = 'dlyp_id asc,address_id desc'; 
	    }
	    $list = $model_addr->getAddressList($condition,$order);
	    Tpl::output('address_list',$list);
	    Tpl::showpage('buy_address.load','null_layout');
	}

    /**
     * 选择不同地区时，异步处理并返回每个店铺总运费以及本地区是否能使用货到付款
     * 如果店铺统一设置了满免运费规则，则运费模板无效
     * 如果店铺未设置满免规则，且使用运费模板，按运费模板计算，如果其中有商品使用相同的运费模板，则两种商品数量相加后再应用该运费模板计算（即作为一种商品算运费）
     * 如果未找到运费模板，按免运费处理
     * 如果没有使用运费模板，商品运费按快递价格计算，运费不随购买数量增加
     */
    public function change_addrOp() {
        $logic_buy = Logic('buy');
        $data = $logic_buy->changeAddr($_POST['freight_hash'], $_POST['city_id'], $_POST['area_id'], $_SESSION['member_id']);
        if(!empty($data)) {
            exit(json_encode($data));
        } else {
            exit();
        }
    }
	
	/**
     * 用户身份信息
     */
    public function identity_infoOp() {
        $member = Model('member');
		$member_id = $_SESSION['member_id'];
		$member_info = $member->getMemberInfo(array('member_id'=> $member_id), 'member_truename,member_code');
		
		exit(json_encode($member_info));
    }
	
	/**
      * 当前用户当天已经付款的订单金额
      */
	public function person_date_buy_amountOp(){
		$time =date('Y-m-d',time());
		
        $condition = array();
        $condition['buyer_reciver_idnum'] = $_POST['true_idnum'];
        $condition['order_state'] = '20';
        $order_info = Model()->table('order')->where($condition)->order('order_id desc')->select();
		
		if($order_info){
			foreach($order_info as $val){
				$payment_time = date('Y-m-d',$val['payment_time']);
				if($payment_time == $time){
					$all_price += $val['order_amount'];
				}
			}
		}
		$data = array(
			'is_toiletry' => false,
			'date_amount' => $all_price ? $all_price : 0,
		);
		
		exit(json_encode($data));
	}
	

     /**
      * 添加新的收货地址
      *
      */
     public function add_addrOp(){
        $model_addr = Model('address');
     	if (chksubmit()){
     		//验证表单信息
     		$obj_validate = new Validate();
     		$obj_validate->validateparam = array(
     			array("input"=>$_POST["true_name"],"require"=>"true","message"=>Language::get('cart_step1_input_receiver')),
     			array("input"=>$_POST["area_id"],"require"=>"true","validator"=>"Number","message"=>Language::get('cart_step1_choose_area')),
     			array("input"=>$_POST["address"],"require"=>"true","message"=>Language::get('cart_step1_input_address'))
     		);
     		$error = $obj_validate->validate();
			if ($error != ''){
				$error = strtoupper(CHARSET) == 'GBK' ? Language::getUTF8($error) : $error;
				exit(json_encode(array('state'=>false,'msg'=>$error)));
			}
			$data = array();
			
			$_POST['true_name'] = str_replace(' ','',$_POST['true_name']);
			$_POST['true_name'] = trim($_POST['true_name']);
			
			$data['member_id'] = $_SESSION['member_id'];
			$data['true_name'] = $_POST['true_name'];
			$data['area_id'] = intval($_POST['area_id']);
			$data['city_id'] = intval($_POST['city_id']);
			$data['area_info'] = $_POST['area_info'];
			$data['address'] = $_POST['address'];
			$data['tel_phone'] = $_POST['tel_phone'];
			$data['mob_phone'] = $_POST['mob_phone'];
	     	//转码
            $data = strtoupper(CHARSET) == 'GBK' ? Language::getGBK($data) : $data;
			$insert_id = $model_addr->addAddress($data);
			if ($insert_id){
				exit(json_encode(array('state'=>true,'addr_id'=>$insert_id)));
			}else {
				exit(json_encode(array('state'=>false,'msg'=>Language::get('cart_step1_addaddress_fail','UTF-8'))));
			}
     	} else {
     		Tpl::showpage('buy_address.add','null_layout');
     	}
     }

	/**
	 * 加载买家发票列表，最多显示10条
	 *
	 */
	public function load_invOp() {
        $logic_buy = Logic('buy');

	    $condition = array();
	    if ($logic_buy->buyDecrypt($_GET['vat_hash'], $_SESSION['member_id']) == 'allow_vat') {
	    } else {
	        Tpl::output('vat_deny',true);
	        $condition['inv_state'] = 1;
	    }
	    $condition['member_id'] = $_SESSION['member_id'];

	    $model_inv = Model('invoice');
	    //如果传入ID，先删除再查询
	    if (intval($_GET['del_id']) > 0) {
            $model_inv->delInv(array('inv_id'=>intval($_GET['del_id']),'member_id'=>$_SESSION['member_id']));
	    }
	    $list = $model_inv->getInvList($condition,10);
	    if (!empty($list)) {
	        foreach ($list as $key => $value) {
	           if ($value['inv_state'] == 1) {
	               $list[$key]['content'] = '普通发票'.' '.$value['inv_title'].' '.$value['inv_content'];
	           } else {
	               $list[$key]['content'] = '增值税发票'.' '.$value['inv_company'].' '.$value['inv_code'].' '.$value['inv_reg_addr'];
	           }
	        }
	    }
	    Tpl::output('inv_list',$list);
	    Tpl::showpage('buy_invoice.load','null_layout');
	}

     /**
      * 新增发票信息
      *
      */
     public function add_invOp(){
        $model_inv = Model('invoice');
     	if (chksubmit()){
     		//如果是增值税发票验证表单信息
     		if ($_POST['invoice_type'] == 2) {
     		    if (empty($_POST['inv_company']) || empty($_POST['inv_code']) || empty($_POST['inv_reg_addr'])) {
     		        exit(json_encode(array('state'=>false,'msg'=>Language::get('nc_common_save_fail','UTF-8'))));
     		    }
     		}
			$data = array();
            if ($_POST['invoice_type'] == 1) {
                $data['inv_state'] = 1;
                $data['inv_title'] = $_POST['inv_title_select'] == 'person' ? '个人' : $_POST['inv_title'];
                $data['inv_content'] = $_POST['inv_content'];
            } else {
                $data['inv_state'] = 2;
    			$data['inv_company'] = $_POST['inv_company'];
    			$data['inv_code'] = $_POST['inv_code'];
    			$data['inv_reg_addr'] = $_POST['inv_reg_addr'];
    			$data['inv_reg_phone'] = $_POST['inv_reg_phone'];
    			$data['inv_reg_bname'] = $_POST['inv_reg_bname'];
    			$data['inv_reg_baccount'] = $_POST['inv_reg_baccount'];
    			$data['inv_rec_name'] = $_POST['inv_rec_name'];
    			$data['inv_rec_mobphone'] = $_POST['inv_rec_mobphone'];
    			$data['inv_rec_province'] = $_POST['area_info'];
    			$data['inv_goto_addr'] = $_POST['inv_goto_addr'];
            }
            $data['member_id'] = $_SESSION['member_id'];
	     	//转码
            $data = strtoupper(CHARSET) == 'GBK' ? Language::getGBK($data) : $data;
			$insert_id = $model_inv->addInv($data);
			if ($insert_id) {
				exit(json_encode(array('state'=>'success','id'=>$insert_id)));
			} else {
				exit(json_encode(array('state'=>'fail','msg'=>Language::get('nc_common_save_fail','UTF-8'))));
			}
     	} else {
     		Tpl::showpage('buy_address.add','null_layout');
     	}
     }

    /**
     * AJAX验证支付密码
     */
    public function check_pd_pwdOp(){
        if (empty($_GET['password'])) exit('0');
        $buyer_info	= Model('member')->getMemberInfoByID($_SESSION['member_id'],'member_paypwd');
        echo ($buyer_info['member_paypwd'] != '' && $buyer_info['member_paypwd'] === md5($_GET['password'])) ? '1' : '0';
    }

    /**
     * F码验证
     */
    public function check_fcodeOp() {
        $result = logic('buy')->checkFcode($_GET['goods_commonid'], $_GET['fcode']);
        echo $result['state'] ? '1' : '0';
        exit;
    }

    /**
     * 得到所购买的id和数量
     *
     */
    private function _parseItems($cart_id) {
        //存放所购商品ID和数量组成的键值对
        $buy_items = array();
        if (is_array($cart_id)) {
            foreach ($cart_id as $value) {
                if (preg_match_all('/^(\d{1,10})\|(\d{1,6})$/', $value, $match)) {
                    $buy_items[$match[1][0]] = $match[2][0];
                }
            }
        }
        return $buy_items;
    }

    /**
     * 购买分流
     */
    private function _buy_branch($post) {
        if (!$post['ifcart']) {
            //取得购买商品信息
            $buy_items = $this->_parseItems($post['cart_id']);
            $goods_id = key($buy_items);
            $quantity = current($buy_items);

            $goods_info = Model('goods')->getGoodsOnlineInfoAndPromotionById($goods_id);
            if ($goods_info['is_virtual']) {
                redirect('index.php?gct=buy_virtual&gp=buy_step1&goods_id='.$goods_id.'&quantity='.$quantity);
            }
        }
    }

}
