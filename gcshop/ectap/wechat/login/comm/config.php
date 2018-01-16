<?php
/**
 * PHP SDK for QQ登录 OpenAPI
 *
 * @version 1.2
 * @author connect@qq.com
 * @copyright © 2011, Tencent Corporation. All rights reserved.
 */

/**
 * @brief 本文件作为demo的配置文件。
 */

/**
 * 正式运营环境请关闭错误信息
 * ini_set("error_reporting", E_ALL);
 * ini_set("display_errors", TRUE);
 * QQDEBUG = true  开启错误提示
 * QQDEBUG = false 禁止错误提示
 * 默认禁止错误信息
 */
define("WECHATDEBUG", false);
if (defined("WECHATDEBUG") && WECHATDEBUG)
{
    @ini_set("error_reporting", E_ALL);
    @ini_set("display_errors", TRUE);
}

/**
 * session
 */
require_once("session.php");

//包含配置信息
$data = require(BASE_DATA_PATH.DS.'cache'.DS.'setting.php');
if(!empty($_GET['mobile']) || !empty($_GET['m'])  || !empty($_SESSION['m'])){
    //微信登陆是否开启
    if($data['wechat_isuse'] != 1){
        header('Content-Type: text/html; charset=utf-8');
        exit('微信登陆未启用');
    }
    //申请到的appid
    //$_SESSION["appid"]    = yourappid;
    $_SESSION["appid"]    = trim($data['wechat_appid']);

    //申请到的appkey
    //$_SESSION["appkey"]   = "yourappkey";
    $_SESSION["appkey"]   = trim($data['wechat_appkey']);

    $callbackUrl = SHOP_SITE_URL."/ectap/wechat/login/wechat.php?gp=callback";
    //微信登录成功后跳转的地址,请确保地址真实可用，否则会导致登录失败。
    $_SESSION["callback"] = SHOP_SITE_URL."/ectap/wechat/login/wechat.php?gp=callback";

    //QQ授权api接口.按需调用
    $_SESSION["scope"] = "snsapi_userinfo";

} else {
    //微信PC登陆是否开启
    if($data['wechatpc_isuse'] != 1){
        header('Content-Type: text/html; charset=utf-8');
        exit('微信PC端登陆未启用');
    }

    //申请到的appid
    //$_SESSION["appid"]    = yourappid;
    $_SESSION["appid"]    = trim($data['wechatpc_appid']);

    //申请到的appkey
    //$_SESSION["appkey"]   = "yourappkey";
    $_SESSION["appkey"]   = trim($data['wechatpc_appkey']);

    $callbackUrl = SHOP_SITE_URL."/ectap/wechat/login/wechat.php?gp=callback";
    //微信登录成功后跳转的地址,请确保地址真实可用，否则会导致登录失败。
    $_SESSION["callback"] = $callbackUrl;

    //QQ授权api接口.按需调用
    $_SESSION["scope"] = "snsapi_login";

}




//print_r ($_SESSION);exit;
?>
