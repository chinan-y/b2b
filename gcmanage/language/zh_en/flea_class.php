<?php
defined('GcWebShop') or exit('Access Invalid!');
	
$lang['flea_class']							   = 'idle category';
$lang['flea_class_help1']					   = 'when users add information for idle items please select the classification referring to the idle  category';
$lang['flea_class_help2']					   = 'Click the category name before the " + " symbol displays the current classification ofthe lower classification';
$lang['flea_class_index_tips']                 = 'the name of top page classification can be changed to " display as XXX " otherwise the original category name is displayed';
$lang['flea_isuse_off_tips']                   = 'the system does not have idle market function';

$lang['flea_class_setting_ok']                 = 'successfully set  '; 
$lang['flea_class_setting_error']              = 'fail to set'; 
$lang['flea_index_class_setting']              = 'first page category settings';
$lang['flea_index_class_setting_info']         = 'set information';
$lang['flea_index_class_setting_digital']      = 'digital';
$lang['flea_index_class_setting_beauty']       = 'decoration';
$lang['flea_index_class_setting_home']         = 'househould';
$lang['flea_index_class_setting_interest']     = 'interest';
$lang['flea_index_class_setting_baby']         = 'mother and baby';
$lang['flea_index_class_setting_as']           = 'display as';
/**
* index
*/
$lang['goods_class_index_del_succ']            = 'successfully delete the categoty';
$lang['goods_class_index_choose_del']          = 'plaese select the content to be deleted';
$lang['goods_class_index_choose_edit']         = 'please select the content to be edited';
$lang['goods_class_index_in_homepage']         = 'within first page';
$lang['goods_class_index_display']             = 'display';
$lang['goods_class_index_hide']                = 'hide';
$lang['goods_class_index_succ']                = 'ok';
$lang['goods_class_index_choose_in_homepage']  = 'please select first page needed';
$lang['goods_class_index_content']             = 'content!';
$lang['goods_class_index_class']               = 'product category';
$lang['goods_class_index_export']              = 'lead-out';
$lang['goods_class_index_import']              = 'lead-in';
$lang['goods_class_index_name']                = 'classification name';
$lang['goods_class_index_display_in_homepage'] = 'first page displays';
$lang['goods_class_index_ensure_del']          = 'Delete the classification will also remove the classification of all the lower classification, are you sure you want to delete';
$lang['goods_class_index_display_tip']         = 'By default only displays the first page to the second category';
/**
* batch edit
*/
$lang['goods_class_batch_edit_succ']           = 'batch aditted';
$lang['goods_class_batch_edit_wrong_content']  = 'batch modified content are not corret';
$lang['goods_class_batch_edit_batch']          = 'batch edit';
$lang['goods_class_batch_edit_keep']           = 'remain the same';
$lang['goods_class_batch_edit_again']          = 're-edit this category';
$lang['goods_class_batch_edit_ok']             = 'category editted';
$lang['goods_class_batch_edit_fail']           = 'fail to edit category';
$lang['goods_class_batch_edit_paramerror']     = 'invalid argument';
/**
* add category
*/
$lang['goods_class_add_name_null']             = 'category name can not be blank';
$lang['goods_class_add_sort_int']              = 'category can only sort in number';
$lang['goods_class_add_back_to_list']          = 'back to category list';
$lang['goods_class_add_again']                 = 'continue to add new category';
$lang['goods_class_add_succ']                  = 'new category added';
$lang['goods_class_add_fail']                  = 'fail to add new category';
$lang['goods_class_add_name_exists']           = 'the category name already exists, please change it';
$lang['goods_class_add_sup_class']             = 'parent category';
$lang['goods_class_add_sup_class_notice']	= 'If you select a parent category, then the new subcategory wil be selected regarding the superior category';
$lang['goods_class_add_update_sort']           = 'update sortin';
$lang['goods_class_add_display_tip']           = 'whether new addded category name can be displayed';
/**
*  lead in by category
*/
$lang['goods_class_import_succ']               = 'led in successfully';
$lang['goods_class_import_csv_null']           = 'lead-in csv file can not be empty';
$lang['goods_class_import_data']               = 'lead-in data ';
$lang['goods_class_import_choose_file']        = 'please select file';
$lang['goods_class_import_file_tip']           = 'if the import is slow, split a file into several smaller files,and import them separately are recommended';
$lang['goods_class_import_choose_code']        = 'please select file number';
$lang['goods_class_import_code_tip']           = 'If the file is large, we recommend that you first convert the file to utf-8 encoding, so you can avoid time-consuming transcoding';
$lang['goods_class_import_file_type']          = 'file format';
$lang['goods_class_import_first_class']        = 'first category';
$lang['goods_class_import_second_class']       = 'second category';
$lang['goods_class_import_third_class']        = 'third category';
$lang['goods_class_import_example_download']   = 'example file download';
$lang['goods_class_import_example_tip']        = 'click the imported example file ';
$lang['goods_class_import_import']             = 'lead in';
/**
* lead out by category
*/
$lang['goods_class_export_data']               = 'lead out data';
$lang['goods_class_export_if_trans']           = 'lead out your data of goods category';
$lang['goods_class_export_trans_tip']          = '';
$lang['goods_class_export_export']             = 'lead out';
$lang['goods_class_export_help1']				= 'lead out CSV file with category information';