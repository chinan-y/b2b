<?php
defined('GcWebShop') or exit('Access Invalid!');
$lang['voucher_unavailable']    = ' the voucher function is not available';
$lang['voucher_applystate_new']    = ' wait to review';
$lang['voucher_applystate_verify']    = ' has reviewed';
$lang['voucher_applystate_cancel']    = 'has cancealled';
$lang['voucher_quotastate_activity']	= ' normal';
$lang['voucher_quotastate_cancel']    = ' canceal';
$lang['voucher_quotastate_expire']    = ' finish';
$lang['voucher_templatestate_usable']	= 'valid';
$lang['voucher_templatestate_disabled']= ' invalid';
$lang['voucher_quotalist']= ' bundling list';
$lang['voucher_applyquota']= ' apply for bundling';
$lang['voucher_applyadd']= ' buy bundling';
$lang['voucher_templateadd']= ' new added voucher';
$lang['voucher_templateedit']= ' edit voucher';
$lang['voucher_templateinfo']= '  voucher details';
/**
 * 
 */
$lang['voucher_apply_num_error']= '，1-12the number can not be empty ,and the number is between 1-12';
$lang['voucher_apply_goldnotenough']= "%s，，At present you have a gold coin number% s, not enough to pay the transaction, please recharge first";
$lang['voucher_apply_fail']= ' bundling application fail';
$lang['voucher_apply_succ']= '，bundling application succeed, wait to review ';
$lang['voucher_apply_date']= ' application date';
$lang['voucher_apply_num']    		= ' application number';
$lang['voucher_apply_addnum']    		= ' bundling purchase number';
$lang['voucher_apply_add_tip1']    		= '(30)，12，the purchase unit is for a month (30 days) and a maximum of 12 months is purchased. You can issue vouchers in months during the purchase period';
$lang['voucher_apply_add_tip2']    		= ' %syou need to pay monthly';
$lang['voucher_apply_add_tip3']    		= '%smonthly max activity% s times';
$lang['voucher_apply_add_tip4']    		= 'package time begins after approval';
$lang['voucher_apply_add_confirm1']    	= 'you will need to pay in total';
$lang['voucher_apply_add_confirm2']    	= ' ,？ensure buying';
$lang['voucher_apply_goldlog']    		= '%s，%s，%spurchase vouchers activities% s months, the price% s gold coins, spent a total of% s gold coins';
$lang['voucher_apply_buy_succ']			= 'package purchase is successful';

/**
 * 
 */
$lang['voucher_quota_startdate']    	= ' beginning time';
$lang['voucher_quota_enddate']    		= ' ending time';
$lang['voucher_quota_timeslimit']    	= ' activity time limit';
$lang['voucher_quota_publishedtimes']   = ' published activity time';
$lang['voucher_quota_residuetimes']    	= ' rest activity time';
/**
 * 
 */
$lang['voucher_template_quotanull']			= '，there is currently no package available. Please apply for packages first';
$lang['voucher_template_noresidual']		= "%s，the current package is full of activity and can not be advertised";
$lang['voucher_template_pricelisterror']	= '，platform voucher denomination settings problems, please contact customer service to help solve';
$lang['voucher_template_title_error'] 		= "50the template name can not be empty and can not be greater than 50 characters";
$lang['voucher_template_total_error'] 		= "the quantity that can be issued can not be empty and must be an integer";
$lang['voucher_template_price_error']		= "，the template denomination can not be empty and must be an integer, and the denomination can not be greater than the quota";
$lang['voucher_template_limit_error'] 		= "templates using consumer quotas can not be empty and must be numbers";
$lang['voucher_template_describe_error'] 	= "255The template description can not be empty and can not be greater than 255 characters";
$lang['voucher_template_title']			= 'voucher name';
$lang['voucher_template_enddate']		= ' invalid time';
$lang['voucher_template_enddate_tip']		= '，the validity period should be valid for the course of validity of the package';
$lang['voucher_template_price']			= ' price';
$lang['voucher_template_total']			= ' total amount';
$lang['voucher_template_eachlimit']		= ' each limit';
$lang['voucher_template_eachlimit_item']= ' without limit';
$lang['voucher_template_eachlimit_unit']= 'pieces';
$lang['voucher_template_orderpricelimit']	= ' consuption amount';
$lang['voucher_template_describe']		= ' voucher description';
$lang['voucher_template_styleimg']		= ' choose voucher skin';
$lang['voucher_template_styleimg_text']	= 'store coupon ';
$lang['voucher_template_image']			= ' coupon image';
$lang['voucher_template_image_tip']		= '，160*160px。the picture will be displayed in the voucher module of the points center, with a recommended size of 160 * 160px';
$lang['voucher_template_list_tip1'] = "1、manually set vouchers invalid, the user will not be able to receive the voucher, but has received vouchers can still use the ";
$lang['voucher_template_list_tip2'] = "2、voucher vouchers and issued vouchers are automatically expired";
$lang['voucher_template_backlist'] 	= " return to list";
$lang['voucher_template_giveoutnum']= ' has claimed';
$lang['voucher_template_usednum']	= 'has used';
/**
 * 
 */
$lang['voucher_voucher_state'] = " condition";
$lang['voucher_voucher_state_unused'] = " unused";
$lang['voucher_voucher_state_used'] = "has used ";
$lang['voucher_voucher_state_expire'] = "has expired ";
$lang['voucher_voucher_price'] = " amount";
$lang['voucher_voucher_storename'] = "applicable stores";
$lang['voucher_voucher_indate'] = " imvalid period";
$lang['voucher_voucher_usecondition'] = "using condition";
$lang['voucher_voucher_usecondition_desc'] = " order";
$lang['voucher_voucher_vieworder'] = " check order";
$lang['voucher_voucher_readytouse'] = " use instantly";
$lang['voucher_voucher_code'] = " code";
?>
