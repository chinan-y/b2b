<?php
defined('GcWebShop') or exit('Access Invalid!');
/**
 * index
 */
$lang['type_index_related_fail']			= '。partial information failed to be added. please edit this again';
$lang['type_index_continue_to_dd']			= 'continue to add';
$lang['type_index_return_type_list']		= 'return to the type list';
$lang['type_index_del_succ']				= '。delete successful';
$lang['type_index_del_fail']				= '。delete failed';
$lang['type_index_del_related_attr_fail']	= '。delete relative attribute failed';
$lang['type_index_del_related_brand_fail']	= '。delete relative brand failed';
$lang['type_index_del_related_type_fail']	= '。delete relative specification failed';
$lang['type_index_type_name']				= 'type';
$lang['type_index_no_checked']				= '。please select operational data';
$lang['type_index_prompts_one']				= '。，。need to chose one type when the administrator add goods category.the goods list on the front category generates goods retrieve, convenient for users to search goods that you need';
/**
 * add attribute
 */
$lang['type_add_related_brand']				= 'select relative brand';
$lang['type_add_related_spec']				= 'select relative specification';
$lang['type_add_remove']					= 'remove';
$lang['type_add_name_no_null']				= 'please fill in the type name';
$lang['type_add_name_max']					= '1-20type name must be between 1 and 20 characters long';
$lang['type_add_sort_no_null']				= 'please fill in category rank';
$lang['type_add_sort_no_digits']			= 'please fill in integer';
$lang['type_add_sort_desc']					= '。。please fill in natural number. type list will be sorted by ascending counts.';
$lang['type_add_spec_name']					= 'specification name';
$lang['type_add_spec_value']				= 'specifications';
$lang['type_add_spec_null_one']				= '，no specification, go';
$lang['type_add_spec_null_two']				= '！add specification!';
$lang['type_add_brand_null_one']			= '，no brand, go';
$lang['type_add_brand_null_two']			= '！add brand!';
$lang['type_add_attr_add']					= 'add attribute';
$lang['type_add_attr_add_one']				= 'add an attribute';
$lang['type_add_attr_add_one_value']		= 'add an attribute value';
$lang['type_add_attr_name']					= 'attribute name';
$lang['type_add_attr_value']				= 'optional attribute value';
$lang['type_add_prompts_one']				= '，。。relative specification  is not mandatory. it will affect the type-in of price and specification when realesed';
$lang['type_add_prompts_two']				= '，。relative brand is not mandatory. it will affect the selection of brand when realesed';
$lang['type_add_prompts_three']				= '，。can add more attribute value.there needs a comma-separated attributr value';
$lang['type_add_prompts_four']				= '“”，。pitch on the "display" option in attribute value, which will be displayed on the goods list page';
$lang['type_add_spec_must_choose']			= 'please select at least one specification';
$lang['type_common_checked_hide']			= 'hide unselected option';
$lang['type_common_checked_show']			= 'display all';
$lang['type_common_belong_class']			= 'belong the category';
$lang['type_common_belong_class_tips']		= 'select category. can associate general category or more specific subcategory.(it only works in the background shortcut location )';
/**
 * edit attribute
 */
$lang['type_edit_type_value_null']			= '。information of type value has not been added';
$lang['type_edit_type_value_del_fail']		= '。faile to delete the information of type value';
$lang['type_edit_type_attr_edit']			= 'edit attribute';
$lang['type_edit_type_attr_is_show']		= 'display or not';
$lang['type_edit_type_attr_name_no_null']	= 'the attribute value can not be null';
$lang['type_edit_type_attr_name_max']		= '10the name of attribute value can not be more than 10 characters ';
$lang['type_edit_type_attr_sort_no_null']	= 'rank can not be null';
$lang['type_edit_type_attr_sort_no_digits']	= 'rank value can only be number';
$lang['type_edit_type_attr_edit_succ']		= 'edit attribute successfully';
$lang['type_edit_type_attr_edit_fail']		= 'fail to edit attribute';
$lang['type_attr_edit_name_desc']			= '；：；please fill in common name of goods attribute';
$lang['type_attr_edit_sort_desc']			= ' 。。please fill in nutural numbers. attribute list will be sorted by ascending counts.';