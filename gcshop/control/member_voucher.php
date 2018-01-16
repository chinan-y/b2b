<?php
/**
 * 代金券
 *
 *
 *
 ***/


defined('GcWebShop') or exit('Access Invalid!');
class member_voucherControl extends BaseMemberControl{
	public function __construct() {
		parent::__construct();
		Language::read('member_layout,member_voucher');
		//判断系统是否开启代金券功能
		if (intval(C('voucher_allow')) !== 1){
			showMessage(Language::get('member_voucher_unavailable'),urlShop('member', 'home'),'html','error');
		}
	}
	/*
	 * 默认显示代金券模版列表
	 */
	public function indexOp() {
        $this->voucher_listOp() ;
    }

	/*
	 * 获取代金券模版详细信息
	 */
    public function voucher_listOp(){

        $member_info = $this->member_info;
        $member_info['security_level'] = Model('member')->getMemberSecurityLevel($member_info);
		$model = Model('voucher');
        $list = $model->getMemberVoucherList($_SESSION['member_id'], $_GET['select_detail_state'], 10);

		//取已经使用过并且未有voucher_order_id的代金券的订单ID
		$used_voucher_code = array();
		$voucher_order = array();
		if (!empty($list)) {
		    foreach ($list as $k =>$v) {
				$member_info = Model('member')->getMemberInfo(array('member_id'=> $v['voucher_give_id']), 'member_name');
				$list[$k]['member_give_name'] = $member_info['member_name'];
		        if ($v['voucher_state'] == 2 && empty($v['voucher_order_id'])) {
		            $used_voucher_code[] = $v['voucher_code'];
		        }
		    }
		}
        if (!empty($used_voucher_code)) {
            $order_list = Model('order')->getOrderCommonList(array('voucher_code'=>array('in',$used_voucher_code)),'order_id,voucher_code');
            if (!empty($order_list)) {
                foreach ($order_list as $v) {
                    $voucher_order[$v['voucher_code']] = $v['order_id'];
                    $model->editVoucher(array('voucher_order_id'=>$v['order_id']),array('voucher_code'=>$v['voucher_code']));
                }
            }
        }

		Tpl::output('list', $list);
		Tpl::output('voucherstate_arr', $model->getVoucherStateArray());
        Tpl::output('show_page',$model->showpage(2)) ;
        $this->profile_menu('voucher_list');
        Tpl::showpage('member_voucher.list');
    }
	
	/**
	 * 赠送代金券
	 */
	public function voucher_giveOp(){
		if (!$_SESSION['is_login']) {
			showMessage(L('wrong_argument'), '', '', 'error');
		}
		Tpl::output('voucher_id', $_GET['voucher_id']);
		Tpl::showpage('voucher_give.submit', 'null_layout');
    }
	
	/**
	 * 赠送他人代金券表单
	 */
	public function voucher_give_submitOp() {
		$mobile = $_POST['mobile'];
		//获取被赠送人的信息
		$member_info = Model('member')->getMemberInfo(array('member_mobile'=> $mobile), 'member_id,member_name');
		if(!$member_info){
			showDialog(L('voucher_template_nomember'));
		}else{
			$data = array();
			$data['voucher_owner_id'] = $member_info['member_id'];
			$data['voucher_owner_name'] = $member_info['member_name'];
			$data['voucher_give_id'] = $_SESSION['member_id'];
			
			$re = Model('voucher')->editVoucher($data, array('voucher_id'=>$_GET['voucher_id']));
		}
		if ($re) {
			$sms = new Sms();
			$sms->send($mobile, '用户（'.$_SESSION['member_name'].'）赠送了您一张代金券，请在个人中心-我的代金券下查看。');
			showDialog(L('voucher_template_succeed'), 'reload', 'succ');
			
		} else {
			showDialog(L('voucher_template_defeated'));
		}
	}


	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @param array 	$array		附加菜单
	 * @return
	 */
	private function profile_menu($menu_key='') {
		$menu_array = array(
			1=>array('menu_key'=>'voucher_list','menu_name'=>Language::get('nc_myvoucher'),'menu_url'=>'index.php?gct=member_voucher&gp=voucher_list'),
		);
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
    }

}
