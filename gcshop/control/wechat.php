<?php
/**
 * 微信登陆控制器
 *
 *
 **by 好商城V3 www.33hao.com 运营版*/


defined('GcWebShop') or exit('Access Invalid!');

class wechatControl extends BaseHomeControl{
    public function __construct(){
        parent::__construct();
        /**
         * 判断微信登陆功能是否开启
         */
        if (C('wechat_isuse') != 1){
            self::showMessage('系统未开启微信登陆功能');
        }
        /**
         * 初始化测试数据
         */
        if (!$_SESSION['openid']){
            self::showMessage('获取微信用户资料失败');
        }
        Tpl::output('hidden_nctoolbar', 1);
    }
    /**
     * 首页
     */
    public function indexOp(){
        if ($_SESSION['wechatBind']) { // 绑定已有账号
            $this->bindExistMember();
        } else {
            $this->autologin();
            $this->registerOp();
        }
    }

    private function bindExistMember() {
        // 欲绑定已有用户ID
        $memberName = $_SESSION['wechatBind'];

        // 如果该用户已经绑定过微信则提示错误
        $model_member	= Model('member');
        $member = $model_member->getMemberInfo(array('member_name' => $memberName));
        if (!empty($member['member_wechatopenid']) || !empty($member['member_wechatpcopenid'])) {
            self::showMessage('当前用户已绑定过微信');
        }

        // 判断当前授权的微信用户是否已经绑定过
        if (self::checkWapWechatlogin()) {
            $cond = array(
                'member_wechatopenid' => $_SESSION['openid']
            );
        } else {
            $cond = array(
                'member_wechatpcopenid' => $_SESSION['openid']
            );
        }
        $boundMember = $model_member->getMemberInfo($cond);
        if (empty($boundMember) && $_SESSION['unionid']) {
            $cond = array(
                'member_wechatunionid' => $_SESSION['unionid']
            );
            $boundMember = $model_member->getMemberInfo($cond);
        }
        if ($boundMember) {
            self::showMessage('当前微信已绑定过其他用户');
        }

        // 开始进行微信绑定
        //获取微信账号信息
        require_once (BASE_PATH.'/ectap/wechat/login/user/get_user_info.php');
        $wechat_user_info = get_user_info($_SESSION["appid"], $_SESSION["appkey"], $_SESSION["token"], $_SESSION["secret"], $_SESSION["openid"]);
        //处理账号信息
        $wechat_user_info['nickname'] = trim($wechat_user_info['nickname']);
        if (self::checkWapWechatlogin()) {
            $update_info = array(
                'member_wechatopenid' => $_SESSION['openid'],
                'member_wechatunionid' => $_SESSION['unionid'],
                'member_wechatinfo' => serialize($wechat_user_info)
            );
        } else {
            $update_info = array(
                'member_wechatpcopenid' => $_SESSION['openid'],
                'member_wechatunionid' => $_SESSION['unionid'],
                'member_wechatpcinfo' => serialize($wechat_user_info)
            );
        }
        $model_member->editMember(array('member_name'=>$memberName),$update_info);
        self::showMessage('绑定成功');
    }
    private static function checkWapWechatlogin(){
        if(!empty($_SESSION['m'])){
            return true;
        }
        return false;
    }

    /**
     * qq绑定新用户
     */
    public function registerOp(){
        //实例化模型
        $model_member	= Model('member');
        if (chksubmit()){
            $update_info	= array();
            $update_info['member_passwd']= md5(trim($_POST["password"]));
            if(!empty($_POST["email"])) {
                $update_info['member_email']= $_POST["email"];
                $_SESSION['member_email']= $_POST["email"];
            }
            $model_member->editMember(array('member_id'=>$_SESSION['member_id']),$update_info);
            self::showMessage('保存成功');
        }else {
            //检查登录状态
            $model_member->checkloginMember();
            //获取微信账号信息
            require_once (BASE_PATH.'/ectap/wechat/login/user/get_user_info.php');
            $wechat_user_info = get_user_info($_SESSION["appid"], $_SESSION["appkey"], $_SESSION["token"], $_SESSION["secret"], $_SESSION["openid"]);
            Tpl::output('wechat_user_info',$wechat_user_info);

            //处理qq账号信息
            $wechat_user_info['nickname'] = trim($wechat_user_info['nickname']);
            $user_passwd = rand(100000, 999999);
            /**
             * 会员添加
             */
            $user_array	= array();
            // if(trim($wechat_user_info['nickname'])==''){$name='weixin'.substr($_SESSION['openid'],-3);}else{$name=trim($wechat_user_info['nickname']);}
            $user_array['member_name']		= self::checkMemberName($wechat_user_info['nickname']);
            $user_array['member_passwd']	= $user_passwd;
            $user_array['member_email']		= '';
            if (self::checkWapWechatlogin()) {
                $user_array['member_wechatopenid']	= $_SESSION['openid'];//qq openid
                $user_array['member_wechatinfo']	= serialize($wechat_user_info);//qq 信息
                $user_array['member_wechatpcopenid'] = '';
                $user_array['member_wechatpcinfo'] = '';
            } else {
                $user_array['member_wechatopenid'] = '';
                $user_array['member_wechatinfo'] = '';
                $user_array['member_wechatpcopenid']	= $_SESSION['openid'];//qq openid
                $user_array['member_wechatpcinfo']	= serialize($wechat_user_info);//qq 信息
            }
            $user_array['member_wechatunionid'] = $_SESSION['unionid'];
			$ref = $_SESSION['ref'];
			$user_array['refer_id'] =$ref;
			$result	= $model_member->addMember($user_array);
		
			
            // $rand = rand(100, 899);
            // if(strlen($user_array['member_name']) < 3) $user_array['member_name']		= $wechat_user_info['nickname'].$rand;
            // $check_member_name	= $model_member->getMemberInfo(array('member_name'=>trim($user_array['member_name'])));
            // $result	= 0;
            // if(empty($check_member_name)) {
                // $result	= $model_member->addMember($user_array);
            // }else {
                // $user_array['member_name'] = trim($wechat_user_info['nickname']).$rand;
                // $check_member_name	= $model_member->getMemberInfo(array('member_name'=>trim($user_array['member_name'])));
                // if(empty($check_member_name)) {
                    // $result	= $model_member->addMember($user_array);
                // }else {
                    // for ($i	= 1;$i < 999999;$i++) {
                        // $rand = $rand+$i;
                        // $user_array['member_name'] = trim($wechat_user_info['nickname']).$rand;
                        // $check_member_name	= $model_member->getMemberInfo(array('member_name'=>trim($user_array['member_name'])));
                        // if(empty($check_member_name)) {
                            // $result	= $model_member->addMember($user_array);
                            // break;
                        // }
                    // }
                // }
            // }
            if($result) {
                Tpl::output('user_passwd',$user_passwd);
                $img = self::get_nr($wechat_user_info['headimgurl']);
                $a = 'avatar_'.$result.'.jpg';
                file_put_contents(BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR."/".$a,$img);
                $avatar	= $a;
                $update_info	= array();
                if($avatar) {
                    $update_info['member_avatar'] 	= "avatar_$result.jpg";
                    $model_member->editMember(array('member_id'=>$result),$update_info);
                    $user_array['member_avatar'] 	= "avatar_$result.jpg";
                }
                $user_array['member_id']		= $result;

                //添加会员积分
                if (C('points_isuse')){
                    Model('points')->savePointsLog('regist',array('pl_memberid'=>$result,'pl_membername'=>$user_array['member_name']),false);
                }

                $model_member->createSession($user_array);
                if(self::checkWapWechatlogin()){
                    @header('location: '.MOBILE_SITE_URL.'/index.php?gct=wechatlogin');
                    exit;
                }else{
                    Tpl::showpage('wechat_register');
                }
            } else {
                self::showMessage('微信登陆时注册用户失败');
            }
        }
    }
	private static function checkMemberName($member_name){
		$model_member = Model('member');
		$result=$model_member->getMemberInfo(array('member_name'=>$member_name));
		$rand= rand(1000, 9999);
		if(!empty($result)){
			$member_name=$member_name.$rand;
			$member=self::checkMemberName($member_name);
			return $member;
		}else{
			return $member_name;
		}
	}
    /**
     * 绑定qq后自动登录
     */
    public function autologin(){
        //查询是否已经绑定该微信,已经绑定则直接跳转
        $model_member	= Model('member');

        if (self::checkWapWechatlogin()) {
            $array	= array();
            $array['member_wechatopenid']	= $_SESSION['openid'];
            $member_info = $model_member->getMemberInfo($array);
        } else {
            $array	= array();
            $array['member_wechatpcopenid']	= $_SESSION['openid'];
            $member_info = $model_member->getMemberInfo($array);
        }
        if (!$member_info) {
            $array	= array();
            $array['member_wechatunionid']	= $_SESSION['unionid'];
            $member_info = $model_member->getMemberInfo($array);
        }
        if (is_array($member_info) && count($member_info)>0){
            if(!$member_info['member_state']){//1为启用 0 为禁用
                self::showMessage('该用户被禁用');
            }
            //获取微信账号信息
            require_once (BASE_PATH.'/ectap/wechat/login/user/get_user_info.php');
            $wechat_user_info = get_user_info($_SESSION["appid"], $_SESSION["appkey"], $_SESSION["token"], $_SESSION["secret"], $_SESSION["openid"]);

            if (self::checkWapWechatlogin()) {
                $update_info = array(
                    'member_wechatopenid' => $_SESSION['openid'],
                    'member_wechatinfo' => serialize($wechat_user_info)
                );
            } else {
                $update_info = array(
                    'member_wechatpcopenid' => $_SESSION['openid'],
                    'member_wechatpcinfo' => serialize($wechat_user_info)
                );
            }
            $model_member->editMember(array('member_id' => $member_info['member_id']),$update_info);
            $model_member->createSession($member_info);
            if(self::checkWapWechatlogin()){
                @header('location: '.MOBILE_SITE_URL.'/index.php?gct=wechatlogin');
                exit;
            }else{
                self::showMessage('登陆成功');
            }
        }
    }

    public static function get_nr( $url , $ref = '' , $coo = '' )
    {
        $header = array( "Referer: " . $ref . "" , "Cookie: " . $coo );
        $ch     = curl_init();
        curl_setopt( $ch , CURLOPT_URL , $url );
        curl_setopt( $ch , CURLOPT_TIMEOUT , 5 );
        //----
        curl_setopt( $ch , CURLOPT_HTTPHEADER , $header );
        curl_setopt( $ch , CURLOPT_FOLLOWLOCATION , 1 );
        //$contents = curl_exec($ch);
        ob_start();
        curl_exec( $ch );
        $contents = ob_get_contents();
        ob_end_clean();
        curl_close( $ch );
        return $contents;
    }

    private static function showMessage($msg){
        if (self::checkWapWechatlogin()) {
            $url = $_SESSION['loginReturn'];
            $json = json_encode(array(
                'msg' => $msg,
                'returnUrl' => $url
            ));
            header("Content-type: text/html; charset=utf-8");
            $html =
<<<EOT
<script>
    var json = {$json}
    alert(json.msg);
    window.location.href = json.returnUrl;
</script>>
EOT;
            echo $html;
        } else {
            showMessage($msg,'index.php','html','error');
        }

        exit;
    }

}
