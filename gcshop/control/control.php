<?php
/**
 * 前台control父类,店铺control父类,会员control父类
 *
 ***/


defined('GcWebShop') or exit('Access Invalid!');

class Control{
    /**
     * 检查短消息数量
     *
     */
    protected function checkMessage() {
        if($_SESSION['member_id'] == '') return ;
        //判断cookie是否存在
        $cookie_name = 'msgnewnum'.$_SESSION['member_id'];
        if (cookie($cookie_name) != null){
            $countnum = intval(cookie($cookie_name));
        }else {
            $message_model = Model('message');
            $countnum = $message_model->countNewMessage($_SESSION['member_id']);
            setNcCookie($cookie_name,"$countnum",2*3600);//保存2小时
        }
        Tpl::output('message_num',$countnum);
    }

    /**
     *  输出头部的公用信息
     *
     */
    protected function showLayout() {
        $this->checkMessage();//短消息检查
        $this->article();//文章输出

        $this->showCartCount();

        Tpl::output('hot_search',@explode(',',C('hot_search')));//热门搜索

        $model_class = Model('goods_class');
        $goods_class = $model_class->get_all_category();
        Tpl::output('show_goods_class',$goods_class);//商品分类
		
		require_once BASE_PATH.'/config/returnLang.php';
		Tpl::output('lang', $lang);
        //获取导航
        Tpl::output('nav_list', rkcache('nav',true));
    }

    /**
     * 显示购物车数量
     */
    protected function showCartCount() {
        if (cookie('cart_goods_num') != null){
            $cart_num = intval(cookie('cart_goods_num'));
        }else {
            //已登录状态，存入数据库,未登录时，优先存入缓存，否则存入COOKIE
            if($_SESSION['member_id']) {
                $save_type = 'db';
            } else {
                $save_type = 'cookie';
            }
            $cart_num = Model('cart')->getCartNum($save_type,array('buyer_id'=>$_SESSION['member_id']));//查询购物车商品种类
        }
        Tpl::output('cart_goods_num',$cart_num);
    }

    /**
     * 输出会员等级
     * @param bool $is_return 是否返回会员信息，返回为true，输出会员信息为false
     */
    protected function getMemberAndGradeInfo($is_return = false){
        $member_info = array();
        //会员详情及会员级别处理
        if($_SESSION['member_id']) {
            $model_member = Model('member');
            $member_info = $model_member->getMemberInfoByID($_SESSION['member_id']);
            if ($member_info){
                $member_gradeinfo = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));
                $member_info = array_merge($member_info,$member_gradeinfo);
            }
        }
        if ($is_return == true){//返回会员信息
            return $member_info;
        } else {//输出会员信息
            Tpl::output('member_info',$member_info);
        }
    }

    /**
     * 验证会员是否登录
     *
     */
    protected function checkLogin(){
        if ($_SESSION['is_login'] !== '1'){
            if (trim($_GET['gp']) == 'favoritesgoods' || trim($_GET['gp']) == 'favoritesstore'){
                $lang = Language::getLangContent('UTF-8');
                echo json_encode(array('done'=>false,'msg'=>$lang['no_login']));
                die;
            }
            $ref_url = request_uri();
            if ($_GET['inajax']){
                showDialog('','','js',"login_dialog();",200);
            }else {
                @header("location: index.php?gct=login&ref_url=".urlencode($ref_url));
            }
            exit;
        }
    }

    /**
     * 添加到任务队列
     *
     * @param array $goods_array
     * @param boolean $ifdel 是否删除以原记录
     */
    protected function addcron($data = array(), $ifdel = false) {
        $model_cron = Model('cron');
        if (isset($data[0])) { // 批量插入
            $where = array();
            foreach ($data as $k => $v) {
                if (isset($v['content'])) {
                    $data[$k]['content'] = serialize($v['content']);
                }
                // 删除原纪录条件
                if ($ifdel) {
                    $where[] = '(type = ' . $data['type'] . ' and exeid = ' . $data['exeid'] . ')';
                }
            }
            // 删除原纪录
            if ($ifdel) {
                $model_cron->delCron(implode(',', $where));
            }
            $model_cron->addCronAll($data);
        } else { // 单条插入
            if (isset($data['content'])) {
                $data['content'] = serialize($data['content']);
            }
            // 删除原纪录
            if ($ifdel) {
                $model_cron->delCron(array('type' => $data['type'], 'exeid' => $data['exeid']));
            }
            $model_cron->addCron($data);
        }
    }

    //文章输出
    protected function article() {

		$model_article_class	= Model('article_class');
		
		$condition	= array();
		$condition['ac_parent_id']	= 2;
		$sub_class_list	= $model_article_class->getClassList($condition);
		Tpl::output('sub_class_list',$sub_class_list);
		
        if (C('cache_open')) {
            if ($article = rkcache("index/article")) {
                Tpl::output('show_article', $article['show_article']);
                Tpl::output('article_list', $article['article_list']);
                return;
            }
        } else {
            if (file_exists(BASE_DATA_PATH.'/cache/index/article.php')){
                include(BASE_DATA_PATH.'/cache/index/article.php');
                Tpl::output('show_article', $show_article);
                Tpl::output('article_list', $article_list);
                return;
            }
        }

        $model_article	= Model('article');
        $show_article = array();//商城公告
        $article_list	= array();//下方文章
        $notice_class	= array('notice');
        $code_array	= array('member','store','payment','sold','service','about');
        $notice_limit	= 5;
        $faq_limit	= 5;

        $class_condition	= array();
        $class_condition['home_index'] = 'home_index';
        $class_condition['order'] = 'ac_sort asc';
        $article_class	= $model_article_class->getClassList($class_condition);
        $class_list	= array();
        if(!empty($article_class) && is_array($article_class)){
            foreach ($article_class as $key => $val){
                $ac_code = $val['ac_code'];
                $ac_id = $val['ac_id'];
                $val['list']	= array();//文章
                $class_list[$ac_id]	= $val;
            }
        }

        $condition	= array();
        $condition['article_show'] = '1';
        $condition['home_index'] = 'home_index';
        $condition['field'] = 'article.article_id,article.ac_id,article.article_url,article.article_title,article.article_time,article_class.ac_name,article_class.ac_parent_id';
        $condition['order'] = 'article_sort asc,article_time desc';
        $condition['limit'] = '300';
        $article_array	= $model_article->getJoinList($condition);
        if(!empty($article_array) && is_array($article_array)){
            foreach ($article_array as $key => $val){
                $ac_id = $val['ac_id'];
                $ac_parent_id = $val['ac_parent_id'];
                if($ac_parent_id == 0) {//顶级分类
                    $class_list[$ac_id]['list'][] = $val;
                } else {
                    $class_list[$ac_parent_id]['list'][] = $val;
                }
            }
        }
        if(!empty($class_list) && is_array($class_list)){
            foreach ($class_list as $key => $val){
                $ac_code = $val['ac_code'];
                if(in_array($ac_code,$notice_class)) {
                    $list = $val['list'];
                    array_splice($list, $notice_limit);
                    $val['list'] = $list;
                    $show_article[$ac_code] = $val;
                }
                if (in_array($ac_code,$code_array)){
                    $list = $val['list'];
                    $val['class']['ac_name']	= $val['ac_name'];
                    array_splice($list, $faq_limit);
                    $val['list'] = $list;
                    $article_list[] = $val;
                }
            }
        }

        if (C('cache_open')) {
            wkcache('index/article', array(
                'show_article' => $show_article,
                'article_list' => $article_list,
            ));
        } else {
            $string = "<?php\n\$show_article=".var_export($show_article,true).";\n";
            $string .= "\$article_list=".var_export($article_list,true).";\n?>";
            file_put_contents(BASE_DATA_PATH.'/cache/index/article.php',($string));
        }

        Tpl::output('show_article',$show_article);
        Tpl::output('article_list',$article_list);
    }
}

/********************************** 前台control父类 **********************************************/

class BaseHomeControl extends Control {

    public function __construct(){
        //输出头部的公用信息
        $this->showLayout();
        //输出会员信息
        $this->getMemberAndGradeInfo(false);

        Language::read('common,home_layout');

        Tpl::setDir('home');

        Tpl::setLayout('home_layout');

        if ($_GET['column'] && strtoupper(CHARSET) == 'GBK'){
            $_GET = Language::getGBK($_GET);
        }
        if(!C('site_status')) halt(C('closed_reason'));
    }

}

/********************************** 购买流程父类 **********************************************/

class BaseBuyControl extends Control {
    protected $member_info = array();   // 会员信息
    
    protected function __construct(){
        Language::read('common,home_layout');
        //输出会员信息
        $this->member_info = $this->getMemberAndGradeInfo(true);
        Tpl::output('member_info', $this->member_info);
        
        Tpl::setDir('buy');
        Tpl::setLayout('buy_layout');
        if ($_GET['column'] && strtoupper(CHARSET) == 'GBK'){
            $_GET = Language::getGBK($_GET);
        }
        if(!C('site_status')) halt(C('closed_reason'));
        //获取导航
        Tpl::output('nav_list', rkcache('nav',true));

        //获取发货仓
        $model_region = Model('region');
        $regions = $model_region->getRegions();
        Tpl::output('regions', $regions);

        //获取模版
        $model_transport = Model('transport');
        $transports = $model_transport->getTransports();
        Tpl::output('transports', $transports);
    }
}

/********************************** 会员control父类 **********************************************/

class BaseMemberControl extends Control {
    protected $member_info = array();   // 会员信息
    protected $quicklink = array();       // 常用菜单
    public function __construct(){

        if(!C('site_status')) halt(C('closed_reason'));

        Language::read('common,member_layout');

        if ($_GET['column'] && strtoupper(CHARSET) == 'GBK'){
            $_GET = Language::getGBK($_GET);
        }
        //会员验证
        $this->checkLogin();
        //输出头部的公用信息
        $this->showLayout();
        Tpl::setDir('member');
        Tpl::setLayout('member_layout');

        //获得会员信息
        $this->member_info = $this->getMemberAndGradeInfo(true);
        $this->quicklink = explode(',', $this->member_info['member_quicklink']);
        Tpl::output('member_info', $this->member_info);

        // 常用操作及导航
        $common_menu_list = $this->_getCommonOperationsAndNavLink();

    }

    /**
     * 常用操作
     *
     * @param string $gct
     * 如果菜单中的切换卡不在一个菜单中添加$act参数，值为当前菜单的下标
     *
     */
    protected function _getCommonOperationsAndNavLink ($gct = '') {
        // 左侧导航
        $menu_list = $this->_getMenuList();
        $operations_list = array();
        foreach ($menu_list as $key => $val) {
            foreach ($val['child'] as $k=>$v) {
                if (in_array($k, $this->quicklink)) {
                    $ql = array_flip($this->quicklink);
                    $operations_list[$ql[$k]] = array_merge($v,array('key' => $k));
                    $menu_list[$key]['child'][$k]['selected'] = true;
                }
                if (($_GET['gct'] == $k && $gct == '') || $gct == $k) {
                    $nav['gct'] = $k;
                    $nav['name'] = $v['name'];
                }
            }
        }
        Tpl::output('menu_list', $menu_list);
        // 菜单高亮
        Tpl::output('menu_highlight', $nav['gct']);
        ksort($operations_list);
        Tpl::output('common_menu_list', $operations_list);


        // 面包屑
        $nav_link = array();
        $nav_link[] = array('title' => L('homepage'), 'link'=>SHOP_SITE_URL);
        if ($nav == '') {
            $nav_link[] = array('title' => L('member_center'));
        } else {
            $nav_link[] = array('title' => L('member_center'),  'link' => urlShop('member', 'home'));
            $nav_link[] = array('title' => $nav['name']);
        }
        Tpl::output('nav_link_list',$nav_link);
    }
	
	/**
	 * 买家的左侧上部的头像和订单数量
	 *
	 */
	public function get_member_info() {
		//生成缓存的键值
		$hash_key = $_SESSION['member_id'];
		//写入缓存的数据
		$cachekey_arr = array('member_name','store_id','member_avatar','member_qq','member_email','member_ww','member_goldnum','member_points',
							'available_predeposit','member_snsvisitnum','credit_arr','order_nopay','order_noreceiving','order_noeval','fan_count');
		if (false){
			foreach ($_cache as $k=>$v){
				$member_info[$k] = $v;
			}
		} else {
		    $model_order = Model('order');
		    $model_member = Model('member');
		    $member_info = $model_member->getMemberInfo(array('member_id'=>$_SESSION['member_id']));
			$member_info['order_nopay'] = $model_order->getOrderStateNewCount(array('buyer_id'=>$_SESSION['member_id']));
			$member_info['order_noreceiving'] = $model_order->getOrderStateSendCount(array('buyer_id'=>$_SESSION['member_id']));
			$member_info['order_noeval'] = $model_order->getOrderStateEvalCount(array('buyer_id'=>$_SESSION['member_id']));
			if (C('voucher_allow') == 1) {
		        $time_to = time();//当前日期
				$member_info['voucher'] = Model()->table('voucher')->where(array('voucher_owner_id'=> $_SESSION['member_id'],'voucher_state'=> 1,'voucher_start_date'=> array('elt',$time_to),'voucher_end_date'=> array('egt',$time_to)))->count();
			}
		}
		Tpl::output('member_info',$member_info);
		Tpl::output('header_menu_sign','snsindex');//默认选中顶部“买家首页”菜单
	}

    /**
     * 左侧导航
     * 菜单数组中child的下标要和其链接的act对应。否则面包屑不能正常显示
     * @return array
     */
    private function _getMenuList() {
		$model = Model('member');
		$isseller = $model->getfby_member_id($_SESSION['member_id'],'is_seller');
		$ismanager = $model->getfby_member_id($_SESSION['member_id'],'is_manager');     

	/*if( $isseller==1)
		{
	        $menu_list = array(
                'trade' => array('name' => L('member_control_trade_manage'), 'child' => array(
						//'member_order_salearea'		=> array('name' => L('member_control_team_manage'), 'url'=>urlShop('member_order_salearea', 'index')), //取消前端显示团队区域的信息-分销系统升级
						'member_excqcode'	=> array('name' => L('member_control_sale_manage'), 'url'=>urlShop('member_information', 'excqcode')),//收益中心
                        'member_order'      => array('name' => L('member_control_orderlist'), 'url'=>urlShop('member_order', 'index')),
                        'member_vr_order'   => array('name' => L('member_control_vr_order'), 'url'=>urlShop('member_vr_order','index')),
                        'member_favorites'  => array('name' => L('member_control_favorites'), 'url'=>urlShop('member_favorites', 'fglist')),
                        'member_evaluate'   => array('name' => L('member_control_evaluate'), 'url'=>urlShop('member_evaluate', 'list')),
                        'predeposit'        => array('name' => L('member_control_predeposit'), 'url'=>urlShop('predeposit', 'pd_log_list')),
                        'member_points'     => array('name' => L('member_control_points'), 'url'=>urlShop('member_points', 'index')),
					    'member_flea'       => array('name' => L('member_control_flea'), 'url'=>urlShop('member_flea', 'index')),
						'voucher_lingqu'    => array('name' => L('member_control_lingqu_voucher'), 'url'=>urlShop('pointvoucher', 'index')),
                        'member_voucher'    => array('name' => L('member_control_voucher'), 'url'=>urlShop('member_voucher', 'index'))	
                )),
                'serv' => array('name' => L('member_control_client_services'), 'child' => array(
                        'member_refund'     => array('name' => L('member_control_refund'), 'url'=>urlShop('member_refund', 'index')),
                        'member_complain'   => array('name' => L('member_control_complain'), 'url'=>urlShop('member_complain', 'index')),
                        'member_consult'    => array('name' => L('member_control_consult'), 'url'=>urlShop('member_consult', 'my_consult')),
                        'member_inform'     => array('name' => L('member_control_inform'), 'url'=>urlShop('member_inform', 'index')),
                        'member_mallconsult'=> array('name' => L('member_control_mallconsult'), 'url'=>urlShop('member_mallconsult', 'index'))
                )),
                'info' => array('name' => L('member_control_info_manage'), 'child' => array(
                        'member_information'=> array('name' => L('member_control_infomation'), 'url'=>urlShop('member_information', 'member')),
                        'member_security'   => array('name' => L('member_control_security'), 'url'=>urlShop('member_security', 'index')),
						'member'   			=> array('name' => L('member_control_identity'), 'url'=>urlShop('member', 'add_identity')),
                        'member_address'    => array('name' => L('member_control_address'), 'url'=>urlShop('member_address', 'address')),
                        'member_message'    => array('name' => L('member_control_message'), 'url'=>urlShop('member_message', 'message')),
                        'member_snsfriend'  => array('name' => L('member_control_snsfriend'), 'url'=>urlShop('member_snsfriend', 'find')),
						'member_invite'  	=> array('name' => L('member_control_invite'), 'url'=>urlShop('invite', 'index')),
                        'member_goodsbrowse'=> array('name' => L('member_control_goodsbrowse'), 'url'=>urlShop('member_goodsbrowse', 'list')),
                        'member_connect'    => array('name' => L('member_control_connect'), 'url'=>urlShop('member_connect', 'qqbind')),
                        'member_sharemanage'=> array('name' => L('member_control_sharemanage'), 'url'=>urlShop('member_sharemanage', 'index'))
                )),
                'app' => array('name' => L('member_control_app_manage'), 'child' => array(
                        'sns'               => array('name' => L('member_control_sns'), 'url'=>urlShop('member_snshome', 'index')),
                        'cms'               => array('name' => L('member_control_cms'), 'url'=>urlCMS('member_article', 'article_list')),
                        'circle'            => array('name' => L('member_control_circle'), 'url'=>urlCircle('p_center', 'index')),
                        'microshop'         => array('name' => L('member_control_microshop'), 'url'=>urlMicroshop('home', 'index', array('member_id' => $_SESSION['member_id'])))
                ))
		);
	}
	else{*/
		$menu_list = array(
                'trade' => array('name' => L('member_control_trade_manage'), 'child' => array(
                        'member_order'      => array('name' => L('member_control_orderlist'), 'url'=>urlShop('member_order', 'index')),
                        // 'member_vr_order'   => array('name' => L('member_control_vr_order'), 'url'=>urlShop('member_vr_order','index')),
                        'member_favorites'  => array('name' => L('member_control_favorites'), 'url'=>urlShop('member_favorites', 'fglist')),
                        // 'member_evaluate'   => array('name' => L('member_control_evaluate'), 'url'=>urlShop('member_evaluate', 'list')),
                        'predeposit'        => array('name' => L('member_control_predeposit'), 'url'=>urlShop('predeposit', 'pd_log_list')),
                        'member_points'     => array('name' => L('member_control_points'), 'url'=>urlShop('member_points', 'index')),
					    // 'member_flea'     	=> array('name' => L('member_control_flea'), 'url'=>urlShop('member_flea', 'index')),
						// 'voucher_lingqu'    => array('name' => L('member_control_lingqu_voucher'), 'url'=>urlShop('pointvoucher', 'index')),
                        'member_voucher'    => array('name' => L('member_control_voucher'), 'url'=>urlShop('member_voucher', 'index'))			    	
                )),
                'serv' => array('name' => L('member_control_client_services'), 'child' => array(
                        'member_refund'     => array('name' => L('member_control_refund'), 'url'=>urlShop('member_refund', 'index')),
                        'member_complain'   => array('name' => L('member_control_complain'), 'url'=>urlShop('member_complain', 'index')),
                        'member_consult'    => array('name' => L('member_control_consult'), 'url'=>urlShop('member_consult', 'my_consult')),
                        'member_inform'     => array('name' => L('member_control_inform'), 'url'=>urlShop('member_inform', 'index')),
                        'member_mallconsult'=> array('name' => L('member_control_mallconsult'), 'url'=>urlShop('member_mallconsult', 'index'))
                )),
                'info' => array('name' => L('member_control_info_manage'), 'child' => array(
                        'member_information'=> array('name' => L('member_control_infomation'), 'url'=>urlShop('member_information', 'member')),
                        'member_security'   => array('name' => L('member_control_security'), 'url'=>urlShop('member_security', 'index')),
						'member'   			=> array('name' => L('member_control_identity'), 'url'=>urlShop('member', 'add_identity')),
                        'member_address'    => array('name' => L('member_control_address'), 'url'=>urlShop('member_address', 'address')),
                        'member_message'    => array('name' => L('member_control_message'), 'url'=>urlShop('member_message', 'message')),
                        'member_snsfriend'  => array('name' => L('member_control_snsfriend'), 'url'=>urlShop('member_snsfriend', 'find')),
						'member_invite'  	=> array('name' => L('member_control_invite'), 'url'=>urlShop('invite', 'index')),
                        'member_goodsbrowse'=> array('name' => L('member_control_goodsbrowse'), 'url'=>urlShop('member_goodsbrowse', 'list'))/*,
                        'member_connect'    => array('name' => L('member_control_connect'), 'url'=>urlShop('member_connect', 'qqbind')),
                        'member_sharemanage'=> array('name' => L('member_control_sharemanage'), 'url'=>urlShop('member_sharemanage', 'index'))
                )),
                'app' => array('name' => L('member_control_app_manage'), 'child' => array(
                        'sns'               => array('name' => L('member_control_sns'), 'url'=>urlShop('member_snshome', 'index')),
                        'cms'               => array('name' => L('member_control_cms'), 'url'=>urlCMS('member_article', 'article_list')),
                        'circle'            => array('name' => L('member_control_circle'), 'url'=>urlCircle('p_center', 'index')),
                        'microshop'         => array('name' => L('member_control_microshop'), 'url'=>urlMicroshop('home', 'index', array('member_id' => $_SESSION['member_id'])))*/
                ))
		);
	// }
        return $menu_list;
    }
}

/********************************** SNS control父类 **********************************************/

class BaseSNSControl extends Control {
    protected $relation = 0;//浏览者与主人的关系：0 表示游客 1 表示一般普通会员 2表示朋友 3表示自己4表示已关注主人
    protected $master_id = 0; //主人编号
    const MAX_RECORDNUM = 20;//允许插入新记录的最大条数
    protected $master_info;

    public function __construct(){

        Tpl::setDir('sns');

        Tpl::setLayout('sns_layout');

        Language::read('common,sns_layout');

        //验证会员及与主人关系
        $this->check_relation();

        //查询会员信息
        $this->getMemberAndGradeInfo(false);

        $this->master_info = $this->get_member_info();
        Tpl::output('master_info',$this->master_info);

        //添加访问记录
        $this->add_visit();

        //我的关注
        $this->my_attention();

        //获取设置
        $this->get_setting();

        //允许插入新记录的最大条数
        Tpl::output('max_recordnum',self::MAX_RECORDNUM);

        $this->showCartCount();

        Tpl::output('nav_list', rkcache('nav',true));
    }

    /**
     * 格式化时间
     * @param string $time时间戳
     */
    protected function formatDate($time){
        $handle_date = @date('Y-m-d',$time);//需要格式化的时间
        $reference_date = @date('Y-m-d',time());//参照时间
        $handle_date_time = strtotime($handle_date);//需要格式化的时间戳
        $reference_date_time = strtotime($reference_date);//参照时间戳
        if ($reference_date_time == $handle_date_time){
            $timetext = @date('H:i',$time);//今天访问的显示具体的时间点
        }elseif (($reference_date_time-$handle_date_time)==60*60*24){
            $timetext = Language::get('sns_yesterday');
        }elseif ($reference_date_time-$handle_date_time==60*60*48){
            $timetext = Language::get('sns_beforeyesterday');
        }else {
            $month_text = Language::get('nc_month');
            $day_text = Language::get('nc_day');
            $timetext = @date("m{$month_text}d{$day_text}",$time);
        }
        return $timetext;
    }

    /**
     * 会员信息
     *
     * @return array
     */
    public function get_member_info() {
        if($this->master_id <= 0){
            showMessage(L('wrong_argument'), '', '', 'error');
        }
        $model = Model();
        $member_info = Model('member')->getMemberInfoByID($this->master_id);
        if(empty($member_info)){
            showMessage(L('wrong_argument'), 'index.php?gct=member_snshome', '', 'error');
        }
        //粉丝数
        $fan_count = $model->table('sns_friend')->where(array('friend_tomid'=>$this->master_id))->count();
        $member_info['fan_count'] = $fan_count;
        //关注数
        $attention_count = $model->table('sns_friend')->where(array('friend_frommid'=>$this->master_id))->count();
        $member_info['attention_count'] = $attention_count;
        //兴趣标签
        $mtag_list = $model->table('sns_membertag,sns_mtagmember')->field('mtag_name')->on('sns_membertag.mtag_id = sns_mtagmember.mtag_id')->join('inner')->where(array('sns_mtagmember.member_id'=>$this->master_id))->select();
        $tagname_array = array();
        if(!empty($mtag_list)){
            foreach ($mtag_list as $val){
                $tagname_array[] = $val['mtag_name'];
            }
        }
        $member_info['tagname'] = $tagname_array;
        return $member_info;
    }

    /**
     * 访客信息
     */
    protected function get_visitor(){
        $model = Model();
        //查询谁来看过我
        $visitme_list = $model->table('sns_visitor')->where(array('v_ownermid'=>$this->master_id))->limit(9)->order('v_addtime desc')->select();
        if (!empty($visitme_list)){
            foreach ($visitme_list as $k=>$v){
                $v['adddate_text'] = $this->formatDate($v['v_addtime']);
                $v['addtime_text'] = @date('H:i',$v['v_addtime']);
                $visitme_list[$k] = $v;
            }
        }
        Tpl::output('visitme_list',$visitme_list);
        if($this->relation == 3){	// 主人自己才有我访问过的人
            //查询我访问过的人
            $visitother_list = $model->table('sns_visitor')->where(array('v_mid'=>$this->master_id))->limit(9)->order('v_addtime desc')->select();
            if (!empty($visitother_list)){
                foreach ($visitother_list as $k=>$v){
                    $v['adddate_text'] = $this->formatDate($v['v_addtime']);
                    $visitother_list[$k] = $v;
                }
            }
            Tpl::output('visitother_list',$visitother_list);
        }
    }

    /**
     * 验证会员及主人关系
     */
    private function check_relation(){
        $model = Model();
        //验证主人会员编号
        $this->master_id = intval($_GET['mid']);
        if ($this->master_id <= 0){
            if ($_SESSION['is_login'] == 1){
                $this->master_id = $_SESSION['member_id'];
            }else {
                @header("location: index.php?gct=login&ref_url=".urlencode('index.php?gct=member_snshome'));
            }
        }
        Tpl::output('master_id', $this->master_id);

        $model = Model();

        //判断浏览者与主人的关系
        if ($_SESSION['is_login'] == '1'){
            if ($this->master_id == $_SESSION['member_id']){//主人自己
                $this->relation = 3;
            }else{
                $this->relation = 1;
                //查询好友表
                $friend_arr = $model->table('sns_friend')->where(array('friend_frommid'=>$_SESSION['member_id'],'friend_tomid'=>$this->master_id))->find();
                if (!empty($friend_arr) && $friend_arr['friend_followstate'] == 2){
                    $this->relation = 2;
                }elseif($friend_arr['friend_followstate'] == 1){
                    $this->relation = 4;
                }
            }
        }
        Tpl::output('relation',$this->relation);
    }

    /**
     * 增加访问记录
     */
    private function add_visit(){
        $model = Model();
        //记录访客
        if ($_SESSION['is_login'] == '1' && $this->relation != 3){
            //访客为会员且不是空间主人则添加访客记录
            $visitor_info = $model->table('member')->find($_SESSION['member_id']);
            if (!empty($visitor_info)){
                //查询访客记录是否存在
                $existevisitor_info = $model->table('sns_visitor')->where(array('v_ownermid'=>$this->master_id, 'v_mid'=>$visitor_info['member_id']))->find();
                if (!empty($existevisitor_info)){//访问记录存在则更新访问时间
                    $update_arr = array();
                    $update_arr['v_addtime'] = time();
                    $model->table('sns_visitor')->update(array('v_id'=>$existevisitor_info['v_id'], 'v_addtime'=>time()));
                }else {//添加新访问记录
                    $insert_arr = array();
                    $insert_arr['v_mid']			= $visitor_info['member_id'];
                    $insert_arr['v_mname']			= $visitor_info['member_name'];
                    $insert_arr['v_mavatar']		= $visitor_info['member_avatar'];
                    $insert_arr['v_ownermid']		= $this->master_info['member_id'];
                    $insert_arr['v_ownermname']		= $this->master_info['member_name'];
                    $insert_arr['v_ownermavatar']	= $this->master_info['member_avatar'];
                    $insert_arr['v_addtime']		= time();
                    $model->table('sns_visitor')->insert($insert_arr);
                }
            }
        }

        //增加主人访问次数
        $cookie_str = cookie('visitor');
        $cookie_arr = array();
        $is_increase = false;
        if (empty($cookie_str)){
            //cookie不存在则直接增加访问次数
            $is_increase = true;
        }else{
            //cookie存在但是为空则直接增加访问次数
            $cookie_arr = explode('_',$cookie_str);
            if(!in_array($this->master_id,$cookie_arr)){
                $is_increase = true;
            }
        }
        if ($is_increase == true){
            //增加访问次数
            $model->table('member')->update(array('member_id'=>$this->master_id, 'member_snsvisitnum'=>array('exp', 'member_snsvisitnum+1')));
            //设置cookie，24小时之内不再累加
            $cookie_arr[] = $this->master_id;
            setNcCookie('visitor',implode('_',$cookie_arr),24*3600);//保存24小时
        }
    }
    //我的关注
    private function my_attention(){
        if(intval($_SESSION['member_id']) >0){
            $my_attention = Model()->table('sns_friend')->where(array('friend_frommid'=>$_SESSION['member_id']))->order('friend_addtime desc')->limit(4)->select();
            Tpl::output('my_attention', $my_attention);
        }
    }

    /**
     * 获取设置信息
     */
    private function get_setting(){
        $m_setting = Model()->table('sns_setting')->find($this->master_id);
        Tpl::output('skin_style', (!empty($m_setting['setting_skin'])?$m_setting['setting_skin']:'skin_01'));
    }
    /**
     * 留言板
     */
    protected function sns_messageboard(){
        $model = Model();
        $where = array();
        $where['from_member_id']	= array('neq',0);
        $where['to_member_id']		= $this->master_id;
        $where['message_state']		= array('neq',2);
        $where['message_parent_id']	= 0;
        $where['message_type']		= 2;
        $message_list = $model->table('message')->where($where)->order('message_id desc')->limit(10)->select();
        if(!empty($message_list)){
            $pmsg_id = array();
            foreach ($message_list as $key=>$val){
                $pmsg_id[]	= $val['message_id'];
                $message_list[$key]['message_time'] = $this->formatDate($val['message_time']);
            }
            $where = array();
            $where['message_parent_id'] = array('in',$pmsg_id);
            $rmessage_array = $model->table('message')->where($where)->select();
            $rmessage_list	= array();
            if(!empty($rmessage_array)){
                foreach ($rmessage_array as $key=>$val){
                    $val['message_time'] = $this->formatDate($val['message_time']);
                    $rmessage_list[$val['message_parent_id']][] = $val;
                }
                foreach ($rmessage_list as $key=>$val){
                    $rmessage_list[$key]	 = array_slice($val, -3, 3);
                }
            }
            Tpl::output('rmessage_list', $rmessage_list);
        }
        Tpl::output('message_list', $message_list);
    }
}
/********************************** 店铺 control父类 **********************************************/



class BaseStoreControl extends Control {

    protected $store_info;
    protected $store_decoration_only = false;

    public function __construct(){

        Language::read('common,store_layout,store_show_store_index');

        if(!C('site_status')) halt(C('closed_reason'));

        //输出头部的公用信息
        $this->showLayout();
        Tpl::setDir('store');
        Tpl::setLayout('store_layout');

        //输出会员信息
        $this->getMemberAndGradeInfo(false);

        $store_id = intval($_GET['store_id']);
        if($store_id <= 0) {
            showMessage(L('nc_store_close'), '', '', 'error');
        }

        $model_store = Model('store');
        $store_info = $model_store->getStoreOnlineInfoByID($store_id);
        if(empty($store_info)) {
            showMessage(L('nc_store_close'), '', '', 'error');
        } else {
            $this->store_info = $store_info;
        }
        if($store_info['store_decoration_switch'] > 0 & $store_info['store_decoration_only'] == 1) {
            $this->store_decoration_only = true;
        }

        //店铺装修
        $this->outputStoreDecoration($store_info['store_decoration_switch'], $store_id);

        $this->outputStoreInfo($this->store_info);
        $this->getStoreNavigation($store_id);
        $this->outputSeoInfo($this->store_info);
    }

    /**
     * 输出店铺装修
     */
    protected function outputStoreDecoration($decoration_id, $store_id) {
        if($decoration_id > 0 ) {
            $model_store_decoration = Model('store_decoration');

            $decoration_info = $model_store_decoration->getStoreDecorationInfoDetail($decoration_id, $store_id);
            if($decoration_info) {
                $decoration_background_style = $model_store_decoration->getDecorationBackgroundStyle($decoration_info['decoration_setting']);
                Tpl::output('decoration_background_style', $decoration_background_style);
                Tpl::output('decoration_nav', $decoration_info['decoration_nav']);
                Tpl::output('decoration_banner', $decoration_info['decoration_banner']);

                $html_file = BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'decoration'.DS.'html'.DS.md5($store_id).'.html';
                if(is_file($html_file)) {
                    Tpl::output('decoration_file', $html_file);
                }

            }

            Tpl::output('store_theme', 'default');
        } else {
            Tpl::output('store_theme', $this->store_info['store_theme']);
        }
    }

    /**
     * 检查店铺开启状态
     *
     * @param int $store_id 店铺编号
     * @param string $msg 警告信息
     */
    protected function outputStoreInfo($store_info){
        if(!$this->store_decoration_only) {
            $model_store = Model('store');
            $model_seller = Model('seller');

            //店铺分类
            $goodsclass_model = Model('store_goods_class');
            $goods_class_list = $goodsclass_model->getShowTreeList($store_info['store_id']);
            Tpl::output('goods_class_list', $goods_class_list);

            //热销排行
            $hot_sales = $model_store->getHotSalesList($store_info['store_id'], 5);
            Tpl::output('hot_sales', $hot_sales);

            //收藏排行
            $hot_collect = $model_store->getHotCollectList($store_info['store_id'], 5);
            Tpl::output('hot_collect', $hot_collect);
        }

        Tpl::output('store_info', $store_info);
        Tpl::output('page_title', $store_info['store_name']);
    }

    protected function getStoreNavigation($store_id) {
        $model_store_navigation = Model('store_navigation');
        $store_navigation_list = $model_store_navigation->getStoreNavigationList(array('sn_store_id' => $store_id));
        Tpl::output('store_navigation_list', $store_navigation_list);
    }

    protected function outputSeoInfo($store_info) {
        $seo_param = array();
        $seo_param['shopname'] = $store_info['store_name'];
        $seo_param['key']  = $store_info['store_keywords'];
        $seo_param['description'] = $store_info['store_description'];
        Model('seo')->type('gcshop')->param($seo_param)->show();
    }

}

class BaseGoodsControl extends BaseStoreControl {

    public function __construct(){

        Language::read('common,store_layout');

        if(!C('site_status')) halt(C('closed_reason'));

        Tpl::setDir('store');
        Tpl::setLayout('home_layout');

        //输出头部的公用信息
        $this->showLayout();
        //输出会员信息
        $this->getMemberAndGradeInfo(false);
    }

    protected function getStoreInfo($store_id) {
        $model_store = Model('store');
        $store_info = $model_store->getStoreOnlineInfoByID($store_id);
        if(empty($store_info)) {
            showMessage(L('nc_store_close'), '', '', 'error');
        }

        $this->outputStoreInfo($store_info);
    }
}
/**
 * 店铺 control新父类
 *
 */
class BaseSellerControl extends Control {

    //店铺信息
    protected $store_info = array();
    //店铺等级
    protected $store_grade = array();

    public function __construct(){
        Language::read('common,store_layout,member_layout');
        if(!C('site_status')) halt(C('closed_reason'));
        Tpl::setDir('seller');
        Tpl::setLayout('seller_layout');

        //输出会员信息
        $this->getMemberAndGradeInfo(false);

        Tpl::output('nav_list', rkcache('nav',true));
        if ($_GET['gct'] !== 'seller_login') {

            if(empty($_SESSION['seller_id'])) {
                @header('location: index.php?gct=seller_login&gp=show_login');die;
            }

            // 验证店铺是否存在
            $model_store = Model('store');
            $this->store_info = $model_store->getStoreInfoByID($_SESSION['store_id']);
            if (empty($this->store_info)) {
                @header('location: index.php?gct=seller_login&gp=show_login');die;
            }

            // 店铺关闭标志
            if (intval($this->store_info['store_state']) === 0) {
                Tpl::output('store_closed', true);
                Tpl::output('store_close_info', $this->store_info['store_close_info']);
            }

            // 店铺等级
            if (checkPlatformStore()) {
                $this->store_grade = array(
                    'sg_id' => '0',
                    'sg_name' => '自营店铺专属等级',
                    'sg_goods_limit' => '0',
                    'sg_album_limit' => '0',
                    'sg_space_limit' => '999999999',
                    'sg_template_number' => '6',
                    // see also store_settingControl.themeOp()
                    // 'sg_template' => 'default|style1|style2|style3|style4|style5',
                    'sg_price' => '0.00',
                    'sg_description' => '',
                    'sg_function' => 'editor_multimedia',
                    'sg_sort' => '0',
                );
            } else {
                $store_grade = rkcache('store_grade', true);
                $this->store_grade = $store_grade[$this->store_info['grade_id']];
            }

            if ($_SESSION['seller_is_admin'] !== 1 && $_GET['gct'] !== 'seller_center' && $_GET['gct'] !== 'seller_logout') {
                if (!in_array($_GET['gct'], $_SESSION['seller_limits'])) {
                    showMessage('没有权限', '', '', 'error');
                }
            }

            // 卖家菜单
            Tpl::output('menu', $_SESSION['seller_menu']);
            // 当前菜单
            $current_menu = $this->_getCurrentMenu($_SESSION['seller_function_list']);
            Tpl::output('current_menu', $current_menu);
            // 左侧菜单
            if($_GET['gct'] == 'seller_center') {
                if(!empty($_SESSION['seller_quicklink'])) {
                    $left_menu = array();
                    foreach ($_SESSION['seller_quicklink'] as $value) {
                        $left_menu[] = $_SESSION['seller_function_list'][$value];
                    }
                }
            } else {
                $left_menu = $_SESSION['seller_menu'][$current_menu['model']]['child'];
            }
            Tpl::output('left_menu', $left_menu);
            Tpl::output('seller_quicklink', $_SESSION['seller_quicklink']);

            $this->checkStoreMsg();
			
			//获取发货仓
			$model_region = Model('region');
			$regions = $model_region->getRegions();
			Tpl::output('regions', $regions);

			//获取模版
			$model_transport = Model('transport');
			$transports = $model_transport->getTransports();
			Tpl::output('transports', $transports);
        }
    }

    /**
     * 记录卖家日志
     *
     * @param $content 日志内容
     * @param $state 1成功 0失败
     */
    protected function recordSellerLog($content = '', $state = 1){
        $seller_info = array();
        $seller_info['log_content'] = $content;
        $seller_info['log_time'] = TIMESTAMP;
        $seller_info['log_seller_id'] = $_SESSION['seller_id'];
        $seller_info['log_seller_name'] = $_SESSION['seller_name'];
        $seller_info['log_store_id'] = $_SESSION['store_id'];
        $seller_info['log_seller_ip'] = getIp();
        $seller_info['log_url'] = $_GET['gct'].'&'.$_GET['gp'];
        $seller_info['log_state'] = $state;
        $model_seller_log = Model('seller_log');
        $model_seller_log->addSellerLog($seller_info);
    }

    /**
     * 记录店铺费用
     *
     * @param $cost_price 费用金额
     * @param $cost_remark 费用备注
     */
    protected function recordStoreCost($cost_price, $cost_remark) {
        // 平台店铺不记录店铺费用
        if (checkPlatformStore()) {
            return false;
        }
        $model_store_cost = Model('store_cost');
        $param = array();
        $param['cost_store_id'] = $_SESSION['store_id'];
        $param['cost_seller_id'] = $_SESSION['seller_id'];
        $param['cost_price'] = $cost_price;
        $param['cost_remark'] = $cost_remark;
        $param['cost_state'] = 0;
        $param['cost_time'] = TIMESTAMP;
        $model_store_cost->addStoreCost($param);

        // 发送店铺消息
        $param = array();
        $param['code'] = 'store_cost';
        $param['store_id'] = $_SESSION['store_id'];
        $param['param'] = array(
            'price' => $cost_price,
            'seller_name' => $_SESSION['seller_name'],
            'remark' => $cost_remark
        );

        QueueClient::push('sendStoreMsg', $param);
    }

    protected function getSellerMenuList($is_admin, $limits) {
        $seller_menu = array();
        if (intval($is_admin) !== 1) {
            $menu_list = $this->_getMenuList();
            foreach ($menu_list as $key => $value) {
                foreach ($value['child'] as $child_key => $child_value) {
                    if (!in_array($child_value['gct'], $limits)) {
                        unset($menu_list[$key]['child'][$child_key]);
                    }
                }

                if(count($menu_list[$key]['child']) > 0) {
                    $seller_menu[$key] = $menu_list[$key];
                }
            }
        } else {
            $seller_menu = $this->_getMenuList();
        }
        $seller_function_list = $this->_getSellerFunctionList($seller_menu);
        return array('seller_menu' => $seller_menu, 'seller_function_list' => $seller_function_list);
    }

    private function _getCurrentMenu($seller_function_list) {
        $current_menu = $seller_function_list[$_GET['gct']];
        if(empty($current_menu)) {
            $current_menu = array(
                'model' => 'index',
                'model_name' => L('homepage')
            );
        }
        return $current_menu;
    }


    private function _getMenuList() {
        $menu_list = array(
            'goods' => array('name' => L('store_center_goods'), 'child' => array(
                array('name' => L('store_center_goods_add'), 'gct'=>'store_goods_add', 'gp'=>'index'),
				array('name' => L('store_center_taobao_import'), 'gct'=>'taobao_import', 'gp'=>'index'),
                array('name' => L('store_center_saling_goods'), 'gct'=>'store_goods_online', 'gp'=>'index'),
                array('name' => L('store_center_warehouse_goods'), 'gct'=>'store_goods_offline', 'gp'=>'index'),
                array('name' => L('store_center_plate'), 'gct'=>'store_plate', 'gp'=>'index'),
                array('name' => L('store_center_spec'), 'gct' => 'store_spec', 'gp' => 'index'),
                array('name' => L('store_center_album'), 'gct'=>'store_album', 'gp'=>'album_cate'),
            )),
            'order' => array('name' => L('store_center_order_transport'), 'child' => array(
                array('name' => L('store_center_goods_order'), 'gct'=>'store_order', 'gp'=>'index'),
				array('name' => L('store_center_vr_order'), 'gct'=>'store_vr_order', 'gp'=>'index'),
                array('name' => L('store_center_deliver'), 'gct'=>'store_deliver', 'gp'=>'index'),
                array('name' => L('store_center_deliver_set'), 'gct'=>'store_deliver_set', 'gp'=>'daddress_list'),
                array('name' => L('store_center_waybill'), 'gct'=>'store_waybill', 'gp'=>'waybill_manage'),
                array('name' => L('store_center_evaluate_manage'), 'gct'=>'store_evaluate', 'gp'=>'list'),
                array('name' => L('store_center_transport_tools'), 'gct'=>'store_transport', 'gp'=>'index'),
            )),
            'promotion' => array('name' => L('store_center_promotion'), 'child' => array(
                array('name' => L('store_center_groupbuy_manage'), 'gct'=>'store_groupbuy', 'gp'=>'index'),
                array('name' => L('store_center_promotion_xianshi'), 'gct'=>'store_promotion_xianshi', 'gp'=>'xianshi_list'),
                array('name' => L('store_center_promotion_mansong'), 'gct'=>'store_promotion_mansong', 'gp'=>'mansong_list'),
                array('name' => L('store_center_promotion_bundling'), 'gct'=>'store_promotion_bundling', 'gp'=>'bundling_list'),
                array('name' => L('store_center_promotion_booth'), 'gct' => 'store_promotion_booth', 'gp' => 'booth_goods_list'),
                array('name' => L('store_center_voucher_manage'), 'gct'=>'store_voucher', 'gp'=>'templatelist'),
                array('name' => L('store_center_activity_manage'), 'gct'=>'store_activity', 'gp'=>'store_activity'),
            )),
            'store' => array('name' => L('store_center_store'), 'child' => array(
                array('name' => L('store_center_store_setting'), 'gct'=>'store_setting', 'gp'=>'store_setting'),
                array('name' => L('store_center_store_decoration'), 'gct'=>'store_decoration', 'gp'=>'decoration_setting'),
                array('name' => L('store_center_store_navigation'), 'gct'=>'store_navigation', 'gp'=>'navigation_list'),
                array('name' => L('store_center_store_sns'), 'gct'=>'store_sns', 'gp'=>'index'),
                array('name' => L('store_center_store_information'), 'gct'=>'store_info', 'gp'=>'bind_class'),
                array('name' => L('store_center_store_class'), 'gct'=>'store_goods_class', 'gp'=>'index'),
                array('name' => L('store_center_offline_store'), 'gct'=>'store_live', 'gp'=>'store_live'),
                array('name' => L('store_center_brand_apply'), 'gct'=>'store_brand', 'gp'=>'brand_list'),
            )),
            'consult' => array('name' => L('store_center_customer_services'), 'child' => array(
                array('name' => L('store_center_consult_mamage'), 'gct'=>'store_consult', 'gp'=>'consult_list'),
                array('name' => L('store_center_complain_manage'), 'gct'=>'store_complain', 'gp'=>'list'),
                array('name' => L('store_center_refund_recoding1'), 'gct'=>'store_refund', 'gp'=>'index'),
                array('name' => L('store_center_refund_recoding2'), 'gct'=>'store_return', 'gp'=>'index'),
            )),
            'statistics' => array('name' => L('store_center_statistical_settlement'), 'child' => array(
                array('name' => L('store_center_statistics_general'), 'gct'=>'statistics_general', 'gp'=>'general'),
                array('name' => L('store_center_statistics_goods'), 'gct'=>'statistics_goods', 'gp'=>'goodslist'),
                array('name' => L('store_center_statistics_sale'), 'gct'=>'statistics_sale', 'gp'=>'sale'),
                array('name' => L('store_center_statistics_industry'), 'gct'=>'statistics_industry', 'gp'=>'hot'),
                array('name' => L('store_center_statistics_flow'), 'gct'=>'statistics_flow', 'gp'=>'storeflow'),
                array('name' => L('store_center_store_bill'), 'gct'=>'store_bill', 'gp'=>'index'),
                array('name' => L('store_center_store_vr_bill'), 'gct'=>'store_vr_bill', 'gp'=>'index'),
            )),
            'message' => array('name' => L('store_center_services_message'), 'child' => array(
                array('name' => L('store_center_services_setting'), 'gct'=>'store_callcenter', 'gp'=>'index'),
                array('name' => L('store_center_system_message'), 'gct'=>'store_msg', 'gp'=>'index'),
                array('name' => L('store_center_chat_query'), 'gct'=>'store_im', 'gp'=>'index'),
            )),
            'account' => array('name' => L('store_center_store_account'), 'child' => array(
                array('name' => L('store_center_account_list'), 'gct'=>'store_account', 'gp'=>'account_list'),
                array('name' => L('store_center_account_group'), 'gct'=>'store_account_group', 'gp'=>'group_list'),
                array('name' => L('store_center_account_log'), 'gct'=>'seller_log', 'gp'=>'log_list'),
                array('name' => L('store_center_store_cost'), 'gct'=>'store_cost', 'gp'=>'cost_list'),
            ))
        );
        return $menu_list;
    }


    private function _getSellerFunctionList($menu_list) {
        $format_menu = array();
        foreach ($menu_list as $key => $menu_value) {
            foreach ($menu_value['child'] as $submenu_value) {
                $format_menu[$submenu_value['gct']] = array(
                    'model' => $key,
                    'model_name' => $menu_value['name'],
                    'name' => $submenu_value['name'],
                    'gct' => $submenu_value['gct'],
                    'gp' => $submenu_value['gp'],
                );
            }
        }
        return $format_menu;
    }

    /**
     * 自动发布店铺动态
     *
     * @param array $data 相关数据
     * @param string $type 类型 'new','coupon','xianshi','mansong','bundling','groupbuy'
     *            所需字段
     *            new       goods表'             goods_id,store_id,goods_name,goods_image,goods_price,goods_transfee_charge,goods_freight
     *            xianshi   p_xianshi_goods表'   goods_id,store_id,goods_name,goods_image,goods_price,goods_freight,xianshi_price
     *            mansong   p_mansong表'         mansong_name,start_time,end_time,store_id
     *            bundling  p_bundling表'        bl_id,bl_name,bl_img,bl_discount_price,bl_freight_choose,bl_freight,store_id
     *            groupbuy  goods_group表'       group_id,group_name,goods_id,goods_price,groupbuy_price,group_pic,rebate,start_time,end_time
     *            coupon在后台发布
     */
    public function storeAutoShare($data, $type) {
        $param = array(
                3 => 'new',
                4 => 'coupon',
                5 => 'xianshi',
                6 => 'mansong',
                7 => 'bundling',
                8 => 'groupbuy'
            );
        $param_flip = array_flip($param);
        if (!in_array($type, $param) || empty($data)) {
            return false;
        }

        $auto_setting = Model('store_sns_setting')->getStoreSnsSettingInfo(array('sauto_storeid' => $_SESSION ['store_id']));
        $auto_sign = false; // 自动发布开启标志

        if ($auto_setting['sauto_' . $type] == 1) {
            $auto_sign = true;
            if (CHARSET == 'GBK') {
                foreach ((array)$data as $k => $v) {
                    $data[$k] = Language::getUTF8($v);
                }
            }
            $goodsdata = addslashes(json_encode($data));
            if ($auto_setting['sauto_' . $type . 'title'] != '') {
                $title = $auto_setting['sauto_' . $type . 'title'];
            } else {
                $auto_title = 'nc_store_auto_share_' . $type . rand(1, 5);
                $title = Language::get($auto_title);
            }
        }
        if ($auto_sign) {
            // 插入数据
            $stracelog_array = array();
            $stracelog_array['strace_storeid'] = $this->store_info['store_id'];
            $stracelog_array['strace_storename'] = $this->store_info['store_name'];
            $stracelog_array['strace_storelogo'] = empty($this->store_info['store_avatar']) ? '' : $this->store_info['store_avatar'];
            $stracelog_array['strace_title'] = $title;
            $stracelog_array['strace_content'] = '';
            $stracelog_array['strace_time'] = TIMESTAMP;
            $stracelog_array['strace_type'] = $param_flip[$type];
            $stracelog_array['strace_goodsdata'] = $goodsdata;
            Model('store_sns_tracelog')->saveStoreSnsTracelog($stracelog_array);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 商家消息数量
     */
    private function checkStoreMsg() {//判断cookie是否存在
        $cookie_name = 'storemsgnewnum'.$_SESSION['seller_id'];
        if (cookie($cookie_name) != null && intval(cookie($cookie_name)) >=0){
            $countnum = intval(cookie($cookie_name));
        }else {
            $where = array();
            $where['store_id'] = $_SESSION['store_id'];
            $where['sm_readids'] = array('notlike', '%,'.$_SESSION['seller_id'].',%');
            if ($_SESSION['seller_smt_limits'] !== false) {
                $where['smt_code'] = array('in', $_SESSION['seller_smt_limits']);
            }
            $countnum = Model('store_msg')->getStoreMsgCount($where);
            setNcCookie($cookie_name,intval($countnum),2*3600);//保存2小时
        }
        Tpl::output('store_msg_num',$countnum);
    }

}

class BaseStoreSnsControl extends Control {
    const MAX_RECORDNUM = 20;	// 允许插入新记录的最大次数，sns页面该常量是一样的。
    public function __construct(){
        Language::read('common,store_layout');
        Tpl::output('max_recordnum', self::MAX_RECORDNUM);
        Tpl::setDir('store');
        Tpl::setLayout('store_layout');
        // 自定义导航条
        $this->getStoreNavigation();
        //输出头部的公用信息
        $this->showLayout();
        //查询会员信息
        $this->getMemberAndGradeInfo(false);
    }

    // 自定义导航条
    protected function getStoreNavigation() {
        $model_store_navigation = Model('store_navigation');
        $store_navigation_list = $model_store_navigation->getStoreNavigationList(array('sn_store_id' => $_GET['sid']));
        Tpl::output('store_navigation_list', $store_navigation_list);
    }

    protected function getStoreInfo($store_id) {
        //得到店铺等级信息
        $store_info = Model('store')->getStoreInfoByID($store_id);
        if (empty($store_info)) {
            showMessage(Language::get('store_sns_store_not_exists'),'','html','error');
        }
        //处理地区信息
        $area_array	= array();
        $area_array = explode("\t",$store_info["area_info"]);
        $map_city	= Language::get('store_sns_city');
        $city	= '';
        if(strpos($area_array[0], $map_city) !== false){
            $city	= $area_array[0];
        }else {
            $city	= $area_array[1];
        }
        $store_info['city'] = $city;

        Tpl::output('store_theme', $store_info['store_theme']);
        Tpl::output('store_info', $store_info);
    }
}

/**
 * 积分中心control父类
 */
class BasePointShopControl extends Control {
    protected $member_info;
    public function __construct(){
        Language::read('common,home_layout');
        //输出头部的公用信息
        $this->showLayout();
        //输出会员信息
        $this->member_info = $this->getMemberAndGradeInfo(true);
        Tpl::output('member_info',$this->member_info);


        Tpl::setDir('home');
        Tpl::setLayout('home_layout');

        if ($_GET['column'] && strtoupper(CHARSET) == 'GBK'){
            $_GET = Language::getGBK($_GET);
        }
        if(!C('site_status')) halt(C('closed_reason'));

        //判断系统是否开启积分和积分中心功能
        if (C('points_isuse') != 1 || C('pointshop_isuse') != 1){
            showMessage(Language::get('pointshop_unavailable'),urlShop('index','index'),'html','error');
        }
        Tpl::output('index_sign','pointshop');
    }
    /**
     * 获得积分中心会员信息包括会员名、ID、会员头像、会员等级、经验值、等级进度、积分、已领代金券、已兑换礼品、礼品购物车
     */
    public function pointshopMInfo($is_return = false){
        if($_SESSION['is_login'] == '1'){
            $model_member = Model('member');
            if (!$this->member_info){
                //查询会员信息
                $member_infotmp = $model_member->getMemberInfoByID($_SESSION['member_id']);
            } else {
                $member_infotmp = $this->member_info;
            }
            $member_infotmp['member_exppoints'] = intval($member_infotmp['member_exppoints']);

            //当前登录会员等级信息
            $membergrade_info = $model_member->getOneMemberGrade($member_infotmp['member_exppoints'],true);
            $member_info = array_merge($member_infotmp,$membergrade_info);
            Tpl::output('member_info',$member_info);

            //查询已兑换并可以使用的代金券数量
            $model_voucher = Model('voucher');
            $vouchercount = $model_voucher->getCurrentAvailableVoucherCount($_SESSION['member_id']);
            Tpl::output('vouchercount',$vouchercount);

            //购物车兑换商品数
            $pointcart_count = Model('pointcart')->countPointCart($_SESSION['member_id']);
            Tpl::output('pointcart_count',$pointcart_count);

            //查询已兑换商品数(未取消订单)
            $pointordercount = Model('pointorder')->getMemberPointsOrderGoodsCount($_SESSION['member_id']);
            Tpl::output('pointordercount',$pointordercount);
            if ($is_return){
            	return array('member_info'=>$member_info,'vouchercount'=>$vouchercount,'pointcart_count'=>$pointcart_count,'pointordercount'=>$pointordercount);
            }
        }
    }
}


/********************************** 前台control父类 **********************************************/
class BaseApiControl extends Control {

    public function __construct(){
        //输出头部的公用信息
        $this->showLayout();
        //输出会员信息
        $this->getMemberAndGradeInfo(false);

        Language::read('common,home_layout');

        Tpl::setDir('api');

        Tpl::setLayout('api_layout');

        if ($_GET['column'] && strtoupper(CHARSET) == 'GBK'){
            $_GET = Language::getGBK($_GET);
        }
        if(!C('site_status')) halt(C('closed_reason'));
    }

}