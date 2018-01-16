<?php
/**
 * 实物订单行为（增加EMS运单号获取接口）
 *
 * 
 */
defined('GcWebShop') or exit('Access Invalid!');
class orderLogic {

    /**
     * 取消订单
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $user 操作人
     * @param string $msg 操作备注
     * @param boolean $if_update_account 是否变更账户金额
     * @param boolean $if_queue 是否使用队列
     * @return array
     */
    public function changeOrderStateCancel($order_info, $role, $user = '', $msg = '', $if_update_account = true, $if_quque = true) {
        try {
            $model_order = Model('order');
            $model_order->beginTransaction();
            $order_id = $order_info['order_id'];

            //库存销量变更
            $goods_list = $model_order->getOrderGoodsList(array('order_id'=>$order_id));
            $data = array();
            foreach ($goods_list as $goods) {
                $data[$goods['goods_id']] = $goods['goods_num'];
            }
            if ($if_quque) {
                QueueClient::push('cancelOrderUpdateStorage', $data);
            } else {
                Logic('queue')->cancelOrderUpdateStorage($data);
            }

            if ($if_update_account) {
                $model_pd = Model('predeposit');
                //解冻充值卡
                $rcb_amount = floatval($order_info['rcb_amount']);
                if ($rcb_amount > 0) {
                    $data_pd = array();
                    $data_pd['member_id'] = $order_info['buyer_id'];
                    $data_pd['member_name'] = $order_info['buyer_name'];
                    $data_pd['amount'] = $rcb_amount;
                    $data_pd['order_sn'] = $order_info['order_sn'];
                    $model_pd->changeRcb('order_cancel',$data_pd);
                }
                
                //解冻预存款
                $pd_amount = floatval($order_info['pd_amount']);
                if ($pd_amount > 0) {
                    $data_pd = array();
                    $data_pd['member_id'] = $order_info['buyer_id'];
                    $data_pd['member_name'] = $order_info['buyer_name'];
                    $data_pd['amount'] = $pd_amount;
                    $data_pd['order_sn'] = $order_info['order_sn'];
                    $model_pd->changePd('order_cancel',$data_pd);
                }                
            }

            //更新订单信息
            $update_order = array('order_state' => ORDER_STATE_CANCEL, 'pd_amount' => 0);
            $update = $model_order->editOrder($update_order,array('order_id'=>$order_id));
            if (!$update) {
                throw new Exception('保存失败');
            }

            //添加订单日志
            $data = array();
            $data['order_id'] = $order_id;
            $data['log_role'] = $role;
            $data['log_msg'] = '取消了订单';
            $data['log_user'] = $user;
            if ($msg) {
                $data['log_msg'] .= ' ( '.$msg.' )';
            }
            $data['log_orderstate'] = ORDER_STATE_CANCEL;
            $model_order->addOrderLog($data);
            $model_order->commit();

            return callback(true,'操作成功');

        } catch (Exception $e) {
            $this->rollback();
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
            $member_id=$order_info['buyer_id'];
            $order_id = $order_info['order_id'];
			$order_sn = $order_info['order_sn'];
			
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
                Model('points')->savePointsLog('order',array('pl_memberid'=>$order_info['buyer_id'],'pl_membername'=>$order_info['buyer_name'],'orderprice'=>$order_info['order_amount'],'order_sn'=>$order_sn,'order_id'=>$order_id),true);
            }
			
            //添加会员经验值
            Model('exppoints')->saveExppointsLog('order',array('exp_memberid'=>$order_info['buyer_id'],'exp_membername'=>$order_info['buyer_name'],'orderprice'=>$order_info['order_amount'],'order_sn'=>$order_sn,'order_id'=>$order_id),true);
			
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
            }else{
				$member_model = Model('member');
				//收到货款后就增加返利到个人余额
				$member_model->to_update($order_info);
				
				//购买指定商品后变更买家为商品级销售员
				if(C('seller_goods')){
					$goods_array = $model_order->getOrderGoodsList(array('order_id'=>$order_info['order_id']), 'goods_id');
					$goods_id = explode(',',C('seller_goods'));
					foreach($goods_array as $val){ 
						if(in_array($val['goods_id'] , $goods_id)){
							$member_model->editMember(array('member_id'=>$order_info['buyer_id']),array('is_seller'=> 1));
						}
					}
				}else{
					$member_model->editMember(array('member_id'=>$order_info['buyer_id']),array('is_seller'=> 1));
				}
				$is_bill_no = true ;
				$goods_id_array = $model_order->getOrderGoodsList(array('order_id'=>$order_info['order_id']), 'store_from');
				foreach($goods_id_array as $val){
					if($val['store_from'] != 1){
						$is_bill_no = false ;
					}
				}
				if($is_bill_no){
					$data = array();
					$data['shipping_express_id'] = 41;
					$data['shipping_time'] = TIMESTAMP ;
					$condition = array();
					$condition['order_id'] = $order_info['order_id'];
					$condition['store_id'] = $order_info['store_id'];
					$model_order->editOrderCommon($data,$condition);
					
					$bill_no = $this->getYundaBillno($order_info['order_sn']);
					if($bill_no['mail_no'] && $bill_no['hawbno']==$order_info['pay_sn']){
						$update_order = array();
						$update_order['shipping_code'] = $bill_no['mail_no'];
						$update_order['order_state'] = ORDER_STATE_SEND;
						$update_order['delay_time'] = TIMESTAMP;
						$model_order->editOrder($update_order,array('order_id'=>$order_info['order_id'],'order_state'=>ORDER_STATE_PAY));
					}else{
						$sms = new Sms();
						$sms->send('15923235201','运单号获取失败，订单编号'.$order_info['order_sn']);
					}
				}
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

            //支付成功发送微信模板消息
            $param = array();
            $param['code'] = 'order_payment_success';
            $msg_tpl = rkcache('member_msg_tpl', true);
            $tpl_info = $msg_tpl[$param['code']];
            // 判断是否开启模板消息
            if($tpl_info['mmt_wechat_switch']) {
                $member_id = $order_info['buyer_id'];
                $member_info = Model('member')->getMemberInfo(array('member_id'=>$member_id),array('member_wechatopenid','member_name'));
                $param['openid'] = $member_info['member_wechatopenid'];
                $param['member_name'] = $member_info['member_name'];
                $param['order_sn'] = $order_info['order_sn'];
                $param['payment_time'] = $post['payment_time'] ? strtotime($post['payment_time']) : TIMESTAMP;
                // 判断对应的模板消息是否存在
                if($param['openid']) {
                    $Wxtplmsg = new Wxtplmsg();
                    $Wxtplmsg->sendTplMsg($param);
                }
            }           

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
			//更新订单表支付宝交易号信息(海关统一版传送信息给支付宝需要)
			$model_order->editOrderTrade(array('trade_no' =>$post['trade_no']) , array('order_id' =>$order_id));
			$model_order->addOrderLog($data);
			
			/*用户满送代金券活动
			$goods_list= $model_order->getOrderGoodsList(array('order_id' =>$order_id),'goods_id');
			
			$goods_id = array('4015', '4016', '4017', '4018', '4019');
			
			foreach($goods_list as $val){
				if(in_array($val['goods_id'],$goods_id) && $order_info['order_amount'] >= 360){
					$insert_arr = array();
					$insert_arr['voucher_code'] = mt_rand(10,99).sprintf('%010d',time() - 946656000).sprintf('%03d', (float) microtime() * 1000).sprintf('%03d', (int) $order_info['buyer_id'] % 1000);
					$insert_arr['voucher_t_id'] = 89;
					$insert_arr['voucher_title'] = '美波丽胸膜60元代金券';
					$insert_arr['voucher_desc'] = '美波丽胸膜代金券';
					$insert_arr['voucher_start_date'] = '1488790800';
					$insert_arr['voucher_end_date'] = '1496851199';
					$insert_arr['voucher_price'] = '60';
					$insert_arr['voucher_limit'] = '68.00';
					$insert_arr['voucher_store_id'] = 0;
					$insert_arr['voucher_design_sku'] = '4740';
					$insert_arr['voucher_state'] = 1;
					$insert_arr['voucher_active_date'] = TIMESTAMP;
					$insert_arr['voucher_owner_id'] = $order_info['buyer_id'];
					$insert_arr['voucher_owner_name'] = $order_info['buyer_name'];
				}
			}
			$result = Model()->table('voucher')->insert($insert_arr);
			$member = Model('member')->getMemberInfo(array('member_id'=>$order_info['buyer_id']),'member_mobile');
			
			if($result && $member['member_mobile']){
				$sms = new Sms();
				$sms->send($member['member_mobile'],'美波丽胸膜60元代金券已派送到您的账户个人中心-我的代金券中，请在有效期内购买美波丽胸膜使用');
			}
			/*用户满送代金券活动*/
        }

        return callback(true,'操作成功');
    }
	
	/*
	 * 获取EMS运单号
	 * 
	 * @param int  $billNoAmount   获取的运单数量 1-100个      
	 * @param int  $businessType   类型 1为标准快递，4为经济快递
	 * 
	 */
	public function getEmsBillno($businessType, $billNoAmount){
    	
    	$billno='';
    	//请求xml
    	$xmls='<XMLInfo>
				<sysAccount>50010713784000</sysAccount>
				<passWord>e10adc3949ba59abbe56e057f20f883e</passWord>
				<appKey></appKey>
				<businessType>'.$businessType.'</businessType>
				<billNoAmount>'.$billNoAmount.'</billNoAmount>
			</XMLInfo>';
		$data['method']="getBillNumBySys";
		$data['xml'] = base64_encode($xmls);
	   
		$data = http_build_query($data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://os.ems.com.cn:8081/zkweb/bigaccount/getBigAccountDataAction.do?');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);
		curl_close($ch);
		
		// 获取返回数据
		$result=str_replace(' ','+',$result);
		$objXml=base64_decode($result);
		$doc = new DOMDocument();
		$doc->loadXML($objXml);
		$re = $doc->getElementsByTagName("result")->item(0)->nodeValue;
		
		if($re == 1){
			$billno = $doc->getElementsByTagName("billno")->item(0)->nodeValue;
		}
		
		return $billno;
    }
	
	/*
	 * 统一版EMS运单推送 
	 *  
	 */
	public function ems_send($ems_code,$order_sn,$shipping_code,$re_no,$re_name,$address,$phone,$freight,$weight){
		
		$ems_XML = '<?xml version="1.0" encoding="UTF-8"?>
<NewDataSet>
	<EMS_DS_TMP>
		<EMS_CODE>'.$ems_code.'</EMS_CODE>
		<BUSINESSTYPE>1</BUSINESSTYPE>
		<ORIGINAL_ORDER_NO>'.$order_sn.'</ORIGINAL_ORDER_NO>
		<BIZ_TYPE_CODE>I20</BIZ_TYPE_CODE>
		<BIZ_TYPE_NAME>保税进口</BIZ_TYPE_NAME>
		<TRANSPORT_BILL_NO>'.$shipping_code.'</TRANSPORT_BILL_NO>
		<ESHOP_ENT_CODE>501226053A</ESHOP_ENT_CODE>
		<ESHOP_ENT_NAME>光彩国际重庆电子商务有限公司</ESHOP_ENT_NAME>
		<RECEIVER_ID_NO>'.$re_no.'</RECEIVER_ID_NO>
		<RECEIVER_NAME>'.$re_name.'</RECEIVER_NAME>
		<RECEIVER_ADDRESS>'.$address.'</RECEIVER_ADDRESS>
		<RECEIVER_TEL>'.$phone.'</RECEIVER_TEL>
		<billNo></billNo>
		<QTY>1</QTY>
		<freight>'.$freight.'</freight>
		<insuredFee>0</insuredFee>
		<weight>'.$weight.'</weight>
		<goodsInfo></goodsInfo>
	</EMS_DS_TMP>
</NewDataSet>';
		// file_put_contents('D:/log/'.$order_sn.'.xml', $ems_XML);	
		$XML_list = base64_encode($ems_XML);
		$XML_list = urlencode($XML_list);
		
		$outer_xml = sprintf('<?xml version="1.0" encoding="utf-8"?>
		<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
			<soap:Body>
				<cqems_electronic_business_all xmlns="http://tempuri.org/">
					<xmlstring>%s</xmlstring>
					<emscode>%s</emscode>
				</cqems_electronic_business_all>
			</soap:Body>
		</soap:Envelope>',$XML_list,$ems_code);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://115.28.134.167/cqemsds.asmx");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $outer_xml); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('SOAPAction:"http://tempuri.org/cqems_electronic_business_all"',
		'Content-Type:text/xml; charset=utf-8')); 
		$result=curl_exec($ch);
		
		file_put_contents('D:/log/ems/'.$order_sn.'.xml', $result);
		$doc = new DOMDocument();
		$doc->loadXML($result);
		$re = array();
		$re['result'] = $doc->getElementsByTagName('cqems_electronic_business_allResult')->item(0)->nodeValue;
		
		return $re;
	}
	
	/*
	 * 获取韵达运单号同步申报运单
	 * 
	 */
	public function getYundaBillno($order_sn){
		
		$order_info = Model('order')->getOrderInfo(array('order_sn'=>$order_sn),array('order_goods','order_common'));
		foreach($order_info['extend_order_goods'] as $val){
			$weight += $val['goods_weight'] * $val['goods_num']; 
		}
		//xml数据
		$xmlData = "<beans>
	<req_type>create_order</req_type>
	<hawbs>
		<hawb>
			<mail_no></mail_no>
			<hawbno>".$order_info['pay_sn']."</hawbno>
			<order_id></order_id>
			<mawb></mawb>
			<piece>1</piece>
			<weight>".$weight."</weight>
			<freight>".$order_info['shipping_fee']."</freight>
			<pre_express></pre_express>
			<next_express></next_express>
			<fcountry>CN</fcountry>  
			<tcountry>CN</tcountry>  
			<infor_origin></infor_origin>
			<receiver>
				<company></company>
				<contacts>".$order_info['extend_order_common']['reciver_name']."</contacts>
				<city></city>  
				<postal_code></postal_code>
				<address>".$order_info['extend_order_common']['reciver_info']['address']."</address>
				<rec_tele>".$order_info['extend_order_common']['reciver_info']['mob_phone']."</rec_tele>
				<e_mail></e_mail>
			</receiver>
			<sender>
				<company>光彩国际重庆电子商务有限公司</company>
				<city></city> 
				<contacts>光彩全球</contacts>
				<address>".$order_info['store_name']."</address>
				<sender_tele>".C('site_tel400')."</sender_tele>
				<postal_code></postal_code>
				<e_mail></e_mail>
			</sender>
			<insurance_fee>0</insurance_fee>
			<goods_money>0</goods_money> 
			<certificate_type>zj01</certificate_type>
			<certificate_id></certificate_id>
			<currency></currency>
			<request></request>
			<remark></remark>
			<vat_service></vat_service> 
			<goods_list>";
			foreach($order_info['extend_order_goods'] as $val){
				$hscode = Model('goods')->getGoodsInfo(array('goods_id'=>$val['goods_id']),'goods_hscode');
	$xmlData .="<goods>
					<name>".$val['goods_name']."</name>
					<hs_code>".$hscode['goods_hscode']."</hs_code>
					<unit_price>".$val['goods_price']."</unit_price>
					<act_weight>0</act_weight>
					<dim_weight>0</dim_weight>
					<quantity>".$val['goods_num']."</quantity>
				</goods >";
			}
  $xmlData .="</goods_list>
		</hawb>
	</hawbs>
</beans>";

		$sysData = array(
			'app_key'=>'gcgj',
			'tradeId'=>'630015180104,04DF3F0B03433742A5EA49DCEA44024B',
			'buz_type'=>'partner',
			'method'=>'global_order_create',
			'version'=>'1.0',
			'format'=>'xml',
			'data'=>$xmlData,
		);

		//生成签名
		ksort($sysData);
		$signStr = '';
		foreach ($sysData as $key => $val){
			$signStr .= $key . $val;
		}
		$signStr .= '4C4899A0628430AC058F48F47DC1D1CB'; //拼上秘钥
		$sign = base64_encode(md5($signStr));
		$sysData['sign'] = $sign;

		//发送数据
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://ydoms.yundasys.com:10582/oms/interface.php');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $sysData);
		$result = curl_exec($ch);
		if ($error = curl_error($ch)) {
			echo $error;
		}
		$doc = new DOMDocument();
		$doc->loadXML($result);
		$re = array();
		$re['hawbno'] = $doc->getElementsByTagName('hawbno')->item(0)->nodeValue;
		$re['mail_no'] = $doc->getElementsByTagName('mail_no')->item(0)->nodeValue;
		$re['msg'] = $doc->getElementsByTagName('msg')->item(0)->nodeValue;
		if(!$re['mail_no']){
			$log  = date('Y-m-d H:i:s', time()).' '.$order_sn.' '.$re['msg'].PHP_EOL;
			$log .= ''.PHP_EOL;
			file_put_contents('D:/log/yunda/log.txt', $log, FILE_APPEND);
		}
		return $re;
	}
	
}