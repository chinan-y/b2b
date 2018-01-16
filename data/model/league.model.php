<?php
/**
 * 招商加盟咨询管理
 *
 * 
 */
defined('GcWebShop') or exit('Access Invalid!');
class leagueModel extends Model{
    public function __construct() {
        parent::__construct('league');
    }
	
	  /**
     * 咨询数量
     * 
     * @param array $condition
     * @param string $field
     * @param string $order
     * @return array
     */
    public function getMerchConsultCount($condition) {
        return $this->where($condition)->count();
    }
    
    /**
     * 咨询列表
     * 
     * @param array $condition
     * @param string $field
     * @param string $order
     * @return array
     */
    public function getMerchConsultList($condition, $field = '*', $page = 0, $order = 'league_id desc') {
        return $this->where($condition)->field($field)->order($order)->page($page)->select();
    }
	
    /**
     * 添加咨询
     * @param array $insert
     * @return int
     */
    public function addMerchConsult($insert) {
        $insert['league_addtime'] = TIMESTAMP;
        return $this->insert($insert);
    }
	
	/**
     * 编辑回复咨询
     * @param array $condition
     * @param array $update
     * @return boolean
     */
    public function editMerchConsult($condition, $update) {
        return $this->where($condition)->update($update);
    }
	
	/**
     * 单条咨询
     * 
     * @param unknown $condition
     * @param string $field
     */
    public function getMerchConsultInfo($condition, $field = '*') {
        return $this->where($condition)->field($field)->find();
    }
	
	/**
     * 删除咨询
     * 
     * @param array $condition
     * @return boolean
     */
    public function delMerchConsult($condition) {
        return $this->where($condition)->delete();
    }
   
}



