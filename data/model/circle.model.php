<?php
/**
 * 圈子模型
 *
 * 
 *
 *
 
 */
defined('GcWebShop') or exit('Access Invalid!');

class circleModel extends Model {
    public function __construct(){
        parent::__construct('circle');
    }
    
    /**
     * 获取圈子数量
     * @param array $condition
     * @return int
     */
    public function getCircleCount($condition) {
        return $this->where($condition)->count();
    }
    
    /**
     * 未审核的圈子数量
     * @param array $condition
     * @return int
     */
    public function getCircleUnverifiedCount($condition = array()) {
        $condition['circle_status'] = 2;
        return $this->getCircleCount($condition);
    }
	
	public function circlelist($member_id){
		$model = Model();
		$circle_list = $model->table('circle')->where(array('circle_masterid'=>$member_id))->select();
		return $circle_list;
	}

	 /**
     * 我的圈子中加入的圈子和创建的圈子 列表
     * @param array $results
     * @return int
     */
    public function select_circle($member_id){
		$model=Model();
		$circle = $model->table('circle')->field('circle_id,circle_name,class_id,circle_mcount,circle_thcount')->where(array('circle_masterid'=>$member_id,'circle_status'=>1))->order('circle_addtime desc')->select();
        $circle_member = $model->table('circle_member')->field('circle_name,circle_id as circleid')->where(array('member_id'=>$member_id,'cm_state'=>1,'is_identity'=>3))->select();		
		$result = array_merge($circle,$circle_member);
		foreach($result as $key=>$value){
			$circle_id = $model->table('circle_class')->where(array('class_id'=>$value['class_id']))->find();
			$member_circle = $model->table('circle')->field('circle_id,circle_name,class_id,circle_mcount as circlemcount,circle_thcount as circlethcount')->where(array('circle_id'=>$value['circleid']))->find();
			$membercircle = $model->table('circle_class')->field('class_name')->where(array('class_id'=>$member_circle['class_id']))->find();
			$circle_theme = $model->table('circle_theme')->where(array('circle_id'=>$value['circle_id']))->select();
			$circletheme = $model->table('circle_theme')->where(array('circle_id'=>$value['circleid']))->select();
			$circle_member = $model->table('circle_member')->where(array('circle_id'=>$value['circle_id'],'cm_state'=>1))->select();
			$circlemember = $model->table('circle_member')->where(array('circle_id'=>$value['circleid'],'cm_state'=>1,'is_identity'=>3))->select();
	 	    $results[$key]['class_name'] = $circle_id['class_name'];
			$results[$key]['classname'] = $membercircle['class_name'];
			$results[$key]['circle_img']=circleLogoM($value['circle_id']);
			$results[$key]['circlename'] = $value['circle_name'];
            $results[$key]['circle_mcount'] = count($circle_member);
            $results[$key]['circle_thcount'] = count($circle_theme);
			$results[$key]['circlemcount'] = count($circlemember);
            $results[$key]['circlethcount'] = count($circletheme);
            $results[$key]['circle_id'] = $value['circle_id'];
			$results[$key]['circleid'] = $value['circleid'];
             			
		}	
		return $results;
	}

     /**
     * 我的圈子管理
     * @param array $my_circle
     * @return int
     */
	public function  manage($circle_id){	
		$model = Model();
		$my_circle = $model->table('circle')->where(array('circle_id'=>$circle_id))->find();
 		return $my_circle;	
	}
	
	 /**
     * 圈子类型
     * @param array $circle_class
     * @return int
     */
	public function circleclass($circle_id){
		$model = Model();
		$my_circle = $model->table('circle')->where(array('circle_id'=>$circle_id))->find();
        $circle_class = $model->table('circle_class')->field('class_name')->where(array('class_id'=>$my_circle['class_id']))->find();	
        return $circle_class;
	}
	
	/**
     * 圈子管理 修改功能
     * @param array $circle_class
     * @return int
     */
	public function updatecircle($circle_id,$circle_content){
		$model = Model();
		$condition = array();
		$condition['circle_desc'] = $circle_content;
		$circle_desc = $model->table('circle')->where(array('circle_id'=>$circle_id))->update($condition);
		return $circle_desc;
	}
	
	/**
     * 圈子管理 圈子下的成员圈子
     * @param array $list
     * @return int
     */
	public function circlelists($circle_id){
		$model = Model();
		$list = $model->table('circle_theme')->where(array('circle_id'=>$circle_id))->select();
		return $list;		
	}
	
	 /**
     * 圈子管理 圈子下的删除圈子
     * @param array $circle_class
     * @return int
     */
	public function deletecircle($theme_id){
		$model = Model();
		$list = $model->table('circle_theme')->where(array('theme_id'=>$theme_id))->delete();
		return $list;		
	}
}
