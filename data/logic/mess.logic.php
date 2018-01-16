<?php
/**
 * 海关订单行为
 *
 * 
 */
defined('GcWebShop') or exit('Access Invalid!');
class messLogic {

	//申报海关代码
	private $CUSTOMS_CODE = '50052602G5';
	
	//申报海关代码密码
	private $CUSTOMS_PASS = '50052602G5';
	
	//申报海关起夜名称
	private $ESHOP_ENT_NAME = '光彩国际重庆电子商务有限公司';
	
	//业务类型
	private $BIZ_TYPE_CODE_INPUT = 'I10';
	private $BIZ_TYPE_CODE_OUTPUT = 'I20';

/*
订单表头					
序号	英文名	中文名	数据类型	是否必填	备注
1	CUSTOMS_CODE	申报海关代码	VARCHAR2(4)	是	海关参数填报
2	BIZ_TYPE_CODE	业务类型	CHAR(3)	是	"直购进口：I10,
网购保税进口：I20"
3	ORIGINAL_ORDER_NO	原始订单编号	VARCHAR2(30)	是	和支付单进行关联
4	ESHOP_ENT_CODE	电商企业代码	VARCHAR2(20)	是	
5	ESHOP_ENT_NAME	电商企业名称	VARCHAR2(100)	是	企业备案的企业全称
6	DESP_ARRI_COUNTRY_CODE	起运国	VARCHAR2(4)	是	
7	SHIP_TOOL_CODE	运输方式	VARCHAR2(4)	是	
8	RECEIVER_ID_NO	收货人身份证号码	VARCHAR2(20)	是	
9	RECEIVER_NAME	收货人姓名	VARCHAR2(100)	是	
10	RECEIVER_ADDRESS	收货人地址	VARCHAR2(500)	是	
11	RECEIVER_TEL	收货人电话	VARCHAR2(50)	是	
12	GOODS_FEE	货款总额	NUMBER(18,2)	是	
13	TAX_FEE	税金总额	NUMBER(18,2)	是	包含 增值税、消费税、关税
14	GROSS_WEIGHT	毛重	NUMBER(18,2)	选择	
15	PROXY_ENT_CODE	代理企业代码	VARCHAR2(20)	选择	
16	PROXY_ENT_NAME	代理企业名称	VARCHAR2(200)	选择	代理境外电商代码
17	SORTLINE_ID	分拣线ID	VARCHAR2(50)	是	"分拣线标识：
SORTLINE01：代表寸滩空港
SORTLINE02：代表重庆西永
SORTLINE03：代表寸滩水港
SORTLINE04：代表邮政EMS
SORTLINE05：代表潍坊分拣线01
SORTLINE06：代表南彭保仓分拣线"
18	TRANSPORT_FEE	运费	NUMBER(18,2)	是	无则为0
23	CHECK_TYPE	验证类型	VARCHAR2(1)	进口必填，出口非必填	进口必填，R:订购人P:支付人
24	SEND_ENT_CODE	发送企业代码	VARCHAR2(20)	选择	发送报文的企业代码
25	BUYER_REG_NO	订购人注册号	VARCHAR2(64)	是	
26	BUYER_NAME	订购人姓名	VARCHAR2(255)	是	
27	BUYER_ID_TYPE	订购人证件类型	VARCHAR2(2)	是	1=身份证，2=其他
28	BUYER_ID	订购人证件号码	VARCHAR2(64)	是	
29	DISCOUNT	优惠减免金额	NUMBER(18,2)	是	无则为0
30	ACTUAL_PAID	实际支付金额	NUMBER(18,2)	是	货款+运费+税款+保费-优惠金额，与支付保持一致
31	INSURED_FEE	保费	NUMBER(18,2)	是	
					

订单明细					
序号	英文名	中文名	数据类型	是否必填	备注
1	SKU	商品货号	VARCHAR2(30)	是	
2	GOODS_SPEC	规格型号	VARCHAR2(200)	是	
3	CURRENCY_CODE	币制代码	VARCHAR2(4)	是	
4	PRICE	商品单价	NUMBER(18,2)	是	
5	QTY	商品数量	NUMBER(18,2)	是	
6	GOODS_FEE	商品总价	NUMBER(18,2)	是	
7	TAX_FEE	税款金额	NUMBER(18,2)	是	
8	COUNTRY	原产国	VARCHAR2(12)	是	
					

*/			
 
    /**
     * 海关订单推送
     * 
     */
    public function messInput() {
        try {
            $model_order = Model('order');
            $model_order->beginTransaction();
			
			
			//将已付款且未提取的订单数据插入临时表中
			$sql = "SELECT
						33hao_order_goods.order_id,
						33hao_order_goods.goods_id,
						33hao_order.order_sn,
						33hao_order.pay_sn,
						(SELECT 33hao_order.order_amount + IFNULL(33hao_order_common.voucher_price,0)) AS order_amount,
						33hao_goods.goods_serial,
						33hao_goods.sku_spec,
						33hao_order_goods.goods_total,
						(33hao_order_goods.taxes_total)/(33hao_order_goods.goods_total+33hao_order_goods.shipping_total),
						33hao_order_goods.goods_num,
						33hao_order_goods.taxes_total,
						33hao_order_goods.shipping_total,
						33hao_goods.goods_weight,
						33hao_goods.pack_units,
						33hao_order_common.reciver_name,
						33hao_order_common.reciver_idnum,
						33hao_order_common.reciver_info
						FROM 33hao_order
						INNER JOIN 33hao_order_goods ON 33hao_order_goods.order_id = 33hao_order.order_id
						INNER JOIN 33hao_goods ON 33hao_goods.goods_id = 33hao_order_goods.goods_id
						INNER JOIN 33hao_goods_post_tax ON 33hao_goods_post_tax.POST_TAX_NO = 33hao_goods.post_tax_no
						INNER JOIN 33hao_order_common ON 33hao_order_common.order_id = 33hao_order.order_id
						WHERE
						33hao_order.order_state = 20 AND 33hao_order.MESS_STATE = 10 AND (33hao_goods.store_from = '1' OR 33hao_goods.store_from = '2')  ";
						// order_state = 20 已付款 MESS_STATE = 10 未抛送2.0 MESS_STATE = 20 已抛送2.0
						//goods.store_from=1 保税进口 2 海外直邮  3 外贸进口 文本格式
			$return = DB::getAll($sql);
			header("Content-type:text/html;charset=utf-8");echo '<pre>';print_r($return);exit;
			
			
			
            $model_order->commit();
            return callback(true,'操作成功');
        } catch (Exception $e) {
            $model_order->rollback();
            return callback(false,'操作失败');
        }
    }

    /**
     * 收货
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $user 操作人
     * @param string $msg 操作备注
     * @return array
     */
    public function changeOrderStateReceive($order_info, $role, $user = '', $msg = '') {
        try {
            //ming>>>
            $member_id=$order_info['buyer_id'];
            $order_id = $order_info['order_id'];
			$order_sn = $order_info['order_sn'];
			$goods_rebate_amount = $order_info['goods_rebate_amount'];
			
            $model_order = Model('order');
			$model_member = Model("member");
            //更新订单状态
            $update_order = array();
            $update_order['finnshed_time'] = TIMESTAMP;
            $update_order['order_state'] = ORDER_STATE_SUCCESS;
            $update = $model_order->editOrder($update_order,array('order_id'=>$order_id));
			
            if (!$update) {
                throw new Exception('保存失败');
            }

            //添加订单日志
            $data = array();
            $data['order_id'] = $order_id;
            $data['log_role'] = 'buyer';
            $data['log_msg'] = '签收了货物';
            $data['log_user'] = $user;
            if ($msg) {
                $data['log_msg'] .= ' ( '.$msg.' )';
            }
            $data['log_orderstate'] = ORDER_STATE_SUCCESS;
            $model_order->addOrderLog($data);
		 

            //添加会员积分
            if (C('points_isuse') == 1){
                Model('points')->savePointsLog('order',array('pl_memberid'=>$order_info['buyer_id'],'pl_membername'=>$order_info['buyer_name'],'orderprice'=>$order_info['order_amount'],'order_sn'=>$order_info['order_sn'],'order_id'=>$order_info['order_id']),true);
            }
			

			
            //添加会员经验值
            Model('exppoints')->saveExppointsLog('order',array('exp_memberid'=>$order_info['buyer_id'],'exp_membername'=>$order_info['buyer_name'],'orderprice'=>$order_info['order_amount'],'order_sn'=>$order_info['order_sn'],'order_id'=>$order_info['order_id']),true);
			
			//邀请人获得返利积分
			$model_member = Model('member');
			$inviter_id = $model_member->table('member')->getfby_member_id($member_id,'inviter_id');
			$inviter_name = $model_member->table('member')->getfby_member_id($inviter_id,'member_name');
			$rebate_amount = ceil(0.01 * $order_info['order_amount'] * $GLOBALS['setting_config']['points_rebate']);
			Model('points')->savePointsLog('rebate',array('pl_memberid'=>$inviter_id,'pl_membername'=>$inviter_name,'rebate_amount'=>$rebate_amount),true);
			
			return callback(true,'操作成功');
			          
        } catch (Exception $e) {
            return callback(false,'操作失败');
        }
    }

    /**
     * 更改运费
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $user 操作人
     * @param float $price 运费
     * @return array
     */
    public function changeOrderShipPrice($order_info, $role, $user = '', $price) {
        try {

            $order_id = $order_info['order_id'];
            $model_order = Model('order');

            $data = array();
            $data['shipping_fee'] = abs(floatval($price));
            $data['order_amount'] = array('exp','goods_amount+'.$data['shipping_fee']);
            $update = $model_order->editOrder($data,array('order_id'=>$order_id));
            if (!$update) {
                throw new Exception('保存失败');
            }
            //记录订单日志
            $data = array();
            $data['order_id'] = $order_id;
            $data['log_role'] = $role;
            $data['log_user'] = $user;
            $data['log_msg'] = '修改了运费'.'( '.$price.' )';
            $data['log_orderstate'] = $order_info['payment_code'] == 'offline' ? ORDER_STATE_PAY : ORDER_STATE_NEW;
            $model_order->addOrderLog($data);
            return callback(true,'操作成功');
        } catch (Exception $e) {
            return callback(false,'操作失败');
        }
    }
    /**
     * 更改运费
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $user 操作人
     * @param float $price 运费
     * @return array
     */
    public function changeOrderSpayPrice($order_info, $role, $user = '', $price) {
        try {

            $order_id = $order_info['order_id'];
            $model_order = Model('order');

            $data = array();
            $data['goods_amount'] = abs(floatval($price));
            $data['order_amount'] = array('exp','shipping_fee+'.$data['goods_amount']);
            $update = $model_order->editOrder($data,array('order_id'=>$order_id));
            if (!$update) {
                throw new Exception('保存失败');
            }
            //记录订单日志
            $data = array();
            $data['order_id'] = $order_id;
            $data['log_role'] = $role;
            $data['log_user'] = $user;
            $data['log_msg'] = '修改了运费'.'( '.$price.' )';
            $data['log_orderstate'] = $order_info['payment_code'] == 'offline' ? ORDER_STATE_PAY : ORDER_STATE_NEW;
            $model_order->addOrderLog($data);
            return callback(true,'操作成功');
        } catch (Exception $e) {
            return callback(false,'操作失败');
        }
    }
    /**
     * 回收站操作（放入回收站、还原、永久删除）
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $state_type 操作类型
     * @return array
     */
    public function changeOrderStateRecycle($order_info, $role, $state_type) {
        $order_id = $order_info['order_id'];
        $model_order = Model('order');
        //更新订单删除状态
        $state = str_replace(array('delete','drop','restore'), array(ORDER_DEL_STATE_DELETE,ORDER_DEL_STATE_DROP,ORDER_DEL_STATE_DEFAULT), $state_type);
        $update = $model_order->editOrder(array('delete_state'=>$state),array('order_id'=>$order_id));
        if (!$update) {
            return callback(false,'操作失败');
        } else {
            return callback(true,'操作成功');
        }
    }

    /**
     * 发货
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $user 操作人
     * @return array
     */
    public function changeOrderSend($order_info, $role, $user = '', $post = array()) {
        $order_id = $order_info['order_id'];
        $model_order = Model('order');
		try {
            $model_order->beginTransaction();
            $data = array();
            $data['reciver_name'] = $post['reciver_name'];
			$data['reciver_idnum'] = $post['reciver_idnum'];
            $data['reciver_info'] = $post['reciver_info'];
            $data['deliver_explain'] = $post['deliver_explain'];
            $data['daddress_id'] = intval($post['daddress_id']);
            $data['shipping_express_id'] = intval($post['shipping_express_id']);
            $data['shipping_time'] = TIMESTAMP;

            $condition = array();
            $condition['order_id'] = $order_id;
            $condition['store_id'] = $_SESSION['store_id'];
            $update = $model_order->editOrderCommon($data,$condition);
            if (!$update) {
                throw new Exception('操作失败');
            }

            $data = array();
            $data['shipping_code']  = $post['shipping_code'];
            $data['order_state'] = ORDER_STATE_SEND;
            $data['delay_time'] = TIMESTAMP;
            $update = $model_order->editOrder($data,$condition);
            if (!$update) {
                throw new Exception('操作失败');
            }
            $model_order->commit();
		} catch (Exception $e) {
		    $model_order->rollback();
		    return callback(false,$e->getMessage());
		}

		//更新表发货信息
		if ($post['shipping_express_id'] && $order_info['extend_order_common']['reciver_info']['dlyp']) {
		    $data = array();
		    $data['shipping_code'] = $post['shipping_code'];
		    $data['order_sn'] = $order_info['order_sn'];
		    $express_info = Model('express')->getExpressInfo(intval($post['shipping_express_id']));
		    $data['express_code'] = $express_info['e_code'];
		    $data['express_name'] = $express_info['e_name'];
		    Model('delivery_order')->editDeliveryOrder($data,array('order_id' => $order_info['order_id']));
		}

		//添加订单日志
		$data = array();
		$data['order_id'] = intval($_GET['order_id']);
		$data['log_role'] = 'seller';
		$data['log_user'] = $_SESSION['member_name'];
		$data['log_msg'] = '发出了货物 ( 编辑了发货信息 )';
		$data['log_orderstate'] = ORDER_STATE_SEND;
		$model_order->addOrderLog($data);

		// 发送买家消息
        $param = array();
        $param['code'] = 'order_deliver_success';
        $param['member_id'] = $order_info['buyer_id'];
        $param['param'] = array(
            'order_sn' => $order_info['order_sn'],
            'order_url' => urlShop('member_order', 'show_order', array('order_id' => $order_id))
        );
        QueueClient::push('sendMemberMsg', $param);

        return callback(true,'操作成功');
    }

    /**
     * 收到货款
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $user 操作人
     * @return array
     */
    public function changeOrderReceivePay($order_list, $role, $user = '', $post = array()) {
        $model_order = Model('order');

        try {
            $model_order->beginTransaction();

            $data = array();
            $data['api_pay_state'] = 1;
			$update = $model_order->editOrderPay($data,array('pay_sn'=>$order_list[0]['pay_sn']));
            if (!$update) {
                throw new Exception('更新支付单状态失败');
            }

            $model_pd = Model('predeposit');
            foreach($order_list as $order_info) {
                $order_id = $order_info['order_id'];
                if ($order_info['order_state'] != ORDER_STATE_NEW) continue;
                //下单，支付被冻结的充值卡
                $rcb_amount = floatval($order_info['rcb_amount']);
                if ($rcb_amount > 0) {
                    $data_pd = array();
                    $data_pd['member_id'] = $order_info['buyer_id'];
                    $data_pd['member_name'] = $order_info['buyer_name'];
                    $data_pd['amount'] = $rcb_amount;
                    $data_pd['order_sn'] = $order_info['order_sn'];
                    $model_pd->changeRcb('order_comb_pay',$data_pd);
                }

                //下单，支付被冻结的预存款
                $pd_amount = floatval($order_info['pd_amount']);
                if ($pd_amount > 0) {
                    $data_pd = array();
                    $data_pd['member_id'] = $order_info['buyer_id'];
                    $data_pd['member_name'] = $order_info['buyer_name'];
                    $data_pd['amount'] = $pd_amount;
                    $data_pd['order_sn'] = $order_info['order_sn'];
                    $model_pd->changePd('order_comb_pay',$data_pd);
                }
            }

            //更新订单状态
            $update_order = array();
            $update_order['order_state'] = ORDER_STATE_PAY;
            $update_order['payment_time'] = ($post['payment_time'] ? strtotime($post['payment_time']) : TIMESTAMP);
            $update_order['payment_code'] = $post['payment_code'];
            $update = $model_order->editOrder($update_order,array('pay_sn'=>$order_info['pay_sn'],'order_state'=>ORDER_STATE_NEW));
            if (!$update) {
                throw new Exception('操作失败');
            }
            $model_order->commit();
        } catch (Exception $e) {
            $model_order->rollback();
            return callback(false,$e->getMessage());
        }

        foreach($order_list as $order_info) {
			//防止重复发送消息
			if ($order_info['order_state'] != ORDER_STATE_NEW) continue;
            $order_id = $order_info['order_id'];
            // 支付成功发送买家消息
            $param = array();
            $param['code'] = 'order_payment_success';
            $param['member_id'] = $order_info['buyer_id'];
            $param['param'] = array(
                    'order_sn' => $order_info['order_sn'],
                    'order_url' => urlShop('member_order', 'show_order', array('order_id' => $order_info['order_id']))
            );
            QueueClient::push('sendMemberMsg', $param);

            // 支付成功发送店铺消息
            $param = array();
            $param['code'] = 'new_order';
            $param['store_id'] = $order_info['store_id'];
            $param['param'] = array(
                    'order_sn' => $order_info['order_sn']
            );
            QueueClient::push('sendStoreMsg', $param);

            //添加订单日志
            $data = array();
            $data['order_id'] = $order_id;
            $data['log_role'] = $role;
            $data['log_user'] = $user;
            $data['log_msg'] = '收到了货款 ( 支付平台交易号 : '.$post['trade_no'].' )';
            $data['log_orderstate'] = ORDER_STATE_PAY;
            $model_order->addOrderLog($data);
			
        }

        return callback(true,'操作成功');
    }
	
	
}