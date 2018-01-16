<?php
/**
 * 清理缓存
 ***/

defined('GcWebShop') or exit('Access Invalid!');

class cacheControl extends SystemControl
{
    protected $cacheItems = array(
        'setting',          // 基本缓存
        'seo',              // SEO缓存
        'groupbuy_price',   // 抢购价格区间
        'nav',              // 底部导航缓存
        'express',          // 快递公司
        'store_class',      // 店铺分类
        'store_grade',      // 店铺等级
        'store_msg_tpl',    // 店铺消息
        'member_msg_tpl',   // 用户消息
        'consult_type',     // 咨询类型
        'circle_level',     // 圈子成员等级
    );

    public function __construct()
    {
        parent::__construct();
        Language::read('cache');
    }

    /**
     * 清理缓存
     */
    public function clearOp()
    {
        if (!chksubmit()) {
            Tpl::showpage('cache.clear');
            return;
        }

        $lang = Language::getLangContent();

        // 清理所有缓存
        if ($_POST['cls_full'] == 1) {
            foreach ($this->cacheItems as $i) {
                dkcache($i);
            }

            // 表主键
            Model::dropTablePkArrayCache();

            // 商品分类
            dkcache('gc_class');
            dkcache('all_categories');
            dkcache('goods_class_seo');
            dkcache('class_tag');

            // 广告
            Model('adv')->makeApAllCache();

            // 首页
            Model('web_config')->getWebHtml('index', 1);
            delCacheFile('index');
        } else {
            $todo = (array) $_POST['cache'];

            foreach ($this->cacheItems as $i) {
                if (in_array($i, $todo)) {
                    dkcache($i);
                }
            }

            // 表主键
            if (in_array('table', $todo)) {
                Model::dropTablePkArrayCache();
            }

            // 商品分类
            if (in_array('goodsclass', $todo)) {
                dkcache('gc_class');
                dkcache('all_categories');
                dkcache('goods_class_seo');
                dkcache('class_tag');
            }

            // 广告
            if (in_array('adv', $todo)) {
                Model('adv')->makeApAllCache();
            }

            // 首页
            if (in_array('index', $todo)) {
                Model('web_config')->getWebHtml('index', 1);
                delCacheFile('index');
            }
        }

        $this->log(L('cache_cls_operate'));
        showMessage($lang['cache_cls_ok']);
    }
	
	/**
     * 清理天天特价缓存
     */
	public function clear_groupbuyOp(){
		$lang = Language::getLangContent();
		$condition = array();
        $condition['start_time'] = array('lt', TIMESTAMP);
        $condition['end_time'] = array('gt', TIMESTAMP);
        $groupbuy = Model('groupbuy')->getGroupbuyList($condition);
        foreach ($groupbuy as $val) {
            Model('goods')->editGoods(array('goods_promotion_price' => $val['groupbuy_price'], 'goods_promotion_type' => 1), array('goods_commonid' => $val['goods_commonid']));
			dcache($val['goods_commonid'], 'goods_groupbuy');//抢购商品列表和详情价格
        }
		dcache('0', 'mb_special');//手机首页
		dcache('1000', 'home_index_groupbuy');//pc首页
		// $this->log('清理特价redis缓存和特价商品价格');
        showMessage($lang['cache_cls_ok']);
	}
	
	/**
     * 清理超低折扣缓存
     */
	public function clear_xianshiOp(){
		$lang = Language::getLangContent();
		$condition = array();
        $condition['start_time'] = array('lt', TIMESTAMP);
        $condition['end_time'] = array('gt', TIMESTAMP);
        $xianshi = Model('p_xianshi_goods')->getXianshiGoodsList($condition);
        foreach ($xianshi as $val) {
            Model('goods')->editGoodsById(array('goods_promotion_price' => $val['xianshi_price'], 'goods_promotion_type' => 2), array('goods_id' => $val['goods_id']));
			dcache($val['goods_id'], 'goods_xianshi');//折扣商品列表和详情价格
        }
		dcache('0', 'mb_special');//手机首页
		dcache('200', 'home_index_xianshi_goods_commend');//pc首页
		// $this->log('清理折扣redis缓存和折扣商品价格');
        showMessage($lang['cache_cls_ok']);
	}
	
	/**
     * 清理满送缓存
     */
	public function clear_mansongOp(){
		$lang = Language::getLangContent();
		dcache('2', 'goods_mansong');
		dcache('7', 'goods_mansong');
		// $this->log('清理满送redis缓存');
        showMessage($lang['cache_cls_ok']);
	}
	
	/**
     * 清理手机专题缓存
     */
	public function clear_specialOp(){
		$lang = Language::getLangContent();
		dcache($_POST['spe_num'], 'mb_special');
		// $this->log('清理手机专题'.$_POST['spe_num'].'redis缓存');
        showMessage($lang['cache_cls_ok']);
	}
}
