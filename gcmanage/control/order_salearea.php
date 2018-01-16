<?php
/**
 * 销售总代理区域交易管理
 *
 *
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');
class order_saleareaControl extends SystemControl{
    /**
     * 每次导出订单数量
     * @var int
     */
	const EXPORT_SIZE = 1000;

	public function __construct(){
		parent::__construct();
		Language::read('trade');
	}

	public function indexOp(){
        $model_order = Model('order');
        $condition = array();
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
		if($_GET['area_name']){
			$condition_area = array();
			$condition_area['sa_name'] = array('like', '%' . $_GET['area_name'] . '%');
			$model_arealist = Model('sales_area') -> getSalesAreaIn($condition_area,'*');
			foreach($model_arealist as $k => $v){
				$area_member .= ','.$v['sa_manager_id'];
			}
			$condition['area_member_id'] = array('in',$area_member);
		}else{
			$condition['area_member_id'] = array('gt',0);//区域合作方area_member_id均有大于0的值
		}
			
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_time']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_time']);
        $start_unixtime = $if_start_time ? strtotime($_GET['query_start_time']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['query_end_time']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
		
        if($_GET['area_id']) {
			$condition_common = array();
            $condition_common['reciver_province_id|reciver_city_id|reciver_area_id'] = $_GET['area_id'];
			$order_common=Model();
			$order_id=array();
			$order_id['order_id']=$order_common->table('order_common')->field('order_id')->where($condition_common)->order('order_id desc')->select();

			foreach($order_id['order_id'] as $k=>$v)
				{
				foreach($v as $d){ $str.=$d.",";}
				}
			$str = trim($str,",");	
			$condition['order_id'] = array('in',$str);
        }

        $order_list = $model_order->getOrderList($condition, 100, '*', 'order_id desc','', array('order_goods','order_common','member'));
		//$order_list	= $model_order->getOrderList($condition,10);
        //页面中显示那些操作
        foreach ($order_list as $key => $order_info) {
        	//显示取消订单
        	$order_info['if_cancel'] = $model_order->getOrderOperateState('store_cancel',$order_info);

        	//显示调整运费
        	$order_info['if_modify_price'] = $model_order->getOrderOperateState('modify_price',$order_info);
			
			//显示修改价格
        	$order_info['if_spay_price'] = $model_order->getOrderOperateState('spay_price',$order_info);

        	//显示发货
        	$order_info['if_send'] = $model_order->getOrderOperateState('send',$order_info);

        	//显示锁定中
        	$order_info['if_lock'] = $model_order->getOrderOperateState('lock',$order_info);

        	//显示物流跟踪
        	$order_info['if_deliver'] = $model_order->getOrderOperateState('deliver',$order_info);

        	foreach ($order_info['extend_order_goods'] as $value) {
        	    $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
        	    $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
        	    $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
        	    $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
        	    if ($value['goods_type'] == 5) {
        	        $order_info['zengpin_list'][] = $value;
        	    } else {
        	        $order_info['goods_list'][] = $value;
        	    }
        	}

        	if (empty($order_info['zengpin_list'])) {
        	    $order_info['goods_count'] = count($order_info['goods_list']);
        	} else {
        	    $order_info['goods_count'] = count($order_info['goods_list']) + 1;
        	}
        	$order_list[$key] = $order_info;
			
			$condition_uper =array();
			$condition_uper['member_id'] =	$order_info['buyer_id'];
			$uper_id = Model('member')->getMemberSuperior($condition_uper);
			$order_list[$key]['one_id'] = $uper_id['one_id'];
			$order_list[$key]['two_id'] = $uper_id['two_id'];
			$order_list[$key]['three_id'] = $uper_id['three_id'];
			$onename = Model('member')->getMemberInfoByID($uper_id['one_id'],'member_name');
			$twoname = Model('member')->getMemberInfoByID($uper_id['two_id'],'member_name');
			$threename = Model('member')->getMemberInfoByID($uper_id['three_id'],'member_name');
			$order_list[$key]['one_name'] = $onename['member_name'];
			$order_list[$key]['two_name'] = $twoname['member_name'];
			$order_list[$key]['three_name'] = $threename['member_name'];
			$platname = Model('partner')->getPartnerInfo(array('member_id'=>$order_info['platform_member_id']),'partner_name');
			$areaname = Model('sales_area')->getSalesAreaInfo(array('sa_manager_id'=>$order_info['area_member_id']),'sa_name,sa_areaname');
			$order_list[$key]['platform_name'] = $platname['partner_name'];
			$order_list[$key]['salesarea_name'] = $areaname['sa_name'];
			$order_list[$key]['agentarea_name'] = $areaname['sa_areaname'];
        }
		 //显示支付接口列表(搜索)
        $payment_list = Model('payment')->getPaymentOpenList();
        Tpl::output('payment_list',$payment_list);
        Tpl::output('order_list',$order_list);
        Tpl::output('show_page',$model_order->showpage());
        Tpl::showpage('order_salearea.index');
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
        $order_info	= $model_order->getOrderInfo(array('order_id'=>$order_id),array('order_goods','order_common','store'));

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

		Tpl::output('order_info',$order_info);
        Tpl::showpage('order.view');
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
		if($_GET['area_name']){
			$condition_area = array();
			$condition_area['sa_name'] = array('like', '%' . $_GET['area_name'] . '%');
			$model_arealist = Model('sales_area') -> getSalesAreaIn($condition_area,'*');
			foreach($model_arealist as $k => $v){
				$area_member .= ','.$v['sa_manager_id'];
			}
			$condition['area_member_id'] = array('in',$area_member);
		}else{
			$condition['area_member_id'] = array('gt',0);//区域合作方area_member_id均有大于0的值
		}
			
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_time']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_time']);
        $start_unixtime = $if_start_time ? strtotime($_GET['query_start_time']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['query_end_time']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
		
        if($_GET['area_id']) {
			$condition_common = array();
            $condition_common['reciver_province_id|reciver_city_id|reciver_area_id'] = $_GET['area_id'];
			$order_common=Model();
			$order_id=array();
			$order_id['order_id']=$order_common->table('order_common')->field('order_id')->where($condition_common)->order('order_id desc')->select();

			foreach($order_id['order_id'] as $k=>$v)
				{
				foreach($v as $d){ $str.=$d.",";}
				}
			$str = trim($str,",");	
			$condition['order_id'] = array('in',$str);
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
				//$data = $model_order->getOrderList($condition,'','*','order_id desc',self::EXPORT_SIZE);
				$data = $model_order->getOrderList($condition, '', '*', 'order_id desc','', array('order_goods','order_common','member'),self::EXPORT_SIZE);
				$this->createExcel($data);
			}
		}else{	//下载
			$limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
			$limit2 = self::EXPORT_SIZE;
			//$data = $model_order->getOrderList($condition,'','*','order_id desc',"{$limit1},{$limit2}");
			$data = $model_order->getOrderList($condition, '', '*', 'order_id desc','', array('order_goods','order_common','member'),"{$limit1},{$limit2}");
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
		$excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'微软雅黑','Size'=>'11','Bold'=>'1')));
		//header
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_no'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_storeid'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_store'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_buyerid'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_buyer'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_bemail'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'消费奖励');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_xtimd'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_count'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_yfei'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'UP1奖励');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'UP2奖励');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'UP3奖励');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'区域合作方');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'区域管理ID');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'推广提成');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_paytype'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_state'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'收货区域');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'收货人');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'收货人手机');
		//data
		foreach ((array)$data as $k=>$v){
			$tmp = array();
			$tmp[] = array('data'=>$v['order_sn']);
			$tmp[] = array('data'=>$v['store_id']);
			$tmp[] = array('data'=>$v['store_name']);
			$tmp[] = array('data'=>$v['buyer_id']);
			$tmp[] = array('data'=>$v['buyer_name']);
			$tmp[] = array('data'=>$v['buyer_email']);
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['goods_rebate_amount']));
			$tmp[] = array('data'=>date('Y-m-d H:i:s',$v['add_time']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['order_amount']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['shipping_fee']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['one_rebate']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['two_rebate']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['three_rabate']));
			$tmp[] = array('data'=>$v['salesarea_name']);
			$tmp[] = array('data'=>$v['area_member_id']);
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['area_rebate']));
			$tmp[] = array('data'=>orderPaymentName($v['payment_code']));
			$tmp[] = array('data'=>orderState($v));
			$tmp[] = array('data'=>$v['extend_order_common']['reciver_info']['area']);
			$tmp[] = array('data'=>$v['extend_order_common']['reciver_name']);
			$tmp[] = array('data'=>$v['extend_order_common']['reciver_info']['mob_phone']);
			$excel_data[] = $tmp;
		}
		$excel_data = $excel_obj->charset($excel_data,CHARSET);
		$excel_obj->addArray($excel_data);
		$excel_obj->addWorksheet($excel_obj->charset(L('exp_od_order'),CHARSET));
		$excel_obj->generateXML($excel_obj->charset(L('exp_od_order'),CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
	}
}
