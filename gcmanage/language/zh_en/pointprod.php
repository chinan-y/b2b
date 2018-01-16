<?php
defined('GcWebShop') or exit('Access Invalid!');
/**
 * point gifts function pulicity
 */
$lang['admin_pointprodp']	 		= 'gifts';
$lang['admin_pointprod_unavailable']	 		= 'The system is unavailable for points center, whether to open it automatically';
$lang['admin_pointprod_parameter_error']		= 'parameter error';
$lang['admin_pointprod_record_error']			= 'record information error';
$lang['admin_pointprod_userrecord_error']		= 'user information error';
$lang['admin_pointprod_goodsrecord_error']		= 'gifts information error';
$lang['admin_pointprod_list_title']			= 'gifts list';
$lang['admin_pointprod_add_title']			= 'new gifts';
$lang['admin_pointprod_state']				= 'state';
$lang['admin_pointprod_show_up']			= 'hit the shelves';
$lang['admin_pointprod_show_down']			= 'taken off shelves';
$lang['admin_pointprod_commend']			= 'recommendation';
$lang['admin_pointprod_forbid']				= 'forbidden';
$lang['admin_pointprod_goods_name']			= 'gifts name';
$lang['pointprod_help1']					= 'Please ensure that the integral state of the system is turned on (Operation->Basic setting) before using the points exchange fuction, the gifts will appear in the integration center and the members can exchange them with points. After a successful exchange, the system platform will deliver gifts.';
$lang['admin_pointprod_goods_points']		= 'exchange points';
$lang['admin_pointprod_goods_price']		= 'gifts price';
$lang['admin_pointprod_goods_storage']		= 'storage';
$lang['admin_pointprod_goods_view']			= 'browse';
$lang['admin_pointprod_salenum']			= 'saled';
$lang['admin_pointprod_yes']				= 'yes';
$lang['admin_pointprod_no']					= 'no';
$lang['admin_pointprod_delfail']			= 'delete failed';
$lang['admin_pointorder_list_title']		= 'exchange list';
/**
 * add
 */
$lang['admin_pointprod_baseinfo']		= 'basic information of gifts';
$lang['admin_pointprod_goods_image']	= 'gifts image';
$lang['admin_pointprod_goods_tag']		= 'gifts tag';
$lang['admin_pointprod_goods_serial']	= 'gifts serial number';
$lang['admin_pointprod_requireinfo']	= 'requirement of exchange';
$lang['admin_pointprod_limittip']		= 'limit the exchange amount of each member';
$lang['admin_pointprod_limit_yes']		= 'limited';
$lang['admin_pointprod_limit_no']		= 'unlimited';
$lang['admin_pointprod_limitnum']		= 'the limited exchange amount of each member';
$lang['admin_pointprod_freightcharge']	= 'terms of freight charge';
$lang['admin_pointprod_freightcharge_saler']	= 'saler';
$lang['admin_pointprod_freightcharge_buyer']	= 'buyer';
$lang['admin_pointprod_freightprice']	= 'freight charge';
$lang['admin_pointprod_limittimetip']		= 'limit the time of exchange';
$lang['admin_pointprod_limittime_yes']		= 'limited';
$lang['admin_pointprod_limittime_no']		= 'unlimited';
$lang['admin_pointprod_starttime']	= 'start time';
$lang['admin_pointprod_endtime']	= 'end time';
$lang['admin_pointprod_time_day']	= 'day';
$lang['admin_pointprod_time_hour']	= 'hour';
$lang['admin_pointprod_stateinfo']	= 'conductivity state';
$lang['admin_pointprod_isshow']	= 'whether to hit the shelves';
$lang['admin_pointprod_iscommend']	= 'whether to recommend';
$lang['admin_pointprod_isforbid']	= 'whether to forbid';
$lang['admin_pointprod_forbidreason']= 'forbid reason';
$lang['admin_pointprod_seoinfo']	= 'SEO setting';
$lang['admin_pointprod_seokey']		= 'key words';
$lang['admin_pointprod_otherinfo']		= 'other settings';
$lang['admin_pointprod_sort']		= 'gifts ordering';
$lang['admin_pointprod_sorttip']		= 'Note: the number smaller, the rank higher';
$lang['admin_pointprod_seodescription']		= 'SEO description';
$lang['admin_pointprod_descriptioninfo']	= 'gifts description';
$lang['admin_pointprod_uploadimg']	= 'image upload';
$lang['admin_pointprod_uploadimg_more']	= 'batch upload';
$lang['admin_pointprod_uploadimg_common']	= 'normal upload';
$lang['admin_pointprod_uploadimg_complete']	= 'transmitted images';
$lang['admin_pointprod_uploadimg_add']	= 'insert';
$lang['admin_pointprod_uploadimg_addtoeditor']	= 'insert editor';
$lang['admin_pointprod_add_goodsname_error']	= 'please add the gifts name';
$lang['admin_pointprod_add_goodsprice_null_error']	= 'please add the gifts price ';
$lang['admin_pointprod_add_goodsprice_number_error']	= 'The price of gifts must be numeric and greater than or equal to 0';
$lang['admin_pointprod_add_goodspoint_null_error']	= 'please add the exchange points';
$lang['admin_pointprod_add_goodspoint_number_error']	= 'The exchange points must be integral and greater than or equal to 0';
$lang['admin_pointprod_add_goodsserial_null_error']	= 'plaese add the gifts serial number';
$lang['admin_pointprod_add_storage_null_error']	    = 'please add the gifts storage';
$lang['admin_pointprod_add_storage_number_error']	= 'The gifts storage must be integral and greater than or equal to 0';
$lang['admin_pointprod_add_limitnum_error']			= 'please add the limited exchange number of each member';
$lang['admin_pointprod_add_limitnum_digits_error']	= 'The limited exchange number must be integral and greater than or equal to 0';
$lang['admin_pointprod_add_freightprice_null_error']		= 'please add the freight charge';
$lang['admin_pointprod_add_freightprice_number_error']		= 'The freight charge must be numeric and greater than or equal to 0';
$lang['admin_pointprod_add_limittime_null_error']		= 'please add the time of start and end';
$lang['admin_pointprod_add_sort_null_error']		= 'please add gifts ordering';
$lang['admin_pointprod_add_sort_number_error']		= 'The gifts order must be integral and greater than or equal to 0';
$lang['admin_pointprod_add_upload']		= 'upload';
$lang['admin_pointprod_add_upload_img_error']		= 'images limited to png,gif,jpeg,jpg format';
$lang['admin_pointprod_add_iframe_uploadfail']		= 'upload failed';
$lang['admin_pointprod_add_success']		= 'gift adding succeed';
/**
 * update
 */
$lang['admin_pointprod_edit_success']		= 'gifts update succeed';
$lang['admin_pointprod_edit_addtime']		= 'add time';
$lang['admin_pointprod_edit_viewnum']		= 'page views';
$lang['admin_pointprod_edit_salenum']		= 'sale volume';
/**
 * deletion
 */
$lang['admin_pointprod_del_success']		= 'delete succeed';
$lang['admin_pointprod_del_fail']			= 'delete failed';