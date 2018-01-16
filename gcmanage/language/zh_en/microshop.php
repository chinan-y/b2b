<?php
defined('GcWebShop') or exit('Access Invalid!');
$lang['microshop_not_install'] = 'you did not install microshop module';

$lang['microshop_member'] = 'user';
$lang['microshop_channel'] = 'channel';
$lang['microshop_commend'] = 'recommendation';
$lang['microshop_text_id'] = 'number';

$lang['microshop_class_name'] = 'classification name';
$lang['microshop_parent_class'] = 'parent classification ';
$lang['microshop_class_image'] = 'calassification image';
$lang['microshop_class_keyword'] = 'classification keywords';

$lang['microshop_goods_class_binding'] = 'binding classification';
$lang['microshop_goods_class_binding_select'] = 'select classification';
$lang['microshop_goods_class_binded'] = 'classification binded';
$lang['goods_relation_save_success'] = 'binding classificaton saved';
$lang['goods_relation_save_fail'] = 'failed to save binging classification';
$lang['microshop_goods_class_default'] = 'set as default';

//classification table
$lang['class_parent_id_error'] = 'classification parent number is incorrect';
$lang['class_name_error'] = 'classification name cannot be empty and must be less than 10 characters';
$lang['class_name_required'] = 'classification name cannot be empty';
$lang['class_name_maxlength'] = 'classification names require maximum {0} characters';
$lang['class_keyword_maxlength'] = 'Category keywords require maximum {0} characters';
$lang['class_keyword_explain'] = 'Category keywords separated by commas, if you need to highlight the keywords, add "*" in the front, for example: "pants, * shoes"';
$lang['class_sort_explain'] = 'the range of numbers is 0~255 in ascending order';
$lang['class_sort_error'] = 'Sorting must be a number between 0~255';
$lang['class_sort_required'] = 'ordering cannot be empty';
$lang['class_sort_digits'] = 'orders must be numeris';
$lang['class_sort_max'] = 'the highest rank is {0}';
$lang['class_sort_min'] = 'the lowest rank is {0}';
$lang['class_add_success'] = 'Category saved successfully';
$lang['class_add_fail'] = 'failed to save category';
$lang['class_drop_success'] = 'category deleted successfully';
$lang['class_drop_fail'] = 'failed to delete category';
$lang['microshop_sort_error'] = 'orders must be number between 0 and 255';

//Microshop management
$lang['microshop_isuse'] = 'Microshop switch';
$lang['microshop_isuse_explain'] = 'Microshop will not be able to access after closing the front page';
$lang['microshop_url'] = 'Microshop address';
$lang['microshop_url_explain'] = 'if microshop set a second-level domain, the link of microshop will use this domain after filling up, default address will be used if any blank space exsits , ';
$lang['microshop_style'] = 'theme of microshop';
$lang['microshop_style_explain'] = 'default set the theme of microshop, original setting is default';
$lang['microshop_header_image'] = 'microshop head images';
$lang['microshop_personal_limit'] = 'quantitative limitation of personal show';
$lang['microshop_personal_limit_explain'] = 'members publish the quantity limitation of personal show, 0 is excluded.';
$lang['taobao_api_isuse'] = 'Taobao interface switch';
$lang['taobao_api_isuse_explain'] = 'enable and fill in correct interface parameters and then purchase links to support Taobao and Tmall when publishing personal show.';
$lang['taobao_app_key'] = 'Taobao application identity';
$lang['taobao_app_key_explain'] = 'Apply online now';
$lang['taobao_secret_key'] = 'Taobao application key';
$lang['microshop_seo_keywords'] = 'SEO keywords of microshop';
$lang['microshop_seo_description'] = 'SEO description of microshop';

//random look
$lang['microshop_goods_name'] = 'product name';
$lang['microshop_goods_image'] = 'product images';
$lang['microshop_commend_time'] = 'Recommended time';
$lang['microshop_commend_message'] = 'Recommended description';
$lang['microshop_goods_tip1'] = 'the display order of random look in front page can be controled through changing the ranking number, the smaller the number is, the more forward the rank will be';
$lang['microshop_goods_tip2'] = 'light recommendation column to recommend this product to the Home of microshop';
$lang['microshop_goods_class_tip1'] = 'the display order of random look in front page can be controled through changing the ranking number, the smaller the number is, the more forward the rank will be';
$lang['microshop_goods_class_tip2'] = 'light recommendation column to recommend this category to the Home of microshop';
$lang['microshop_goods_class_tip3'] = 'at the beginning of line to expand lower classifications';
$lang['microshop_goods_class_tip4'] = 'click "bind category" button behind second-level category to bind the category of microshop and its system, items recommended by random look will be automatically classified after that.';
$lang['microshop_goods_class_tip5'] = 'click "set as default" button behind second-level category to set default category of microshop, all unbinded categories published by random look will use default category';
$lang['microshop_goods_class_binding_tip1'] = 'select the shop category below and click to finish binding, items recommended by random look will be automatically classified after that';
$lang['microshop_goods_class_binding_tip2'] = 'move the mouse to binded category, click "x" to cancel bind';
$lang['microshop_personal_tip1'] = 'the display order of random look in front page can be controled through changing the ranking number, the smaller the number is, the more forward the rank will be';
$lang['microshop_personal_tip2'] = 'light recommendation column to recommend this product to the Home of microshop';
//shop
$lang['microshop_store_add_confirm'] = 'Confirm adding the shop to shop Street?';
$lang['microshop_store_goods_count'] = 'number of goods';
$lang['microshop_store_credit'] = 'credit of seller';
$lang['microshop_store_praise_rate'] = 'Favorable rate';
$lang['microshop_store_add'] = 'added';
$lang['microshop_store_tip1'] = 'the display order of store street in front page can be controled through changing the ranking number, the smaller the number is, the more forward the rank will be';
$lang['microshop_store_tip2'] = 'light recommendation column to recommend this store to the Home of microshop, maximum 15 stores will be displayed in Home';
$lang['microshop_store_add_tip1'] = 'click "add" to add stores to the store street of microshop';

//Comment
$lang['microshop_comment_id'] = 'Comment number';
$lang['microshop_comment_object_id'] = 'Object number';
$lang['microshop_comment_message'] = 'Comment content';
$lang['microshop_comment_tip1'] = 'Click "delete" button to delete corresponding comment';

//advertisement
$lang['microshop_adv_type'] = 'types of advertisement';
$lang['microshop_adv_name'] = 'names of advertisement';
$lang['microshop_adv_image'] = 'advertising images';
$lang['microshop_adv_url'] = 'advertising links';
$lang['microshop_adv_type_index'] = 'Home slide';
$lang['microshop_adv_type_store_list'] = 'store list page slide ';
$lang['microshop_adv_image_error'] = 'advertising image cannot be empty';
$lang['microshop_adv_tip1'] = 'the display order in front page can be controled through changing the ranking number, the smaller the number is, the more forward the rank will be.';
$lang['microshop_adv_type_explain'] = 'choose corresponding advertising places ';
$lang['microshop_adv_image_explain'] = '1000px*250px recommended size of advertising images in Home page:700px*280px, recommended size of advertising images in store list page:1000px*250px';
$lang['microshop_adv_url_explain'] = 'corresponding links of this advertisement';


