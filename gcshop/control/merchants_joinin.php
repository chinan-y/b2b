<?php
/**
 * 招商加盟
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');
class merchants_joininControl extends BaseHomeControl{
    public function __construct() {
		Language::read('merchants_join');
        parent::__construct();
		 
    }

    /**
     * 显示招商加盟留言联系
     */
    public function merchants_indexOp() {
        Tpl::showpage('merchants_join.add');
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
        $model_league = Model('league');
        $result = $model_league->addMerchConsult($condition);
        if ($result) {
            showDialog(L('nc_common_op_sub_succeed'), 'reload', 'succ');
        } else {
            showDialog(L('nc_common_op_fail'), 'reload');
        }
    }

}
