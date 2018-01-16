<?php

require_once(dirname(__DIR__) . '/comm/config.php');

function wechat_login($appid, $scope, $callback)
{
    $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
    if(!empty($_GET['mobile'])){
        if (!empty($_GET['wechatBind'])) {
            $_SESSION['wechatBind'] = $_COOKIE['username'];
        }else{
        	$_SESSION['wechatBind'] = '';
        }
        if (!empty($_GET['loginReturn'])) {
            $_SESSION['loginReturn'] = $_GET['loginReturn'];
        }else{
        	$_SESSION['wechatBind'] = '';
        }
        $callback = $callback.'&m=mobile';
        $params = array(
            'appid' => $appid,
            'redirect_uri' => $callback,
            'response_type' => 'code',
            'scope' => $scope,
            'state' => $_SESSION['state']
        );
        $login_url = sprintf(
            '%s?%s', 'https://open.weixin.qq.com/connect/oauth2/authorize', http_build_query($params)
        );
    } else {
        if (!empty($_GET['wechatBind'])) {
            $_SESSION['wechatBind'] = $_SESSION['member_name'];
        }
        $login_url = "https://open.weixin.qq.com/connect/qrconnect";
        $params = array(
            'appid' => $appid,
            'redirect_uri' => $callback,
            'response_type' => 'code',
            'scope' => 'snsapi_login',
            'state' => $_SESSION['state']
        );
        $login_url = sprintf(
            '%s?%s', $login_url, http_build_query($params)
        );
    }
    header("Location:$login_url");
}
//用户点击qq登录按钮调用此函数
wechat_login($_SESSION["appid"], $_SESSION["scope"], $_SESSION["callback"]);
?>
