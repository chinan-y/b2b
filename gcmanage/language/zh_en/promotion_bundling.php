<?php
defined('GcWebShop') or exit('Access Invalid!');

// index
$lang['promotion_unavailable'] = 'sales promotion is unvailable';
$lang['bundling_quota']				= 'bundling list';
$lang['bundling_list']				= 'activity';
$lang['bundling_setting']			= 'setting';
$lang['bundling_gold_price']		= 'discount bundling price';
$lang['bundling_sum']				= 'bundling pbulished summary';
$lang['bundling_goods_sum']			= 'product amount is added to every bundling';
$lang['bundling_price_prompt']		= 'sold in month(30 days).seller can  syndicate discount bundling activity during the purchase period after purchase';
$lang['bundling_sum_prompt']		= 'the maximum of allowing store owners to publish discount bundling. 0 means no limitation';
$lang['bundling_goods_sum_prompt']	= 'the maximum of goods added to each bundling, less than 5';
$lang['bundling_price_error']		= 'not null and the integer is more than 1';
$lang['bundling_sum_error']			= 'not null and the integer is more than 0';
$lang['bundling_goods_sum_error']	= 'not null and the intefer is between 1 and 5';
$lang['bundling_update_succ']		= 'update successfully';
$lang['bundling_update_fail']		= 'fail to update ';

$lang['bundling_state_all']			= 'overall condition';
$lang['bundling_state_1']			= 'open';
$lang['bundling_state_0']			= 'closed';


// activity list
$lang['bundling_quota_list_prompts']= 'discount bundling purchase activity list';
$lang['bundling_quota_store_name']	= 'store name';

// activity edition
$lang['bundling_quota_endtime_required']	= 'add end time';
$lang['bundling_quota_endtime_dateValidate']= 'the end time needs to be greater than the start time';
$lang['bundling_quota_store_name']			= 'store name';
$lang['bundling_quota_quantity']			= 'purchase quantity';
$lang['bundling_quota_starttime']			= 'start time';
$lang['bundling_quota_endtime']				= 'end time';
$lang['bundling_quota_endtime_tips']		= 'if you choose open state, please set the end time greater than current time, or it can not be opened';
$lang['bundling_quota_state_tips']			= 'the end time must be greater than current time when setting open state, or its state can not be opened';
$lang['bundling_quota_prompts']				= 'view discount bundling information of every stores, you can cancel somme activity';

// bundling list
$lang['bundling_name']						= 'activity name';
$lang['bundling_price']						= 'price of sales activity ';
$lang['bundling_goods_count']				= 'goods amount';
