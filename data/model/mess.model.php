<?php
/**
 * 海关接口管理	by ming
 */
defined('GcWebShop') or exit('Access Invalid!');

class messModel extends Model{
	public function __construct(){
		parent::__construct('setting');
	}
	 /**
	 * 读取配置
	 */ 
	 public function getMessSetting($name){
		 $param = array();
		 $param['table'] = 'setting';
		 $param['where'] = "name='".$name."'";
		 $result = Db::select($param);
		 if(is_array($result) and is_array($result[0])){
			return $result[0];
		}
		return false;
	 }
	 
	 /**
	 * 读取配置列表
	 */ 
	public function getMessList(){
		$param = array();
		$param['table'] = 'setting';
		$result = Db::select($param);
		/**
		 * 整理
		 */
		if (is_array($result)){
			$mess_list = array();
			foreach ($result as $k => $v){
				$mess_list[$v['name']] = $v['value'];
			}
		}
		return $mess_list;
	}
	 
	 /**
	 * 更新配置
	 */ 
	public function updateMessSetting($param){
		if (empty($param)){
			return false;
		}

		if (is_array($param)){
			foreach ($param as $k => $v){
				$tmp = array();
				$specialkeys_arr = array('statistics_code');
				$tmp['value'] = (in_array($k,$specialkeys_arr) ? htmlentities($v,ENT_QUOTES) : $v);
				$where = " name = '". $k ."'";
				$result = Db::update('setting',$tmp,$where);
				if ($result !== true){
					return $result;
				}
			}
			dkcache('setting');
			return true;
		}else {
			return false;
		}
	}
	
	
	 
}
