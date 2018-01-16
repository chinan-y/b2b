<?php
defined('GcWebShop') or exit('Access Invalid!');

/**
 * template page
 */
$lang['index_index_store_goods_price']		= 'price';

/**
 * list page & edit page
 */
$lang['web_config_index']			= 'homepage management';
$lang['web_config_index_help1']			= 'the smaller it sorts, the higher it ranks. board can be controlled to display the ranks.';
$lang['web_config_index_help2']			= 'the color stylizer sticks with the front page, which can be changed in the basic setup.';
$lang['web_config_index_help3']			= 'the color stylizer has already existed in css style sheet. it is effective to change its name when the name of relevant program has been changed at the same time.';
$lang['web_config_update_time']	= 'refresh time';
$lang['web_config_web_name']				= 'boardtype';
$lang['web_config_style_name']				= 'color stylizer';
$lang['web_config_web_edit']				= 'basic setup';
$lang['web_config_code_edit']				= 'board edition';
$lang['web_config_web_name_tips']				= 'the boardtype is only displayed on the background template setting as panelcode but not on the front page.';
$lang['web_config_style_name_tips']				= 'the selection of color stylizer will have influence on the layout of frame, background color, font color of template section in the Mall Home but not the content.';
$lang['web_config_style_red']				= 'red';
$lang['web_config_style_pink']				= 'pink';
$lang['web_config_style_orange']				= 'orange';
$lang['web_config_style_green']				= 'green';
$lang['web_config_style_blue']				= 'blue';
$lang['web_config_style_purple']				= 'purple';
$lang['web_config_style_brown']				= 'brown';
$lang['web_config_style_white']				= 'white';
$lang['web_config_style_default']				= 'default';
$lang['web_config_add_name_null']				= 'boardtyle name is null.';
$lang['web_config_sort_int']		= 'only use number for ranking.';
$lang['web_config_sort_tips']	= 'the number range is between 0 and 225. the smaller the number is, the higher it ranks.';

/**
 * template edit page
 */
$lang['web_config_save']			= 'save';
$lang['web_config_web_html']			= 'update content';
$lang['web_config_edit_help1']			= 'the front display pagethe will change, with the usage of the "update content" at the bottom, when all the relevant settings are accomplished ';
$lang['web_config_edit_help2']			= 'there is no restriction on the number of "recommended category" on the left, but it can not be displayed if too much(the selected subcategory can be dragged to sort, selecting with click while deleting with double-click.).';
$lang['web_config_edit_help3']			= 'there are 8 items in the "Commodity recommendation" in the middle, because the page width can only contains 4 items.';
$lang['web_config_edit_html']			= 'content settings';
$lang['web_config_picture_tit']			= 'banner graphic';
$lang['web_config_edit_category']			= 'recommend category';
$lang['web_config_category_name']			= 'category name';
$lang['web_config_gc_name']			= 'subcategory';
$lang['web_config_picture_act']			= 'active picture';
$lang['web_config_add_recommend']			= 'add recommend category';
$lang['web_config_recommend_max']			= '(4)(a maxi of four)';
$lang['web_config_goods_order']			= 'goods ranking';
$lang['web_config_goods_name']			= 'goods name on the list.';
$lang['web_config_goods_price']			= 'price';
$lang['web_config_picture_adv']			= 'advertising images';
$lang['web_config_brand_list']			= 'brand recommendation';

$lang['web_config_upload_tit']			= 'header image upload';
$lang['web_config_prompt_tit']			= 'according to the requirement of operation annotation, please upload the header image in the upper-left corner of the setting module';
$lang['web_config_upload_tit_tips']			= 'photos of gif\jpg\png format which measures 210*40 are recommended. photos beyond the prescribed scope will be automatically hidden.';
$lang['web_config_upload_url']			= ' picture chaining';
$lang['web_config_upload_url_tips']			= 'input the jump chained address or leave it empty after clicking the picture.';
$lang['web_config_category_title']			= 'add the recommend category.';
$lang['web_config_category_note']			= 'select the recommending category in this module from the pull-down menu';
$lang['web_config_category_tips']			= 'tips:double-click on the categroy to delete those won not be displayed';

$lang['web_config_upload_act']			= 'active picture upload';
$lang['web_config_prompt_act']			= 'according to the requirement of operation annotation, please upload the active picture in the left area of the setting module';
$lang['web_config_upload_type']			= 'select type';
$lang['web_config_upload_pic']			= 'image upload';
$lang['web_config_upload_adv']			= 'advertising call';
$lang['web_config_upload_act_tips']			= 'photos of gif\jpg\png format which measures 212*280 are recommended. photos beyond the prescribed scope will be automatically hidden.';
$lang['web_config_upload_act_url']			= 'input the jump chained address or leave it empty after clicking the picture';

$lang['web_config_recommend_goods']			= 'recommend goods';
$lang['web_config_recommend_title']			= 'title name of recommending module';
$lang['web_config_recommend_tips']			= 'change the name of goods recommending module in the middle area. the name characters are limited to 4 to 8 characters, which will be automatically hidden if beyond the prescribed scope.';
$lang['web_config_recommend_goods_tips']			= 'tips: click on queried goods and select, then double-click on the selected one to delete, a maximum of eight, saved and effective.';
$lang['web_config_recommend_add_goods']			= 'select the recommending goods to displayed.';
$lang['web_config_recommend_gcategory']			= 'select category';
$lang['web_config_recommend_goods_name']			= 'goods name';

$lang['web_config_goods_order']			= 'goods ranking';
$lang['web_config_goods_order_title']			= 'title name of goods list module.';
$lang['web_config_goods_order_tips']			= 'change the name of goods recommending module in the middle area. the name characters are limited to 4 to 8 characters, which will be automatically hidden if beyond the prescribed scope.';
$lang['web_config_goods_list']			= 'goods list.';
$lang['web_config_goods_list_tips']			= 'tips:click on queried goods and select, then double-click on the selected one to delete. a maximum of five, saved and effective.';
$lang['web_config_goods_order_add']			= 'select the goods list to be displayed';
$lang['web_config_goods_order_gcategory']			= 'select category.';
$lang['web_config_goods_order_type']			= 'sort by';
$lang['web_config_goods_order_sale']			= 'sales number';
$lang['web_config_goods_order_click']			= 'views number';
$lang['web_config_goods_order_comment']			= 'comments number';
$lang['web_config_goods_order_collect']			= 'collect quantity';
$lang['web_config_goods_order_name']			= 'goods name';

$lang['web_config_brand_title']			= 'recommend brand';
$lang['web_config_brand_tips']			= 'tips: click on candidate brand and select, then double-click on the selected one to delete, a maximum of twelve, saved and effective.';
$lang['web_config_brand_list']			= 'candidate recommending brands list';

$lang['web_config_upload_adv_tips']			= 'according to the requirement of operation annotation, please upload the advertising images in the lower right corner of the setting module';
$lang['web_config_upload_adv_pic']			= 'upload advertising images';
$lang['web_config_upload_pic_tips']			= 'photos of gif\jpg\png format which measures 212*241 are recommended. photos beyond the prescribed scope will be automatically hidden.';
$lang['web_config_upload_adv_url']			= 'advertising chaining';
$lang['web_config_upload_pic_url_tips']			= 'input the jump chained address after clicking the picture.';