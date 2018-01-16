<?php
/**
 * 验证码
 ***/


defined('GcWebShop') or exit('Access Invalid!');

class seccodeControl{
	public function __construct(){
	}

	/**
	 * 产生验证码
	 *
	 */
	public function makecodeOp(){
		$refererhost = parse_url($_SERVER['HTTP_REFERER']);
		$refererhost['host'] .= !empty($refererhost['port']) ? (':'.$refererhost['port']) : '';
	
		$seccode = makeSeccode($_GET['nchash']);
		//设置过期时间，-1表示立即过期
		@header("Expires: -1");
		//打开新窗口访问服务器：max-age的值表示访问此网页后多少秒后不会再次访问服务器
		@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
		//不缓存
		@header("Pragma: no-cache");

		$_SESSION['verify_code_captcha'] = $seccode;
		$code = new seccode();
		$code->code = $seccode;
		$code->width = 90;
		$code->height = 26;
		$code->background = 1;
		$code->adulterate = 1;
		$code->scatter = '';
		$code->color = 1;
		$code->size = 0;
		$code->shadow = 1;
		$code->animator = 0;
		$code->datapath =  BASE_DATA_PATH.'/resource/seccode/';
		$code->display();
	}

	/**
	 * 产生短信验证码
	 *
	 */
	public function makeAuthcodeOp() {
	    $left_time = decrypt(cookie('left_time'.$nchash),MD5_KEY);
	    if($left_time){
	        if(TIMESTAMP - $left_time < 60){
	            setNcCookie('left_time'.$_POST['nchash'],left_time);
	            exit(json_encode(array('state'=>'false','msg'=>'请等待')));
	        }
	    }
		
		if (!checkSeccode($_GET['nchash'],$_GET['captcha'])){
			exit(json_encode(array('state'=>'false','msg'=>'图片验证码有误')));
		}

	    if (!in_array($_GET['type'],array('email','mobile'))) {
			exit(json_encode(array('state'=>'false','msg'=>'参数错误')));
		}

		// 验证
		$obj_validate = new Validate();
		if($_GET['type'] == 'email'){
			$obj_validate->validateparam = array(
					array("input"=>$_GET['mobile'],			"require"=>"true",		"validator"=>"email", "message"=>'电子邮件格式不正确'),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				exit(json_encode(array('state'=>'false','msg'=>'参数错误')));
			}
		}
		
		if($_GET['type'] == 'mobile'){
			$obj_validate->validateparam = array(
					array("input"=>$_GET['mobile'],			"require"=>"true",		"validator"=>"mobile", "message"=>'手机格式不正确')
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				exit(json_encode(array('state'=>'false','msg'=>'参数错误')));
			}
		}
	
	    $model_member = Model('member');
	
	    $verify_code = rand(100,999).rand(100,999);
		
	    $_SESSION['verify_code'] = $verify_code; 

	    $model_tpl = Model('mail_templates');
	    $tpl_info = $model_tpl->getTplInfo(array('code'=>'authenticate'));

	    //把手机加入session验证
	    $_SESSION['mobile'] = $_GET['mobile'];
	    $param = array();
	    $param['send_time'] = date('Y-m-d H:i',TIMESTAMP);
	    $param['verify_code'] = $verify_code;
	    $param['site_name']	= C('site_name');
	    $subject = ncReplaceText($tpl_info['title'],$param);
	    $message = ncReplaceText($tpl_info['content'],$param);
	    if ($_GET['type'] == 'email') {
	        $email	= new Email();
	        $result	= $email->send_sys_email($_GET['mobile'],$subject,$message);
	    } elseif ($_GET['type'] == 'mobile') {
	        $sms = new Sms();
	        $result = $sms->send($_GET['mobile'],$message);
	    }
	    if ($result) {
	        setNcCookie('left_time'.$_POST['nchash'],TIMESTAMP);
	        exit(json_encode(array('state'=>'true','msg'=>'验证码已发出，请注意查收')));
	    } else {
	        exit(json_encode(array('state'=>'false','msg'=>'验证码发送失败')));
	    }
	}
	
	
	
	/**
	 * 产生短信验证码
	 * 检查手机号码是否重复
	 */
	public function makeMobileAuthcodeOp() {
	    $left_time = decrypt(cookie('left_time'.$nchash),MD5_KEY);

	    if($left_time){
	        if(TIMESTAMP - $left_time < 60){
	            setNcCookie('left_time'.$_POST['nchash'],left_time);
	            exit(json_encode(array('state'=>'false','msg'=>'请等待')));
	        }
	    }
		$mobile = Model()->table('member')->where(array('member_mobile' =>$_GET['mobile']))->select();
		if($mobile){
			//判断手机号码是否存在	
			exit(json_encode(array('state'=>'false','msg'=>'该手机号码已经存在！')));
		}
		
		if (!checkSeccode($_GET['nchash'],$_GET['captcha'])){
			exit(json_encode(array('state'=>'false','msg'=>'图片验证码有误')));
		}

	    if (!in_array($_GET['type'],array('email','mobile'))) {
			exit(json_encode(array('state'=>'false','msg'=>'参数错误')));
		}

		// 验证
		$obj_validate = new Validate();
		if($_GET['type'] == 'email'){
			$obj_validate->validateparam = array(
					array("input"=>$_GET['mobile'],			"require"=>"true",		"validator"=>"email", "message"=>'电子邮件格式不正确'),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				exit(json_encode(array('state'=>'false','msg'=>'参数错误')));
			}
		}
		
		if($_GET['type'] == 'mobile'){
			$obj_validate->validateparam = array(
					array("input"=>$_GET['mobile'],			"require"=>"true",		"validator"=>"mobile", "message"=>'手机格式不正确')
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				exit(json_encode(array('state'=>'false','msg'=>'参数错误')));
			}
		}
	
	    $model_member = Model('member');
	
	    $verify_code = rand(100,999).rand(100,999);
		
	    $_SESSION['verify_code'] = $verify_code; 

	    $model_tpl = Model('mail_templates');
	    $tpl_info = $model_tpl->getTplInfo(array('code'=>'authenticate'));

	    //把手机加入session验证
	    $_SESSION['mobile'] = $_GET['mobile'];
	    $param = array();
	    $param['send_time'] = date('Y-m-d H:i',TIMESTAMP);
	    $param['verify_code'] = $verify_code;
	    $param['site_name']	= C('site_name');
	    $subject = ncReplaceText($tpl_info['title'],$param);
	    $message = ncReplaceText($tpl_info['content'],$param);
	    if ($_GET['type'] == 'email') {
	        $email	= new Email();
	        $result	= $email->send_sys_email($_GET['mobile'],$subject,$message);
	    } elseif ($_GET['type'] == 'mobile') {
	        $sms = new Sms();
	        $result = $sms->send($_GET['mobile'],$message);
	    }
	    if ($result) {
	        setNcCookie('left_time'.$_POST['nchash'],TIMESTAMP);
	        exit(json_encode(array('state'=>'true','msg'=>'验证码已发出，请注意查收')));
	    } else {
	        exit(json_encode(array('state'=>'false','msg'=>'验证码发送失败')));
	    }
	}
	
	/*
	 * AJAX验证
	 *
	 */
	public function checkOp(){
		if (checkSeccode($_GET['nchash'],$_GET['captcha'])){
			exit('true');
		}else{
			exit('false');
		}
	}
	
	/*
	 * AJAX短信验证
	 *
	 */
	public function checkAuthOp(){
		if (!empty($_SESSION['verify_code']) && $_SESSION['verify_code'] == $_GET['mobile_captcha']){
			exit('true');
		}else{
			exit('false');
		}
	}

	/*
	 * AJAX图片验证码hash
	 *
	 */
	public function makeHashCodeOp(){
		//显示注册方式的开关
		if(C('register_way')) {
			$register_way = 1;
		}		
		exit(json_encode(array('state'=>'true','nchash'=>getNchash(), 'register_way'=>$register_way)));
	}
	
	/**
	 * 得到支付体检验证
	 */
	public function makeMobileAuthcode_codeOp() {
	    $left_time = decrypt(cookie('left_time'.$nchash),MD5_KEY);
	    if($left_time){
	        if(TIMESTAMP - $left_time < 60){
	            setNcCookie('left_time'.$_POST['nchash'],left_time);
	            exit(json_encode(array('state'=>'false','msg'=>'请等待')));
	        }
	    }
		$mobile = Model()->table('member')->where(array('member_mobile' =>$_GET['mobile']))->select();
		if($mobile[0]['member_mobile'] != $_GET['mobile']){
			//判断手机号码是否存在	
			exit(json_encode(array('state'=>'false','msg'=>'该手机号码已与上面手机号码不一致！')));
		}else if($mobile[0]['member_mobile'] == null){
			exit(json_encode(array('state'=>'2','msg'=>'对不起，您还没有设置支付密码，请问是否绑定？')));
		}
		
		if (!checkSeccode($_GET['nchash'],$_GET['captcha'])){
			exit(json_encode(array('state'=>'false','msg'=>'图片验证码有误')));
		}

	    if (!in_array($_GET['type'],array('email','mobile'))) {
			exit(json_encode(array('state'=>'false','msg'=>'参数错误')));
		}

	
	    $model_member = Model('member');
	
	    $verify_code = rand(100,999).rand(100,999);
		
	    $_SESSION['verify_code'] = $verify_code; 

	    $model_tpl = Model('mail_templates');
	    $tpl_info = $model_tpl->getTplInfo(array('code'=>'authenticate'));

	    //把手机加入session验证
	    $_SESSION['mobile'] = $_GET['mobile'];
	    $param = array();
	    $param['send_time'] = date('Y-m-d H:i',TIMESTAMP);
	    $param['verify_code'] = $verify_code;
	    $param['site_name']	= C('site_name');
	    $subject = ncReplaceText($tpl_info['title'],$param);
	    $message = ncReplaceText($tpl_info['content'],$param);
	    if ($_GET['type'] == 'email') {
	        $email	= new Email();
	        $result	= $email->send_sys_email($_GET['mobile'],$subject,$message);
	    } elseif ($_GET['type'] == 'mobile') {
	        $sms = new Sms();
	        $result = $sms->send($_GET['mobile'],$message);
	    }
	    if ($result) {
	        setNcCookie('left_time'.$_POST['nchash'],TIMESTAMP);
	        exit(json_encode(array('state'=>'true','msg'=>'验证码已发出，请注意查收')));
	    } else {
	        exit(json_encode(array('state'=>'false','msg'=>'验证码发送失败')));
	    }
	}
	
	/**
	 * 保存支付密码 获取手机验证码
	 */
	 public function makeMobileAuthcode_code1Op() {
	    $left_time = decrypt(cookie('left_time'.$nchash),MD5_KEY);
	    if($left_time){
	        if(TIMESTAMP - $left_time < 60){
	            setNcCookie('left_time'.$_POST['nchash'],left_time);
	            exit(json_encode(array('state'=>'false','msg'=>'请等待')));
	        }
	    }
		$mobile = Model()->table('member')->where(array('member_mobile' =>$_GET['mobile']))->select();
		if($mobile[0]['member_mobile'] != $_GET['mobile']){
			//判断手机号码是否存在	
			exit(json_encode(array('state'=>'false','msg'=>'该手机号码已与上面手机号码不一致！')));
		}
		
		if (!checkSeccode($_GET['nchash'],$_GET['captcha'])){
			exit(json_encode(array('state'=>'false','msg'=>'图片验证码有误')));
		}

	    if (!in_array($_GET['type'],array('email','mobile'))) {
			exit(json_encode(array('state'=>'false','msg'=>'参数错误')));
		}

		// 验证
		
	
	    $model_member = Model('member');
	
	    $verify_code = rand(100,999).rand(100,999);
		
	    $_SESSION['verify_code'] = $verify_code; 

	    $model_tpl = Model('mail_templates');
	    $tpl_info = $model_tpl->getTplInfo(array('code'=>'authenticate'));

	    //把手机加入session验证
	    $_SESSION['mobile'] = $_GET['mobile'];
	    $param = array();
	    $param['send_time'] = date('Y-m-d H:i',TIMESTAMP);
	    $param['verify_code'] = $verify_code;
	    $param['site_name']	= C('site_name');
	    $subject = ncReplaceText($tpl_info['title'],$param);
	    $message = ncReplaceText($tpl_info['content'],$param);
	    if ($_GET['type'] == 'email') {
	        $email	= new Email();
	        $result	= $email->send_sys_email($_GET['mobile'],$subject,$message);
	    } elseif ($_GET['type'] == 'mobile') {
	        $sms = new Sms();
	        $result = $sms->send($_GET['mobile'],$message);
	    }
	    if ($result) {
	        setNcCookie('left_time'.$_POST['nchash'],TIMESTAMP);
	        exit(json_encode(array('state'=>'true','msg'=>'验证码已发出，请注意查收')));
	    } else {
	        exit(json_encode(array('state'=>'false','msg'=>'验证码发送失败')));
	    }
	}
}

?>
