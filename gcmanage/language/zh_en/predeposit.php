<?php
defined('GcWebShop') or exit('Access Invalid!');
/**
 * pre-deposit  function publicity
 */
$lang['admin_predeposit_no_record']	 		= 'no matching record';
$lang['admin_predeposit_unavailable']	 	= 'The system is unavailable for pre-deposit function, it is jumping to the pre-deposit setting...';
$lang['admin_predeposit_parameter_error']	= 'parameter error';
$lang['admin_predeposit_record_error']		= 'record information error';
$lang['admin_predeposit_userrecord_error']	= 'member information error';
$lang['admin_predeposit_membername']			= 'member name';
$lang['admin_predeposit_addtime']				= 'create time';
$lang['admin_predeposit_maketime']				= 'occured time';
$lang['admin_predeposit_changetime']			= 'change time';
$lang['admin_predeposit_apptime']				= 'application time';
$lang['admin_predeposit_paymtime']				= 'tranfer time';
$lang['admin_predeposit_checktime']				= 'check time';
$lang['admin_predeposit_paytime']				= 'check time';
$lang['admin_predeposit_payment']				= 'terms of payment';
$lang['admin_predeposit_payed']				= 'change the payment state';
$lang['admin_predeposit_addtime_to']			= 'to';
$lang['admin_predeposit_screen']				= 'screening criteria';
$lang['admin_predeposit_paystate']				= 'payment state';
$lang['admin_predeposit_remark']				= 'remark';
$lang['admin_predeposit_recordstate']			= 'record state';
$lang['admin_predeposit_backlist']				= 'back to list';
$lang['admin_predeposit_adminname']				= 'operations administrator';
$lang['admin_predeposit_adminremark']			= 'adiministrator remarks';
$lang['admin_predeposit_memberremark']			= 'member remarks';
$lang['admin_predeposit_remark']				= 'remark';
$lang['admin_predeposit_shortprice_error']		= 'The pre-deposit amount is insufficient, please check the user account information';
$lang['admin_predeposit_pricetype']				= 'pre-deposit type';
$lang['admin_predeposit_pricetype_available']	= 'available amount';
$lang['admin_predeposit_pricetype_freeze']		= 'blocked amount';
$lang['admin_predeposit_price']					= 'amount';
$lang['admin_predeposit_sn']					= 'the recharge order number';
$lang['admin_predeposit_cs_sn'] 				= 'the cash-out order number';
$lang['admin_predeposit_cash_check'] 			= 'check';
$lang['admin_predeposit_cash_pay'] 				= 'change the payment state';
$lang['admin_predeposit_enuth_error'] 			= 'the available amount is insufficient';
$lang['admin_predeposit_check_tips'] 			= 'After the audit, the equal member pre-deposit will be frozen into the financial payment link, confirm the operation?';
$lang['admin_predeposit_pay_tips'] 				= 'After submittion, the cash-out order will be reset to the paid state and a corresponding cash-out amount will be deducted from the member pre-deposit by the system, confirm the operation?';
$lang['nc_prompts']								= 'operation tips';
/**
 * recharge function publicity
 */
$lang['admin_predeposit_rechargelist']				= 'recharge management';
$lang['admin_predeposit_rechargewaitpaying']		= 'unpaid';
$lang['admin_predeposit_rechargepaysuccess']		= 'pay success';
$lang['admin_predeposit_rechargestate_auditing']	= 'auditing';
$lang['admin_predeposit_rechargestate_completed']	= 'completed';
$lang['admin_predeposit_rechargestate_closed']		= 'closed';
$lang['admin_predeposit_recharge_onlinecode']		= 'online trade serial number';
$lang['admin_predeposit_recharge_price']			= 'recharge amount';
$lang['admin_predeposit_recharge_huikuanname']		= 'name of remittance applicant';
$lang['admin_predeposit_recharge_huikuanbank']		= 'paying bank';
$lang['admin_predeposit_recharge_huikuandate']		= 'date of remittance';
$lang['admin_predeposit_recharge_memberremark']		= 'member remarks';
$lang['admin_predeposit_recharge_help1']			= 'can click to browse the detailed information about the recharge';
$lang['admin_predeposit_recharge_help2']			= 'If the system platform has confirmed the recharge receipt, but the system recharge order still in unpaid state, you can click to view and modify it to paid state manually';
$lang['admin_predeposit_recharge_searchtitle']			= 'conditional filtering';
/**
 * recharge information editing
 */
$lang['admin_predeposit_recharge_edit_logdesc']		= 'modify the payment state of member recharge to reduce pre-deposit';
$lang['admin_predeposit_recharge_edit_success']		= 'the recharge information modification succeed';
$lang['admin_predeposit_recharge_edit_fail']		= 'the recharge information modification failed';
$lang['admin_predeposit_recharge_edit_state']		= 'modify the state of recharge order';
$lang['admin_predeposit_recharge_notice']		= 'only visible to administrator';
/**
 * recharge information deletion
 */
$lang['admin_predeposit_recharge_del_success']		= 'delete recharge information succeed';
$lang['admin_predeposit_recharge_del_fail']		= 'delete recharge infomation failed';
/**
 * cash-out function publicity
 */
$lang['admin_predeposit_cashmanage']			= 'cash-out management';
$lang['admin_predeposit_cashwaitpaying']		= 'awaiting payment';
$lang['admin_predeposit_cashpaysuccess']		= 'pay success';
$lang['admin_predeposit_cashstate_auditing']	= 'auditing';
$lang['admin_predeposit_cashstate_completed']	= 'completed';
$lang['admin_predeposit_cashstate_closed']		= 'closed';
$lang['admin_predeposit_cash_price']			= 'cash-out amount';
$lang['admin_predeposit_cash_shoukuanname']			= 'name of beneficiary';
$lang['admin_predeposit_cash_shoukuanbank']			= 'terms of payment';
$lang['admin_predeposit_cash_shoukuanaccount']		= 'account of beneficiary';
$lang['admin_predeposit_cash_remark_tip1']			= 'only visible to administrator';
$lang['admin_predeposit_cash_remark_tip2']			= 'The remark information will be displyed on the related pre-deposit details page, members and administrators can notice it';
$lang['admin_predeposit_cash_help1']			= 'The payment state of unpaid cash-out order can be modified by clicking to view choice,';
$lang['admin_predeposit_cash_help2']			= 'click Delete to delete the unpaid cash-out order';
$lang['admin_predeposit_cash_confirm']			= 'Have you confirmed to transfer your cash-out amount to the cash-out account of the buyer?';
/**
 * cash-out information deletion
 */
$lang['admin_predeposit_cash_del_success']	= 'delete the cash-out information succeed';
$lang['admin_predeposit_cash_del_fail']		= 'delete the cash-out infomation failed';
$lang['admin_predeposit_cash_del_reducefreezelogdesc']		= 'delete the member cash-out record successfully to reduce the blocked pre-deposit amount';
$lang['admin_predeposit_cash_del_adddesc']	= 'delete the member cash-out record successfully to increase pre-deposit amount';
/**
 * cash-out information editing 
 */
$lang['admin_predeposit_cash_edit_reducefreezelogdesc']	= 'modify the state of member cash-out record to successfully paid to reduce the blocked pre-deposit amount';
$lang['admin_predeposit_cash_edit_success']		= 'the cash-out information modification succeed';
$lang['admin_predeposit_cash_edit_fail']		= 'the cash-out information modification failed';
$lang['admin_predeposit_cash_edit_state']		= 'modify the state of cash-out order';
/**
 * manual modification
 */
$lang['admin_predeposit_artificial'] 	= 'manual modification';
$lang['admin_predeposit_artificial_membername_error'] 	= 'The member information is incorrect, please complete the member name again';
$lang['admin_predeposit_artificial_membernamenull_error'] 	= 'please enter a member name';
$lang['admin_predeposit_artificial_pricenull_error'] 		= 'please add amount';
$lang['admin_predeposit_artificial_pricemin_error'] 		= '0The amount must be greater than 0';
$lang['admin_predeposit_artificial_shortprice_error']		= 'The amount is insufficient, the current available amount for member is';
$lang['admin_predeposit_artificial_shortfreezeprice_error']	= 'The amount is insufficient, the current available amount for member is';
$lang['admin_predeposit_artificial_success']				= 'member pre-deposit modification succeed';
$lang['admin_predeposit_artificial_fail']					= 'member pre-deposit modification failed';
$lang['admin_predeposit_artificial_operatetype']			= 'change type';
$lang['admin_predeposit_artificial_operatetype_add']		= 'add';
$lang['admin_predeposit_artificial_operatetype_reduce']		= 'reduce';
$lang['admin_predeposit_artificial_member_tip_1']			= 'member';
$lang['admin_predeposit_artificial_member_tip_2']			= ',the current available pre-deposit is';
$lang['admin_predeposit_artificial_member_tip_3']			= ',the bloked pre-deposit is';
$lang['admin_predeposit_artificial_notice']					= 'You can choose to modify the available amount or blocked amount';
/**
 *  account details
 */
$lang['admin_predeposit_log_help1']			= 'It displays the detailed change log information of pre-deposit';
$lang['admin_predeposit_log_stage'] 	= 'type';
$lang['admin_predeposit_log_stage_recharge']	= 'recharge';
$lang['admin_predeposit_log_stage_cash']		= 'cash-out';
$lang['admin_predeposit_log_stage_order']		= 'consumption';
$lang['admin_predeposit_log_stage_artificial']	= 'manual modification';
$lang['admin_predeposit_log_stage_system']		= 'system';
$lang['admin_predeposit_log_stage_income']	= 'income';
$lang['admin_predeposit_log_desc']		= 'description';
?>