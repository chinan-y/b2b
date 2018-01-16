<?php
defined('GcWebShop') or exit('Access Invalid!');

/**
 * corecore simplified language pack
 */
$lang['please_check_your_url_arg'] = 'please check the parameter information entered in your URL address bar!! Error coding:';

$lang['error_info'] = 'system information';
$lang['error_notice_operate'] = '400-0893123 ！the system is running abnormally, and we apologize for the inconvenience. Please contact customer service for help, customer service hotline: 400-0893123!';
$lang['company_name'] = '';

$lang['order_state_cancel'] = '<span style="color:#999">canceled</span>';
$lang['order_state_new'] = '<span style="color:#36C">pending payment</span>';
$lang['order_state_pay'] = '<span style="color:#F30">waiting for delivery</span>';
$lang['order_state_send'] = '<span style="color:#F30">receiving goods</span>';
$lang['order_state_refund_no'] = '<span style="color:#F30">pending refund</span>';
$lang['order_state_refund_yes'] = '<span style="color:#F30">refunded</span>';
$lang['order_state_success'] = '<span style="color:#999">transaction completed</span>';
$lang['order_state_eval'] = '<span style="color:#999">already evaluated</span>';
$lang['order_state_refund_underway'] = '<span style="color:#999">refund return</span>';
$lang['order_state_refund_perform'] = '<span style="color:#999">refund completed</span>';

$lang['please_check_your_system_chmod'] = '，system configuration information cache file cannot be written, please check the file and folder permissions are correct!';
$lang['please_check_your_system_chmod_area'] = '，!the area cache file cannot be written, please check the file and folder permissions are correct!';
$lang['please_check_your_cache_type'] = '，!this method does not exist. Make sure the cache class is correct!';
$lang['please_check_your_system_chmod_goods'] = '，!product category cache file can not be written, please check the file and folder permissions are correct!';
$lang['please_check_your_system_chmod_ad'] = '，!the file cannot be written, please check the file and folder permissions are correct!';
$lang['please_check_your_system_chmod_adv'] = '，!the message cache file cannot be written, please check the file and folder permissions are correct!';
$lang['please_check_your_system_chmod_goods_class']	= '，!category list cache file cannot be written, please check the file and folder permissions are correct!';

$lang['first_page'] = 'First';
$lang['last_page'] = 'Last';
$lang['pre_page'] = 'Pre';
$lang['next_page'] = 'Next';

$lang['cant_find_temporary_files'] = '，could not find a temporary file, make sure that the temporary folder exists';
$lang['upload_file_size_none'] = 'upload empty files';
$lang['upload_file_size_cant_over'] = 'upload file size can not exceed';
$lang['upload_file_fail'] = ':copyfile upload failed: does not have copy operation permission';
$lang['upload_file_size_over'] = 'file size exceeds system settings';
$lang['upload_file_is_not_complete'] = 'The file is only partially Uploaded';
$lang['upload_file_is_not_uploaded'] = 'the file is only partially Uploaded';
$lang['upload_dir_chmod'] = 'temporary folder not found';
$lang['upload_file_write_fail'] = 'file write failure';
$lang['upload_file_mkdir'] = '(create directory';
$lang['upload_file_mkdir_fail'] = ')failure)';
$lang['upload_file_dir'] = '(catalog';
$lang['upload_file_dir_cant_touch_file'] = ')，cannot create file, please modify the permissions and then upload';

$lang['upload_image_px'] = 'pixel';
$lang['image_allow_ext_is'] = '，:the file type is not allowed to upload, the file type allowed is: ';
$lang['upload_image_is_not_image'] = 'illegal image file';
$lang['upload_image_mime_error'] = 'invalid image file type';
$lang['upload_file_attack'] = 'file upload';

$lang['transport_type_py']	= 'mail';
$lang['transport_type_kd']	= 'express';