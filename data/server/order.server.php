<?php
/**
 * 登录逻辑层处理
 */
defined('GcWebShop') or exit('Access Invalid!');
class orderServer {

    /**
     * [订单详情逻辑层]
     * @author fulijun
     * @dateTime 2016-09-17T16:45:09+0800
     * @param    [Array]                   $condition [查询条件]
     * @param    [Array]                   $tables    [表数组：键为表名，值为要查询的字段，如下]
     * @return   [Array]                              [返回订单详情数组]
     * $condition = array('buyer_id'=>$member_id,'order_id'=>$order_id);
       * $tables = array(
        *    //'order'=>'order_id,order_sn,order_state,payment_code,store_id,store_id,buyer_id,order_amount,shipping_fee,add_time',
         *   //'order_goods'=>'goods_id,goods_name,goods_price,goods_num,goods_pay_price',
          *  //'order_common'=>'order_id,reciver_name,reciver_info,invoice_info',
           * //'store'=>'store_id,store_name',
           * //'member'=>'member_id,member_name'
        *);
     */
    public function getOrderdetail($condition,$tables){
        //取得表数组
        $extend = array();
        foreach ($tables as $k => $v) {
            $extend[] = $k;
        }

        $order_detail = array();
        $order_detail = Model()->table('order')->field($tables['order'])->where($condition)->find();
        if (empty($order_detail)) {
            return array();
        }
        //订单实付款
        $order_detail['real_pay_amount'] = $order_detail['order_amount']+$order_datail['shipping_fee'];
        
        //支付名称
        if (isset($order_detail['payment_code'])) {
            $order_detail['payment_name'] = orderPaymentName($order_detail['payment_code']);
        }
        

        //追加返回订单扩展表信息
        if (in_array('order_common',$extend)) {
            $tmp = Model()->table('order_common')
                                                       ->field($tables['order_common'])
                                                       ->where(array('order_id'=>$order_detail['order_id']))
                                                       ->find();
            $reciver_info_arr = unserialize($tmp['reciver_info']);
           
            //收货人地址姓名电话
            $order_detail['order_common_ext']['reciver_name'] = $tmp['reciver_name'];
            $order_detail['order_common_ext']['reciver_phone'] = $reciver_info_arr['phone'];
            $kongbai = array(" ","  ","\t","\r","\n");
            $order_detail['order_common_ext']['reciver_addr'] = strip_tags(str_replace($kongbai,"",$reciver_info_arr['address']));
        }

        //追加返回店铺信息
        if (in_array('store',$extend)) {
            $order_detail['store_ext'] = Model()->table('store')->field($tables['store'])->where(array('store_id'=>$order_detail['store_id']))->find();
        }

        //返回买家信息
        if (in_array('member',$extend)) {
            $order_detail['member_ext'] = Model()->table('member')->field($tables['member'])->where(array('member_id'=>$order_detail['buyer_id']))->find();
        }

        //追加返回商品信息
        if (in_array('order_goods',$extend)) {
            $order_detail['order_goods_ext'] = Model()->table('order_goods')
                                                    ->field($tables['order_goods'])
                                                    ->where(array('order_id'=>$order_detail['order_id']))
                                                    ->select();
  
            if(is_array($order_detail['order_goods_ext'])){
               foreach ($order_detail['order_goods_ext'] as $k => $v) {
                    $order_detail['order_goods_ext'][$k]['goods_image'] = cthumb($v['goods_image'], 240, $v['store_id'], $v['goods_id']);
                }   
            }

        }
        return $order_detail;
    } 



    /**
     * [订单列表逻辑层]
     * @author fulijun
     * @dateTime 2016-09-17T16:45:09+0800
     * @param    [Array]                   $condition [查询条件]
     * @param    [String]                  $limit [条数,如：0,10]
     * @param    [String]                  $order [排序]
     * @param    [Array]                   $tables    [表数组：键为表名，值为要查询的字段，如下]
     * @return   [Array]                              [返回订单详情数组]
     * $condition = array('buyer_id'=>$member_id,'order_id'=>$order_id);
       * $tables = array(
        *    //'order'=>'order_id,order_sn,order_state,payment_code,store_id,store_id,buyer_id,order_amount,shipping_fee,add_time',
         *   //'order_goods'=>'goods_id,goods_name,goods_price,goods_num,goods_pay_price',
          *  //'order_common'=>'order_id,reciver_name,reciver_info,invoice_info',
           * //'store'=>'store_id,store_name',
           * //'member'=>'member_id,member_name'
        *);
     */
    public function getOrderList($condition,$limit,$order="order_id DESC",$tables){
        //取得表数组
        $extend = array();
        foreach ($tables as $k => $v) {
            $extend[] = $k;
        }

        //订单表
        $order_list = array();
        $order_list = Model()->table('order')->field($tables['order'])->where($condition)->limit($limit)->order($order)->select();
        if (empty($order_list)) {
            return array();
        }

        foreach ($order_list as $k => $v) {
            //订单实付款
            $order_list[$k]['pay_amount'] = $v['order_amount']+$v['shipping_fee'];
            
            //支付名称
            if (isset($v['payment_code'])) {
                $order_list[$k]['payment_name'] = orderPaymentName($v['payment_code']);
            }

        }


        //追加返回订单扩展表信息
        if (in_array('order_common',$extend)) {
            foreach ($order_list as $k => $v) {
                $tmp = Model()->table('order_common')
                                                       ->field($tables['order_common'])
                                                       ->where(array('order_id'=>$v['order_id']))
                                                           ->find();
                $reciver_info_arr = unserialize($tmp['reciver_info']);
               
                //收货人地址姓名电话
                $order_list[$k]['order_common_ext']['reciver_name'] = $tmp['reciver_name'];
                $order_list[$k]['order_common_ext']['reciver_phone'] = $reciver_info_arr['phone'];
                $kongbai = array(" ","  ","\t","\r","\n");
                $order_list[$k]['order_common_ext']['reciver_addr'] = strip_tags(str_replace($kongbai,"",$reciver_info_arr['address']));
            }
        }

        //追加返回店铺信息
        if (in_array('store',$extend)) {
            foreach ($order_list as $k => $v) {
                $order_list[$k]['store_ext'] = Model()->table('store')->field($tables['store'])->where(array('store_id'=>$v['store_id']))->find();
            }
            
        }

        //返回买家信息
        if (in_array('member',$extend)) {
            foreach ($order_list as $k => $v) {
                $order_list[$k]['member_ext'] = Model()->table('member')->field($tables['member'])->where(array('member_id'=>$v['buyer_id']))->find();
            }
        }

        //追加返回商品信息
        if (in_array('order_goods',$extend)) {
            foreach ($order_list as $k => $v) {
                $order_list[$k]['goods_ext'] = Model()->table('order_goods')
                                                    ->field($tables['order_goods'])
                                                    ->where(array('order_id'=>$v['order_id']))
                                                    ->select();
                if(is_array($order_list[$k]['goods_ext'])){
                   foreach ($order_list[$k]['goods_ext'] as $m => $n) {
                         $order_list[$k]['goods_ext'][$m]['goods_image'] = cthumb($n['goods_image'], 240, $n['store_id'], $n['goods_id']);
                    }   
                }
                
             }
        }
        return $order_list;
    }   


    /**
     * [getOrderdel description]
     * @author fulijun
     * @dateTime 2016-09-28T15:38:04+0800
     * @return   [type]                   [description]
     */
    public function getOrderdel($member_id,$order_id,$member_name){
        try{
            $model_order=Model('order');
            //开启事务
            $model_order->beginTransaction();

                $condition = array('buyer_id'=>$member_id,'order_id'=>$order_id);
                $tables = array(
                    'order'=>'order_id,order_sn,order_state,payment_code,store_id,store_id,buyer_id,order_amount,rcb_amount,pd_amount,shipping_fee,add_time',
                    'order_goods'=>'goods_id,goods_name,goods_price,goods_num,goods_pay_price',
                    'order_common'=>'order_id,reciver_name,reciver_info,invoice_info',
                    'store'=>'store_id,store_name',
                    'member'=>'member_id,member_name'
                );
                //取得订单信息
                $order_detail = $this->getOrderdetail($condition,$tables);

                //goods_id=>goods_num商品ID对应购买数量
                $goods_list = Model()->table('order_goods')->where(array('buyer_id'=>$member_id,'order_id'=>$order_id))->select();

                $goodsid_arr = array();
                if(isset($goods_list)){
                    foreach ($goods_list as $k => $v) {
                        $goodsid_arr[$v['goods_id']] = $v['goods_num'];
                    }
                }

                //更新库存和销售量：暂不管销量，因为原始数据销量对不上，醉了
                foreach ($goodsid_arr as $k=> $v) {
                        //更新库存和销售量
                        $gcondition = array('goods_id'=>array('IN',$k));
                        $gdata = array(
                            'goods_storage'=>array('exp','goods_storage+'.$v),
                            //'goods_salenum'=>array('exp','goods_salenum-'.$v),
                            'goods_edittime'=>TIMESTAMP
                        );
                        //先查库存是否存在
                        $isgoods = Model()->table('goods')->where($gcondition)->field('goods_name,goods_storage,goods_salenum')->find();
                        if($isstore['goods_salenum'] - $v >= 0){
                            $gdata['goods_salenum'] = array('exp','goods_salenum-'.$v);
                        }
                        Model()->table('goods')->where($gcondition)->update($gdata);
                }
                //解冻充值卡
                $model_pd = Model('predeposit');
                $rcb_amount = floatval($order_detail['rcb_amount']);
                if ($rcb_amount > 0) {
                    $data_pd = array();
                    $data_pd['member_id'] = $order_detail['buyer_id'];
                    $data_pd['member_name'] = $order_detail['buyer_name'];
                    $data_pd['amount'] = $rcb_amount;
                    $data_pd['order_sn'] = $order_detail['order_sn'];
                    $model_pd->changeRcb('order_cancel',$data_pd);
                }
                
                //解冻预存款
                $pd_amount = floatval($order_detail['pd_amount']);
                if ($pd_amount > 0) {
                    $data_pd = array();
                    $data_pd['member_id'] = $order_detail['buyer_id'];
                    $data_pd['member_name'] = $order_detail['buyer_name'];
                    $data_pd['amount'] = $pd_amount;
                    $data_pd['order_sn'] = $order_detail['order_sn'];
                    $model_pd->changePd('order_cancel',$data_pd);
                } 
                //更新订单信息
                $save_data = array('order_state'=>0,'pd_amount'=>0);
                $save_con = array('order_id'=>$order_id,'buyer_id'=>$member_id);
                $issave = Model()->table('order')->where($save_con)->update($save_data);
                if(!$issave){
                    return json_return(501,'取消订单失败');
                }  
                //添加订单日志
                $log_data = array(
                        'order_id'=>$order_id,
                        'log_role'=>'买家',
                        'log_msg'=>'取消了订单',
                        'log_user'=>$member_name,
                        'log_orderstate'=>0,
                        'log_time'=>TIMESTAMP
                );

                $islog = Model()->table('order_log')->insert($log_data);
                $model_order->commit();
                if($islog){
                    return json_return(400,'取消订单成功');
                }
                return json_return(500,'取消订单失败');
                
        }catch(Exception $e){
            $model_order->rollback();
            return json_return(501,'取消订单失败');
        }

    } 



    /**
     * [取得商品信息-购物车过来和直接过来都有]
     * @author fulijun
     * @dateTime 2016-10-20T16:01:52+0800
     * @param    [Int]                    $member_id [会员ID]
     * @param    [type]                   $buyArr    [description]
     * @return   [type]                              [description]
     */
    public function getGoodsinfo($member_id,$buyArr,$iscart){
        if(!$member_id || !is_array($buyArr) || empty($buyArr)){
           return array();
        }
        if($iscart == 0){//直接购买确认订单
            $goods_id = key($buyArr);
            $num = current($buyArr);
            $data = $this->directBuying($goods_id,$num);
        }else{//购物车过来确认订单
            $data = $this->cartBuying($member_id,$buyArr);     
        }
        return $data;   
    }



    public function gotoReceive($order_id,$member_id,$member_name){

        try{
            //取得订单信息
            $order_info = Model()->table('order')
                                ->field('order_id,order_sn,pay_sn,buyer_id,buyer_name,order_amount,goods_rebate_amount,area_manager_difference')
                                ->find($order_id);
            //更新订单状态
            $data = array(
                'order_id'=>$order_id,
                'order_state'=>40,
                'finnshed_time'=>TIMESTAMP
            );
            $isupdate = Model()->table('order')->update($data);
            if(!$isupdate){
                return json_return(500,'更新订单状态失败');
            }
            //添加返利，待添加
            //$israte = Model()->table('member')->where(array('member_id'=>$member_id))->setInc('member_salescredit',$order_info['member_salescredit']);

            //添加订单日志
            $logdata = array(
                'order_id'=>$order_id,
                'log_role'=>'buyer',
                'log_msg'=>'签收了货物',
                'log_user'=>$member_name,
                'log_orderstate'=>40
                );
            Model('order')->addOrderLog($logdata);

            //添加会员积分
            if (C('points_isuse') == 1){
                $poicon = array(
                    'pl_memberid'=>$order_info['buyer_id'],
                    'pl_membername'=>$order_info['buyer_name'],
                    'orderprice'=>$order_info['order_amount'],
                    'order_sn'=>$order_info['order_sn'],
                    'order_id'=>$order_info['order_id']
                );
                Model('points')->savePointsLog('order',$poicon,true);
            }

            //添加会员经验值
            $expcon = array(
                'exp_memberid'=>$order_info['buyer_id'],
                'exp_membername'=>$order_info['buyer_name'],
                'orderprice'=>$order_info['order_amount'],
                'order_sn'=>$order_info['order_sn'],
                'order_id'=>$order_info['order_id']
            );
            Model('exppoints')->saveExppointsLog('order',$expcon,true);
            //邀请人获得返利积分
            if($inviter_id > 0 && $inviter_name){
                $inviter_id = $model_member->getfby_member_id($member_id,'inviter_id');
                $inviter_name = $model_member->table('member')->getfby_member_id($inviter_id,'member_name');
            }
            $rebate_amount = ceil(0.01 * $order_info['order_amount'] * $GLOBALS['setting_config']['points_rebate']);
            $poicon = array(
                'pl_memberid'=>$inviter_id,
                'pl_membername'=>$inviter_name,
                'rebate_amount'=>$rebate_amount
            );
            Model('points')->savePointsLog('rebate',$poicon,true);
            return json_return(400,'确认收货成功');                
        } catch (Exception $e) {
            return json_return(500,'确认收货失败');
        } 

    }


    /**
     * [直接购买，不是从购买车过来]
     * @author fulijun
     * @dateTime 2016-10-30T13:25:00+0800
     * @return   [type]                   [description]
     */
    public function directBuying($goods_id,$num){
        $goods_info = array();

        //取得商品信息
        $goods_info = Model()->table('goods')->find($goods_id);
        if($goods_info['goods_state'] != 1){
            return json_return(500,'商品已下架');
        }
        $goods_info['goods_num'] = $num ?$num:1;


        //取得促销和团购信息
        $goods_commonid = $goods_info['goods_commonid'];
        $tmp= Server('goods')->getPromoGroupById($goods_id,'xianshi_title,lower_limit,xianshi_price,xianshi_explain',$goods_info['goods_commonid'],'*');

        //取得商品调节参数
        $ajust_rate =  Model()->table('goods_common')->getfby_goods_commonid($goods_commonid,'adjust_rebate_rate');
       
        //取得商品的赠品
        $gifcon = array('goods_id'=>$goods_id);
        $gift_info = Model()->table('goods_gift')->field('goods_id,gift_goodsid,gift_goodsname,gift_goodsimage,gift_amount')->where($gifcon)->select();
        
        //组装数据
        $goods_info['state'] = true;
        $goods_info['storage_state'] = intval($goods_info['goods_storage']) < intval($num) ? false : true;
        $goods_info['groupbuy_info'] = $tmp['groupbuy_info'];
        $goods_info['xianshi_info'] =  $tmp['promo_info'];
        $goods_info['adjust_rebate_rate'] = $ajust_rate;
        //填充必要下标，方便后面统一使用购物车方法与模板
        $goods_info['cart_id'] = $goods_id;
        $goods_info['bl_id'] = 0;

        //进一步处理数组
        $store_cart_list = array();
        $goods_list = array();
        $goods_list[0] = $store_cart_list[$goods_info['store_id']][0] = $goods_info;
        $data['goods_list']  = $goods_list;
        $data['store_cart_list']  = $store_cart_list;
        return $data;
    }

    /**
     * [购物车购买，确认订单]
     * @author fulijun
     * @dateTime 2016-10-30T19:02:02+0800
     * @return   [type]                   [description]
     */
    public function cartBuying($member_id,$buyArr){
        //取得购物车里的商品并计算
        $condition = array('goods_id'=>array('in',array_keys($buyArr)), 'buyer_id'=>$member_id);
        $cart_list = Model()->table("cart")->where($condition)->select();
        if(empty($cart_list) || !is_array($cart_list)){
          return array();
        }

        //取得组合套装的运费
        foreach($cart_list as $key => $val){
          if($val['bl_id']){
            $shipping_fee = Model()->table('p_bundling')->where(array('bl_id' => $val['bl_id']))->field('bl_freight')->select();
            $cart_list[$key]['shipping_fee'] = $shipping_fee[0]['bl_freight'];
          }
        }
    
        $package_goods = Logic('buy_1')->getGoodsCartList($cart_list);
        
        //套装按商家分类
        $tmp = Server('goods')->sortPackageBybusi($package_goods);
        //组织数据
        $data['goods_list'] = $tmp['goods_list'];
        $package_busigoods = array();
        foreach ($package_goods as $k => $v) {
          $package_busigoods[$v['store_id']][] = $v;
        }
        $data['store_cart_list'] = $package_busigoods;
        
        return $data;
    }


    /**
     * [提交订单-2取得商品信息]
     * @author fulijun
     * @dateTime 2016-10-30T19:40:42+0800
     * @return   [type]                   [description]
     */
    public function submitGoodsinfo($member_id,$buyArr,$iscart){   
       $tmp= $this->getGoodsinfo($member_id,$buyArr,$iscart);
       $goods_list = $tmp['goods_list'];

       //验证F码
       foreach ($goods_list as $k => $v) {
           if($v['is_fcode'] == 1){
                $fcodecon = array(
                    'goods_commonid'=>$v['goods_commonid'],
                    'fc_code'=>$v['fcode'],
                    'fc_state'=>0
                );
                $isfcode = Model()->table('goods_fcode')->where($fcodecon)->find();
           }
       }

    }
}
