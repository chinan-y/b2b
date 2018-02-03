<?php
/**
 * 购买行为	by Ming
 *
 */
defined('GcWebShop') or exit('Access Invalid!');
class buyLogic {

    /**
     * 会员信息
     * @var array
     */
    private $_member_info = array();

    /**
     * 下单数据
     * @var array
     */
    private $_order_data = array();

    /**
     * 表单数据
     * @var array
     */
    private $_post_data = array();

    /**
     * buy_1.logic 对象
     * @var obj
     */
    private $_logic_buy_1;

    public function __construct() {
        error_reporting(E_ERROR);
        $this->_logic_buy_1 = Logic('buy_1');
		$this->_logic_tax_1 = Logic('tax');
    }

    /**
     * 购买第一步
     * @param unknown $cart_id
     * @param unknown $ifcart
     * @param unknown $member_id
     * @param unknown $store_id
     * @return Ambigous <multitype:unknown, multitype:unknown >
     */
    public function buyStep1($cart_id, $ifcart, $member_id, $store_id,$third="") {

        //得到购买商品信息
        if ($ifcart) {
            $result = $this->getCartList($cart_id, $member_id,$third);
        } else {
            $result = $this->getGoodsList($cart_id, $member_id, $store_id);
        }

        if(!$result['state']) {
            return $result;
        }

        //得到页面所需要数据：收货地址、发票、代金券、预存款、商品列表等信息
        $result = $this->getBuyStep1Data($member_id,$result['data']);
        return $result;
    }

    /**
     * 第一步：处理购物车
     *
     * @param array $cart_id 购物车
     * @param int $member_id 会员编号
     */
    public function getCartList($cart_id, $member_id,$third="") {
        $model_cart = Model('cart');

        //取得POST ID和购买数量
        $buy_items = $this->_parseItems($cart_id);
        if (empty($buy_items)) {
            return callback(false, '所购商品无效');
        }

        if (count($buy_items) > 50) {
            return callback(false, '一次最多只可购买50种商品');
        }

        //购物车列表
        $condition = array('cart_id'=>array('in',array_keys($buy_items)), 'buyer_id'=>$member_id);
        $cart_list	= $model_cart->listCart('db', $condition);
		foreach($cart_list as $key => $val){
			if($val['bl_id']){
				$shipping_fee = Model()->table('p_bundling')->where(array('bl_id' => $val['bl_id']))->field('bl_freight')->select();
				$cart_list[$key]['shipping_fee'] = $shipping_fee[0]['bl_freight'];
			}
		}
		//$cart_list	= $model_cart->order('goods_id asc')->listCart('db', $condition);

        //购物车列表 [得到最新商品属性及促销信息]
        $cart_list = $this->_logic_buy_1->getGoodsCartList($cart_list,$third);

        //商品列表 [优惠套装子商品与普通商品同级罗列]
        $goods_list = $this->_getGoodsList($cart_list);

        //以店铺下标归类
        $store_cart_list = $this->_getStoreCartList($cart_list);

        return callback(true, '', array('goods_list' => $goods_list, 'store_cart_list' => $store_cart_list));

    }

    /**
     * 第一步：处理立即购买
     *
     * @param array $cart_id 购物车
     * @param int $member_id 会员编号
     * @param int $store_id 店铺编号
     */
    public function getGoodsList($cart_id, $member_id, $store_id) {

        //取得POST ID和购买数量
        $buy_items = $this->_parseItems($cart_id);
        if (empty($buy_items)) {
            return callback(false, '所购商品无效');
        }

        $goods_id = key($buy_items);
        $quantity = current($buy_items);

        //商品信息[得到最新商品属性及促销信息]
        $goods_info = $this->_logic_buy_1->getGoodsOnlineInfo($goods_id,intval($quantity));
        if(empty($goods_info)) {
            return callback(false, '商品已下架或不存在');
        }

        //不能购买自己店铺的商品
        if ($goods_info['store_id'] == $store_id) {
            return callback(false, '不能购买自己店铺的商品');
        }

        //进一步处理数组
        $store_cart_list = array();
        $goods_list = array();
        $goods_list[0] = $store_cart_list[$goods_info['store_id']][0] = $goods_info;

        return callback(true, '', array('goods_list' => $goods_list, 'store_cart_list' => $store_cart_list));
    }

    /**
     * 购买第一步：返回商品、促销、地址、发票等信息，然后交前台抛出
     * @param unknown $member_id
     * @param unknown $data 商品信息
     * @return
     */
    public function getBuyStep1Data($member_id, $data) {
        list($goods_list,$store_cart_list) = $data;
        $goods_list = $data['goods_list'];
        $store_cart_list = $data['store_cart_list'];

        //定义返回数组
        $result = array();

        //商品金额计算(分别对每个商品/优惠套装小计、每个店铺小计)
        list($store_cart_info,$store_goods_total) = $this->_logic_buy_1->calcCartList($store_cart_list);

        $result['store_cart_list'] = $store_cart_info;
        $result['store_goods_total'] = $store_goods_total;
		
        //取得店铺优惠 - 满即送(赠品列表，店铺满送规则列表)
        list($store_premiums_list,$store_mansong_rule_list) = $this->_logic_buy_1->getMansongRuleCartListByTotal($store_goods_total);
        $result['store_premiums_list'] = $store_premiums_list;
        $result['store_mansong_rule_list'] = $store_mansong_rule_list;
		
		foreach($store_cart_list as $key => $value){
			
			$mansong_discount = ($store_mansong_rule_list[$key]['discount'] / $store_goods_total[$key] );
			foreach($value as $k =>$val){ 
				$store_cart_list[$key][$k]['mansong_discount'] = floatval(number_format(($mansong_discount * $val['goods_price'] ) ,5));
			}
		}
		//单独计算总税金(商品价格减去满及送优惠金额再计算)
		$store_taxes_total = $this->_logic_buy_1->calcCartList_taxes($store_cart_list);
		
		foreach($store_taxes_total as $key => $value){
			foreach($value as $k =>$val){ 
				$taxes_total[$key] += $val['taxes_total'];
			}
			$store_taxes_total[$key] = ncPriceFormat($taxes_total[$key]);
		}
		$result['store_taxes_total'] = $store_taxes_total;

        //重新计算优惠后(满即送)的店铺实际商品总金额
        $store_goods_total = $this->_logic_buy_1->reCalcGoodsTotal($store_goods_total,$store_mansong_rule_list,'mansong');

        //返回店铺可用的代金券
        $store_voucher_list = $this->_logic_buy_1->getStoreAvailableVoucherList($store_goods_total, $member_id);
        $result['store_voucher_list'] = $store_voucher_list;

        //返回需要计算运费的店铺ID数组 和 不需要计算运费(满免运费活动的)店铺ID及描述
        list($need_calc_sid_list,$cancel_calc_sid_list) = $this->_logic_buy_1->getStoreFreightDescList($store_goods_total);
        $result['need_calc_sid_list'] = $need_calc_sid_list;
        $result['cancel_calc_sid_list'] = $cancel_calc_sid_list;

        //将商品ID、数量、运费模板、运费序列化，加密，输出到模板，选择地区AJAX计算运费时作为参数使用
        $freight_list = $this->_logic_buy_1->getStoreFreightList($goods_list,array_keys($cancel_calc_sid_list));
        $result['freight_list'] = $this->buyEncrypt($freight_list, $member_id);

        //输出用户默认收货地址
        $result['address_info'] = Model('address')->getDefaultAddressInfo(array('member_id'=>$member_id));

        //输出有货到付款时，在线支付和货到付款及每种支付下商品数量和详细列表
        $pay_goods_list = $this->_logic_buy_1->getOfflineGoodsPay($goods_list);
        if (!empty($pay_goods_list['offline'])) {
            $result['pay_goods_list'] = $pay_goods_list;
            $result['ifshow_offpay'] = true;
        } else {
            //如果所购商品只支持线上支付，支付方式不允许修改
            $result['deny_edit_payment'] = true;
        }

        //发票 :只有所有商品都支持增值税发票才提供增值税发票
        foreach ($goods_list as $goods) {
        	if (!intval($goods['goods_vat'])) {
        	    $vat_deny = true;break;
        	}
        }
        //不提供增值税发票时抛出true(模板使用)
        $result['vat_deny'] = $vat_deny;
        $result['vat_hash'] = $this->buyEncrypt($result['vat_deny'] ? 'deny_vat' : 'allow_vat', $member_id);

        //输出默认使用的发票信息
        $inv_info = Model('invoice')->getDefaultInvInfo(array('member_id'=>$member_id));
        if ($inv_info['inv_state'] == '2' && !$vat_deny) {
            $inv_info['content'] = '增值税发票 '.$inv_info['inv_company'].' '.$inv_info['inv_code'].' '.$inv_info['inv_reg_addr'];
        } elseif ($inv_info['inv_state'] == '2' && $vat_deny) {
            $inv_info = array();
            $inv_info['content'] = '不需要发票';
        } elseif (!empty($inv_info)) {
            $inv_info['content'] = '普通发票 '.$inv_info['inv_title'].' '.$inv_info['inv_content'];
        } else {
            $inv_info = array();
            $inv_info['content'] = '不需要发票';
        }
        $result['inv_info'] = $inv_info;

        $buyer_info	= Model('member')->getMemberInfoByID($member_id);
        if (floatval($buyer_info['available_predeposit']) > 0) {
            $result['available_predeposit'] = $buyer_info['available_predeposit'];
        }
        if (floatval($buyer_info['available_rc_balance']) > 0) {
            $result['available_rc_balance'] = $buyer_info['available_rc_balance'];
        }
		
		if (intval($buyer_info['member_points']/C('exchange_rate')) > 0) {
			$member_points = array();
			foreach($store_goods_total as $store_id =>$goods_total){
				$member_points[$store_id] = $buyer_info['member_points'];
				break;
			}
            $result['member_points'] = $member_points;
        }
        $result['member_paypwd'] = $buyer_info['member_paypwd'] ? true : false;

        return callback(true,'',$result);
    }

    /**
     * 购买第二步
     * @param array $post
     * @param int $member_id
     * @param string $member_name
     * @param string $member_email
     * @param array $buy_encrypt
     * @return array
     */
    public function buyStep2($post, $member_id, $member_name, $member_email, $buy_encrypt=array()) {
		
        
        $this->_member_info['member_id'] = $member_id;
        $this->_member_info['member_name'] = $member_name;
        $this->_member_info['member_email'] = $member_email;
        $this->_post_data = $post;

        try {

            $model = Model('order');
            $model->beginTransaction();

            //第1步 表单验证
            $this->_createOrderStep1();
    
            //第2步 得到购买商品信息
            $this->_createOrderStep2();
            //第3步 得到购买相关金额计算等信息
            $this->_createOrderStep3();
    
            //第4步 生成订单
            $this->_createOrderStep4($buy_encrypt);

            //第5步 处理预存款
            $this->_createOrderStep5();
            $model->commit();

            //第6步 订单后续处理
            $this->_createOrderStep6($buy_encrypt);

            return callback(true,'',$this->_order_data);

        }catch (Exception $e){
            $model->rollback();
            return callback(false, $e->getMessage());
        }

    }

    /**
     * 删除购物车商品
     * @param unknown $ifcart
     * @param unknown $cart_ids
     */
    public function delCart($ifcart, $member_id, $cart_ids) {
        if (!$ifcart || !is_array($cart_ids)) return;
        $cart_id_str = implode(',',$cart_ids);
        if (preg_match('/^[\d,]+$/',$cart_id_str)) {
            QueueClient::push('delCart', array('buyer_id'=>$member_id,'cart_ids'=>$cart_ids));
        }
    }
    
    /**
     * 选择不同地区时，异步处理并返回每个店铺总运费以及本地区是否能使用货到付款
     * 如果店铺统一设置了满免运费规则，则运费模板无效
     * 如果店铺未设置满免规则，且使用运费模板，按运费模板计算，如果其中有商品使用相同的运费模板，则两种商品数量相加后再应用该运费模板计算（即作为一种商品算运费）
     * 如果未找到运费模板，按免运费处理
     * 如果没有使用运费模板，商品运费按快递价格计算，运费不随购买数量增加
     */
    public function changeAddr($freight_hash, $city_id, $area_id, $member_id) {
        //$city_id计算运费模板,$area_id计算货到付款
        $city_id = intval($city_id);
        $area_id = intval($area_id);
        if ($city_id <= 0 || $area_id <= 0) return null;
    
        //将hash解密，得到运费信息(店铺ID，运费,运费模板ID,购买数量),hash内容有效期为1小时
        $freight_list = $this->buyDecrypt($freight_hash, $member_id);
    
        //算运费
        $store_freight_list = $this->_logic_buy_1->calcStoreFreight($freight_list, $city_id);
        $data = array();
        $data['state'] = empty($store_freight_list) ? 'fail' : 'success';
        $data['content'] = $store_freight_list;
    
        //是否能使用货到付款(只有包含平台店铺的商品才会判断)
        //$if_include_platform_store = array_key_exists(DEFAULT_PLATFORM_STORE_ID,$freight_list['iscalced']) || array_key_exists(DEFAULT_PLATFORM_STORE_ID,$freight_list['nocalced']);
    
        //$offline_store_id_array = Model('store')->getOwnShopIds();
        $order_platform_store_ids = array();
    
        if (is_array($freight_list['iscalced']))
        foreach (array_keys($freight_list['iscalced']) as $k)
        //if (in_array($k, $offline_store_id_array))
            $order_platform_store_ids[$k] = null;
    
        if (is_array($freight_list['nocalced']))
        foreach (array_keys($freight_list['nocalced']) as $k)
        //if (in_array($k, $offline_store_id_array))
            $order_platform_store_ids[$k] = null;
    
        //if ($order_platform_store_ids) {
            $allow_offpay_batch = Model('offpay_area')->checkSupportOffpayBatch($area_id, array_keys($order_platform_store_ids));
    /*
            //JS验证使用
            $data['allow_offpay'] = array_filter($allow_offpay_batch) ? '1' : '0';
            $data['allow_offpay_batch'] = $allow_offpay_batch;
        } else {*/
            //JS验证使用
            $data['allow_offpay'] = array_filter($allow_offpay_batch) ? '1' : '0';
            $data['allow_offpay_batch'] = $allow_offpay_batch;
        //}

        //PHP验证使用
        $data['offpay_hash'] = $this->buyEncrypt($data['allow_offpay'] ? 'allow_offpay' : 'deny_offpay', $member_id);
        $data['offpay_hash_batch'] = $this->buyEncrypt($data['allow_offpay_batch'], $member_id);

        return $data;
    }
    
    /**
     * 验证F码
     * @param int $goods_commonid
     * @param string $fcode
     * @return array
     */
    public function checkFcode($goods_commonid, $fcode) {
        $fcode_info = Model('goods_fcode')->getGoodsFCode(array('goods_commonid' => $goods_commonid,'fc_code' => $fcode,'fc_state' => 0));
        if ($fcode_info) {
            return callback(true,'',$fcode_info);
        } else {
            return callback(false,'F码错误');
        }
    }

    /**
     * 订单生成前的表单验证与处理
     *
     */
    private function _createOrderStep1() {
        $post = $this->_post_data;

        //取得商品ID和购买数量
        $input_buy_items = $this->_parseItems($post['cart_id']);
        if (empty($input_buy_items)) {
            throw new Exception('所购商品无效');
        }

        //验证收货地址
        $input_address_id = intval($post['address_id']);
        if ($input_address_id <= 0) {
            throw new Exception('请选择收货地址');
        } else {
            $input_address_info = Model('address')->getAddressInfo(array('address_id'=>$input_address_id));
            if ($input_address_info['member_id'] != $this->_member_info['member_id']) {
                throw new Exception('请选择收货地址');
            }
        }

        //收货地址城市编号
        $input_area_id = intval($input_address_info['area_id']);//order_common表增加
		$input_city_id = intval($input_address_info['city_id']);

        //是否开增值税发票
        $input_if_vat = $this->buyDecrypt($post['vat_hash'], $this->_member_info['member_id']);
        if (!in_array($input_if_vat,array('allow_vat','deny_vat'))) {
            throw new Exception('订单保存出现异常[值税发票出现错误]，请重试');
        }
        $input_if_vat = ($input_if_vat == 'allow_vat') ? true : false;

        //是否支持货到付款
        $input_if_offpay = $this->buyDecrypt($post['offpay_hash'], $this->_member_info['member_id']);
        if (!in_array($input_if_offpay,array('allow_offpay','deny_offpay'))) {
            throw new Exception('订单保存出现异常[货到付款验证错误]，请重试');
        }
        $input_if_offpay = ($input_if_offpay == 'allow_offpay') ? true : false;

        // 是否支持货到付款 具体到各个店铺
        $input_if_offpay_batch = $this->buyDecrypt($post['offpay_hash_batch'], $this->_member_info['member_id']);
        if (!is_array($input_if_offpay_batch)) {
            throw new Exception('订单保存出现异常[部分店铺付款方式出现异常]，请重试');
        }

        //付款方式:在线支付/货到付款(online/offline)
        if (!in_array($post['pay_name'],array('online','offline'))) {
            throw new Exception('付款方式错误，请重新选择');
        }
        $input_pay_name = $post['pay_name'];

        //验证发票信息
        if (!empty($post['invoice_id'])) {
            $input_invoice_id = intval($post['invoice_id']);
            if ($input_invoice_id > 0) {
                $input_invoice_info = Model('invoice')->getinvInfo(array('inv_id'=>$input_invoice_id));
                if ($input_invoice_info['member_id'] != $this->_member_info['member_id']) {
                    throw new Exception('请正确填写发票信息');
                }
            }
        }

        //验证代金券
        $input_voucher_list = array();
        if (!empty($post['voucher']) && is_array($post['voucher'])) {
            foreach ($post['voucher'] as $store_id => $voucher) {
                if (preg_match_all('/^(\d+)\|(\d+)\|([\d.]+)$/',$voucher,$matchs)) {
                    if (floatval($matchs[3][0]) > 0) {
                        $input_voucher_list[$store_id]['voucher_t_id'] = $matchs[1][0];
                        $input_voucher_list[$store_id]['voucher_price'] = $matchs[3][0];
                    }
                }
            }
        }
		//验证积分
        if (!empty($post['member_points'])) {
			$member_points = array();
			$member_points[$post['points_store_id']] = $post['member_points'];
			$this->_order_data['input_member_points'] = $member_points;
        }
        //保存数据
        $this->_order_data['input_buy_items'] = $input_buy_items;
		$this->_order_data['input_area_id'] = $input_area_id;
        $this->_order_data['input_city_id'] = $input_city_id;
        $this->_order_data['input_pay_name'] = $input_pay_name;
        $this->_order_data['input_if_offpay'] = $input_if_offpay;
        $this->_order_data['input_if_offpay_batch'] = $input_if_offpay_batch;
        $this->_order_data['input_pay_message'] = $post['pay_message'];
        $this->_order_data['input_address_info'] = $input_address_info;
        $this->_order_data['input_invoice_info'] = $input_invoice_info;
        $this->_order_data['input_voucher_list'] = $input_voucher_list;
        $this->_order_data['order_from'] = $post['order_from'] == 2 ? 2 : 1;

    }

    /**
     * 得到购买商品信息
     *
     */
    private function _createOrderStep2() {
        $post = $this->_post_data;
        $input_buy_items = $this->_order_data['input_buy_items'];
       
        if ($post['ifcart']) {
            
            //购物车列表
            $model_cart = Model('cart');
            $condition = array('cart_id'=>array('in',array_keys($input_buy_items)),'buyer_id'=>$this->_member_info['member_id']);
            $cart_list	= $model_cart->listCart('db',$condition);

            //购物车列表 [得到最新商品属性及促销信息]
            $cart_list = $this->_logic_buy_1->getGoodsCartList($cart_list,$post['third']);
			foreach($cart_list as $key => $val){
				if($val['bl_id']){
					$shipping_fee = Model()->table('p_bundling')->where(array('bl_id' => $val['bl_id']))->field('bl_freight')->select();
					$cart_list[$key]['shipping_fee'] = $shipping_fee[0]['bl_freight'];
				}
			}
            //商品列表 [优惠套装子商品与普通商品同级罗列]
            $goods_list = $this->_getGoodsList($cart_list);

            //以店铺下标归类
            $store_cart_list = $this->_getStoreCartList($cart_list);
        } else {
			$info=array();
            //来源于直接购买
			foreach($input_buy_items as $key=>$value){
            $goods_id = $key;
            $quantity = $value;

            //商品信息[得到最新商品属性及促销信息]
            $goods_info = $this->_logic_buy_1->getGoodsOnlineInfo($goods_id,intval($quantity));
            if(empty($goods_info)) {
                throw new Exception('商品已下架或不存在');
            }

            //进一步处理数组
            $store_cart_list = array();
            $goods_list = array();
            $info[]=$goods_list[0] = $store_cart_list[$goods_info['store_id']][0] = $goods_info;
			}
        }

        //F码验证
        $fc_id = $this->_checkFcode($goods_list, $post['fcode']);
        if(!$fc_id) {
            throw new Exception('F码商品验证错误');
        }
        //保存数据
		$this->_order_data['info']=$info;
        $this->_order_data['goods_list'] = $goods_list;
        $this->_order_data['store_cart_list'] = $store_cart_list;
		if ($fc_id > 0) {
            $this->_order_data['fc_id'] = $fc_id;
        }

    }

    /**
     * 得到购买相关金额计算等信息
     *
     */
    private function _createOrderStep3() {
        $goods_list = $this->_order_data['goods_list'];
        $store_cart_list = $this->_order_data['store_cart_list'];
        $input_voucher_list = $this->_order_data['input_voucher_list'];
        $input_area_id = $this->_order_data['input_area_id'];
		$input_city_id = $this->_order_data['input_city_id'];

		//商品金额计算(分别对每个商品/优惠套装小计、每个店铺小计)
        list($store_cart_list,$store_goods_total) = $this->_logic_buy_1->calcCartList($store_cart_list);//增加返利

        //取得店铺优惠 - 满即送(赠品列表，店铺满送规则列表)
        list($store_premiums_list,$store_mansong_rule_list) = $this->_logic_buy_1->getMansongRuleCartListByTotal($store_goods_total);

        //重新计算店铺扣除满即送后商品实际支付金额
        $store_final_goods_total = $this->_logic_buy_1->reCalcGoodsTotal($store_goods_total,$store_mansong_rule_list,'mansong');
		
		foreach($store_cart_list as $key => $value){
			
			$mansong_discount = ($store_mansong_rule_list[$key]['discount'] / $store_goods_total[$key] );
			foreach($value as $k =>$val){ 
				$store_cart_list[$key][$k]['mansong_discount'] = floatval(number_format(($mansong_discount * $val['goods_price'] ) ,5));
			}
		}
		//单独计算总税金(商品价格减去满及送优惠金额再计算)
		$store_taxes_total= $this->_logic_buy_1->calcCartList_taxes($store_cart_list);
		foreach($store_taxes_total as $key => $value){
			foreach($value as $k =>$val){ 
				$store_cart_list[$key][$k]['taxes_total'] = $val['taxes_total'];
				$taxes_total[$key] += $val['taxes_total'];
			}
			$store_taxes_total[$key] = ncPriceFormat($taxes_total[$key]);
		}
        //得到有效的代金券
        $input_voucher_list = $this->_logic_buy_1->reParseVoucherList($input_voucher_list,$store_goods_total,$this->_member_info['member_id']);

        //重新计算店铺扣除优惠券送商品实际支付金额
        $store_final_goods_total = $this->_logic_buy_1->reCalcGoodsTotal($store_final_goods_total,$input_voucher_list,'voucher');
		
		//重新计算店铺扣除积分抵扣后商品实际支付金额
        $store_final_goods_total = $this->_logic_buy_1->reCalcGoodsTotal($store_final_goods_total,$this->_order_data['input_member_points'],'points');

        //计算每个店铺(所有店铺级优惠活动)总共优惠多少
        $store_promotion_total = $this->_logic_buy_1->getStorePromotionTotal($store_goods_total, $store_final_goods_total);

        //计算每个店铺运费(如果使用了代金券就提交没使用代金券的金额算运费)
        if(empty($input_voucher_list) && empty($this->_order_data['input_member_points'])){
			list($need_calc_sid_list,$cancel_calc_sid_list) = $this->_logic_buy_1->getStoreFreightDescList($store_final_goods_total);
			
		}else{
			list($need_calc_sid_list,$cancel_calc_sid_list) = $this->_logic_buy_1->getStoreFreightDescList($store_goods_total);
		}
        $freight_list = $this->_logic_buy_1->getStoreFreightList($goods_list,array_keys($cancel_calc_sid_list));
        $store_freight_total = $this->_logic_buy_1->calcStoreFreight($freight_list,$input_city_id);

        //计算店铺最终订单实际支付金额(加上运费)
        $store_final_order_total = $this->_logic_buy_1->reCalcGoodsTotal($store_final_goods_total,$store_freight_total,'freight');

        //计算店铺分类佣金[改由任务计划]
        $store_gc_id_commis_rate_list = Model('store_bind_class')->getStoreGcidCommisRateList($goods_list);

        //将赠品追加到购买列表(如果库存0，则不送赠品)
        $append_premiums_to_cart_list = $this->_logic_buy_1->appendPremiumsToCartList($store_cart_list,$store_premiums_list,$store_mansong_rule_list,$this->_member_info['member_id']);
        if($append_premiums_to_cart_list === false) {
            throw new Exception('抱歉，您购买的商品库存不足，请重购买');
        } else {
            list($store_cart_list,$goods_buy_quantity,$store_mansong_rule_list) = $append_premiums_to_cart_list;
        }

        //保存数据
        $this->_order_data['store_goods_total'] = $store_goods_total;
        $this->_order_data['store_final_order_total'] = $store_final_order_total;
        $this->_order_data['store_freight_total'] = $store_freight_total;
        $this->_order_data['store_promotion_total'] = $store_promotion_total;
        $this->_order_data['store_gc_id_commis_rate_list'] = $store_gc_id_commis_rate_list;
        $this->_order_data['store_mansong_rule_list'] = $store_mansong_rule_list;
        $this->_order_data['store_cart_list'] = $store_cart_list;
        $this->_order_data['goods_buy_quantity'] = $goods_buy_quantity;
        $this->_order_data['input_voucher_list'] = $input_voucher_list;
		$this->_order_data['store_taxes_total'] = $store_taxes_total;

    }

    /**
     * 生成订单
     * @param array $input
     * @param array $buy_encrypt
     * @throws Exception
     * @return array array(支付单sn,订单列表)
     */
    private function _createOrderStep4($buy_encrypt = array()) {
		
		$input_member_points = $this->_order_data['input_member_points'];
		$goods_goods_taxes = $this->_order_data['goods_list'];
        extract($this->_order_data);
		$post = $this->_post_data;
		$input_address_id = intval($post['address_id']);
        $input_address_info = Model('address')->getAddressInfo(array('address_id'=>$input_address_id));
		
        $member_id = $this->_member_info['member_id'];
        $member_name = $this->_member_info['member_name'];
        $member_email = $this->_member_info['member_email'];
		
		$member_identity = Model('member')->getMemberInfo(array('member_id'=> $member_id), 'member_truename,member_code');
		$true_idnum = $member_identity['member_code'];
		$true_name  = $member_identity['member_truename'];
		
        $model_order = Model('order');

        //存储生成的订单数据
        $order_list = array();
        //存储通知信息
        $notice_list = array();

        //每个店铺订单是货到付款还是线上支付,店铺ID=>付款方式[在线支付/货到付款]
        $store_pay_type_list    = $this->_logic_buy_1->getStorePayTypeList(array_keys($store_cart_list), $input_if_offpay, $input_pay_name);

        /*foreach ($store_pay_type_list as $k => & $v) {
            if (empty($input_if_offpay_batch[$k]))
                $v = 'online';
        }*/ //结算两个店铺的商品时 7店铺有问题（货到付款还是线上支付？？？）

		$pay_sn=array();
		$order_pay_id=array();
		foreach ($store_cart_list as $store_id => $goods_list) {
			$pay_sn[$store_id] = $this->_logic_buy_1->makePaySn($member_id);
			$order_pay = array();
			if($buy_encrypt['p']!=''){
				//三方订单order_pay数据
				$order_pay['pay_sn']=$buy_encrypt['ps'];
				$order_pay['api_pay_state']='1';
			}else{
				$order_pay['pay_sn'] = $pay_sn[$store_id];	
			}
			$order_pay['buyer_id'] = $member_id;
			$order_pay_id[$store_id] = $model_order->addOrderPay($order_pay);
			
			if (!$order_pay_id[$store_id]) {
				throw new Exception('订单保存失败[未生成支付单]');
			}
		}
		
        //收货人信息
        list($reciver_info,$reciver_name,$reciver_idnum,$order_name) = $this->_logic_buy_1->getReciverAddr($input_address_info);

        foreach ($store_cart_list as $store_id => $goods_list) {
    		
			//满及送优惠(统一版的订单商品价格需要减去满及送的价格)
            $mansong_total = !empty($store_mansong_rule_list[$store_id]['discount']) ? $store_mansong_rule_list[$store_id]['discount'] : 0;
			
			//使用代金券的金额(统一版的订单商品价格需要加上代金券的金额)
            $voucher_total = !empty($input_voucher_list[$store_id]['voucher_price']) ? $input_voucher_list[$store_id]['voucher_price'] : 0;
			
			//所有优惠(包括代金券 满及送优惠，计算返利使用)
			$promotion_total = !empty($store_promotion_total[$store_id]) ? $store_promotion_total[$store_id] : 0;
			
			//积分抵扣的金额加在代金券里面一起算多个商品的分摊
			if($input_member_points[$store_id]){
				$voucher_total += $input_member_points[$store_id] / C('exchange_rate');
				$promotion_total -= $input_member_points[$store_id] / C('exchange_rate');
				$points_total = $input_member_points[$store_id] / C('exchange_rate');
			}
            $should_goods_total = $store_final_order_total[$store_id]-$store_freight_total[$store_id]+$points_total+$promotion_total;
			
			//本店总的优惠比例,保留5位小数(计算返利使用)
            $promotion_rate = abs(number_format($promotion_total/$should_goods_total,5));
            if ($promotion_rate <= 1) {
                $promotion_rate = floatval(substr($promotion_rate,0,7));
            } else {
                $promotion_rate = 0;
            }
			
			//使用满减金额的优惠比例
			$mansong_rate = abs(number_format($mansong_total/$should_goods_total,5));
            if ($mansong_rate <= 1) {
                $mansong_rate = floatval(substr($mansong_rate,0,7));
            } else {
                $mansong_rate = 0;
            }
 
			//使用代金券金额的优惠比例 (计算统一版非现金抵扣金额使用)
			$voucher_rate = abs(number_format($voucher_total/$should_goods_total,5));
            if ($voucher_rate <= 1) {
                $voucher_rate = floatval(substr($voucher_rate,0,7));
            } else {
                $voucher_rate = 0;
            }
			
			/*------------------------------订单返利start------------------------------*/
			$member = Model('member');
			$order = array();
			$goods_rebate = array();
			$rebate = array(); 							//销售员自身返利
			$one_rebate = array(); 						//一级返利
			$two_rebate = array(); 						//二级返利
			$three_rebate = array(); 					//三级返利
			$platform_rebate = array(); 				//平台合作方返利
			$area_rebate = array(); 					//区域合作方返利
			
			$is_rebate = C('salescredit_isuse'); 		//是否开启返利模式
			$is_one = C('one_rank_rebate'); 			//是否开启一级返利
			$is_two = C('two_rank_rebate'); 			//是否开启二级返利
			$is_three = C('three_rank_rebate'); 		//是否开启三级返利
			
			$rate = C('salescredit_rebate') / 100; 		//自身返利率
			$one_rate = C('one_rebate_rate') / 100; 	//一级返利率
			$two_rate = C('two_rebate_rate') / 100; 	//二级返利率
			$three_rate = C('three_rebate_rate') / 100; //三级返利率
			
			require_once(BASE_DATA_PATH.'/area/area.php');
			$reciver_province_id = intval($area_array[$input_city_id]['area_parent_id']);
			$member_info = $member->getMemberInfo(array('member_id'=>$member_id));
			if(trim($input_pay_message[$store_id]) == '样品'){
				$order['goods_rebate_amount'] = 0;
				$order['order_distinguish'] = 3 ;
			}else{
			if($is_rebate == 1){
				foreach($goods_list as $k =>$v){
					
					//计算商品金额
                    $goods_total = $v['goods_price'] * $v['goods_num'];
					
					//优惠后的金额（结算返利要减去代金券和满送等优惠再计算）
					$goods_pay_price = $goods_total - (number_format($goods_total*($promotion_rate),2));
					
					//商品返利（平台销售员）
					if($member_info['is_rebate'] == 1){
						//总的商品返利金额
						$rebate_total = $v['goods_rebate_rate'] * $goods_pay_price;
						if($is_one && !$is_two && !$is_three){
							$rebate[$k] = $rebate_total * $rate;
							$one_rebate[$k] = $rebate_total * $one_rate;
							
						}else if($is_one && $is_two && !$is_three){
							$rebate[$k] = $rebate_total * $rate;
							$one_rebate[$k] = $rebate_total * $one_rate;
							$two_rebate[$k] = $rebate_total * $two_rate;
							
						}else if($is_one && $is_two && $is_three){
							$rebate[$k] = $rebate_total * $rate;
							$one_rebate[$k] = $rebate_total * $one_rate;
							$two_rebate[$k] = $rebate_total * $two_rate;
							$three_rebate[$k] = $rebate_total * $three_rate;
						}
						//平台合作方（优先结算）
						if($member_info['saleplat_id']){
							$partner = Model('partner')->getPartnerInfo(array('partner_id'=>$member_info['saleplat_id']), 'member_id');
							$member_in = $member->getMemberInfo(array('member_id'=>$partner['member_id']),'member_rebate_rate');
							$platform_rebate[$k] = $member_in['member_rebate_rate'] * $goods_pay_price;
							$order['platform_member_id'] = $partner['member_id'];
							
						//区域合作方（按收货地址返利）						
						}else{
							$where = array();
							$where = ('sa_areaid='.$reciver_province_id.' or sa_areaid='.$input_city_id.' or sa_areaid='.$input_area_id);
							$sa_manager = Model('sales_area')-> getSalesAreaInfo($where,'sa_manager_id');
							$manager_in = Model('member')->getMemberInfo(array('member_id' =>$sa_manager['sa_manager_id']),'member_rebate_rate,is_manager');
							if($manager_in['is_manager'] == 1){
								$area_rebate[$k] = $manager_in['member_rebate_rate'] * $goods_pay_price;
							}
							$order['area_member_id'] = $sa_manager['sa_manager_id'] ;
						}
						$order['order_distinguish'] = 1 ;
						
					//销售员返利（平台、区域经理）
					}else if($member_info['is_member_rebate'] == 1 && $member_info['is_manager'] == 1){
						$rebate[$k] = $member_info['member_rebate_rate'] * $goods_pay_price;
						$order['order_distinguish'] = 0 ;
						
					}else{
						$order['goods_rebate_amount'] = 0;
						$order['order_distinguish'] = 2 ;
					}
					$order['goods_rebate_amount'] += number_format($rebate[$k],2);
					$order['one_rebate'] += number_format($one_rebate[$k],2);
					$order['two_rebate'] += number_format($two_rebate[$k],2);
					$order['three_rebate'] += number_format($three_rebate[$k],2);
					$order['platform_rebate'] += number_format($platform_rebate[$k],2);
					$order['area_rebate'] += number_format($area_rebate[$k],2);
				}
			}else{
				$order['goods_rebate_amount'] = 0;
				$order['order_distinguish'] = 2 ;
			}
			}
			/*------------------------------订单返利end------------------------------*/
			
			$order['order_sn'] = $this->_logic_buy_1->makeOrderSn($order_pay_id[$store_id]);
			if($buy_encrypt['ps']!=''){
				$order['pay_sn']=$buy_encrypt['ps'];
				$order['payment_time']=TIMESTAMP;
			}else{
				$order['pay_sn'] = $pay_sn[$store_id];
			}	
            $order['store_id'] = $store_id;
            $order['store_name'] = $goods_list[0]['store_name'];
            $order['buyer_id'] = $member_id;
            $order['buyer_name'] = $member_name;
            $order['out_trade_no'] = $post['out_trade_no'] ? $post['out_trade_no'] : '';
			if($post['partner_id']){
				$order['partner_id'] = $post['partner_id'];
			}else{
				$order['partner_id'] = $member_info['saleplat_id'];
			}
			if(!empty($member_email)){
				$order['buyer_email'] = $member_email;//email显示
			}
			$order['buyer_reciver_idnum'] = $true_idnum;
            $order['add_time'] = TIMESTAMP;
            $order['payment_code'] = $store_pay_type_list[$store_id];
            if($buy_encrypt['g']!=''){
				//三方订单反计算处理order表数据
				/* if($buy_encrypt['p']!=''){
				$order['order_state']=ORDER_STATE_PAY;
				} */
				$order['shipping_fee'] = $buy_encrypt['tr'];
                $goodsprice=array();
				$tax_total=array();
				$taxsum=array();
				$order_amount=array();
				foreach($buy_encrypt['g'] as $key=>$value){
				  $goods = $model_order->table('goods')->where(array('goods_serial'=>$value['s']))->select();
				  $tax_rate_info = Model()->table('tax_rate')->where(array('hs_code'=>$goods[0]['goods_hscode']))->select();
				  $consumption_tax = (1 - $tax_rate_info[0]['consumption_tax']) * $tax_rate_info[0]['consumption_tax'] * 0.7;
				  $normal_consumption_tax = (1 - $tax_rate_info[0]['consumption_tax']) * $tax_rate_info[0]['consumption_tax'] ;
				  $vat_tax = $tax_rate_info[0]['vat_tax'] * 0.7 ;
				  if(($normal_consumption_tax+$consumption_tax+$normal_consumption_tax*$consumption_tax*$vat_tax+$consumption_tax*$vat_tax+$normal_consumption_tax*$consumption_tax)!=0){
				      $price=(($value['pr']*$consumption_tax*$normal_consumption_tax)/$value['n'])/($normal_consumption_tax+$consumption_tax+$normal_consumption_tax*$consumption_tax*$vat_tax+$consumption_tax*$vat_tax+$normal_consumption_tax*$consumption_tax);				 
				 }else{
					  $price=($value['pr']-0.119 * $order['shipping_fee'])/1.119;
				  }
/* 				$cart_info['goods_price'] = $price;
				$cart_info['shipping_fee'] = $order['shipping_fee']?$order['shipping_fee']:0;
				$order_amount = ncPriceFormat($this->_logic_tax_1->single_times_allow_2000($cart_info)*$value['n']);
				$order_tax=$this->_logic_tax_1->single_times_allow_2000($cart_info); */
			    $tax_total[] = $value['pr']-$price;
				$taxsum[] = ($value['pr']-$price)*$value['n'];
				$goodsprice[] = $price;
				$order_amount[] = $value['pr'] * $value['n'];
				}
				$order['order_amount'] =array_sum($order_amount);
			}else{
			    $order['order_state'] = $store_pay_type_list[$store_id] == 'online' ? ORDER_STATE_NEW : ORDER_STATE_PAY;           
				$order['order_amount'] = $store_final_order_total[$store_id] + $store_taxes_total[$store_id];
				$order['shipping_fee'] = $store_freight_total[$store_id];
				$order['order_tax'] = $store_taxes_total[$store_id];
				$order['goods_amount'] = $order['order_amount'] - $order['shipping_fee'] - $order['order_tax'];
			}
			if($buy_encrypt['g']!=''){
				//三方订单税
				$order['order_tax'] = array_sum($taxsum);
                $order['goods_amount'] = $order['order_amount'] - $order['shipping_fee'] - $order['order_tax'];
			}
            $order['order_from'] = $order_from;
			//修改货到付款bug BY MING
			if( $order['payment_code']=="")
			{
				$order['payment_code']="offline";
			}
			
            $order_id = $model_order->addOrder($order);
            if (!$order_id) {
                throw new Exception('订单保存失败[未生成订单数据]');
            }
			
			if($buy_encrypt['g']!=''){
				//三方订单order_log数据
				$data = array();
				$data['order_id'] = $order_id;
				$data['log_role'] = '系统';
				$data['log_msg'] = '收到了货款 ( 支付平台交易号 : '.$buy_encrypt['t'].' )';
				$data['log_orderstate'] =20;
				$data['log_time'] = TIMESTAMP;
				$model_order->table('order_log')->insert($data);
			}
            $order['order_id'] = $order_id;
            $order_list[$order_id] = $order;
    
			//生成order_common订单商品数据
			$order_common = array();
            $order_common['order_id'] = $order_id;
            $order_common['store_id'] = $store_id;
            $order_common['order_message'] = $input_pay_message[$store_id];
            //代金券
            if (isset($input_voucher_list[$store_id])){
                $order_common['voucher_price'] = $input_voucher_list[$store_id]['voucher_price'];
                $order_common['voucher_code'] = $input_voucher_list[$store_id]['voucher_code'];
            }
            $order_common['reciver_info']= $reciver_info;
            $order_common['reciver_name'] = $reciver_name;
			$order_common['order_name'] = $true_name;
			$order_common['reciver_idnum'] = $true_idnum;
			$order_common['reciver_area_id'] = $input_area_id;
            $order_common['reciver_city_id'] = $input_city_id;
			$order_common['reciver_province_id'] = intval($area_array[$input_city_id]['area_parent_id']);//11.3+
            //发票信息
            $order_common['invoice_info'] = $this->_logic_buy_1->createInvoiceData($input_invoice_info);
			$re = $store_mansong_rule_list[$store_id];
            //保存促销信息
            if(is_array($store_mansong_rule_list[$store_id])) {
              $order_common['promotion_info'] = addslashes($re['mansong_name'].'，单笔订单满'.intval($re['price']).'元，立减现金'.intval($re['discount']).'元');
            }
            $order_common_id = $model_order->addOrderCommon($order_common);
            if (!$order_common_id) {
                throw new Exception('订单保存失败[未生成订单扩展数据]');
            }
    
            //生成order_goods订单商品数据
            $i = 0;
			$order_goods = array();
            $promotion_sum = 0;	//每种商品的优惠金额累加保存入 $promotion_sum
			if($buy_encrypt['g']==''){
				foreach ($goods_list as $goods_info) {
					if (!$goods_info['state'] || !$goods_info['storage_state']) {
						throw new Exception('部分商品已经下架或库存不足，请重新选择');
					}
					if (!intval($goods_info['bl_id'])) {
						//如果不是优惠套装
						$order_goods[$i]['order_id'] = $order_id;
						$order_goods[$i]['goods_id'] = $goods_info['goods_id'];
						$order_goods[$i]['store_id'] = $store_id;
						$order_goods[$i]['store_from'] = $goods_info['store_from'];
						$order_goods[$i]['goods_name'] = $goods_info['goods_name'];
						$order_goods[$i]['goods_weight'] = $goods_info['goods_weight'];
						//计算商品金额
						$goods_total = $goods_info['goods_price'] * $goods_info['goods_num'];
						//计算商品税金
						$taxes_total = $goods_info['goods_taxes'] * $goods_info['goods_num'];
						//计算本件商品优惠总金额
						$promotion_value = number_format($goods_total*($promotion_rate),2);
						//计算本件商品优惠的满减金额
						$manjian_value = number_format($goods_total*($mansong_rate),2);
						//计算本件商品优惠的代金券金额
						$voucher_value = number_format($goods_total*($voucher_rate),2);
						
						$order_goods[$i]['goods_pay_price'] = $goods_total - $manjian_value;
						$order_goods[$i]['goods_price'] = $goods_info['goods_price'];
						$order_goods[$i]['goods_num'] = $goods_info['goods_num'];
						$order_goods[$i]['goods_taxes'] = $goods_info['goods_taxes'];
						$order_goods[$i]['goods_image'] = $goods_info['goods_image'];
						$order_goods[$i]['buyer_id'] = $member_id;
						if ($goods_info['ifgroupbuy']) {
							$ifgroupbuy = true;
							$order_goods[$i]['goods_type'] = 2;
						}elseif ($goods_info['ifxianshi']) {
							$order_goods[$i]['goods_type'] = 3;
						}elseif ($goods_info['ifzengpin']) {
							$order_goods[$i]['goods_type'] = 5;
						}elseif (is_array($store_mansong_rule_list[$store_id])) {
							$order_goods[$i]['goods_type'] = 6;
						}else {
							$order_goods[$i]['goods_type'] = 1;
						}
						$order_goods[$i]['promotions_id'] = $goods_info['promotions_id'] ? $goods_info['promotions_id'] : 0;

						$order_goods[$i]['commis_rate'] =floatval($store_gc_id_commis_rate_list[$store_id][$goods_info['gc_id']]);

						$order_goods[$i]['gc_id'] = $goods_info['gc_id'];

						$promotion_sum += $voucher_value;
						$mansong_sum   += $manjian_value;
						
						//mess海关推送订单数据
						$order_goods[$i]['goods_total'] = $goods_info['goods_total'] ? $goods_info['goods_total'] : 0;
						$order_goods[$i]['shipping_total'] = $goods_info['shipping_total'] ? $goods_info['shipping_total'] : 0;
						$order_goods[$i]['insurance_total'] = $goods_info['insurance_total'] ? $goods_info['insurance_total'] : 0;
						
						if(is_array($input_voucher_list)) {
							$order_goods[$i]['discount_total'] = $voucher_value;
						}else{
							$order_goods[$i]['discount_total'] = $goods_info['discount_total']  ? $goods_info['discount_total'] : 0;
						}
						$order_goods[$i]['taxes_total'] = $goods_info['taxes_total'] ? $goods_info['taxes_total'] : 0;
						$order_goods[$i]['mess_country_code'] = $goods_info['mess_country_code'] ? $goods_info['mess_country_code'] : 0;
						$i++;

						//存储库存报警数据
						if ($goods_info['goods_storage_alarm'] >= ($goods_info['goods_storage'] - $goods_info['goods_num'])) {
							$goods_serial = Model('goods')->getGoodsIn(array('goods_id' => $goods_info['goods_id']), 'goods_serial');
							$param = array();
							$param['goods_serial'] = $goods_serial[0]['goods_serial'];
							$param['goods_name'] = $goods_info['goods_name'];
							$param['goods_storage'] = $goods_info['goods_storage'] - $goods_info['goods_num'];
							$notice_list['goods_storage_alarm'][$goods_info['store_id']] = $param;
						}

					} elseif (intval($goods_info['bl_id'])) {

						//优惠套装
						$order_goods[$i]['order_id'] = $order_id;
						$order_goods[$i]['goods_id'] = $goods_info['goods_id'];
						$order_goods[$i]['store_id'] = $store_id;
						$order_goods[$i]['store_from'] = $goods_info['store_from'];
						$order_goods[$i]['goods_name'] = $goods_info['goods_name'];
						$order_goods[$i]['goods_price'] = $goods_info['bl_goods_price'];
						$order_goods[$i]['goods_weight'] = $goods_info['goods_weight'];
						//计算商品实际支付金额(goods_price减去分摊优惠金额后的值)
						$goods_total = $goods_info['bl_goods_price'] * $goods_info['goods_num'];
						//计算本件商品优惠金额
						$promotion_value = number_format($goods_total*($promotion_rate),2);
						//计算本件商品优惠的满减金额
						$manjian_value = number_format($goods_total*($mansong_rate),2);
						//计算本件商品优惠的代金券金额
						$voucher_value = number_format($goods_total*($voucher_rate),2);
						
						$order_goods[$i]['goods_pay_price'] = $goods_total - $manjian_value;
						$order_goods[$i]['goods_num'] = $goods_info['goods_num'];
						$order_goods[$i]['goods_taxes'] = $goods_info['goods_taxes'];
						$order_goods[$i]['goods_image'] = $goods_info['goods_image'];
						$order_goods[$i]['buyer_id'] = $member_id;
						$order_goods[$i]['goods_type'] = 4;
						$order_goods[$i]['promotions_id'] = $goods_info['bl_id'];
						$order_goods[$i]['commis_rate'] = floatval($store_gc_id_commis_rate_list[$store_id][$goods_info['gc_id']]);
						$order_goods[$i]['gc_id'] = $goods_info['gc_id'];

						$promotion_sum += $voucher_value;
						$mansong_sum   += $manjian_value;

						//mess海关推送订单数据
						$order_goods[$i]['goods_total'] = $goods_info['goods_total'] ? $goods_info['goods_total'] : 0;
						$order_goods[$i]['shipping_total'] = $goods_info['shipping_total'] ? $goods_info['shipping_total'] : 0;
						$order_goods[$i]['insurance_total'] = $goods_info['insurance_total'] ? $goods_info['insurance_total'] : 0;
						if(is_array($input_voucher_list)) {
							$order_goods[$i]['discount_total'] = $voucher_value;
						}else{
							$order_goods[$i]['discount_total'] = $goods_info['discount_total']  ? $goods_info['discount_total'] : 0;
						}
						$order_goods[$i]['taxes_total'] = $goods_info['taxes_total'] ? $goods_info['taxes_total'] : 0;
						$order_goods[$i]['mess_country_code'] = $goods_info['mess_country_code'] ? $goods_info['mess_country_code'] : 0;
						$i++;

						//存储库存报警数据
						if ($goods_info['goods_storage_alarm'] >= ($goods_info['goods_storage'] - $goods_info['goods_num'])) {
							$goods_serial = Model('goods')->getGoodsIn(array('goods_id' => $goods_info['goods_id']), 'goods_serial');
							$param = array();
							$param['goods_serial'] = $goods_serial[0]['goods_serial'];
							$param['goods_name'] = $goods_info['goods_name'];
							$param['goods_storage'] = $goods_info['goods_storage'] - $goods_info['goods_num'];
							$notice_list['goods_storage_alarm'][$goods_info['store_id']] = $param;
						}
					}
				}
				// 满减金额有差异时的调整
				if ($mansong_total > $mansong_sum || $mansong_total < $mansong_sum) {
					$i--;
					for($i;$i>=0;$i--) {
						if (floatval($order_goods[$i]['goods_price']) > 0) {
							$balance = $mansong_total - $mansong_sum;
							if(is_array($store_mansong_rule_list)) {
								$order_goods[$i]['goods_pay_price'] = $order_goods[$i]['goods_pay_price'] - $balance;
							}
							break;
						}
					}
				}
				//将因舍出小数部分出现的差值补到最后一个商品的实际成交价中(商品goods_price=0时不给补，可能是赠品)
				if ($voucher_total > $promotion_sum || $voucher_total < $promotion_sum) {
					$i--;
					for($i;$i>=0;$i--) {
						if (floatval($order_goods[$i]['goods_price']) > 0) {
							$balance = $voucher_total - $promotion_sum;
							if(is_array($input_voucher_list)) {
								$order_goods[$i]['discount_total'] = $order_goods[$i]['discount_total'] + $balance;
							}else{
								$order_goods[$i]['discount_total'] = $goods_info['discount_total']  ? $goods_info['discount_total'] : 0;
							}
							break;
						}
					}
				}
				$insert = $model_order->addOrderGoods($order_goods);
				if (!$insert) {
					throw new Exception('订单保存失败[未生成商品数据]');
				}
			}else{
				//三方订单order_gooods数据的插入
				$model=Model();
				$i=0;
				foreach($this->_order_data['info'] as $key=>$value){
					$goods_total = $goodsprice[$i] * $value['goods_num'];
					$order_goods[$i]['order_id'] = $order_id;
					$order_goods[$i]['goods_id'] = $value['goods_id'];
					$order_goods[$i]['store_id'] = $value['store_id'];
					$order_goods[$i]['goods_name'] = $value['goods_name'];
					$order_goods[$i]['goods_weight'] = $value['goods_weight'];
					$order_goods[$i]['goods_pay_price'] = $goods_total;
					$order_goods[$i]['goods_price'] = $goodsprice[$i];
					$order_goods[$i]['goods_num'] = $value['goods_num'];
					$order_goods[$i]['taxes_total'] = $tax_total[$i]*$value['goods_num'];
					$order_goods[$i]['goods_total'] = $order_goods[$i]['goods_price'] * $value['goods_num'];
					$order_goods[$i]['goods_taxes'] = $tax_total[$i];
					$order_goods[$i]['goods_image'] = $value['goods_image'];
					$order_goods[$i]['buyer_id'] = $member_id;
					$order_goods[$i]['gc_id'] = $value['gc_id'];
					$order_goods[$i]['mess_country_code'] = $value['mess_country_code'];					
					$i++;
				}
				foreach($order_goods as $value){
			          $goods=$model->table('order_goods')->insert($value);
				}
			}
            //存储商家发货提醒数据
            if ($store_pay_type_list[$store_id] == 'offline') {
                $notice_list['new_order'][$order['store_id']] = array('order_sn' => $order['order_sn']);
            }
        }
		
		//增加提示二维码用户消息账单
//		if($member_info[0]['refer_id']){
//			$conditions = array();
//			$message_model = Model('message');
//			$conditions['message_parent_id'] = 0;
//			$conditions['from_member_id'] = 0;
//			$conditions['to_member_id'] = $member_info[0]['refer_id'];
//			$conditions['message_body'] = '订单['.$order['order_sn'] .']已产生与返利金额'.$order['goods_rebate_amount'].'元。';
//			$conditions['message_time'] = time();
//			$conditions['message_update_time'] = time();
//			$conditions['message_type'] = 1;
//			$message_model -> message_mail($conditions);
//		}

        //保存数据
        $this->_order_data['pay_sn'] = $pay_sn;
        $this->_order_data['order_list'] = $order_list;
        $this->_order_data['notice_list'] = $notice_list;
        $this->_order_data['ifgroupbuy'] = $ifgroupbuy;
    }

    /**
     * 充值卡、预存款支付
     *
     */
    private function _createOrderStep5() {
        if (empty($this->_post_data['password'])) return ;
        $buyer_info	= Model('member')->getMemberInfoByID($this->_member_info['member_id']);
        if ($buyer_info['member_paypwd'] == '' || $buyer_info['member_paypwd'] != md5($this->_post_data['password'])) return ;

        //使用充值卡支付
        if (!empty($this->_post_data['rcb_pay'])) {
            $order_list = $this->_logic_buy_1->rcbPay($this->_order_data['order_list'], $this->_post_data, $buyer_info);
        }
        
        //使用预存款支付
        if (!empty($this->_post_data['pd_pay'])) {
            $this->_logic_buy_1->pdPay($order_list ? $order_list :$this->_order_data['order_list'], $this->_post_data, $buyer_info);
        }
    }

    /**
     * 订单后续其它处理
     */
    private function _createOrderStep6() {
        $ifcart = $this->_post_data['ifcart'];
        $goods_buy_quantity = $this->_order_data['goods_buy_quantity'];
        $input_voucher_list = $this->_order_data['input_voucher_list'];
        $store_cart_list = $this->_order_data['store_cart_list'];
        $input_buy_items = $this->_order_data['input_buy_items'];
        $order_list = $this->_order_data['order_list'];
        $input_address_info = $this->_order_data['input_address_info'];
        $notice_list = $this->_order_data['notice_list'];
        $fc_id = $this->_order_data['fc_id'];
        $ifgroupbuy = $this->_order_data['ifgroupbuy'];
		$input_member_points = $this->_order_data['input_member_points'];

        //变更库存和销量
        QueueClient::push('createOrderUpdateStorage', $goods_buy_quantity);

        //更新使用的代金券状态
        if (!empty($input_voucher_list) && is_array($input_voucher_list)) {
            QueueClient::push('editVoucherState', $input_voucher_list);
        }
		
		//更新抵扣的积分相关信息
        if (!empty($input_member_points) && is_array($input_member_points)) {
			$member_id = $this->_member_info['member_id'];
			$member_name = $this->_member_info['member_name'];
			foreach ($order_list as $v) {
                $order_sn = $v['order_sn'];
            }
			foreach($input_member_points as $val){
				Model('points')->savePointsLog('cash',array('pl_memberid'=>$member_id,'pl_membername'=>$member_name,'member_points'=>$val,'order_sn'=>$order_sn));
			}
        }
		
        //更新F码使用状态
        if ($fc_id) {
            QueueClient::push('updateGoodsFCode', $fc_id);
        }

        //更新抢购购买人数和数量
        if ($ifgroupbuy) {
            foreach ($store_cart_list as $goods_list) {
                foreach ($goods_list as $goods_info) {
                    if ($goods_info['ifgroupbuy'] && $goods_info['groupbuy_id']) {
                        $groupbuy_info = array();
                        $groupbuy_info['groupbuy_id'] = $goods_info['groupbuy_id'];
                        $groupbuy_info['quantity'] = $goods_info['goods_num'];
                        QueueClient::push('editGroupbuySaleCount', $groupbuy_info);
                    }
                }
            }
        }

        //删除购物车中的商品
        $this->delCart($ifcart,$this->_member_info['member_id'],array_keys($input_buy_items));
        @setNcCookie('cart_goods_num','',-3600);

        //保存订单自提点信息
        if (C('delivery_isuse') && intval($input_address_info['dlyp_id'])) {
            $data = array();
            $data['mob_phone'] = $input_address_info['mob_phone'];
            $data['tel_phone'] = $input_address_info['tel_phone'];
            $data['reciver_name'] = $input_address_info['true_name'];
			$data['reciver_idnum'] = $input_address_info['true_idnum'];
            $data['dlyp_id'] = $input_address_info['dlyp_id'];
            foreach ($order_list as $v) {
                $data['order_sn_list'][$v['order_id']]['order_sn'] = $v['order_sn'];
                $data['order_sn_list'][$v['order_id']]['add_time'] = $v['add_time'];
            }
            QueueClient::push('saveDeliveryOrder', $data);
        }

        //发送提醒类信息
        if (!empty($notice_list)) {
            foreach ($notice_list as $code => $value) {
                QueueClient::push('sendStoreMsg', array('code' => $code, 'store_id' => key($value), 'param' => current($value)));
            }
        }

    }

    /**
     * 加密
     * @param array/string $string
     * @param int $member_id
     * @return mixed arrray/string
     */
    public function buyEncrypt($string, $member_id) {
        $buy_key = sha1(md5($member_id.'&'.MD5_KEY));
        if (is_array($string)) {
            $string = serialize($string);
        } else {
            $string = strval($string);
        }
        return encrypt(base64_encode($string), $buy_key);
    }

    /**
     * 解密
     * @param string $string
     * @param int $member_id
     * @param number $ttl
     */
    public function buyDecrypt($string, $member_id, $ttl = 0) {
        $buy_key = sha1(md5($member_id.'&'.MD5_KEY));
        if (empty($string)) return;
        $string = base64_decode(decrypt(strval($string), $buy_key, $ttl));
        return ($tmp = @unserialize($string)) !== false ? $tmp : $string;
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
                    if (intval($match[2][0]) > 0) {
                        $buy_items[$match[1][0]] = $match[2][0];
                    }
                }
            }
        }
        return $buy_items;
    }

    /**
     * 从购物车数组中得到商品列表
     * @param unknown $cart_list
     */
    private function _getGoodsList($cart_list) {
        if (empty($cart_list) || !is_array($cart_list)) return $cart_list;
        $goods_list = array();
        $i = 0;
		
        foreach ($cart_list as $key => $cart) {
            if (!$cart['state'] || !$cart['storage_state']) continue;
            //购买数量
            $quantity = $cart['goods_num'];
            if (!intval($cart['bl_id'])) {
                //如果是普通商品
                $goods_list[$i]['goods_num'] = $quantity;
                $goods_list[$i]['goods_weight'] = $cart['goods_weight'];
                $goods_list[$i]['goods_id'] = $cart['goods_id'];
                $goods_list[$i]['store_id'] = $cart['store_id'];
                $goods_list[$i]['gc_id'] = $cart['gc_id'];
                $goods_list[$i]['goods_name'] = $cart['goods_name'];
                $goods_list[$i]['goods_price'] = $cart['goods_price'];
				if($goods_info['goods_rebate_rate']=="")
					{
					$goods_list[$i]['goods_rebate_rate']=$cart['goods_rebate_rate'];
					$goods_list[$i]['goods_rebate_amount']=0.00;
					}//返利
					else
					{
					$goods_list[$i]['goods_rebate_rate'] =$cart['goods_rebate_rate'];
					$goods_list[$i]['goods_rebate_amount'] =$cart['goods_rebate_rate'] * $quantity * $cart['goods_price'];
					}///返利
				$goods_list[$i]['goods_taxes'] = $cart['goods_taxes'];
                $goods_list[$i]['store_name'] = $cart['store_name'];
                $goods_list[$i]['goods_image'] = $cart['goods_image'];
                $goods_list[$i]['transport_id'] = $cart['transport_id'];
                $goods_list[$i]['goods_freight'] = $cart['goods_freight'];
                $goods_list[$i]['goods_vat'] = $cart['goods_vat'];
                $goods_list[$i]['is_fcode'] = $cart['is_fcode'];
                $goods_list[$i]['bl_id'] = 0;
				$goods_list[$i]['adjust_rebate_rate'] = $cart['adjust_rebate_rate'];
				$goods_list[$i]['is_use_adjustrate'] = $cart['is_use_adjustrate'];
                $i++;
            } else {
                //如果是优惠套装商品
                foreach ($cart['bl_goods_list'] as $bl_goods) {
                    $goods_list[$i]['goods_num'] = $quantity;
                    $goods_list[$i]['goods_id'] = $bl_goods['goods_id'];
                    $goods_list[$i]['store_id'] = $cart['store_id'];
                    $goods_list[$i]['gc_id'] = $bl_goods['gc_id'];
                    $goods_list[$i]['goods_name'] = $bl_goods['goods_name'];
                    $goods_list[$i]['goods_price'] = $bl_goods['goods_price'];
					if($goods_info['goods_rebate_rate']=="")
						{
						$goods_list[$i]['goods_rebate_rate']=$cart['goods_rebate_rate'];
						$goods_list[$i]['goods_rebate_amount']=0.00;
						}//返利
					else
						{
						$goods_list[$i]['goods_rebate_rate'] =$cart['goods_rebate_rate'];
						$goods_list[$i]['goods_rebate_amount'] =$cart['goods_rebate_rate'] * $quantity * $bl_goods['goods_price'];
						}///返利
					$goods_list[$i]['goods_taxes'] = $bl_goods['goods_taxes'];
                    $goods_list[$i]['store_name'] = $bl_goods['store_name'];
                    $goods_list[$i]['goods_image'] = $bl_goods['goods_image'];
                    $goods_list[$i]['transport_id'] = $bl_goods['transport_id'];
                    $goods_list[$i]['goods_freight'] = $bl_goods['goods_freight'];
                    $goods_list[$i]['goods_vat'] = $bl_goods['goods_vat'];
                    $goods_list[$i]['bl_id'] = $cart['bl_id'];
					$goods_list[$i]['adjust_rebate_rate'] = $cart['adjust_rebate_rate'];
					$goods_list[$i]['is_use_adjustrate'] = $cart['is_use_adjustrate'];
                    $i++;
                }
            }
        }
        return $goods_list;
    }

    /**
     * 将下单商品列表转换为以店铺ID为下标的数组
     *
     * @param array $cart_list
     * @return array
     */
    private function _getStoreCartList($cart_list) {
        if (empty($cart_list) || !is_array($cart_list)) return $cart_list;
        $new_array = array();
        foreach ($cart_list as $cart) {
            $new_array[$cart['store_id']][] = $cart;
        }
        return $new_array;
    }

    /**
     * 本次下单是否需要码及F码合法性
     * 无需使用F码，返回 true
     * 需要使用F码，返回($fc_id/false)
     */
    private function _checkFcode($goods_list, $fcode) {
        foreach ($goods_list as $k => $v) {
            if ($v['is_fcode'] == 1) {
                $is_fcode = true; break;
            }
        }
        if (!$is_fcode) return true;
        if (empty($fcode) || count($goods_list) > 1) {
            return false;
        }
        $goods_info = $goods_list[0];
        $fcode_info = $this->checkFcode($goods_info['goods_commonid'],$fcode);
        if ($fcode_info['state']) {
            return intval($fcode_info['data']['fc_id']);
        } else {
            return false;
        }
    }

    /**
     * 将订单数据存入内存表
     * @param array $data
     * return boolean
     */
    private function _pushCallback($order) {
        $model_pay_callback = Model('pay_callback');
        foreach($order as $k=>$v){
            $data = array();
            $data['times'] = 0;
            $data['next_time'] = TIMESTAMP;
            $data['param'] = serialize(array(
               'o'=>$v['order_sn'],
               't'=>$v['out_trade_no'],
               'p'=>$v['parter_id'],
            ));
            $model_pay_callback->insert($data);
        }
        return true;
    }

   /**
    * [将数组转为XML]
    * @author fulijun
    * @dateTime 2016-08-12T18:55:24+0800
    * @param    [array]                   $array [要转为XML的数组]
    * @return   [type]                           [返回XML]
    */
    public function arrayToXML($arr,$xmlstr=''){
        if(!$xmlstr){
            $xmlstr .= '<xml>';
        }
        foreach ($arr as $key=>$val){
            $key = is_string($key)?$key:"item";
            $xmlstr .= '<'.$key.'>';
            if (!is_array($val)){
                $xmlstr .= $val;
            }else {
                $xmlstr = $this->arrayToXML($val,$xmlstr);
            }
            $xmlstr .= '</'.$key.'>';
        }
        if($xmlstr){
            $xmlstr .= '</xml>';
        }
        return $xmlstr;
    }

    /**
     * [将数组转为"a=b&c=d"形式的字符串]
     * @author fulijun
     * @dateTime 2016-08-13T16:38:31+0800
     * @param    [array]                   $array [要转为字符串的数组]
     * @return   [string]                         [返回"a=b&c=d"形式的字符串]
     */
    function arrayToString($array){
        $string = '';
        if($array && is_array($array)){
            //键值排序
            ksort($array);
            foreach ($array as $key=> $value){
                $string .= $key.'='.$value.'&';
            }
            $keystr = rtrim($string,'&');
        }
        return $keystr;
    }

    /**
    * [对象转为数组]
    * @author fulijun
    * @dateTime 2016-08-17T18:20:15+0800
    * @param    [object]                   $obj [要转为数组的对象]
    * @return   [array]                         [返回数组]
    */
   function objectToArray($obj){
      $_arr = is_object($obj) ? get_object_vars($obj) :$obj;
      foreach ($_arr as $key=>$val){
       $val = (is_array($val) || is_object($val)) ? $this->objectToArray($val):$val;
       $arr[$key] = $val;
      }
      return $arr;
   }
}
