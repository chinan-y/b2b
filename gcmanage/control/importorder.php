<?php
/**
 * 录入订单信息管理
 *
 *
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');
class importorderControl extends SystemControl{
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
        $condition = array();
		if($_GET['out_trade_no']) {
        	$condition['out_trade_no'] = $_GET['out_trade_no'];
        }
		if($_GET['partner_id']) {
        	$condition['partner_id'] = $_GET['partner_id'];
        }
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
			$order_id['order_id']=$order_common->table('order_common')->field('order_id')->where($condition_common)->order('order_id desc')->select();

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

        $order_list = $model_order->getOrderList($condition,20, '*', 'order_id desc','', array('order_goods','order_common','member','partner'));
        $list = $model_order->getOrderList('partner_id>0',20, '*', 'order_id desc','', array('order_goods','order_common','member','partner'));
 
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
			
			$resource_place=$model_order->table('partner')->where(array('partner_id'=>$order_info['partner_id']))->select();
			$order_info['partner_name']=$resource_place[0]['partner_name'];

/*         	foreach ($order_info['extend_order_goods'] as $value) {
        	    $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
        	    $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
        	    $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
        	    $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
        	    if ($value['goods_type'] == 5) {
        	        $order_info['zengpin_list'][] = $value;
        	    } else {
        	        $order_info['goods_list'][] = $value;
        	    }
        	} */

        	if (empty($order_info['zengpin_list'])) {
        	    $order_info['goods_count'] = count($order_info['goods_list']);
        	} else {
        	    $order_info['goods_count'] = count($order_info['goods_list']) + 1;
        	}
        	$order_list[$key] = $order_info;

        }
		$address_class = Model();
		$area_parent_id['area_parent_id']=0;
		$address_info=$address_class->table('area')->where(array('area_parent_id'=>area_parent_id))->select();
		Tpl::output('address_info',$address_info);		
		
		$model_partner = Model('partner');
		$partner_name=$model_partner->getPartnerList();
		Tpl::output('partner_name',$partner_name);
		//显示支付接口列表(搜索)
        $payment_list = Model('payment')->getPaymentOpenList();
        Tpl::output('payment_list',$payment_list);
        Tpl::output('order_list',$order_list);
		Tpl::output('list',$list);
        Tpl::output('show_page',$model_order->showpage());
        Tpl::showpage('importorder.index');

	}
	public function getOp(){
			$address_class = Model();
			$data=$address_class->table('area')->where(array('area_parent_id'=>$_POST['node']))->select();
            echo json_encode($data);
    }
	
	public function showcityOp(){
			$address_class = Model();
			$data=$address_class->table('area')->where(array('area_parent_id'=>$_POST['code']))->select();
            echo json_encode($data);
    }

    /**
     * 手动录入订单信息
     * 
     */
	public function addOp(){
		
		/*
		*
		*手机号码作为唯一判断条件，无则注册会员信息，有则调用已有会员信息
		*在传输订单或导入或录入订单之前，平台合作方已存在于系统中
		*
		*/
		
		if($_POST['form_submit']=='ok'){
		
		if($_POST['saleplat_id']=='' ||$_POST['buyer_member_mobile']=='' || $_POST['buyer_member_truename']=='' ||	$_POST['buyer_member_code']=='' || 	$_POST['out_trade_no']==''|| $_POST['pay_sn']==''  || $_POST['order_amount']=='' || $_POST['goods_serials_1']=='' || $_POST['goods_num_1']=='' || $_POST['recevicer_name']=='' ||	$_POST['receiver_mobile']=='' || $_POST['receiver_address']=='' || 	$_POST['address1']=='' || $_POST['address2']=='' ||	$_POST['address3']=='' ){
			showMessage('订单信息录入不完整！','index.php?gct=importorder','html','error');
		} 

		
		//会员信息
		$model_member = Model('member');

		//用手机号码判断用户是否已经注册
		$condition_member = array();
		if($_POST['buyer_member_mobile']){
		$condition_member['member_mobile'] = trim($_POST['buyer_member_mobile']);
		}
		$memberinfo = $model_member -> getMemberInfo($condition_member,'*');
		//用户不存在，则注册新会员
		if(!$memberinfo){
			$condition_email = array();
			//如果填了email,判断email是否存在，存在则退出
			if($_POST['buyer_member_email'] && $_POST['buyer_member_email']!=''){
				$condition_email['member_email'] = trim($_POST['buyer_member_email']);
				$is_email = $model_member -> getMemberInfo($condition_email,'*');
				if($is_email){
					showMessage('Email已存在','index.php?gct=importorder','html','error');
				}
			}
			
			//平台合作方
			$model_partner = Model('partner');
			$condition_partner = array();
			if($_POST['saleplat_id']){
				$condition_partner['partner_id'] = trim($_POST['saleplat_id']);
			}
			$partnerinfo = $model_partner -> getPartnerInfo($condition_partner,'*');

			//如果平台合作方不存在
			if(!$partnerinfo){
				showMessage('数据逻辑错误！','index.php?gct=importorder','html','error');
			}
			$partner_manager = $model_member -> getMemberInfo(array('member_id' => $partnerinfo['member_id']),'*');//新注册用户获取平台合作方的管理者member信息
			
			//将会员信息加入到member表
			$condition_member['member_name'] 		= 'ogc'.TIMESTAMP.rand(100,999);
			$condition_member['member_nickname'] 	= $_POST['buyer_member_nickname'];
			$condition_member['member_mobile'] 		= trim($_POST['buyer_member_mobile']);
			$condition_member['member_email'] 		= $_POST['buyer_member_email'];
			$condition_member['member_truename']	= trim($_POST['buyer_member_truename']);
			$condition_member['member_code'] 		= trim($_POST['buyer_member_code']);
			$condition_member['member_passwd']		= md5(trim($_POST['buyer_member_mobile']));//手机号码作为登录密码
			$condition_member['member_time']		= TIMESTAMP;
			$condition_member['member_login_time']	= TIMESTAMP;
			$condition_member['member_old_login_time']= TIMESTAMP;
			$condition_member['member_login_ip']	= getip();
			$condition_member['member_old_login_ip']= getip();
			$condition_member['is_rebate']			= 1;
			$condition_member['is_seller']			= 0;
			$condition_member['refer_id']			= $partnerinfo['member_id'];
			$condition_member['saleplat_id']		= $_POST['saleplat_id'];

			$adduser = $model_member -> insert($condition_member);
			$adduser_common = Model()->table('member_common')->insert(array('member_id'=>$adduser));	
			$adduser_super = Model()->table('member_superior')->insert(array('member_id'=>$adduser,'one_id'=>$partnerinfo['member_id']));	
			if(!$adduser){
				showMessage('增加新用户失败','index.php?gct=importorder','html','error');
				}
			if(!$adduser_common){
				showMessage('增加新用户失败','index.php?gct=importorder','html','error');
				}
			if(!$adduser_super){
				showMessage('增加新用户推荐人表失败','index.php?gct=importorder','html','error');
				}
				
			$memberinfo = $model_member -> getMemberInfo(array('member_id' =>$adduser),'*');
			}
		else{
			//平台合作方
			$model_partner = Model('partner');
			//已注册用户通过手机号码获取平台合作方的管理者member信息
			$member_saleplat = $model_member -> getMemberInfo(array('member_mobile' =>$condition_member['member_mobile']),'*');			
			$partnerinfo = $model_partner -> getPartnerInfo(array('partner_id'=>$member_saleplat['saleplat_id']),'*');
			$partner_manager = $model_member -> getMemberInfo(array('member_id' =>$partnerinfo['member_id']),'*');
			}
			
		
		//获取并处理商品信息
		$model_goods = Model('goods');
		if($_POST['goods_serials_1']){
			$goods_info_1 = $model_goods -> getGoodsOnlineList(array('goods_serial'=>trim($_POST['goods_serials_1'])),'*');
			if(trim($_POST['goods_num_1']) > $goods_info_1[0]['goods_storage']){
				showMessage('编码为'.$goods_info_1[0]['goods_serial'].'的商品['.$goods_info_1[0]['goods_name'].']库存不足','index.php?gct=importorder','html','error');
			}
			$goods_counts = 1;
		}
		if($_POST['goods_serials_2']){
			$goods_info_2 = $model_goods -> getGoodsOnlineList(array('goods_serial'=>trim($_POST['goods_serials_2'])),'*');
			if(trim($_POST['goods_num_2']) > $goods_info_2[0]['goods_storage']){
				showMessage('编码为'.$goods_info_2[0]['goods_serial'].'的商品['.$goods_info_2[0]['goods_name'].']库存不足','index.php?gct=importorder','html','error');
			}
			$goods_counts++;
		}
		if($_POST['goods_serials_3']){
			$goods_info_3 = $model_goods -> getGoodsOnlineList(array('goods_serial'=>trim($_POST['goods_serials_3'])),'*');
			if(trim($_POST['goods_num_3']) > $goods_info_3[0]['goods_storage']){
				showMessage('编码为'.$goods_info_3[0]['goods_serial'].'的商品['.$goods_info_3[0]['goods_name'].']库存不足','index.php?gct=importorder','html','error');
			}
			$goods_counts++;
		}
		if($_POST['goods_serials_4']){
			$goods_info_4 = $model_goods -> getGoodsOnlineList(array('goods_serial'=>trim($_POST['goods_serials_4'])),'*');
			if(trim($_POST['goods_num_4']) > $goods_info_4[0]['goods_storage']){
				showMessage('编码为'.$goods_info_4[0]['goods_serial'].'的商品['.$goods_info_4[0]['goods_name'].']库存不足','index.php?gct=importorder','html','error');
			}
			$goods_counts++;
		}
		if($_POST['goods_serials_5']){
			$goods_info_5 = $model_goods -> getGoodsOnlineList(array('goods_serial'=>trim($_POST['goods_serials_5'])),'*');
			if(trim($_POST['goods_num_5']) > $goods_info_5[0]['goods_storage']){
				showMessage('编码为'.$goods_info_5[0]['goods_serial'].'的商品['.$goods_info_5[0]['goods_name'].']库存不足','index.php?gct=importorder','html','error');
			}
			$goods_counts++;
		}
		//判断商品是不是同一店铺或仓库商品		
		if($_POST['goods_serials_2']){
			if($goods_info_1[0]['store_id']!=$goods_info_2[0]['store_id']){
				showMessage('商品不是同一发货仓库商品！','','html','error');
			}
		}
		if($_POST['goods_serials_3']){
			if($goods_info_1[0]['store_id']!=$goods_info_3[0]['store_id']){
				showMessage('商品不是同一发货仓库商品！','','html','error');
			}
		}
		if($_POST['goods_serials_4']){
			if($goods_info_1[0]['store_id']!=$goods_info_4[0]['store_id']){
				showMessage('商品不是同一发货仓库商品！','','html','error');
			}
		}
		if($_POST['goods_serials_5']){
			if($goods_info_1[0]['store_id']!=$goods_info_5[0]['store_id']){
				showMessage('商品不是同一发货仓库商品！','','html','error');
			}
		}

		
		$is_rebate = C('salescredit_isuse'); 		//是否开启返利模式
		$is_one = C('one_rank_rebate'); 			//是否开启一级返利
		$is_two = C('two_rank_rebate'); 			//是否开启二级返利
		$is_three = C('three_rank_rebate'); 		//是否开启三级返利
			
		$rate = C('salescredit_rebate') / 100; 		//自身返利率
		$one_rate = C('one_rebate_rate') / 100; 	//一级返利率
		$two_rate = C('two_rebate_rate') / 100; 	//二级返利率
		$three_rate = C('three_rebate_rate') / 100; //三级返利率
		

		//录入订单信息处理开始	
		//order表录入
		$model_order = Model('order');
		
		$condition_order = array();
		$condition_order['out_trade_no'] 		= trim($_POST['out_trade_no']);
		$condition_order['pay_sn'] 				= trim($_POST['pay_sn']);   	// out_order_no =pay_sn
		$condition_order['store_id'] 			= $goods_info_1[0]['store_id']; 
		$condition_order['store_name'] 			= $goods_info_1[0]['store_name']; 
		$condition_order['buyer_id'] 			= $memberinfo['member_id'];
		$condition_order['buyer_name'] 			= $memberinfo['member_name'];
		$condition_order['buyer_reciver_idnum'] = $memberinfo['member_code'];
		$condition_order['add_time'] 			= TIMESTAMP;
		$condition_order['shipping_fee']		= trim($_POST['shipping_fee']);  //订单运费
		$condition_order['order_amount'] 		= trim($_POST['order_amount']);//订单总价格,含税金,也是支付金额 order_amount =order_pay_price
		
		
		//初始化订单order表上商品相关计算金额，待分录商品计算完成后更新
		$condition_order['goods_amount'] 		= 0;
		$condition_order['order_tax'] 			= 0;  
		$condition_order['goods_rebate_amount'] = 0;		
		$condition_order['one_rebate'] 			= 0;
		$condition_order['two_rebate'] 			= 0;
		$condition_order['three_rebate'] 		= 0;
		$condition_order['platform_rebate'] 	= 0;
	
		
		$condition_order['platform_member_id'] 	= $partnerinfo['member_id'];
		$condition_order['partner_id'] 			= $partnerinfo['partner_id'];
		$condition_order['order_distinguish'] 	= '1'; 	//0是个人返利,1是商品返利,2其它
		if(!$_POST['pay_trade_no']){					//如果不存在第三方支付平台流水号
			$condition_order['order_state'] 	= '10'; //默认 10 未支付 
			$condition_order['payment_code'] 	= 'online'; //默认 在线支付 
			
			$conditon_order_pay = array();
			$conditon_order_pay['pay_sn'] 		= $_POST['pay_sn'];
			$conditon_order_pay['buyer_id'] 	= $memberinfo['member_id'];
			$conditon_order_pay['api_pay_state']='0';
			$order_pay = $model_order -> addOrderPay($conditon_order_pay);
			if(!$order_pay){
				showMessage('订单支付表增加数据失败','index.php?gct=importorder','html','error');
				}
		}else{
			$condition_order['order_state'] 	= '20'; 	//默认 10 未支付 20 已付款
			$condition_order['payment_code'] 	= $_POST['payment_code'];
			$condition_order['payment_time'] 	= TIMESTAMP;
			$condition_order['delay_time'] 		= TIMESTAMP;
			$condition_order['trade_no'] 		= $_POST['pay_trade_no'];
			
			$conditon_order_pay = array();
			$conditon_order_pay['pay_sn'] 		= $_POST['pay_sn'];
			$conditon_order_pay['buyer_id'] 	= $memberinfo['member_id'];
			$conditon_order_pay['api_pay_state']='1';
			$order_pay = $model_order -> addOrderPay($conditon_order_pay);
			
			if(!$order_pay){
				showMessage('订单支付表增加数据失败','index.php?gct=importorder','html','error');
				}
		}
		$condition_order['order_sn'] 			= $this->makeOrderSn($order_pay);

		$is_order = $model_order -> getOrderInfo(array('out_trade_no' => $condition_order['out_trade_no']));
		if($is_order){
			//如果三方订单好存在则退出
			showMessage('三方订单已存在！');
		}else{
			//如果三方订单不存在，则插入order表
			$insert_order = $model_order -> addOrder($condition_order);
			if(!$insert_order){
				showMessage('增加订单表失败','index.php?gct=importorder','html','error');
				}
			if($insert_order){
				if($_POST['pay_trade_no']){
					$data = array();
					$data['order_id'] = $insert_order;
					$data['log_role'] = '系统';
					$data['log_user'] = 'System';
					$data['log_msg'] = '收到了货款 ( ['.$_POST['payment_code'].']支付平台交易号 : '.$_POST['pay_trade_no'].' )';
					$data['log_orderstate'] =20;
					$data['log_time'] = TIMESTAMP;
					$model_order->table('order_log')->insert($data);
				}
			}
		}
		$order_info = $model_order -> getOrderInfo(array('order_id' => $insert_order));
		//var_dump($order_info);
		
		//收货人区域地址信息
		$area_address = Model('area');
		$area_condtion = array();
		$area_condtion['area_id'] 		= trim($_POST['address1']);
		$provinceinfo = $area_address -> getAreaInfo($area_condtion);
		$area_condtion['area_id']		= trim($_POST['address2']);
		$cityinfo 	= $area_address -> getAreaInfo($area_condtion);
		$area_condtion['area_id']		= trim($_POST['address3']);
		$areainfo 	= $area_address -> getAreaInfo($area_condtion);
		$areainfo = $provinceinfo['area_name'] . $cityinfo['area_name'] . $areainfo['area_name'] ;
		
		
		//收货人信息
		$user_address = Model('address');
		$con_address = array();
		$con_address['member_id']		 = $memberinfo['member_id'];
		$con_address['true_name']		 = trim($_POST['recevicer_name']);
		$con_address['area_id'] 		 = trim($_POST['address3']);
		$con_address['city_id']			 = trim($_POST['address2']);
		$con_address['area_info']		 = $areainfo;
		$con_address['address']			 = trim($_POST['receiver_address']);
		$con_address['mob_phone']		 = trim($_POST['receiver_mobile']) ? trim($_POST['receiver_mobile']) : $memberinfo['receiver_mobile'];
		$con_address['is_default']		 = '0';							//1默认，0为不默认地址
		
		//用户id,收货人姓名、收货人手机和地址一样则地址表不增加
		$is_address = $user_address -> getAddressInfo(array('member_id'=>$memberinfo['member_id'],'true_name'=>trim($_POST['recevicer_name']),'mob_phone'=>trim($_POST['receiver_mobile']),'address'=>trim($_POST['receiver_address'])));
		if(!$is_address){
		$inser_address = $user_address -> addAddress($con_address);
		}

		
		$rec_info = array();
        $rec_info['phone'] = $con_address['mob_phone'];
        $rec_info['mob_phone'] = $con_address['mob_phone'];
        $rec_info['address'] = $areainfo.' '.trim($_POST['receiver_address']);
        $rec_info['area'] = $areainfo;
		$rec_info['street'] = trim($_POST['receiver_address']);
        $rec_info = serialize($rec_info);	//收货人信息序列化
		
		
		
		//order_common表录入  addOrderCommon
		$condition_ordercommon = array();
		$condition_ordercommon['order_id'] 				= $order_info['order_id'];
		$condition_ordercommon['store_id'] 				= $goods_info_1[0]['store_id'];
		if($order_info['order_state']=='20'){	//当订单状态为已付款时
			$condition_ordercommon['shipping_time'] 		= TIMESTAMP;						//0未发货
			$condition_ordercommon['shipping_express_id'] 	= 41;						//配送公司ID  41 yunda
			$shipping_code = logic('order') -> getYundaBillno($order_info['order_sn']);	//获取韵达单号
			//$shipping_code ='101010101';
			$update_order = $model_order -> editOrder(array('shipping_code'=>$shipping_code['mail_no'],'order_state'=>30),array('order_id'=>$order_info['order_id'])); //更新order表快递单号
			
			if($shipping_code['mail_no']){
				$data = array();
				$data['order_id'] = $insert_order;
				$data['log_role'] = '系统';
				$data['log_user'] = 'System';
				$data['log_msg'] = '自动获取物流单号设置了发货';
				$data['log_orderstate'] =30;
				$data['log_time'] = TIMESTAMP;
				$model_order->table('order_log')->insert($data);
			}
		}
		else{
			$condition_ordercommon['shipping_time'] 		= '0';						//0未发货
			$condition_ordercommon['shipping_express_id'] 	= 0;						//配送公司ID  8 ems
		}
		$condition_ordercommon['reciver_name'] 			= trim($_POST['recevicer_name']);
		$condition_ordercommon['order_name'] 			= trim($_POST['buyer_member_truename']) ? trim($_POST['buyer_member_truename']) : $memberinfo['member_truename'];
		$condition_ordercommon['reciver_idnum'] 		= trim($_POST['buyer_member_code']) ? trim($_POST['buyer_member_code']) : $memberinfo['member_code'];
		$condition_ordercommon['reciver_info'] 			= $rec_info;
		$condition_ordercommon['reciver_province_id'] 	= trim($_POST['address1']);
		$condition_ordercommon['reciver_city_id'] 		= trim($_POST['address2']);
		$condition_ordercommon['reciver_area_id'] 		= trim($_POST['address3']);
		$condition_ordercommon['invoice_info'] 			= 'a:0:{}';							//无发票
		$condition_ordercommon['voucher_price'] 		= trim($_POST['discount_amount']);	//订单优惠金额
		$condition_ordercommon['promotion_info'] 		= trim($_POST['promotion_info']);
		
		$order_common = $model_order -> addOrderCommon($condition_ordercommon);
		if(!$order_common){
			showMessage('增加订单common表失败','index.php?gct=importorder','html','error');
		}
		
		/*
		********************************************************************************************************
		**  跨境电商综合税(T) = 关税(G)*优惠(0)+消费税(C)*优惠(0.7)+增值税(Z)*优惠(0.7)
		**  跨境电商综合税率(Tr) = 0.7(消费税率(Cr)+增值税率(Zr)) / (1-消费税率(Cr))    即  Tr=0.7(Cr+Zr)/(1-Cr)
		**  商品含税价(Ph)  =商品不含税价(P) + 商品不含税价(P) * 跨境电商综合税率(Tr)    即Ph=P(1+Tr)
		**  在已知[商品含税价]的情况下，计算[商品不含税价格]和[商品跨境电商综合税税金]
		**  P = Ph(1-Cr)/(0.7(Cr+Zr)+1-Cr)  =  Ph(1-Cr)/(1-0.3Cr+0.7Zr)
		********************************************************************************************************
		*/
		

		//order_goods表录入 
		//订单第一个商品录入
		if($goods_info_1){
			$condition_ordergoods = array();
			$condition_ordergoods[0]['order_id'] 			= $order_info['order_id'];	
			$condition_ordergoods[0]['goods_id'] 			= $goods_info_1[0]['goods_id'];
			$condition_ordergoods[0]['goods_name'] 			= $goods_info_1[0]['goods_name'];	//商品名称
			$condition_ordergoods[0]['shipping_total'] 		= trim($_POST['shipping_fee']) / $goods_counts;
			
			$con_hscode_tax =array();
			$con_hscode_tax['hs_code'] = $goods_info_1[0]['goods_hscode'];
			$hstax_rate = Model('tax_rate')->getHsTaxInfo($con_hscode_tax,'*');
			$Tr = 0.7 * ($hstax_rate['consumption_tax'] + $hstax_rate['vat_tax']) / (1- $hstax_rate['consumption_tax']);  //计算跨境电商综合税率
			
			if($_POST['goods_price_1']){ //如果是不含税价格
				$condition_ordergoods[0]['goods_price'] 	= trim($_POST['goods_price_1']);	//不含税商品价格
				$condition_ordergoods[0]['goods_pay_price'] = trim($_POST['goods_price_1']) * trim($_POST['goods_num_1']);
				$condition_ordergoods[0]['goods_total'] 	= trim($_POST['goods_price_1']) * trim($_POST['goods_num_1']);
				$condition_ordergoods[0]['taxes_total'] 	= (trim($_POST['goods_price_1']) * trim($_POST['goods_num_1']) + trim($_POST['shipping_fee'])/ $goods_counts)* $Tr;

				$goods_amount_sum1 		= trim($_POST['goods_price_1']) * trim($_POST['goods_num_1']);
				$goods_taxamount_sum1 	= (trim($_POST['goods_price_1']) * trim($_POST['goods_num_1']) + trim($_POST['shipping_fee'])/ $goods_counts)* $Tr;
			}
			if($_POST['goods_intax_price_1']){  //如果是含税价格
				$condition_ordergoods[0]['goods_price'] 	= ncPriceFormat(trim($_POST['goods_intax_price_1'])/(1+$Tr));	//通过含税价格计算不含税商品价格
				$condition_ordergoods[0]['goods_pay_price'] = ncPriceFormat(trim($_POST['goods_intax_price_1'])/(1+$Tr)) * trim($_POST['goods_num_1']);	
				$condition_ordergoods[0]['goods_total'] 	= ncPriceFormat(trim($_POST['goods_intax_price_1'])/(1+$Tr)) * trim($_POST['goods_num_1']);
				$condition_ordergoods[0]['taxes_total'] 	= ncPriceFormat((trim($_POST['goods_intax_price_1']) * trim($_POST['goods_num_1']) + trim($_POST['shipping_fee'])/ $goods_counts)/(1+$Tr)* $Tr);
				
				$goods_amount_sum1 		= trim($_POST['goods_intax_price_1'])/(1+$Tr) * trim($_POST['goods_num_1']);
				$goods_taxamount_sum1 	= (trim($_POST['goods_intax_price_1']) * trim($_POST['goods_num_1']) + trim($_POST['shipping_fee'])/ $goods_counts)/(1+$Tr)* $Tr;//（商品税金+运费税金+保费[0]）
				$goods_shipping_fee1	= trim($_POST['shipping_fee'])/ $goods_counts/(1+$Tr);
			}
			$condition_ordergoods[0]['goods_num'] 			= trim($_POST['goods_num_1']);	//商品数量
			$condition_ordergoods[0]['goods_weight'] 		= $goods_info_1[0]['goods_weight'];	//商品重量
			$condition_ordergoods[0]['goods_taxes'] 		= 0; 							//行邮税
			$condition_ordergoods[0]['goods_image'] 		= $goods_info_1[0]['goods_image'];
			$condition_ordergoods[0]['store_id'] 			= $goods_info_1[0]['store_id'];
			$condition_ordergoods[0]['buyer_id'] 			= $order_info['buyer_id'];
			$condition_ordergoods[0]['goods_type'] 			= '1';   						//1默认2天天特价3超低折扣4组合套装5赠品6满减
			$condition_ordergoods[0]['promotions_id'] 		= '0';							//促销活动ID（团购ID/限时折扣ID/优惠套装ID）与goods_typ
			$condition_ordergoods[0]['commis_rate'] 		= 0;							//佣金比例 getStoreGcidCommisRateList,自营店铺均不去计算
			$condition_ordergoods[0]['gc_id'] 				= $goods_info_1[0]['gc_id'];	//商品最底层分类ID

			$condition_ordergoods[0]['insurance_total']		= 0;							//海关商品保费
			$condition_ordergoods[0]['discount_total'] 		= 0;							//海关商品优惠---待计算
			$condition_ordergoods[0]['mess_country_code'] 	= $goods_info_1[0]['mess_country_code'];
			
			$goods_rebate_amount_sum1=	$goods_amount_sum1 * $goods_info_1[0]['goods_rebate_rate'] * $rate;
			$one_rabte_sum1 		= $goods_amount_sum1 * $goods_info_1[0]['goods_rebate_rate'] * $one_rate;
			$two_rabte_sum1 		= $goods_amount_sum1 * $goods_info_1[0]['goods_rebate_rate'] * $two_rate;
			$three_rabte_sum1 		= $goods_amount_sum1 * $goods_info_1[0]['goods_rebate_rate'] * $three_rate;
			$platform_rebate_sum1 	= $goods_amount_sum1 * $partner_manager['member_rebate_rate'];

			$order_goods_1 = $model_order -> addOrderGoods($condition_ordergoods);
			if(!$order_goods_1){
				showMessage('增加订单商品表失败','index.php?gct=importorder','html','error');
			}
			//订单成功后商品减库存
			$update_storage = Model()->table('goods')->where(array('goods_id'=>$goods_info_1[0]['goods_id']))->setDec('goods_storage',trim($_POST['goods_num_1']));
			//订单成功后商品增加销量
			$update_storage = Model()->table('goods')->where(array('goods_id'=>$goods_info_1[0]['goods_id']))->setInc('goods_salenum',trim($_POST['goods_num_1']));
		}
		
		//订单第二个商品录入
		if($goods_info_2){
			$condition_ordergoods = array();
			$condition_ordergoods[0]['order_id'] 			= $order_info['order_id'];	
			$condition_ordergoods[0]['goods_id'] 			= $goods_info_2[0]['goods_id'];
			$condition_ordergoods[0]['goods_name'] 			= $goods_info_2[0]['goods_name'];	//商品名称
			$condition_ordergoods[0]['shipping_total'] 		= trim($_POST['shipping_fee']) / $goods_counts;
			
			$con_hscode_tax =array();
			$con_hscode_tax['hs_code'] = $goods_info_2[0]['goods_hscode'];
			$hstax_rate = Model('tax_rate')->getHsTaxInfo($con_hscode_tax,'*');
			$Tr = 0.7 * ($hstax_rate['consumption_tax'] + $hstax_rate['vat_tax']) / (1- $hstax_rate['consumption_tax']);  //计算跨境电商综合税率
			
			if($_POST['goods_price_2']){ //如果是不含税价格
				$condition_ordergoods[0]['goods_price'] 	= trim($_POST['goods_price_2']);	//不含税商品价格
				$condition_ordergoods[0]['goods_pay_price'] = trim($_POST['goods_price_2']) * trim($_POST['goods_num_2']);
				$condition_ordergoods[0]['goods_total'] 	= trim($_POST['goods_price_2']) * trim($_POST['goods_num_2']);
				$condition_ordergoods[0]['taxes_total'] 	= (trim($_POST['goods_price_2']) * trim($_POST['goods_num_2']) + trim($_POST['shipping_fee'])/ $goods_counts)* $Tr;

				$goods_amount_sum2 		= trim($_POST['goods_price_2']) * trim($_POST['goods_num_2']);
				$goods_taxamount_sum2 	= (trim($_POST['goods_price_2']) * trim($_POST['goods_num_2']) + trim($_POST['shipping_fee'])/ $goods_counts)* $Tr;
			}
			if($_POST['goods_intax_price_2']){  //如果是含税价格
				$condition_ordergoods[0]['goods_price'] 	= ncPriceFormat(trim($_POST['goods_intax_price_2'])/(1+$Tr));	//通过含税价格计算不含税商品价格
				$condition_ordergoods[0]['goods_pay_price'] = ncPriceFormat(trim($_POST['goods_intax_price_2'])/(1+$Tr)) * trim($_POST['goods_num_1']);	
				$condition_ordergoods[0]['goods_total'] 	= ncPriceFormat(trim($_POST['goods_intax_price_2'])/(1+$Tr)) * trim($_POST['goods_num_1']);
				$condition_ordergoods[0]['taxes_total'] 	= ncPriceFormat((trim($_POST['goods_intax_price_2']) * trim($_POST['goods_num_2']) + trim($_POST['shipping_fee'])/ $goods_counts)/(1+$Tr)* $Tr);
				
				$goods_amount_sum2 		= trim($_POST['goods_intax_price_2'])/(1+$Tr) * trim($_POST['goods_num_2']);
				$goods_taxamount_sum2 	= (trim($_POST['goods_intax_price_2']) * trim($_POST['goods_num_2']) + trim($_POST['shipping_fee'])/ $goods_counts)/(1+$Tr)* $Tr;//（商品税金+运费税金+保费[0]）
				$goods_shipping_fee2	= trim($_POST['shipping_fee'])/ $goods_counts/(1+$Tr);
			}
			$condition_ordergoods[0]['goods_num'] 			= trim($_POST['goods_num_2']);	//商品数量
			$condition_ordergoods[0]['goods_weight'] 		= $goods_info_2[0]['goods_weight'];	//商品重量
			$condition_ordergoods[0]['goods_taxes'] 		= 0; 							//行邮税
			$condition_ordergoods[0]['goods_image'] 		= $goods_info_2[0]['goods_image'];
			$condition_ordergoods[0]['store_id'] 			= $goods_info_2[0]['store_id'];
			$condition_ordergoods[0]['buyer_id'] 			= $order_info['buyer_id'];
			$condition_ordergoods[0]['goods_type'] 			= '1';   						//1默认2天天特价3超低折扣4组合套装5赠品6满减
			$condition_ordergoods[0]['promotions_id'] 		= '0';							//促销活动ID（团购ID/限时折扣ID/优惠套装ID）与goods_typ
			$condition_ordergoods[0]['commis_rate'] 		= 0;							//佣金比例 getStoreGcidCommisRateList,自营店铺均不去计算
			$condition_ordergoods[0]['gc_id'] 				= $goods_info_2[0]['gc_id'];	//商品最底层分类ID

			$condition_ordergoods[0]['insurance_total']		= 0;							//海关商品保费
			$condition_ordergoods[0]['discount_total'] 		= 0;							//海关商品优惠---待计算
			$condition_ordergoods[0]['mess_country_code'] 	= $goods_info_2[0]['mess_country_code'];
			
			$goods_rebate_amount_sum2=	$goods_amount_sum2 * $goods_info_2[0]['goods_rebate_rate'] * $rate;
			$one_rabte_sum2 		= $goods_amount_sum2 * $goods_info_2[0]['goods_rebate_rate'] * $one_rate;
			$two_rabte_sum2 		= $goods_amount_sum2 * $goods_info_2[0]['goods_rebate_rate'] * $two_rate;
			$three_rabte_sum2 		= $goods_amount_sum2 * $goods_info_2[0]['goods_rebate_rate'] * $three_rate;
			$platform_rebate_sum2 	= $goods_amount_sum2 * $partner_manager['member_rebate_rate'];

			$order_goods_2 = $model_order -> addOrderGoods($condition_ordergoods);
			if(!$order_goods_2){
				showMessage('增加订单商品表失败','index.php?gct=importorder','html','error');
			}
			//订单成功后商品减库存
			$update_storage = Model()->table('goods')->where(array('goods_id'=>$goods_info_2[0]['goods_id']))->setDec('goods_storage',trim($_POST['goods_num_2']));
			//订单成功后商品增加销量
			$update_storage = Model()->table('goods')->where(array('goods_id'=>$goods_info_2[0]['goods_id']))->setInc('goods_salenum',trim($_POST['goods_num_2']));
		}
		
		//订单第三个商品录入
		if($goods_info_3){
			$condition_ordergoods = array();
			$condition_ordergoods[0]['order_id'] 			= $order_info['order_id'];	
			$condition_ordergoods[0]['goods_id'] 			= $goods_info_3[0]['goods_id'];
			$condition_ordergoods[0]['goods_name'] 			= $goods_info_3[0]['goods_name'];	//商品名称
			$condition_ordergoods[0]['shipping_total'] 		= trim($_POST['shipping_fee']) / $goods_counts;
			
			$con_hscode_tax =array();
			$con_hscode_tax['hs_code'] = $goods_info_3[0]['goods_hscode'];
			$hstax_rate = Model('tax_rate')->getHsTaxInfo($con_hscode_tax,'*');
			$Tr = 0.7 * ($hstax_rate['consumption_tax'] + $hstax_rate['vat_tax']) / (1- $hstax_rate['consumption_tax']);  //计算跨境电商综合税率
			
			if($_POST['goods_price_3']){ //如果是不含税价格
				$condition_ordergoods[0]['goods_price'] 	= trim($_POST['goods_price_3']);	//不含税商品价格
				$condition_ordergoods[0]['goods_pay_price'] = trim($_POST['goods_price_3']) * trim($_POST['goods_num_3']);	
				$condition_ordergoods[0]['goods_total'] 	= trim($_POST['goods_price_3']) * trim($_POST['goods_num_3']);
				$condition_ordergoods[0]['taxes_total'] 	= (trim($_POST['goods_price_3']) * trim($_POST['goods_num_3']) + trim($_POST['shipping_fee'])/ $goods_counts)* $Tr;

				$goods_amount_sum3 		= trim($_POST['goods_price_3']) * trim($_POST['goods_num_3']);
				$goods_taxamount_sum3 	= (trim($_POST['goods_price_3']) * trim($_POST['goods_num_3']) + trim($_POST['shipping_fee'])/ $goods_counts)* $Tr;
			}
			if($_POST['goods_intax_price_3']){  //如果是含税价格
				$condition_ordergoods[0]['goods_price'] 	= ncPriceFormat(trim($_POST['goods_intax_price_3'])/(1+$Tr));	//通过含税价格计算不含税商品价格
				$condition_ordergoods[0]['goods_pay_price'] = ncPriceFormat(trim($_POST['goods_intax_price_3'])/(1+$Tr)) * trim($_POST['goods_num_1']);	
				$condition_ordergoods[0]['goods_total'] 	= ncPriceFormat(trim($_POST['goods_intax_price_3'])/(1+$Tr)) * trim($_POST['goods_num_1']);
				$condition_ordergoods[0]['taxes_total'] 	= ncPriceFormat((trim($_POST['goods_intax_price_3']) * trim($_POST['goods_num_3']) + trim($_POST['shipping_fee'])/ $goods_counts)/(1+$Tr)* $Tr);
				
				$goods_amount_sum3 		= trim($_POST['goods_intax_price_3'])/(1+$Tr) * trim($_POST['goods_num_3']);
				$goods_taxamount_sum3 	= (trim($_POST['goods_intax_price_3']) * trim($_POST['goods_num_3']) + trim($_POST['shipping_fee'])/ $goods_counts)/(1+$Tr)* $Tr;//（商品税金+运费税金+保费[0]）
				$goods_shipping_fee3	= trim($_POST['shipping_fee'])/ $goods_counts/(1+$Tr);
			}
			$condition_ordergoods[0]['goods_num'] 			= trim($_POST['goods_num_3']);	//商品数量
			$condition_ordergoods[0]['goods_weight'] 		= $goods_info_3[0]['goods_weight'];	//商品重量
			$condition_ordergoods[0]['goods_taxes'] 		= 0; 							//行邮税
			$condition_ordergoods[0]['goods_image'] 		= $goods_info_3[0]['goods_image'];
			$condition_ordergoods[0]['store_id'] 			= $goods_info_3[0]['store_id'];
			$condition_ordergoods[0]['buyer_id'] 			= $order_info['buyer_id'];
			$condition_ordergoods[0]['goods_type'] 			= '1';   						//1默认2天天特价3超低折扣4组合套装5赠品6满减
			$condition_ordergoods[0]['promotions_id'] 		= '0';							//促销活动ID（团购ID/限时折扣ID/优惠套装ID）与goods_typ
			$condition_ordergoods[0]['commis_rate'] 		= 0;							//佣金比例 getStoreGcidCommisRateList,自营店铺均不去计算
			$condition_ordergoods[0]['gc_id'] 				= $goods_info_3[0]['gc_id'];	//商品最底层分类ID

			$condition_ordergoods[0]['insurance_total']		= 0;							//海关商品保费
			$condition_ordergoods[0]['discount_total'] 		= 0;							//海关商品优惠---待计算
			$condition_ordergoods[0]['mess_country_code'] 	= $goods_info_3[0]['mess_country_code'];
			
			$goods_rebate_amount_sum3=	$goods_amount_sum3 * $goods_info_3[0]['goods_rebate_rate'] * $rate;
			$one_rabte_sum3 		= $goods_amount_sum3 * $goods_info_3[0]['goods_rebate_rate'] * $one_rate;
			$two_rabte_sum3 		= $goods_amount_sum3 * $goods_info_3[0]['goods_rebate_rate'] * $two_rate;
			$three_rabte_sum3 		= $goods_amount_sum3 * $goods_info_3[0]['goods_rebate_rate'] * $three_rate;
			$platform_rebate_sum3 	= $goods_amount_sum3 * $partner_manager['member_rebate_rate'];

			$order_goods_3 = $model_order -> addOrderGoods($condition_ordergoods);
			if(!$order_goods_3){
				showMessage('增加订单商品表失败','index.php?gct=importorder','html','error');
			}
			//订单成功后商品减库存
			$update_storage = Model()->table('goods')->where(array('goods_id'=>$goods_info_3[0]['goods_id']))->setDec('goods_storage',trim($_POST['goods_num_3']));
			//订单成功后商品增加销量
			$update_storage = Model()->table('goods')->where(array('goods_id'=>$goods_info_3[0]['goods_id']))->setInc('goods_salenum',trim($_POST['goods_num_3']));
		}
		
		//订单第四个商品录入
		if($goods_info_4){
			$condition_ordergoods = array();
			$condition_ordergoods[0]['order_id'] 			= $order_info['order_id'];	
			$condition_ordergoods[0]['goods_id'] 			= $goods_info_4[0]['goods_id'];
			$condition_ordergoods[0]['goods_name'] 			= $goods_info_4[0]['goods_name'];	//商品名称
			$condition_ordergoods[0]['shipping_total'] 		= trim($_POST['shipping_fee']) / $goods_counts;
			
			$con_hscode_tax =array();
			$con_hscode_tax['hs_code'] = $goods_info_4[0]['goods_hscode'];
			$hstax_rate = Model('tax_rate')->getHsTaxInfo($con_hscode_tax,'*');
			$Tr = 0.7 * ($hstax_rate['consumption_tax'] + $hstax_rate['vat_tax']) / (1- $hstax_rate['consumption_tax']);  //计算跨境电商综合税率
			
			if($_POST['goods_price_4']){ //如果是不含税价格
				$condition_ordergoods[0]['goods_price'] 	= trim($_POST['goods_price_4']);	//不含税商品价格
				$condition_ordergoods[0]['goods_pay_price'] = trim($_POST['goods_price_4']) * trim($_POST['goods_num_4']);
				$condition_ordergoods[0]['goods_total'] 	= trim($_POST['goods_price_4']) * trim($_POST['goods_num_4']);
				$condition_ordergoods[0]['taxes_total'] 	= (trim($_POST['goods_price_4']) * trim($_POST['goods_num_4']) + trim($_POST['shipping_fee'])/ $goods_counts)* $Tr;

				$goods_amount_sum4 		= trim($_POST['goods_price_4']) * trim($_POST['goods_num_4']);
				$goods_taxamount_sum4 	= (trim($_POST['goods_price_4']) * trim($_POST['goods_num_4']) + trim($_POST['shipping_fee'])/ $goods_counts)* $Tr;
			}
			if($_POST['goods_intax_price_4']){  //如果是含税价格
				$condition_ordergoods[0]['goods_price'] 	= ncPriceFormat(trim($_POST['goods_intax_price_4'])/(1+$Tr));	//通过含税价格计算不含税商品价格
				$condition_ordergoods[0]['goods_pay_price'] = ncPriceFormat(trim($_POST['goods_intax_price_4'])/(1+$Tr)) * trim($_POST['goods_num_1']);	
				$condition_ordergoods[0]['goods_total'] 	= ncPriceFormat(trim($_POST['goods_intax_price_4'])/(1+$Tr)) * trim($_POST['goods_num_1']);
				$condition_ordergoods[0]['taxes_total'] 	= ncPriceFormat((trim($_POST['goods_intax_price_4']) * trim($_POST['goods_num_4']) + trim($_POST['shipping_fee'])/ $goods_counts)/(1+$Tr)* $Tr);
				
				$goods_amount_sum4 		= trim($_POST['goods_intax_price_4'])/(1+$Tr) * trim($_POST['goods_num_4']);
				$goods_taxamount_sum4 	= (trim($_POST['goods_intax_price_4']) * trim($_POST['goods_num_4']) + trim($_POST['shipping_fee'])/ $goods_counts)/(1+$Tr)* $Tr;//（商品税金+运费税金+保费[0]）
				$goods_shipping_fee4	= trim($_POST['shipping_fee'])/ $goods_counts/(1+$Tr);
			}
			$condition_ordergoods[0]['goods_num'] 			= trim($_POST['goods_num_4']);	//商品数量
			$condition_ordergoods[0]['goods_weight'] 		= $goods_info_4[0]['goods_weight'];	//商品重量
			$condition_ordergoods[0]['goods_taxes'] 		= 0; 							//行邮税
			$condition_ordergoods[0]['goods_image'] 		= $goods_info_4[0]['goods_image'];
			$condition_ordergoods[0]['store_id'] 			= $goods_info_4[0]['store_id'];
			$condition_ordergoods[0]['buyer_id'] 			= $order_info['buyer_id'];
			$condition_ordergoods[0]['goods_type'] 			= '1';   						//1默认2天天特价3超低折扣4组合套装5赠品6满减
			$condition_ordergoods[0]['promotions_id'] 		= '0';							//促销活动ID（团购ID/限时折扣ID/优惠套装ID）与goods_typ
			$condition_ordergoods[0]['commis_rate'] 		= 0;							//佣金比例 getStoreGcidCommisRateList,自营店铺均不去计算
			$condition_ordergoods[0]['gc_id'] 				= $goods_info_4[0]['gc_id'];	//商品最底层分类ID

			$condition_ordergoods[0]['insurance_total']		= 0;							//海关商品保费
			$condition_ordergoods[0]['discount_total'] 		= 0;							//海关商品优惠---待计算
			$condition_ordergoods[0]['mess_country_code'] 	= $goods_info_4[0]['mess_country_code'];
			
			$goods_rebate_amount_sum4=	$goods_amount_sum4 * $goods_info_4[0]['goods_rebate_rate'] * $rate;
			$one_rabte_sum4 		= $goods_amount_sum4 * $goods_info_4[0]['goods_rebate_rate'] * $one_rate;
			$two_rabte_sum4 		= $goods_amount_sum4 * $goods_info_4[0]['goods_rebate_rate'] * $two_rate;
			$three_rabte_sum4 		= $goods_amount_sum4 * $goods_info_4[0]['goods_rebate_rate'] * $three_rate;
			$platform_rebate_sum4 	= $goods_amount_sum4 * $partner_manager['member_rebate_rate'];

			$order_goods_4 = $model_order -> addOrderGoods($condition_ordergoods);
			if(!$order_goods_4){
				showMessage('增加订单商品表失败','index.php?gct=importorder','html','error');
			}
			//订单成功后商品减库存
			$update_storage = Model()->table('goods')->where(array('goods_id'=>$goods_info_4[0]['goods_id']))->setDec('goods_storage',trim($_POST['goods_num_4']));
			//订单成功后商品增加销量
			$update_storage = Model()->table('goods')->where(array('goods_id'=>$goods_info_4[0]['goods_id']))->setInc('goods_salenum',trim($_POST['goods_num_4']));
		}
		
		//订单第五个商品录入
		if($goods_info_5){
			$condition_ordergoods = array();
			$condition_ordergoods[0]['order_id'] 			= $order_info['order_id'];	
			$condition_ordergoods[0]['goods_id'] 			= $goods_info_5[0]['goods_id'];
			$condition_ordergoods[0]['goods_name'] 			= $goods_info_5[0]['goods_name'];	//商品名称
			$condition_ordergoods[0]['shipping_total'] 		= trim($_POST['shipping_fee']) / $goods_counts;
			
			$con_hscode_tax =array();
			$con_hscode_tax['hs_code'] = $goods_info_5[0]['goods_hscode'];
			$hstax_rate = Model('tax_rate')->getHsTaxInfo($con_hscode_tax,'*');
			$Tr = 0.7 * ($hstax_rate['consumption_tax'] + $hstax_rate['vat_tax']) / (1- $hstax_rate['consumption_tax']);  //计算跨境电商综合税率
			
			if($_POST['goods_price_5']){ //如果是不含税价格
				$condition_ordergoods[0]['goods_price'] 	= trim($_POST['goods_price_5']);	//不含税商品价格
				$condition_ordergoods[0]['goods_pay_price'] = trim($_POST['goods_price_5']) * trim($_POST['goods_num_5']);	
				$condition_ordergoods[0]['goods_total'] 	= trim($_POST['goods_price_5']) * trim($_POST['goods_num_5']);
				$condition_ordergoods[0]['taxes_total'] 	= (trim($_POST['goods_price_5']) * trim($_POST['goods_num_5']) + trim($_POST['shipping_fee'])/ $goods_counts)* $Tr;

				$goods_amount_sum5 		= trim($_POST['goods_price_5']) * trim($_POST['goods_num_5']);
				$goods_taxamount_sum5 	= (trim($_POST['goods_price_5']) * trim($_POST['goods_num_5']) + trim($_POST['shipping_fee'])/ $goods_counts)* $Tr;
			}
			if($_POST['goods_intax_price_5']){  //如果是含税价格
				$condition_ordergoods[0]['goods_price'] 	= ncPriceFormat(trim($_POST['goods_intax_price_5'])/(1+$Tr));	//通过含税价格计算不含税商品价格
				$condition_ordergoods[0]['goods_pay_price'] = ncPriceFormat(trim($_POST['goods_intax_price_5'])/(1+$Tr)) * trim($_POST['goods_num_1']);	
				$condition_ordergoods[0]['goods_total'] 	= ncPriceFormat(trim($_POST['goods_intax_price_5'])/(1+$Tr)) * trim($_POST['goods_num_1']);
				$condition_ordergoods[0]['taxes_total'] 	= ncPriceFormat((trim($_POST['goods_intax_price_5']) * trim($_POST['goods_num_5']) + trim($_POST['shipping_fee'])/ $goods_counts)/(1+$Tr)* $Tr);
				
				$goods_amount_sum5 		= trim($_POST['goods_intax_price_5'])/(1+$Tr) * trim($_POST['goods_num_5']);
				$goods_taxamount_sum5 	= (trim($_POST['goods_intax_price_5']) * trim($_POST['goods_num_5']) + trim($_POST['shipping_fee'])/ $goods_counts)/(1+$Tr)* $Tr;//（商品税金+运费税金+保费[0]）
				$goods_shipping_fee5	= trim($_POST['shipping_fee'])/ $goods_counts/(1+$Tr);
			}
			$condition_ordergoods[0]['goods_num'] 			= trim($_POST['goods_num_5']);	//商品数量
			$condition_ordergoods[0]['goods_weight'] 		= $goods_info_5[0]['goods_weight'];	//商品重量
			$condition_ordergoods[0]['goods_taxes'] 		= 0; 							//行邮税
			$condition_ordergoods[0]['goods_image'] 		= $goods_info_5[0]['goods_image'];
			$condition_ordergoods[0]['store_id'] 			= $goods_info_5[0]['store_id'];
			$condition_ordergoods[0]['buyer_id'] 			= $order_info['buyer_id'];
			$condition_ordergoods[0]['goods_type'] 			= '1';   						//1默认2天天特价3超低折扣4组合套装5赠品6满减
			$condition_ordergoods[0]['promotions_id'] 		= '0';							//促销活动ID（团购ID/限时折扣ID/优惠套装ID）与goods_typ
			$condition_ordergoods[0]['commis_rate'] 		= 0;							//佣金比例 getStoreGcidCommisRateList,自营店铺均不去计算
			$condition_ordergoods[0]['gc_id'] 				= $goods_info_5[0]['gc_id'];	//商品最底层分类ID

			$condition_ordergoods[0]['insurance_total']		= 0;							//海关商品保费
			$condition_ordergoods[0]['discount_total'] 		= 0;							//海关商品优惠---待计算
			$condition_ordergoods[0]['mess_country_code'] 	= $goods_info_5[0]['mess_country_code'];
			
			$goods_rebate_amount_sum5=	$goods_amount_sum5 * $goods_info_5[0]['goods_rebate_rate'] * $rate;
			$one_rabte_sum5 		= $goods_amount_sum5 * $goods_info_5[0]['goods_rebate_rate'] * $one_rate;
			$two_rabte_sum5 		= $goods_amount_sum5 * $goods_info_5[0]['goods_rebate_rate'] * $two_rate;
			$three_rabte_sum5 		= $goods_amount_sum5 * $goods_info_5[0]['goods_rebate_rate'] * $three_rate;
			$platform_rebate_sum5 	= $goods_amount_sum5 * $partner_manager['member_rebate_rate'];

			$order_goods_5 = $model_order -> addOrderGoods($condition_ordergoods);
			if(!$order_goods_5){
				showMessage('增加订单商品表失败','index.php?gct=importorder','html','error');
			}
			//订单成功后商品减库存
			$update_storage = Model()->table('goods')->where(array('goods_id'=>$goods_info_5[0]['goods_id']))->setDec('goods_storage',trim($_POST['goods_num_5']));
			//订单成功后商品增加销量
			$update_storage = Model()->table('goods')->where(array('goods_id'=>$goods_info_5[0]['goods_id']))->setInc('goods_salenum',trim($_POST['goods_num_5']));
		}
	
		
		//更新订单order表	$insert_order

		$data_update_order =array();
		$data_update_order['goods_amount'] 		= $goods_amount_sum1 + $goods_amount_sum2 + $goods_amount_sum3 + $goods_amount_sum4 + $goods_amount_sum5;
		$data_update_order['order_tax'] 		= $goods_taxamount_sum1 + $goods_taxamount_sum2 + $goods_taxamount_sum3 + $goods_taxamount_sum4 + $goods_taxamount_sum5; 
		$data_update_order['goods_rebate_amount']= $goods_rebate_amount_sum1 + $goods_rebate_amount_sum2 + $goods_rebate_amount_sum3 + $goods_rebate_amount_sum4 + $goods_rebate_amount_sum5;
		$data_update_order['one_rebate']		= $one_rabte_sum1 + $one_rabte_sum2 + $one_rabte_sum3 + $one_rabte_sum4 + $one_rabte_sum5;
		$data_update_order['two_rebate']		= $two_rabte_sum1 + $two_rabte_sum2 + $two_rabte_sum3 + $two_rabte_sum4 + $two_rabte_sum5;
		$data_update_order['three_rebate']		= $three_rabte_sum1 + $three_rabte_sum2 + $three_rabte_sum3 + $three_rabte_sum4 + $three_rabte_sum5;
		$data_update_order['platform_rebate']	= $platform_rebate_sum1 + $platform_rebate_sum2 + $platform_rebate_sum3 + $platform_rebate_sum4 + $platform_rebate_sum5;
		
		$update_order = $model_order -> editOrder($data_update_order,array('order_id'=>$insert_order));
		if($update_order){
			showMessage('订单增加成功！','index.php?gct=importorder','html','succ');
		}
		
		//更新订单common表
		$uodate_ordercommom_1 = $model_order -> editOrderGoods(array('discount_total'=>trim($_POST['discount_amount'])*$goods_amount_sum1/$data_update_order['goods_amount']),array('rec_id'=>$order_goods_1));
		$uodate_ordercommom_2 = $model_order -> editOrderGoods(array('discount_total'=>trim($_POST['discount_amount'])*$goods_amount_sum2/$data_update_order['goods_amount']),array('rec_id'=>$order_goods_2));
		$uodate_ordercommom_3 = $model_order -> editOrderGoods(array('discount_total'=>trim($_POST['discount_amount'])*$goods_amount_sum3/$data_update_order['goods_amount']),array('rec_id'=>$order_goods_3));
		$uodate_ordercommom_4 = $model_order -> editOrderGoods(array('discount_total'=>trim($_POST['discount_amount'])*$goods_amount_sum4/$data_update_order['goods_amount']),array('rec_id'=>$order_goods_4));
		$uodate_ordercommom_5 = $model_order -> editOrderGoods(array('discount_total'=>trim($_POST['discount_amount'])*$goods_amount_sum5/$data_update_order['goods_amount']),array('rec_id'=>$order_goods_5));
		
		}
		
		$address_class = Model();
		$area_parent_id['area_parent_id']=0;
		$address_info=$address_class->table('area')->where(array('area_parent_id'=>area_parent_id))->select();
		Tpl::output('address_info',$address_info);		
		//平台合作方列表
		$model_partner = Model('partner');
		$partner_name=$model_partner->getPartnerList();
		Tpl::output('partner_name',$partner_name);
		//显示支付接口列表(搜索)
        $payment_list = Model('payment')->getPaymentOpenList();
        Tpl::output('payment_list',$payment_list);
        Tpl::output('order_list',$order_list);
		Tpl::output('list',$list);
        Tpl::showpage('importorder.add');
		
		
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
	 * 导入Excel表
	 *
	 */
	public function export_step1Op(){
		header("Content-type:text/html;charset=utf-8");
		$models=Model();
		include(BASE_PATH.'/excel/reader.php');
		$tmp = $_FILES['file']['tmp_name'];
		if (empty ($tmp)) {
			echo '请选择要导入的Excel文件！';
			exit;
		}
		$save_path = "excel/importExcelfile/";
		$file_name = $save_path.date('YmdHis') . ".xls";
		$data_values=array();
		if (copy($tmp, $file_name)) {
			$xls = new Spreadsheet_Excel_Reader();
			$xls->setOutputEncoding('utf-8');
			$xls->read($file_name);
			for ($i=2; $i<=$xls->sheets[0]['numRows']; $i++) {
				//读取excel
				$resource		= trim($xls->sheets[0]['cells'][$i][1]);		//订单来源平台
				$out_trade_no	= trim($xls->sheets[0]['cells'][$i][2]);		//三方电商平台订单编号
				$pay_sn			= trim($xls->sheets[0]['cells'][$i][3]);		//三方电商平台订单支付交易号
				$trade_no		= trim($xls->sheets[0]['cells'][$i][4]);		//支付平台支付单编号
				$pay_way		= trim($xls->sheets[0]['cells'][$i][5]);		//支付方式
				$order_amount	= trim($xls->sheets[0]['cells'][$i][6]);		//订单金额=支付金额
				$discount_amount= trim($xls->sheets[0]['cells'][$i][7]);		//订单优惠金额
				$shipping_fee	= trim($xls->sheets[0]['cells'][$i][8]);		//运费
				$member_nickname= trim($xls->sheets[0]['cells'][$i][9]);		//订购人在三方电商平台的用户名或昵称
				$member_mobile	= trim($xls->sheets[0]['cells'][$i][10]);		//订购人手机号码
				$buyer_truename	= trim($xls->sheets[0]['cells'][$i][11]);		//订购人真实姓名
				$buyer_idcode	= trim($xls->sheets[0]['cells'][$i][12]);		//订购人身份证号码
				$receiver_name 	= trim($xls->sheets[0]['cells'][$i][13]);		//收件人姓名
				$receiver_phone	= trim($xls->sheets[0]['cells'][$i][14]);		//收件人电话号码
				$address1 		= trim($xls->sheets[0]['cells'][$i][15]);		//收件人省
				$address2		= trim($xls->sheets[0]['cells'][$i][16]);		//收件人市
				$address3		= trim($xls->sheets[0]['cells'][$i][17]);		//收件人区县
				$receiver_address=trim($xls->sheets[0]['cells'][$i][18]);		//收件人详细地址

				$goods_serial	= trim($xls->sheets[0]['cells'][$i][19]);		//商品编码
				$goods_num		= trim($xls->sheets[0]['cells'][$i][20]);		//商品数量
				$goods_price	= trim($xls->sheets[0]['cells'][$i][21]);		//商品成交价格-不含税
                $goods_intax_price= trim($xls->sheets[0]['cells'][$i][22]);		//商品成交价格-包税价格=含税价格
				
				$order_goods_counts= trim($xls->sheets[0]['cells'][$i][23]);	//订单商品个数统计


				//会员注册信息
				$model_member = Model('member');
				$model_partner = Model('partner');
				$is_exist_member 	= $model_member -> getMemberInfo(array('member_mobile'=>$member_mobile));
				if($is_exist_member){
					$member_info 	= $model_member -> getMemberInfo(array('member_mobile'=>$member_mobile));
					$partner_info 	= $model_partner -> getPartnerInfo(array('partner_id'=>$member_info['saleplat_id']));
					$partner_manager = $model_member -> getMemberInfo(array('member_id'=>$partner_info['member_id']));
				
				}
				else{
					$partner_info = $model_partner ->getPartnerInfo(array('partner_name'=> array('like','%'.$resource.'%')));
					$member=array();
					$member['member_name']		= 'ogc'.TIMESTAMP.rand(100,999);
					$member['member_mobile']	= $member_mobile;
					$member['member_passwd']	= md5($member_mobile);
					$member['member_nickname']	= $member_nickname;
					$member['member_truename']	= $buyer_truename;
					$member['member_code']		= $buyer_idcode;
					$member['member_sex']		= 0;
					$member['member_code']		= $buyer_idcode;
					$member['member_mobile_bind']= 0;
					$member['is_rebate']		= 1;
					$member['is_seller']		= 1;
					$member['refer_id']			= $partner_info['member_id'];
					$member['saleplat_id']		= $partner_info['partner_id'];
					$member['is_membername_modify']='1';
				
					$add_member = $model_member -> addMember($member);
					$member_info 	= $model_member -> getMemberInfo(array('member_id'=>$add_member));
					$partner_manager = $model_member -> getMemberInfo(array('member_id'=>$partner_info['member_id']));

					if(!$add_member){
						showMessage('新增用户失败！');
					}
				}
			
			
				//插入订单order表	
				$model_order = Model('order');
					
				$condition_order = array();
				$condition_order['out_trade_no'] 		= $out_trade_no;
				$condition_order['pay_sn'] 				= $pay_sn;   	// out_order_no =pay_sn
				$condition_order['buyer_id'] 			= $member_info['member_id'];
				$condition_order['buyer_name'] 			= $member_info['member_name'];
				$condition_order['buyer_reciver_idnum'] = $member_info['member_code'];
				$condition_order['add_time'] 			= TIMESTAMP;
				$condition_order['shipping_fee']		= $shipping_fee;  //订单运费
				$condition_order['order_amount'] 		= $order_amount;//订单总价格,含税金,也是支付金额 order_amount =order_pay_price
				$condition_order['platform_member_id'] 	= $partner_info['member_id'];
				$condition_order['partner_id'] 			= $partner_info['partner_id'];
				$condition_order['order_distinguish'] 	= '1'; 	//0是个人返利,1是商品返利,2其它
		
				//初始化订单order表上商品相关计算金额，待分录商品计算完成后更新
				$condition_order['store_id'] 			= 0; 
				$condition_order['store_name'] 			= 0; 
				$condition_order['goods_amount'] 		= 0;
				$condition_order['order_tax'] 			= 0;  
				$condition_order['goods_rebate_amount'] = 0;		
				$condition_order['one_rebate'] 			= 0;
				$condition_order['two_rebate'] 			= 0;
				$condition_order['three_rebate'] 		= 0;
				$condition_order['platform_rebate'] 	= $partner_manager['member_rebate_rate'];

	
		
				if(!$trade_no){					//如果不存在第三方支付平台流水号
					$condition_order['order_state'] 	= '10'; //默认 10 未支付 
					$condition_order['payment_code'] 	= 'online'; //默认 在线支付 
			
					$conditon_order_pay = array();
					$conditon_order_pay['pay_sn'] 		= $pay_sn;
					$conditon_order_pay['buyer_id'] 	= $member_info['member_id'];
					$conditon_order_pay['api_pay_state']='0';
					$is_exist_orderpay = $model_order -> getOrderPayList(array('pay_sn'=>$pay_sn));
					if(!$is_exist_orderpay){
						$order_pay = $model_order -> addOrderPay($conditon_order_pay);
						if(!$order_pay){
						showMessage('订单支付表增加数据失败','index.php?gct=importorder','html','error');
						}
					}
				}else{
					$condition_order['order_state'] 	= '20'; 	//默认 10 未支付 20 已付款
					$condition_order['payment_code'] 	= $pay_way;
					$condition_order['payment_time'] 	= TIMESTAMP;
					$condition_order['delay_time'] 		= TIMESTAMP;
					$condition_order['trade_no'] 		= $trade_no;
			
					$conditon_order_pay = array();
					$conditon_order_pay['pay_sn'] 		= $pay_sn;
					$conditon_order_pay['buyer_id'] 	= $member_info['member_id'];
					$conditon_order_pay['api_pay_state']='1';
					$is_exist_orderpay = $model_order -> getOrderPayList(array('pay_sn'=>$pay_sn));
					if(!$is_exist_orderpay){
						$order_pay = $model_order -> addOrderPay($conditon_order_pay);
						if(!$order_pay){
						showMessage('订单支付表增加数据失败','index.php?gct=importorder','html','error');
						}
					}
				}
				$condition_order['order_sn'] 			= $this->makeOrderSn($order_pay);

				$is_order = $model_order -> getOrderInfo(array('out_trade_no' => $condition_order['out_trade_no']));
				if(!$is_order){
					//如果三方订单不存在，则插入order表
					$insert_order = $model_order -> addOrder($condition_order);
					if($insert_order){
						

						$data = array();
						$data['order_id'] = $insert_order;
						$data['log_role'] = '系统';
						$data['log_user'] = 'System';
						$data['log_msg'] = '来自文件' .BASE_PATH.'/'.$file_name.'的订单';
						$data['log_orderstate'] =10;
						$data['log_time'] = TIMESTAMP;
						$model_order->table('order_log')->insert($data);

						
						
						if($trade_no){
							$data = array();
							$data['order_id'] = $insert_order;
							$data['log_role'] = '系统';
							$data['log_user'] = 'System';
							$data['log_msg'] = '收到了货款 ( ['.$pay_way.']支付平台交易号 : '.$trade_no.' )';
							$data['log_orderstate'] =20;
							$data['log_time'] = TIMESTAMP;
							$model_order->table('order_log')->insert($data);
						}
					}else{
						showMessage('订单表增加数据失败','index.php?gct=importorder','html','error');
					}
					
					$order_info = $model_order -> getOrderInfo(array('order_id' => $insert_order));
				}
				else{
					$order_info = $model_order -> getOrderInfo(array('order_id' => $insert_order));
				}
		
		/*
		********************************************************************************************************
		**  跨境电商综合税(T) = 关税(G)*优惠(0)+消费税(C)*优惠(0.7)+增值税(Z)*优惠(0.7)
		**  跨境电商综合税率(Tr) = 0.7(消费税率(Cr)+增值税率(Zr)) / (1-消费税率(Cr))    即  Tr=0.7(Cr+Zr)/(1-Cr)
		**  商品含税价(Ph)  =商品不含税价(P) + 商品不含税价(P) * 跨境电商综合税率(Tr)    即Ph=P(1+Tr)
		**  在已知[商品含税价]的情况下，计算[商品不含税价格]和[商品跨境电商综合税税金]
		**  P = Ph(1-Cr)/(0.7(Cr+Zr)+1-Cr)  =  Ph(1-Cr)/(1-0.3Cr+0.7Zr)
		********************************************************************************************************
		*/
				for($j=1;$j<=$order_goods_counts;$j++){
					$model_goods = Model('goods');
					$goods_info = $model_goods -> getGoodsOnlineList(array('goods_serial'=>$goods_serial));
					$con_hscode_tax =array();
					$con_hscode_tax['hs_code'] = $goods_info[0]['goods_hscode'];
					$hstax_rate = Model('tax_rate')->getHsTaxInfo($con_hscode_tax,'*');
					$Tr = 0.7 * ($hstax_rate['consumption_tax'] + $hstax_rate['vat_tax']) / (1- $hstax_rate['consumption_tax']);  //计算跨境电商综合税率

					//插入订单order_goods表	
					$order_goods = array();
					$order_goods[0]['order_id'] 		= $order_info['order_id'];
					$order_goods[0]['goods_id'] 		= $goods_info[0]['goods_id'];
					$order_goods[0]['goods_name'] 		= $goods_info[0]['goods_name'];
					$order_goods[0]['store_id'] 		= $goods_info[0]['store_id'];
					$order_goods[0]['buyer_id'] 		= $member_info['member_id'];
					$order_goods[0]['goods_num'] 		= $goods_num;
					$order_goods[0]['goods_weight'] 	= $goods_info[0]['goods_weight'];
					$order_goods[0]['goods_image'] 		= $goods_info[0]['goods_image'];
					$order_goods[0]['shipping_total'] 	= ncPriceFormat($shipping_fee / (1+$Tr) / $order_goods_counts);
					$order_goods[0]['discount_total'] 	= ncPriceFormat($discount_amount / $order_goods_counts);
					$order_goods[0]['mess_country_code']=$goods_info[0]['mess_country_code'];
					$order_goods[0]['goods_type'] 		= '1';   						//1默认2天天特价3超低折扣4组合套装5赠品6满减
					$order_goods[0]['promotions_id'] 	= '0';							//促销活动ID（团购ID/限时折扣ID/优惠套装ID）与goods_typ
					$order_goods[0]['commis_rate'] 		= 0;							//佣金比例 getStoreGcidCommisRateList,自营店铺均不去计算
					$order_goods[0]['gc_id'] 			= $goods_info[0]['gc_id'];
		
					if($goods_price){
						$order_goods[0]['goods_price'] 	= $goods_price;
						$order_goods[0]['goods_pay_price'] = $goods_price * $goods_num;
						$order_goods[0]['goods_total'] 	= $goods_price * $goods_num;
						$order_goods[0]['taxes_total'] 	= ($goods_price * $goods_num  + $shipping_fee / $order_goods_counts)* $Tr;
						$goods_rebate	= $goods_price * $goods_num * $goods_info[0]['goods_rebate_rate'];
					}
					if($goods_intax_price){
						$order_goods[0]['goods_price'] 	= ncPriceFormat($goods_intax_price / (1+$Tr));
						$order_goods[0]['goods_pay_price'] = ncPriceFormat($goods_intax_price / (1+$Tr)) * $goods_num;
						$order_goods[0]['goods_total'] 	= ncPriceFormat($goods_intax_price / (1+$Tr)) * $goods_num;
						$order_goods[0]['taxes_total'] 	=  ncPriceFormat(($goods_intax_price * $goods_num  + $shipping_fee / $order_goods_counts )/ (1+$Tr) * $Tr);
						$goods_rebate	= $goods_intax_price / (1+$Tr) * $goods_num * $goods_info[0]['goods_rebate_rate'];
						$goods_shipping_fee	= $shipping_fee / $order_goods_counts / (1+$Tr);
					}
					//订单商品表中存在该订单的商品重复时不在增加
					//查询订单商品表中是否已存在重复商品
					$is_exist_ordergoods = $model_order -> getOrderGoodsInfo(array('order_id'=>$order_info['order_id'],'goods_id'=>$goods_info[0]['goods_id']));
					if(!$is_exist_ordergoods){
						$insert_ordergoods = $model_order -> addOrderGoods($order_goods);
					
						if(!$insert_ordergoods){
							showMessage('订单商品表增加商品失败','index.php?gct=importorder','html','error');
						}else{
							//更新order表的店铺ID和店铺名称，同一订单商品应为同一店铺
							if($order_info['store_id']==0){
								$model_order -> editOrder(array('store_id'=>$goods_info[0]['store_id'],'store_name'=>$goods_info[0]['store_name']),array('order_id'=>$order_info['order_id']));
							}
							//订单成功后商品减库存
							$update_storage = Model()->table('goods')->where(array('goods_id'=>$goods_info[0]['goods_id']))->setDec('goods_storage',$goods_num);
							//订单成功后商品增加销量
							$update_salenum = Model()->table('goods')->where(array('goods_id'=>$goods_info[0]['goods_id']))->setInc('goods_salenum',$goods_num);
						}
					}
				}
				
				//循环数组合并放入新数组,按订单商品表根据order——id统计商品金额
				$order_goods_rebate= array();
				$order_goods_rebate['order_id']		= $order_info['order_id'];
				$order_goods_rebate['goods_rebate']	= $goods_rebate;
				$order_goods_rebate['goods_shipping_fee']	= $goods_shipping_fee;
				$temp[] =$order_goods_rebate;

				
				//收货人信息
				$reciver_info = array();
				$reciver_info['address'] 	= $address1.' '.$address2.' '.$address3.' '.$receiver_address;
				$reciver_info['area']		= $address1.$address2.$address3;
				$reciver_info['street']		= $receiver_address;
				$reciver_info['phone'] 		= $receiver_phone;
				$reciver_info['mob_phone']	= $receiver_phone;
				$reciver_info = serialize($reciver_info);
				
				$address1	= str_replace('省','',$address1);
				$province	= Model()->table('area')->where(array('area_name'=>$address1))->select();
				if($province){$reciver_province_id	= $province[0]['area_id'] ? $province[0]['area_id']:0; }
				$city		= Model()->table('area')->where(array('area_name'=>$address2))->select();
				if($city){$reciver_city_id	= $city[0]['area_id']?$city[0]['area_id']:0;}
				$area		= Model()->table('area')->where(array('area_name'=>$address3))->select();
				if($area){$reciver_area_id	= $area[0]['area_id'] ? $area[0]['area_id']:0;}

				//收货人信息插入用户地址表
				$user_address = Model('address');
				$con_address = array();
				$con_address['member_id']		 = $member_info['member_id'];
				$con_address['true_name']		 = $receiver_name;
				$con_address['area_id'] 		 = $reciver_area_id;
				$con_address['city_id']			 = $reciver_city_id;
				$con_address['area_info']		 = $address1.$address2.$address3;
				$con_address['address']			 = $address1.' '.$address2.' '.$address3.' '.$receiver_address;
				$con_address['mob_phone']		 = $receiver_phone ? $receiver_phone : $member_info['member_mobile'];
				$con_address['tel_phone']		 = $receiver_phone ? $receiver_phone : $member_info['member_mobile'];
				$con_address['is_default']		 = '0';							//1默认，0为不默认地址
		
				//用户id,收货人姓名、收货人手机和地址一样则地址表不增加
				$is_address = $user_address -> getAddressList(array('member_id'=>$member_info['member_id'],'true_name'=>$receiver_name,'mob_phone'=>$receiver_phone,'address'=>$address1.' '.$address2.' '.$address3.' '.$receiver_address));
				if(!$is_address){
					$inser_address = $user_address -> addAddress($con_address);
					if(!$inser_address){
						showMessage('用户收货地址表增加数据失败','index.php?gct=importorder','html','error');
					}
				}				
		  
				//插入订单order_common表数据
				$order_common=array();
				$order_common['reciver_info']		= $reciver_info;
				$order_common['order_id'] 			= $order_info['order_id'];
				$order_common['store_id'] 			= $goods_info[0]['store_id'];
				$order_common['order_name'] 		= $buyer_truename;
				$order_common['voucher_price'] 		= $discount_amount;
				$order_common['reciver_name'] 		= $receiver_name;
				$order_common['reciver_idnum'] 		= $buyer_idcode;
				$order_common['reciver_province_id'] 	= $reciver_province_id;
				$order_common['reciver_city_id'] 		= $reciver_city_id;
				$order_common['reciver_area_id'] 		= $reciver_area_id;
				if($order_info['order_state']=='20'){	//当订单状态为已付款时
					$order_common['shipping_time'] 		= TIMESTAMP;						//0未发货
					$order_common['shipping_express_id'] 	= 41;						//配送公司ID  41 yunda
					$shipping_code = logic('order') -> getYundaBillno($order_info['order_sn']);	//获取韵达单号
					//$shipping_code ='101010101';
					$update_order = $model_order -> editOrder(array('shipping_code'=>$shipping_code['mail_no'],'order_state'=>30),array('order_id'=>$order_info['order_id'])); //更新order表快递单号
			
					if($shipping_code['mail_no']){
						$data = array();
						$data['order_id'] = $insert_order;
						$data['log_role'] = '系统';
						$data['log_user'] = 'System';
						$data['log_msg'] = '自动获取物流单号设置了发货';
						$data['log_orderstate'] =30;
						$data['log_time'] = TIMESTAMP;
						$model_order->table('order_log')->insert($data);
					}
				}
				else{
					$order_common['shipping_time'] 		= '0';					//0未发货
					$order_common['shipping_express_id'] 	= 0;				//配送公司ID  8 ems
				}
				$is_exist_ordercommon = $model_order ->getOrderCommonList(array('order_id'=>$order_info['order_id']));
				if(!$is_exist_ordercommon){
					$insert_ordercommon = $model_order -> addOrderCommon($order_common);
				}
				//$cart_info['goods_price']=$price;
				//$cart_info['shipping_fee']=$shipping_fee?$shipping_fee:0;
				//$order_amount=ncPriceFormat($this->_logic_tax_1->single_times_allow_2000($cart_info)*$goods_num);
				//$order_tax=$this->_logic_tax_1->single_times_allow_2000($cart_info);
			}
			//start更新消费奖励、一级推广奖励、二级推广奖励、三级推广奖励 开始
			//循环形成的新数组按某一条件求和存入新结果数组
			$result = array();
			foreach($temp as $key=>$value){
				$result[$value['order_id']]['goods_rebate'] += $value['goods_rebate'];
				$result[$value['order_id']]['goods_shipping_fee'] += $value['goods_shipping_fee'];
			}
			
			$is_rebate = C('salescredit_isuse'); 		//是否开启返利模式
			$is_one = C('one_rank_rebate'); 			//是否开启一级返利
			$is_two = C('two_rank_rebate'); 			//是否开启二级返利
			$is_three = C('three_rank_rebate'); 		//是否开启三级返利
			
			$rate = C('salescredit_rebate') / 100; 		//自身返利率
			$one_rate = C('one_rebate_rate') / 100; 	//一级返利率
			$two_rate = C('two_rebate_rate') / 100; 	//二级返利率
			$three_rate = C('three_rebate_rate') / 100; //三级返利率
			
			foreach($result as $k=>$v){
				if($is_rebate==1){$model_order -> editOrder(array('goods_rebate_amount'=>$v['goods_rebate']*$rate),array('order_id'=>$k)); }
				if($is_one==1){$model_order -> editOrder(array('one_rebate'=>$v['goods_rebate']*$one_rate),array('order_id'=>$k)); 	}
				if($is_two==1){$model_order -> editOrder(array('two_rebate'=>$v['goods_rebate']*$two_rate),array('order_id'=>$k)); 	}
				if($is_three==1){$model_order -> editOrder(array('three_rebate'=>$v['goods_rebate']*$three_rate),array('order_id'=>$k)); }
				$model_order -> editOrder(array('shipping_fee'=>$v['goods_shipping_fee']),array('order_id'=>$k)); 
			}
			//end更新消费奖励、一级推广奖励、二级推广奖励、三级推广奖励结束
			
			
			//更新订单商品金额和税金开始
			//$update_order_info = $model_order -> getOrderList(array('goods_amount'=>array('eq',0)));
			$orderid =0;
			foreach($temp as $key=>$value){
				$orderid .=','.$value['order_id'];
			}
			$condition_upamoumt =array();
			$condition_upamoumt['order_id'] =array('in',$orderid);
			$find_ordergoods_info = $model_order -> getOrderGoodsList($condition_upamoumt,'order_id,sum(goods_total) as goodsSum,sum(taxes_total) as taxesSum','','','','order_id',null);

			foreach($find_ordergoods_info as $key=>$value){
				$update_order = $model_order -> editOrder(array('goods_amount'=>$value['goodsSum'],'order_tax'=>$value['taxesSum']),array('order_id'=>$value['order_id'])); //更新订单商品金额总和和订单税金
				if(!$update_order){
					showMessage('计算订单商品金额和订单税金失败','index.php?gct=importorder','html','error');
				}
			}

			//更新订单商品金额和税金结束
			$aaa = $model_order -> getOrderList(array('order_id'=>array('in',$orderid)));

			foreach($aaa as $k => $v){
			$data=array();
			$data['order_id'] = $v['order_id'];
			$data['platform_rebate'] = $v['goods_amount'] * $v['platform_rebate'] ;
			$bbb[]=$data;
			}
			foreach($bbb as $k => $v){
			$update_order_platform_rebate = $model_order -> editOrder(array('platform_rebate'=>$v['platform_rebate']),array('order_id'=>$v['order_id'])); 
			}

			showDialog('订单信息录入成功！');
		}

		Tpl::output('partner_name',$partner_name);
		Tpl::showpage('importorder.add');	
	}
		
		/**
		 * 导出
		 *
		*/
	  public function export_excelOp(){
			$this->createExcel($data);		
	  }
		/**
		 * 导出录入订单信息excel模板
		 *
		 * 
		 */
	 private function createExcel($data = array()){
			header("Content-type:text/html;charset=utf-8");
		    include(BASE_PATH.'/excel/ExcelReviser.php');
			$oWrite = new ExcelReviser();
			$oWrite->setInternalCharset('UTF-8');
			// 将字符串写入第1个sheet,第1列，第1行

			$readfile = BASE_PATH.'/excel/import_excel_model.xls';//在这儿放入excel模版的地址
			$outfile = iconv("utf-8","gb2312",date('YmdHi').".xls");//输出新文件的名字.xls

			// 新文件的路径，如果不指定，则弹出提示框按用户选择路径存 $oWrite->reviseFile($readfile,$outfile);

			$oWrite->reviseFile($readfile,$outfile);

			$oWrite->reviseFile($readfile,$outfile,$savepath);
		}
		
		
	/**
	 * 订单编号生成规则，n(n>=1)个订单表对应一个支付表，
	 * 生成订单编号(年取1位 + $pay_id取13位 + 第N个子订单取2位)
	 * 1000个会员同一微秒提订单，重复机率为1/100
	 * @param $pay_id 支付表自增ID
	 * @return string
	 */
	public function makeOrderSn($pay_id) {
	    //记录生成子订单的个数，如果生成多个子订单，该值会累加
	    static $num;
	    if (empty($num)) {
	        $num = 1;
	    } else {
	        $num ++;
	    }
		return  'gc'.(date('y',time()) % 9+1) . sprintf('%013d', $pay_id) . sprintf('%02d', $num);
	}

		/**
	* 价格格式化
	*
	* @param int	$price
	* @return string	$price_format
	*/
	   function ncPriceFormat($price) {
		$price_format	= number_format($price,2,'.','');
		return $price_format;
	   }	

	}
