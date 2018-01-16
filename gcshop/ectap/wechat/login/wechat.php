<?php
define('ProjectName','');
define('BASE_PATH', str_replace('\\', '/', dirname(dirname(dirname(__DIR__)))));
require_once('../../../../global.php');

session_save_path(BASE_DATA_PATH.DS.'session');
require_once(BASE_DATA_PATH.DS.'config/config.ini.php');
if(!empty($config) && is_array($config)){
    $site_url = $config['shop_site_url'];
    define('SHOP_SITE_URL',$site_url);
}
if ($_GET['gp'] == 'callback'){
    include 'oauth/wechat_callback.php';
}else{
    include 'oauth/wechat_login.php';
}