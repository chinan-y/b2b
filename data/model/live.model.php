<?php
/**
 *  直播管理
 *
 *
 *
 *
 
 */


defined('GcWebShop') or exit('Access Invalid!');

class liveModel extends Model{
    public function __construct(){
        parent::__construct('live');
    }
//member table
	//获得用户信息
	public function get_memberinfo($member_id){
		$model=Model();
		$result=$model->table('member')->where(array('member_id'=>$member_id))->field('islive,member_name,member_mobile')->find();
		return $result;
	}
	//申请主播
	function applyFoeLive($where,$update){
		$model=Model();
		$result=$model->table('member')->where($where)->update($update);
		return $result;
	}
	
	
//live table
	//推荐
	public function live_recommended_list($condition=array(),$limit=null){
		$model=Model();
		$result=$model->table('live')->where($condition)->order('live_fouce_num desc')->limit($limit)->select();
		return $result;
	}
	//主播信息
	function getLiveInfo($where){
		$model=Model();
		$result=$model->table('live')->where($where)->find();
		return $result;
		
	}
	//修改主播信息
	function updataLive($where,$updata){
		$model=Model();
		$result=$model->table('live')->where($where)->update($updata);
		return $result;
	}
	//新增主播信息
	function insertLive($insert){
		$model=Model();
		$result=$model->table('live')->insert($insert);
		return $result;
	}
	//根据条件查询主播
	function search($keyword){
		$model=Model();
		$condition=array();
		$pex='/^[1]\d{4}$/';
		if($word=preg_match($pex,$keyword)){
			$condition['live_id']=$keyword;
			$result=$model->table('live')->where($condition)->select();
		}else{
			$str='select * from `33hao_live` where live_id like "%%'.$keyword.'%%" or live_name like "%%'.$keyword.'%%" or live_title like "%%'.$keyword.'%%"';
			$result=$model->table('live')->query($str);
			
		}
		
		return $result;
	}
	//点播视频列表
	public function onDemandList($condition,$order,$page){
		$result = Model()->table('live_demand')->where($condition)->order($order)->page($page)->select();
		return $result;
	}
	
	//上传点播视频内容
	public function addDemand($insert){
		$result = Model()->table('live_demand')->insert($insert);
		return $result;
	}
	
	//删除点播视频
	public function delDemand($condition){
		$result = Model()->table('live_demand')->where($condition)->delete();
		return $result;
	}
	
//table classification
	
	//查询一级分类信息
	public function live_class($condition=array()){
		$model=Model();
		$result=$model->table('live_classification')->where($condition)->select();
		return $result;
	}
	
	//查询单个分类信息
	public function select_one_live_class($where){
		$model=Model();
		$result=$model->table('live_classification')->where($where)->find();
		return $result;
	}
	//修改一级分类单个信息
	function updata_class_one($where,$updata){
		$model=Model();
		$result=$model->table('live_classification')->where($where)->update($updata);
		return $result;
		
	}
	//插入新分类
	function insert_class_one($insert){
		$model=Model();
		$result=$model->table('live_classification')->insert($insert);
		return $result;
		
	}
	//删除一级分类
	function delet_one_class($where){
		$model=Model();
		$result=$model->table('live_classification')->where($where)->delete();
		return $result;
	}
	
//table    live_class_evea
	//查询单个二级分类
	function selectTwoClass($condition){
		$model=Model();
		$result=$model->table('live_class_evea')->where($condition)->find();
		return $result;
		
	}
	//删除二级分类
	function delet_two_class($where){
		$model=Model();
		$result=$model->table('live_class_evea')->where($where)->delete();
		return $result;
	}
	//查询二级分类信息
	function live_class_evea($condition=array()){
		$model=Model();
		$result=$model->table('live_class_evea')->where($condition)->limit(1000)->select();
		return $result;
	}
	//修改二级分类信息
	function updataTwoClass($condition,$updata){
		$model=Model();
		$result=$model->table('live_class_evea')->where($condition)->update($updata);
		return $result;
		
	}
	//增加二级分类
	function insert_class_two($insert){
		$model=Model();
		$result=$model->table('live_class_evea')->insert($insert);
		return $result;
	}
	//根据分类查询在线主播信息
	function live_online(){
		$model=Model();
		$live_class_evea=$this->live_class_evea();
		$class_live=array();
		foreach($live_class_evea as $key=>$value){
			
			$result=$model->table('live')->where(array('class_evea_id'=>$value['class_evea_id'],'live_state'=>1))->order('live_fouce_num desc')->limit(6)->select();
			$class_live[$value['class_evea_id']]=$result;
		} 
		// header("Content-type: text/html; charset=utf-8");echo '<pre>';print_r($class_live);die;
		return $class_live;
	}
	
	
	
// table liveprovisionalapproval	
	//查询临时信息表是否存在
	function getInfo_pro($member_id){
		$model=Model();
		$result=$model->table('liveprovisionalapproval')->where(array('member_id'=>$member_id))->find();
		return $result;
		
		
	}
	//查询临时信息表的全部信息
	function getModifly($where=array()){
		$model=Model();
		$result=$model->table('liveprovisionalapproval')->where($where)->select();
		// var_dump($result);die;
		return $result;
		
	}
	//删除临时信息表的内容
	function delet_pro($where){
		$model=Model();
		$result=$model->table('liveprovisionalapproval')->where($where)->delete();
		return $result;
	}
//table 
	//查询订阅用户是否订阅
    function subscribe($member_id,$array){
		$model=Model();
		if($array['demand']){
			$member=$model->table('live_demand')->where(array('demand_id'=>$array['demand']))->find();
			$live=$this->getLiveInfo(array('live_member_id'=>$member['demand_member_id']));
			$live_id=$live['live_id'];
		}elseif($array['live_id']){
			$live_id=$array['live_id'];
		}
		$result=$model->table('live_subscribe')->where(array('subscribe_member_id'=>$member_id,'subscribe_live_id'=>$live_id))->find();
		return $result;
	}

	function subscribeNum($array){
		$model=Model();
		if($array['demand']){
			$member_id=$model->table('live_demand')->where(array('demand_id'=>$array['demand']))->find();
			$live=$this->getLiveInfo(array('live_member_id'=>$member_id['demand_member_id']));
			$live_id=$live['live_id'];
		}elseif($array['live_id']){
			$live_id=$array['live_id'];
		}
		$result=$model->table('live_subscribe')->where(array('subscribe_live_id'=>$live_id))->count();
		return $result;
	}
	function insertSub($insert){		
		$model=Model();
		$result=$model->table('live_subscribe')->insert($insert);
		return $result;
	}
	function get_subscribe($member_id){		
		$model=Model();
		$result=$model->table('live_subscribe')->where(array('subscribe_member_id'=>$member_id))->select();
		return $result;
	}	
	function live_subscribe($member_id){
		$model=Model();
		$result=array();
		$sub=$this->get_subscribe($member_id);
		foreach($sub as $key=>$value){
			$l=$model->table('live')->where(array('live_id'=>$value['subscribe_live_id']))->field('live_name,live_id,class_evea_id')->find();
			$result[$key]['live_name']=$l['live_name'];
			$result[$key]['live_id']=$l['live_id'];
			$result[$key]['class_evea_id']=$l['class_evea_id'];
		}
		return $result;
	}
	//点播视频列表
	public function selectDemand($limit){
		$model=Model();
		$result=$model->table('live_demand')->limit($limit)->select();
		return $result;
	}
	public function getDemand($demand){
		$model=Model();
		$result=$model->table('live_demand')->where(array('demand_id'=>$demand))->find();
		
		$result['live_header_image']=$result['demand_img_url'];
		$result['live_title']=$result['demand_title'];
		$result['class_evea_name']='精彩回顾';
		$result['live_name']=$result['demand_title'];
		$result['player_address_flv']=$result['demand_url'];
		if($result['demand_member_id']){
			$name=$model->table('member')->where(array('member_id'=>$result['demand_member_id']))->field('member_name')->find();
		}elseif($result['demand_admin_id']){
			$name=$model->table('member')->where(array('member_id'=>$result['demand_member_id']))->field('member_name')->find();
		}
		$result['live_name']=$name['member_name'];
		$result['live_id']  =$this->getLiveInfo(array('live_member_id'=>$result['demand_member_id']))['live_id'];
		
		return $result;
		
	}
	public function getRecomend($array){
		$model=Model();
		if($array['demand']){
			$demand=$model->table('live_demand')->where(array('demand_id'=>$array['demand']))->field('demand_member_id')->find();
			$sql='select * from 33hao_live_demand where demand_member_id='.$demand['demand_member_id'].' and demand_id<>'.$array['demand'];
			$recomend=$model->query($sql);
		}elseif($array['live_id']){
			$liveInfo=$this->getLiveInfo(array('live_id'=>$array['live_id']));
			$recomend=$model->table('live_demand')->where(array('demand_member_id'=>$liveInfo['live_member_id']))->select();
		}
		return $recomend;
		
	}
	
}