<?php
defined('GcWebShop') or exit('Access Invalid!');
/**
 * navigation and global
 */
$lang['adv_index_manage']	= 'Advertise manage';
$lang['adv_manage']	= 'Advertising';
$lang['adv_add']	= 'add advertising ';
$lang['ap_manage']	= 'advertising space';
$lang['ap_add']	    = 'add advertising space';
$lang['adv_change']	= 'change advertising ';
$lang['ap_change']	= 'add advertising space';
$lang['adv_pic']	= 'picture';
$lang['adv_word']	= 'word';
$lang['adv_slide']	= 'slide';
$lang['adv_edit']	= 'edit';
$lang['adv_change']	= 'change';
$lang['adv_pix']	= 'pixel';
$lang['adv_edit_support'] = ': image formats system supported are :';
$lang['adv_cache_refresh'] = 'refresh cache';
$lang['adv_cache_refresh_done'] = 'the advertising cache are cleared';
/**
 * ad
 */
$lang['adv_name']	         = 'ad name';
$lang['adv_ap_id']	         = 'affiliated ad space';
$lang['adv_class']	         = 'classification';
$lang['adv_start_time']	     = 'start time';
$lang['adv_end_time']	     = 'end time';
$lang['adv_all']	         = 'all';
$lang['adv_overtime']	     = 'over time';
$lang['adv_not_overtime']	 = 'not over time';
$lang['adv_img_upload']	     = 'image upload';
$lang['adv_url']	         = 'link address';
$lang['adv_url_donotadd']	 = 'http://';
$lang['adv_word_content']	 = 'word content';
$lang['adv_max']	         = 'maxium';
$lang['adv_byte']	         = 'byte';
$lang['adv_slide_upload']	 = 'slide upload';
$lang['adv_slide_sort']	     = 'slide sort';
$lang['adv_slide_sort_role'] = 'the smaller the number is,the more front it will be';
$lang['adv_ap_select']       = 'select advertising space';
$lang['adv_search_from']     = 'publish time';
$lang['adv_search_to']	     = 'to';
$lang['adv_click_num']	     = 'click numberˇ';
$lang['adv_admin_add']	     = 'administrator add';
$lang['adv_owner']	         = 'advertising owner';
$lang['adv_wait_check']	     = 'advertising wait to be checked ';
$lang['adv_flash_upload']	 = 'Flashflash upload';
$lang['adv_please_upload_swf_file']	 = 'swfplease upload swf file';
$lang['adv_help1']			 = '，when add an ad, you need to specify their advertising';
$lang['adv_help2']			 = '，place advertising calling code in a front page to display the advertising';
$lang['adv_help3']			 = 'the owner can use coins to buy advertising';
$lang['adv_help4']			 = 'audit the advertising brought by shop keeper';
$lang['adv_help5']			 = '，click to view, audit is available in detail pages';

/**
 * advertising space
 */
$lang['ap_name']	         = 'name';
$lang['ap_intro']	         = 'introdiction';
$lang['ap_class']	         = 'classification';
$lang['ap_show_style']	     = 'show style';
$lang['ap_width']	         = '/width/word';
$lang['ap_height']	         = 'height';
$lang['ap_price']	         = '(/)unit price (gold coin/month';
$lang['ap_show_num']	     = 'is showing';
$lang['ap_publish_num']	     = 'published';
$lang['ap_is_use']	         = 'enable?';
$lang['ap_slide_show']	     = 'slideshow';
$lang['ap_mul_adv']	         = 'multi-ads show';
$lang['ap_one_adv']	         = 'one ad show';
$lang['ap_use']	             = 'enabled';
$lang['ap_not_use']	         = 'not enabled';
$lang['ap_get_js']	         = 'code calls';
$lang['ap_use_s']	         = 'enable';
$lang['ap_not_use_s']	     = 'not enable';
$lang['ap_price_name']	     = 'unit price';
$lang['ap_price_unit']	     = '/gold coin/month';
$lang['ap_allow_mul_adv']	 = 'can post more ads and display at random';
$lang['ap_allow_one_adv']	 = 'only allow  display an AD';
$lang['ap_width_l']	         = 'width';
$lang['ap_height_l']	     = 'geight';
$lang['ap_word_num']	     = 'word';
$lang['ap_select_showstyle'] = 'select the show style of this advertising space ';
$lang['ap_click_num']	     = 'click number';
$lang['ap_help1']			 = ' you can choose whether to enable advertising after adding advertising';
/**
 * prompt message
 */
$lang['adv_can_not_null']	    = 'name can not be null';
$lang['must_select_ap']	        = 'must select an ad space';
$lang['must_select_start_time'] = 'must select start time';
$lang['must_select_end_time']	= 'must select end time';
$lang['must_select_ap_id']		= 'please select an ad space ';
$lang['textadv_null_error']		= 'please add text';
$lang['slideadv_null_error']	= 'please upload slide';
$lang['slideadv_sortnull_error']	= 'please add slide sort';
$lang['flashadv_null_error']	= 'FLASHplease upload flash file';
$lang['picadv_null_error']		= 'please upload image';
$lang['wordadv_toolong']	    = 'the advertising words are too long';
$lang['goback_adv_manage']	    = 'back to advertising management';
$lang['resume_adv_add']	        = 'continue to add advertising';
$lang['resume_ap_add']	        = 'continue to add advertising space';
$lang['adv_add_succ']	        = 'add successfully';
$lang['adv_add_fail']	        = 'fail to add';
$lang['ap_add_succ']	        = 'add successfully';
$lang['ap_add_fail']	        = 'fail to add advertising space';
$lang['goback_ap_manage']	    = 'back to advertising management';
$lang['ap_stat_edit_fail']	    = 'fail to modify advertising space atate';
$lang['ap_del_fail']	        = 'fail to delete advertising space';
$lang['ap_del_succ']	        = '，jssuccessful removal of the advertising, please deal with relevant template of advertising Js calls';
$lang['adv_del_fail']	        = 'fail to delete ad';
$lang['adv_del_succ']	        = 'delete ad successfully';
$lang['ap_can_not_null']	    = 'the name of ad space can not be null';
$lang['adv_url_can_not_null']	    = 'the ad link can not be null';
$lang['ap_price_can_not_null']	= 'the price of ad space can not be null';
$lang['ap_input_digits_pixel']		= '()please enter a pixel value ( positive integer )';
$lang['ap_input_digits_words']		= '()please enter the number of words ( positive integer )';
$lang['ap_default_word_can_not_null'] = 'the default words can not be null';
$lang['adv_start_time_can_not_null']	= 'the ad start time can not be null';
$lang['adv_end_time_can_not_null']	= 'the ad end time can not be null';
$lang['ap_w&h_can_not_null']	= 'the ad width and height can not be null';
$lang['ap_display_can_not_null']	= 'must select the ad space display mode';
$lang['ap_wordnum_can_not_null']	= ' the ad space word number can not be null';
$lang['ap_price_must_num']	    = 'advertising space prices can be noly digital form';
$lang['ap_width_must_num']	    = 'advertising space width can be noly digital form';
$lang['ap_wordwidth_must_num']	= 'advertising space word number can be noly digital form';
$lang['ap_height_must_num']	    = 'advertising space height can be noly digital form';
$lang['ap_change_succ']	        = 'change the ad space information successfully';
$lang['ap_change_fail']	        = ' fail to change the ad space information';
$lang['adv_change_succ']	    = 'change the ad information successfully';
$lang['adv_change_fail']	    = 'fail to change ad information';
$lang['adv_del_sure']	        = '?are you sure you want to delete all the information for the selected ad?';
$lang['ap_del_sure']	        = 'are you sure you want to delete all the information for the selected ad space?';
$lang['default_word_can_not_null'] = 'ad space default text can not be null';
$lang['default_pic_can_not_null']  = 'ad space default image must be uploaded';
$lang['must_input_all']  = '(!)(please be sure to fill in all the contents before submitting!)';
$lang['adv_index_copy_to_clip']	= 'JavaScriptPHP！please copy and paste the JavaScript or PHP code into the corresponding template file!';

$lang['check_adv_submit']  = 'review advertising application';
$lang['check_adv_yes']     = 'approved';
$lang['check_adv_no']      = 'do not pass';
$lang['check_adv_no2']     = 'fail to pass';
$lang['check_adv_type']    = 'type';
$lang['check_adv_buy']     = 'buy';
$lang['check_adv_order']   = 'order';
$lang['check_adv_change']  = 'cahnge content';
$lang['check_adv_view']    = 'check';
$lang['check_adv_nothing'] = 'there are currently no ads to be reviewed';
$lang['check_adv_chart']   = 'ad click rate statistics';
$lang['adv_chart_searchyear_input']  = ' :enter the year queried:';
$lang['adv_chart_year']    = 'year';
$lang['adv_chart_years_chart']    = 'ad click rate statistics in';
$lang['ap_default_pic']    = ':ad space default image';
$lang['ap_default_pic_upload']    = ':upload ad space default image';
$lang['ap_default_word']   = 'ad space default words';
$lang['ap_show_defaultpic_when_nothing']    = 'the default image used when no ad is available for display';
$lang['ap_show_defaultword_when_nothing']    = 'the default words used when no ad is available for display';

$lang['goback_to_adv_check']    = 'return to the pending ad list page';
$lang['adv_check_ok']      = 'audit ad successfully';
$lang['adv_check_failed']    = 'fail to audit ad';
$lang['return_goldpay']    = 'return of gold coins which are used to purchase advertising';
$lang['adv_chart_nothing_left']    = 'do not have this ad';
$lang['adv_chart_nothing_right']    = 'click rate information in';
