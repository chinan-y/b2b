<?php
defined('GcWebShop') or exit('Access Invalid!');
/**
 * 
 */
$lang['predeposit_no_record']	 			= 'no matching record';
$lang['predeposit_unavailable']	 			= 'the pre-deposit function is not enabled';
$lang['predeposit_parameter_error']			= 'parameter error';
$lang['predeposit_record_error']			= 'record information error';
$lang['predeposit_userrecord_error']		= 'member information is wrong';
$lang['predeposit_payment']					= 'payment method';
$lang['predeposit_addtime']					= 'create time';
$lang['predeposit_apptime']					= 'application time';
$lang['predeposit_checktime']					= 'review time';
$lang['predeposit_paytime']					= 'payment time';
$lang['predeposit_addtime_to']				= 'to';
$lang['predeposit_trade_no']				= 'transaction number';
$lang['predeposit_adminremark']				= 'administrator notes';
$lang['predeposit_recordstate']				= 'record status';
$lang['predeposit_paystate']				= 'status';
$lang['predeposit_backlist']				= 'back to list';
$lang['predeposit_pricetype']				= 'pre - deposit type';
$lang['predeposit_pricetype_available']		= 'available amount';
$lang['predeposit_pricetype_freeze']		= 'frozen amount';
$lang['predeposit_price']					= 'amount of money';
$lang['predeposit_payment_error']			= 'the payment method is wrong';
/**
 * 
 */
$lang['predeposit_rechargesn']					= 'recharge number';
$lang['predeposit_rechargewaitpaying']			= 'unpaid';
$lang['predeposit_rechargepaysuccess']			= 'paid';
$lang['predeposit_rechargestate_auditing']		= 'under review';
$lang['predeposit_rechargestate_completed']		= 'completed';
$lang['predeposit_rechargestate_closed']		= 'closed';
$lang['predeposit_recharge_price']				= 'recharge amount';
$lang['predeposit_recharge_huikuanname']		= 'name of remitter';
$lang['predeposit_recharge_huikuanbank']		= 'remittance bank';
$lang['predeposit_recharge_huikuandate']		= 'remittance date';
$lang['predeposit_recharge_memberremark']		= 'membership notes';
$lang['predeposit_recharge_success']			= 'recharge successful';
$lang['predeposit_recharge_fail']				= 'recharge failed';
$lang['predeposit_recharge_pay']				= '&nbsp;support';
$lang['predeposit_recharge_view']				= 'view details';
$lang['predeposit_recharge_paydesc']			= 'pre - deposit recharge order';
$lang['predeposit_recharge_pay_offline']		= 'to be confirmed';
/**
 * 
 */
$lang['predeposit_recharge_add_pricenull_error']			= 'please add the amount of recharge';
$lang['predeposit_recharge_add_pricemin_error']				= '0.01the amount of the recharge is greater than or equal to 0.01';
/**
 * 
 */
$lang['predeposit_recharge_del_success']		= 'recharge information deleted successfully';
$lang['predeposit_recharge_del_fail']		= 'fill message deletion failed';
/**
 * 
 */
$lang['predeposit_cashsn']				= 'requisition Number';
$lang['predeposit_cashmanage']			= 'mention management';
$lang['predeposit_cashwaitpaying']		= 'wait for payment';
$lang['predeposit_cashpaysuccess']		= 'payment successful';
$lang['predeposit_cashstate_auditing']	= 'under review';
$lang['predeposit_cashstate_completed']	= 'completed';
$lang['predeposit_cashstate_closed']		= 'closed';
$lang['predeposit_cash_price']				= 'withdrawal amount';
$lang['predeposit_cash_shoukuanname']			= 'name of account holder';
$lang['predeposit_cash_shoukuanbank']			= 'beneficiary bank';
$lang['predeposit_cash_shoukuanaccount']		= 'receipt account';
$lang['predeposit_cash_shoukuanname_tip']	= '4(、、)<br/>，、“”、“”(bank of china, china construction bank, industrial and commercial bank of china and agricultural bank of china) please fill in the details of the bank account name, virtual account such as alipay, tenpay fill out "alipay", "money" can be';
$lang['predeposit_cash_shoukuanaccount_tip']	= '(、)bank account or virtual account (alipay, tenpay account)';
$lang['predeposit_cash_shoukuanauser_tip']	= 'the name of the account holder';
$lang['predeposit_cash_shortprice_error']		= 'pre-deposit amount is insufficient';
$lang['predeposit_cash_price_tip']				= 'currently available amount';

$lang['predeposit_cash_availablereducedesc']	=  'membership application to reduce the amount of pre-deposit';
$lang['predeposit_cash_freezeadddesc']	=  'membership application is added to increase the amount of pre-deposit';
$lang['predeposit_cash_availableadddesc']	=  'members are deleted to add the amount of pre-deposit';
$lang['predeposit_cash_freezereducedesc']	=  'the member is deleted and the amount of the pre-deposit is reduced';

/**
 * 
 */
$lang['predeposit_cash_add_shoukuannamenull_error']		= 'please enter your account name';
$lang['predeposit_cash_add_shoukuanbanknull_error']		= 'please fill in the receiving bank';
$lang['predeposit_cash_add_pricemin_error']				= '0.01the amount of money is greater than or equal to 0.01';
$lang['predeposit_cash_add_enough_error']				= 'account balance is insufficient';
$lang['predeposit_cash_add_pricenull_error']			= 'please enter the amount of cash';
$lang['predeposit_cash_add_shoukuanaccountnull_error']	= 'please enter the alipay account';
$lang['predeposit_cash_add_success']					= '，your request has been successfully submitted. please wait for the system to process';
$lang['predeposit_cash_add_fail']						= 'timing information added failed';
/**
 * 
 */
$lang['predeposit_cash_del_success']	= 'retrieve information deleted successfully';
$lang['predeposit_cash_del_fail']		= 'recruitment message deletion failed';
/**
 * 
 */
$lang['predeposit_payment_pay_fail']		= 'recharge failed';
$lang['predeposit_payment_pay_success']		= '，recharge is successful, is going to my order';
$lang['predepositrechargedesc']	=  'recharge';
/**
 *  
 */
$lang['predeposit_log_stage'] 			= 'type ';
$lang['predeposit_log_stage_recharge']	= 'recharge';
$lang['predeposit_log_stage_cash']		= 'withdraw';
$lang['predeposit_log_stage_order']		= 'consumption';
$lang['predeposit_log_stage_artificial']= 'manual modification';
$lang['predeposit_log_stage_system']	= 'system';
$lang['predeposit_log_stage_income']	= 'income';
$lang['predeposit_log_desc']			= 'change the description';
?>