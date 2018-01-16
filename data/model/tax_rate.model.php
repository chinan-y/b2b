<?php
/**
 * 跨境电商综合税模型		
 *
 */
defined('GcWebShop') or exit('Access Invalid!');
class tax_rateModel extends Model {

    public function __construct(){
        parent::__construct('tax_rate');
    }

	   /**
     * 读取列表
     * @param unknown $condition
     * @param string $pagesize
     * @param string $order
     */
	 public function getHsTaxList($condition, $page='', $order, $field='*'){
		 $result = $this->field($field)->where($condition)->page($page)->order($order)->select();
		 return $result;
	 }
    /**
     * 详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getHsTaxInfo($condition, $field = '*') {
        return $this->table('tax_rate')->field($field)->where($condition)->find();
    }

    /**
     * 使用税号找查税率
     * @param varchar $tax_no
     * @return array
     */
    public function getRateByNo($tax_no, $fields = '*') {

		$tax_rate = $this->getPostTaxInfo(array('hs_code'=>$tax_no),'*');
		return $tax_rate;
    }
	
	/**
     * 插入税率表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addHsTaxRate($data) {
        return $this->table('tax_rate')->insert($data);
    }
	
	/**编辑税率表信息
	* @param unknown_type $data
	 * @param unknown_type $condition
	**/
	
	public function editHsTaxRate($data,$condition){
		return $this->table('tax_rate')->where($condition)->update($data);
	}
		

}
