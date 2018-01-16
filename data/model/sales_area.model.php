<?php
/**
 * 销售区域模型
 */
 
defined('GcWebShop') or exit('Access Invalid!');

class sales_areaModel extends Model {

    public function __construct(){
        parent::__construct('sales_area');
    }

    /**
     * 获取销售区域列表
     * @param unknown $condition
     * @param string $pagesize
     * @param string $order
     */
    public function getSalesAreaList($condition = array(), $pagesize = '', $limit = '', $order = 'sa_sort asc,sa_id asc') {
        return $this->where($condition)->order($order)->page($pagesize)->limit($limit)->select();
    }
	
    /**
     * 获取未指定经理的区域列表
     * @param array $condition
     * @param string $field
     * @return array
     */
	public function getSalesAreaNoManagerList($condition, $field = '*', $page = 0) {
	$condition['sa_manager_id'] = 0;
	return $this->getSalesAreaList($condition, $field, $page);
	}

    /**
     * 取得单条信息
     * @param unknown $condition
     */
    public function getSalesAreaInfo($condition, $field = '*') {
        return $this->where($condition)->field($field)->find();
    }
	
	/**
     * ID查找销售区域
     * @param int $sa_id
     * @param string $field 需要取得的缓存键值, 例如：'*','sa_name,sa_manager'
     * @return array
     */
    public function getSalesAreaInfoByID($sa_id, $fields = '*') {
            $sales_area_info = $this->getSalesAreaInfo(array('sa_id'=>$sa_id),'*',true);
        return $sales_area_info;
    }

	/**
	 * 获取销售区域信息
	 */
	public function getSalesAreaIn($condition, $field='*'){
		return $this->where($condition)->field($field)->select();
	}
	
 
    /**
     * 删除销售区域
     * @param unknown $condition
     */
    public function delSalesArea($condition = array()) {
        return $this->where($condition)->delete();
    }

    /**
     * 增加销售区域
     * @param unknown $data
     * @return boolean
     */
    public function addSalesArea($data) {
        return $this->insert($data);
    }

    /**
     * 更新销售区域
     * @param unknown $data
     * @param unknown $condition
     */
    public function editSalesArea($data = array(),$condition = array()) {
        return $this->where($condition)->update($data);
    }
}