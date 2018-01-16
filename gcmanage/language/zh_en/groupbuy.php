<?php
defined('GcWebShop') or exit('Access Invalid!');
/**
 *  buying stutas
 */
$lang['groupbuy_state_all'] 		= 'sold out';
$lang['groupbuy_state_verify'] 		= 'unaudited';
$lang['groupbuy_state_cancel'] 		= 'canceled';
$lang['groupbuy_state_progress'] 	= 'passed';
$lang['groupbuy_state_fail'] 		= 'failed to audit';
$lang['groupbuy_state_close'] 		= 'finished';

/**
 * index
 */
$lang['groupbuy_index_manage']		= 'buying management';
$lang['tuangou_index_manage']		= 'group purshase management';
$lang['groupbuy_verify']    		= 'pending';
$lang['groupbuy_cancel']    		= 'canceled';
$lang['groupbuy_progress']  		= 'audited';
$lang['groupbuy_close']     		= 'finished';
$lang['groupbuy_back']     			= 'back to list';

$lang['groupbuy_recommend_goods']	= 'recommend goods';
$lang['groupbuy_template_list']		= 'buying activity';
$lang['groupbuy_template_add']		= 'add activity';
$lang['groupbuy_template_name']		= 'activity name';
$lang['groupbuy_class_list']		= 'buying classficition';
$lang['groupbuy_class_add']		    = 'add classfication';
$lang['groupbuy_class_edit']	    = 'edit classification';
$lang['groupbuy_class_name']	    = 'category name';
$lang['groupbuy_parent_class']	    = 'parent category';
$lang['groupbuy_root_class']	    = 'first category';
$lang['groupbuy_area_list'] 		= 'buying area';
$lang['groupbuy_area_add']		    = 'add area';
$lang['groupbuy_area_edit'] 	    = 'adit area';
$lang['groupbuy_area_name'] 	    = 'area name';
$lang['groupbuy_parent_area']	    = 'parent area';
$lang['groupbuy_root_area'] 	    = 'first area';
$lang['groupbuy_price_list']		= 'buying price range';
$lang['groupbuy_price_add']		    = 'add price range';
$lang['groupbuy_price_edit']	    = 'edit price range';
$lang['groupbuy_price_name']	    = 'price range name';
$lang['groupbuy_price_range_start'] = 'maximum price range';
$lang['groupbuy_price_range_end']	= 'minimum price range';
$lang['groupbuy_detail'] 			= 'buying detailed information';
$lang['range_name']	    			= 'price range name';
$lang['range_start']	    		= 'minus price range';
$lang['range_end']	    			= 'maximum price range';
$lang['groupbuy_index_name']		= 'buying name';
$lang['tuangou_index_name']			= 'group buying name';
$lang['groupbuy_index_goods_name']	= 'goods name';
$lang['groupbuy_index_store_name']	= 'shop name';
$lang['start_time']             	= 'starting time';
$lang['end_time']               	= 'ending time';
$lang['join_end_time']             	= 'ending time';
$lang['groupbuy_index_start_time']	= 'starting time';
$lang['groupbuy_index_end_time']	= 'ending time';
$lang['day']						= 'day';
$lang['hour']						= 'hour';
$lang['groupbuy_index_state']		= 'buying status';
$lang['tuangou_index_state']		= 'group buying status';
$lang['groupbuy_index_pub_state']	= 'publishing status';
$lang['groupbuy_index_click']		= 'browse data';
$lang['groupbuy_index_long_group']	= 'long-term activity';
$lang['groupbuy_index_un_pub']		= 'unpulished';
$lang['groupbuy_index_canceled']	= 'canceled';
$lang['groupbuy_index_going']		= 'ongoing';
$lang['groupbuy_index_finished']	= 'finished';
$lang['groupbuy_index_ended']		= 'ended';
$lang['groupbuy_index_published']	= 'published';
$lang['group_template'] 			= 'buying activity';
$lang['group_name'] 				= 'buying name';
$lang['store_name'] 				= 'shop name';
$lang['goods_name'] 				= 'goods name';
$lang['group_help'] 				= 'buying instruction';
$lang['start_time'] 				= 'starting time';
$lang['end_time'] 					= 'ending time';
$lang['goods_price'] 				= 'original price';
$lang['store_price'] 				= 'MALL price';
$lang['groupbuy_price'] 			= 'buying price';
$lang['limit_type'] 				= 'constraint type ';
$lang['virtual_quantity'] 			= 'dummy quant';
$lang['min_quantity'] 				= 'order quant';
$lang['sale_quantity'] 				= 'limit buying quant';
$lang['max_num'] 					= 'totall goods quant';
$lang['group_intro'] 				= 'buying introduction';
$lang['group_pic'] 					= 'buying picture';
$lang['buyer_count'] 				= 'bought member';
$lang['def_quantity'] 				= 'bought goods quant';
$lang['base_info'] 					= 'basic innformation';


/**
 * page instruction
 **/
$lang['groupbuy_template_help1'] 	= 'Click the manage button to view the detailed information, approve and manage buying applications ';
$lang['groupbuy_template_help2'] 	= 'Unstarted activities can directly deleted, all buying information will be deleted at the same time';
$lang['groupbuy_template_help3'] 	= 'you can click the close button to close it manually after the beginning of the activity';
$lang['groupbuy_template_help4'] 	= 'recommend snapping up goods to the homepage, please go to the management page lit up recommended under the check mark';
$lang['groupbuy_template_add_help1']= 'Activity cannot overlap, the new activity start time  must be earlier than than the end time of existing activities ';
$lang['groupbuy_template_add_help2']= 'Closing time must be late than the starting time';
$lang['groupbuy_start_time_explain']= 'Buying activity cannot be earlier than the start time';
$lang['groupbuy_class_help1'] 		= 'Classification of snapping up in background is second grade classification , first grade is the default displayed at the front desk and extension requires a secondary development';
$lang['groupbuy_area_help1'] 		= 'Classification of snapping up in background is third grade classification , first grade is the default displayed at the front desk and extension requires a secondary development';
$lang['groupbuy_price_range_help1'] = 'Front snap filter by price range, the amount of intervals do not overlap';
$lang['groupbuy_index_help1']		= "click on the ' return to list' link in navigation menu and return to the active list page";
$lang['groupbuy_index_help2']		= 'only snapping up audited information will appear on the front page';
$lang['groupbuy_parent_class_add_tip'] = 'Please select the parent category, the default is the first level category';
$lang['groupbuy_parent_area_add_tip'] = 'Please select the parent area, the default is the first level area';
$lang['sort_tip'] = 'Sorting numeric value from 0 to 255, the number 0 highest priority';
$lang['price_range_tip'] = "interval name should be specific, such as '1000 yuan' and '2000 Yuan - 3000 yuan";
$lang['price_range_price_tip'] 		= 'prices must be positive integers';

$lang['groupbuy_recommend_help1'] 	= 'This page displays snapping up goods that have been approved , only the recommended action available';

$lang['state_text_notstarted'] 		= 'not start';
$lang['state_text_in_progress'] 	= 'ongoing';
$lang['state_text_closed'] 			= 'closed';

/**
 * delete buying
 */
$lang['groupbuy_del_choose']		= 'please choose the content to be deleted';
$lang['groupbuy_del_succ']			= 'deleted';
$lang['groupbuy_del_fail']			= 'failed to delete';
/**
 * buying recommendation
 */
$lang['groupbuy_recommend_choose']	= 'please choose the content to be recommended';
$lang['groupbuy_recommend_succ']	= 'recommended';
$lang['groupbuy_recommend_fail']	= 'failed to remmened';


/**
 * promoting message
 */
$lang['class_name_error'] 			= 'category name can not be blank';
$lang['sort_error'] 				= 'sort must be numeric';
$lang['area_name_error'] 			= 'area name can not be blank';
$lang['verify_success'] 			= 'audited';
$lang['verify_fail'] 				= 'failed to audit';
$lang['ensure_verify_success'] 		= 'Confirm the auditing of the buying activity are approved';
$lang['ensure_verify_fail'] 		= 'Confirm the auditing of the buying activity are failed';
$lang['op_close'] 					= 'ended';
$lang['ensure_close'] 				= 'Confirm to the buying activity';
$lang['template_name_error'] 		= 'activity name can not be blank';
$lang['start_time_error'] 			= 'atart timecan not be blank';
$lang['end_time_error'] 			= 'end time can not be blank,and must be earlier than the start time';
$lang['join_end_time_error'] 		= 'egistration deadline cannot be blank';
$lang['range_name_error'] 			= 'price range cannot be blank';
$lang['range_start_error'] 			= 'maximum price range cannot be blank and must be number';
$lang['range_end_error'] 			= 'minimum price range cannot be blank and must be number';
$lang['start_time_overlap'] 		= 'Buying activity time overlap please reselect snapping up the start tim';
/**
 * prompting message
 */

$lang['admin_groupbuy_unavailable'] 		= 'snap funvtion is not yet open, whether to open it automatically';
$lang['groupbuy_template_add_success'] 		= 'buying activity added';
$lang['groupbuy_template_add_fail'] 		= 'failed to add buying activity ';
$lang['groupbuy_tempalte_drop_success'] 	= 'buying activity deleted';
$lang['groupbuy_template_drop_fail'] 		= 'failed to delete buying activity';
$lang['groupbuy_tempalte_close_success'] 	= 'buying activity closed';
$lang['groupbuy_template_close_fail'] 		= 'failed to end buying activity';
$lang['groupbuy_verify_success'] 			= 'buying activity audited';
$lang['groupbuy_verify_fail'] 				= 'failed to audit buying activity';
$lang['groupbuy_close_success'] 			= 'buying ended';
$lang['groupbuy_close_fail'] 				= 'failed to end buying';
$lang['groupbuy_class_add_success'] 		= 'buying category added';
$lang['groupbuy_class_add_fail'] 			= 'buying category added';
$lang['groupbuy_class_edit_success'] 		= 'buying category edited';
$lang['groupbuy_class_edit_fail'] 			= 'failed to edit buying category';
$lang['groupbuy_class_drop_success'] 		= 'buying category deleted';
$lang['groupbuy_class_drop_fail'] 			= 'failed to delete buying category';
$lang['groupbuy_area_add_success'] 			= 'buying price area added';
$lang['groupbuy_area_add_fail'] 			= 'failed to add buying area';
$lang['groupbuy_area_edit_success'] 		= 'buying price area edited';
$lang['groupbuy_area_edit_fail'] 			= 'failed to edit buying area';
$lang['groupbuy_area_drop_success'] 		= 'buying price area deleted';
$lang['groupbuy_area_drop_fail'] 			= 'failed to delete buying area';
$lang['groupbuy_price_range_add_success']	= 'buying price range added';
$lang['groupbuy_price_range_add_fail'] 		= 'failed to add buying price range';
$lang['groupbuy_price_range_edit_success'] 	= 'buying price range edited';
$lang['groupbuy_price_range_edit_fail'] 	= 'failed to edit buying price range';
$lang['groupbuy_price_range_drop_success'] 	= 'buying price range delelted';
$lang['groupbuy_price_range_drop_fail'] 	= 'failed to delete buying price range';

$lang['groupbuy_close_confirm'] 			= 'Connfirm closing buying activity? Unable to open again after closing';
