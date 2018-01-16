<?php

require_once(dirname(__DIR__) . '/comm/config.php');
require_once(dirname(__DIR__) . '/comm/utils.php');

function wechat_callback()
{
    //debug
//    echo '<pre>';
//    var_dump($_SESSION);exit;

    if($_REQUEST['state'] == $_SESSION['state']) //csrf
    {
        $params = array(
            'appid' => $_SESSION["appid"],
            'secret' => $_SESSION["appkey"],
            'code' => $_REQUEST["code"],
            'grant_type' => 'authorization_code'
        );

        $token_url = sprintf(
            '%s/access_token?%s', 'https://api.weixin.qq.com/sns/oauth2',
            http_build_query($params)
        );

        $response = get_url_contents($token_url);
        $result = json_decode($response, true);
        if (!empty($result['errcode'])) {
            echo "<h3>error:</h3>" . $result['errcode'];
            echo "<h3>msg  :</h3>" . $result['errmsg'];
            exit;
        }

        //debug
        //print_r($params);
        //set access token to session
        $_SESSION["access_token"] = $result["access_token"];
        $_SESSION["openid"] = $result["openid"];
        $_SESSION["unionid"] = $result["unionid"];
        $_SESSION['m'] = empty($_GET['m']) ? '' : $_GET['m'];//判断是否手机登陆

    }
    else 
    {
        echo("The state does not match. You may be a victim of CSRF.");
        exit;
    }
}

//QQ登录成功后的回调地址,主要保存access token
wechat_callback();
@header('location: ' . SHOP_SITE_URL . '/index.php?gct=wechat');
exit;
?>
