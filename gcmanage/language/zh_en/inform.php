<?php
defined('GcWebShop') or exit('Access Invalid!');

/**
 *  Language needed in page
 */
$lang['inform_page_title'] 				= 'report goods';
$lang['inform_manage_title'] 			= 'report management';
$lang['inform'] 						= 'report';

$lang['inform_state_all'] 				= 'all reports';
$lang['inform_state_handled'] 			= 'processed';
$lang['inform_state_unhandle'] 			= 'unprocessed';
$lang['inform_goods_name'] 				= 'goods name';
$lang['inform_member_name'] 			= 'informer';
$lang['inform_subject'] 				= 'report theme';
$lang['inform_type'] 					= 'report type';
$lang['inform_type_desc']				= 'report type description';
$lang['inform_pic'] 					= 'image';
$lang['inform_pic_view'] 				= 'viwe image';
$lang['inform_pic_none'] 				= 'no image';
$lang['inform_datetime'] 				= 'report time';
$lang['inform_state'] 					= 'status';
$lang['inform_content'] 				= 'reprot content';
$lang['inform_handle_message'] 			= 'process information';
$lang['inform_handle_type'] 			= 'process result';
$lang['inform_handle_type_unuse'] 		= 'unvalid report';
$lang['inform_handle_type_venom'] 		= 'malicious report';
$lang['inform_handle_type_valid'] 		= 'valid report';
$lang['inform_handle_type_unuse_message'] = 'unvalid report--all goods on sell normally';
$lang['inform_handle_type_venom_message'] = 'Malicious report -- all of the user is pending report will be canceled, the user will be bebanned from reporting';
$lang['inform_handle_type_valid_message'] = 'Valid report-- Merchandise will be off the shelves for violation';
$lang['inform_subject_add'] 			= 'add theme';
$lang['inform_type_add'] 				= 'add type';

$lang['inform_text_none'] 				= 'no';
$lang['inform_text_handle'] 			= 'process';
$lang['inform_text_select'] 			= 'please select';

/**
 * promoting message
 */
$lang['inform_content_null'] 			= 'Report content not be blank and cannot be greater than 100 characters';
$lang['inform_subject_add_null'] 		= 'Report theme not be blank and cannot be greater than 100 characters';
$lang['inform_handle_message_null'] 	= 'process information not be blank and cannot be greater than 100 characters';
$lang['inform_type_null'] 				= 'Report type not be blank and cannot be greater than 100 characters';
$lang['inform_type_desc_null'] 			= 'Report type description not be blank and cannot be greater than 100 characters';
$lang['inform_handle_confirm'] 			= 'confirm to process this report';
$lang['inform_type_delete_confirm'] 	= 'confirm the deletion of the reporting classification , the classification under the theme will also be deleted';
$lang['confirm_delete'] 				= 'confirm deletion';
$lang['inform_pic_error'] 				= 'image is in jpg format only';
$lang['inform_handling'] 				= 'the commodity has been reported,please wait  to be processed';
$lang['inform_type_error'] 				= 'Report platform type does not exist, please contact the administrator to add types';
$lang['inform_subject_null'] 			= 'Report theme type does not exist, please contact the administrator';
$lang['inform_success'] 				= 'Reported ,please wait to be processed';
$lang['inform_fail'] 					= 'failed to report, please contact the administrator';
$lang['goods_null'] 					= 'product does not exis';
$lang['deny_inform'] 					= 'You have been banned reporting of goods, if you have any questions please contact platform administrator'; 
$lang['inform_help1']					= 'Report types and reporting topics set by the administrator in the background,members can repor tillegal goods according to the report topic in the product information page ';
$lang['inform_help2']					= 'Click details to view the report contents';
$lang['inform_help3']					= 'view processed report content';
$lang['inform_help4']					= 'You can add multiple report types in the same report topics';
$lang['inform_help5']					= 'Members can illegal goods according to the report theme';

?>
