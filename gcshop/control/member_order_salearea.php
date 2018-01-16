<?php
/**
 * 团队管理交易管理
 *
 *
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');
class member_order_saleareaControl extends BaseMemberControl{
    /**
     * 每次导出订单数量
     * @var int
     */
	const EXPORT_SIZE = 1000;

	public function __construct(){
		parent::__construct();
		Language::read('member_member_index');
	}

	public function indexOp(){
		$model_member = Model('member');
		$member_info = $model_member->getMemberInfoByID($_SESSION['member_id'], 'member_id,is_seller,is_manager,sa_id');
		//得到属于哪个区域区域
		$sales_area = Model()->table('sales_area')->where(array('sa_id' => $member_info['sa_id']))->find();
		
		if($member_info['is_manager']){
			$member_info['sa_id'];

			$model_order = Model('order');
			$condition = array();
			if($_GET['order_sn']) {
				$condition['order_sn'] = $_GET['order_sn'];
			}
			if($_GET['store_name']) {
				$condition['store_name'] = $_GET['store_name'];
			}
			if(in_array($_GET['state_type'],array('0','10','20','30','40'))){
				$condition['order_state'] = $_GET['state_type'];
			}
//			if($_GET['area_info']) {
//				$condition['area_info'] = $_GET['area_info'];
//			}
//			if($_GET['buyer_name']) {
//				$condition['buyer_name'] = $_GET['buyer_name'];
//			}

			$if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
			$if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
			$start_unixtime = $if_start_time ? strtotime($_GET['query_start_date']) : null;
			$end_unixtime = $if_end_time ? strtotime($_GET['query_end_date']): null;
			if ($start_unixtime || $end_unixtime) {
				$condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
			}
			
			$is_manager = array();
			$is_manager['sa_id'] = $member_info['sa_id'];
			$sa_id = $model_member->sa_id($is_manager);	
			foreach($sa_id as $k=>$v){
					$member_id.=$v[ 'member_id'].",";
			}
			$member_id = substr($member_id,0,strlen($member_id)-1); 
			$member = array();
			//筛选某一销售员查询业绩,
			if($_GET['superior_id']) {
				$member_id= $_GET['superior_id'];
			}
			$member['refer_id'] = array('in',$member_id);
			$refer_id = $model_member->refer_id($member);
			foreach($refer_id as $k=>$v){
				$buyer_id.=$v[ 'member_id'].",";
			}
			$buyer_id = substr($buyer_id,0,strlen($buyer_id)-1); 
			$condition['buyer_id'] = array('in',$member_id.','.$buyer_id);

			$_SESSION['condition'] = $condition;
			$order_list = $model_order->getOrderList($condition, 50, '*', 'order_id desc','', array('order_goods','order_common','member'));
			//var_dump($order_list);
			
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
			
			//得到上级用户的信息
			$member_user = Model('member')->superior_member(array('member_id' => $value['buyer_id']));
			$superior_member = Model('member')->ref_id(array('member_id' => $member_user[0]['refer_id']));
			$order_info['superior_member'] = $superior_member[0]['member_name'];
			$order_info['superior_id'] = $superior_member[0]['member_id'];
				$order_list[$key] = $order_info;
			}
			
		//查询上三级用户名
		$model_member = Model('member');
		$member_user = $model_member->getMemberInfoByID($value['buyer_id'], 'member_name, refer_id');
		$superior1 = $model_member->getMemberInfoByID($member_user['refer_id']);
		if(!empty($superior1['member_id'])){
		$upname[$key]['up1name'] = $superior1['member_name'];
		$superior2 = $model_member->getMemberInfoByID($superior1['refer_id']);
			if(!empty($superior2['member_id'])){
			$upname[$key]['up2name'] = $superior2['member_name'];
			$superior3 = $model_member->getMemberInfoByID($superior2['refer_id']);
				if(!empty($superior3['member_id'])){
					$upname[$key]['up3name'] = $superior3['member_name'];
				}
			}
		}

		Tpl::output('upname',$upname);

			//筛选框选定输出的团队二维码销售员
			$model_alltlist = Model('member');
			$where = array();
			$where['sa_id'] = intval($_SESSION["sa_id"]);
			$all_team_list = $model_alltlist->table('member')->where(array('sa_id' => intval($_SESSION["sa_id"])))->select();
		
			//显示支付接口列表(搜索)
			$payment_list = Model('payment')->getPaymentOpenList();
			self::profile_menu('qcode','excqcode');
			Tpl::output('payment_list',$payment_list);
			Tpl::output('order_list',$order_list);
			Tpl::output('show_page',$model_order->showpage());
			Tpl::output('all_team_list',$all_team_list);
			Tpl::output('sales_area',$sales_area);
			Tpl::showpage('member_order_salearea.index');
		
		}

	}
		/**
		* 业绩明细导出订单
		*/
	public function export_steppOp(){
	 	$model_order = Model('order');
		$condition = $_SESSION['condition'];
			
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
				$data = $model_order->getOrderList($condition,'','*','order_id desc',$count);
				$this->createExcel($data);
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
		* 销售区域导出订单
		*/
	public function export_step_regionorderlistOp(){
	 	$model_order = Model('order');
		$condition = $_SESSION['condition'];

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
     * 查看订单详细
     *
     */
    public function show_orderOp() {
        $order_id = intval($_GET['order_id']);
        if ($order_id <= 0) {
            showMessage(Language::get('member_order_none_exist'),'','html','error');
        }
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $order_info = $model_order->getOrderInfo($condition,array('order_goods','order_common','store'));
        if (empty($order_info) || $order_info['delete_state'] == ORDER_DEL_STATE_DROP) {
            showMessage(Language::get('member_order_none_exist'),'','html','error');
        }
        $model_refund_return = Model('refund_return');
        $order_list = array();
        $order_list[$order_id] = $order_info;
        $order_list = $model_refund_return->getGoodsRefundList($order_list,1);//订单商品的退款退货显示
        $order_info = $order_list[$order_id];
        $refund_all = $order_info['refund_list'][0];
        if (!empty($refund_all) && $refund_all['seller_state'] < 3) {//订单全部退款商家审核状态:1为待审核,2为同意,3为不同意
            Tpl::output('refund_all',$refund_all);
        }

        //显示锁定中
        $order_info['if_lock'] = $model_order->getOrderOperateState('lock',$order_info);

        //显示取消订单
        $order_info['if_cancel'] = $model_order->getOrderOperateState('buyer_cancel',$order_info);

        //显示退款取消订单
        $order_info['if_refund_cancel'] = $model_order->getOrderOperateState('refund_cancel',$order_info);

        //显示投诉
        $order_info['if_complain'] = $model_order->getOrderOperateState('complain',$order_info);

        //显示收货
        $order_info['if_receive'] = $model_order->getOrderOperateState('receive',$order_info);

        //显示物流跟踪
        $order_info['if_deliver'] = $model_order->getOrderOperateState('deliver',$order_info);

        //显示评价
        $order_info['if_evaluation'] = $model_order->getOrderOperateState('evaluation',$order_info);

        //显示分享
        $order_info['if_share'] = $model_order->getOrderOperateState('share',$order_info);

        //显示系统自动取消订单日期
        if ($order_info['order_state'] == ORDER_STATE_NEW) {
            //$order_info['order_cancel_day'] = $order_info['add_time'] + ORDER_AUTO_CANCEL_DAY * 24 * 3600;
			$order_info['order_cancel_day'] = $order_info['add_time'] + ORDER_AUTO_CANCEL_DAY + 7 * 24 * 3600;
        }

        //显示快递信息
        if ($order_info['shipping_code'] != '') {
            $express = rkcache('express',true);
            $order_info['express_info']['e_code'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_code'];
            $order_info['express_info']['e_name'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_name'];
            $order_info['express_info']['e_url'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_url'];
        }

        //显示系统自动收获时间
        if ($order_info['order_state'] == ORDER_STATE_SEND) {
			$order_info['order_confirm_day'] = $order_info['delay_time'] + ORDER_AUTO_RECEIVE_DAY + 15 * 24 * 3600;
        }

        //如果订单已取消，取得取消原因、时间，操作人
        if ($order_info['order_state'] == ORDER_STATE_CANCEL) {
            $order_info['close_info'] = $model_order->getOrderLogInfo(array('order_id'=>$order_info['order_id']),'log_id desc');
        }

        foreach ($order_info['extend_order_goods'] as $k=>$value) {
			$commonid = Model('goods')->getGoodsIn(array('goods_id' => $value['goods_id']), 'goods_commonid,store_from,goods_rebate_rate');
			$value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id'], $commonid[0]['goods_commonid']);
			$value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id'], $commonid[0]['goods_commonid']);
            $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
            $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
			$value['goods_rebate_rate'] =$commonid[0]['goods_rebate_rate'];
			$order_info['extend_order_goods'][$k]['store_from'] = $commonid[0]['store_from'];
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
		
		//查询上三级用户名
		$model_member = Model('member');
		$member_user = $model_member->getMemberInfoByID($value['buyer_id'], 'member_name, refer_id');
		$superior1 = $model_member->getMemberInfoByID($member_user['refer_id']);
		if(!empty($superior1['member_id'])){
		$upname[$key]['up1name'] = $superior1['member_name'];
		$superior2 = $model_member->getMemberInfoByID($superior1['refer_id']);
			if(!empty($superior2['member_id'])){
			$upname[$key]['up2name'] = $superior2['member_name'];
			$superior3 = $model_member->getMemberInfoByID($superior2['refer_id']);
				if(!empty($superior3['member_id'])){
					$upname[$key]['up3name'] = $superior3['member_name'];
				}
			}
		}
		Tpl::output('upname',$upname);
		
		//查询区域合作方和平台合作方

		if($order_info['platform_member_id'] > 0){
			$model_partner = Model('partner');
			$condition_partner['member_id'] = $order_info['platform_member_id'];
			$partner = $model_partner->getPartnerInfo($condition_partner,'*');
			Tpl::output('partner',$partner);
		}

		if($order_info['area_member_id'] > 0){
			$model_salearea = Model('sales_area');
			$condition_salearea['sa_manager_id'] = $order_info['area_member_id'];
			$salearea = $model_salearea->getSalesAreaInfo($condition_salearea,'*');
			Tpl::output('salearea',$salearea);
		}		
		
		$is_one = C('one_rank_rebate'); 			//是否开启一级返利
		$is_two = C('two_rank_rebate'); 			//是否开启二级返利
		$is_three = C('three_rank_rebate'); 		//是否开启三级返利
		
		Tpl::output('is_one',$is_one);
		Tpl::output('is_two',$is_two);
		Tpl::output('is_three',$is_three);
        Tpl::output('order_info',$order_info);

        //卖家发货信息
        if (!empty($order_info['extend_order_common']['daddress_id'])) {
            $daddress_info = Model('daddress')->getAddressInfo(array('address_id'=>$order_info['extend_order_common']['daddress_id']));
            Tpl::output('daddress_info',$daddress_info);
        }
		//代金券信息
		if (!empty($order_info['extend_order_common']['voucher_code'])) {
			$voucher_list = Model('voucher')->getVoucherList(array('voucher_code'=>$order_info['extend_order_common']['voucher_code']));
			Tpl::output('voucher_list',$voucher_list);
		}

        Tpl::showpage('member_order_salearea.view');
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
		if($_GET['area_id']) {
			$condition_common = array();
            $condition_common['reciver_province_id|reciver_city_id|reciver_area_id'] = $_GET['area_id'];
			$order_common=Model();
			$order_id=array();
			$order_id['order_id']=$order_common->table('order_common')->field('order_id')->where($condition_common)->select();

			foreach($order_id['order_id'] as $k=>$v)
				{
				foreach($v as $d)
					{
					 $str.=$d.",";
					}
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
		$excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'微软雅黑','Size'=>'11')));
		//header
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'订单编号');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'店铺');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'销售员');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'买家帐号');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'买家邮箱');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'下单时间');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'订单金额');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'运费');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'支付方式');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'订单状态');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'店铺ID');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'买家ID');
		// $excel_data[0][] = array('styleid'=>'s_title','data'=>'收货地址');
		// $excel_data[0][] = array('styleid'=>'s_title','data'=>'收货人');
		// $excel_data[0][] = array('styleid'=>'s_title','data'=>'收货人手机');
		//data
		foreach ((array)$data as $k=>$v){
			$tmp = array();
			$tmp[] = array('data'=>'GC'.$v['order_sn']);
			$tmp[] = array('data'=>$v['store_name']);
			$tmp[] = array('data'=>$v['ref_name']);
			$tmp[] = array('data'=>$v['buyer_name']);
			$tmp[] = array('data'=>$v['buyer_email']);
			$tmp[] = array('data'=>date('Y-m-d H:i:s',$v['add_time']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['order_amount']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['shipping_fee']));
			$tmp[] = array('data'=>orderPaymentName($v['payment_code']));
			$tmp[] = array('data'=>orderState($v));
			$tmp[] = array('data'=>$v['store_id']);
			$tmp[] = array('data'=>$v['buyer_id']);
			// $tmp[] = array('data'=>$v['extend_order_common']['reciver_info']['address']);
			// $tmp[] = array('data'=>$v['extend_order_common']['reciver_name']);
			// $tmp[] = array('data'=>$v['extend_order_common']['reciver_info']['mob_phone']);
			$excel_data[] = $tmp;
		}

		$excel_data = $excel_obj->charset($excel_data,CHARSET);
		$excel_obj->addArray($excel_data);
		$excel_obj->addWorksheet($excel_obj->charset(L('exp_od_order'),CHARSET));
		$excel_obj->generateXML($excel_obj->charset(L('exp_od_order'),CHARSET).$_GET['curpage'].'orderlist'.date('Y-m-d-H-m',time()));
	}
	

	
	//我的销售团队	
	public function member_my_teamOp() {
		Language::read('member_home_member');
		$model_alltlist = Model('member');
		$partner = $model_alltlist->getPartnerId(array('member_id'=>$_SESSION["member_id"]), 'partner_id');
		$where = array();
		$where['saleplat_id'] = intval($partner["partner_id"]);
		$all_team_list = $model_alltlist->getMemberList($where,'*',30);

		self::profile_menu('qcode','member_my_team');
		Tpl::output('menu_sign','profile');
		Tpl::output('menu_sign_url','index.php?gct=member_information&gp=member_my_team');
		Tpl::output('menu_sign1','member_my_team');
		Tpl::output('all_team_list',$all_team_list);
		Tpl::output('show_page',$model_alltlist->showpage(2));
		Tpl::showpage('member_my_team');

	}
	
	//查看销售团队下面的全部用户
	public function member_my_userOp(){
		Language::read('member_home_member');
		$model_member = Model('member');
		$where = array();
		$where['sa_id'] = intval($_SESSION["sa_id"]);
		$order = 'member_id';
		$member_id = $_GET['member_id'];
		$all_team_list = Model('member')->where($where)->select();
		if(!$member_id){
			foreach($all_team_list as $k => $v){
				$member_id .= $v['member_id'].','; 
			}
			$member_id=substr($member_id, 0, -1);
		}
		$member = array();
		$condition['refer_id'] = array('in',$member_id);
		$member_my_user = $model_member->getMemberList($condition, '*', 100, 'member_id desc');

		foreach($member_my_user as $k => $v){
			$ref = $model_member->getMemberInfoByID($v['refer_id']);
			$member_my_user[$k]['refer_member_name'] = $ref['member_name'];
			$member_my_user[$k]['refer_member_mobile'] = $ref['member_mobile'];
		}

		self::profile_menu('qcode','member_my_user');
		Tpl::output('menu_sign','profile');
		Tpl::output('menu_sign_url','index.php?gct=member_information&gp=member_my_user');
		Tpl::output('menu_sign1','member_my_team');
		Tpl::output('member_eam_list',$all_team_list);
		Tpl::output('all_team_list',$member_my_user);
		Tpl::output('show_page',$model_member->showpage());
		Tpl::showpage('member_my_user');
	}
	
	
	//销售区域管理者查看代理的销售区域订单，以订单收货地址区分
	public function member_order_regionOp(){
		$model_member = Model('member');
		$member_info = $model_member->getMemberInfoByID($_SESSION['member_id'], 'member_id,is_seller,is_manager,sa_id');
		
		//得到属于哪个区域区域
		$sales_area = Model()->table('sales_area')->where(array('sa_id' => $member_info['sa_id']))->find();
		
		if($member_info['is_manager'] && $sales_area['sa_areaid']){
			$member_info['sa_id'];

			$model_order = Model('order');
			$condition = array();
			if($_GET['order_sn']) {
				$condition['order_sn'] = $_GET['order_sn'];
			}
			if($_GET['store_name']) {
				$condition['store_name'] = $_GET['store_name'];
			}
			if(in_array($_GET['state_type'],array('0','10','20','30','40'))){
				$condition['order_state'] = $_GET['state_type'];
			}

			$if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
			$if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
			$start_unixtime = $if_start_time ? strtotime($_GET['query_start_date']) : null;
			$end_unixtime = $if_end_time ? strtotime($_GET['query_end_date']): null;
			if ($start_unixtime || $end_unixtime) {
				$condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
			}
			$order_id = array();
			$order_id = Model()->table('order_common')->where(array('reciver_province_id|reciver_city_id|reciver_area_id'=>$sales_area['sa_areaid']))->order('order_id desc')->select();
			$order_id['order_id'] = array('in',$order_id);
			foreach($order_id as $k=>$v){
				$order_id.=$v[ 'order_id'].",";
				}
			$order_id = substr($order_id,0,strlen($order_id)-1); 
					
			$condition['order_id'] = array('in',$order_id.','.$order_id);
			$_SESSION['condition'] = $condition;
		
			$order_list = $model_order->getOrderList($condition, '20', '*', 'order_id desc','', array('order_goods','order_common','member'));

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

				foreach ($order_info['extend_order_goods'] as $k=>$value) {
					$commonid = Model('goods')->getGoodsIn(array('goods_id' => $value['goods_id']), 'goods_commonid');
					$value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id'], $commonid[0]['goods_commonid']);
					$value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id'], $commonid[0]['goods_commonid']);
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
			
			//得到上级用户的信息
			$member_user = Model('member')->superior_member(array('member_id' => $value['buyer_id']));
			$superior_member = Model('member')->ref_id(array('member_id' => $member_user[0]['refer_id']));
			
			$order_info['superior_member'] = $superior_member[0]['member_name'];
				$order_list[$key] = $order_info;

			}
			Tpl::output('show_page',$model_order->showpage());
			
		}
		//显示支付接口列表(搜索)
		$payment_list = Model('payment')->getPaymentOpenList();
		self::profile_menu('qcode','member_order_region');
		Tpl::output('payment_list',$payment_list);
		Tpl::output('order_list',$order_list);
		Tpl::output('sales_area',$sales_area);
		Tpl::showpage('member_order_region');
	}
	
	
	/**
	 * 团队销售业绩列表日志
	 */
	public function mgtsalescreditlogOp(){
		$model_member = Model('member');
		$member_info = $model_member->getMemberInfoByID($_SESSION['member_id'], 'member_id,is_seller,is_manager,sa_id');
				
		if($member_info['is_manager']){	
		$condition_arr = array();
		//接收筛选条件
		if ($_GET['saleman_id']){
		$condition_arr['sc_memberid'] = trim($_GET['saleman_id']);
		}
		//
		if ($_GET['stage']){
			$condition_arr['sc_stage'] = trim($_GET['stage']);
		}
		$condition_arr['saddtime'] = strtotime($_GET['query_start_date']);
		$condition_arr['eaddtime'] = strtotime($_GET['query_end_date']);
        if($condition_arr['eaddtime'] > 0) {
            $condition_arr['eaddtime'] += 86400;
        }
		//订单描述
		$condition_arr['sc_desc_like'] 	= trim($_GET['order_sn']);
			
		//判断是区域管理，找出下属二维码销售员，只显示其下属二维码销售员业绩
		$sa_id = Model('member')->getMemberInfo(array('member_id' =>$_SESSION['member_id']), 'sa_id');
		$allsaleman_id	=array();
		$allsaleman_id = Model('member')->getMemberList(array('sa_id' =>$sa_id['sa_id']), 'member_id');
		foreach($allsaleman_id as $key=>$v){
			$allsaleman_id.=$v['member_id'].",";
			}
		$allsaleman_id = substr($allsaleman_id,5,strlen($allsaleman_id)); 
		$allsaleman_id .= "0";	
		$condition_arr['sc_memberid_in'] = $allsaleman_id;	
		
		//分页
		$page	= new Page();
		$page->setEachNum(50);
		$page->setStyle('admin');

		//查询
		$salescredit_model = Model('salescredit');
		$list_log = $salescredit_model->getsalescreditlogList($condition_arr,$page,'*','');
		if($list_log){
		foreach($list_log as $k=>$val){
			$re = Model('member')->getMemberInfo(array('member_id'=>$val['sc_memberid']),'sa_id');
			$sa_name = Model('sales_area')->getSalesAreaInfoByID($re['sa_id'] ,'sa_name');
			$list_log[$k]['sa_name'] = $sa_name['sa_name'];
		}
		}
		
		//筛选框选定输出的团队二维码销售员
		$model_alltlist = Model('member');
		$where = array();
		$where['sa_id'] = intval($_SESSION["sa_id"]);
		$all_team_list = $model_alltlist->table('member')->where(array('sa_id' => intval($_SESSION["sa_id"])))->select();
		
		//信息输出
		self::profile_menu('qcode','mgtsalescreditlog');
		Tpl::output('list_log',$list_log);
		Tpl::output('show_page',$page->show());
		Tpl::output('all_team_list',$all_team_list);
		Tpl::showpage('member_order_mgtsalescredit');
	}
	}
	
		/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_type,$menu_key='') {

		
		$menu_array	= array();
		$menu_array	= array(
		1=>array('menu_key'=>'excqcode','menu_name'=>'团队业绩订单','menu_url'=>'index.php?gct=member_order_salearea&gp=index'),
		2=>array('menu_key'=>'member_my_team','menu_name'=>'我的团队成员','menu_url'=>'index.php?gct=member_order_salearea&gp=member_my_team'),
		3=>array('menu_key'=>'member_my_user','menu_name'=>'团队拓展用户','menu_url'=>'index.php?gct=member_order_salearea&gp=member_my_user'),
		4=>array('menu_key'=>'mgtsalescreditlog','menu_name'=>'团队销售业绩','menu_url'=>'index.php?gct=member_order_salearea&gp=mgtsalescreditlog'),
		5=>array('menu_key'=>'member_order_region','menu_name'=>'销售区域订单','menu_url'=>'index.php?gct=member_order_salearea&gp=member_order_region'),	
		);

		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
