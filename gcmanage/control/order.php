<?php
/**
 * 交易管理
 *
 *
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');
class orderControl extends SystemControl{
    /**
     * 每次导出订单数量
     * @var int
     */
	const EXPORT_SIZE = 1000;

	public function __construct(){
		parent::__construct();
		Language::read('trade');
		$this->_logic_tax_1 = Logic('tax');
	}

	public function indexOp(){
	    $model_order = Model('order');
        $condition	= array();
        if($_GET['order_sn']) {
        	$condition['order_sn'] = $_GET['order_sn'];
        }
        if($_GET['store_name']) {
            $condition['store_name'] = $_GET['store_name'];
        }
        if(in_array($_GET['order_state'],array('0','10','20','30','40'))){
        	$condition['order_state'] = $_GET['order_state'];
        }
        if($_GET['payment_code']) {
            $condition['payment_code'] = $_GET['payment_code'];
        }
        if($_GET['buyer_name']) {
            $condition['buyer_name'] = $_GET['buyer_name'];
        }
		if($_GET['buyer_id']) {
            $condition['buyer_id'] = $_GET['buyer_id'];
        }
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_time']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_time']);
        $start_unixtime = $if_start_time ? strtotime($_GET['query_start_time']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['query_end_time']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        $order_list	= $model_order->getOrderList($condition,30);

        foreach ($order_list as $order_id => $order_info) {
			$orders = DB::getAll("select list_status, MAKE_CSV, OIF_MEMO from 33hao_mess_order_info where ORDER_ID = '".$order_info['order_id']."'" );
			if($orders != null){
				$order_list[$order_id]['lib_state'] = $orders[0];
			}else{
				$order_list[$order_id]['lib_state'] = '';
			}
            //显示取消订单
            $order_list[$order_id]['if_cancel'] = $model_order->getOrderOperateState('system_cancel',$order_info);
            //显示收到货款
            $order_list[$order_id]['if_system_receive_pay'] = $model_order->getOrderOperateState('system_receive_pay',$order_info);
        }
        //显示支付接口列表(搜索)
        $payment_list = Model('payment')->getPaymentOpenList();
        Tpl::output('payment_list',$payment_list);

        Tpl::output('order_list',$order_list);
        Tpl::output('show_page',$model_order->showpage());
        Tpl::showpage('order.index');
	}

	/**
	 * 平台订单状态操作
	 *
	 */
	public function change_stateOp() {
        $order_id = intval($_GET['order_id']);

        if($order_id <= 0){
            showMessage(L('miss_order_number'),$_POST['ref_url'],'html','error');
        }
        $model_order = Model('order');

        //获取订单详细
        $condition = array();
        $condition['order_id'] = $order_id;
        $order_info	= $model_order->getOrderInfo($condition);

        if ($_GET['state_type'] == 'cancel') {
            $result = $this->_order_cancel($order_info);
        } elseif ($_GET['state_type'] == 'receive_pay') {
            $result = $this->_order_receive_pay($order_info,$_POST);
        }

        if (!$result['state']) {
            showMessage($result['msg'],$_POST['ref_url'],'html','error');
        } else {
            showMessage($result['msg'],$_POST['ref_url']);
        }
	}

	/**
	 * 系统取消订单
	 */
	private function _order_cancel($order_info) {
	    $order_id = $order_info['order_id'];
	    $model_order = Model('order');
	    $logic_order = Logic('order');
	    $if_allow = $model_order->getOrderOperateState('system_cancel',$order_info);
	    if (!$if_allow) {
	        return callback(false,'无权操作');
	    }
	    $result =  $logic_order->changeOrderStateCancel($order_info,'system', $this->admin_info['name']);
        if ($result['state']) {
            $this->log(L('order_log_cancel').','.L('order_number').':'.$order_info['order_sn'],1);
        }
        return $result;
	}

	/**
	 * 系统收到货款
	 * @throws Exception
	 */
	private function _order_receive_pay($order_info, $post) {
	    $order_id = $order_info['order_id'];
	    $model_order = Model('order');
	    $logic_order = Logic('order');
	    $if_allow = $model_order->getOrderOperateState('system_receive_pay',$order_info);
	    if (!$if_allow) {
	        return callback(false,'无权操作');
	    }

	    if (!chksubmit()) {
	        Tpl::output('order_info',$order_info);
	        //显示支付接口列表
	        $payment_list = Model('payment')->getPaymentOpenList();
	        //去掉预存款和货到付款
	        foreach ($payment_list as $key => $value){
	            if ($value['payment_code'] == 'predeposit' || $value['payment_code'] == 'offline') {
	               unset($payment_list[$key]);
	            }
	        }
	        Tpl::output('payment_list',$payment_list);
	        Tpl::showpage('order.receive_pay');
	        exit();
	    }
	    $order_list	= $model_order->getOrderList(array('pay_sn'=>$order_info['pay_sn'],'order_state'=>ORDER_STATE_NEW));
	    $result = $logic_order->changeOrderReceivePay($order_list,'system',$this->admin_info['name'],$post);
        if ($result['state']) {
            $this->log('将订单改为已收款状态,'.L('order_number').':'.$order_info['order_sn'],1);
        }
	    return $result;
	}

	/**
	 * 查看订单
	 *
	 */
	public function show_orderOp(){
	    $order_id = intval($_GET['order_id']);
	    if($order_id <= 0 ){
	        showMessage(L('miss_order_number'));
	    }
        $model_order	= Model('order');
        $model_goods	= Model('goods');
        $order_info	= $model_order->getOrderInfo(array('order_id'=>$order_id),array('order_goods','order_common','store'));
		$orders = DB::getAll("select list_status, MAKE_CSV, OIF_MEMO from 33hao_mess_order_info where ORDER_ID = '".$order_info['order_id']."'" );
		if($orders != null){
			$order_info['lib_state'] = $orders[0];
		}else{
			$order_info['lib_state'] = '';
		}
		// foreach($order_info['extend_order_goods'] as $key => $val){
			// $goods_hscode = Model()->table('goods')->where(array('goods_id' => $val['goods_id']))->field('goods_id,goods_hscode,store_from')->find();
			// if($goods_hscode['goods_id'] == $val['goods_id']){
				// $order_info['extend_order_goods'][$key]['goods_hscode'] = $goods_hscode['goods_hscode'];
				// $order_info['extend_order_goods'][$key]['store_from'] = $goods_hscode['store_from'];
				// $taxes = $this->calcOrderList($order_info['extend_order_goods']);
			// }
			
		// }
		//积分使用记录
		$where = array();
		$where['pl_memberid'] = $order_info['buyer_id'];
		$where['pl_stage'] = 'cash';
		$where['pl_desc_like'] = $order_info['order_sn'];
		$points_info = Model('points')->getPointsInfo($where);
		if(!empty($points_info) && is_array($points_info)){
			$order_info['points_amount'] = number_format(-$points_info['pl_points']/C('exchange_rate'),2);
			$order_info['pl_points'] 	 = -$points_info['pl_points'] ;
		}
		
		foreach($order_info['extend_order_goods'] as $key => $value){
			$commonid = $model_goods->getGoodsIn(array('goods_id' => $value['goods_id']), 'goods_commonid');
			$order_info['extend_order_goods'][$key]['goods_commonid'] =  $commonid[0]['goods_commonid'];
		}
        //订单变更日志
		$log_list	= $model_order->getOrderLogList(array('order_id'=>$order_info['order_id']));
		Tpl::output('order_log',$log_list);

		//退款退货信息
        $model_refund = Model('refund_return');
        $condition = array();
        $condition['order_id'] = $order_info['order_id'];
        $condition['seller_state'] = 2;
        $condition['admin_time'] = array('gt',0);
        $return_list = $model_refund->getReturnList($condition);
        Tpl::output('return_list',$return_list);

        //退款信息
        $refund_list = $model_refund->getRefundList($condition);
        Tpl::output('refund_list',$refund_list);

		//卖家发货信息
		if (!empty($order_info['extend_order_common']['daddress_id'])) {
		    $daddress_info = Model('daddress')->getAddressInfo(array('address_id'=>$order_info['extend_order_common']['daddress_id']));
		    Tpl::output('daddress_info',$daddress_info);
		}
		//代金券信息
//		if (!empty($order_info['extend_order_common']['voucher_code'])) {
//			$voucher_list = Model('voucher')->getVoucherList(array('voucher_code'=>$order_info['extend_order_common']['voucher_code']));
//			Tpl::output('voucher_list',$voucher_list);
//		}
		// $order_info['extend_order_goods'] = $taxes[0];
		// $order_info['order_taxes_total']  = end($taxes[1]);
		Tpl::output('order_info',$order_info);
        Tpl::showpage('order.view');
	}
	
	/**
     * 后台交易订单商品税金计算
     * @param unknown $store_cart_list 以店铺ID分组的订单商品信息
     * @return array
     */
    public function calcOrderList($order_info) {
        if (empty($order_info) || !is_array($order_info)) return array($order_info,array(),0);
		$order_taxes_total = array();
		$goods_num = 0;
		$tmp_taxes = 0;
		//@todu计算运费
		$content = $_SESSION['content'][2];
		foreach ($order_info as $key => $cart_info) {
			$goods_num++;
			$cart_info['content'] = $content /count($order_info);
			if($cart_info['store_from'] == 2){
				//海外直邮方式
				$order_info[$key]['taxes_total'] = ncPriceFormat($cart_info['goods_taxes'] * $cart_info['goods_num']);
				
			}else if($cart_info['store_from'] == 1 || $cart_info['store_from'] == 8){
				//保税进口方式
				$order_info[$key]['taxes_total'] = ncPriceFormat($this->_logic_tax_1->single_times_allow_2000($cart_info));
			}else{
				//其他不收税方式
			}
			$tmp_taxes += $order_info[$key]['taxes_total'];
			$order_info[$key]['goods_num2'] = $goods_num;
			$order_taxes_total[$key] = ncPriceFormat($tmp_taxes);
		}
	return array($order_info,$order_taxes_total);
    }
	

	/**
	 * 导出
	 *
	 */
	public function export_step1Op(){
		$lang	= Language::getLangContent();

	    $model_order = Model('order');
        $condition	= array();
        if($_GET['order_sn']) {
        	$condition['order_sn'] = $_GET['order_sn'];
        }
        if($_GET['store_name']) {
            $condition['store_name'] = $_GET['store_name'];
        }
        if(in_array($_GET['order_state'],array('0','10','20','30','40'))){
        	$condition['order_state'] = $_GET['order_state'];
        }
        if($_GET['payment_code']) {
            $condition['payment_code'] = $_GET['payment_code'];
        }
        if($_GET['buyer_name']) {
            $condition['buyer_name'] = $_GET['buyer_name'];
        }
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_time']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_time']);
        $start_unixtime = $if_start_time ? strtotime($_GET['query_start_time']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['query_end_time']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }

		if (!is_numeric($_GET['curpage'])){
			$count = $model_order->getOrderCount($condition);
			$array = array();
			if ($count > self::EXPORT_SIZE ){	//显示下载链接
				$page = ceil($count/self::EXPORT_SIZE);
				for ($i=1;$i<=$page;$i++){
					$limit1 = ($i-1)*self::EXPORT_SIZE + 1;
					$limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
					$array[$i] = $limit1.' ~ '.$limit2 ;
				}
				Tpl::output('list',$array);
				Tpl::output('murl','index.php?gct=order&gp=index');
				Tpl::showpage('export.excel');
			}else{	//如果数量小，直接下载
				$data = $model_order->getOrderList($condition,'','*','order_id desc',self::EXPORT_SIZE);
				$this->createExcel($data);
			}
		}else{	//下载
			$limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
			$limit2 = self::EXPORT_SIZE;
			$data = $model_order->getOrderList($condition,'','*','order_id desc',"{$limit1},{$limit2}");
			$this->createExcel($data);
		}
	}

	/**
	 * 生成excel
	 *
	 * @param array $data
	 */
	private function createExcel($data = array()){
		Language::read('export');
		import('libraries.excel');
		$excel_obj = new Excel();
		$excel_data = array();
		//设置样式
		$excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
		//header
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_no'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_store'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_buyer'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_xtimd'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_count'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_yfei'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_paytype'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_state'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_storeid'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_buyerid'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_bemail'));
		//data
		foreach ((array)$data as $k=>$v){
			$tmp = array();
			$tmp[] = array('data'=>'NC'.$v['order_sn']);
			$tmp[] = array('data'=>$v['store_name']);
			$tmp[] = array('data'=>$v['buyer_name']);
			$tmp[] = array('data'=>date('Y-m-d H:i:s',$v['add_time']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['order_amount']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['shipping_fee']));
			$tmp[] = array('data'=>orderPaymentName($v['payment_code']));
			$tmp[] = array('data'=>orderState($v));
			$tmp[] = array('data'=>$v['store_id']);
			$tmp[] = array('data'=>$v['buyer_id']);
			$tmp[] = array('data'=>$v['buyer_email']);
			$excel_data[] = $tmp;
		}
		$excel_data = $excel_obj->charset($excel_data,CHARSET);
		$excel_obj->addArray($excel_data);
		$excel_obj->addWorksheet($excel_obj->charset(L('exp_od_order'),CHARSET));
		$excel_obj->generateXML($excel_obj->charset(L('exp_od_order'),CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
	}

	/**
	 * 取消订单得返利值
	 */
	public function reasonOp(){
		$model_order = Model('order');
		$admininfo = $this->getAdminInfo();
		$reason = array();
		$reason['order_id'] = $_GET['order_id'];
		$reason['order_reason'] = $_GET['reason'];
		$reason['id'] = $admininfo['id'];
		$reason['name'] = $admininfo['name'];
		$order_reason = $model_order ->reason($reason);
		if($order_reason){
			$this->log('清掉订单返利数据',1);
			showMessage('修改成功！','');
		}else{
			showMessage('修改失败！','','html','error');
		}
	}		
	
	
	

}
