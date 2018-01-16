<?php
defined('GcWebShop') or exit('Access Invalid!');
/**
 * common language	
 */

/**
 * index
 */
$lang['payment_index_order']					= 'order';
$lang['payment_index_not_exists']				= 'not exist';
$lang['payment_index_pay_finish']				= 'paid';
$lang['payment_index_add_info_fail']			= 'fail to add payment method to order';
$lang['payment_index_refresh_fail']				= 'fail to refresh';
$lang['payment_index_sys_not_support']			= ':system does not support selected payment method';
$lang['payment_index_lose_file']				= 'specific payment port does not exist';
$lang['payment_index_spec_order_not_exists1']	= 'payment number is';
$lang['payment_index_spec_order_not_exists2']	= 'order does not exist';
$lang['payment_index_miss_pay_method']			= 'miss payment methid';
$lang['payment_index_miss_pay_method_data']		= 'paymen method data lose';
$lang['payment_index_identify_fail']			= 'fail to identify';
$lang['payment_index_order_ensure']				= 'confirmed order state';
$lang['payment_index_deal_order_success']		= '，pay for the order successfully. now going to mt order';
$lang['payment_index_deal_pdr_success']			= '，recharge successfully. now going to my recharge list';
$lang['payment_index_deal_order_fail']			= '，process overtime, please try again';
$lang['payment_index_input_pay_info']			= 'input payment meaasage';
$lang['payment_index_not_belong_you']			= 'order does not belong to you';
$lang['payment_index_pay_method_tip']			= ':,.→for example: transfer accounts, time etc. you can pay in my order in the user center after a while';
$lang['payment_index_submit']					= 'submit';
$lang['payment_index_buyerinfo_error']			= 'member information error';
$lang['payment_index_password_error']			= 'member login password error';
$lang['payment_order_predeposit_logdesc']		= 'consumptions reduce available fund of predeposite';
$lang['payment_order_predepositfreeze_logdesc']	= 'consumptions add frozen fund of predeposite';
$lang['payment_predeposit_short_error']			= '，insufficient predeposite, please recharge';
$lang['payment_refund_predeposit_logdesc']		= 'refund will reduce available fund of predeposite';
$lang['payment_pay_predeposit_logdesc']	= 'refund will add available fund of predeposite';
$lang['payment_refund_predepositfreeze_logdesc']	= 'refund will reduce frozen fund of predeposite';