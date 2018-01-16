<?php
/**
 * 用户消息模板模型
 *
 
 */
defined('GcWebShop') or exit('Access Invalid!');
class member_msg_tplModel extends Model{
    public function __construct() {
        parent::__construct('member_msg_tpl');
    }
    
    /**
     * 用户消息模板列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getMemberMsgTplList($condition, $field = '*', $page = 0, $order = 'mmt_type asc,mmt_code asc') {
        return $this->field($field)->where($condition)->order($order)->page($page)->select();
    }
    
    /**
     * 用户消息模板详细信息
     * @param array $condition
     * @param string $field
     */
    public function getMemberMsgTplInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }
    
    /**
     * 编辑用户消息模板
     * @param array $condition
     * @param unknown $update
     */
    public function editMemberMsgTpl($condition, $update) {
        return $this->where($condition)->update($update);
    }

    /**
     * 编译消息模版字符串
     * @param array $tpl
     * @param array $data
     */
    public function compileMemberMsgTpl($tpl, $data) {
        $site_name = '光彩全球';
        $site_url = 'https://www.qqbsmall.com';
        $now_time = time();
        $now_time_formart = date('Y-m-d H:i:s');
        extract($data);
        eval('$tpl = "'.$tpl.'";');
        return $tpl;
    }
}