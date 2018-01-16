<?php
/**
 * 前台商品
 *
 * By Ming 二次开发
 */

defined('GcWebShop') or exit('Access Invalid!');

class goodsdetailControl extends BaseGoodsControl {
	public function __construct() {
		parent::__construct();
		Language::read('store_goods_index');
	}
	 /**
     * 商品专题详细页
     */
	 public function goodsdetailOp() {
		//记录销售来源平台  by liu
		$saleplat = intval($_GET['saleplat']);
		if ($saleplat > 0) {
			setcookie('saleplat', $saleplat);
		}

		$ref = intval($_SESSION['member_id']);
		if ($ref > 0) {
			setcookie('ref', $ref);
		}

		//查询店铺ID,自营店是38元包邮，其他店铺不包邮
		$store_list = Model('store');
		$store_info = $store_list -> field('store_id') -> select();
		Tpl::output('store_id', $store_info);
		foreach($store_info as $v){
			$store_id[]=$v['store_id'];
		}

		$goods_id = intval($_GET['goods_id']);
		
		$favorites_model = Model('favorites');
		//判断是否已经收藏
		$favorites_info = $favorites_model->getOneFavorites(array('fav_id'=>"$goods_id",'fav_type'=>'goods','member_id'=>"{$_SESSION['member_id']}"));
		$is_favorites = 1;
		if(!empty($favorites_info)){
			Tpl::output('is_favorites',$is_favorites);
		}
		$logic_buy_1 = logic('buy_1');
		$free_freight_list = $logic_buy_1->getFreeFreightActiveList($store_id);
        Tpl::output('free_freight_list',$free_freight_list);
		

		// 商品详细信息
		$model_goods = Model('goods');
		$goods_detail = $model_goods -> getGoodsDetail($goods_id);
		$goods_info = $goods_detail['goods_info'];
		$mess_country = $model_goods->getGoodsMessCountry($goods_info['country_code']);
		$goods_info['mess_country'] = $mess_country[0]['name'];
		if (empty($goods_info)) {
			showMessage(L('goods_index_no_goods'), '', 'html', 'error');
		}

		$rs = $model_goods -> getGoodsList(array('goods_commonid' => $goods_info['goods_commonid']));
		$count = 0;
		foreach ($rs as $v) {
			$count += $v['goods_salenum'];
		}
		$goods_info['goods_salenum'] = $count;
		//  添加 end
		$this -> getStoreInfo($goods_info['store_id']);

		Tpl::output('spec_list', $goods_detail['spec_list']);
		Tpl::output('spec_image', $goods_detail['spec_image']);
		Tpl::output('goods_image', $goods_detail['goods_image']);
		Tpl::output('mansong_info', $goods_detail['mansong_info']);
		Tpl::output('gift_array', $goods_detail['gift_array']);

		// 生成缓存的键值
		$hash_key = $goods_info['goods_id'];
		$_cache = rcache($hash_key, 'product');
		if (empty($_cache)) {
			// 查询SNS中该商品的信息
			$snsgoodsinfo = Model('sns_goods') -> getSNSGoodsInfo(array('snsgoods_goodsid' => $goods_info['goods_id']), 'snsgoods_likenum,snsgoods_sharenum');
			$data = array();
			$data['likenum'] = $snsgoodsinfo['snsgoods_likenum'];
			$data['sharenum'] = $snsgoodsinfo['snsgoods_sharenum'];
			// 缓存商品信息
			wcache($hash_key, $data, 'product');
		}
		$goods_info = array_merge($goods_info, $_cache);
		
		$inform_switch = true;
		// 检测商品是否下架,检查是否为店主本人
		if ($goods_info['goods_state'] != 1 || $goods_info['goods_verify'] != 1 || $goods_info['store_id'] == $_SESSION['store_id']) {
			$inform_switch = false;
		}

		Tpl::output('inform_switch', $inform_switch);
		
        //获取发货仓
        $model_region = Model('region');
        $regions = $model_region->getRegions();
        Tpl::output('regions', $regions);

        //获取模版
        $model_transport = Model('transport');
        $transports = $model_transport->getTransports();
        Tpl::output('transports', $transports);
		
		// 如果使用运费模板
		if ($goods_info['transport_id'] > 0) {
			// 取得三种运送方式默认运费
			$model_transport = Model('transport');
			$transport = $model_transport -> getExtendList(array('transport_id' => $goods_info['transport_id'], 'is_default' => 1));
			if (!empty($transport) && is_array($transport)) {
				foreach ($transport as $v) {
					$goods_info[$v['type'] . "_price"] = $v['sprice'];
				}
			}
		}
		Tpl::output('goods', $goods_info);

		//检测是否抢购中的商品(开始)
		$IsHaveBuy = 0;
		if (!empty($_SESSION['member_id'])) {
			$buyer_id = $_SESSION['member_id'];
			$promotion_type = $goods_info["promotion_type"];
			if ($promotion_type == 'groupbuy') {
				//检测是否限购数量
				$upper_limit = $goods_info["upper_limit"];
				if ($upper_limit > 0) {
					//查询些会员的订单中，是否已买过了
					$model_order = Model('order');
					//取商品列表
					$order_goods_list = $model_order -> getOrderGoodsList(array('goods_id' => $goods_id, 'buyer_id' => $buyer_id, 'goods_type' => 2));
					if ($order_goods_list) {
						//取得上次购买的活动编号(防一个商品参加多次团购活动的问题)
						$promotions_id = $order_goods_list[0]["promotions_id"];
						//用此编号取数据，检测是否这次活动的订单商品。
						$model_groupbuy = Model('groupbuy');
						$groupbuy_info = $model_groupbuy -> getGroupbuyInfo(array('groupbuy_id' => $promotions_id));
						if ($groupbuy_info) {
							$IsHaveBuy = 1;
						} else {
							$IsHaveBuy = 0;
						}
					}
				}
			}
		}
		Tpl::output('IsHaveBuy', $IsHaveBuy);
		//检测是否抢购中的商品(完成)

		$model_plate = Model('store_plate');
		// 顶部关联版式
		if ($goods_info['plateid_top'] > 0) {
			$plate_top = $model_plate -> getStorePlateInfoByID($goods_info['plateid_top']);
			Tpl::output('plate_top', $plate_top);
		}
		// 底部关联版式
		if ($goods_info['plateid_bottom'] > 0) {
			$plate_bottom = $model_plate -> getStorePlateInfoByID($goods_info['plateid_bottom']);
			Tpl::output('plate_bottom', $plate_bottom);
		}

		Tpl::output('store_id', $goods_info['store_id']);

		// 输出一级地区
		$area_list = Model('area') -> getTopLevelAreas();

		if (strtoupper(CHARSET) == 'GBK') {
			$area_list = Language::getGBK($area_list);
		}
		Tpl::output('area_list', $area_list);

		//优先得到推荐商品
		if($goods_info['store_id']==6){
		$goods_commend_list = $model_goods -> getGoodsOnlineList(array('store_id' => $goods_info['store_id'], 'goods_commend' => 1), 'goods_id,goods_commonid,goods_name,goods_jingle,goods_image,store_id,goods_price,goods_marketprice', 0, 'rand()', 5, 'goods_commonid');
		Tpl::output('goods_commend', $goods_commend_list);
		}

		// 当前位置导航
		$nav_link_list = Model('goods_class') -> getGoodsClassNav($goods_info['gc_id'], 0);
		$nav_link_list[] = array('title' => $goods_info['goods_name']);
		Tpl::output('nav_link_list', $nav_link_list);

		//评价信息
		$goods_evaluate_info = Model('evaluate_goods') -> getEvaluateGoodsInfoByGoodsID($goods_id);
		Tpl::output('goods_evaluate_info', $goods_evaluate_info);

		$seo_param = array();
		$seo_param['name'] = $goods_info['goods_name'];
		$seo_param['key'] = $goods_info['goods_keywords'];
		$seo_param['description'] = $goods_info['goods_description'];
		Model('seo') -> type('product') -> param($seo_param) -> show();
		 Tpl::showpage('goods_b2b');
	 }
	/**
     * 保存加盟咨询
     */
    public function save_merchantsOp() {
        if (!chksubmit()) {
            showDialog(L('wrong_argument'), 'reload');
        }
		$condition = array();
        $condition['league_name'] = $_POST['username'];
        $condition['league_mobile'] = $_POST['mobile'];
        $condition['league_email'] = $_POST['email'];
        $condition['league_content'] = $_POST['merch_content'];
        $model_league = Model();
        $result = $model_league->table('league')->insert($condition);
        if ($result) {
            showDialog(L('nc_common_op_sub_succeed'), 'reload', 'succ');
        } else {
            showDialog(L('nc_common_op_fail'), 'reload');
        }
    }
	 public function join_b2bOp(){
		 Tpl::showpage('join_b2b','null_layout');		 
	 }
}
