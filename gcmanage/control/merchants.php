<?php
/**
 * 加盟咨询管理界面
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');

class merchantsControl extends SystemControl{
	public function __construct(){
		Language::read('merchants');
		parent::__construct();
	}

    /**
     * 加盟咨询管理
     */
    public function indexOp(){
        $condition = array();
        if(chksubmit()){
            $member_name = trim($_GET['member_name']);
            if($member_name != ''){
                $condition['league_name'] = array('like', '%' . $member_name . '%');
                Tpl::output('league_name', $member_name);
            }
        }
        $model_merchants = Model('league');
        $consult_list = $model_merchants->getMerchConsultList($condition,'*', 10);
        Tpl::output('show_page',$model_merchants->showpage());
        Tpl::output('consult_list',$consult_list);

        // 回复状态
        $state = array('0'=>'未回复', '1'=>'已回复');
        Tpl::output('state', $state);

        Tpl::showpage('merchants_join');
    }

    /**
     * 查看加盟咨询
     */
    public function merchants_replyOp() {
        $model_merchants = Model('league');
        $league_id = intval($_GET['id']);
		$update['is_reply'] = 1;
		$update['league_reply_time'] = TIMESTAMP;
		$update['admin_id'] = $this->admin_info['id'];
		$update['admin_name'] = $this->admin_info['name'];
		$result = $model_merchants->editMerchConsult(array('league_id' => $league_id), $update);
        $id = intval($_GET['id']);
        if ($id <= 0) {
            showMessage(L('param_error'));
        }

        $merchants_info = $model_merchants->getMerchConsultInfo(array('league_id' => $id));
        Tpl::output('merchants_info', $merchants_info);
        Tpl::showpage('merchants.reply');
    }

    /**
     * 删除加盟咨询
     */
    public function del_consultOp(){
        $id = $_GET['id'];
        if($id <= 0){
            showMessage(Language::get('nc_common_del_fail'));
        }
        $result = Model('league')->delMerchConsult(array('league_id' => $id));
        if($result){
            $this->log('删除加盟咨询'.'[ID:'.$id.']',null);
            showMessage(Language::get('nc_common_del_succ'));
        }else{
            showMessage(Language::get('nc_common_del_fail'));
        }
    }

}
