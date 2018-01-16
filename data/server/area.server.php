<?php
/**
 * 地区逻辑层处理
 */
defined('GcWebShop') or exit('Access Invalid!');
class areaServer {

	/**
	 * [取得地区内容]
	 * @author fulijun
	 * @dateTime 2016-09-26T17:56:21+0800
	 * @param    [Int]                   $province_id [省ID]
	 * @param    [Int]                   $city_id     [市ID]
	 * @param    [Int]                   $area_id     [区ID]
	 * @return   [String]                             [返回省市区名拼接成的字符串，如：江苏扬州市高邮市]
	 */
	public function getAreainfo($province_id,$city_id,$area_id){
		if(!$province_id || !$city_id ||!$area_id){
			return false;
		}
		$fields  = "area_id,area_name,area_parent_id";
		//取得省
		$prowhere = array(
			'area_id'=>$province_id
		);
		$province = Model()->table('area')->where($prowhere)->field($fields)->find();
		//取得市
		$citywhere = array(
			'area_id'=>$city_id
		);
		$city = Model()->table('area')->where($citywhere)->field($fields)->find();
		//取得区
		$areawhere = array(
			'area_id'=>$area_id
		);
		$area = Model()->table('area')->where($areawhere)->field($fields)->find();
		if(!$province || !$city || !$area){
			return false;
		}
		return $province['area_name'].' '.$city['area_name'].' '.$area['area_name'];		
	}
}