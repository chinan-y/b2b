<?php
defined('GcWebShop') or exit('Access Invalid!');

/**
 * navigation menu
 */
$lang['complain_new_list'] = 'New complaint';
$lang['complain_handle_list'] = 'Pending arbitration';
$lang['complain_appeal_list'] = 'Pending appeals';
$lang['complain_talk_list'] = 'Talking';
$lang['complain_finish_list'] = 'Closed';
$lang['complain_subject_list'] = 'Complain subject';

/**
 * navigation menu
 */
$lang['complain_manage_title'] = 'Complain manage';
$lang['complain_submit'] = 'Complain handling';
$lang['complain_setting'] = 'Complain setting';

$lang['complain_state_new'] = 'New complain';
$lang['complain_state_handle'] = 'Pending arbitration';
$lang['complain_state_appeal'] = 'Pending appeals';
$lang['complain_state_talk'] = 'Talking';
$lang['complain_state_finish'] = 'Closed';
$lang['complain_subject_list'] = 'Complain subject';

$lang['complain_pic'] = 'pictures';
$lang['complain_pic_view'] = 'pictures view';
$lang['complain_pic_none'] = 'none picture';
$lang['complain_detail'] = 'complain detail';
$lang['complain_message'] = 'complain details';
$lang['complain_evidence'] = 'complain evidence';
$lang['complain_evidence_upload'] = 'upload complain evidence';
$lang['complain_content'] = 'complain content';
$lang['complain_accuser'] = 'complainant';
$lang['complain_accused'] = 'complaint of shop';
$lang['complain_admin'] = 'administrator';
$lang['complain_unknow'] = 'unknow';
$lang['complain_datetime'] = 'complain datetime';
$lang['complain_goods'] = 'complaint of goods';
$lang['complain_goods_name'] = 'goods name';
$lang['complain_state'] = 'complain state';
$lang['complain_progress'] = 'complain progress';
$lang['complain_handle'] = 'complain hangling';
$lang['complain_subject_content'] = 'complain theme';
$lang['complain_subject_select'] = 'select the complain theme';
$lang['complain_subject_desc'] = 'the description of the complain theme';
$lang['complain_subject_add'] = 'Add theme';
$lang['complain_appeal_detail'] = 'appeal detail';
$lang['complain_appeal_message'] = 'appeal information';
$lang['complain_appeal_content'] = 'appeal content';
$lang['complain_appeal_datetime'] = 'appeal datetime';
$lang['complain_appeal_evidence'] = 'appeal evidence';
$lang['complain_appeal_evidence_upload'] = 'upload appeal evidence';
$lang['complain_state_inprogress'] = 'in progress';
$lang['complain_state_finish'] = 'finished';
$lang['final_handle_detail'] = 'handle detail';
$lang['final_handle_message'] = 'handle message';
$lang['final_handle_datetime'] = 'hadle datetime';
$lang['order_detail'] = 'order detail';
$lang['order_message'] = 'order message';
$lang['order_state'] = 'order state';
$lang['order_sn'] = 'order number';
$lang['order_datetime'] = 'order time';
$lang['order_price'] = 'total orders';
$lang['order_discount'] = 'discount';
$lang['order_voucher_price'] = 'the denomination of vouchers used';
$lang['order_voucher_sn'] = 'voucher code';
$lang['order_buyer_message'] = 'buyer information';
$lang['order_seller_message'] = 'store information';
$lang['order_shop_name'] = 'store name';
$lang['order_buyer_name'] = 'buyer name';
$lang['order_state_cancel'] = 'cancel';
$lang['order_state_unpay'] = 'unpay';
$lang['order_state_payed'] = 'payed';
$lang['order_state_send'] = 'sent';
$lang['order_state_receive'] = 'received';
$lang['order_state_commit'] = 'commit';
$lang['order_state_verify'] = 'confirmed';
$lang['complain_time_limit'] = 'the limitation of complaint';
$lang['complain_time_limit_desc'] = 'the unit is day, calculating after order is completed, how many days can make a complaint';

$lang['refund_message']	= 'refund message';
$lang['refund_order_refund']	= 'the amount of the refund has been confirmed';

/**
 * prompt message
 */
$lang['confirm_delete'] = 'are you sure to delete?';
$lang['complain_content_error'] = 'complaint contents can not be null and must be less than 100 characters';
$lang['appeal_message_error'] = 'complaint contents can not be null and must be less than 100 characters';
$lang['complain_pic_error'] = 'pictures must be in JPG format';
$lang['complain_time_limit_error'] = 'the limitation of complaint can not be null and must be numbers';
$lang['complain_subject_content_error'] = 'the complaint theme can not be null and must be less than 50 characters';
$lang['complain_subject_desc_error'] = 'the description of the theme can not be null and must be less than 100 characters';
$lang['complain_subject_type_error'] = 'the unknown complain subject type';
$lang['complain_subject_add_success'] = 'add complain subject successfully';
$lang['complain_subject_add_fail'] = 'fail to complain subject';
$lang['complain_subject_delete_success'] = 'delete the complain subject successfully';
$lang['complain_subject_delete_fail'] = 'fail to delete the complain subject';
$lang['complain_setting_save_success'] = 'save complain setting successfully';
$lang['complain_setting_save_fail'] = 'fail to save complain setting';
$lang['complain_goods_select'] = 'select the goods to be complained';
$lang['complain_submit_success'] = 'complain submit successfully';
$lang['complain_close_confirm'] = 'are you sure to close this complaint?';
$lang['appeal_submit_success'] = 'the complaint was submitted successfully';
$lang['talk_detail'] = 'talk detail';
$lang['talk_null'] = 'talk can not be null';
$lang['talk_none'] = 'no talk at this time';
$lang['talk_list'] = 'talk list';
$lang['talk_send'] = 'publish talk';
$lang['talk_refresh'] = 'refresh talk';
$lang['talk_send_success'] = 'the talk was sent successfully';
$lang['talk_send_fail'] = 'the talk failed to send';
$lang['talk_forbit_success'] = 'tald was forbitted successfully';
$lang['talk_forbit_fail'] = 'fail to forbit talk';
$lang['complain_verify_success'] = 'audit complain successfully';
$lang['complain_verify_fail'] = 'fail to audit complain';
$lang['complain_close_success'] = 'close complain successfully';
$lang['complain_close_fail'] = 'fail to close complain';
$lang['talk_forbit_message'] =  '<talk was forbitted by administrator>';
$lang['final_handle_message_error'] = 'the processing opinion can not be null and must be less than 255 characters';
$lang['final_handle_message'] = 'the processing opinion';
$lang['handle_submit'] = 'submitted to arbitration';
$lang['complain_repeat'] = 'you have complained about the order  please pend processing';
$lang['verify_submit_message'] = 'erify submit message';


/**
 * text
 */
$lang['complain_text_select'] = '...please select';
$lang['complain_text_handle'] = 'operate';
$lang['complain_text_detail'] = 'detail';
$lang['complain_text_submit'] = 'submit';
$lang['complain_text_pic'] = 'image';
$lang['complain_text_num'] = 'number';
$lang['complain_text_price'] = 'price';
$lang['complain_text_problem'] = 'problem description';
$lang['complain_text_say'] = 'say';
$lang['complain_text_verify'] = 'vertify';
$lang['complain_text_close'] = 'close complain';
$lang['complain_text_forbit'] = 'forbit';
$lang['complain_help1']='，，within the limitation of the complaints of orders, buyers can complain order, complaints subject was set by administrators in the background';
$lang['complain_help2']='complaints limitation can be set in the system Settings';
$lang['complain_help3']='，。，。，，click on the details, you can conduct a complaint review. after the audit is completed, the complaint shop can appeal. when complain successfully, the two sides of the complaint begin to talk, and arbitrated finally by the background administrator';