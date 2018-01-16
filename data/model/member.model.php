<?php
/**
 * 会员模型
 *
 */
defined('GcWebShop') or exit('Access Invalid!');
class memberModel extends Model {

    public function __construct(){
        parent::__construct('member');
    }

    /**
     * 会员详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getMemberInfo($condition, $field = '*', $master = false) {
        return $this->table('member')->field($field)->where($condition)->master($master)->find();
    }
	
	/**
     * 会员上三级用户（用于返利）
     */
    public function getMemberSuperior($condition) {
        return $this->table('member_superior')->where($condition)->find();
    }

    /**
     * 取得会员详细信息（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @param int $member_id
     * @param string $field 需要取得的缓存键值, 例如：'*','member_name,member_sex'
     * @return array
     */
    public function getMemberInfoByID($member_id, $fields = '*') {
        $member_info = rcache($member_id, 'member', $fields);
        if (empty($member_info)) {
            $member_info = $this->getMemberInfo(array('member_id'=>$member_id),$fields ,true);
            wcache($member_id, $member_info, 'member');
        }
        return $member_info;
    }

    /**
     * 会员列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getMemberList($condition = array(), $field = '*', $page = 0, $order = 'member_id desc', $limit = '') {
       return $this->table('member')->where($condition)->page($page)->order($order)->field($field)->limit($limit)->select();
    }

    /**
     * 会员数量
     * @param array $condition
     * @return int
     */
    public function getMemberCount($condition) {
        return $this->table('member')->where($condition)->count();
    }

    /**
     * 编辑会员
     * @param array $condition
     * @param array $data
     */
    public function editMember($condition, $data) {
        $update = $this->table('member')->where($condition)->update($data);
        if ($update && $condition['member_id']) {
            dcache($condition['member_id'], 'member');
        }
        return $update;
    }
	
	/**
     * 增加二维码销售员开通的新标记录
     * @param array $condition
     * @param array $data
     */
    public function addSalesman($data) {
        $insert_id = Model()->table('open_salesman')->insert($data);
        return $insert_id;
    }
	
	/**
     * 编辑二维码销售员开通的新表记录
     * @param array $condition
     * @param array $data
     */
    public function editSalesman($condition, $data) {
        $update = $this->table('open_salesman')->where($condition)->update($data);
        return $update;
    }

    /**
     * 登录时创建会话SESSION
     *
     * @param array $member_info 会员信息
     */
    public function createSession($member_info = array(),$reg = false) {
        if (empty($member_info) || !is_array($member_info)) return ;

		$_SESSION['is_login']		= '1';
		$_SESSION['member_id']		= $member_info['member_id'];
		$_SESSION['member_name']	= $member_info['member_name'];
		$_SESSION['member_email']	= $member_info['member_email'];
		$_SESSION['member_mobile']	= $member_info['member_mobile'];
		$_SESSION['is_buy']			= isset($member_info['is_buy']) ? $member_info['is_buy'] : 1;
		$_SESSION['avatar'] 		= $member_info['member_avatar'];
		$_SESSION['refer_id'] 		= $member_info['refer_id'];
		$_SESSION['is_seller'] 		= $member_info['is_seller'];
		$_SESSION['is_manager']		= $member_info['is_manager'];
		$_SESSION['sa_id']			= $member_info['sa_id'];
		$_SESSION['saleplat_id'] 	= $member_info['saleplat_id'];


		$seller_info = Model('seller')->getSellerInfo(array('member_id'=>$_SESSION['member_id']));
		$_SESSION['store_id'] = $seller_info['store_id'];

		if (trim($member_info['member_qqopenid'])){
			$_SESSION['openid']		= $member_info['member_qqopenid'];
		}
		if (trim($member_info['member_sinaopenid'])){
			$_SESSION['slast_key']['uid'] = $member_info['member_sinaopenid'];
		}

		if (!$reg) {
		    //添加会员积分
		    $this->addPoint($member_info);
		    //添加会员经验值
		    $this->addExppoint($member_info);		    
		}

		if(!empty($member_info['member_login_time'])) {
            $update_info	= array(
                'member_login_num'=> ($member_info['member_login_num']+1),
                'member_login_time'=> TIMESTAMP,
                'member_old_login_time'=> $member_info['member_login_time'],
                'member_login_ip'=> getIp(),
                'member_old_login_ip'=> $member_info['member_login_ip']
            );
            $this->editMember(array('member_id'=>$member_info['member_id']),$update_info);
		}
		setNcCookie('cart_goods_num','',-3600);

    }
	/**
	 * 获取会员信息
	 *
	 * @param	array $param 会员条件
	 * @param	string $field 显示字段
	 * @return	array 数组格式的返回结果
	 */
	public function infoMember($param, $field='*') {
		if (empty($param)) return false;

		//得到条件语句
		$condition_str	= $this->getCondition($param);
		$param	= array();
		$param['table']	= 'member';
		$param['where']	= $condition_str;
		$param['field']	= $field;
		$param['limit'] = 1;
		$member_list	= Db::select($param);
		$member_info	= $member_list[0];
		if (intval($member_info['store_id']) > 0){
	      $param	= array();
	      $param['table']	= 'store';
	      $param['field']	= 'store_id';
	      $param['value']	= $member_info['store_id'];
	      $field	= 'store_id,store_name,grade_id';
	      $store_info	= Db::getRow($param,$field);
	      if (!empty($store_info) && is_array($store_info)){
		      $member_info['store_name']	= $store_info['store_name'];
		      $member_info['grade_id']	= $store_info['grade_id'];
	      }
		}
		return $member_info;
	}

    /**
     * 注册
     */
    public function register($register_info) {
		// 注册验证
		/*$obj_validate = new Validate();
		$obj_validate->validateparam = array(
		array("input"=>$register_info["username"],		"require"=>"true",		"message"=>'用户名不能为空'),
		array("input"=>$register_info["password"],		"require"=>"true",		"message"=>'密码不能为空'),
		array("input"=>$register_info["password_confirm"],"require"=>"true",	"validator"=>"Compare","operator"=>"==","to"=>$register_info["password"],"message"=>'密码与确认密码不相同'),
		if($register_info['email']){
			array("input"=>$register_info["email"],			"require"=>"true",		"validator"=>"email", "message"=>'电子邮件格式不正确'),
		}
		if($register_info['mobile']){
			array("input"=>$register_info["mobile"],			"require"=>"true",		"validator"=>"mobile", "message"=>'手机格式不正确')
		}
		);
		$error = $obj_validate->validate();
		if ($error != ''){
            return array('error' => $error);
		}*/

        // 验证用户名是否重复
		$check_member_name	= $this->getMemberInfo(array('member_name'=>$register_info['username']));
		if(is_array($check_member_name) and count($check_member_name) > 0) {
            return array('error' => '该用户名已经存在');
		}

        // 验证邮箱是否重复
		$check_member_email	= $this->getMemberInfo(array('member_email'=>$register_info['email']));
		if(is_array($check_member_email) and count($check_member_email)>0) {
           return array('error' => '该邮箱已经存在');
		}
		
	    // 验证手机号是否重复
		$check_member_mobile	= $this->getMemberInfo(array('member_mobile'=>$register_info['mobile']));
		if(is_array($check_member_mobile) and count($check_member_mobile)>0) {
            return array('error' => '该手机号码已经存在');
		}
		// 会员添加
		$member_info	= array();
		$member_info['member_name']			= $register_info['username'];
		$member_info['member_nickname']		= $register_info['member_nickname'];
		$member_info['member_truename']		= $register_info['member_truename'];
		$member_info['member_code']			= $register_info['member_code'];
		$member_info['member_passwd']		= $register_info['password'];
		$member_info['member_email']		= $register_info['email'];
		$member_info['member_mobile']		= $register_info['mobile'];
		$member_info['member_mobile_bind']	= $register_info['member_mobile_bind'];
		$member_info['ref_url']				= $register_info['ref_url'];
		$member_info['is_membername_modify']= $register_info['is_membername_modify'];
		//添加邀请人(推荐人)会员积分 by Ming 
		$member_info['refer_id']	=	$register_info['refer_id'];
		$member_info['inviter_id']		= $register_info['inviter_id'];
		$refer = $this->getMemberInfo(array('member_id' => $register_info['refer_id']), 'saleplat_id,team_branch,is_manager,is_member_rebate');
		if($register_info['saleplat_id'] && $register_info['saleplat_id'] != 'null'){
			$member_info['saleplat_id'] = $register_info['saleplat_id'];
		}else{
			if($refer['saleplat_id']){
				$member_info['saleplat_id'] = $refer['saleplat_id'];
			}
		}
		//团队分支
		if($refer['team_branch'] && (substr_count($refer['team_branch'], ','))< 3){
			$member_info['team_branch'] = $refer['team_branch'];
		}
		if(!$refer['team_branch'] && $refer['is_manager']==1 && $refer['is_member_rebate']==1){
			$member_info['team_branch'] = $register_info['refer_id'];
		}
		$insert_id	= $this->addMember($member_info);
		if($insert_id) {
			
			if (C('salescredit_isuse')){
				//添加销售人员邀请返利
				Model('salescredit')->saveSalescreditLog('regist',array('sc_memberid'=>$insert_id,'sc_membername'=>$register_info['username']),false);
				
				$refer_name = Model('member')->table('member')->getfby_member_id($member_info['refer_id'],'member_name');
				
				Model('salescredit')->saveSalescreditLog('inviter',array('sc_memberid'=>$register_info['refer_id'],'sc_membername'=>$refer_name,'invited'=>$member_info['member_name']));
				}
				
			//添加邀请人(推荐人)会员积分
			if (C('points_isuse')){
				Model('points')->savePointsLog('regist',array('pl_memberid'=>$insert_id,'pl_membername'=>$register_info['username']),false);

				$inviter_name = Model('member')->table('member')->getfby_member_id($member_info['inviter_id'],'member_name');
			
				Model('points')->savePointsLog('inviter',array('pl_memberid'=>$register_info['inviter_id'],'pl_membername'=>$inviter_name,'invited'=>$member_info['member_name']));
				}
			

            // 添加默认相册
            $insert['ac_name']      = '买家秀';
            $insert['member_id']    = $insert_id;
            $insert['ac_des']       = '买家秀默认相册';
            $insert['ac_sort']      = 1;
            $insert['is_default']   = 1;
            $insert['upload_time']  = TIMESTAMP;
            $this->table('sns_albumclass')->insert($insert);

            $member_info['member_id'] = $insert_id;
            $member_info['is_buy'] = 1;

            return $member_info;
		} else {
            return array('error' => '注册失败');
		}

    }

	 public function registerm($register_info) {
		// 注册验证
		$obj_validate = new Validate();
		$obj_validate->validateparam = array(
		array("input"=>$register_info["username"],		"require"=>"true",		"message"=>'用户名不能为空'),
		array("input"=>$register_info["password"],		"require"=>"true",		"message"=>'密码不能为空'),
		/*array("input"=>$register_info["password_confirm"],"require"=>"true",	"validator"=>"Compare","operator"=>"==","to"=>$register_info["password"],"message"=>'密码与确认密码不相同'),
		array("input"=>$register_info["email"],			"require"=>"true",		"validator"=>"email", "message"=>'电子邮件格式不正确'),
		array("input"=>$register_info["mobile"],			"require"=>"true",		"validator"=>"mobile", "message"=>'手机格式不正确')*/
		);
		$error = $obj_validate->validate();
		if ($error != ''){
            return array('error' => $error);
		}

        // 验证用户名是否重复
		$check_member_name	= $this->getMemberInfo(array('member_name'=>$register_info['username']));
		if(is_array($check_member_name) and count($check_member_name) > 0) {
            return array('error' => '用户名已存在');
		}

        // 验证邮箱是否重复
		/*$check_member_email	= $this->getMemberInfo(array('member_email'=>$register_info['email']));
		if(is_array($check_member_email) and count($check_member_email)>0) {
           return array('error' => '邮箱已存在');
		}
	    //验证手机号是否重复
		$check_member_mobile	= $this->getMemberInfo(array('member_mobile'=>$register_info['mobile']));
		if(is_array($check_member_mobile) and count($check_member_mobile)>0) {
         return array('error' => '手机号码已存在');
		}*/
		
		// 会员添加
		$member_info	= array();
		$member_info['member_name']			= $register_info['username'];
		$member_info['member_nickname']		= $register_info['member_nickname'];
		$member_info['member_truename']		= $register_info['member_truename'];
		$member_info['member_code']			= $register_info['member_code'];
		$member_info['member_passwd']		= $register_info['password'];
		$member_info['member_email']		= $register_info['email'];
		$member_info['member_mobile']		= $register_info['mobile'];
		$member_info['member_mobile_bind']	= $register_info['member_mobile_bind'];
		$member_info['ref_url']				= $register_info['ref_url'];
		$member_info['is_membername_modify']= $register_info['is_membername_modify'];
		//添加邀请人(推荐人)会员积分 by Ming
		$member_info['refer_id']	=	$register_info['refer_id'];
		$member_info['inviter_id']		= $register_info['inviter_id'];
		if($register_info['saleplat_id'] && $register_info['saleplat_id'] != 'null'){
			$member_info['saleplat_id'] = $register_info['saleplat_id'];
		}else{
			$ref = $this->getMemberInfo(array('member_id' => $register_info['refer_id']), 'saleplat_id');
			if($ref['saleplat_id']){
				$member_info['saleplat_id'] = $ref['saleplat_id'];
			}
		}
		//团队分支
		$refer = $this->getMemberInfo(array('member_id' => $register_info['refer_id']), 'team_branch,is_manager,is_member_rebate');
		if($refer['team_branch'] && (substr_count($refer['team_branch'], ','))< 3){
			$member_info['team_branch'] = $refer['team_branch'];
		}
		if(!$refer['team_branch'] && $refer['is_manager']==1 && $refer['is_member_rebate']==1){
			$member_info['team_branch'] = $register_info['refer_id'];
		}
		$insert_id	= $this->addMember($member_info);
		if($insert_id) {
			
			if (C('salescredit_isuse')){
				//添加销售人员邀请返利
				Model('salescredit')->saveSalescreditLog('regist',array('sc_memberid'=>$insert_id,'sc_membername'=>$register_info['username']),false);
				
				$refer_name = Model('member')->table('member')->getfby_member_id($member_info['refer_id'],'member_name');
				
				Model('salescredit')->saveSalescreditLog('inviter',array('sc_memberid'=>$register_info['refer_id'],'sc_membername'=>$refer_name,'invited'=>$member_info['member_name']));
				}
				
			//添加邀请人(推荐人)会员积分
			if (C('points_isuse')){
				Model('points')->savePointsLog('regist',array('pl_memberid'=>$insert_id,'pl_membername'=>$register_info['username']),false);

				$inviter_name = Model('member')->table('member')->getfby_member_id($member_info['inviter_id'],'member_name');
			
				Model('points')->savePointsLog('inviter',array('pl_memberid'=>$register_info['inviter_id'],'pl_membername'=>$inviter_name,'invited'=>$member_info['member_name']));
				}

            // 添加默认相册
            $insert['ac_name']      = '买家秀';
            $insert['member_id']    = $insert_id;
            $insert['ac_des']       = '买家秀默认相册';
            $insert['ac_sort']      = 1;
            $insert['is_default']   = 1;
            $insert['upload_time']  = TIMESTAMP;
            $this->table('sns_albumclass')->insert($insert);

            $member_info['member_id'] = $insert_id;
            $member_info['is_buy'] = 1;

            return $member_info;
		} else {
            return array('error' => '注册失败');
		}

    }

	/**
	 * 注册商城会员
	 *
	 * @param	array $param 会员信息
	 * @return	array 数组格式的返回结果
	 */
	public function addMember($param) {
		if(empty($param)) {
			return false;
		}
		try {
		    $this->beginTransaction();
		    $member_info	= array();
		    $member_info['member_id']				= $param['member_id'];
		    $member_info['member_name']				= $param['member_name'];
		    $member_info['member_passwd']			= md5(trim($param['member_passwd']));
		    $member_info['member_email']			= $param['member_email'];
			$member_info['member_mobile']			= $param['member_mobile'];
			$member_info['member_mobile_bind']		= $param['member_mobile_bind'];
		    $member_info['member_time']				= TIMESTAMP;
		    $member_info['member_login_time'] 		= TIMESTAMP;
		    $member_info['member_old_login_time'] 	= TIMESTAMP;
		    $member_info['member_login_ip']			= getIp();
		    $member_info['member_old_login_ip']		= $member_info['member_login_ip'];

		    $member_info['member_truename']			= $param['member_truename'];
		    $member_info['member_nickname']			= $param['member_nickname'];
		    $member_info['member_code']				= $param['member_code'];
		    $member_info['member_qq']				= $param['member_qq'];
		    $member_info['member_sex']				= $param['member_sex'];
		    $member_info['member_avatar']			= $param['member_avatar'];
		    $member_info['member_qqopenid']			= $param['member_qqopenid'];
		    $member_info['member_qqinfo']			= $param['member_qqinfo'];
		    $member_info['member_sinaopenid']		= $param['member_sinaopenid'];
		    $member_info['member_sinainfo']			= $param['member_sinainfo'];
			//新加
			$member_info['member_wechatopenid']     = $param['member_wechatopenid'];
			$member_info['member_wechatinfo']       = $param['member_wechatinfo'];
			$member_info['member_wechatpcopenid'] 	= $param['member_wechatpcopenid'];
            $member_info['member_wechatpcinfo']     = $param['member_wechatpcinfo'];
            $member_info['member_wechatunionid']    = $param['member_wechatunionid'];
			$member_info['is_membername_modify']	= 0;
			$member_info['ref_url']					= $param['ref_url'];
			$member_info['saleplat_id']				= $param['saleplat_id'] ? $param['saleplat_id'] : 0;
		    //添加邀请人(推荐人)会员积分 by Ming
		    $member_info['inviter_id']	        	= $param['inviter_id'] ? $param['inviter_id'] : 0;
			$member_info['refer_id']				= $param['refer_id'] ? $param['refer_id'] : 0;
			$member_info['team_branch']				= $param['team_branch'];
			$member_info['is_rebate']				= 1;
			$member_info['is_seller']				= 0;
			
		    $insert_id	= Model()->table('member')->insert($member_info);
			if($param['team_branch'] && (substr_count($param['team_branch'], ','))<2){
				$this->editMember(array('member_id'=>$insert_id), array('team_branch'=>$param['team_branch'].','.$insert_id));
			}
			/*新注册会员添加上3级用户ID到新表*/
			Model()->table('member_superior')->insert(array('member_id'=>$insert_id));
			$one_id = $this->getMemberInfo(array('member_id'=>$insert_id), 'refer_id');
			if($one_id['refer_id'] > 0){
				Model()->table('member_superior')->where(array('member_id'=>$insert_id))->update(array('one_id'=>$one_id['refer_id']));
			}
			$two_id = $this->getMemberInfo(array('member_id'=>$one_id['refer_id']), 'refer_id');
			if($two_id['refer_id'] > 0){
				Model()->table('member_superior')->where(array('member_id'=>$insert_id))->update(array('two_id'=>$two_id['refer_id']));
			}
			$three_id = $this->getMemberInfo(array('member_id'=>$two_id['refer_id']), 'refer_id');
			if($three_id['refer_id'] > 0){
				Model()->table('member_superior')->where(array('member_id'=>$insert_id))->update(array('three_id'=>$three_id['refer_id']));
			}
			/*新注册会员添加上3级用户ID到新表*/
			
		    if (!$insert_id) {
		        throw new Exception();
		    }
		    $insert = $this->addMemberCommon(array('member_id'=>$insert_id));
		    if (!$insert) {
		        throw new Exception();
		    }
		    $this->commit();
		    return $insert_id;
		} catch (Exception $e) {
		    $this->rollback();
		    return false;
		}
	}

	/**
	 * 会员登录检查
	 *
	 */
	public function checkloginMember() {
		if($_SESSION['is_login'] == '1') {
			@header("Location: index.php");
			exit();
		}
	}

    /**
	 * 检查会员是否允许举报商品
	 *
	 */
	public function isMemberAllowInform($member_id) {
        $condition = array();
        $condition['member_id'] = $member_id;
        $member_info = $this->getMemberInfo($condition,'inform_allow');
        if(intval($member_info['inform_allow']) === 1) {
            return true;
        }
        else {
            return false;
        }
	}
	
	//检查会员是否允许生成二维码
	public function isMemberMakeQcode($member_id){
		$condition = array();
		$condition['member_id'] = $member_id;
		$member_info = $this-getMemberInfo($condition,'is_seller');
		if(intval($member_info['is_seller']) === 1) {
			return true;
		}
		else {
			return false;
		}
	}
	
	//检查会员是否为区域经理
	public function is_manager_check($member_id){
		$condition = array();
		$condition['member_id'] = $member_id;
		$member_info = $this->getMemberInfo($condition,'is_manager');
		if(intval($member_info['is_manager']) === 1){
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * 取单条信息
	 * @param unknown $condition
	 * @param string $fields
	 */
	public function getMemberCommonInfo($condition = array(), $fields = '*') {
	    return $this->table('member_common')->where($condition)->field($fields)->find();
	}

	/**
	 * 插入扩展表信息
	 * @param unknown $data
	 * @return Ambigous <mixed, boolean, number, unknown, resource>
	 */
	public function addMemberCommon($data) {
	    return $this->table('member_common')->insert($data);
	}

	/**
	 * 编辑会员扩展表
	 * @param unknown $data
	 * @param unknown $condition
	 * @return Ambigous <mixed, boolean, number, unknown, resource>
	 */
	public function editMemberCommon($data,$condition) {
	    return $this->table('member_common')->where($condition)->update($data);
	}

	/**
	 * 添加会员积分
	 * @param unknown $member_info
	 */
	public function addPoint($member_info) {
	    if (!C('points_isuse') || empty($member_info)) return;
	
	    //一天内只有第一次登录赠送积分
	    if(trim(@date('Y-m-d',$member_info['member_login_time'])) == trim(date('Y-m-d'))) return;

	    //加入队列
	    $queue_content = array();
	    $queue_content['member_id'] = $member_info['member_id'];
	    $queue_content['member_name'] = $member_info['member_name'];
	    QueueClient::push('addPoint',$queue_content);
	}

	/**
	 * 添加会员经验值
	 * @param unknown $member_info
	 */
	public function addExppoint($member_info) {
	    if (empty($member_info)) return;

	    //一天内只有第一次登录赠送经验值
	    if(trim(@date('Y-m-d',$member_info['member_login_time'])) == trim(date('Y-m-d'))) return;
	
	    //加入队列
	    $queue_content = array();
	    $queue_content['member_id'] = $member_info['member_id'];
	    $queue_content['member_name'] = $member_info['member_name'];
	    QueueClient::push('addExppoint',$queue_content);
	}

	/**
	 * 取得会员安全级别
	 * @param unknown $member_info
	 */
	public function getMemberSecurityLevel($member_info = array()) {
	    $tmp_level = 0;
	    if ($member_info['member_email_bind'] == '1') {
	        $tmp_level += 1;
	    }
	    if ($member_info['member_mobile_bind'] == '1') {
	        $tmp_level += 1;
	    }
	    if ($member_info['member_paypwd'] != '') {
	        $tmp_level += 1;
	    }
	    return $tmp_level;
	}

	/**
	 * 获得会员等级
	 * @param bool $show_progress 是否计算其当前等级进度
	 * @param int $exppoints  会员经验值
	 * @param array $cur_level 会员当前等级
	 */
	public function getMemberGradeArr($show_progress = false,$exppoints = 0,$cur_level = ''){
	    $member_grade = C('member_grade')?unserialize(C('member_grade')):array();
	    //处理会员等级进度
	    if ($member_grade && $show_progress){
	        $is_max = false;
	        if ($cur_level === ''){
	            $cur_gradearr = $this->getOneMemberGrade($exppoints, false, $member_grade);
	            $cur_level = $cur_gradearr['level'];
	        }
	        foreach ($member_grade as $k=>$v){
	            if ($cur_level == $v['level']){
	                $v['is_cur'] = true;
	            }
	            $member_grade[$k] = $v;
	        }
	    }
	    return $member_grade;
	}

	/**
	 * 获得会员来源
	 * @param bool $show_progress 是否计算其当前等级进度
	 * @param int $exppoints  会员经验值
	 * @param array $cur_level 会员当前等级
	 */
	public function getMemberSaleplatArr(){
	    return $member_saleplat = array(
			'0' => 'PC端注册',
			'11' => '手机端注册',
			'12' => '三方数据注册',
			'12' => '三方数据注册',
			'21' => '21注册',
		);
	}
	/**
	 * 将条件数组组合为SQL语句的条件部分
	 *
	 * @param	array $conditon_array
	 * @return	string
	 */
	private function getCondition($conditon_array){
		$condition_sql = '';
		if($conditon_array['member_id'] != '') {
			$condition_sql	.= " and member_id= '" .intval($conditon_array['member_id']). "'";
		}
		if($conditon_array['member_name'] != '') {
			$condition_sql	.= " and member_name='".$conditon_array['member_name']."'";
		}
		if($conditon_array['member_passwd'] != '') {
			$condition_sql	.= " and member_passwd='".$conditon_array['member_passwd']."'";
		}
		//专属销售人员
		if($conditon_array['refer_id'] != '') {
			$condition_sql	.= " and refer_id= '" .intval($conditon_array['refer_id']). "'";
		}
		//所属销售区域
		if($conditon_array['sa_id'] != '') {
			$condition_sql	.= " and sa_id= '" .intval($conditon_array['sa_id']). "'";
		}
		//是否允许举报
		if($conditon_array['inform_allow'] != '') {
			$condition_sql	.= " and inform_allow='{$conditon_array['inform_allow']}'";
		}
		//是否允许购买
		if($conditon_array['is_buy'] != '') {
			$condition_sql	.= " and is_buy='{$conditon_array['is_buy']}'";
		}
		//是否允许发言
		if($conditon_array['is_allowtalk'] != '') {
			$condition_sql	.= " and is_allowtalk='{$conditon_array['is_allowtalk']}'";
		}
		//是否允许登录
		if($conditon_array['member_state'] != '') {
			$condition_sql	.= " and member_state='{$conditon_array['member_state']}'";
		}
		//是否是销售人员
		if($conditon_array['is_seller'] != '') {
			$condition_sql	.= " and is_seller='{$conditon_array['is_seller']}'";
		}
		//是否是区域经理
		if($conditon_array['is_manager'] != '') {
			$condition_sql	.= " and is_manager='{$conditon_array['is_manager']}'";
		}
		if($conditon_array['friend_list'] != '') {
			$condition_sql	.= " and member_name IN (".$conditon_array['friend_list'].")";
		}
		if($conditon_array['member_email'] != '') {
			$condition_sql	.= " and member_email='".$conditon_array['member_email']."'";
		}
		if($conditon_array['no_member_id'] != '') {
			$condition_sql	.= " and member_id != '".$conditon_array['no_member_id']."'";
		}
		if($conditon_array['like_member_name'] != '') {
			$condition_sql	.= " and member_name like '%".$conditon_array['like_member_name']."%'";
		}
		if($conditon_array['like_member_email'] != '') {
			$condition_sql	.= " and member_email like '%".$conditon_array['like_member_email']."%'";
		}
		if($conditon_array['like_member_truename'] != '') {
			$condition_sql	.= " and member_truename like '%".$conditon_array['like_member_truename']."%'";
		}
		if($conditon_array['in_member_id'] != '') {
			$condition_sql	.= " and member_id IN (".$conditon_array['in_member_id'].")";
		}
		if($conditon_array['in_member_name'] != '') {
			$condition_sql	.= " and member_name IN (".$conditon_array['in_member_name'].")";
		}
		if($conditon_array['member_qqopenid'] != '') {
			$condition_sql	.= " and member_qqopenid = '{$conditon_array['member_qqopenid']}'";
		}
		if($conditon_array['member_sinaopenid'] != '') {
			$condition_sql	.= " and member_sinaopenid = '{$conditon_array['member_sinaopenid']}'";
		}
		return $condition_sql;
	}
		/**
	 * 删除会员
	 *
	 * @param int $id 记录ID
	 * @return array $rs_row 返回数组形式的查询结果
	 */
	public function del($id){
		if (intval($id) > 0){
			$where = " member_id = '". intval($id) ."'";
			$result = Db::delete('member',$where);
			return $result;
		}else {
			return false;
		}
	}

	/**
	 * 获得某一会员等级
	 * @param int $exppoints
	 * @param bool $show_progress 是否计算其当前等级进度
	 * @param array $member_grade 会员等级
	 */
	public function getOneMemberGrade($exppoints,$show_progress = false,$member_grade = array()){
	    if (!$member_grade){
	        $member_grade = C('member_grade')?unserialize(C('member_grade')):array();
	    }
	    if (empty($member_grade)){//如果会员等级设置为空
	        $grade_arr['level'] = -1;
	        $grade_arr['level_name'] = '暂无等级';
	        return $grade_arr;
	    }
	    
	    $exppoints = intval($exppoints);
	    
	    $grade_arr = array();
	    if ($member_grade){
		    foreach ($member_grade as $k=>$v){
		        if($exppoints >= $v['exppoints']){
		            $grade_arr = $v;
		        }
			}
		}
		//计算提升进度
		if ($show_progress == true){
		    if (intval($grade_arr['level']) >= (count($member_grade) - 1)){//如果已达到顶级会员
		        $grade_arr['downgrade'] = $grade_arr['level'] - 1;//下一级会员等级
		        $grade_arr['downgrade_name'] = $member_grade[$grade_arr['downgrade']]['level_name'];
		        $grade_arr['downgrade_exppoints'] = $member_grade[$grade_arr['downgrade']]['exppoints'];
		        $grade_arr['upgrade'] = $grade_arr['level'];//上一级会员等级
		        $grade_arr['upgrade_name'] = $member_grade[$grade_arr['upgrade']]['level_name'];
		        $grade_arr['upgrade_exppoints'] = $member_grade[$grade_arr['upgrade']]['exppoints'];
		        $grade_arr['less_exppoints'] = 0;
		        $grade_arr['exppoints_rate'] = 100;
		    } else {
		        $grade_arr['downgrade'] = $grade_arr['level'];//下一级会员等级
		        $grade_arr['downgrade_name'] = $member_grade[$grade_arr['downgrade']]['level_name'];
		        $grade_arr['downgrade_exppoints'] = $member_grade[$grade_arr['downgrade']]['exppoints'];
		        $grade_arr['upgrade'] = $member_grade[$grade_arr['level']+1]['level'];//上一级会员等级
		        $grade_arr['upgrade_name'] = $member_grade[$grade_arr['upgrade']]['level_name'];
		        $grade_arr['upgrade_exppoints'] = $member_grade[$grade_arr['upgrade']]['exppoints'];
		        $grade_arr['less_exppoints'] = $grade_arr['upgrade_exppoints'] - $exppoints;
		        $grade_arr['exppoints_rate'] = round(($exppoints - $member_grade[$grade_arr['level']]['exppoints'])/($grade_arr['upgrade_exppoints'] - $member_grade[$grade_arr['level']]['exppoints'])*100,2);
		    }
		}
		return $grade_arr;
	}
	
	/**
	 * 找回密码
	 * @param $register_info 用户电话
	 * 
	 */
	public function change($register_info){
		$password = array("member_passwd" =>md5($register_info["password"]));
		$user = $this->table("member")->where(array('member_mobile'=>$register_info['mobile']))->select();
		if($user){
			$this->table("member")->where(array('member_mobile'=>$register_info['mobile']))->update($password);
			$user = $this->table("member")->where(array('member_mobile'=>$register_info['mobile']))->select();
		}

		return $user;
	}
	
	/**
	 * 修改密码
	 */
	
	public function modify($modify){
		$pwd = $this -> where(array("member_id" => $modify['member_id']))->select();
		$old = $pwd[0]['member_passwd'];
		$mod = array('member_passwd' =>$modify['member_passwd']);
		
		if($modify['old_password'] == $old){
			$mody = $this -> where(array("member_id" => $modify['member_id']))->update($mod);
			$mody = $this -> where(array("member_id" => $modify['member_id']))->select();
		}else{
			$mody = false;
		}
		return $mody;
	}
	
	/**
	 * 手机个人中心'设置'修改绑定手机号
	 */
	public function modify_mobile($modify){
		$mobile = $this -> where(array("member_id" => $modify['member_id']))->select();
		$old_mobile = $mobile[0]['member_mobile'];
		$new_mobile = array();
		$new_mobile['member_mobile'] = $modify['new_mobile'];
		$new_mobile['member_mobile_bind'] = $modify['member_mobile_bind'];
		$mody = $this -> where(array("member_id" => $modify['member_id']))->update($new_mobile);
		$mody = $this -> where(array("member_id" => $modify['member_id']))->select();
		return $mody;
	}
	
	public function unbundling($member_id){
		$wx = array();
		$wx['member_wechatopenid'] = null; 
		$wx['member_wechatinfo'] = null; 
		$wx['member_wechatpcopenid'] = null; 
		$wx['member_wechatpcinfo'] = null; 
		$wx['member_wechatunionid'] = null; 
		$member_true = Model()->table('member')->where($member_id)->update($wx);
		return $member_true;
		
	}
	public function binding($register_info){
		$member = array();
		$member_id =array('member_id' =>$register_info['member_id']);
		$member['member_nickname'] = $register_info['nickname'];
		$member['member_passwd'] = $register_info['member_passwd'];
		$member['member_name'] = $register_info['username'];
		$member['member_mobile'] = $register_info['member_mobile'];
		$member['member_mobile_bind'] = $register_info['member_mobile_bind'];
		$member_mobile = $this->editMember(array('member_id'=>$register_info['member_id']),$member);
		return $member_mobile;
	}
	
	/**
	 * 修改个人返利率
	 */
	public function member_rebate_rate($member_array){
		$thh = Model() ->table('member') -> where(array('member_id' => $member_array['member_id'])) ->update(array("member_rebate_rate" => $member_array['rebate_rate']));
		return $thh;
	}
	
	/**
	 * 确认收货，结算返利到用户的累计收益和提现余额（更改为付款后结算并增加短信提醒，2017-7-10）
	 * 
	 */
	 public function to_update($order_info){
		$member_id = $order_info['buyer_id'];
		$rebate = $order_info['goods_rebate_amount'];
		$one_rebate = $order_info['one_rebate'];
		$two_rebate = $order_info['two_rebate'];
		$three_rebate = $order_info['three_rebate'];
		$platform_rebate = $order_info['platform_rebate'];
		$area_rebate = $order_info['area_rebate'];
		
		$is_rebate = C('salescredit_isuse'); //是否开启返利模式
		$member_superior = $this->getMemberSuperior(array('member_id'=>$member_id));
	 	$member = $this->getMemberInfo(array('member_id' => $member_id));
		$sms = new Sms();
		
		if($member['is_seller'] == 1 && $member['is_rebate'] == 1 && $is_rebate == 1){
			//只有一级返利
			if($one_rebate>0 && $two_rebate==0 && $three_rebate==0){
				if($member_superior['one_id'] == NULL){
					$rebate_data = array();
					$rebate_data['sc_memberid'] = $member_id;
					$rebate_data['sc_membername'] = $order_info['buyer_name'];
					$rebate_data['buyer_name'] = $order_info['buyer_name'];
					$rebate_data['order_sn'] = $order_info['order_sn'];
					$rebate_data['rebate_amount'] = $rebate + $one_rebate;
					$re = Model('salescredit')->saveSalescreditLog('order',$rebate_data);
					if($re && $member['member_mobile']){
						$sms->send($member['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$rebate_data['rebate_amount'].'元的消费奖励，详见http://t.cn/RKAEOgL');
					}
				}else{
					$rebate_data = array();
					$rebate_data['sc_memberid'] = $member_id;
					$rebate_data['sc_membername'] = $order_info['buyer_name'];
					$rebate_data['buyer_name'] = $order_info['buyer_name'];
					$rebate_data['order_sn'] = $order_info['order_sn'];
					$rebate_data['rebate_amount'] = $rebate ;
					$re = Model('salescredit')->saveSalescreditLog('order',$rebate_data);
					if($re && $member['member_mobile']){
						$sms->send($member['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$rebate_data['rebate_amount'].'元的消费奖励，详见http://t.cn/RKAEOgL');
					}
					$one_data = array();
					$one_data['sc_memberid'] = $member_superior['one_id'];
					$one_name = $this->getMemberInfo(array('member_id' => $member_superior['one_id']),'member_name,member_mobile');
					$one_data['sc_membername'] = $one_name['member_name'];
					$one_data['buyer_name'] = $order_info['buyer_name'];
					$one_data['order_sn'] = $order_info['order_sn'];
					$one_data['rebate_amount'] = $one_rebate;
					$one = Model('salescredit')->saveSalescreditLog('rebate',$one_data);
					if($one && $one_name['member_mobile']){
						$sms->send($one_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$one_data['rebate_amount'].'元的推广奖励，详见http://t.cn/RKAEOgL');
					}
				}
			//一级返利和二级返利
			}else if($one_rebate>0 && $two_rebate>0 && $three_rebate==0){
				if($member_superior['one_id'] == NULL && $member_superior['two_id'] == NULL){
					$rebate_data = array();
					$rebate_data['sc_memberid'] = $member_id;
					$rebate_data['sc_membername'] = $order_info['buyer_name'];
					$rebate_data['buyer_name'] = $order_info['buyer_name'];
					$rebate_data['order_sn'] = $order_info['order_sn'];
					$rebate_data['rebate_amount'] = $rebate + $one_rebate + $two_rebate;
					$re = Model('salescredit')->saveSalescreditLog('order',$rebate_data);
					if($re && $member['member_mobile']){
						$sms->send($member['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$rebate_data['rebate_amount'].'元的消费奖励，详见http://t.cn/RKAEOgL');
					}
				}else if($member_superior['one_id']>0 && $member_superior['two_id'] == NULL){
					$rebate_data = array();
					$rebate_data['sc_memberid'] = $member_id;
					$rebate_data['sc_membername'] = $order_info['buyer_name'];
					$rebate_data['buyer_name'] = $order_info['buyer_name'];
					$rebate_data['order_sn'] = $order_info['order_sn'];
					$rebate_data['rebate_amount'] = $rebate + $two_rebate ;
					$re = Model('salescredit')->saveSalescreditLog('order',$rebate_data);
					if($re && $member['member_mobile']){
						$sms->send($member['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$rebate_data['rebate_amount'].'元的消费奖励，详见http://t.cn/RKAEOgL');
					}
					$one_data = array();
					$one_data['sc_memberid'] = $member_superior['one_id'];
					$one_name = $this->getMemberInfo(array('member_id' => $member_superior['one_id']),'member_name,member_mobile');
					$one_data['sc_membername'] = $one_name['member_name'];
					$one_data['buyer_name'] = $order_info['buyer_name'];
					$one_data['order_sn'] = $order_info['order_sn'];
					$one_data['rebate_amount'] = $one_rebate;
					$one = Model('salescredit')->saveSalescreditLog('rebate',$one_data);
					if($one && $one_name['member_mobile']){
						$sms->send($one_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$one_data['rebate_amount'].'元的推广奖励，详见http://t.cn/RKAEOgL');
					}
				}else if($member_superior['one_id']>0 && $member_superior['two_id']>0){
					$rebate_data = array();
					$rebate_data['sc_memberid'] = $member_id;
					$rebate_data['sc_membername'] = $order_info['buyer_name'];
					$rebate_data['buyer_name'] = $order_info['buyer_name'];
					$rebate_data['order_sn'] = $order_info['order_sn'];
					$rebate_data['rebate_amount'] = $rebate ;
					$re = Model('salescredit')->saveSalescreditLog('order',$rebate_data);
					if($re && $member['member_mobile']){
						$sms->send($member['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$rebate_data['rebate_amount'].'元的消费奖励，详见http://t.cn/RKAEOgL');
					}
					$one_data = array();
					$one_data['sc_memberid'] = $member_superior['one_id'];
					$one_name = $this->getMemberInfo(array('member_id' => $member_superior['one_id']),'member_name,member_mobile');
					$one_data['sc_membername'] = $one_name['member_name'];
					$one_data['buyer_name'] = $order_info['buyer_name'];
					$one_data['order_sn'] = $order_info['order_sn'];
					$one_data['rebate_amount'] = $one_rebate;
					$one = Model('salescredit')->saveSalescreditLog('rebate',$one_data);
					if($one && $one_name['member_mobile']){
						$sms->send($one_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$one_data['rebate_amount'].'元的推广奖励，详见http://t.cn/RKAEOgL');
					}
					$two_data = array();
					$two_data['sc_memberid'] = $member_superior['two_id'];
					$two_name = $this->getMemberInfo(array('member_id' => $member_superior['two_id']),'member_name,member_mobile');
					$two_data['sc_membername'] = $two_name['member_name'];
					$two_data['buyer_name'] = $order_info['buyer_name'];
					$two_data['order_sn'] = $order_info['order_sn'];
					$two_data['rebate_amount'] = $two_rebate;
					$two = Model('salescredit')->saveSalescreditLog('rebate',$two_data);
					if($two && $two_name['member_mobile']){
						$sms->send($two_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$two_data['rebate_amount'].'元的推广奖励，详见http://t.cn/RKAEOgL');
					}
				}
			//三级返利都有
			}else if($one_rebate>0 && $two_rebate>0 && $three_rebate>0){
				if($member_superior['one_id'] == NULL && $member_superior['two_id'] == NULL && $member_superior['three_id'] == NULL){
					$rebate_data = array();
					$rebate_data['sc_memberid'] = $member_id;
					$rebate_data['sc_membername'] = $order_info['buyer_name'];
					$rebate_data['buyer_name'] = $order_info['buyer_name'];
					$rebate_data['order_sn'] = $order_info['order_sn'];
					$rebate_data['rebate_amount'] = $rebate + $one_rebate + $two_rebate + $three_rebate;
					$re = Model('salescredit')->saveSalescreditLog('order',$rebate_data);
					if($re && $member['member_mobile']){
						$sms->send($member['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$rebate_data['rebate_amount'].'元的消费奖励，详见http://t.cn/RKAEOgL');
					}
				}else if($member_superior['one_id']>0 && $member_superior['two_id'] == NULL && $member_superior['three_id'] == NULL){
					$rebate_data = array();
					$rebate_data['sc_memberid'] = $member_id;
					$rebate_data['sc_membername'] = $order_info['buyer_name'];
					$rebate_data['buyer_name'] = $order_info['buyer_name'];
					$rebate_data['order_sn'] = $order_info['order_sn'];
					$rebate_data['rebate_amount'] = $rebate + $two_rebate + $three_rebate ;
					$re = Model('salescredit')->saveSalescreditLog('order',$rebate_data);
					if($re && $member['member_mobile']){
						$sms->send($member['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$rebate_data['rebate_amount'].'元的消费奖励，详见http://t.cn/RKAEOgL');
					}
					$one_data = array();
					$one_data['sc_memberid'] = $member_superior['one_id'];
					$one_name = $this->getMemberInfo(array('member_id' => $member_superior['one_id']),'member_name,member_mobile');
					$one_data['sc_membername'] = $one_name['member_name'];
					$one_data['buyer_name'] = $order_info['buyer_name'];
					$one_data['order_sn'] = $order_info['order_sn'];
					$one_data['rebate_amount'] = $one_rebate;
					$one = Model('salescredit')->saveSalescreditLog('rebate',$one_data);
					if($one && $one_name['member_mobile']){
						$sms->send($one_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$one_data['rebate_amount'].'元的推广奖励，详见http://t.cn/RKAEOgL');
					}
				}else if($member_superior['one_id']>0 && $member_superior['two_id']>0 && $member_superior['three_id'] == NULL){
					$rebate_data = array();
					$rebate_data['sc_memberid'] = $member_id;
					$rebate_data['sc_membername'] = $order_info['buyer_name'];
					$rebate_data['buyer_name'] = $order_info['buyer_name'];
					$rebate_data['order_sn'] = $order_info['order_sn'];
					$rebate_data['rebate_amount'] = $rebate + $three_rebate ;
					$re = Model('salescredit')->saveSalescreditLog('order',$rebate_data);
					if($re && $member['member_mobile']){
						$sms->send($member['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$rebate_data['rebate_amount'].'元的消费奖励，详见http://t.cn/RKAEOgL');
					}
					$one_data = array();
					$one_data['sc_memberid'] = $member_superior['one_id'];
					$one_name = $this->getMemberInfo(array('member_id' => $member_superior['one_id']),'member_name,member_mobile');
					$one_data['sc_membername'] = $one_name['member_name'];
					$one_data['buyer_name'] = $order_info['buyer_name'];
					$one_data['order_sn'] = $order_info['order_sn'];
					$one_data['rebate_amount'] = $one_rebate;
					$one = Model('salescredit')->saveSalescreditLog('rebate',$one_data);
					if($one && $one_name['member_mobile']){
						$sms->send($one_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$one_data['rebate_amount'].'元的推广奖励，详见http://t.cn/RKAEOgL');
					}
					$two_data = array();
					$two_data['sc_memberid'] = $member_superior['two_id'];
					$two_name = $this->getMemberInfo(array('member_id' => $member_superior['two_id']),'member_name,member_mobile');
					$two_data['sc_membername'] = $two_name['member_name'];
					$two_data['buyer_name'] = $order_info['buyer_name'];
					$two_data['order_sn'] = $order_info['order_sn'];
					$two_data['rebate_amount'] = $two_rebate;
					$two = Model('salescredit')->saveSalescreditLog('rebate',$two_data);
					if($two && $two_name['member_mobile']){
						$sms->send($two_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$two_data['rebate_amount'].'元的推广奖励，详见http://t.cn/RKAEOgL');
					}
				}else if($member_superior['one_id']>0 && $member_superior['two_id']>0 && $member_superior['three_id']>0){
					$rebate_data = array();
					$rebate_data['sc_memberid'] = $member_id;
					$rebate_data['sc_membername'] = $order_info['buyer_name'];
					$rebate_data['buyer_name'] = $order_info['buyer_name'];
					$rebate_data['order_sn'] = $order_info['order_sn'];
					$rebate_data['rebate_amount'] = $rebate ;
					$re = Model('salescredit')->saveSalescreditLog('order',$rebate_data);
					if($re && $member['member_mobile']){
						$sms->send($member['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$rebate_data['rebate_amount'].'元的消费奖励，详见http://t.cn/RKAEOgL');
					}
					$one_data = array();
					$one_data['sc_memberid'] = $member_superior['one_id'];
					$one_name = $this->getMemberInfo(array('member_id' => $member_superior['one_id']),'member_name,member_mobile');
					$one_data['sc_membername'] = $one_name['member_name'];
					$one_data['buyer_name'] = $order_info['buyer_name'];
					$one_data['order_sn'] = $order_info['order_sn'];
					$one_data['rebate_amount'] = $one_rebate;
					$one = Model('salescredit')->saveSalescreditLog('rebate',$one_data);
					if($one && $one_name['member_mobile']){
						$sms->send($one_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$one_data['rebate_amount'].'元的推广奖励，详见http://t.cn/RKAEOgL');
					}
					$two_data = array();
					$two_data['sc_memberid'] = $member_superior['two_id'];
					$two_name = $this->getMemberInfo(array('member_id' => $member_superior['two_id']),'member_name,member_mobile');
					$two_data['sc_membername'] = $two_name['member_name'];
					$two_data['buyer_name'] = $order_info['buyer_name'];
					$two_data['order_sn'] = $order_info['order_sn'];
					$two_data['rebate_amount'] = $two_rebate;
					$two = Model('salescredit')->saveSalescreditLog('rebate',$two_data);
					if($two && $two_name['member_mobile']){
						$sms->send($two_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$two_data['rebate_amount'].'元的推广奖励，详见http://t.cn/RKAEOgL');
					}
					$three_data = array();
					$three_data['sc_memberid'] = $member_superior['three_id'];
					$three_name = $this->getMemberInfo(array('member_id' => $member_superior['three_id']),'member_name,member_mobile');
					$three_data['sc_membername'] = $three_name['member_name'];
					$three_data['buyer_name'] = $order_info['buyer_name'];
					$three_data['order_sn'] = $order_info['order_sn'];
					$three_data['rebate_amount'] = $three_rebate;
					$three = Model('salescredit')->saveSalescreditLog('rebate',$three_data);
					if($three && $three_name['member_mobile']){
						$sms->send($three_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$three_data['rebate_amount'].'元的推广奖励，详见http://t.cn/RKAEOgL');
					}
				}
			}
			
			//合作方返利
			if($order_info['platform_member_id']>0 && $platform_rebate>0){
				/*分支奖励*/
				$partner = Model('partner')->getPartnerInfo(array('member_id'=>$order_info['platform_member_id']));
				if($partner['one_award']>0 || $partner['two_award']>0){
					$branch = $this->getMemberInfo(array('member_id' => $order_info['buyer_id']),'team_branch');
					$re = explode(',',$branch['team_branch']);
					if($re[0] == $order_info['platform_member_id']){
						if($partner['one_award']>0 && $re[1]){
							$one_award = number_format(($platform_rebate * $partner['one_award']),2);
							$one_award_name = $this->getMemberInfo(array('member_id' => $re[1]),'member_name');
							$one_award_data = array();
							$one_award_data['sc_memberid'] = $re[1];
							$one_award_data['sc_membername'] = $one_award_name['member_name'];
							$one_award_data['buyer_name'] = $order_info['buyer_name'];
							$one_award_data['order_sn'] = $order_info['order_sn'];
							$one_award_data['rebate_amount'] = $one_award ;
							$one_award_data['sc_desc'] = '用户 '.$order_info['buyer_name'].' 完成订单交易 [团队奖励]';
							Model('salescredit')->saveSalescreditLog('rebate',$one_award_data);
						}
						
						if($partner['two_award']>0 && $re[2]){
							$two_award = number_format(($platform_rebate * $partner['two_award']),2);
							$two_award_name = $this->getMemberInfo(array('member_id' => $re[2]),'member_name');
							$two_award_data = array();
							$two_award_data['sc_memberid'] = $re[2];
							$two_award_data['sc_membername'] = $two_award_name['member_name'];
							$two_award_data['buyer_name'] = $order_info['buyer_name'];
							$two_award_data['order_sn'] = $order_info['order_sn'];
							$two_award_data['rebate_amount'] = $two_award ;
							$two_award_data['sc_desc'] = '用户 '.$order_info['buyer_name'].' 完成订单交易 [团队奖励]';
							Model('salescredit')->saveSalescreditLog('rebate',$two_award_data);
						}
					}
				}
				/*分支奖励*/
				$rebate_data = array();
				$rebate_data['sc_memberid'] = $order_info['platform_member_id'];
				$member_name = $this->getMemberInfo(array('member_id' => $order_info['platform_member_id']),'member_name,member_mobile');
				$rebate_data['sc_membername'] = $member_name['member_name'];
				$rebate_data['buyer_name'] = $order_info['buyer_name'];
				$rebate_data['order_sn'] = $order_info['order_sn'];
				$rebate_data['rebate_amount'] = $platform_rebate - $one_award - $two_award;
				$rebate_data['sc_desc'] = '用户 '.$order_info['buyer_name'].' 完成订单交易 [推广提成]';
				$re = Model('salescredit')->saveSalescreditLog('rebate',$rebate_data);
				if($re && $member_name['member_mobile']){
					$sms->send($member_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$rebate_data['rebate_amount'].'元的推广提成，详见http://t.cn/RKAEOgL');
				}
			}
			
			//区域方返利
			if($order_info['area_member_id']>0 && $area_rebate>0){
				$rebate_data = array();
				$rebate_data['sc_memberid'] = $order_info['area_member_id'];
				$member_name = $this->getMemberInfo(array('member_id' => $order_info['area_member_id']),'member_name,member_mobile');
				$rebate_data['sc_membername'] = $member_name['member_name'];
				$rebate_data['buyer_name'] = $order_info['buyer_name'];
				$rebate_data['order_sn'] = $order_info['order_sn'];
				$rebate_data['rebate_amount'] = $area_rebate ;
				$rebate_data['sc_desc'] = '用户 '.$order_info['buyer_name'].' 完成订单交易 [推广提成]';
				$re = Model('salescredit')->saveSalescreditLog('rebate',$rebate_data);
				if($re && $member_name['member_mobile']){
					$sms->send($member_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$rebate_data['rebate_amount'].'元的推广提成，详见http://t.cn/RKAEOgL');
				}
			}
			
		}else if($member['is_seller'] == 1 && $member['is_member_rebate'] == 1 && $is_rebate == 1){
			$rebate_data = array();
			$rebate_data['sc_memberid'] = $member_id;
			$rebate_data['sc_membername'] = $order_info['buyer_name'];
			$rebate_data['buyer_name'] = $order_info['buyer_name'];
			$rebate_data['order_sn'] = $order_info['order_sn'];
			$rebate_data['rebate_amount'] = $rebate;
			$re = Model('salescredit')->saveSalescreditLog('order',$rebate_data);
			if($re && $member['member_mobile']){
				$sms->send($member['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$rebate_data['rebate_amount'].'元的消费奖励，详见http://t.cn/RKAEOgL');
			}
			
		//增加买家还不是销售员的情况下返利流程	
		}else if($member['is_seller'] == 0 && $member['is_rebate'] == 1 && $is_rebate == 1){
			//只有一级返利
			if($one_rebate>0 && $two_rebate==0 && $three_rebate==0){
				if($member_superior['one_id']>0){
					$one_data = array();
					$one_data['sc_memberid'] = $member_superior['one_id'];
					$one_name = $this->getMemberInfo(array('member_id' => $member_superior['one_id']),'member_name,member_mobile');
					$one_data['sc_membername'] = $one_name['member_name'];
					$one_data['buyer_name'] = $order_info['buyer_name'];
					$one_data['order_sn'] = $order_info['order_sn'];
					$one_data['rebate_amount'] = $rebate + $one_rebate;
					$one = Model('salescredit')->saveSalescreditLog('rebate',$one_data);
					if($one && $one_name['member_mobile']){
						$sms->send($one_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$one_data['rebate_amount'].'元的推广奖励，详见http://t.cn/RKAEOgL');
					}
				}
			//一级返利和二级返利
			}else if($one_rebate>0 && $two_rebate>0 && $three_rebate==0){
				if($member_superior['one_id']>0 && $member_superior['two_id'] == NULL){
					$one_data = array();
					$one_data['sc_memberid'] = $member_superior['one_id'];
					$one_name = $this->getMemberInfo(array('member_id' => $member_superior['one_id']),'member_name,member_mobile');
					$one_data['sc_membername'] = $one_name['member_name'];
					$one_data['buyer_name'] = $order_info['buyer_name'];
					$one_data['order_sn'] = $order_info['order_sn'];
					$one_data['rebate_amount'] = $rebate + $one_rebate + $two_rebate;
					$one = Model('salescredit')->saveSalescreditLog('rebate',$one_data);
					if($one && $one_name['member_mobile']){
						$sms->send($one_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$one_data['rebate_amount'].'元的推广奖励，详见http://t.cn/RKAEOgL');
					}
				}else if($member_superior['one_id']>0 && $member_superior['two_id']>0){
					$one_data = array();
					$one_data['sc_memberid'] = $member_superior['one_id'];
					$one_name = $this->getMemberInfo(array('member_id' => $member_superior['one_id']),'member_name,member_mobile');
					$one_data['sc_membername'] = $one_name['member_name'];
					$one_data['buyer_name'] = $order_info['buyer_name'];
					$one_data['order_sn'] = $order_info['order_sn'];
					$one_data['rebate_amount'] = $rebate + $one_rebate;
					$one = Model('salescredit')->saveSalescreditLog('rebate',$one_data);
					if($one && $one_name['member_mobile']){
						$sms->send($one_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$one_data['rebate_amount'].'元的推广奖励，详见http://t.cn/RKAEOgL');
					}
					$two_data = array();
					$two_data['sc_memberid'] = $member_superior['two_id'];
					$two_name = $this->getMemberInfo(array('member_id' => $member_superior['two_id']),'member_name,member_mobile');
					$two_data['sc_membername'] = $two_name['member_name'];
					$two_data['buyer_name'] = $order_info['buyer_name'];
					$two_data['order_sn'] = $order_info['order_sn'];
					$two_data['rebate_amount'] = $two_rebate;
					$two = Model('salescredit')->saveSalescreditLog('rebate',$two_data);
					if($two && $two_name['member_mobile']){
						$sms->send($two_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$two_data['rebate_amount'].'元的推广奖励，详见http://t.cn/RKAEOgL');
					}
				}
			//三级返利都有
			}else if($one_rebate>0 && $two_rebate>0 && $three_rebate>0){
				if($member_superior['one_id']>0 && $member_superior['two_id'] == NULL && $member_superior['three_id'] == NULL){
					$one_data = array();
					$one_data['sc_memberid'] = $member_superior['one_id'];
					$one_name = $this->getMemberInfo(array('member_id' => $member_superior['one_id']),'member_name,member_mobile');
					$one_data['sc_membername'] = $one_name['member_name'];
					$one_data['buyer_name'] = $order_info['buyer_name'];
					$one_data['order_sn'] = $order_info['order_sn'];
					$one_data['rebate_amount'] = $rebate + $one_rebate + $two_rebate + $three_rebate;
					$one = Model('salescredit')->saveSalescreditLog('rebate',$one_data);
					if($one && $one_name['member_mobile']){
						$sms->send($one_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$one_data['rebate_amount'].'元的推广奖励，详见http://t.cn/RKAEOgL');
					}
				}else if($member_superior['one_id']>0 && $member_superior['two_id']>0 && $member_superior['three_id'] == NULL){
					$one_data = array();
					$one_data['sc_memberid'] = $member_superior['one_id'];
					$one_name = $this->getMemberInfo(array('member_id' => $member_superior['one_id']),'member_name,member_mobile');
					$one_data['sc_membername'] = $one_name['member_name'];
					$one_data['buyer_name'] = $order_info['buyer_name'];
					$one_data['order_sn'] = $order_info['order_sn'];
					$one_data['rebate_amount'] = $rebate + $one_rebate + $three_rebate;
					$one = Model('salescredit')->saveSalescreditLog('rebate',$one_data);
					if($one && $one_name['member_mobile']){
						$sms->send($one_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$one_data['rebate_amount'].'元的推广奖励，详见http://t.cn/RKAEOgL');
					}
					$two_data = array();
					$two_data['sc_memberid'] = $member_superior['two_id'];
					$two_name = $this->getMemberInfo(array('member_id' => $member_superior['two_id']),'member_name,member_mobile');
					$two_data['sc_membername'] = $two_name['member_name'];
					$two_data['buyer_name'] = $order_info['buyer_name'];
					$two_data['order_sn'] = $order_info['order_sn'];
					$two_data['rebate_amount'] = $two_rebate;
					$two = Model('salescredit')->saveSalescreditLog('rebate',$two_data);
					if($two && $two_name['member_mobile']){
						$sms->send($two_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$two_data['rebate_amount'].'元的推广奖励，详见http://t.cn/RKAEOgL');
					}
				}else if($member_superior['one_id']>0 && $member_superior['two_id']>0 && $member_superior['three_id']>0){
					$one_data = array();
					$one_data['sc_memberid'] = $member_superior['one_id'];
					$one_name = $this->getMemberInfo(array('member_id' => $member_superior['one_id']),'member_name,member_mobile');
					$one_data['sc_membername'] = $one_name['member_name'];
					$one_data['buyer_name'] = $order_info['buyer_name'];
					$one_data['order_sn'] = $order_info['order_sn'];
					$one_data['rebate_amount'] = $rebate + $one_rebate;
					$one = Model('salescredit')->saveSalescreditLog('rebate',$one_data);
					if($one && $one_name['member_mobile']){
						$sms->send($one_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$one_data['rebate_amount'].'元的推广奖励，详见http://t.cn/RKAEOgL');
					}
					$two_data = array();
					$two_data['sc_memberid'] = $member_superior['two_id'];
					$two_name = $this->getMemberInfo(array('member_id' => $member_superior['two_id']),'member_name,member_mobile');
					$two_data['sc_membername'] = $two_name['member_name'];
					$two_data['buyer_name'] = $order_info['buyer_name'];
					$two_data['order_sn'] = $order_info['order_sn'];
					$two_data['rebate_amount'] = $two_rebate;
					$two = Model('salescredit')->saveSalescreditLog('rebate',$two_data);
					if($two && $two_name['member_mobile']){
						$sms->send($two_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$two_data['rebate_amount'].'元的推广奖励，详见http://t.cn/RKAEOgL');
					}
					$three_data = array();
					$three_data['sc_memberid'] = $member_superior['three_id'];
					$three_name = $this->getMemberInfo(array('member_id' => $member_superior['three_id']),'member_name,member_mobile');
					$three_data['sc_membername'] = $three_name['member_name'];
					$three_data['buyer_name'] = $order_info['buyer_name'];
					$three_data['order_sn'] = $order_info['order_sn'];
					$three_data['rebate_amount'] = $three_rebate;
					$three = Model('salescredit')->saveSalescreditLog('rebate',$three_data);
					if($three && $three_name['member_mobile']){
						$sms->send($three_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$three_data['rebate_amount'].'元的推广奖励，详见http://t.cn/RKAEOgL');
					}
				}
			}
			
			//合作方返利
			if($order_info['platform_member_id']>0 && $platform_rebate>0){
				/*分支奖励*/
				$partner = Model('partner')->getPartnerInfo(array('member_id'=>$order_info['platform_member_id']));
				if($partner['one_award']>0 || $partner['two_award']>0){
					$branch = $this->getMemberInfo(array('member_id' => $order_info['buyer_id']),'team_branch');
					$re = explode(',',$branch['team_branch']);
					if($re[0] == $order_info['platform_member_id']){
						if($partner['one_award']>0 && $re[1]){
							$one_award = number_format(($platform_rebate * $partner['one_award']),2);
							$one_award_name = $this->getMemberInfo(array('member_id' => $re[1]),'member_name');
							$one_award_data = array();
							$one_award_data['sc_memberid'] = $re[1];
							$one_award_data['sc_membername'] = $one_award_name['member_name'];
							$one_award_data['buyer_name'] = $order_info['buyer_name'];
							$one_award_data['order_sn'] = $order_info['order_sn'];
							$one_award_data['rebate_amount'] = $one_award ;
							$one_award_data['sc_desc'] = '用户 '.$order_info['buyer_name'].' 完成订单交易 [团队奖励]';
							Model('salescredit')->saveSalescreditLog('rebate',$one_award_data);
						}
						
						if($partner['two_award']>0 && $re[2]){
							$two_award = number_format(($platform_rebate * $partner['two_award']),2);
							$two_award_name = $this->getMemberInfo(array('member_id' => $re[2]),'member_name');
							$two_award_data = array();
							$two_award_data['sc_memberid'] = $re[2];
							$two_award_data['sc_membername'] = $two_award_name['member_name'];
							$two_award_data['buyer_name'] = $order_info['buyer_name'];
							$two_award_data['order_sn'] = $order_info['order_sn'];
							$two_award_data['rebate_amount'] = $two_award ;
							$two_award_data['sc_desc'] = '用户 '.$order_info['buyer_name'].' 完成订单交易 [团队奖励]';
							Model('salescredit')->saveSalescreditLog('rebate',$two_award_data);
						}
					}
				}
				/*分支奖励*/
				$rebate_data = array();
				$rebate_data['sc_memberid'] = $order_info['platform_member_id'];
				$member_name = $this->getMemberInfo(array('member_id' => $order_info['platform_member_id']),'member_name,member_mobile');
				$rebate_data['sc_membername'] = $member_name['member_name'];
				$rebate_data['buyer_name'] = $order_info['buyer_name'];
				$rebate_data['order_sn'] = $order_info['order_sn'];
				$rebate_data['rebate_amount'] = $platform_rebate - $one_award - $two_award;
				$rebate_data['sc_desc'] = '用户 '.$order_info['buyer_name'].' 完成订单交易 [推广提成]';
				$re = Model('salescredit')->saveSalescreditLog('rebate',$rebate_data);
				if($re && $member_name['member_mobile']){
					$sms->send($member_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$rebate_data['rebate_amount'].'元的推广提成，详见http://t.cn/RKAEOgL');
				}
			}
			
			//区域方返利
			if($order_info['area_member_id']>0 && $area_rebate>0){
				$rebate_data = array();
				$rebate_data['sc_memberid'] = $order_info['area_member_id'];
				$member_name = $this->getMemberInfo(array('member_id' => $order_info['area_member_id']),'member_name,member_mobile');
				$rebate_data['sc_membername'] = $member_name['member_name'];
				$rebate_data['buyer_name'] = $order_info['buyer_name'];
				$rebate_data['order_sn'] = $order_info['order_sn'];
				$rebate_data['rebate_amount'] = $area_rebate ;
				$rebate_data['sc_desc'] = '用户 '.$order_info['buyer_name'].' 完成订单交易 [推广提成]';
				$re = Model('salescredit')->saveSalescreditLog('rebate',$rebate_data);
				if($re && $member_name['member_mobile']){
					$sms->send($member_name['member_mobile'],'用户['.$order_info['buyer_name'].']实现订单交易，您已获得'.$rebate_data['rebate_amount'].'元的推广提成，详见http://t.cn/RKAEOgL');
				}
			}
		}
	 }
	 
	 /**
	  * 得到当前用户的信息
	  */
	 public function superior_member($member_id){
	 	$member = Model()->table('member')->where($member_id)->select();
		return $member;
	 }
	 
	/*
	 *得到上级用户的信息 
	 */ 
	public function ref_id($member_id){
		$ref_id = Model()->table('member')->where($member_id)->select();
		return $ref_id;
	}
	 
	public function model_achievement($sc_memberid){
		$sc_money = Model()->table('salescredit_log')->where($sc_memberid)->order('sc_memberid desc')->select();
		$sc_points = array();
		$sc_withdrawals =array();
		foreach($sc_money as $v => $s){
			if($s['sc_stage'] == 'rebate' || $s['sc_stage'] == 'system'){ 
				$sc_points[] = $s['sc_points'];
			}
			if($s['sc_stage'] == 'salerebate'){
				$sc_withdrawals[] = $s['sc_points'];
			}
		}
		$v = array();
		$v['sc_points'] = array_sum($sc_points);
		$v['sc_withdrawal'] = abs(array_sum($sc_withdrawals));
		return $v;
		
	}
	
	/**
	 * 用户收益（当天订单数和收益）
	 * 
	 */
	public function member_earnings($sc_memberid){
		$sales_list = Model()->table('salescredit_log')->where($sc_memberid)->order('sc_memberid desc')->select();
		$income_info = array();
		$i = 0;
		if(!empty($sales_list) && is_array($sales_list)){
			foreach($sales_list as $key => $val){
				$sc_addtime = date('Ymd' ,$val['sc_addtime']);
				$time = date('Ymd' ,time());
				//当天收入
				if($sc_addtime == $time && ($val['sc_stage'] == 'rebate' || $val['sc_stage'] == 'system' || $val['sc_stage'] == 'order')){
					$income_info['income_today'] += $val['sc_points'];
				}
				//当天订单数
				$desc = strpos($val['sc_desc'],'团队奖励');
				if($sc_addtime == $time && ($val['sc_stage'] == 'rebate' || $val['sc_stage'] == 'order') && !$desc){
					$i++;
				}
			}
			if(!$income_info['income_today']){
				$income_info['income_today'] = 0 ;
			}
		}else{
			$income_info['income_today'] = 0 ;
		}
		$income_info['order_num'] = $i;
		return $income_info;
	}
	
	/**
	 * 通过区域，sa_id 查询到一个团队成员
	 * 
	 */
	
	public function sa_id($sa_id){
		return Model()->table('member')->where($sa_id)->select(); 
	} 
	 
	/**
	 * 根据ref_id 查询到全部二维码下面的会员
	 */ 
	public function refer_id($member){
		return Model()->table('member')->where($member)->limit(10000)->select(); 
	} 
	 
	/**
     * 更新二维码销售员店铺简介
     * @param array $condition
     * @param array $data
     */
    public function editShopBrief($condition, $data) {
        $update = $this->table('open_salesman')->where($condition)->update($data);
        return $update;
    } 
	
	/**
     * 获取平台合作ID
     */
    public function getPartnerId($condition, $field = '*') {
        return $this->table('partner')->field($field)->where($condition)->find();
    }
}
