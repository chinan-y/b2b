<?php
/**
 * 三方订单录入
 *
 *
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');

class wxpackControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		// Language::read('adv');
	}
	public function indexOp(){
		$prefix='wxpack_modify';
		$wxpack=rcache('modelList',$prefix);
		if(!empty($wxpack)){
			
			// header("Content-type: text/html; charset=utf-8");echo '<pre>';print_r(unserialize($wxpack['modelList']));exit;
			TPL::output('wxpack',unserialize($wxpack['modelList']));
		}else{
			TPL::output('edit','1');
		}
		
		TPL::showpage('wxPack');
	}
	public function wxpack_editOp(){
		// var_dump(chksubmit());die;
		 if (chksubmit()) { 
            $obj_validate = new Validate();
            $validate_array = array(
                array('input'=>$_POST['act_name'],'require'=>'true',"validator"=>"Range","min"=>0,"max"=>255,'message'=>Language::get('groupbuy_class_name_is_not_null')),
                array('input'=>$_POST['total_amount'],'require'=>'true','validator'=>'Length','min'=>"1",'max'=>"10",'message'=>Language::get('groupbuy_class_sort_is_not_null')),
				array('input'=>$_POST['total_num'],'require'=>'true',"validator"=>"Length","min"=>"1","max"=>"7",'message'=>Language::get('groupbuy_class_name_is_not_null')),
                array('input'=>$_POST['wishing'],'require'=>'true','validator'=>'Range','min'=>0,'max'=>255,'message'=>Language::get('groupbuy_class_sort_is_not_null')),
				array('input'=>$_POST['remark'],'require'=>'true','validator'=>'Range','min'=>0,'max'=>255,'message'=>Language::get('groupbuy_class_sort_is_not_null')),
            
			);
            $obj_validate->validateparam = $validate_array;
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage(Language::get('error').$error, '', '', 'error');
            }
			
			
			
			
			$prefix='wxpack_modify';
			$wxPack=rcache('modelList',$prefix);
			if(!empty($wxPack)){
				$wxPack=unserialize($wxPack['modelList']);
				
			}
			
			$params=array();
			$params['act_name']=$_POST['act_name'];
			$params['total_amount']=$_POST['total_amount'];
			$params['total_num']=$_POST['total_num'];
			$params['wishing']=$_POST['wishing'];
			$params['remark']=$_POST['remark'];
			$params['time']=time();
			// echo count($wxPack);die;
			if($_POST['editKey']!=''){
				$wxPack[$_POST['editKey']]=$params;
			}else{
				$wxPack[count($wxPack)]=$params;
			}
			$cache=array('modelList'=>serialize($wxPack));
			// header("Content-type: text/html; charset=utf-8");echo '<pre>';print_r($wxPack);exit;
			wcache('modelList',$cache,$prefix);
			TPL::showpage('wxPack');
		 }else{
			$prefix='wxpack_modify';
			$wxPack=rcache('modelList',$prefix);
			if(isset($_GET['key']) && $_GET['key']!=''){
					
					if(!empty($wxPack)){
						// header("Content-type: text/html; charset=utf-8");echo '<pre>';print_r(unserialize($wxPack['modelList'])[$_GET['key']]);exit;
					TPL::output('wxPack',unserialize($wxPack['modelList'])[$_GET['key']]);
					TPL::output('editKey',$_GET['key']);
					}
			} 
			TPL::output('wxpack',unserialize($wxPack['modelList']));
			 // echo 1;die;
			TPL::showpage('wxPack-edit'); 
			 
		 }

		
	}
	public function wxpack_deleteOp(){
		if(isset($_GET['key']) && $_GET['key']!=''){
			$prefix='wxpack_modify';
			$wxPack=rcache('modelList',$prefix);
			if(!empty($wxPack)){
				$wxPack=unserialize($wxPack['modelList']);
				
			}
			unset($wxPack[$_GET['key']]);
			
			$cache=array('modelList'=>serialize($wxPack));
			// header("Content-type: text/html; charset=utf-8");echo '<pre>';print_r($wxPack);exit;
			wcache('modelList',$cache,$prefix);
			echo 1;die;
		}
		
	}
	 
}


