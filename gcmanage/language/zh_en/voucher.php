<?php
defined('GcWebShop') or exit('Access Invalid!');
$lang['admin_voucher_unavailable']    = ' 、， 。。。need to open voucher, bonus points, jumping to settings page';
$lang['admin_voucher_jb_unavailable']    = '， 。。。need to open gold coins, jumping to settings page';
$lang['admin_voucher_applystate_new']    = 'pending verify';
$lang['admin_voucher_applystate_verify']    = 'verified';
$lang['admin_voucher_applystate_cancel']    = 'canceled';
$lang['admin_voucher_quotastate_activity']	= 'normal';
$lang['admin_voucher_quotastate_cancel']    = 'cancel';
$lang['admin_voucher_quotastate_expire']    = 'exoire';
$lang['admin_voucher_templatestate_usable']	= 'usable';
$lang['admin_voucher_templatestate_disabled']= 'disabled';
$lang['admin_voucher_storename']			= 'storename';
$lang['admin_voucher_cancel_confirm']    	= '？confirm a cancel?';
$lang['admin_voucher_verify_confirm']    	= '？confirm a verify?';
//menu
$lang['admin_voucher_apply_manage']= 'apply package managment';
$lang['admin_voucher_quota_manage']= 'package management';
$lang['admin_voucher_template_manage']= 'store voucher';
$lang['admin_voucher_template_edit']= 'edit voucher';
$lang['admin_voucher_setting']		= 'setting';
$lang['admin_voucher_pricemanage']		= 'denomination setting';
$lang['admin_voucher_priceadd']		= 'add denomination';
$lang['admin_voucher_priceedit']		= 'denomination setting';
$lang['admin_voucher_styletemplate']	= 'style template';
/**
 * setting
 */
$lang['admin_voucher_setting_price_error']		= '0the purchse price should be an integer greater than zero';
$lang['admin_voucher_setting_storetimes_error']	= '0the number of activities should be an integer greater than zero';
$lang['admin_voucher_setting_buyertimes_error']	= '0the number of getting voucher should be an integer greater than zero';
$lang['admin_voucher_setting_price']			= '（/）purchase price(yuan per month)';
$lang['admin_voucher_setting_price_tip']		= '，the expense of purchasing voucher. the seller can release voucher promotion activities during the buying cycle.';
$lang['admin_voucher_setting_storetimes']		= 'number of monthly activity';
$lang['admin_voucher_setting_storetimes_tip']	= 'the maximum number of voucher promotion activity';
$lang['admin_voucher_setting_buyertimes']		= 'the maximum quantity of buyer getting';
$lang['admin_voucher_setting_buyertimes_tip']	= 'the buyers only owns the maximum number of unused voucher in the same store.';
$lang['admin_voucher_setting_default_styleimg']	= 'default style temolate of voucher';
/**
 * voucher denomination
 */
$lang['admin_voucher_price_error']   		= '0the voucher denomination should be an integer greater than zero';
$lang['admin_voucher_price_describe_error'] = 'description can not be null';
$lang['admin_voucher_price_describe_lengtherror'] = '255description of voucher can neither be null nor greater than 225 characters';
$lang['admin_voucher_price_points_error']   = '0the default exchange bonus points should be an integer greater than zero';
$lang['admin_voucher_price_exist']    		= 'the voucher denomination has existed';
$lang['admin_voucher_price_title']    		= 'voucher denomination';
$lang['admin_voucher_price_describe']    	= 'describe';
$lang['admin_voucher_price_points']    		= 'exchange bonus points';
$lang['admin_voucher_price_points_tip']    	= '，use up bonus points when exchange voucher';
$lang['admin_voucher_price_tip1']    	= '，adminisrator regulate the denomination of voucher. the store distribute voucher whose denomination determined by the regulated denomination. ';
/**
 * voucher package apply
 */
$lang['admin_voucher_apply_goldnotenough']	= '，the member gold coins are not enough. operation failure';
$lang['admin_voucher_apply_goldlog']    	= '%s，%s，%svoucher purchase activity for %s months, price %s gold coins, amount to %s gold coins';
$lang['admin_voucher_apply_message']    	= '%s，%s，%s，you have purchased successfully voucher activity for %s months, price %s gold coins, amount to %s gold coins. time will be calculated after being verified';
$lang['admin_voucher_apply_verifysucc']    	= '，application for verification is successful.activity package has been released';
$lang['admin_voucher_apply_verifyfail']    	= 'application for verify fail';
$lang['admin_voucher_apply_cancelsucc']    	= 'application canceled successfully';
$lang['admin_voucher_apply_cancelfail']    	= 'application for cancel fail';
$lang['admin_voucher_apply_list_tip1']    	= '，verify the package from sellers. message will be sent to sellers by system after verification';
$lang['admin_voucher_apply_list_tip2']    	= '，，the verification will fail when the gold coins are not enough. the voucher released successfully by sellers will present in the bonus points centre';
$lang['admin_voucher_apply_num']    		= 'application quantity';
$lang['admin_voucher_apply_date']    		= 'application date';
/**
 * voucher package
 */
$lang['admin_voucher_quota_cancelsucc']    	= 'package cancel successfully';
$lang['admin_voucher_quota_cancelfail']    	= 'package cancel faid';
$lang['admin_voucher_quota_tip1']    	= '，unrecoverable after cancel operation, please be careful';

$lang['admin_voucher_quota_startdate']    	= 'start date';
$lang['admin_voucher_quota_enddate']    	= 'end date';
$lang['admin_voucher_quota_timeslimit']    	= 'limitation on cativity number';
$lang['admin_voucher_quota_publishedtimes']    	= 'released activity number';
$lang['admin_voucher_quota_residuetimes']    	= 'residue degree';
/**
 * voucher
 */
$lang['admin_voucher_template_points_error']	= '0the bonus points to be exchanged should be an integer greater than ';
$lang['admin_voucher_template_title']			= 'voucher name';
$lang['admin_voucher_template_enddate']			= 'period of validity';
$lang['admin_voucher_template_price']			= 'denomination';
$lang['admin_voucher_template_total']			= 'total amount';
$lang['admin_voucher_template_eachlimit']		= 'limitaions on getting';
$lang['admin_voucher_template_orderpricelimit']	= 'consumption amount';
$lang['admin_voucher_template_describe']		= 'voucher desciption';
$lang['admin_voucher_template_styleimg']		= 'select voucher skin';
$lang['admin_voucher_template_image']			= 'voucher image';
$lang['admin_voucher_template_points']			= 'required bonus points for exchange';
$lang['admin_voucher_template_adddate']			= 'add date';
$lang['admin_voucher_template_list_tip']		= ',,users can not get this voucher whent it fails to be set manually';
$lang['admin_voucher_template_giveoutnum']		= 'giveout';
$lang['admin_voucher_template_usednum']			= 'used';
?>
