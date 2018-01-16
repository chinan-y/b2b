<?php
defined('GcWebShop') or exit('Access Invalid!');
/**
 * exchange order function pulicity
 */
$lang['admin_pointorder_unavailable']	 		= 'The system is unavailable for points or points exchange function';
$lang['admin_pointorder_parameter_error']		= 'parameter error';
$lang['admin_pointorderd_record_error']			= 'record information error';
$lang['admin_pointorder_userrecord_error']		= 'user information error';
$lang['admin_pointorder_goodsrecord_error']		= 'gifts information error';
$lang['admin_pointprod_list_title']				= 'gifts list';
$lang['admin_pointprod_add_title']				= 'new gifts';
$lang['admin_pointorder_list_title']			= 'exchange list';
$lang['admin_pointorder_ordersn']				= 'exchange order number';
$lang['admin_pointorder_membername']			= 'member name';
$lang['admin_pointorder_payment']				= 'terms of payment';
$lang['admin_pointorder_orderstate']			= 'state';
$lang['admin_pointorder_exchangepoints']		= 'exchange points';
$lang['admin_pointorder_shippingfee']			= 'freight charge';
$lang['admin_pointorder_addtime']				= 'time of exchange';
$lang['admin_pointorder_shipping_code']			= 'tracking number';
$lang['admin_pointorder_shipping_time']			= 'delivery time';
$lang['admin_pointorder_gobacklist']			= 'back to list';
/**
 * exchange information status
 */
$lang['admin_pointorder_state_submit']			= 'submitted';
$lang['admin_pointorder_state_waitpay']			= 'wait for payment';
$lang['admin_pointorder_state_canceled']		= 'canceled';
//$lang['admin_pointorder_state_waitfinish']	= 'wait for finish';
$lang['admin_pointorder_state_paid']			= 'paid';
$lang['admin_pointorder_state_confirmpay']		= 'wait for confirmation';
$lang['admin_pointorder_state_confirmpaid']		= 'confirm payment';
$lang['admin_pointorder_state_waitship']		= 'wait for delivery';
$lang['admin_pointorder_state_shipped']			= 'delivered';
$lang['admin_pointorder_state_waitreceiving']	= 'wait for receiving';
$lang['admin_pointorder_state_finished']		= 'finished';
$lang['admin_pointorder_state_unknown']			= 'unknown';
/**
 * exchange information list
 */
$lang['admin_pointorder_changefee']					= 'change freight charge';
$lang['admin_pointorder_changefee_success']			= 'change freight charge succeed';
$lang['admin_pointorder_changefee_freightpricenull']= 'please add freight charge';
$lang['admin_pointorder_changefee_freightprice_error']= 'The freight charge must be numeric and greater than or equal to 0';
$lang['admin_pointorder_cancel_tip1']				= 'cancel the information of exchange gifts';
$lang['admin_pointorder_cancel_tip2']				= 'increase points';
$lang['admin_pointorder_cancel_title']				= 'cancel exchange';
$lang['admin_pointorder_cancel_confirmtip']			= 'Confirm to cancel the exchange information?';
$lang['admin_pointorder_cancel_success']			= 'cancellation succeed';
$lang['admin_pointorder_cancel_fail']				= 'cancellation failed';
$lang['admin_pointorder_confirmpaid']				= 'confirm payment';
$lang['admin_pointorder_confirmpaid_confirmtip']	= 'Whether to confirm that the payment of information exchange has been received?';
$lang['admin_pointorder_confirmpaid_success']		= 'verification succeed';
$lang['admin_pointorder_confirmpaid_fail']			= 'verification failed';
$lang['admin_pointorder_ship_title']				= 'shipment';
$lang['admin_pointorder_ship_modtip']				= 'modify logistics';
$lang['admin_pointorder_ship_code_nullerror']		= 'please add the logistics sheet';
$lang['admin_pointorder_ship_success']				= 'information operation succeed';
$lang['admin_pointorder_ship_fail']					= 'information operation failed';
$lang['admin_pointorder_shipping_timetip']			= 'Note: The default is the current time';
$lang['admin_pointorder_shipping_description']		= 'discription';
/**
 * exchange information deletion
 */
$lang['admin_pointorder_del_success']		= 'delete succeed';
$lang['admin_pointorder_del_fail']			= 'delete failed';
/**
 * detailed exchange information
 */
$lang['admin_pointorder_info_title']			= 'detailed exchange information';
$lang['admin_pointorder_info_ordersimple']		= 'exchange information';
$lang['admin_pointorder_info_orderdetail']		= 'exchange details';
$lang['admin_pointorder_info_memberinfo']		= 'member information';
$lang['admin_pointorder_info_memberemail']		= 'member Email';
$lang['admin_pointorder_info_ordermessage']		= 'message';
$lang['admin_pointorder_info_paymentinfo']		= 'payment information';
$lang['admin_pointorder_info_paymenttime']		= 'time of payment';
$lang['admin_pointorder_info_paymentmessage']	= 'payment message';
$lang['admin_pointorder_info_shipinfo']			= 'the consignee and shipping information';
$lang['admin_pointorder_info_shipinfo_truename']= 'the consignee';
$lang['admin_pointorder_info_shipinfo_areainfo']= 'location';
$lang['admin_pointorder_info_shipinfo_zipcode']= 'zip code';
$lang['admin_pointorder_info_shipinfo_telphone']= 'telphone number';
$lang['admin_pointorder_info_shipinfo_mobphone']= 'mobile phone number';
$lang['admin_pointorder_info_shipinfo_address']= 'full address';
$lang['admin_pointorder_info_shipinfo_description']	= 'shipping description';
$lang['admin_pointorder_info_prodinfo']				= 'gifts information';
$lang['admin_pointorder_info_prodinfo_exnum']		= 'number of exchange';

$lang['pay_bank_user']			= 'name of remittance applicant';
$lang['pay_bank_bank']			= 'paying bank';
$lang['pay_bank_account']		= 'remitter account';
$lang['pay_bank_num']			= 'remit money';
$lang['pay_bank_date']			= 'date of remittance';
$lang['pay_bank_extend']		= 'others';
$lang['pay_bank_order']			= 'remittance order number';
$lang['pay_bank_bank_tips']		= 'to fill in the detailed name of Branch';