<?php
defined('GcWebShop') or exit('Access Invalid!');
/**
 * for public use
 */
$lang['activity_openstate']		= 'status';
$lang['activity_openstate_open']		= 'open';
$lang['activity_openstate_close']		= 'close';
/**
 * 
 */
$lang['activity_index']				= 'activity';
$lang['activity_index_content']		= 'the content of the activity';
$lang['activity_index_manage']		= 'activity manage';
$lang['activity_index_title']		= 'activity title';
$lang['activity_index_type']		= 'activity type';
$lang['activity_index_banner']		= 'banner picture';
$lang['activity_index_style']		= 'use style';
$lang['activity_index_start']		= 'start time';
$lang['activity_index_end']			= 'end time';
$lang['activity_index_goods']		= 'goods';
$lang['activity_index_group']		= 'panic buying';
$lang['activity_index_default']		= 'default style';
$lang['activity_index_long_time']	= 'long time activity';
$lang['activity_index_deal_apply']	= 'deal with application';
$lang['activity_index_help1']		= 'when the platform launched a campaign, shops can apply to participate in activities';
$lang['activity_index_help2']		= 'I=in the module" page navigation ", you can optionally add the activity navigation ';
$lang['activity_index_help3']		= 'the activities that are closed or expired can be deleted';
$lang['activity_index_help4']		= 'the samller the activity list is, the more front it will be';
$lang['activity_index_periodofvalidity']= 'term of validity';
/**
 * take part in activity
 */
$lang['activity_new_title_null']	= 'activity title cannot be null';
$lang['activity_new_style_null']	= 'must select the page style';
$lang['activity_new_type_null']		= 'must choose activity type';
$lang['activity_new_sort_tip']		= 'sort must be digital, the range from 0 to 255';
$lang['activity_new_end_date_too_early']	= '';
$lang['activity_new_title_tip']		= '';
$lang['activity_new_type_tip']		= 'end time must be later than start time';
$lang['activity_new_start_tip']		= 'default empty refers to start the activity right now';
$lang['activity_new_end_tip']		= 'default empty refers to permanent activity';
$lang['activity_new_banner_tip']	= 'supports JPG, jpeg, GIF, PNG format';
$lang['activity_new_style']			= 'page style';
$lang['activity_new_style_tip']		= 'please select the page style of the activity';
$lang['activity_new_desc']			= 'activity description';
$lang['activity_new_sort_tip1']		= 'digital range is of 0 to 255, the smaller the number is, the more front it will be';
$lang['activity_new_sort_null']		= 'sort can not be null';
$lang['activity_new_sort_minerror']	= 'digital range is of 0 to 255';
$lang['activity_new_sort_maxerror']	= 'digital range is of 0 to 255';
$lang['activity_new_sort_error']	= 'the sort is the number of 0 to 255';
$lang['activity_new_banner_null']   = 'the banner can not be null';
$lang['activity_new_ing_wrong']     = 'pictures are limited to png,gif,jpeg,jpg format';
$lang['activity_new_startdate_null']   = 'start date can not be null';
$lang['activity_new_enddate_null']     = 'end date can not be null';

/**
 * delete activity
 */
$lang['activity_del_choose_activity']	= 'choose activity';
/**
 * activity content
 */
$lang['activity_detail_index_goods_name']	= 'goods name';
$lang['activity_detail_index_store']		= 'store guid';
$lang['activity_detail_index_auditstate']	= 'audit state';
$lang['activity_detail_index_to_audit']		= 'wait to be audited';
$lang['activity_detail_index_passed']		= 'passed';
$lang['activity_detail_index_unpassed']		= 'refused';
$lang['activity_detail_index_apply_again']	= 'apply again';
$lang['activity_detail_index_pass']			= 'pass';
$lang['activity_detail_index_refuse']		= 'refuse';
$lang['activity_detail_index_pass_all']		= 'are you sure you want to pass the selected information?';
$lang['activity_detail_index_refuse_all']	= 'are you sure you want to refuse the selected information?';
$lang['activity_detail_index_tip1']	= 'application of goods in the absence of audit or audit failure can be deleted';
$lang['activity_detail_index_tip2']	= 'the display rules of this page is first showing that is not audited , the smaller the ranking,the more front it will be';
$lang['activity_detail_index_tip3']	= 'the goods which is taken off the shelves, illegally taken off or their shops have been closed  will not be displayed on the activities page, please carefully review';

/**
 * delete the activity contents
 */
$lang['activity_detail_del_choose_detail']	= 'please select the activity content (such as goods or panic buying, etc.)';