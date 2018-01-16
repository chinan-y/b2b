<?php
defined('GcWebShop') or exit('Access Invalid!');
/**
 * index
 */
$lang['notice_index_member_list_null']	= 'menber list cannot be empty';
$lang['notice_index_store_grade_null']	= 'store grade cannot be empty';
$lang['notice_index_title_null']		= 'notification title cannot be empty';
$lang['notice_index_content_null']		= 'notification content cannot be empty';
$lang['notice_index_batch_int']			= 'the number of seperate sending must be numeric';
$lang['notice_index_member_error']		= 'the information of member is incorrect, please redo it.';
$lang['notice_index_sending']			= 'sending';
$lang['notice_index_send_succ']			= 'sent succesfull';
$lang['notice_index_member_notice']		= 'member notification';
$lang['notice_index_send']				= 'send notification';
$lang['notice_index_send_type']			= 'send type';
$lang['notice_index_spec_member']		= 'specified member';
$lang['notice_index_all_member']		= 'all member';
$lang['notice_index_smtp_incomplate']	= 'the settings of SMTP information is incomplete';
$lang['notice_index_smtp_close']		= 'mail function is locked';
$lang['notice_index_spec_store_grade']	= 'specified store grade';
$lang['notice_index_all_store']			= 'all store';
$lang['notice_index_member_list']		= 'member list';
$lang['notice_index_member_tip']		= 'fill in one member name in each line';
$lang['notice_index_store_grade']		= 'store grade';
$lang['notice_index_store_tip']			= 'Press and hold the Ctrl button to select multiple options';
$lang['notice_index_batch']				= 'the number of seperate sending';
$lang['notice_index_batch_tip']			= 'the system might stop operating because of time-out, pelase control the number of notifications.';
$lang['notice_index_send_method']		= 'send mode';
$lang['notice_index_message']			= 'send station message';
$lang['notice_index_email']				= 'send E-mail';
$lang['notice_index_title']				= 'notification title';
$lang['notice_index_content']			= 'notification content';
$lang['notice_index_member_error']		= 'send to specified member, member name cannot be empty and in each line only one member name is allowed.';
$lang['notice_index_help1']				= 'seperate sending, an operation of sending notification is automatically split into multiple batches, you can set the number of notifications sent per batch';