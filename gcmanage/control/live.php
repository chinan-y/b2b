<?php
/**
 * 直播管理
 *
 *
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');

class liveControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		// Language::read('adv');
	}

	/**
	 *
	 * 直播审核
	 */
	public function live_modiflyOp(){
		$model=Model('live');
		$result=$model->getModifly();
		if($result){
			TPL::output('modiflyInfo',$result);
		}
		TPL::showpage('live_modifly');
	}
	public function LKOp(){
		$liveMb=$_GET['liveMb'];
		$model=Model('live');
		$result=$model->getModifly(array('mobile'=>$liveMb));
		TPL::output('one_modiflyInfo',$result);
		TPL::showpage('people');
		
	}
	public function successOp(){
		$where_dele=array();
		$where_member=array();
		$updata=array();
		$insert=array();
		$liveMb=$_GET['liveMb'];
		$model=Model('live');
		$result=$model->getModifly(array('mobile'=>$liveMb));
		// var_dump($result);
		foreach($result as $key=>$value){
			$member_id=$value['member_id'];
			$insert['live_member_id']=$value['member_id'];
			$insert['live_mobile']=$value['mobile'];
			$insert['live_usersex']=$value['usersex'];
			$insert['live_qq']=$value['qq'];
			$insert['live_cade']=$value['cade'];
			$where_dele['member_id']=$value['member_id'];
			$where_member['member_id']=$value['member_id'];
			$updata['member_mobile']=$value['mobile'];
			$class_one=$value['level_one'];
			$calss_two=$value['level_two'];
			$AppName=$value['mobile'];
			// $StreamName=$value['member_id'];
		}
		DB::beginTransaction();
		$updata['islive']=2;
		$islive=$model->applyFoeLive($where_member,$updata);
		include(BASE_ROOT_PATH.'/live/framework/function/function.php');
		//判断分类
		$level_two=$model->live_class_evea(array('class_name'=>$calss_two));
		$level_one=$model->live_class(array('class_name'=>$class_one));
		switch($level_one[0]['class_id']){
			case '1':
			$DomainName='live.qqbsmall.com';
			$play_address_push=$member_id.'?vhost=live.qqbsmall.com';
			 $player_address_flv='http://live.qqbsmall.com/'.$AppName.'/'.$member_id.'.flv';
			break;
			case '2':
			$DomainName='live01.qqbsmall.com';
			$play_address_push=$member_id.'?vhost=live01.qqbsmall.com';
			 $player_address_flv='http://live01.qqbsmall.com/'.$AppName.'/'.$member_id.'.flv';
			break;
			case '3':
			$DomainName='live02.qqbsmall.com';
			$play_address_push=$member_id.'?vhost=live02.qqbsmall.com';
			 $player_address_flv='http://live02.qqbsmall.com/'.$AppName.'/'.$member_id.'.flv';
			break;
			case '4':
			$DomainName='live.qqbsmall.com';
			$play_address_push=$member_id.'?vhost=live.qqbsmall.com';
			 $player_address_flv='http://live.qqbsmall.com/'.$AppName.'/'.$member_id.'.flv';
			break;
			case '5':
			$DomainName='live.qqbsmall.com';
			$play_address_push=$member_id.'?vhost=live.qqbsmall.com';
			 $player_address_flv='http://live.qqbsmall.com/'.$AppName.'/'.$member_id.'.flv';
			break;
			case '6':
			$DomainName='live.qqbsmall.com';
			$play_address_push=$member_id.'?vhost=live.qqbsmall.com';
			 $player_address_flv='http://live.qqbsmall.com/'.$AppName.'/'.$member_id.'.flv';
			break;
		}
		$succ=setAppLive($DomainName,$AppName);
		$insert['live_type']=1;
		$insert['live_class_id']=$level_one[0]['class_id'];
		$insert['class_evea_id']=$level_two[0]['class_evea_id'];
		$insert['live_class_name']=$class_one;
		$insert['class_evea_name']=$calss_two;
		$insert['push_address_rtmp_camrea'] = 'rtmp://video-center.alivecdn.com/'.$AppName;
		$insert['play_address_push']  = $play_address_push;
		$insert['player_address_flv'] = $player_address_flv;
		$insert['live_online_image'] = 'http://hd01invideo.oss-cn-hangzhou.aliyuncs.com/image_live01/'.$AppName.'/'.$member_id.'.jpg';
		$live=$model->insertLive($insert);
		$dele=$model->delet_pro($where_dele);
		if($islive && $succ && $live && $dele){
			DB::commit();
			echo true;
		}else{
			DB::rollback();
			echo false;
		}
		 // header("Content-type: text/html; charset=utf-8");echo '<pre>';print_r($rollback);exit;
		// if($rollback!=1){
			// echo true;
		// }else{
			// echo false;
		// }
		
	}
	public function failOp(){
		$liveMb=$_GET['liveMb'];
		$where_dele=array();
		$model=Model('live');
		$result=$model->getModifly(array('mobile'=>$liveMb));
		foreach($result as $value){
			$where_dele['member_id']=$value['member_id'];
		}
		$dele=$model->delet_pro($where_dele);
		if($dele){
			echo  true;
		}else{
			echo false;
		}
	}
	
	//直播间管理
	public function live_manageOp(){
		$model=Model('live');
		$result=$model->live_recommended_list();
		TPL::output('live_info',$result);
		TPL::showpage('manage');
	}
	public function live_class_aOp(){
		$model=Model('live');
		$live_class=$model->live_class();
		Tpl::output('live_class',$live_class);
			//下级分类
			$live_class_evea=$model->live_class_evea();
			Tpl::output('live_class_evea',$live_class_evea);
		
		
		
		
		TPL::showpage('live_evea_class');
	}
	public function live_classOp(){
		$model=Model('live');
		$where_one_class=array();
		$where_two_class=array();
		$updata_two=array();
		$updata_one=array();
		$insert_two=array();
		$insert_one=array();
		
		
		
		if($_POST['one_class']){
			foreach($_POST['one_class'] as $key=>$value){
				if($value!=''){
					$res=$model->select_one_live_class(array('class_id'=>intval($key)));
					if(!empty($res)){
						$where_one_class['class_id']=$key;
						$updata_one['class_name']=$value;
						$updata_one['class_image']=$_POST['image_url'][$key];
						$upresult=$model->updata_class_one($where_one_class,$updata_one);
						if($upresult){
							echo true;
						}else{
							echo false;
							exit;
						}
						
					}else{
						$insert['class_id']=intval($key);
						$insert['class_name']=$value;
						$insert['class_image']=$_POST['image_url'][$key];
						$re=$model->insert_class_one($insert);
						if($re){
							echo true;
						}else{
							echo false;
							exit;
						}
					}
				}
				
			}
		}
		
		
		foreach($_POST['class_two'] as $class_evea_id=>$class_name){
			$resu=$model->selectTwoClass(array('class_evea_id'=>$class_evea_id));
			if($class_name!=''){
				if(!empty($resu)){
					$where_two_class['class_evea_id']=$class_evea_id;
					$updata_two['class_name']=$class_name;
					$upda_result=$model->updataTwoClass($where_two_class,$updata_two);
					if($upda_result){
						echo true;
					}else{
						echo false;
						exit;
					}
				}else{
					$insert_two['class_evea_id']=$class_evea_id;
					$insert_two['class_name']=$class_name;
					$insert_two['class_id']=$_POST['Relationship'][$class_evea_id];
					$insert_result=$model->insert_class_two($insert_two);
					// header("Content-type: text/html; charset=utf-8");echo '<pre>';print_r($insert_two);
					if($insert_result){
						echo true;
					}else{
						echo false;
						exit;
					}
				}
			}
			
			
			
		}
		
	}
	public function delet_classOp(){
		$model=Model('live');
		$class_id=$_POST['class_id'];
		$evel=$_POST['evel'];
		if($evel=='one'){
			$result_one=$model->delet_one_class(array('class_id'=>$class_id));
			$result_two=$model->delet_two_class(array('class_id'=>$class_id));
			if($result_one&&$result_two){
				echo true;
			}else{
				echo false;
			}
		}else if($evel=='two'){
			$result_two=$model->delet_two_class(array('class_evea_id'=>$class_id));
			if($result_two){
				echo true;
			}else{
				echo false;
			}
		}
	}
	public function close_live_roomOp(){
		include(BASE_ROOT_PATH.'/live/framework/function/function.php');
		$model=Model('live');
		$live_id=$_POST['live_RM'];
		$live_info=$model->getLiveInfo(array('live_id'=>$live_id));
		if($live_info['live_type']=='1'){
			switch($live_info['live_class_id']){
				case '1':
				$DomainName='live.qqbsmall.com';
				break;
				case '2':
				$DomainName='live01.qqbsmall.com';
				break;
				case '3':
				$DomainName='live02.qqbsmall.com';
				break;
				case '4':
				$DomainName='live.qqbsmall.com';
				break;
				case '5':
				$DomainName='live.qqbsmall.com';
				break;
				case '6':
				$DomainName='live.qqbsmall.com';
				break;
			}			
			$appname=$live_info['live_mobile'];
			$streamName=$live_info['live_member_id'];
			$result=closeLiveRoom($DomainName,$appname,$streamName);
			$fix=Model()->table('live')->where(array('live_mobile'=>$live_id))->update(array('live_state'=>0));
			if($result && $fix){
				echo true;
			}else{
				echo false;
			}
		}else if($live_info['live_type']=='2'){
			showMessage('erreo','网易云的数据正在筹备中敬请期待');
		}
		
	}
	public function open_live_roomOp(){
		include(BASE_ROOT_PATH.'/live/framework/function/function.php');
		$live_id=$_POST['live_RM'];
		if($live_info['live_type']=='1'){
			switch($live_info['live_class_id']){
				case '1':
				$DomainName='live.qqbsmall.com';
				break;
				case '2':
				$DomainName='live01.qqbsmall.com';
				break;
				case '3':
				$DomainName='live02.qqbsmall.com';
				break;
				case '4':
				$DomainName='live.qqbsmall.com';
				break;
				case '5':
				$DomainName='live.qqbsmall.com';
				break;
				case '6':
				$DomainName='live.qqbsmall.com';
				break;
			}			
			$appname=$live_info['live_mobile'];
			$streamName=$live_info['live_member_id'];
			$result=openLiveRoom($DomainName,$appname,$streamName);
			$fix=Model()->table('live')->where(array('live_mobile'=>$live_id))->update(array('live_state'=>0));
			if($result && $fix){
				echo true;
			}else{
				echo false;
			}
		}else if($live_info['live_type']=='2'){
			showMessage('erreo','网易云的数据正在筹备中敬请期待');
		}
	}
	public function searchLiveOp(){
		$keyword=$_POST['keyword'];
		$model=Model('live');
		$result=$model->search($keyword);
		// header("Content-type: text/html; charset=utf-8");echo '<pre>';print_r($result);
		if($result){
			echo json_encode($result);
		}else{
			echo false;
		}
	}
	
	//直播点播列表
	public function on_demandOp(){
		$where	= array();
		if($_POST['demand_title']) {
            $where['demand_title'] = $_POST['demand_title'];
        }
		if($_POST['source']) {
			if($_POST['source'] == 1){
				$where['demand_member_id'] = array('neq','');
			}else if($_POST['source'] == 2){
				$where['demand_admin_id'] = array('neq','');
			}
        }
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_POST['query_start_time']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_POST['query_end_time']);
        $start_unixtime = $if_start_time ? strtotime($_POST['query_start_time']) : null;
        $end_unixtime = $if_end_time ? strtotime($_POST['query_end_time']): null;
        if ($start_unixtime || $end_unixtime) {
            $where['demand_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
		$live_model = Model('live');
		$demand_list = $live_model->onDemandList($where, 'demand_id desc', 10);
		foreach($demand_list as $k=>$val){
			if($val['demand_member_id']){
				$re = Model('member')->getMemberInfo(array('member_id'=>$val['demand_member_id']), 'member_name');
				$demand_list[$k]['member_name'] = $re['member_name'];
				$demand_list[$k]['source'] = '前端';
			}else if($val['demand_admin_id']){
				$re = Model('admin')->infoAdmin(array('admin_id'=>$val['demand_admin_id']), 'admin_name');
				$demand_list[$k]['member_name'] = $re['admin_name'];
				$demand_list[$k]['source'] = '后台';
			}
			$demand_list[$k]['demand_time'] = date('Y-m-d',$val['demand_time']);
		}
		Tpl::output("page",$live_model->showpage());
		TPL::output('demand_list',$demand_list);
		TPL::showpage('live_on_demand');
	}
	
	//上传点播视频内容
	public function add_demandOp(){
		if($_POST['demand_url'] && $_POST['demand_title']){
			$live_model = Model('live');
			$condition = array();
			$condition['demand_admin_id'] = $this->admin_info['id'];
			$condition['demand_url'] = $_POST['demand_url'];
			$condition['demand_title'] = $_POST['demand_title'];
			$condition['demand_time'] = TIMESTAMP;
			$re = $live_model-> addDemand($condition);
			if($re){
				showMessage('添加成功');
			}
			else{
				showMessage('添加失败');
			}
		}
		TPL::showpage('live_demand.add');
	}
	
	//删除点播视频
	public function del_demandOp(){
		$live_model = Model('live');
		$where = array();
		$where['demand_id'] = $_POST['demand_id'];
		$re = $live_model->delDemand($where);
		$this->log('删除点播视频，视频标题：'.$_POST['demand_title'].'',1);
	}
	//推荐
	public function recomendOp(){
		$live_id=$_POST['live_RM'];
		$recomend=$_POST['recomend'];
		if(intval($recomend)===1){
			$recomend=0;
		}elseif(intval($recomend)===0){
			$recomend=1;
		}
		$model=Model('live');
		$where=array();
		$where['live_id']=$live_id;
		$updata=array();
		$updata['live_recomend']=$recomend;
		$result=$model->updataLive($where,$updata);
		if($result){
			echo true;
			
		}else{
			echo false;
		}
	}
}


