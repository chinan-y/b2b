<?php
/**
 * 载入权限
 *
 */
defined('GcWebShop') or exit('Access Invalid!');
$_limit =  array(
	array('name'=>$lang['nc_config'], 'child'=>array(
		array('name'=>$lang['nc_web_set'], 'gp'=>null, 'gct'=>'setting'),
		// array('name'=>$lang['nc_web_account_syn'], 'gp'=>null, 'gct'=>'account'),
		array('name'=>$lang['nc_upload_set'], 'gp'=>null, 'gct'=>'upload'),
		array('name'=>$lang['nc_seo_set'], 'gp'=>'seo', 'gct'=>'setting'),
		array('name'=>$lang['nc_pay_method'], 'gp'=>null, 'gct'=>'payment'),
		array('name'=>$lang['nc_message_set'], 'gp'=>null, 'gct'=>'message'),
		array('name'=>$lang['nc_admin_express_set'], 'gp'=>null, 'gct'=>'express'),
		array('name'=>$lang['nc_waybill_template'], 'gp'=>null, 'gct'=>'waybill'),
		array('name'=>$lang['nc_admin_offpay_area_set'], 'gp'=>null, 'gct'=>'offpay_area'),
	    array('name'=>$lang['nc_admin_clear_cache'], 'gp'=>null, 'gct'=>'cache'),
	    array('name'=>$lang['nc_admin_perform_opt'], 'gp'=>null, 'gct'=>'perform'),
	    array('name'=>$lang['nc_admin_search_set'], 'gp'=>null, 'gct'=>'search'),
	    array('name'=>$lang['nc_admin_log'], 'gp'=>null, 'gct'=>'admin_log'),
		array('name'=>$lang['nc_custom_qrcode'], 'gp'=>null, 'gct'=>'custom_qrcode'),
		)),
	array('name'=>$lang['nc_goods'], 'child'=>array(
		array('name'=>$lang['nc_goods_manage'], 'gp'=>null, 'gct'=>'goods'),
		array('name'=>$lang['nc_class_manage'], 'gp'=>null, 'gct'=>'goods_class'),
		array('name'=>$lang['nc_brand_manage'], 'gp'=>null, 'gct'=>'brand'),
		array('name'=>$lang['nc_type_manage'], 'gp'=>null, 'gct'=>'type'),
		array('name'=>$lang['nc_spec_manage'], 'gp'=>null, 'gct'=>'spec'),
		array('name'=>$lang['nc_album_manage'], 'gp'=>null, 'gct'=>'goods_album'),
		array('name'=>$lang['nc_goods_warehouse'], 'gp'=>null,   'gct'=>'goods_address'),
		array('name'=>$lang['nc_goods_source'], 'gp'=>null,   'gct'=>'source'),
		array('name'=>$lang['nc_goods_supplier'],'gp'=>null,   'gct'=>'supplier'),
		array('name'=>$lang['nc_per_manage'],'gp'=>null, 'gct'=>'per_goods'),
		array('name'=>$lang['nc_validate_manage'],'gp'=>null, 'gct'=>'goods_validity'),
		)),
	array('name'=>$lang['nc_store'], 'child'=>array(
		array('name'=>$lang['nc_store_manage'], 'gp'=>null, 'gct'=>'store'),
		array('name'=>$lang['nc_store_grade'], 'gp'=>null, 'gct'=>'store_grade'),
		array('name'=>$lang['nc_store_class'], 'gp'=>null, 'gct'=>'store_class'),
		array('name'=>$lang['nc_domain_manage'], 'gp'=>null, 'gct'=>'domain'),
		array('name'=>$lang['nc_s_snstrace'], 'gp'=>null, 'gct'=>'sns_strace'),
		array('name'=>$lang['nc_openstore_help'], 'gp'=>null, 'gct'=>'help_store'),
		array('name'=>$lang['nc_store_index'], 'gp'=>null, 'gct'=>'store_joinin'),
		array('name'=>$lang['nc_self_store'], 'gp'=>null, 'gct'=>'ownshop'),
		)),		
	array('name'=>$lang['nc_member'], 'child'=>array(
		array('name'=>$lang['nc_member_manage'], 'gp'=>null, 'gct'=>'member'),
	    array('name'=>$lang['nc_member_level'], 'gp'=>null, 'gct'=>'member_grade'),
	    array('name'=>$lang['nc_exppoints_manage'], 'gp'=>null, 'gct'=>'exppoints'),
		array('name'=>$lang['nc_member_notice'], 'gp'=>null, 'gct'=>'notice'),
		array('name'=>$lang['nc_member_pointsmanage'], 'gp'=>null, 'gct'=>'points'),
		array('name'=>$lang['nc_binding_manage'], 'gp'=>null, 'gct'=>'sns_sharesetting'),
		array('name'=>$lang['nc_cash_transfei'], 'gp'=>null, 'gct'=>'alipay_batch'),
		array('name'=>$lang['nc_member_album_manage'], 'gp'=>null, 'gct'=>'sns_malbum'),
	    array('name'=>$lang['nc_snstrace'], 'gp'=>null, 'gct'=>'snstrace'),
		array('name'=>$lang['nc_member_tag'], 'gp'=>null, 'gct'=>'sns_member'),
		array('name'=>$lang['nc_member_predepositmanage'], 'gp'=>null, 'gct'=>'predeposit'),
		array('name'=>$lang['nc_chat_record'], 'gp'=>null, 'gct'=>'chat_log'),
		array('name'=>$lang['nc_member_group'], 'gp'=>null, 'gct'=>'member_batch'),
		)),
	array('name'=>$lang['nc_trade'], 'child'=>array(
		array('name'=>$lang['nc_order_manage'], 'gp'=>null, 'gct'=>'order'),
	    array('name'=>$lang['nc_vr_order'], 'gp'=>null, 'gct'=>'vr_order'),
		array('name'=>$lang['nc_refund_manage1'], 'gp'=>null, 'gct'=>'refund'),
		array('name'=>$lang['nc_refund_manage2'], 'gp'=>null, 'gct'=>'return'),
		array('name'=>$lang['nc_vrorder_refund'], 'gp'=>null, 'gct'=>'vr_refund'),
		array('name'=>$lang['nc_consult_manage'], 'gp'=>null, 'gct'=>'consulting'),
		array('name'=>$lang['nc_inform_config'], 'gp'=>null, 'gct'=>'inform'),
		array('name'=>$lang['nc_goods_evaluate'], 'gp'=>null, 'gct'=>'evaluate'),
		array('name'=>$lang['nc_complain_config'], 'gp'=>null, 'gct'=>'complain'),
		)),
	array('name'=>$lang['nc_website'], 'child'=>array(
		array('name'=>$lang['nc_article_class'], 'gp'=>null, 'gct'=>'article_class'),
		array('name'=>$lang['nc_article_manage'], 'gp'=>null, 'gct'=>'article'),
		array('name'=>$lang['nc_document'], 'gp'=>null, 'gct'=>'document'),
		array('name'=>$lang['nc_navigation'], 'gp'=>null, 'gct'=>'navigation'),
		array('name'=>$lang['nc_adv_manage'], 'gp'=>null, 'gct'=>'adv'),
		array('name'=>$lang['nc_web_index'], 'gp'=>null, 'gct'=>'web_config|web_api'),
		array('name'=>$lang['nc_admin_res_position'], 'gp'=>null, 'gct'=>'rec_position'),
		array('name'=>$lang['nc_link'], 'gp'=>null, 'gct'=>'link'),
		)),
	array('name'=>$lang['nc_operation'], 'child'=>array(
		array('name'=>$lang['nc_operation_set'], 'gp'=>null, 'gct'=>'operation'),
		array('name'=>$lang['nc_groupbuy_manage'], 'gp'=>null, 'gct'=>'groupbuy'),
		array('name'=>$lang['nc_tuangou_manage'], 'gp'=>null, 'gct'=>'tuangou'),
        array('name'=>$lang['nc_vrbuying_set'], 'gp'=>null, 'gct'=>'vr_groupbuy'),
        array('name'=>$lang['nc_mpweixin_redbagset'], 'gp'=>null, 'gct'=>'wxpack'),
		array('name'=>$lang['nc_activity_manage'], 'gp'=>null, 'gct'=>'activity'),
		array('name'=>$lang['nc_promotion_xianshi'], 'gp'=>null, 'gct'=>'promotion_xianshi'),
		array('name'=>$lang['nc_promotion_mansong'], 	'gp'=>null, 'gct'=>'promotion_mansong'),
		array('name'=>$lang['nc_promotion_bundling'], 'gp'=>null, 'gct'=>'promotion_bundling'),
		array('name'=>$lang['nc_promotion_bundling'], 'gp'=>null, 'gct'=>'promotion_bundling'),
		array('name'=>$lang['nc_pointprod'], 'gp'=>null, 'gct'=>'pointprod|pointorder'),
		array('name'=>$lang['nc_voucher_price_manage'], 	'gp'=>null, 'gct'=>'voucher'),
	    array('name'=>$lang['nc_bill_manage'], 'gp'=>null, 'gct'=>'bill'),
	    array('name'=>$lang['nc_vrorder_settlement'], 'gp'=>null, 'gct'=>'vr_bill'),
	    array('name'=>$lang['nc_mall_services'], 'gp'=>null, 'gct'=>'mall_consult'),
        array('name'=>$lang['nc_mall_rechargecard'], 'gp'=>null, 'gct'=>'rechargecard'),
	    array('name'=>$lang['nc_logistics_station'], 'gp'=>null, 'gct'=>'delivery'),

		)),
	/*array('name'=>$lang['nc_stat'], 'child'=>array(
	    array('name'=>$lang['nc_statgeneral'], 'gp'=>null, 'gct'=>'stat_general'),
	    array('name'=>$lang['nc_statindustry'], 'gp'=>null, 'gct'=>'stat_industry'),
		array('name'=>$lang['nc_statmember'], 'gp'=>null, 'gct'=>'stat_member'),
		array('name'=>$lang['nc_statstore'], 'gp'=>null, 'gct'=>'stat_store'),
		array('name'=>$lang['nc_stattrade'], 'gp'=>null, 'gct'=>'stat_trade'),
		array('name'=>$lang['nc_statgoods'], 'gp'=>null, 'gct'=>'stat_goods'),
		array('name'=>$lang['nc_statmarketing'], 'gp'=>null, 'gct'=>'stat_marketing'),
		array('name'=>$lang['nc_stataftersale'], 	'gp'=>null, 'gct'=>'stat_aftersale'),
		array('name'=>'导出报表', 	'gp'=>null, 'gct'=>'exportexcel'),
		)),
	array('name'=>$lang['nc_live'], 'child'=>array(
	    array('name'=>$lang['nc_live1'], 'gp'=>'live_modifly', 'gct'=>'live'),
	    array('name'=>$lang['nc_live2'], 'gp'=>'live_manage', 'gct'=>'live'),
		array('name'=>$lang['nc_live3'], 'gp'=>'live_class_a', 'gct'=>'live'),
		array('name'=>$lang['nc_live4'], 'gp'=>'on_demand', 'gct'=>'live'),
		)),
	array('name'=>'分销', 'child'=>array(
		array('name'=>$lang['nc_saleman_manage'], 'gp'=>null, 'gct'=>'erwei_member'),
		array('name'=>$lang['nc_sale_achievement'], 'gp'=>null, 'gct'=>'salescredit'),
		array('name'=>$lang['nc_cooper_platform'], 'gp'=>null, 'gct'=>'partner'),
		array('name'=>$lang['nc_uparea_manage'], 'gp'=>null, 'gct'=>'platform_order'),
		array('name'=>'订单导入管理', 'gp'=>null, 'gct'=>'importorder'),
		array('name'=>$lang['nc_interface_order'], 'gp'=>null, 'gct'=>'thirdface'),
		array('name'=>$lang['nc_saleteam_manage'],'gp'=>null, 'gct'=>'sales_area'),
		array('name'=>$lang['nc_sale_general_area'], 'gp'=>null, 'gct'=>'order_salearea'),
		array('name'=>$lang['nc_business_join'],'gp'=>null, 'gct'=>'merchants'),
		)),	
	array('name'=>'对象存储', 'child'=>array(
		array('name'=>'阿里云参数', 'gp'=>null, 'gct'=>'aliyunset'),
		array('name'=>'对象存储', 'gp'=>null, 'gct'=>'oss_aliyun'),
		)),	*/
);
/*if (C('mess_isuse') !== NULL){
	$_limit[] = array('name'=>$lang['nc_custom_api'], 'child'=>array(
		array('name'=>$lang['nc_custom_basic_set'], 'gp'=>NULL, 'gct'=>'mess'),
		array('name'=>$lang['nc_custom_goods_audit'], 'gp'=>NULL, 'gct'=>'mess_sku'),
		array('name'=>$lang['nc_custom_order_manage'], 'gp'=>NULL, 'gct'=>'mess_order'),
		array('name'=>$lang['nc_custom_payment_manage'], 'gp'=>NULL, 'gct'=>'mess_paysn'),
		array('name'=>$lang['nc_custom_transbill_manage'], 'gp'=>NULL, 'gct'=>'mess_bill'),
		array('name'=>'通关查询', 'gp'=>NULL, 'gct'=>'mess_order_info'),
		array('name'=>'税率查询', 'gp'=>NULL, 'gct'=>'tax_rate'),
		));
}

if (C('flea_isuse') !== NULL){
	$_limit[] = array('name'=>$lang['nc_flea'], 'child'=>array(
		array('name'=>$lang['nc_flea_seoset'], 'gp'=>NULL, 'gct'=>'flea_index'),
		array('name'=>$lang['nc_flea_class_manage'], 'gp'=>NULL, 'gct'=>'flea_class'),
		array('name'=>$lang['nc_flea_index_class'], 'gp'=>NULL, 'gct'=>'flea_class_index'),
		array('name'=>$lang['nc_flea_manage'], 'gp'=>NULL, 'gct'=>'flea'),
		array('name'=>$lang['nc_flea_area_manage'], 'gp'=>NULL, 'gct'=>'flea_cs')
		));
}
if (C('mobile_isuse') !== NULL){
	$_limit[] = array('name'=>$lang['nc_mobile'], 'child'=>array(
		array('name'=>$lang['nc_index_edit'], 'gp'=>NULL, 'gct'=>'mb_special'),
		array('name'=>$lang['nc_special_set'], 'gp'=>NULL, 'gct'=>'mb_special'),
		array('name'=>$lang['nc_mobile_catepic'], 'gp'=>NULL, 'gct'=>'mb_category'),
		array('name'=>$lang['nc_download_set'], 'gp'=>NULL, 'gct'=>'mb_app'),
		array('name'=>$lang['nc_mobile_feedback'], 'gp'=>NULL, 'gct'=>'mb_feedback'),
		array('name'=>$lang['nc_mobile_payment'], 'gp'=>NULL, 'gct'=>'mb_payment'),
		));
}

if (C('microshop_isuse') !== NULL){
	$_limit[] = array('name'=>$lang['nc_microshop'], 'child'=>array(
		array('name'=>$lang['nc_microshop_manage'], 'gp'=>'manage', 'gct'=>'microshop'),
		array('name'=>$lang['nc_microshop_goods_manage'], 'gp'=>'goods|goods_manage', 'gct'=>'microshop'),//op值重复(goods_manage,goodsclass_list,personal_manage...)是为了无权时，隐藏该菜单
		array('name'=>$lang['nc_microshop_goods_class'], 'gp'=>'goodsclass|goodsclass_list', 'gct'=>'microshop'),
		array('name'=>$lang['nc_microshop_personal_manage'], 'gp'=>'personal|personal_manage', 'gct'=>'microshop'),
		array('name'=>$lang['nc_microshop_personal_class'], 'gp'=>'personalclass|personalclass_list', 'gct'=>'microshop'),
		array('name'=>$lang['nc_microshop_store_manage'], 'gp'=>'store|store_manage', 'gct'=>'microshop'),
		array('name'=>$lang['nc_microshop_comment_manage'], 'gp'=>'comment|comment_manage', 'gct'=>'microshop'),
		array('name'=>$lang['nc_microshop_adv_manage'], 'gp'=>'adv|adv_manage', 'gct'=>'microshop')
		));
}

if (C('cms_isuse') !== NULL){
	$_limit[] = array('name'=>$lang['nc_cms'], 'child'=>array(
		array('name'=>$lang['nc_cms_manage'], 'gp'=>null, 'gct'=>'cms_manage'),
		array('name'=>$lang['nc_cms_index_manage'], 'gp'=>null, 'gct'=>'cms_index'),
		array('name'=>$lang['nc_cms_article_manage'], 'gp'=>null, 'gct'=>'cms_article|cms_article_class'),
		array('name'=>$lang['nc_cms_picture_manage'], 'gp'=>null, 'gct'=>'cms_picture|cms_picture_class'),
		array('name'=>$lang['nc_cms_special_manage'], 'gp'=>null, 'gct'=>'cms_special'),
		array('name'=>$lang['nc_cms_navigation_manage'], 'gp'=>null, 'gct'=>'cms_navigation'),
		array('name'=>$lang['nc_cms_tag_manage'], 'gp'=>null, 'gct'=>'cms_tag'),
		array('name'=>$lang['nc_cms_comment_manage'], 'gp'=>null, 'gct'=>'cms_comment')
		));
}

if (C('circle_isuse') !== NULL){
	$_limit[] = array('name'=>$lang['nc_circle'], 'child'=>array(
		array('name'=>$lang['nc_circle_setting'], 'gp'=>null, 'gct'=>'circle_setting'),
		array('name'=>$lang['nc_circle_memberlevel'], 'gp'=>null, 'gct'=>'circle_memberlevel'),
		array('name'=>$lang['nc_circle_classmanage'], 'gp'=>null, 'gct'=>'circle_class'),
		array('name'=>$lang['nc_circle_manage'], 'gp'=>null, 'gct'=>'circle_manage'),
		array('name'=>$lang['nc_circle_thememanage'], 'gp'=>null, 'gct'=>'circle_theme'),
		array('name'=>$lang['nc_circle_membermanage'], 'gp'=>null, 'gct'=>'circle_member'),
		array('name'=>$lang['nc_circle_informnamage'], 'gp'=>null, 'gct'=>'circle_inform'),
		array('name'=>$lang['nc_circle_advmanage'],'gp'=>'adv_manage', 'gct'=>'circle_setting')
		));
}*/


return $_limit;
