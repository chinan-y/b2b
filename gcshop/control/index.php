<?php
/**
 * 默认展示页面
 *
 *
*/


defined('GcWebShop') or exit('Access Invalid!');
class indexControl extends BaseHomeControl{
	public function indexOp(){
		Language::read('home_index_index');
		Tpl::output('index_sign','index');
		//把加密的用户id写入cookie 已换另一个方式，临时去掉此方法
		//$uid = $_GET['uid'];
		//setcookie('uid', $uid);
		$uid = intval(base64_decode($_COOKIE['uid']));
		
		//获得推荐人ID
		$zmr=intval($_GET['zmr']);
		if($zmr>0)
		{
		   setcookie('zmr', $zmr);
		}
		$refer = intval($_GET['ref']);
		if($refer>0)
		{
			setcookie('ref', $refer);
		}
		//记录销售来源平台  by liu
		$saleplat=intval($_GET['saleplat']);
		if($saleplat>0)
		{
		  setcookie('saleplat', $saleplat);
		}
        

		
		//查询首页所有品牌
		$model_brand = Model('brand');
        $brand_c_list = $model_brand->getIndexBrandList(1000);
        Tpl::output('brand_c_list',$brand_c_list);
		
		//查询首页抢购专区
		Language::read('member_groupbuy');
        $model_groupbuy = Model('groupbuy');
        $group_list = $model_groupbuy->getIndexGroupbuyCommendedList(1000);
		foreach($group_list as $k => $val){
			$re = Model('goods')->getGoodsInfo(array('goods_id'=> $val['goods_id']),'goods_jingle, country_code');
			$group_list[$k]['goods_jingle'] = $re['goods_jingle'];
			$group_list[$k]['country_code'] = $re['country_code'];
		}
		Tpl::output('group_list', $group_list);
		
		//查询首页抢购开售预告
		$group_preview = $model_groupbuy->getIndexGroupbuyPreviewList(1000);
		Tpl::output('group_time', $group_preview);
		
		//查询首页友情链接
		$model_link = Model('link');
		$link_list = $model_link->getIndexLinkList(1000);
		/**
		 * 整理图片链接
		 */
		if (is_array($link_list)){
			foreach ($link_list as $k => $v){
				if (!empty($v['link_pic'])){
					$link_list[$k]['link_pic'] = UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/common/'.DS.$v['link_pic'];
				}
			}
		}
		Tpl::output('$link_list',$link_list);
	
		//首页限时折扣
        $model_xianshi_goods = Model('p_xianshi_goods');
        $xianshi_item = $model_xianshi_goods->getIndexXianshiGoodsCommendList(200);
		Tpl::output('xianshi_item', $xianshi_item);

		//首页板块信息
		$model_web_config = Model('web_config');
		$web_html = $model_web_config->getIndexWebHtml('index');
		Tpl::output('web_html',$web_html);
		// header("Content-type: text/html; charset=utf-8");echo '<pre>';print_r($web_html['index_pic']);exit;
		//首页站点公告
		$model_setting = Model('setting');
		$site_notice = $model_setting->getIndexSiteNotice();
		Tpl::output('site_notice',$site_notice);
			
		Model('seo')->type('index')->show();
		Tpl::showpage('index');
	}
	public function setLanguageOp(){
		$language=$_POST['language'];
		if(isset($language)){
			$_SESSION['language']=$language;
			
		}
		if($_SESSION['language']){
			echo 1;die;
		}
		
	}
	//json首页推荐商品
	public function josn_index_goodsOp() {
		//首页推荐商品
		$curpage = intval($_REQUEST['curpage']);
		$curpage = $curpage > 1 ? $curpage : 1;

		$model_goods = Model('goods');
		$goods_commend_list = $model_goods->getIndexGoodsCommendList($curpage);

		foreach($goods_commend_list as $key=>$value){
			
			$commonid = $model_goods->getGoodsIn(array('goods_id' => $value['goods_id']), 'goods_commonid');
			$value['goods_commonid'] =  $commonid[0]['goods_commonid'];
			$goods_commend_list[$key]['goods_href'] = urlShop('goods', 'index', array('goods_id' => $value['goods_id'],'ref'=>$_SESSION['member_id']));
			$goods_commend_list[$key]['store_href'] = urlShop('show_store', 'index', array('store_id' => $value['store_id'],'ref'=>$_SESSION['member_id']));
			$goods_commend_list[$key]['goods_image'] = thumb($value, 240);
		}
		$array = $goods_commend_list;

		/**
		 * 转码
		 */
		if (strtoupper(CHARSET) == 'GBK'){
			$array = Language::getUTF8(array_values($array));//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
		} else {
			$array = array_values($array);
		}
		echo json_encode($array);
	}

	//json输出商品分类
	public function josn_classOp() {
		/**
		 * 实例化商品分类模型
		 */
		$model_class		= Model('goods_class');
		$goods_class		= $model_class->getGoodsClassListByParentId(intval($_GET['gc_id']));
		$array				= array();
		if(is_array($goods_class) and count($goods_class)>0) {
			foreach ($goods_class as $val) {
				$array[$val['gc_id']] = array('gc_id'=>$val['gc_id'],'gc_name'=>htmlspecialchars($val['gc_name']),'gc_parent_id'=>$val['gc_parent_id'],'commis_rate'=>$val['commis_rate'],'gc_sort'=>$val['gc_sort']);
			}
		}
		/**
		 * 转码
		 */
		if (strtoupper(CHARSET) == 'GBK'){
			$array = Language::getUTF8(array_values($array));//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
		} else {
			$array = array_values($array);
		}
		echo $_GET['callback'].'('.json_encode($array).')';
	}

   
	
	//闲置物品地区json输出
	public function flea_areaOp() {
		if(intval($_GET['check']) > 0) {
			$_GET['area_id'] = $_GET['region_id'];
		}
		if(intval($_GET['area_id']) == 0) {
			return ;
		}
		$model_area	= Model('flea_area');
		$area_array			= $model_area->getListArea(array('flea_area_parent_id'=>intval($_GET['area_id'])),'flea_area_sort desc');
		$array	= array();
		if(is_array($area_array) and count($area_array)>0) {
			foreach ($area_array as $val) {
				$array[$val['flea_area_id']] = array('flea_area_id'=>$val['flea_area_id'],'flea_area_name'=>htmlspecialchars($val['flea_area_name']),'flea_area_parent_id'=>$val['flea_area_parent_id'],'flea_area_sort'=>$val['flea_area_sort']);
			}
			/**
			 * 转码
			 */
			if (strtoupper(CHARSET) == 'GBK'){
				$array = Language::getUTF8(array_values($array));//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
			} else {
				$array = array_values($array);
			}
		}
		if(intval($_GET['check']) > 0) {//判断当前地区是否为最后一级
			if(!empty($array) && is_array($array)) {
				echo 'false';
			} else {
				echo 'true';
			}
		} else {
			echo json_encode($array);
		}
	}

	//json输出闲置物品分类
	public function josn_flea_classOp() {
		/**
		 * 实例化商品分类模型
		 */
		$model_class		= Model('flea_class');
		$goods_class		= $model_class->getClassList(array('gc_parent_id'=>intval($_GET['gc_id'])));
		$array				= array();
		if(is_array($goods_class) and count($goods_class)>0) {
			foreach ($goods_class as $val) {
				$array[$val['gc_id']] = array('gc_id'=>$val['gc_id'],'gc_name'=>htmlspecialchars($val['gc_name']),'gc_parent_id'=>$val['gc_parent_id'],'gc_sort'=>$val['gc_sort']);
			}
		}
		/**
		 * 转码
		 */
		if (strtoupper(CHARSET) == 'GBK'){
			$array = Language::getUTF8(array_values($array));//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
		} else {
			$array = array_values($array);
		}
		echo json_encode($array);
	}
	
	/**
     * json输出地址数组 原data/resource/js/area_array.js
     */
    public function json_areaOp()
    {
        echo $_GET['callback'].'('.json_encode(Model('area')->getAreaArrayForJson()).')';
    }
	//判断是否登录
	public function loginOp(){
		echo ($_SESSION['is_login'] == '1')? '1':'0';
	}

	/**
	 * 头部最近浏览的商品
	 */
	public function viewed_infoOp(){
	    $info = array();
		if ($_SESSION['is_login'] == '1') {
		    $member_id = $_SESSION['member_id'];
		    $info['m_id'] = $member_id;
		    if (C('voucher_allow') == 1) {
		        $time_to = time();//当前日期
    		    $info['voucher'] = Model()->table('voucher')->where(array('voucher_owner_id'=> $member_id,'voucher_state'=> 1,
    		    'voucher_start_date'=> array('elt',$time_to),'voucher_end_date'=> array('egt',$time_to)))->count();
		    }
    		$time_to = strtotime(date('Y-m-d'));//当前日期
    		$time_from = date('Y-m-d',($time_to-60*60*24*7));//7天前
		    $info['consult'] = Model()->table('consult')->where(array('member_id'=> $member_id,
		    'consult_reply_time'=> array(array('gt',strtotime($time_from)),array('lt',$time_to+60*60*24),'and')))->count();
		}
		$goods_list = Model('goods_browse')->getViewedGoodsList($_SESSION['member_id'],5);
		if(is_array($goods_list) && !empty($goods_list)) {
		    $viewed_goods = array();
		    foreach ($goods_list as $key => $val) {
		        $goods_id = $val['goods_id'];
		        $val['url'] = urlShop('goods', 'index', array('goods_id' => $goods_id,'ref'=>$_SESSION['member_id']));
		        $val['goods_image'] = thumb($val, 60);
		        $viewed_goods[$goods_id] = $val;
		    }
		    $info['viewed_goods'] = $viewed_goods;
		}
		if (strtoupper(CHARSET) == 'GBK'){
			$info = Language::getUTF8($info);
		}
		echo json_encode($info);
	}
	/**
	 * 查询每月的周数组
	 */
	public function getweekofmonthOp(){
	    import('function.datehelper');
	    $year = $_GET['y'];
	    $month = $_GET['m'];
	    $week_arr = getMonthWeekArr($year, $month);
	    echo json_encode($week_arr);
	    die;
	}

	/**
	 * 首页抢购商品预告
	 * @param int $count 推荐数量
	 * @return array 推荐商品列表
	 *
	 */
	public function getIndexGroupbuyPreviewList($count = 1000) {
		$prefix = 'home_index_groupbuy_preview';

		$item_list = rcache($count, $prefix);
		//缓存有效
		if(!empty($item_list)) {
			return unserialize($item_list['groupbuy_preview']);
		}

		//缓存无效查库并缓存
		$condition = array();
		$condition['state'] = self::GROUPBUY_STATE_NORMAL;
		$condition['start_time'] = array('gt',TIMESTAMP);
		$item_list = $this->getGroupbuyExtendList($condition, null, 'recommended desc', '*', $count);

		if(!empty($item_list)) {
			foreach ($item_list as $key=>$value) {
				//处理图片
				$item_data[$key]['goods_image'] = cthumb($value['goods_image']);
			}
		}
		$cache = array('groupbuy_preview' => serialize($item_list));
		wcache($count, $cache, $prefix);
		return $item_list;
	}
	
}
