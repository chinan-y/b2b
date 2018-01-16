<?php
defined('GcWebShop') or exit('Access Invalid!');
/**
 * index
 */
$lang['goods_class_index_choose_edit']			= 'please select the content to be edited';
$lang['goods_class_index_in_homepage']			= 'within homepage';
$lang['goods_class_index_display']				= 'display';
$lang['goods_class_index_hide']					= 'hide';
$lang['goods_class_index_succ']					= 'successful';
$lang['goods_class_index_choose_in_homepage']	= 'please select first page needed';
$lang['goods_class_index_content']				= 'content';
$lang['goods_class_index_class']				= 'product category';
$lang['goods_class_index_export']				= 'lead-out';
$lang['goods_class_index_import']				= 'lead-in';
$lang['goods_class_index_tag']					= 'TAG management';
$lang['goods_class_index_name']					= 'classification name';
$lang['goods_class_index_display_in_homepage']	= 'homepage displays';
$lang['goods_class_index_recommended']			= 'recommend';
$lang['goods_class_index_ensure_del']			= 'Delete the classification will also remove the classification of all the lower classification, are you sure you want to delete';
$lang['goods_class_index_display_tip']			= 'By default only displays the homepage to the second category';
$lang['goods_class_index_help1']				= 'when selles add idle items please select the classification referring to the idle  category';
$lang['goods_class_index_help2']				= 'Click the category name before the" + " symbol displays the current classification ofthe lower classification';
$lang['goods_class_index_help3'] 				= '<a>After you modify the category, need to go to settings - > after cleaning up the express company cache can come into effect</a>';
/**
 * batch edit
 */
$lang['goods_class_batch_edit_succ']			= 'batch adited';
$lang['goods_class_batch_edit_wrong_content']	= 'batch modified content are not corret';
$lang['goods_class_batch_edit_batch']			= 'batch edit';
$lang['goods_class_batch_edit_keep']			= 'remain the same';
$lang['goods_class_batch_edit_again']			= 're-edit this category';
$lang['goods_class_batch_edit_ok']				= 'category edited。';
$lang['goods_class_batch_edit_fail']			= 'fail to edit category。';
$lang['goods_class_batch_edit_paramerror']		= 'invalid argument';
$lang['goods_class_batch_order_empty_tip']		= '，space and remain the same  ';
/**
 * add category
 */
$lang['goods_class_add_name_null']				= 'category name can not be blank';
$lang['goods_class_add_sort_int']				= 'category can only sort in number';
$lang['goods_class_add_commis_rate_error']		= 'please input correct commission rate';
$lang['goods_class_add_back_to_list']			= 'back to category list';
$lang['goods_class_add_again']					= 'continue to add new category';
$lang['goods_class_add_name_exists']			= 'the category name already exists, please change it';
$lang['goods_class_add_sup_class']				= 'parent category';
$lang['goods_class_add_sup_class_notice']		= 'If you select a parent category, then the new subcategory wil be selected regarding the superior category';
$lang['goods_class_add_update_sort']			= 'number ranges from 0 to 255 , the smaller rank earlier';
$lang['goods_class_add_display_tip']			= 'whether category name can be displayed';
$lang['goods_class_add_type']					= 'type';
$lang['goods_class_add_commis_rate']			= 'commission rate';
$lang['goods_class_null_type']					= 'no category';
$lang['goods_class_add_type_desc_one']			= 'If no appropriate type in the drop-down option, you can go to';
$lang['goods_class_add_type_desc_two']			= 'add new type in the function';
$lang['goods_class_edit_prompts_one']			= 'Type relate to modify the specifications when publishing, goods without category can not add specification';
$lang['goods_class_edit_prompts_two']			= 'Check the default "associated with subcategory" categorizing commodity to subcategory
If the subclass is different from the superior type, you can cancel the check and separate edit the specification of subcategory.';
$lang['goods_class_edit_related_to_subclass']	= 'associated with subcategory';
/**
 * lead in by category
*/

$lang['goods_class_import_csv_null']			= 'lead-in csv file can not be empty';
$lang['goods_class_import_data']				= 'lead-in data';
$lang['goods_class_import_choose_file']			= 'please select file';
$lang['goods_class_import_file_tip']			= 'if the import is slow, split a file into several smaller files,and import them separately are recommended';
$lang['goods_class_import_choose_code']			= 'please select file number';
$lang['goods_class_import_code_tip']			= 'If the file is large, we recommend that you first convert the file to utf-8 encoding, so you can avoid time-consuming transcoding';
$lang['goods_class_import_file_type']			= 'file format';
$lang['goods_class_import_first_class']			= 'first category';
$lang['goods_class_import_second_class']		= 'second category';
$lang['goods_class_import_third_class']			= 'third category';
$lang['goods_class_import_example_download']	= 'example file download';
$lang['goods_class_import_example_tip']			= 'click and download the example file';
$lang['goods_class_import_import']				= 'import';
/**
 * lead out by category
 */
$lang['goods_class_export_data']				= 'export data';
$lang['goods_class_export_if_trans']			= 'export your commodity classification data';
$lang['goods_class_export_trans_tip']			= '';
$lang['goods_class_export_export']				= 'lead out';
$lang['goods_class_export_help1']				= 'lead out CSV file with category information';
/**
 * TAG index
 */
$lang['goods_class_tag_name']					= 'TAG name';
$lang['goods_class_tag_value']					= 'TAG number';
$lang['goods_class_tag_update']					= 'update TAG name';
$lang['goods_class_tag_update_prompt']			= 'update TAG name needs to spend a longer time, please wait patiently';
$lang['goods_class_tag_reset']					= 'lead in/reset';
$lang['goods_class_tag_reset_confirm']			= 'Are sure you want to import TAG, please? re-leading will  reset all the TAG information ';
$lang['goods_class_tag_prompts_two']			= 'TAG value is a search keyword  by category, please accurately fill out the TAG value.TAG values can fill out more than one and commas are needed in each value.';
$lang['goods_class_tag_prompts_three']			= 'import/reset TAG function can update TAG according to the commodity category, the TAG value is the default value for all classification of goods';
$lang['goods_class_tag_choose_data']			= 'select the data item you want to modify';
/**
 * TAG resetTAG
 */
$lang['goods_class_reset_tag_fail_no_class']	= 'reset TAG failed, didn not find any classified information';
/**
 * TAGupdate TAG name
 */
$lang['goods_class_update_tag_fail_no_class']	= 'failed to update the TAG name, and did not find any classified information';
/**
 * TAG delete TAG
 */
$lang['goods_class_tag_del_confirm']			= 'Are you sure you want to delete the category TAG?';