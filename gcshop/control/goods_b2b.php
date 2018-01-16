<?php
/**
 * b2b专题
 *
 * @copyright  
 * @link       
 */
defined('GcWebShop') or exit('Access Invalid!');
class goods_b2bControl extends BaseHomeControl{

    public function __construct() {
        parent::__construct();
        Tpl::output('index_sign','special');
    }

    public function indexOp() {
        $this->special_listOp();
    }

    /**
     * 专题列表
     */
    public function special_listOp() {
        $conition = array();
        $conition['special_state'] = 2;
        $model_special = Model('cms_special');
        $special_list = $model_special->getShopList($conition, 10, 'special_id desc');
        Tpl::output('show_page', $model_special->showpage(2));
        Tpl::output('special_list', $special_list);

		//分类导航
		$nav_link = array(
			0=>array(
				'title'=>Language::get('homepage'),
				'link'=>SHOP_SITE_URL
			),
			1=>array(
				'title'=>'专题'
			)
		);
		Tpl::output('nav_link_list', $nav_link);

        Tpl::showpage('special_list');
    }

    /**
     * 商品专题详细页
     */
    public function goods_b2bOp() {
        $model_class = Model();
	    if (in_array($_GET['key'],array('1','2','3','4'))) {
            $sequence = $_GET['order'] == '1' ? 'asc' : 'desc';
            $order = str_replace(array('1','2','3','4'), array('goods_id','goods_salenum','goods_click','goods_promotion_price'), $_GET['key']);
            $order .= ' '.$sequence;
         }
		 if($_GET['type']){
		$condition['is_own_shop']=$_GET['type'];
		 }elseif($_GET['gift']){
		 $condition['have_gift']=$_GET['gift'];
		 }else{
		   $condition['store_from']=6; 
		 }
		$condition['goods_state']=1;
		$goods_list=$model_class->table('goods')->where($condition)->order($order)->limit($limit)->page('30')->select();
		Tpl::output('show_page', $model_class->showpage(2));
		Tpl::output('show_page1', $model_class->showpage(1));
        Tpl::output('goods_list',$goods_list);//商品分类
        Tpl::showpage('goods_b2b');

    }

}