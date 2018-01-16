<?php
/**
 * 海关接口管理 订单	by ming
 */
defined('GcWebShop') or exit('Access Invalid!');

class mess_orderModel extends Model{
	public function __construct(){
		parent::__construct('mess_order_info');
	}
	
    /**
     * 读取订单列表
     * @param unknown $condition
     * @param string $pagesize
     * @param string $order
     */
	 public function getMessOrderList($condition, $page='', $field='*'){
		 $result = $this->field($field)->where($condition)->page($page)->select();
		 return $result;
	 }
	 
    /**
     * 取得单条信息
     * @param unknown $condition
     */
    public function getMessOrderInfo($condition) {
        $result = $this->where($condition)->find();
        return $result;
    }
	 
	/**
     * 原始单号查找订单
     */
    public function getMessOrderInfoByOrderNo($ORIGINAL_ORDER_NO, $fields = '*') {
            $mess_order_info = $this->getMessOrderInfo(array('ORIGINAL_ORDER_NO'=>$ORIGINAL_ORDER_NO),'*',true);
        return $mess_order_info;
    }
	 
    /**
     * 增加
     * @param unknown $data
     * @return boolean
     */
    public function addMessOrder($data){
        return $this->insert($data);	
    }

    /**
     * 删除
     * @param unknown $condition
     */
    public function delMessOrder($condition){
        return $this->where($condition)->delete();
    }
    /**
     * 更新
     * @param unknown $data
     * @param unknown $condition
     */
    public function editMessOrder($update, $condition){
        return $this->where($condition)->update($update);
    }
	
}
