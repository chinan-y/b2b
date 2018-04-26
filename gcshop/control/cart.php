<?php
/**
 * 购物车操作
 ***/


defined('GcWebShop') or exit('Access Invalid!');
class cartControl extends BaseBuyControl {

	public function __construct() {
		parent::__construct();
		Language::read('home_cart_index');

		$gp = isset($_GET['gp']) ? $_GET['gp'] : $_POST['gp'];

		/* 允许不登录就可以访问的op
		$op_arr = array('ajax_load','add','del');
		if (!in_array($gp,$op_arr) && !$_SESSION['member_id'] ){
			$current_url = request_uri();
			redirect('index.php?gct=login&ref_url='.urlencode($current_url));
		} */
		
		Tpl::output('hidden_rtoolbar_cart', 1);

		require_once BASE_PATH.'/config/returnLang.php';
		Tpl::output('lang', $lang);
	}

	/**
	 * 购物车首页
	 */
	public function indexOp() {
        $model_cart	= Model('cart');
        $logic_buy_1 = logic('buy_1');
		
		
        //购物车列表
		if($_SESSION['member_id']){
			 $cart_list	= $model_cart->listCart('db',array('buyer_id'=>$_SESSION['member_id']));
		}else{
			$cart_list = $model_cart->listCart('cookie');
		}
		
       
		//$cart_list	= $model_cart->order('goods_id asc')->listCart('db',array('buyer_id'=>$_SESSION['member_id']));

        //购物车列表 [得到最新商品属性及促销信息] 
        $cart_list = $logic_buy_1->getGoodsCartList($cart_list,1);

        //购物车商品以店铺ID分组显示,并计算商品小计,店铺小计与总价由JS计算得出
        $store_cart_list = array();
        foreach ($cart_list as $cart) {
            $cart['goods_total'] = ncPriceFormat($cart['goods_price'] * $cart['goods_num']);
            $store_cart_list[$cart['store_id']][]= $cart;
			//$store_cart_list[$cart['store_from']][]= $cart;//by liu 分类分组域有问题未解决
        }

		//判断是否需要拆单
		$cartSplit = $model_cart->cartSplit($store_cart_list);
		list($split, $new_cart_list,$regions) = $cartSplit;
		Tpl::output('split',$split);
		Tpl::output('new_cart_list',$new_cart_list);

		Tpl::output('store_cart_list',$store_cart_list);

        //店铺信息
        $store_list = Model('store')->getStoreMemberIDList(array_keys($store_cart_list));
        Tpl::output('store_list',$store_list);

        //取得店铺级活动 - 可用的满即送活动
	    $mansong_rule_list = $logic_buy_1->getMansongRuleList(array_keys($store_cart_list));
	    Tpl::output('mansong_rule_list',$mansong_rule_list);

	    //取得哪些店铺有满免运费活动
        $free_freight_list = $logic_buy_1->getFreeFreightActiveList(array_keys($store_cart_list));
        Tpl::output('free_freight_list',$free_freight_list);

		//判断是否是三方订单
		if(isset($_POST['buy_encrypt']) && !empty($_POST['buy_encrypt'])){
			Tpl::output('buy_encrypt',$_POST['buy_encrypt']);
		}
		
		//标识 购买流程执行第几步
	    Tpl::output('buy_step','step1');
        Tpl::showpage(empty($cart_list) ? 'cart_empty' : 'cart');
	}

	/**
	 * 异步查询购物车
	 */
	public function ajax_loadOp() {
	    $model_cart	 = Model('cart');
	    $model_goods = Model('goods');
		if ($_SESSION['member_id']){
		    //登录后
			$cart_list	= $model_cart->listCart('db',array('buyer_id'=>$_SESSION['member_id']));
			$cart_array	= array();
			if(!empty($cart_list)){
				foreach ($cart_list as $k => $cart){
					$commonid = $model_goods->getGoodsIn(array('goods_id' => $cart['goods_id']), 'goods_commonid');
					$cart['goods_commonid'] =  $commonid[0]['goods_commonid'];
					$cart_array['list'][$k]['cart_id'] = $cart['cart_id'];
					$cart_array['list'][$k]['goods_id'] = $cart['goods_id'];
					$cart_array['list'][$k]['goods_name'] = $cart['goods_name'];
					$cart_array['list'][$k]['goods_price'] 	= $cart['goods_price'];
					$cart_array['list'][$k]['goods_image']	= thumb($cart,60);
					$cart_array['list'][$k]['goods_num'] = $cart['goods_num'];
					$cart_array['list'][$k]['goods_url'] = urlShop('goods', 'index', array('goods_id' => $cart['goods_id']));
				}
			}
		} else {
		    //登录前
			$cart_list = $model_cart->listCart('cookie');
			foreach ($cart_list as $key => $cart){
				$commonid = $model_goods->getGoodsIn(array('goods_id' => $cart['goods_id']), 'goods_commonid');
				$cart['goods_commonid'] =  $commonid[0]['goods_commonid'];
			    $value = array();
			    $value['cart_id'] = $cart['goods_id'];
				$value['goods_name'] = $cart['goods_name'];
				$value['goods_price'] = $cart['goods_price'];
				$value['goods_num'] = $cart['goods_num'];
				$value['goods_image'] = thumb($cart,60);
				$value['goods_url'] = urlShop('goods', 'index', array('goods_id' => $cart['goods_id']));
				$cart_array['list'][] = $value;
			}
		}
		setNcCookie('cart_goods_num',$model_cart->cart_goods_num,2*3600);
		$cart_array['cart_all_price'] = ncPriceFormat($model_cart->cart_all_price);
		$cart_array['cart_goods_num'] = $model_cart->cart_goods_num;
		if ($_GET['type'] == 'html') {
		    Tpl::output('cart_list',$cart_array);
		    Tpl::showpage('cart_mini','null_layout');
		} else {
		    $cart_array = strtoupper(CHARSET) == 'GBK' ? Language::getUTF8($cart_array) : $cart_array;
		    $json_data = json_encode($cart_array);
		    if (isset($_GET['callback'])) {
		        $json_data = $_GET['callback']=='?' ? '('.$json_data.')' : $_GET['callback']."($json_data);";
		    }
		    exit($json_data);		    
		}

	}

	/**
	 * 加入购物车，登录后存入购物车表
	 * 存入COOKIE，由于COOKIE长度限制，最多保存5个商品
	 * 未登录不能将优惠套装商品加入购物车，登录前保存的信息以goods_id为下标
	 *
	 */
	public function addOp() {
	    $model_goods = Model('goods');
	    $logic_buy_1 = Logic('buy_1');
        if (is_numeric($_GET['goods_id'])) {

            //商品加入购物车(默认)
            $goods_id = intval($_GET['goods_id']);
            $quantity = intval($_GET['quantity']);
            if ($goods_id <= 0) return ;
            $goods_info	= $model_goods->getGoodsOnlineInfoAndPromotionById($goods_id);
			$rule = Model()->table('goods_price_rule')->where(array('goods_id'=>$goods_id))->find();
			if(is_array($rule) && $rule['num1'] && $rule['price1']){
				if($quantity < $rule['num1']){
					$goods_info['goods_price'] = $goods_info['goods_price'];
				}else{
					if(!$rule['num2']){
						$goods_info['goods_price'] = $rule['price1'];
					}else{
						if($quantity < $rule['num2']){
							$goods_info['goods_price'] = $rule['price1'];
						}else{
							if(!$rule['num3']){
								$goods_info['goods_price'] = $rule['price2'];
							}else{
								if($quantity < $rule['num3']){
									$goods_info['goods_price'] = $rule['price2'];
								}else{
									if(!$rule['num4']){
										$goods_info['goods_price'] = $rule['price3'];
									}else{
										if($quantity < $rule['num4']){
											$goods_info['goods_price'] = $rule['price3'];
										}else{
											if(!$rule['num5']){
												$goods_info['goods_price'] = $rule['price4'];
											}else{
												if($quantity < $rule['num5']){
													$goods_info['goods_price'] = $rule['price4'];
												}else{
													$goods_info['goods_price'] = $rule['price5'];
												}
											}
										}
									}
								}
							}
							
						}
					}
				}
			}else{
				$goods_info['goods_price'] = $goods_info['goods_price'];
			}
            //抢购
            $logic_buy_1->getGroupbuyInfo($goods_info);

            //限时折扣
            $logic_buy_1->getXianshiInfo($goods_info,$quantity);

            $this->_check_goods($goods_info,$_GET['quantity']);

        } elseif (is_numeric($_GET['bl_id'])) {

            //优惠套装加入购物车(单套)
            if (!$_SESSION['member_id']) {
                exit(json_encode(array('msg'=>'请先登录','UTF-8')));
            }
            $bl_id = intval($_GET['bl_id']);
            if ($bl_id <= 0) return ;
            $model_bl = Model('p_bundling');
            $bl_info = $model_bl->getBundlingInfo(array('bl_id'=>$bl_id));
            if (empty($bl_info) || $bl_info['bl_state'] == '0') {
                exit(json_encode(array('msg'=>'该优惠套装已不存在，建议您单独购买','UTF-8')));
            }

            //检查每个商品是否符合条件,并重新计算套装总价
            $bl_goods_list = $model_bl->getBundlingGoodsList(array('bl_id'=>$bl_id));
            $goods_id_array = array();
            $bl_amount = 0;
            foreach ($bl_goods_list as $goods) {
            	$goods_id_array[] = $goods['goods_id'];
            	$bl_amount += $goods['bl_goods_price'];
            }
            $model_goods = Model('goods');
            $goods_list = $model_goods->getGoodsOnlineListAndPromotionByIdArray($goods_id_array);
            foreach ($goods_list as $goods) {
                $this->_check_goods($goods,1);
            }

            //优惠套装作为一条记录插入购物车，图片取套装内的第一个商品图
            $goods_info    = array();
			foreach ($goods_list as $goods) {
				$goods_info['goods_taxes'] += $goods['goods_taxes'];
				$goods_info['goods_weight'] += $goods['goods_weight'];
            }
            $goods_info['store_id']	= $bl_info['store_id'];
            $goods_info['goods_id']	= $goods_list[0]['goods_id'];
            $goods_info['goods_name'] = $bl_info['bl_name'];
            $goods_info['goods_price'] = $bl_amount;
            $goods_info['goods_num']   = 1;
            $goods_info['goods_image'] = $goods_list[0]['goods_image'];
            $goods_info['store_name'] = $bl_info['store_name'];
			$goods_info['store_from'] = $goods_list[0]['store_from'];//套装的商品要弄同一个来源 选择第一个的来源
            $goods_info['bl_id'] = $bl_id;
            $quantity = 1;
        }

        //已登录状态，存入数据库,未登录时，存入COOKIE
        if($_SESSION['member_id']) {
            $save_type = 'db';
            $goods_info['buyer_id'] = $_SESSION['member_id'];
        } else {
            $save_type = 'cookie';
        }
        $model_cart	= Model('cart');
        $insert = $model_cart->addCart($goods_info,$save_type,$quantity);
        if ($insert) {
            //购物车商品种数记入cookie
            setNcCookie('cart_goods_num',$model_cart->cart_goods_num,2*3600);
            $data = array('state'=>'true', 'num' => $model_cart->cart_goods_num, 'amount' => ncPriceFormat($model_cart->cart_all_price));
        } else {
            $data = array('state'=>'false');
        }
	    exit(json_encode($data));
	}

	/**
	 * 推荐组合加入购物车
	 */
	public function add_combOp() {
	    if (!preg_match('/^[\d|]+$/', $_GET['goods_ids'])) {
	        exit(json_encode(array('state'=>'false')));
	    }

	    $model_goods = Model('goods');
	    $logic_buy_1 = Logic('buy_1');
	
		// 没登录的用户也可以加组合到购物车
        // if (!$_SESSION['member_id']) {
            // exit(json_encode(array('msg'=>'请先登录','UTF-8')));
        // }

        $goods_id_array = explode('|', $_GET['goods_ids']);

        $model_goods = Model('goods');
        $goods_list = $model_goods->getGoodsOnlineListAndPromotionByIdArray($goods_id_array);
        
        foreach ($goods_list as $goods) {
            $this->_check_goods($goods,1);
        }

        //抢购
        $logic_buy_1->getGroupbuyCartList($goods_list);

        //限时折扣
        $logic_buy_1->getXianshiCartList($goods_list);

        $model_cart	= Model('cart');
        foreach ($goods_list as $goods_info) {
            $cart_info = array();
            $cart_info['store_id']	= $goods_info['store_id'];
			$cart_info['store_from']	= $goods_info['store_from'];//by liu
            $cart_info['goods_id']	= $goods_info['goods_id'];
            $cart_info['goods_name'] = $goods_info['goods_name'];
            $cart_info['goods_price'] = $goods_info['goods_price'];
			$cart_info['goods_taxes'] = $goods_info['goods_taxes'];
			$cart_info['goods_weight'] = $goods_info['goods_weight'];
            $cart_info['goods_num']   = 1;
            $cart_info['goods_image'] = $goods_info['goods_image'];
            $cart_info['store_name'] = $goods_info['store_name'];
            $quantity = 1;
    	    //已登录状态，存入数据库,未登录时，存入COOKIE
    	    if($_SESSION['member_id']) {
    	        $save_type = 'db';
    	        $cart_info['buyer_id'] = $_SESSION['member_id'];
    	    } else {
    	        $save_type = 'cookie';
    	    }
    	    $insert = $model_cart->addCart($cart_info,$save_type,$quantity);
    	    if ($insert) {
    	        //购物车商品种数记入cookie
    	        setNcCookie('cart_goods_num',$model_cart->cart_goods_num,2*3600);
    	        $data = array('state'=>'true', 'num' => $model_cart->cart_goods_num, 'amount' => ncPriceFormat($model_cart->cart_all_price));
    	    } else {
    	        $data = array('state'=>'false');
    	        exit(json_encode($data));
    	    }
        }
        exit(json_encode($data));
	}

	/**
	 * 检查商品是否符合加入购物车条件
	 * @param unknown $goods
	 * @param number $quantity
	 */
	private function _check_goods($goods_info, $quantity) {
		if(empty($quantity)) {
			exit(json_encode(array('msg'=>Language::get('wrong_argument','UTF-8'))));
		}
		if(empty($goods_info)) {
			exit(json_encode(array('msg'=>Language::get('cart_add_goods_not_exists','UTF-8'))));
		}
		if ($goods_info['store_id'] == $_SESSION['store_id']) {
			exit(json_encode(array('msg'=>Language::get('cart_add_cannot_buy','UTF-8'))));
		}
		if(intval($goods_info['goods_storage']) < 1) {
			exit(json_encode(array('msg'=>Language::get('cart_add_stock_shortage','UTF-8'))));
		}
		if(intval($goods_info['goods_storage']) < $quantity) {
			exit(json_encode(array('msg'=>Language::get('cart_add_too_much','UTF-8'))));
		}
		if ($goods_info['is_virtual'] || $goods_info['is_fcode'] || $goods_info['is_presell']) {
		    exit(json_encode(array('msg'=>'该商品不允许加入购物车，请直接购买','UTF-8')));
		}
	}

	/**
	 * 购物车更新商品数量
	 */
	public function updateOp() {
		$cart_id	= intval(abs($_GET['cart_id']));
		$goods_price	= intval(abs($_GET['goods_price']));
		$quantity	= intval(abs($_GET['quantity']));
		
		// if(empty($cart_id) || empty($quantity)) {
			// exit(json_encode(array('msg'=>Language::get('cart_update_buy_fail','UTF-8'))));
		// }

		$model_cart = Model('cart');
		$model_goods= Model('goods');
		$logic_buy_1 = logic('buy_1');

		//存放返回信息
		$return = array();

		$cart_info = $model_cart->getCartInfo(array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
		if ($cart_info['bl_id'] == '0') {

		    //普通商品
		    $goods_id = intval($cart_info['goods_id']);
		    $goods_info	= $logic_buy_1->getGoodsOnlineInfo($goods_id,$quantity);
			$rule = Model()->table('goods_price_rule')->where(array('goods_id'=>$goods_id))->find();
			if(is_array($rule) && $rule['num1'] && $rule['price1']){
				if($quantity < $rule['num1']){
					$goods_info['goods_price'] = $goods_info['goods_price'];
				}else{
					if(!$rule['num2']){
						$goods_info['goods_price'] = $rule['price1'];
					}else{
						if($quantity < $rule['num2']){
							$goods_info['goods_price'] = $rule['price1'];
						}else{
							if(!$rule['num3']){
								$goods_info['goods_price'] = $rule['price2'];
							}else{
								if($quantity < $rule['num3']){
									$goods_info['goods_price'] = $rule['price2'];
								}else{
									if(!$rule['num4']){
										$goods_info['goods_price'] = $rule['price3'];
									}else{
										if($quantity < $rule['num4']){
											$goods_info['goods_price'] = $rule['price3'];
										}else{
											if(!$rule['num5']){
												$goods_info['goods_price'] = $rule['price4'];
											}else{
												if($quantity < $rule['num5']){
													$goods_info['goods_price'] = $rule['price4'];
												}else{
													$goods_info['goods_price'] = $rule['price5'];
												}
											}
										}
									}
								}
							}
							
						}
					}
				}
			}else{
				$goods_info['goods_price'] = $goods_info['goods_price'];
			}
		    if(empty($goods_info)) {
		        $return['state'] = 'invalid';
		        $return['msg'] = '商品已被下架';
		        $return['subtotal'] = 0;
		        QueueClient::push('delCart', array('buyer_id'=>$_SESSION['member_id'],'cart_ids'=>array($cart_id)));
		        exit(json_encode($return));
		    }

		    //抢购
		    $logic_buy_1->getGroupbuyInfo($goods_info);

		    //限时折扣
		    $logic_buy_1->getXianshiInfo($goods_info,$quantity);

		    $quantity = $goods_info['goods_num'];

		    if(intval($goods_info['goods_storage']) < $quantity) {
		        $return['state'] = 'shortage';
		        $return['msg'] = '库存不足';
		        $return['goods_num'] = $goods_info['goods_num'];
		        $return['goods_price'] = $goods_info['goods_price'];
		        $return['subtotal'] = $goods_info['goods_price'] * $quantity;
		        $model_cart->editCart(array('goods_num'=>$goods_info['goods_storage']),array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
		        exit(json_encode($return));
		    }
		} else {

		    //优惠套装商品
		    $model_bl = Model('p_bundling');
		    $bl_goods_list = $model_bl->getBundlingGoodsList(array('bl_id'=>$cart_info['bl_id']));
		    $goods_id_array = array();
		    foreach ($bl_goods_list as $goods) {
		        $goods_id_array[] = $goods['goods_id'];
		    }
		    $goods_list = $model_goods->getGoodsOnlineListAndPromotionByIdArray($goods_id_array);

		    //如果其中有商品下架，删除
		    if (count($goods_list) != count($goods_id_array)) {
		        $return['state'] = 'invalid';
		        $return['msg'] = '该优惠套装已经无效，建议您购买单个商品';
		        $return['subtotal'] = 0;
		        QueueClient::push('delCart', array('buyer_id'=>$_SESSION['member_id'],'cart_ids'=>array($cart_id)));
		        exit(json_encode($return));
		    }

		    //如果有商品库存不足，更新购买数量到目前最大库存
		    foreach ($goods_list as $goods_info) {
		        if ($quantity > $goods_info['goods_storage']) {
		            $return['state'] = 'shortage';
		            $return['msg'] = '该优惠套装部分商品库存不足，建议您降低购买数量或购买库存足够的单个商品';
		            $return['goods_num'] = $goods_info['goods_storage'];
		            $return['goods_price'] = $cart_info['goods_price'];
		            $return['subtotal'] = $cart_info['goods_price'] * $quantity;
		            $model_cart->editCart(array('goods_num'=>$goods_info['goods_storage']),array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
		            exit(json_encode($return));
		            break;
		        }
		    }
		    $goods_info['goods_price'] = $cart_info['goods_price'];
		}

		if($_SESSION['member_id']){
			$data = array();
			$data['goods_num'] = $quantity;
			$data['goods_price'] = $goods_info['goods_price'];
			$update = $model_cart->editCart($data,array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
		}else{
			$data = array();
			$data['goods_num'] = $quantity;
			$data['goods_price'] = $goods_info['goods_price'];
			$data['goods_id'] = intval($_REQUEST['goods_id']);
			$update1 = $model_cart->editCart($data,array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
			$update1 = true;
		}
            
		if ($update) {
		    $return = array();
			$return['state'] = 'true';
			$return['subtotal'] = $goods_info['goods_price'] * $quantity;
			$return['goods_price'] = $goods_info['goods_price'];
			$return['goods_num'] = $quantity;
		} else if($update1){
			$return = array();
			$return['state'] = 'true';
			$return['subtotal'] = $goods_price * $quantity;
			$return['goods_price'] = $goods_info['goods_price'];
			$return['goods_num'] = $quantity;
		}else{
			$return = array('msg'=>Language::get('cart_update_buy_fail','UTF-8'));
		}
		exit(json_encode($return));
	}

	/**
	 * 购物车删除单个商品，未登录前使用cart_id即为goods_id
	 */
	public function delOp() {
		$cart_id = intval($_GET['cart_id']);
		if($cart_id < 0) return ;
		$model_cart	= Model('cart');
		$data = array();
		if ($_SESSION['member_id']) {
		    //登录状态下删除数据库内容
			$delete	= $model_cart->delCart('db',array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
			if($delete) {
			    $data['state'] = 'true';
			    $data['quantity'] = $model_cart->cart_goods_num;
			    $data['amount'] = $model_cart->cart_all_price;
			} else {
				$data['msg'] = Language::get('cart_drop_del_fail','UTF-8');
			}
		} else {
			//未登录时删除cookie的购物车信息
			$delete	= $model_cart->delCart('cookie',array('goods_id'=>$cart_id));
			if($delete) {
			    $data['state'] = 'true';
			    $data['quantity'] = $model_cart->cart_goods_num;
			    $data['amount'] = $model_cart->cart_all_price;
			}
		}
		setNcCookie('cart_goods_num',$model_cart->cart_goods_num,2*3600);
		$json_data = json_encode($data);
        if (isset($_GET['callback'])) {
            $json_data = $_GET['callback']=='?' ? '('.$json_data.')' : $_GET['callback']."($json_data);";
        }
        exit($json_data);
	}
	
	/**
	 * 清空购物车
	 */
	public function clearCartOp() {
		$model_cart	= Model('cart');
		$data = array();
		if ($_SESSION['member_id']) {
		    //登录状态下删除数据库内容
			$delete	= $model_cart->clearCart('db',array('buyer_id'=>$_SESSION['member_id']));
			if($delete) {
			    $data['state'] = 'true';
			} else {
				$data['msg'] = Language::get('cart_drop_del_fail','UTF-8');
			}
		} else {
			//未登录时删除cookie的购物车信息
			$delete	= $model_cart->clearCart('cookie');
			if($delete == null) {
			    $data['state'] = 'true';
			}
		}
		$json_data = json_encode($data);
        if (isset($_GET['callback'])) {
            $json_data = $_GET['callback']=='?' ? '('.$json_data.')' : $_GET['callback']."($json_data);";
        }
        exit($json_data);
	}

	/**
	 * 三方平台连接光彩全球
	 */
	public function apiOp() {
		require_once(BASE_CORE_PATH.DS.'framework/function/gcclient.php');
		
		//判断是否是三方订单
		//https://www.qqbsmall.com/gcshop/api.html?gct=cart&gp=api&auth_type=mobile&auth_value=15528309540&goods_info=GC8809464990047|2;GC8809320938633|1&trade_no=1604260000001&APPID=13&sign=48b8dd35ac40fb140c4920e74d885e11
		if(isset($_GET['sign'])){
			
			$gct = 'cart';
			$gp = 'api';
			$auth_type = $_GET['auth_type'];
			$auth_value = $_GET['auth_value'];
			$goods_info = $_GET['goods_info'];
			$trade_no = $_GET['trade_no'];
			$APPID = $_GET['APPID'];
			$sign = $_GET['sign'];
			
			$parameter = array(
				'gct'=>$gct,
				'gp'=>$gp,
				'auth_type'=>$auth_type,
				'auth_value'=>$auth_value,
				'goods_info'=>$goods_info,
				'trade_no'=>$trade_no,
				'APPID'=>$APPID,
				'sign'=>$sign,
			);
			
			//验证参数
			if($APPID){
				$APPID = intval($APPID);
				$model_partner = Model('partner');
				$partner_info = $model_partner->getPartnerInfo(array('APPID'=>$APPID));
			}else{
				//output_error('300','数据错误或系统内部错误');
				showMessage('合作伙伴APPID不能为空', '', 'html', 'error');
			}
			if(!$partner_info){
				//output_error('300','合作伙伴APPID在光彩全球不存在');
				showMessage('合作伙伴APPID在光彩全球不存在', '', 'html', 'error');
			}
			
			//计算参数加密结果
			$client = new gcclient();
			$client->parameter['APPKEY'] = $partner_info['APPKEY'];
			$client->parameter['sign_type'] = 'MD5';
			$sign_string = $client->sign($parameter);
			
			if( 1 /* $sign == $sign_string */){
			//if($sign == $sign_string){

				//判断用户信息
				if($_SESSION['member_id']){
					//@todo
				}else{
					$model_member = Model('member');
					$condition = array();
					if($auth_type == 'mobile'){
						$condition['member_mobile'] = $auth_value;
					}elseif($auth_type == 'email'){
						$condition['member_email'] = $auth_value;
					}elseif($auth_type == 'username'){
						$condition['member_name'] = $auth_value;
					}else{
						showMessage('参数注册类型为空', '', 'html', 'error');
					}
					
					$member_info = $model_member->getMemberInfo($condition);
					if(!empty($member_info) && $member_info['saleplat_id'] != $APPID){
						showMessage('当前用户在系统中存在，但是当前用户不是平台用户，请用户自行登录后，执行购买操作。', SHOP_SITE_URL.'/index.php?gct=login&ref_url='.urlencode('http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]), 'html', 'error');
					}
					
					if($member_info){
						//$member_info = $model_member->register($register_info);
						if(!isset($member_info['error'])) {
							$model_member->createSession($member_info,true);
							process::addprocess('reg');

							// cookie中的cart存入数据库
							Model('cart')->mergecart($member_info,$_SESSION['store_id']);

							// cookie中的浏览记录存入数据库
							Model('goods_browse')->mergebrowse($_SESSION['member_id'],$_SESSION['store_id']);
						}
					}else{
						$register_info = array();
						$register_rand = time().rand(10,99);
						if($auth_type == 'mobile'){
							$register_info['username'] = 'gc'.$register_rand;
							$register_info['email'] = $auth_value.'@qqbsmall.com';
							$register_info['mobile'] = $auth_value;
						}elseif($auth_type == 'email'){
							$register_info['username'] = 'gc'.$register_rand;
							$register_info['email'] = $auth_value;
							$register_info['mobile'] = '';
						}elseif($auth_type == 'username'){
							$register_info['username'] = $auth_value;
							$register_info['email'] = $auth_value.'@qqbsmall.com';
							$register_info['mobile'] = '';
						}
						$register_info['password'] = '123456';
						$register_info['ref_url'] = '';
						$register_info['saleplat_id'] = $APPID;
						$register_info['is_membername_modify'] = '1';
						$register_info['refer_id'] = '';
						$register_info['inviter_id'] = '';
						
						$member_info = $model_member->register($register_info);
						if(!isset($member_info['error'])) {
							$model_member->createSession($member_info,true);
							process::addprocess('reg');

							// cookie中的cart存入数据库
							Model('cart')->mergecart($member_info,$_SESSION['store_id']);

							// cookie中的浏览记录存入数据库
							Model('goods_browse')->mergebrowse($_SESSION['member_id'],$_SESSION['store_id']);
						}

					}
				}
				
				$model_goods = Model('goods');
				foreach(explode(';', $goods_info) as $v){
					list($goods_serial,$goods_number) = explode('|',$v);
					$temp = $model_goods->getGoodsInfo(array('goods_serial'=>$goods_serial),'goods_id,goods_serial');
					
					//格式化goods_info生成buy_encrypt
					$goods_info_encrypt[$temp['goods_id']] = array(
						'i'=>$temp['goods_id'],
						'n'=>$goods_number,
					);
					
					//模版输出商品列表
					$goods = $model_goods->getGoodsOnlineInfoAndPromotionById($temp['goods_id']);
					$goods['goods_num'] = $goods_number;
					$goods_list[] = $goods;
				}
				
				//加密三方订单订单信息
				$buy_encrypt = array(
					'g'=>$goods_info_encrypt,
					't'=>$trade_no,
					'a'=>$APPID,
				);
				$buy_encrypt = serialize($buy_encrypt);
				$logic_buy = Logic('buy');
				$buy_encrypt = $logic_buy->buyEncrypt($buy_encrypt, $_SESSION['member_id']);
				
				Tpl::output('buy_encrypt',$buy_encrypt);
				Tpl::output('partner_info',$partner_info);
				Tpl::output('goods_list',$goods_list);
				Tpl::showpage('partner_cart');
			}else{
				//output_error('签名错误或者加密错误',array('code'=>'300'));
				showMessage('签名错误或者加密错误', '', 'html', 'error');
			}
		}else{
			Tpl::showpage('partner');
		}

		
	}
}
