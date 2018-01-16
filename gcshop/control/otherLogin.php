<?php
/**
 * 第三方登陆控制器
 *
 *
 **by 好商城V3 www.33hao.com 运营版*/


defined('GcWebShop') or exit('Access Invalid!');

class otherLoginControl extends BaseHomeControl{
	private $login;//第三方登录名称，通过GET['login']方式传递和获取；默认微信
	private $m;//PC或手机端，通过GET['login']方式传递和获取；默认wap端
    public function __construct(){
        parent::__construct();
		isset($_GET['login'])?$this->login=$_GET['login']:$this->login='weixin';
		isset($_GET['m'])?$this->m=$_GET['m']:$this->m='wap';
    }
    /**
     * 首页
     */
    public function indexOp(){
		$parms=array();
		if($this->login=='weixin'){
			$wxInfo=Model()->table('temporaryTable')->where(array('openid'=>$_SESSION['openid']))->find();
			//如果该用户的第一次登录
			if($wxInfo){
				if($this->m='wap'){//wap端的绑定跳转地址
					header("location:https://www.qqbsmall.com/wap/tmpl/member/bind.html?openid=".$_SESSION['openid']);
				}elseif($this->m='pc'){//pc跳转地址
					
				}
			}else{//如果该用户已经绑定过微信则直接登录
				if($this->m='wap'){//wap端的登录跳转地址
					header("location:https://www.qqbsmall.com/mobile/index.php?gct=wxlogin&gp=wlogin");
				}elseif($this->m='pc'){
					//pc登录
				}
			}
		}else if($this->login=='app_bind'){
			header("location:https://www.qqbsmall.com/mobile/index.php?gct=wxlogin&gp=wxbind&openid=".$_SESSION['openid']."&unionid=".$_SESSION['unionid']);
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
    }

   
}
