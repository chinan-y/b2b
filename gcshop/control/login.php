<?php
/**
 * 前台登录 退出操作
 *
 ***/


defined('GcWebShop') or exit('Access Invalid!');

class loginControl extends BaseHomeControl {

	public function __construct(){
		parent::__construct();
		Tpl::output('hidden_nctoolbar', 1);
	}

	/**
	 * 登录操作
	 *
	 */
	public function indexOp(){
		
		Language::read("home_login_index");
		$lang	= Language::getLangContent();
		$model_member	= Model('member');

		//记录销售来源平台  by liu
		$saleplat=intval($_GET['saleplat']);
		if($saleplat>0)
		{
		  setcookie('saleplat', $saleplat);
		}

		//检查登录状态
		$model_member->checkloginMember();
		if ($_GET['inajax'] == 1 && C('captcha_status_login') == '1'){
		    $script = "document.getElementById('codeimage').src='".APP_SITE_URL."/index.php?gct=seccode&gp=makecode&nchash=".getNchash()."&t=' + Math.random();";
		}
		$result = chksubmit(true,C('captcha_status_login'),'num');
		if ($result !== false){
			if ($result === -11){
				showDialog($lang['login_index_login_illegal'],'','error',$script);
			}elseif ($result === -12){
				showDialog($lang['login_index_wrong_checkcode'],'','error',$script);
			}
			if (process::islock('login')) {
				showDialog($lang['nc_common_op_repeat'],SHOP_SITE_URL,'','error',$script);
			}
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_POST["user_name"],		"require"=>"true", "message"=>$lang['login_index_username_isnull']),
				array("input"=>$_POST["password"],		"require"=>"true", "message"=>$lang['login_index_password_isnull']),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
			    showDialog($error,SHOP_SITE_URL,'error',$script);
			}

			//用户名、手机、电子邮箱任意三选一登录
			$where=array();
			$where['member_name']	=$_POST['user_name'];
			$where['member_mobile']	=$_POST['user_name'];
			$where['member_email']	=$_POST['user_name'];
			$where['_op']='OR';
			$member_subtxt = $model_member->where($where)->find();
			
			if($member_subtxt['member_name']==$_POST['user_name'])
			{
				$array	= array();
				$array['member_name']	= $_POST['user_name'];
				$array['member_passwd']	= md5($_POST['password']);
				$member_info = $model_member->getMemberInfo($array);
			}
			elseif($member_subtxt['member_mobile']==$_POST['user_name'])
			{			
				$array	= array();
				$array['member_mobile']	= $_POST['user_name'];
				$array['member_passwd']	= md5($_POST['password']);
				$member_info = $model_member->getMemberInfo($array);
			}
			elseif($member_subtxt['member_email']==$_POST['user_name'])
			{			
				$array	= array();
				$array['member_email']	= $_POST['user_name'];
				$array['member_passwd']	= md5($_POST['password']);
				$member_info = $model_member->getMemberInfo($array);
			}else{
				showDialog($lang['login_index_login_fail'],''.'error',$script);
				}
			//用户名、手机、电子邮箱任意三选一登结束

			if(is_array($member_info) and !empty($member_info)) {
				if(!$member_info['member_state']){
			        showDialog($lang['login_index_account_stop'],''.'error',$script);
				}
			}else{
			    process::addprocess('login');
			    showDialog($lang['login_index_login_fail'],'','error',$script);
			}
    		$model_member->createSession($member_info);
			process::clear('login');

			// cookie中的cart存入数据库
			Model('cart')->mergecart($member_info,$_SESSION['store_id']);

			// cookie中的浏览记录存入数据库
			Model('goods_browse')->mergebrowse($_SESSION['member_id'],$_SESSION['store_id']);

			//增加登录界面验证手机绑定 by liu
			if(($member_info['member_mobile_bind'] == '0')  && ($member_info['member_email_bind'] == '0'))
				{
				showDialog('请先绑定手机号码或电子邮箱，便于找回密码和查询订单信息！','index.php?gct=member_security&gp=auth&type=modify_mobile',$update ? 'succ' : 'error');
				//redirect('index.php?gct=member_security&gp=auth&type=modify_mobile');
				
				}


			if ($_GET['inajax'] == 1){
			    showDialog('',$_POST['ref_url'] == '' ? 'reload' : $_POST['ref_url'],'js');
			} else {
			    redirect($_POST['ref_url']);
			}

		}else{

			//登录表单页面
			$_pic = @unserialize(C('login_pic'));
			if ($_pic[0] != ''){
				Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.$_pic[array_rand($_pic)]);
			}else{
				Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.rand(1,4).'.jpg');
			}

			if(empty($_GET['ref_url'])) {
			    $ref_url = getReferer();
			    if (!preg_match('/gct=login&gp=logout/', $ref_url)) {
			     $_GET['ref_url'] = $ref_url;
			    }
			}
			Tpl::output('html_title',C('site_name').' - '.$lang['login_index_login']);
			if ($_GET['inajax'] == 1){
				Tpl::showpage('login_inajax','null_layout');
			}else{
				Tpl::showpage('login');
			}
		}
	}
	
	/**
	 * 登录会员名检测
	 *
	 * @param
	 * @return
	 */
	public function check_nameOp(){
		$model_member	= Model('member');
		$where=array();
		$where['member_name']	=$_POST['user_name'];
		$where['member_mobile']	=$_POST['user_name'];
		$where['member_email']	=$_POST['user_name'];
		$where['_op']='OR';
		$member_subtxt = $model_member->where($where)->find();
		
		if($member_subtxt['member_name']==$_POST['user_name'])
		{
			$array	= array();
			$array['member_name']	= $_POST['user_name'];
			$member_name = $model_member->getMemberInfo($array);
		}
		elseif($member_subtxt['member_mobile']==$_POST['user_name'])
		{			
			$array	= array();
			$array['member_mobile']	= $_POST['user_name'];
			$member_name = $model_member->getMemberInfo($array);
		}
		elseif($member_subtxt['member_email']==$_POST['user_name'])
		{			
			$array	= array();
			$array['member_email']	= $_POST['user_name'];
			$member_name = $model_member->getMemberInfo($array);
		}
		
		if($member_name){
			exit('true');
		}else{
			exit('false');
		}
	}
	
	/**
	 * 登录密码检测
	 *
	 * @param
	 * @return
	 */
	public function check_passwordOp(){
		$model_member	= Model('member');
		$where=array();
		$where['member_name']	=$_POST['user_name'];
		$where['member_mobile']	=$_POST['user_name'];
		$where['member_email']	=$_POST['user_name'];
		$where['_op']='OR';
		$member_subtxt = $model_member->where($where)->find();
		
		if($member_subtxt['member_name']==$_POST['user_name'] && $member_subtxt['member_passwd']== md5($_POST['password']))
		{
			$array	= array();
			$array['member_name']	= $_POST['user_name'];
			$array['member_passwd']	= md5($_POST['password']);
			$member_passwd = $model_member->getMemberInfo($array);
		}
		elseif($member_subtxt['member_mobile']==$_POST['user_name'] && $member_subtxt['member_passwd']== md5($_POST['password']))
		{			
			$array	= array();
			$array['member_mobile']	= $_POST['user_name'];
			$array['member_passwd']	= md5($_POST['password']);
			$member_passwd = $model_member->getMemberInfo($array);
		}
		elseif($member_subtxt['member_email']==$_POST['user_name'] && $member_subtxt['member_passwd']== md5($_POST['password']))
		{			
			$array	= array();
			$array['member_email']	= $_POST['user_name'];
			$array['member_passwd']	= md5($_POST['password']);
			$member_passwd = $model_member->getMemberInfo($array);
		}
		if($member_passwd){
			exit('true');
		}else{
			exit('false');
		}
	}
	
	/**
	 * 退出操作
	 *
	 * @param int $id 记录ID
	 * @return array $rs_row 返回数组形式的查询结果
	 */
	public function logoutOp(){
		Language::read("home_login_index");
		$lang	= Language::getLangContent();
		// 清理消息COOKIE
		setNcCookie('msgnewnum'.$_SESSION['member_id'],'',-3600);
		session_unset();
		session_destroy();
		setNcCookie('cart_goods_num','',-3600);
		if(empty($_GET['ref_url'])){
			$ref_url = getReferer();
		}else {
			$ref_url = $_GET['ref_url'];
		}
		redirect('index.php?gct=login&ref_url='.urlencode($ref_url));
	}
	
	/**
	 * 会员签到送积分
	 *
	 */
	public function signOp(){
		$member_info = Model('member')->getMemberInfo(array('member_id'=>$_SESSION['member_id']),'member_sign_time');
	    if(trim(@date('Y-m-d',$member_info['member_sign_time'])) == trim(date('Y-m-d'))){
			showDialog('您今天已经签到了','index.php?gct=member_points&gp=index','error');
			return;
		}else{
			$re = Model('points')->savePointsLog('sign',array('pl_memberid'=>$_SESSION['member_id'],'pl_membername'=>$_SESSION['member_name']));
			if($re){
				showDialog('签到成功  增加'.intval(C('points_sign')).'积分','index.php?gct=member_points&gp=index','succ');
			}
		} 
	}

	/**
	 * 会员注册页面
	 *
	 * @param
	 * @return
	 */
	public function registerOp() {
	    $left_time = cookie('left_time'.$nchash);
        if(TIMESTAMP - $left_time < 60){
            Tpl::output('left_time', 60 - (TIMESTAMP - $left_time));
        }

		//记录推荐人
		$zmr=intval($_GET['zmr']);
		if($zmr>0){
		  setcookie('zmr', $zmr);
		}
		
		//记录上级用户
		$refer=intval($_GET['ref']);
		if($refer>0){
		  setcookie('ref', $refer);
		}

		//记录销售来源平台 
		$saleplat=intval($_GET['saleplat']);
		if($saleplat>0){
		  setcookie('saleplat', $saleplat);
		}
		
		$_pic = @unserialize(C('login_pic'));
		if ($_pic[0] != ''){
			Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.$_pic[array_rand($_pic)]);
		}else{
			Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.rand(1,4).'.jpg');
		}
		Language::read("home_login_register");
		$lang	= Language::getLangContent();
		$model_member	= Model('member');
		$model_member->checkloginMember();
		Tpl::output('html_title',C('site_name').' - '.$lang['login_register_join_us']);
		Tpl::showpage('register');
	}


    public function  normal(&$register_info){
		//重复注册验证
		if (process::islock('reg')){
			showDialog(Language::get('nc_common_op_repeat'));
		}
		Language::read("home_login_register");
		$lang	= Language::getLangContent();
		$model_member	= Model('member');
		$model_member->checkloginMember();
		
		//添加奖励积分
		$zmr=intval($_COOKIE['zmr']);
		if($zmr>0){
			$pinfo=$model_member->getMemberInfoByID($zmr,'member_id');
			if(empty($pinfo)){
				$zmr=0;
			}
		}
		$register_info['inviter_id'] = $zmr;

		//添加分销平台ID:优品正源ID=11
		$saleplat=intval($_COOKIE['saleplat']);
		/* if($saleplat>0)
		{
			$saleplatinfo=$model_member->getMemberInfoByID($saleplat,'member_id');
			if(empty($saleplatinfo))
			{
				$saleplat=0;
			}
		} */
		$register_info['saleplat_id'] = $saleplat;
		
		//绑定专属销售员
		$refer=intval($_COOKIE['ref']);
		if($refer>0){
			$refinfo=$model_member->getMemberInfoByID($refer,'member_id');
			if(empty($refinfo)){
				$refer=0;
			}else {
				$refer = $refinfo['member_id'];
			}	
		}
		$register_info['refer_id'] = $refer;

        $member_info = $model_member->register($register_info);
        if(!isset($member_info['error'])) {
            $model_member->createSession($member_info,true);
			process::addprocess('reg');

			// cookie中的cart存入数据库
			Model('cart')->mergecart($member_info,$_SESSION['store_id']);

			// cookie中的浏览记录存入数据库
			Model('goods_browse')->mergebrowse($_SESSION['member_id'],$_SESSION['store_id']);
			
			/*注册后赠送免费代金券给用户*/
			$model_voucher = Model('voucher');
			$store_id=0;
			$member_id= $_SESSION['member_id'];
			$member_name= $_SESSION['member_name'];
			$voucher_list = $model_voucher->getRegisterTemplate(10); 
			foreach($voucher_list as $val){
				$voucher_t_id[]= $val['voucher_t_id'];
			}
			if($voucher_t_id){
				$voucher_t_id = array_unique($voucher_t_id);
				$voucher_t_id = implode(',',$voucher_t_id);
			}
			//验证是否可以兑换代金券
			$re = $model_voucher->getFreeVoucherInfo(array('voucher_t_id'=>array('in',$voucher_t_id)),$member_id,$store_id);
			if($re['state'] == true){
				//添加代金券信息
				$data = $model_voucher->exFreeVoucher($re['info'],$member_id,$member_name);
				if($data['state'] == true){
					// 发送新注册会员赠送代金券消息
					$param = array();
					$param['code'] = 'push_member_voucher';
					$param['member_id'] = $_SESSION['member_id'];
					QueueClient::push('sendMemberMsg', $param);
				}
			}
			/*注册后需要提交资料审核*/
			$_POST['ref_url']	= 'index.php?gct=login&gp=member_verify';
			redirect($_POST['ref_url']);

        } else {
			showDialog($member_info['error']);
        }
	}
	
	/**
	 * 会员验证
	 *
	 */
	public function member_verifyOp(){
		Language::read("home_login_register");
		$_pic = @unserialize(C('login_pic'));
		if ($_pic[0] != ''){
			Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.$_pic[array_rand($_pic)]);
		}else{
			Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.rand(1,4).'.jpg');
		}
		Tpl::showpage('member_verify'); 
	}
	
	/**
	 * 提交验证
	 *
	 */
	public function submit_verifyOp(){
		Language::read("home_login_register");
		$member_id = $_SESSION['member_id'];
		if(is_uploaded_file($_FILES['license']['tmp_name'])){ 
			$upfile    = $_FILES['license'];
			$name      = $upfile["name"];
			$_name     = strchr($name,'.');
			$size      = $upfile["size"];
			if(($size/1024)>2000){
				showDialog('图片不能大于2M');
				TPL::showpage('member_verify');
			}
			$tmp_name  = $upfile["tmp_name"];
			$image = move_uploaded_file($tmp_name,BASE_DATA_PATH.DS.'upload'.DS.'gcshop'.DS.'member'.DS.'license'.DS.$member_id.'_license'.$_name);
			if($image){
				$data = array();
				$data['member_company_name'] = $_POST['company_name'];
				$data['member_license'] = '/data'.DS.'upload'.DS.'gcshop'.DS.'member'.DS.'license'.DS.$_SESSION['member_id'].'_license'.$_name;
				$re = Model('member')->editMember(array('member_id'=>$member_id),$data);
				if($re){
					$_pic = @unserialize(C('login_pic'));
					if ($_pic[0] != ''){
						Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.$_pic[array_rand($_pic)]);
					}else{
						Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.rand(1,4).'.jpg');
					}
					Tpl::showpage('member_await_verify');
				}
			}
		}
	}
	
	/**
	 * 等待审核
	 *
	 */
	public function await_verifyOp(){
		Language::read("home_login_register");
		$_pic = @unserialize(C('login_pic'));
		if ($_pic[0] != ''){
			Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.$_pic[array_rand($_pic)]);
		}else{
			Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.rand(1,4).'.jpg');
		}
		Tpl::showpage('member_await_verify');
	}
	
	/**
	 * 会员添加操作
	 *
	 * @param
	 * @return
	 */
	public function usersaveOp() {
		
		$result = chksubmit(true,C('captcha_status_register'),'num');
		if ($result){
			if ($result === -11){
				showDialog($lang['invalid_request'],'','error');
			}
			/* elseif ($result === -12){
				showDialog($lang['login_usersave_wrong_code'],'','error');
			} */
		} else {
		    showDialog($lang['invalid_request'],'','error');
		}
        $register_info = array();
		$register_info['username'] = 'gb'.TIMESTAMP;
        $register_info['password'] = $_POST['password'];
		$register_info['mobile']   = $_POST['mobile'];
		$register_info['is_membername_modify'] = 1;
		$register_info['member_mobile_bind'] = 1;
		$register_info['ref_url'] = $_POST['ref_url'];
		$mobile_captcha = $_POST['mobile_captcha'];
        $verify_code = $_SESSION['verify_code'];
		
        if($mobile_captcha != $verify_code || empty($verify_code)){
           showDialog('手机验证码输入有误，请重新输入');
        }
		unset($_SESSION['verify_code']);
        
		if($register_info['mobile'] != $_SESSION["mobile"]){
		    showDialog('对不起，您输入的手机号与获取验证码的手机号码不匹配！');
		}
		
		$this->normal($register_info);
	}
	
	public function usernamesaveOp() {
        $register_info = array();
        $register_info['username'] = $_POST['user_name'];
		$register_info['mobile'] = $_POST['mobile_username'];
        $register_info['password'] = $_POST['username_password']; 
		$register_info['is_membername_modify'] = 1;
		$register_info['member_mobile_bind'] = 1; 
		$register_info['ref_url'] = $_POST['ref_url'];

        $this->normal($register_info);
		
	}
	
	public function emailOp() {
        $register_info = array();   
        $register_info['username'] = $_POST['email'];	
        $register_info['password'] = $_POST['email_password'];
		$register_info['email'] = $_POST['email'];
		$register_info['is_membername_modify'] = 1;
		$register_info['member_mobile_bind'] = 0; 
		$register_info['ref_url'] = $_POST['ref_url'];
		$verify_code = $_SESSION['verify_code'];
        if($_POST['emailCaptcha'] != $verify_code || empty($verify_code)){
           showDialog('邮件验证码输入有误，请重新输入');
        }
        $this->normal($register_info);
		
	}
	/**
	 * 会员名称检测
	 *
	 * @param
	 * @return
	 */
	public function check_memberOp() {
			/**
		 	* 实例化模型
		 	*/
			$model_member	= Model('member');

			$check_member_name	= $model_member->getMemberInfo(array('member_name'=>$_GET['user_name']));
			if(is_array($check_member_name) and count($check_member_name)>0) {
				echo 'false';
			} else {
				echo 'true';
			}
	}
	
	/**
	 * 会员密码检测
	 *
	 * @param
	 * @return
	 */
	public function check_passwdOp() {
			/**
		 	* 实例化模型
		 	*/
			$model_member	= Model('member');
			$check_member_passwd	= $model_member->getMemberInfo(array('member_passwd'=>md5($_POST(['password']))));
			if(is_array($check_member_passwd) and count($check_member_passwd)>0) {
				echo 'false';
			} else {
				echo 'true';
			}
	}

	/**
	 * 电子邮箱检测
	 *
	 * @param
	 * @return
	 */
	public function check_emailOp() {
		$model_member = Model('member');
		$check_member_email	= $model_member->getMemberInfo(array('member_email'=>$_GET['email']));
		if(is_array($check_member_email) and count($check_member_email)>0) {
			echo 'false';
		} else {
			echo 'true';
		}
	}

	/**
	 * 手机号码检测
	 *
	 * @param
	 * @return
	 */
	public function check_mobileOp() {
		$model_member = Model('member');
		$check_member_mobile= $model_member->getMemberInfo(array('member_mobile'=>$_GET['mobile']));
		if(is_array($check_member_mobile) and count($check_member_mobile)>0) {
			echo 'false';
		} else {
			echo 'true';
		}
	}

	/**
	 * 忘记密码页面
	 */
	public function forget_passwordOp(){
		/**
		 * 读取语言包
		 */
		Language::read('home_login_register');
		$_pic = @unserialize(C('login_pic'));
		if ($_pic[0] != ''){
			Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.$_pic[array_rand($_pic)]);
		}else{
			Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.rand(1,4).'.jpg');
		}
		Tpl::output('html_title',C('site_name').' - '.Language::get('login_index_find_password'));
		Tpl::showpage('find_password');
	}

	/**
	 * 找回密码的发邮件处理
	 */
	public function find_passwordOp(){
		Language::read('home_login_register');
		$lang	= Language::getLangContent();

		$result = chksubmit(true,true,'num');
		if ($result !== false){
		    if ($result === -11){
		        showDialog('非法提交');
		    }elseif ($result === -12){
		        showDialog('验证码错误');
		    }
		}

		if(empty($_POST['username'])){
			showDialog($lang['login_password_input_username']);
		}

		if (process::islock('forget')) {
		    showDialog($lang['nc_common_op_repeat'],'reload');
		}

		$member_model	= Model('member');
		$member	= $member_model->getMemberInfo(array('member_name'=>$_POST['username']));
		if(empty($member) or !is_array($member)){
		    process::addprocess('forget');
			showDialog($lang['login_password_username_not_exists'],'reload');
		}

		if(empty($_POST['email'])){
			showDialog($lang['login_password_input_email'],'reload');
		}

		if(strtoupper($_POST['email'])!=strtoupper($member['member_email'])){
		    process::addprocess('forget');
			showDialog($lang['login_password_email_not_exists'],'reload');
		}
		process::clear('forget');
		//产生密码
		$new_password	= random(15);
		if(!($member_model->editMember(array('member_id'=>$member['member_id']),array('member_passwd'=>md5($new_password))))){
			showDialog($lang['login_password_email_fail'],'reload');
		}

		$model_tpl = Model('mail_templates');
		$tpl_info = $model_tpl->getTplInfo(array('code'=>'reset_pwd'));
		$param = array();
		$param['site_name']	= C('site_name');
		$param['user_name'] = $_POST['username'];
		$param['new_password'] = $new_password;
		$param['site_url'] = SHOP_SITE_URL;
		$subject	= ncReplaceText($tpl_info['title'],$param);
		$message	= ncReplaceText($tpl_info['content'],$param);

		$email	= new Email();
		$result	= $email->send_sys_email($_POST["email"],$subject,$message);
		showDialog('新密码已经发送至您的邮箱，请尽快登录并更改密码！','index.php?gct=login&gp=index','succ','',4);
	}
	
	/**
	 * 手机号找回密码
	 */
	public function mobile_find_passwordOp(){
		$member_model	= Model('member');
		$param = array();
		$param['mobile'] = $_POST['mobile']; 
		$param['password'] = $_POST['password']; 
		$param['verify_code'] = $_POST['mobile_captcha'];
		
		if($param['verify_code'] != $_SESSION['verify_code'] || empty($_SESSION['verify_code'])){
			showDialog('验证码错误，请重新输入');
        }
		unset($_SESSION['verify_code']);
        if($param['mobile'] != $_SESSION['mobile']){
            showDialog('手机与验证码不一致，请重新输入');
        }
        $re = $member_model->change($param);
        if($re) {
			showDialog('密码修改成功,请用新密码登录','index.php?gct=login&gp=index','succ','',4);
        } else {
			showDialog("找回失败,请确认是否绑定手机");
        }
	}

	/**
	 * 邮箱绑定验证
	 */
	public function bind_emailOp() {
	   $model_member = Model('member');
	   $uid = @base64_decode($_GET['uid']);
	   $uid = decrypt($uid,'');
	   list($member_id,$member_email) = explode(' ', $uid);

	   if (!is_numeric($member_id)) {
	       showMessage('验证失败',SHOP_SITE_URL,'html','error');
	   }

	   $member_info = $model_member->getMemberInfo(array('member_id'=>$member_id),'member_email');
	   if ($member_info['member_email'] != $member_email) {
	       showMessage('验证失败',SHOP_SITE_URL,'html','error');
	   }

	   $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$member_id));
	   if (empty($member_common_info) || !is_array($member_common_info)) {
	       showMessage('验证失败',SHOP_SITE_URL,'html','error');
	   }
	   if (md5($member_common_info['auth_code']) != $_GET['hash'] || TIMESTAMP - $member_common_info['send_acode_time'] > 24*3600) {
	       showMessage('验证失败',SHOP_SITE_URL,'html','error');
	   }

	   $update = $model_member->editMember(array('member_id'=>$member_id),array('member_email_bind'=>1));
	   if (!$update) {
	       showMessage('系统发生错误，如有疑问请与管理员联系',SHOP_SITE_URL,'html','error');
	   }

	   $data = array();
	   $data['auth_code'] = '';
	   $data['send_acode_time'] = 0;
	   $update = $model_member->editMemberCommon($data,array('member_id'=>$_SESSION['member_id']));
	   if (!$update) {
	       showDialog('系统发生错误，如有疑问请与管理员联系');
	   }
	   showMessage('邮箱设置成功','index.php?gct=member_security&gp=index');

	}
}
