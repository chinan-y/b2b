<?php
defined('GcWebShop') or exit('Access Invalid!');
//语言种类配置文件$lang[$key]=(前台显示文字，前台显示与不显示开关1：开启；0：关闭)
$lang=array();
$lang['zh_cn']=array('name'=>'简体中文','status'=>1);
$lang['zh']=array('name'=>'繁体中文','status'=>1);
$lang['zh_en']=array('name'=>'English','status'=>1);
return $lang;
