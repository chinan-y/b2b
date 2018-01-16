<?php
defined('GcWebShop') or exit('Access Invalid!');
/**
 * common language
 */

/**
 * consignee
 */
$lang['member_address_receiver_name']	= 'consignee';
$lang['member_address_location']		= 'location area';
$lang['member_address_address']			= 'street address';
$lang['member_address_zipcode']			= 'zip code';
$lang['member_address_phone']			= 'telephone';
$lang['member_address_mobile']			= 'mobile phone';
$lang['member_address_edit_address']	= 'edit address';
$lang['member_address_no_address']		= 'you do not add a recipient address';
$lang['member_address_input_name']		= 'please fill in the same name as the identity card';
$lang['member_address_please_choose']	= 'please select';
$lang['member_address_not_repeat']		= 'no need to fill in the area';
$lang['member_address_phone_num']		= 'phone number';
$lang['member_address_area_num']		= 'area code';
$lang['member_address_sub_phone']		= 'extension';
$lang['member_address_phone']		    = 'telephone';
$lang['member_address_input_receiver']	= 'please fill in the name of consignee';
$lang['member_address_choose_location']	= 'please select the area';
$lang['member_address_input_address']	= 'please fill in the details';
$lang['member_address_zip_code']		= '6postal code consists of 6 digits';
$lang['member_address_phone_and_mobile']= 'please fill in at least one of the fixed phones and mobile phones.';
$lang['member_address_phone_rule']		= '6.the telephone number by numbers, pluses and minuses, spaces, and parentheses, not less than 6. ';
$lang['member_address_wrong_mobile']	= 'wrong phone number';

/**
 * set shipping address
 */
$lang['store_daddress_wrong_argument']	= 'parameter is incorrect';
$lang['store_daddress_receiver_null']	= 'shipper cannot be empty';
$lang['store_daddress_wrong_area']		= 'incorrect selection of area';
$lang['store_daddress_area_null']		= 'where information cannot be empty';
$lang['store_daddress_address_null']	= 'detailed address cannot be empty';
$lang['store_daddress_modify_fail']		= 'failed to modify address';
$lang['store_daddress_add_fail']		= 'new address failed';
$lang['store_daddress_del_fail']		= 'delete address failed';
$lang['store_daddress_del_succ']		= 'delete success';
$lang['store_daddress_new_address']		= 'add address';
$lang['store_daddress_deliver_address']	= 'shipping Address';
$lang['store_daddress_default']			= 'default';
$lang['store_daddress_receiver_name']	= 'contacts';
$lang['store_daddress_location']		= 'location area';
$lang['store_daddress_address']			= 'street address';
$lang['store_daddress_zipcode']			= 'zip code';
$lang['store_daddress_phone']			= 'telephone';
$lang['store_daddress_mobile']			= 'mobile phone';
$lang['store_daddress_company']			= 'company';
$lang['store_daddress_content']			= 'remarks';
$lang['store_daddress_edit_address']	= 'edit address';
$lang['store_daddress_no_address']		= 'you did not add shipping address';
$lang['store_daddress_input_name']		= 'please fill in the same name as the identity card';
$lang['store_daddress_please_choose']	= 'please select';
$lang['store_daddress_not_repeat']		= 'no need to fill in the area';
$lang['store_daddress_phone_num']		= 'telephone';
$lang['store_daddress_area_num']		= 'area code';
$lang['store_daddress_sub_phone']		= 'extension';
$lang['store_daddress_mobile_num']		= 'phone number';
$lang['store_daddress_input_receiver']	= 'please fill in the contact name';
$lang['store_daddress_choose_location']	= 'please select the area';
$lang['store_daddress_input_address']	= 'please fill in the street address';
$lang['store_daddress_zip_code']		= '6postal code consists of 6 digits';
$lang['store_daddress_phone']	        = 'telephone';
$lang['store_daddress_phone_rule']		= '、、、、,6. the telephone number by numbers, pluses and minuses, spaces, and parentheses, not less than 6.';
$lang['store_daddress_wrong_mobile']	= 'wrong phone number';

/**
 * logistics company
 */
$lang['store_deliver_express_title']	= 'logistics company';

/**
 * deliver goods
 */
$lang['store_deliver_order_state_send']		= 'already shipped';
$lang['store_deliver_order_state_receive']	= 'receiving goods';
$lang['store_deliver_modfiy_address']		= 'modify receiving information';
$lang['store_deliver_select_daddress']		= 'select shipping address';
$lang['store_deliver_select_ather_daddress']= 'select other shipping address';
$lang['store_deliver_daddress_list']		= 'address Library';
$lang['store_deliver_default_express']		= 'default logistics company';
$lang['store_deliver_buyer_name']			= 'buyers';
$lang['store_deliver_buyer_address']		= 'receiving address';
$lang['store_deliver_shipping_amount']		= 'freight';
$lang['store_deliver_modify_info']			= 'edit delivery';
$lang['store_deliver_first_step']			= 'first step';
$lang['store_deliver_second_step']			= 'second step';
$lang['store_deliver_third_step']			= 'third step';
$lang['store_deliver_confirm_trade']		= 'confirm receipt information and transaction details';
$lang['store_deliver_forget']				= 'shipping memo';
$lang['store_deliver_forget_tips']			= '（）you can enter some shipping memo information (see the seller only)';
$lang['store_deliver_buyer_adress']			= 'consignee';
$lang['store_deliver_confirm_daddress']		= 'confirm shipment information';
$lang['store_deliver_my_daddress']			= 'my shipping information';
$lang['store_deliver_none_set']				= '， > shipping address has not been set, please go to the shipping Settings > address Library';
$lang['store_deliver_add_daddress']			= 'add shipping address';
$lang['store_deliver_express_select']		= 'select logistics services';
$lang['store_deliver_express_note']			= '"you can set the shipping-><a href="index.php?gct=store_deliver_set&gp=express" target="_parent" ></a>"。<span class="red">[]</span>。';
$lang['store_deliver_express_zx']			= 'self contact logistics company';
$lang['store_deliver_express_wx']			= 'no logistics service';
$lang['store_deliver_company_name']			= 'corporate name';
$lang['store_deliver_shipping_code']		= 'logistics number';
$lang['store_deliver_bforget']				= 'memo';
$lang['store_deliver_shipping_code_tips']	= '，fill in the logistics number correctly, ensure that the courier tracking information is correct';
$lang['store_deliver_no_deliver_tips']		= '，if the goods in the order do not need to be shipped, you can click to confirm';
$lang['store_deliver_shipping_code_pl']		= 'please fill in the logistics number';

/**
 * select shipping address
 */
$lang['store_deliver_man']			= 'consignor';
$lang['store_deliver_daddress']		= 'shipping Address';
$lang['store_deliver_telphone']		= 'telephone';

/**
 * dynamic Logistics
 */
$lang['member_show_expre_my_fdback']		= 'my message';
$lang['member_show_expre_type']				= '：shipping method: contact';
$lang['member_show_expre_company']			= 'logistics company';
$lang['member_show_receive_info']			= 'receiving information';
$lang['member_show_deliver_info']			= 'shipping Information';