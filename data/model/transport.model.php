<?php
/**
 * 运费模板
 *
 * 
 *
 *
 
 */
defined('GcWebShop') or exit('Access Invalid!');

class transportModel extends Model {

	protected $cachedData;

	public function __construct(){
		parent::__construct();
	}

	/**
	 * 增加运费模板
	 *
	 * @param unknown_type $data
	 * @return unknown
	 */
	public function addTransport($data){
		dkcache('transport');
	    return $this->table('transport')->insert($data);
	}

	/**
	 * 增加各地区详细运费设置
	 *
	 * @param unknown_type $data
	 * @return unknown
	 */
	public function addExtend($data){
		dkcache('transport');
	    return $this->table('transport_extend')->insertAll($data);
	}

	/**
	 * 取得一条运费模板信息
	 *
	 * @return unknown
	 */
	public function getTransportInfo($condition){
	    return $this->table('transport')->where($condition)->find();
	}

	/**
	 * 取得一条运费模板扩展信息
	 *
	 * @return unknown
	 */
	public function getExtendInfo($condition){
	    return $this->table('transport_extend')->where($condition)->select();
	}

	/**
	 * 删除运费模板
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
	public function delTansport($condition){
		dkcache('transport');
	    try {
            $this->beginTransaction();
            $delete = $this->table('transport')->where($condition)->delete();
            if ($delete) {
                $delete = $this->table('transport_extend')->where(array('transport_id'=>$condition['id']))->delete();
            }
            if (!$delete) throw new Exception();
            $this->commit();
        }catch (Exception $e){
            $model->rollback();
            return false;
        }
        return true;
	}

	/**
	 * 删除运费模板扩展信息
	 *
	 * @param unknown_type $transport_id
	 * @return unknown
	 */
	public function delExtend($transport_id){
		dkcache('transport');
		return $this->table('transport_extend')->where(array('transport_id'=>$transport_id))->delete();
	}

	/**
	 * 取得运费模板列表
	 *
	 * @param unknown_type $condition
	 * @param unknown_type $page
	 * @param unknown_type $order
	 * @return unknown
	 */
	public function getTransportList($condition=array(), $pagesize = '', $order = 'id desc'){
		return $this->table('transport')->where($condition)->order($order)->page($pagesize)->select();
	}

	/**
	 * 取得扩展信息列表
	 *
	 * @param unknown_type $condition
	 * @param unknown_type $order
	 * @return unknown
	 */
	public function getExtendList($condition=array(), $order='is_default'){
		return $this->table('transport_extend')->where($condition)->order($order)->select();
	}

	public function transUpdate($data){
		dkcache('transport');
	    return $this->table('transport')->where($condition)->update($data);
	}

	/**
	 * 检测运费模板是否正在被使用
	 *
	 */
	public function isUsing($id){
        if (!is_numeric($id)) return false;
        $goods_info = $this->table('goods')->where(array('transport_id'=>$id))->field('goods_id')->find();
        return $goods_info ? true : false;
	}
    
	/**
	 * 计算某地区某运费模板ID下的商品总运费，如果运费模板不存在或，按免运费处理
	 *
	 * @param int $transport_id
	 * @param int $buy_num
	 * @param int $area_id
	 * @return number/boolean
	 */
    public function calc_transport($transport_id, $buy_num, $area_id) {
		if (empty($transport_id) || empty($buy_num) || empty($area_id)) return 10;
		$transportInfo = $this->getTransportInfo(array('id'=>$transport_id));
		$extend_list = $this->getExtendList(array('transport_id'=>$transport_id));
		if (empty($extend_list)) {
		    return 0;
		} else {
			if($transportInfo['type']==0){
				return $this->calc_weight($area_id,$buy_num,$extend_list);
			}else{
				return $this->calc_unit($area_id,$buy_num,$extend_list);
			}
		}
    }

	/**
	 * 计算某个具单元的运费
	 *
	 * @param 配送地区 $area_id
	 * @param 购买数量 $num
	 * @param 货品重量 $weight
	 * @param 运费模板内容 $extend
	 * @return number 总运费
	 */
	private function calc_unit($area_id, $num, $extend){
		if (!empty($extend) && is_array($extend)){
			foreach ($extend as $v) {
				if (strpos($v['area_id'],",".$area_id.",") !== false){
					if ($num <= $v['snum']){
						//在首件数量范围内
						$calc_total = $v['sprice'];
					}else{
						//超出首件数量范围，需要计算续件
						$calc_total = sprintf('%.2f',($v['sprice'] + ceil(($num-$v['snum'])/$v['xnum'])*$v['xprice']));
					}
				}
				if ($v['is_default']==1){
					if ($num <= $v['snum']){
						//在首件数量范围内
						$calc_default_total = $v['sprice'];
					}else{
						//超出首件数量范围，需要计算续件
						$calc_default_total = sprintf('%.2f',($v['sprice'] + ceil(($num-$v['snum'])/$v['xnum'])*$v['xprice']));
					}
				}
			}
			//如果运费模板中没有指定该地区，取默认运费
			if (!isset($calc_total) && isset($calc_default_total)){
				$calc_total = $calc_default_total;
			}
		}
		return $calc_total;
	}
		private function calc_weight($area_id, $weight, $extend){
		if (!empty($extend) && is_array($extend)){
			foreach ($extend as $v) {
				if (strpos($v['area_id'],",".$area_id.",") !== false){
					if ($weight <= $v['snum']){
						//在首重范围内
						$weight_total = $v['sprice'];
					}else{
						//超出首重需要计算续重
						$weight_total = sprintf('%.2f',($v['sprice'] + ceil(($weight-$v['snum'])/$v['xnum'])*$v['xprice']));
					}
				}
				if ($v['is_default']==1){
					if ($weight <= $v['snum']){
						//在首件数量范围内
						$weight_default_total = $v['sprice'];
					}else{
						//超出首件数量范围，需要计算续件
						$weight_default_total = sprintf('%.2f',($v['sprice'] + ceil(($weight-$v['snum'])/$v['xnum'])*$v['xprice']));
					}
				}
			}
			//如果运费模板中没有指定该地区，取默认运费
			if (!isset($weight_total) && isset($weight_default_total)){
				$weight_total = $weight_default_total;
			}
		}
		return $weight_total;	
	}  

	/**
	 * 获取运费模版缓存
	 *
	 * @return array
	 */
	public function getTransports() {
		return $this->getCache();
	}

	/**
	 * 获取运费模版数组 格式如下
	 *
	 * @return array
	 */
	protected function getCache() {
		// 对象属性中有数据则返回
		if ($this->cachedData !== null)
			return $this->cachedData;

		// 缓存中有数据则返回
		if ($data = rkcache('transport')) {
			$this->cachedData = $data;
			return $data;
		}

		// 查库
		$data = array();
		$transport_all_array = $this->table('transport')->limit(false)->select();
		foreach ((array) $transport_all_array as $a) {
			$data[$a['id']] = $a;
		}

		wkcache('transport', $data);
		$this->cachedData = $data;

		return $data;
	}
}
?>
