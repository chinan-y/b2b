<?php
/**
 * 销售区域管理
 ***/

defined('GcWebShop') or exit('Access Invalid!');

class erwei_qu_memberControl extends SystemControl{
	
	private $search_arr;//处理后的参数
	
	public function __construct(){
		parent::__construct();
		Language::read('sales_area');	
		$this->search_arr = $_REQUEST;	//存储参数
	}

	/**
	 * 销售区域
	 */
	public function memberOp(){
		$lang	= Language::getLangContent();
		$model_class = Model('sales_area');

		//删除
		if (chksubmit()){
			if (!empty($_POST['check_sa_id']) && is_array($_POST['check_sa_id']) ){
			    $result = $model_class->delSalesArea(array('sa_id'=>array('in',$_POST['check_sa_id'])));
				if ($result) {
			        $this->log(L('nc_del,sales_area').'[ID:'.implode(',',$_POST['check_sa_id']).']',1);
				    showMessage($lang['nc_common_del_succ']);
				}
			}
		    showMessage($lang['nc_common_del_fail']);
		}
		$condition = array('sa_distinguish' => 0);
		$sales_area_list = $model_class->getSalesAreaList($condition,200);
		
		Tpl::output('sales_area_list',$sales_area_list);
		Tpl::output('page',$model_class->showpage());

		Tpl::showpage('erwei_qu_member');
	}

	
	/**
	 * 销售团队列表
	 */
	public function sales_area_samlistOp(){
	    Language::read('member');
		$model_samlist = Model('stat');
		$where = array();
		$where['sa_id'] = intval($_GET["sa_id"]);
		$samlist = $model_samlist->getMemberList($where, '', 10);
		if (is_array($samlist)){
			foreach ($samlist as $k=> $v){
				$samlist[$k]['member_time'] = $v['member_time']?date('Y-m-d H:i:s',$v['member_time']):'';
				$samlist[$k]['member_login_time'] = $v['member_login_time']?date('Y-m-d H:i:s',$v['member_login_time']):'';
			}
		}
		
		Tpl::output('samlist',$samlist);
		Tpl::output('show_page',$model_samlist->showpage(2));
		Tpl::showpage('erwei_qu_member.samlist');		
		
		}
	

	/**
	 * 编辑
	 */
	public function sales_area_editOp(){
		$lang	= Language::getLangContent();

		$model_class = Model('sales_area');

		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["sa_name"], "require"=>"true", "message"=>$lang['sales_area_name_no_null']),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$update_array = array();
				$update_array['sa_name'] = $_POST['sa_name'];
				$update_array['sa_manager'] = $_POST['sa_manager'];
				$update_array['sa_manager_id'] = intval($_POST['sa_manager_id']);
				$update_array['sa_contact'] = $_POST['sa_contact'];
				$update_array['sa_desc'] = $_POST['sa_desc'];
				$update_array['sa_cash'] = intval($_POST['sa_cash']);
				$update_array['sa_rate'] = intval($_POST['sa_rate']);
				$update_array['sa_sort'] = intval($_POST['sa_sort']);
				$update_array['sa_areaid'] = intval($_POST['area_id']);//增加区域关联
				$update_array['sa_areaname'] = $_POST['area_info'];//增加区域关联
				$result = $model_class->editSalesArea($update_array,array('sa_id'=>intval($_POST['sa_id'])));
				if ($result){
					$this->log(L('nc_edit,sales_area').'['.$_POST['sa_name'].']',1);
					showMessage($lang['nc_common_save_succ'],'index.php?gct=sales_area&gp=sales_area');
				}else {
					showMessage($lang['nc_common_save_fail']);
				}
			}
		}

		$area_array = $model_class->getSalesAreaInfo(array('sa_id'=>intval($_GET['sa_id'])));
		if (empty($area_array)){
			showMessage($lang['illegal_parameter']);
		}
		
		$model_member = Model('member');
		$where = array();
		$where['sa_id'] = intval($_GET["sa_id"]);
		$manager_list = $model_member->getMemberList($where,'',50);

		
		Tpl::output('manager_list',$manager_list);

		Tpl::output('area_array',$area_array);
		Tpl::showpage('erwei_qu_member.edit');
	}

	/**
	 * 删除销售区域
	 */
	public function sales_area_delOp(){
		$lang	= Language::getLangContent();
		$model_class = Model('sales_area');
		if (intval($_GET['sa_id']) > 0){
			$array = array(intval($_GET['sa_id']));
			$result = $model_class->delSalesArea(array('sa_id'=>intval($_GET['sa_id'])));
			if ($result) {
			     $this->log(L('nc_del,sales_area').'[ID:'.$_GET['sa_id'].']',1);
			     showMessage($lang['nc_common_del_succ'],getReferer());
			}
		}
		showMessage($lang['nc_common_del_fail'],'index.php?gct=erwei_qu_member&gp=sales_area');
	}

	/**
	 * ajax操作
	 */
	public function ajaxOp(){
		switch ($_GET['branch']){
			//添加、修改操作中 检测类别名称是否有重复
			case 'check_area_name':
				$model_area = Model('sales_area');
				$condition['sa_name'] = trim($_GET['sa_name']);
				$condition['sa_id'] = array('neq',intval($_GET['sa_id']));
				$area_list = $model_area->getSalesAreaInfo($condition);
				if(empty($area_list)){
					echo 'true';exit;
				}else{
					echo 'false';exit;
				}
				break;
			//排序 显示 设置
			case 'sales_area_sort':
				$model_class = Model('sales_area');
				$update_array['sa_sort'] = intval($_GET['value']);
				$result = $model_class->editSalesArea($update_array,array('sa_id'=>intval($_GET['id'])));
				$return = $result ? 'true' : 'false';
				break;
		}
	}

	public function orderOp(){
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
		$if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_time']);
		$if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_time']);
		$start_unixtime = $if_start_time ? strtotime($_GET['query_start_time']) : null;
		$end_unixtime = $if_end_time ? strtotime($_GET['query_end_time']): null;
		if ($start_unixtime || $end_unixtime) {
			$condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
		}//var_dump($condition);

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

		$order_list = $model_order->getOrderList($condition, 100, '*', 'order_id desc','', array('order_goods','order_common','member'));

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

		}
		if(!$_GET['area_id']){
			$order_list = null;
		}

//		var_dump($order_list);die;
		//显示支付接口列表(搜索)
		$payment_list = Model('payment')->getPaymentOpenList();
		Tpl::output('payment_list',$payment_list);

		Tpl::output('order_list',$order_list);
		Tpl::output('show_page',$model_order->showpage());
		Tpl::showpage('area_order');
	}

	public function achievementOp(){
		$condition_arr = array();
		$condition_arr['sc_membername_like'] = trim($_GET['mname']);
		$condition_arr['sc_adminname_like'] = trim($_GET['aname']);
		if ($_GET['stage']){
			$condition_arr['sc_stage'] = trim($_GET['stage']);
		}
		$condition_arr['saddtime'] = strtotime($_GET['stime']);
		$condition_arr['eaddtime'] = strtotime($_GET['etime']);
		if($condition_arr['eaddtime'] > 0) {
			$condition_arr['eaddtime'] += 86400;
		}
		$condition_arr['sc_desc_like'] = trim($_GET['description']);
		//分页
		$page	= new Page();
		$page->setEachNum(15);
		$page->setStyle('admin');
		//查询
		$salescredit_model = Model('salescredit');
		$list_log = $salescredit_model->getsalescreditlogList($condition_arr,$page,'*','');
		//信息输出
		Tpl::output('show_page',$page->show());
		Tpl::output('list_log',$list_log);
		Tpl::showpage('erwei_qu_member_achievement');
	}




}
