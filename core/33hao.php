<?php
/**
 * 运行框架
 *
 *
 *
 *
 */

defined('GcWebShop') or exit('Access Invalid!');
if (!@include(BASE_DATA_PATH.'/config/config.ini.php')) exit('config.ini.php isn\'t exists!');
if (file_exists(BASE_PATH.'/config/config.ini.php')){
	include(BASE_PATH.'/config/config.ini.php');
}
global $config;

//默认平台店铺id
define('DEFAULT_PLATFORM_STORE_ID', $config['default_store_id']);

define('URL_MODEL',$config['url_model']);
$auto_site_url = strtolower('http://'.$_SERVER['HTTP_HOST'].implode('/',$tmp_array));
define(SUBDOMAIN_SUFFIX, $config['subdomain_suffix']);
define('BASE_SITE_URL', $config['base_site_url']);
define('SHOP_SITE_URL', $config['shop_site_url']);
define('CMS_SITE_URL', $config['cms_site_url']);
define('CIRCLE_SITE_URL', $config['circle_site_url']);
define('LIVE_SITE_URL', $config['live_site_url']);
define('MICROSHOP_SITE_URL', $config['microshop_site_url']);
define('ADMIN_SITE_URL', $config['admin_site_url']);
define('MOBILE_SITE_URL', $config['mobile_site_url']);
define('WAP_SITE_URL', $config['wap_site_url']);
define('WXSHOP_SITE_URL', $config['wxshop_site_url']);
define('UPLOAD_SITE_URL',$config['upload_site_url']);
define('RESOURCE_SITE_URL',$config['resource_site_url']);
define('DELIVERY_SITE_URL',$config['delivery_site_url']);
define('IMAGE_BUCKET_NAME',$config['image_bucket_name']);

define('BASE_DATA_PATH',BASE_ROOT_PATH.'/data');
define('BASE_UPLOAD_PATH',BASE_DATA_PATH.'/upload');
define('BASE_RESOURCE_PATH',BASE_DATA_PATH.'/resource');

define('CHARSET',$config['db'][1]['dbcharset']);
define('DBDRIVER',$config['dbdriver']);
define('SESSION_EXPIRE',$config['session_expire']);
define('LANG_TYPE',$config['lang']);
define('COOKIE_PRE',$config['cookie_pre']);

define('DBPRE',$config['tablepre']);
define('DBNAME',$config['db'][1]['dbname']);
$_GET['gct'] = $_GET['gct'] ? strtolower($_GET['gct']) : ($_POST['gct'] ? strtolower($_POST['gct']) : null);
$_GET['gp'] = $_GET['gp'] ? strtolower($_GET['gp']) : ($_POST['gp'] ? strtolower($_POST['gp']) : null);

if (empty($_GET['gct'])){
    require_once(BASE_CORE_PATH.'/framework/core/route.php');
    new Route($config);
}
//统一ACTION
$_GET['gct'] = preg_match('/^[\w]+$/i',$_GET['gct']) ? $_GET['gct'] : 'index';
$_GET['gp'] = preg_match('/^[\w]+$/i',$_GET['gp']) ? $_GET['gp'] : 'index';

//对GET POST接收内容进行过滤,$ignore内的下标不被过滤
$ignore = array('article_content','pgoods_body','doc_content','content','sn_content','g_body','store_description','p_content','groupbuy_intro','remind_content','note_content','ref_url','adv_pic_url','adv_word_url','adv_slide_url','appcode','mail_content');
if (!class_exists('Security')) require(BASE_CORE_PATH.'/framework/libraries/security.php');
$_GET = !empty($_GET) ? Security::getAddslashesForInput($_GET,$ignore) : array();
$_POST = !empty($_POST) ? Security::getAddslashesForInput($_POST,$ignore) : array();
$_REQUEST = !empty($_REQUEST) ? Security::getAddslashesForInput($_REQUEST,$ignore) : array();
$_SERVER = !empty($_SERVER) ? Security::getAddSlashes($_SERVER) : array();

//启用ZIP压缩
if ($config['gzip'] == 1 && function_exists('ob_gzhandler') && $_GET['inajax'] != 1){
	ob_start('ob_gzhandler');
}else {
	ob_start();
}
require_once(BASE_CORE_PATH.'/framework/libraries/queue.php');
require_once(BASE_CORE_PATH.'/framework/function/core.php');
require_once(BASE_CORE_PATH.'/framework/core/base.php');

require_once(BASE_CORE_PATH.'/framework/function/goods.php');

if(function_exists('spl_autoload_register')) {
	spl_autoload_register(array('Base', 'autoload'));
} else {
	function __autoload($class) {
		return Base::autoload($class);
	}
}

?>
