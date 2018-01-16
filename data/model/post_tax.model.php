<?php
/**
 * 行邮税模型		By Ming
 *
 */
defined('GcWebShop') or exit('Access Invalid!');
class post_taxModel extends Model {

    public function __construct(){
        parent::__construct('post_tax');
    }

    /**
     * 详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getPostTaxInfo($condition, $field = '*') {
        return $this->table('post_tax')->field($field)->where($condition)->find();
    }

    /**
     * 使用税号找查税率
     * @param varchar $tax_no
     * @return array
     */
    public function getRateByNo($tax_no, $fields = '*') {

		$post_tax = $this->getPostTaxInfo(array('tax_no'=>$tax_no),'*');
		return $post_tax;
    }

}
