<?php
defined('GcWebShop') or exit('Access Invalid!');
/**
 * index
 */
$lang['express_name']	= '快遞公司';
$lang['express_order']	= '常用';
$lang['express_state']	= '狀態';
$lang['express_letter']	= '首字母';
$lang['express_url']	= '網址 (僅供參考)';

$lang['express_index_help1']		= '系統內置的快遞公司不得刪除，只可編輯狀態，平台可禁用不需要的快遞公司，預設按首字母進行排序，常用的快遞公司將會排在靠前位置';
$lang['express_index_help2']		= '更改狀態後，需要到 設置 -> 清理緩存 中，清理快遞公司緩存 後，才會生效';

/** 
 * acount_setting
 */
$lang['acount_setting']			= '帐号设置';
$lang['acount_setting_tip']		= '请设置从<a target="_blank" href="http://www.kuaidi100.com">快递100</a>和<a target="_blank" href="http://www.ickd.cn">爱查快读</a>获取的帐号。系统优先使用快递100，当快递100查询不到时，系统自动使用爱查快递。';
$lang['acount_kd100_id']		= '快递100ID';
$lang['acount_kd100_key']		= '快递100KEY';
$lang['acount_ickd_id']			= '爱查快递ID';
$lang['acount_ickd_key']		= '爱查快递KEY';