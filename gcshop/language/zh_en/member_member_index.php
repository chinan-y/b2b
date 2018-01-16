<?php
defined('GcWebShop') or exit('Access Invalid!');
/**
 * common language
 */

/**
 * groupbuy
 */
$lang['member_groupbuy_all_group']		= 'groubuy all';
$lang['member_groupbuy_not_publish']	= 'unpublished';
$lang['member_groupbuy_canceled']		= 'canceled';
$lang['member_groupbuy_going']			= ' progressing';
$lang['member_groupbuy_finished']		= 'finished';
$lang['member_groupbuy_ended']			= 'ended';
$lang['member_groupbuy_person_num']		= '()number of person';
$lang['member_groupbuy_amount']			= '()amount';
$lang['member_groupbuy_jian']			= 'number';
$lang['member_groupbuy_view']			= 'view';
$lang['member_groupbuy_ensure_exit']	= 'exit this groupbuy activity?';
$lang['member_groupbuy_exit_group']		= 'exit groupbuy';
$lang['member_groupbuy_buy']			= 'buy';
$lang['member_groupbuy_view_order']		= 'view thw order';
$lang['member_groupbuy_search']			= 'search';
$lang['member_groupbuy_group_name']		= 'activity name';
$lang['member_groupbuy_group_state']	= 'activity state';
$lang['member_groupbuy_end_time']		= 'end time';
$lang['member_groupbuy_order_num']		= 'order number';
$lang['member_groupbuy_group_num']		= 'groupbuy number';
$lang['member_groupbuy_order_info']		= 'order details';
$lang['member_groupbuy_handle']			= 'operation';
$lang['member_groupbuy_no_result']		= 'not found the match-condition goods';
/**
 * evaluation
 */
$lang['member_evaluation_order_not_exists']		= 'this order does not exist';
$lang['member_evaluation_order_not_finish']		= 'order is not finished';
$lang['member_evaluation_order_evaluated']		= 'this order has been evaluated';
$lang['member_evaluation_evaluation_not_null']	= 'evaluation can not be null';
$lang['member_evaluation_evaluat_success']		= 'evaluate successfully';
$lang['member_evaluation_order_desc']			= 'order details';
$lang['member_evaluation_evaluat_goods']		= 'evaluate goods';
$lang['member_evaluation_store_name']			= 'store name';
$lang['member_evaluation_storekeeper']			= 'shopkeeper';
$lang['member_evaluation_online_message']		= 'contact';
$lang['member_evaluation_amount']				= 'amount';
$lang['member_evaluation_price']				= 'price';
$lang['member_evaluation_my_evaluation']		= 'my evaluation';
$lang['member_evaluation_good']					= 'good evaluations';
$lang['member_evaluation_increase1']			= ' plus one point';
$lang['member_evaluation_normal']				= 'medium';
$lang['member_evaluation_increase0']			= 'not a plus';
$lang['member_evaluation_bad']					= 'bad review';
$lang['member_evaluation_decrease1']			= 'one point deduction';
$lang['member_evaluation_attention']			= 'attention';
$lang['member_evaluation_rule']					= '，、、。<br/>，。<br/>：<br/>，，。please give true, objective and careful evaluation, according to this transaction. <br/> your evaluation will be the reference of other members and influence on the credits of sellers. <br/> accumulated credits and  counting rules: <br/> medium review is not counted, but influences on the praise rate of sellers, please be carefull.';
$lang['member_evaluation_submit']				= 'submit'; 
$lang['member_evaluation_evaluat_later']		= 'evaluate later';
/**
 * address
 */
$lang['member_address_wrong_argument']	= 'parameter error';
$lang['member_address_receiver_null']	= 'consignee name can not be null';
$lang['member_address_order_null']	    = 'payer name can not be null';
$lang['member_address_true_idnum_null']	= 'the ID number of payer can not be null';
$lang['member_address_wrong_area']		= 'choice of area is wrong';
$lang['member_address_area_null']		= 'information of area can not be null';
$lang['member_address_address_null']	= 'detailed address can not be null';
$lang['member_address_modify_fail']		= 'fail to modify address';
$lang['member_address_add_fail']		= 'fail to add new address';
$lang['member_address_del_fail']		= 'fail to delete address';
$lang['member_address_del_succ']		= 'delete successfully';
$lang['member_address_new_address']		= 'add new address';
$lang['member_address_receiver_name']	= 'consignee';
$lang['member_address_order_name']	    = 'payer';//add new
$lang['member_address_location']		= 'area';
$lang['member_address_address']			= 'detailed address';
$lang['member_address_zipcode']			= 'zip code';
$lang['member_address_phone']			= 'phone number';
$lang['member_address_mobile']			= 'mobile';
$lang['member_address_edit_address']	= 'edit address';
$lang['member_address_no_address']		= 'you have not add address';
$lang['member_address_input_name']		= 'please input the same name with which in ID csrd';
$lang['member_address_order_input']		= 'please fill in the real name of payer of this order correctly';
$lang['member_address_order_notice']	= '：，notice: one payer can have multiple consignees. the system will default payer as the consignee if you not input the consignee.';
$lang['member_address_please_choose']	= 'please choose';
$lang['member_address_not_repeat']		= '，please input real address. not need to repeate area';
$lang['member_address_true_mobile_num']	= 'please input the correct number';
$lang['member_address_phone_num']		= 'fixed-line phone';
$lang['member_address_area_num']		= 'area code';
$lang['member_address_sub_phone']		= ' telephone extension';
$lang['member_address_mobile_num']		= 'telephone number';
$lang['member_address_true_idnum']		= 'payer ID card';
$lang['member_address_input_receiver']	= 'please input consignee name';
$lang['member_address_input_order']	    = 'please input payer name';
$lang['member_address_choose_location']	= 'please choose are';
$lang['member_address_input_address']	= 'please input detailed address';
$lang['member_address_zip_code']		= '6zip code consists of six figures';
$lang['member_address_phone_and_mobile']	= '.fixed-line phone or telephone ';
$lang['member_address_must_true_idnum']	= 'please the ID number of payer';
$lang['member_address_true_idnum_rule']	= 'please input correct ID number';
$lang['member_address_phone_rule']		= '、、、、,6. phone numbe consists of number, plus, minus,spacing, bracket, more than six figures';
$lang['member_address_wrong_mobile']	= 'wrong telephone';
$lang['cart_step1_input_warning_true_idnum']	= 'please input real ID number of this order correctly';
$lang['cart_step1_right_true_idnum']	= 'please input correct ID number';
/**
 * order
 */
$lang['member_order_time']		= 'order time';
$lang['member_order_sn']		= 'order number';
$lang['member_order_state']		= 'order state';
$lang['member_order_all']		= 'all orders';
$lang['member_order_wait_pay']	= 'pending pay';
$lang['member_order_wait_confirm']	= 'wait the sellers to receive payment';
$lang['member_order_submited']	= 'submitted';
$lang['member_order_wait_ship']	= 'pending ship';
$lang['member_order_shipped']	= 'shipped';
$lang['member_order_finished']	= 'finished';
$lang['member_order_canceled']	= 'canceled';
$lang['member_order_refer'] = 'submitted';
$lang['member_order_confirm'] = 'confirmed';
$lang['member_order_search']	= 'search';
$lang['member_order_group']		= 'grouobuy';
$lang['member_order_store_name']	= 'store';
$lang['member_order_evaluated']		= 'evaluated';
$lang['member_order_finished']		= 'finished';
$lang['member_order_goods_info']	= 'goods information';
$lang['member_order_service']	= 'after-sales service';
$lang['member_order_handle']	= 'state and operation';
$lang['member_order_other']	= 'other operation';
$lang['member_order_goods_name']	= 'goods name';
$lang['member_order_price']			= 'unit price';
$lang['member_order_amount']		= 'amount';
$lang['member_order_goods_price']	= 'total price';
$lang['member_order_pay_method']	= 'payment method';
$lang['member_order_sum']			= 'due paymen of order';
$lang['member_order_want_evaluate']	= 'i want to evaluate';
$lang['member_order_pay']			= 'pay';
$lang['member_order_ensure_order']	= 'confirm  reception';
$lang['member_order_cancel_order']	= 'cancel order';
$lang['member_order_view_order']	= 'order details';
$lang['member_order_complain']	= 'complain';
$lang['member_order_no_record']		= 'no match-condition goods';
$lang['member_order_snsshare']  = 'share';
$lang['member_order_show_deliver']	= 'view delivery';
$lang['member_order_shipping_han']	= 'contain';
/**
 * change order state
 */
$lang['member_change_ensure_cancel']	= 'sure to cancel the following order';
$lang['member_change_cancel_reason']	= 'cancel reasons';
$lang['member_change_other_goods']		= 'change to buy other goods';
$lang['member_change_other_shipping']	= 'change ro use other shipping method';
$lang['member_change_other_store']		= 'buy from other stores';
$lang['member_change_other_reason']		= 'other reasons';
$lang['member_change_ensure_receive']	= 'confire reception';
$lang['member_change_ensure_receive1']	= 'receive all the goods?';
$lang['member_change_order_no']			= 'order number';
$lang['remind_member_evaluate']			= '，，！our efforts can do without your honest evaluation. reward points after evaluation. points can exchange voucher';
$lang['member_change_receive_tip']		= '： “”，！tips: if you have not received goods, please not click "confirm", be careful!';
$lang['member_change_ensurereceive_predeposit_logdesc']		= 'confirming to receive goods can add the amount of predeposite';
$lang['member_change_ensurereceive_predepositfreeze_logdesc']	= 'confirming to receive goods can reduce amount of predeposite';
$lang['member_change_parameter_error']	= 'wrong parament';
$lang['member_change_orderrecord_error']	= 'prder information error';
$lang['member_change_order_member_error']	= 'order owners error';
$lang['member_change_changed_error']	= 'order state can not be changed repeatedly';
$lang['member_change_freezepredeposit_short_error']	= '，，frozen fund of deposite is insufficient. you can not confirm. please contact  web site customer service if you have any question';
$lang['member_change_cancel_success']	= 'order';
$lang['member_change_receive_success']	= ',successful transaction. you can evaluate this deal';
/**
 * order details
 */
$lang['member_show_order_info']			= 'order information';
$lang['member_show_order_desc']			= 'order details';
$lang['member_show_order_seller_info']	= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;store &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; onwers';
$lang['member_show_order_voucher_price']	= 'amount of voucher';
$lang['member_show_order_voucher_code']	= 'voucher code';
$lang['member_show_order_voucher_none']	= 'unused voucher';
$lang['member_show_order_wangwang']		= 'wangwang';
$lang['member_show_order_tp_fee']		= 'freight';
$lang['member_show_order_discount']		= 'discount';
$lang['member_show_order_pay_message']	= 'payment time';
$lang['member_show_order_pay_time']		= 'receive time';
$lang['member_show_order_send_time']	= 'shipping time';
$lang['member_show_order_finish_time']	= 'finished time';
$lang['member_show_order_shipping_info']	= 'shipping information';
$lang['member_show_order_shipping_no']		= 'shipping order';
$lang['member_show_order_receiver']	        = 'receiver address';
$lang['member_show_order_buyer_message']	= 'buer message';
$lang['member_show_order_handle_history']	= 'operation history';
$lang['member_show_order_handle_history']	= 'operation history';
$lang['member_show_system']				= 'system';
$lang['member_show_order_at']				= 'at';
$lang['member_order_refund']			= 'refund';
$lang['member_order_return']			= 'return';
$lang['member_refund_confirm']			= 'refunf is being verified';
$lang['member_buyer_confirm']			= 'refund confirm';
$lang['member_return_confirm']			= 'return is being verified';
$lang['member_order_none_exist']	    = 'this order does not exist';
$lang['pay_bank_user']			= 'remitter name';
$lang['pay_bank_bank']			= 'bank';
$lang['pay_bank_account']		= 'remittance account';
$lang['pay_bank_num']			= 'remittance amount';
$lang['pay_bank_date']			= 'remittance date';
$lang['pay_bank_extend']		= 'other';
$lang['pay_bank_order']			= 'remittance order';


$lang['member_show_logistics_details']		= '，if there is no logistic information, you can copy logistic number';
$lang['member_show_see_logistics_details']	= 'check logistic details';
$lang['member_show_express_100']			= '100express 100';
$lang['member_show_go_to']					= 'to';
$lang['member_show_express_ship_code']		= 'logistic number';
$lang['member_show_express_ship_dstatus']	= 'logistic dynamics';
$lang['member_show_affirm_delivery']		= '，，o(∩_∩)o distinguished guest, if you received the correct goods, pleas do not forget confirm reception auntomaically';
$lang['member_show_express_ship_tips']		= '，，informations above come from the third-party platform, only for reference. if you need acurate information, you can contact sellers or the logistic company';
$lang['member_show_express_detail']			= 'logistic details';
$lang['member_show_seller_has_send']		= 'seller have shipped';
$lang['member_show_expre_my_fdback']		= 'my message';
$lang['member_show_expre_type']				= '：shipping method: contact by yourself';
$lang['member_show_expre_company']			= 'logistic company';
$lang['member_show_receive_info']			= 'reception information';
$lang['member_show_deliver_info']			= 'shipping information';