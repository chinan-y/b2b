<?php
/**
 * 商品列表
 *
 ***/


defined('GcWebShop') or exit('Access Invalid!');

class searchControl extends BaseHomeControl {


    //每页显示商品数
    const PAGESIZE = 100;

    //模型对象
    private $_model_search;

    public function indexOp() {
        Language::read('home_goods_class_index');
        $this->_model_search = Model('search');

	//记录销售来源平台  by liu
		$saleplat=intval($_GET['saleplat']);
		if($saleplat>0)
		{
		  setcookie('saleplat', $saleplat);
		}


        //显示左侧分类
        //默认分类，从而显示相应的属性和品牌
        $default_classid = intval($_GET['cate_id']);
        if (intval($_GET['cate_id']) > 0) {
            $goods_class_array = $this->_model_search->getLeftCategory(array($_GET['cate_id']));
        } elseif ($_GET['keyword'] != '') {
            $_GET['keyword'] = rawurldecode($_GET['keyword']);
            //从TAG中查找分类
            $goods_class_array = $this->_model_search->getTagCategory($_GET['keyword']);
            //取出第一个分类作为默认分类，从而显示相应的属性和品牌
            $default_classid = $goods_class_array[0];
            $goods_class_array = $this->_model_search->getLeftCategory($goods_class_array, 1);
        }
        Tpl::output('goods_class_array', $goods_class_array);
        Tpl::output('default_classid', $default_classid);

        //优先从全文索引库里查找
        list($indexer_ids,$indexer_count) = $this->_model_search->indexerSearch($_GET,self::PAGESIZE);

        //获得经过属性过滤的商品信息
        list($goods_param, $brand_array, $attr_array, $checked_brand, $checked_attr) = $this->_model_search->getAttr($_GET, $default_classid);
        Tpl::output('brand_array', $brand_array);
        Tpl::output('attr_array', $attr_array);
        Tpl::output('checked_brand', $checked_brand);
        Tpl::output('checked_attr', $checked_attr);

        //处理排序
        $order = 'is_own_shop desc,goods_sorts desc';
        if (in_array($_GET['key'],array('1','2','3','4'))) {
            $sequence = $_GET['order'] == '1' ? 'asc' : 'desc';
            $order = str_replace(array('1','2','3','4'), array('goods_id','goods_salenum','goods_click','goods_promotion_price'), $_GET['key']);
            $order .= ' '.$sequence;
        }
        $model_goods = Model('goods');
        // 字段
        $fields = "goods_id,goods_commonid,goods_name,sku_spec,goods_jingle,goods_fenlei,goods_serial,goods_spec,gc_id,store_id,store_name,goods_price,goods_promotion_price,goods_promotion_type,goods_marketprice,goods_storage,goods_image,goods_freight,goods_salenum,color_id,evaluation_good_star,evaluation_count,is_virtual,is_fcode,store_from,is_appoint,is_presell,have_gift,pack_units,country_code,goods_rebate_rate";

        $condition = array();
        if (is_array($indexer_ids)) {

            //商品主键搜索
            $condition['goods_id'] = array('in',$indexer_ids);
            $goods_list = $model_goods->getGoodsOnlineList($condition, $fields, 0, $order, self::PAGESIZE, null, false);

            //如果有商品下架等情况，则删除下架商品的搜索索引信息
            if (count($goods_list) != count($indexer_ids)) {
                $this->_model_search->delInvalidGoods($goods_list, $indexer_ids);
            }

            pagecmd('setEachNum',self::PAGESIZE);
            pagecmd('setTotalNum',$indexer_count);

        } else {
            //执行正常搜索
		require BASE_ROOT_PATH.'/data/api/pscws4/pscws4.class.php';
		$pscws = new PSCWS4('utf8');
		$pscws ->set_dict(BASE_ROOT_PATH.'/data/api/pscws4/etc/dict.utf8.xdb');
		$pscws ->set_rule(BASE_ROOT_PATH.'/data/api/pscws4/etc/rules.utf8.ini');
		$pscws ->set_ignore(true);
		$pscws ->set_duality(bool,false);
		$pscws->send_text($_GET['keyword']);
		$ret = $pscws->get_tops(10,'r,v,p');
		$ret[count($ret)]['word']=$_GET['keyword'];
		foreach($ret as $key=>$value){
			
			
		
            if (isset($goods_param['class'])) {
                $condition['gc_id_'.$goods_param['class']['depth']] = $goods_param['class']['gc_id'];
            }
            if (intval($_GET['b_id']) > 0) {
                $condition['brand_id'] = intval($_GET['b_id']);
            }
            if ($value['word'] != '') {
                //$condition['goods_name|goods_jingle|goods_fenlei|sku_spec|goods_serial|goods_promotion_price|store_name|pack_units|goods_id'] = array('like', '%' . $_GET['keyword'] . '%');
                $condition['goods_name|goods_jingle|goods_fenlei|goods_serial|store_name|goods_id'] = array('like', '%' . $value['word'] . '%');
          
		  }
            if (intval($_GET['area_id']) > 0) {
                $condition['areaid_1'] = intval($_GET['area_id']);
            }
            if ($_GET['type'] == 1) {
                $condition['is_own_shop'] = 1;
            }
            if ($_GET['gift'] == 1) {
                $condition['have_gift'] = 1;
            }
            if (isset($goods_param['goodsid_array'])){
                $condition['goods_id'] = array('in', $goods_param['goodsid_array']);
            }
            $goods_list[$key] = $model_goods->getGoodsListByColorDistinct($condition, $fields, $order, self::PAGESIZE);
			
		}
		$array=array();
		foreach($goods_list as $k=>$v){
			$array=array_merge($array,$goods_list[$k]);
		}
		unset($goods_list);
		// $goods_list=$array;
		$goods=array();
		foreach($array as $kkk=>$vvv){
			
			$goods[$vvv['goods_id']]=$vvv;
		}
		$goods_list=array_merge($goods);
        }
		// header("Content-type: text/html; charset=utf-8");echo '<pre>';print_r($array);exit;
        Tpl::output('show_page1', $model_goods->showpage(4));
        Tpl::output('show_page', $model_goods->showpage(5));

        // 商品多图
        if (!empty($goods_list)) {
            $commonid_array = array(); // 商品公共id数组
            $storeid_array = array();       // 店铺id数组
            foreach ($goods_list as $value) {
                $commonid_array[] = $value['goods_commonid'];
                $storeid_array[] = $value['store_id'];
            }
            $commonid_array = array_unique($commonid_array);
            $storeid_array = array_unique($storeid_array);

            // 商品多图
            $goodsimage_more = Model('goods')->getGoodsImageList(array('goods_commonid' => array('in', $commonid_array)));
			
			

            // 店铺
            $store_list = Model('store')->getStoreMemberIDList($storeid_array);
            //搜索的关键字
            $search_keyword = trim($_GET['keyword']);
            foreach ($goods_list as $key => $value) {
                // 商品多图
				//zmr>v30
				$n=0;
                foreach ($goodsimage_more as $v) {
                    if ($value['goods_commonid'] == $v['goods_commonid'] && $value['store_id'] == $v['store_id'] && $value['color_id'] == $v['color_id']) {
						$n++;
						$v['goods_id'] = $value['goods_id'];
						$goods_list[$key]['image'][] = $v;
						if($n>=5)break;
                    }
                }
				
				$member = Model('member')->getMemberInfo(array('member_id'=>$_SESSION['member_id']), 'member_examine,member_company_name');
				if(!$_SESSION['member_id']){
					$goods_list[$key]['goods_href'] = urlShop('login', 'index');
					$goods_list[$key]['show_note'] = '登录后查看价格';
					$goods_list[$key]['add_cart'] = '登录后查看';
					$goods_list[$key]['show_price'] = 1;
				}else if($member['member_examine'] ==0 && !$member['member_company_name']){
					$goods_list[$key]['goods_href'] = urlShop('login', 'member_verify');
					$goods_list[$key]['show_note'] = '认证后查看价格';
					$goods_list[$key]['add_cart'] = '认证后查看';
					$goods_list[$key]['show_price'] = 1;
				}else if(($member['member_examine'] ==0 && $member['member_company_name']) || $member['member_examine'] ==2){
					$goods_list[$key]['goods_href'] = urlShop('login', 'await_verify');
					$goods_list[$key]['show_note'] = '审核后查看价格';
					$goods_list[$key]['add_cart'] = '审核后查看';
					$goods_list[$key]['show_price'] = 1;
				}else{
					$goods_list[$key]['goods_href'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
				}
				
				$rule = Model()->table('goods_price_rule')->where(array('goods_id'=>$value['goods_id']))->find();
				if($rule && $_SESSION['member_id'] && $member['member_company_name'] && $member['member_examine'] == 1){
					$goods_list[$key]['rule_info'] = $rule;
				}
				
                // 店铺的开店会员编号
                $store_id = $value['store_id'];
                $goods_list[$key]['member_id'] = $store_list[$store_id]['member_id'];
                $goods_list[$key]['store_domain'] = $store_list[$store_id]['store_domain'];
                //将关键字置红
                if ($search_keyword){
					foreach($ret as $kk=>$vv){
                    $goods_list[$key]['goods_name_highlight'] = str_replace($vv['word'],'<font style="color:#f00;">'.$vv['word'].'</font>',$value['goods_name']);
					}
				} else {
                    $goods_list[$key]['goods_name_highlight'] = $value['goods_name'];
                }
            }
        }
        Tpl::output('goods_list', $goods_list);
        if ($_GET['keyword'] != ''){
            Tpl::output('show_keyword',  $_GET['keyword']);
        } else {
            Tpl::output('show_keyword',  $goods_param['class']['gc_name']);
        }

        $model_goods_class = Model('goods_class');

        // SEO
        if ($_GET['keyword'] == '') {
            $seo_class_name = $goods_param['class']['gc_name'];
            if (is_numeric($_GET['cate_id']) && empty($_GET['keyword'])) {
                $seo_info = $model_goods_class->getKeyWords(intval($_GET['cate_id']));
                if (empty($seo_info[1])) {
                    $seo_info[1] = C('site_name') . ' - ' . $seo_class_name;
                }
				if(!empty($seo_info[2])){
					$re = substr($seo_info[2] , 0 ,strpos($seo_info[2],','));
					$seo_info[2] = $re ? $re : $seo_info[2];
					
				}
				if(!empty($seo_info[3])){
					$re = substr($seo_info[3] , 0 ,strpos($seo_info[3],','));
					$seo_info[3] = $re ? $re : $seo_info[3];
				}
                $seo_param = array();
				$seo_param['title'] = $seo_class_name.'品种齐全，价格优惠。-光彩全球网';
				$seo_param['keywords'] 	= $seo_class_name.'、'.$seo_class_name.'价格、'.$seo_class_name.'优惠、评论、图片';
				$seo_param['description'] 	= '光彩全球“我的网购，我的生活”网上购物商城。您可以在齐全的进口'.$seo_class_name.'---进口'.$seo_class_name.'品类中选购。也可了解'.$seo_class_name.'的最新价格、优惠促销、网友评论导购、图片等相关信息。';
				
                Model('seo')->type($seo_info)->param($seo_param)->show();
            }
        } elseif ($_GET['keyword'] != '') {
            Tpl::output('html_title', (empty($_GET['keyword']) ? '' : $_GET['keyword'] . ' - ') . C('site_name') . L('nc_common_search'));
        }

        // 当前位置导航
        $nav_link_list = $model_goods_class->getGoodsClassNav(intval($_GET['cate_id']));
        Tpl::output('nav_link_list', $nav_link_list );

        // 得到自定义导航信息
        $nav_id = intval($_GET['nav_id']) ? intval($_GET['nav_id']) : 0;
        Tpl::output('index_sign', $nav_id);

        // 地区
        $province_array = Model('area')->getTopLevelAreas();
        Tpl::output('province_array', $province_array);

        loadfunc('search');

        // 浏览过的商品
        $viewed_goods = Model('goods_browse')->getViewedGoodsList($_SESSION['member_id'],20);
        Tpl::output('viewed_goods',$viewed_goods);
        Tpl::showpage('search');
    }
    /**
     * 获得推荐商品
     */
	
    public function get_booth_goodsOp() {
        $gc_id = $_GET['cate_id'];
        if ($gc_id <= 0) {
            return false;
        }
        // 获取分类id及其所有子集分类id
        $goods_class = Model('goods_class')->getGoodsClassForCacheModel();
        if (empty($goods_class[$gc_id])) {
            return false;
        }
        $child = (!empty($goods_class[$gc_id]['child'])) ? explode(',', $goods_class[$gc_id]['child']) : array();
        $childchild = (!empty($goods_class[$gc_id]['childchild'])) ? explode(',', $goods_class[$gc_id]['childchild']) : array();
        $gcid_array = array_merge(array($gc_id), $child, $childchild);
        // 查询添加到推荐展位中的商品id
        $boothgoods_list = Model('p_booth')->getBoothGoodsList(array('gc_id' => array('in', $gcid_array)), 'goods_id', 0, 4, 'rand()');
        if (empty($boothgoods_list)) {
            return false;
        }

        $goodsid_array = array();
        foreach ($boothgoods_list as $val) {
            $goodsid_array[] = $val['goods_id'];
        }

        $fieldstr = "goods_id,goods_commonid,goods_name,goods_jingle,goods_fenlei,store_id,store_name,goods_price,goods_promotion_price,goods_promotion_type,goods_marketprice,goods_storage,goods_image,goods_freight,goods_salenum,color_id,evaluation_count";
        $goods_list = Model('goods')->getGoodsOnlineList(array('goods_id' => array('in', $goodsid_array)), $fieldstr);
        if (empty($goods_list)) {
            return false;
        }

        Tpl::output('goods_list', $goods_list);
        Tpl::showpage('goods.booth', 'null_layout');
    }

	public function auto_completeOp() {
	    try {
    	    require(BASE_DATA_PATH.'/api/xs/lib/XS.php');
    	    $obj_doc = new XSDocument();
    	    $obj_xs = new XS(C('fullindexer.appname'));
    	    $obj_index = $obj_xs->index;
    	    $obj_search = $obj_xs->search;
    	    $obj_search->setCharset(CHARSET);
            $corrected = $obj_search->getExpandedQuery($_GET['term']);
            if (count($corrected) !== 0) {
                $data = array();
                foreach ($corrected as $word)
                {
                    $row['id'] = $word;
                    $row['label'] = $word;
                    $row['value'] = $word;
                    $data[] = $row;
                }
                exit(json_encode($data));
            }
        } catch (XSException $e) {
            if (is_object($obj_index)) {
                $obj_index->flushIndex();
            }
//             Log::record('search\auto_complete'.$e->getMessage(),Log::RUN);
        }
	}

	/**
	 * 获得猜你喜欢
	 */
	public function get_guesslikeOp(){
	    $goodslist = Model('goods_browse')->getGuessLikeGoods($_SESSION['member_id'], 20);
	    Tpl::output('goodslist',$goodslist);
	    Tpl::showpage('goods_guesslike','null_layout');
	}
}
