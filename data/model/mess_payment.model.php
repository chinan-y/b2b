<?php
/**
 * 海关接口管理 支付单	by ming
 */
defined('GcWebShop') or exit('Access Invalid!');

class mess_paymentModel extends Model{
	public function __construct(){
		parent::__construct('mess_payment_info');
	}
	
    /**
     * 读取支付单列表
     * @param unknown $condition
     * @param string $pagesize
     * @param string $payment
     */
	 public function getMessPaymentList($condition, $page='', $field='*'){
		 $result = $this->field($field)->where($condition)->page($page)->select();
		 return $result;
	 }
	 
    /**
     * 取得单条信息
     * @param unknown $condition
     */
    public function getMessPaymentInfo($condition) {
        $result = $this->where($condition)->find();
        return $result;
    }
	 
	/**
     * 支付单号查找
     */
    public function getMessPaymentInfoBypaymentNo($PAYMENT_NO, $fields = '*') {
            $mess_payment_info = $this->getMessPaymentInfo(array('PAYMENT_NO'=>$PAYMENT_NO),'*',true);
        return $mess_payment_info;
    }
	 
    /**
     * 增加
     * @param unknown $data
     * @return boolean
     */
    public function addMessPayment($data){
        return $this->insert($data);	
    }

    /**
     * 删除
     * @param unknown $condition
     */
    public function delMessPayment($condition){
        return $this->where($condition)->delete();
    }
    /**
     * 更新
     * @param unknown $data
     * @param unknown $condition
     */
    public function editMessPayment($update, $condition){
        return $this->where($condition)->update($update);
    }
	
}
