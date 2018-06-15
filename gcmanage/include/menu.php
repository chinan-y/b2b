<?php
/**
 * 菜单
 *
 
 */
defined('GcWebShop') or exit('Access Invalid!');
/**
 * top 数组是顶部菜单 ，left数组是左侧菜单
 * left数组中'args'=>'welcome,dashboard,dashboard',三个分别为op,gct,nav，权限依据act来判断
 */
$arr = array(
		'top' => array(
			0 => array(
				'args' 	=> 'dashboard',
				'text' 	=> $lang['nc_console']),
			1 => array(
				'args' 	=> 'setting',
				'text' 	=> $lang['nc_config']),
			2 => array(
				'args' 	=> 'goods',
				'text' 	=> $lang['nc_goods']),
			3 => array(
				'args' 	=> 'store',
				'text' 	=> $lang['nc_store']),
			4 => array(
				'args'	=> 'member',
				'text'	=> $lang['nc_member']),
			5 => array(
				'args' 	=> 'trade',
				'text'	=> $lang['nc_trade']),
			6 => array(
				'args'	=> 'website',
				'text' 	=> $lang['nc_website']),
			7 => array(
				'args'	=> 'operation',
				'text'	=> $lang['nc_operation']),
			8 => array(
				'args'	=> 'stat',
				'text'	=> $lang['nc_stat']),
			/*9 =>array(
				'args'  => 'live_modifly',
				'text'  => $lang['nc_live']),
			10 =>array(
				'args'  => 'distribution',
				'text'  => '分销'),
			11 =>array(
				'args'  => 'aliyunoss',
				'text'  => '对象存储'),*/
			
		),
		'left' =>array(
			0 => array(
				'nav' => 'dashboard',
				'text' => $lang['nc_normal_handle'],
				'list' => array(
					array('args'=>'welcome,dashboard,dashboard',			'text'=>$lang['nc_welcome_page']),
					array('args'=>'aboutus,dashboard,dashboard',			'text'=>$lang['nc_aboutus']),
					array('args'=>'base,setting,dashboard',					'text'=>$lang['nc_web_set']),
					array('args'=>'member,member,dashboard',				'text'=>$lang['nc_member_manage']),
					array('args'=>'store,store,dashboard',					'text'=>$lang['nc_store_manage']),
					array('args'=>'goods,goods,dashboard',					'text'=>$lang['nc_goods_manage']),
					array('args'=>'index,order,dashboard',			        'text'=>$lang['nc_order_manage']),
				)
			),
			1 => array(
				'nav' => 'setting',
				'text' => $lang['nc_config'],
				'list' => array(
					array('args'=>'base,setting,setting',			'text'=>$lang['nc_web_set']),
					// array('args'=>'qq,account,setting',		        'text'=>$lang['nc_web_account_syn']),
					array('args'=>'param,upload,setting',			'text'=>$lang['nc_upload_set']),
					array('args'=>'seo,setting,setting',			'text'=>$lang['nc_seo_set']),
					array('args'=>'email,message,setting',			'text'=>$lang['nc_message_set']),
					array('args'=>'system,payment,setting',			'text'=>$lang['nc_pay_method']),
					array('args'=>'admin,admin,setting',			'text'=>$lang['nc_limit_manage']),
					array('args'=>'index,express,setting',			'text'=>$lang['nc_admin_express_set']),
					array('args'=>'waybill_list,waybill,setting', 	'text'=>$lang['nc_waybill_template']),
					array('args'=>'index,offpay_area,setting',		'text'=>$lang['nc_admin_offpay_area_set']),
					array('args'=>'clear,cache,setting',			'text'=>$lang['nc_admin_clear_cache']),
					array('args'=>'db,db,setting',					'text'=>$lang['nc_data_backup']),
					array('args'=>'perform,perform,setting',		'text'=>$lang['nc_admin_perform_opt']),
					array('args'=>'search,search,setting',			'text'=>$lang['nc_admin_search_set']),
					array('args'=>'list,admin_log,setting',			'text'=>$lang['nc_admin_log']),
					array('args'=>'qrcode,custom_qrcode,setting',	'text'=>$lang['nc_custom_qrcode']),
				)
			),
			2 => array(
				'nav' => 'goods',
				'text' => $lang['nc_goods'],
				'list' => array(
					array('args'=>'goods_class,goods_class,goods',			'text'=>$lang['nc_class_manage']),
					array('args'=>'brand,brand,goods',						'text'=>$lang['nc_brand_manage']),
					array('args'=>'goods,goods,goods',						'text'=>$lang['nc_goods_manage']),
					array('args'=>'type,type,goods',						'text'=>$lang['nc_type_manage']),
					array('args'=>'spec,spec,goods',						'text'=>$lang['nc_spec_manage']),
					array('args'=>'list,goods_album,goods',					'text'=>$lang['nc_album_manage']),
					array('args'=>'list,goods_address,goods',				'text'=>$lang['nc_goods_warehouse']),
					array('args'=>'lists,source,goods',			            'text'=>$lang['nc_goods_source']),
				    array('args'=>'list,supplier,goods',				    'text'=>$lang['nc_goods_supplier']),
					array('args'=>'per_goods,per_goods,goods',				'text'=>$lang['nc_per_manage']),
					array('args'=>'goods_validity,goods_validity,goods',	'text'=>$lang['nc_validate_manage']),
				)
			),
			3 => array(
				'nav' => 'store',
				'text' => $lang['nc_store'],
				'list' => array(
					array('args'=>'store,store,store',						'text'=>$lang['nc_store_manage']),
					array('args'=>'store_grade,store_grade,store',			'text'=>$lang['nc_store_grade']),
					array('args'=>'store_class,store_class,store',			'text'=>$lang['nc_store_class']),
					array('args'=>'store_domain_setting,domain,store',		'text'=>$lang['nc_domain_manage']),
					array('args'=>'stracelist,sns_strace,store',			'text'=>$lang['nc_s_snstrace']),
					array('args'=>'help_store,help_store,store',			'text'=>$lang['nc_openstore_help']),
					array('args'=>'edit_info,store_joinin,store',			'text'=>$lang['nc_store_index']),
					array('args'=>'list,ownshop,store',						'text'=>$lang['nc_self_store']),
				)
			),
			4 => array(
				'nav' => 'member',
				'text' => $lang['nc_member'],
				'list' => array(
					array('args'=>'member,member,member',					'text'=>$lang['nc_member_manage']),
					array('args'=>'index,member_grade,member',				'text'=>$lang['nc_member_level']),
					array('args'=>'index,exppoints,member',					'text'=>$lang['nc_exppoints_manage']),
					array('args'=>'notice,notice,member',					'text'=>$lang['nc_member_notice']),
					array('args'=>'addpoints,points,member',				'text'=>$lang['nc_member_pointsmanage']),
					array('args'=>'predeposit,predeposit,member',			'text'=>$lang['nc_member_predepositmanage']),
					array('args'=>'index,alipay_batch,member',				'text'=>$lang['nc_cash_transfei']),
					array('args'=>'sharesetting,sns_sharesetting,member',	'text'=>$lang['nc_binding_manage']),
					array('args'=>'class_list,sns_malbum,member',			'text'=>$lang['nc_member_album_manage']),
					array('args'=>'tracelist,snstrace,member',				'text'=>$lang['nc_snstrace']),
					array('args'=>'member_tag,sns_member,member',			'text'=>$lang['nc_member_tag']),
					array('args'=>'chat_log,chat_log,member',				'text'=>$lang['nc_chat_record']),
					array('args'=>'member,member_batch,member',				'text'=>$lang['nc_member_group']),
				)
			),
			5 => array(
				'nav' => 'trade',
				'text' => $lang['nc_trade'],
				'list' => array(
					array('args'=>'index,order,trade',				        'text'=>$lang['nc_order_manage']),
					array('args'=>'index,vr_order,trade',				    'text'=>$lang['nc_vr_order']),
					array('args'=>'refund_manage,refund,trade',				'text'=>$lang['nc_refund_manage1']),
					array('args'=>'return_manage,return,trade',				'text'=>$lang['nc_refund_manage2']),
					array('args'=>'refund_manage,vr_refund,trade',		    'text'=>$lang['nc_vrorder_refund']),
					array('args'=>'consulting,consulting,trade',			'text'=>$lang['nc_consult_manage']),
					array('args'=>'inform_list,inform,trade',				'text'=>$lang['nc_inform_config']),
					array('args'=>'evalgoods_list,evaluate,trade',			'text'=>$lang['nc_goods_evaluate']),
					array('args'=>'complain_new_list,complain,trade',		'text'=>$lang['nc_complain_config']),
				)
			),
			6 => array(
				'nav' => 'website',
				'text' => $lang['nc_website'],
				'list' => array(
					array('args'=>'article_class,article_class,website',	'text'=>$lang['nc_article_class']),
					array('args'=>'article,article,website',				'text'=>$lang['nc_article_manage']),
					array('args'=>'document,document,website',				'text'=>$lang['nc_document']),
					array('args'=>'navigation,navigation,website',			'text'=>$lang['nc_navigation']),
					array('args'=>'ap_manage,adv,website',					'text'=>$lang['nc_adv_manage']),
					array('args'=>'web_config,web_config,website',			'text'=>$lang['nc_web_index']),
					array('args'=>'rec_list,rec_position,website',			'text'=>$lang['nc_admin_res_position']),
					array('args'=>'link,link,website',						'text'=>$lang['nc_link']),
				)
			),
			7 => array(
				'nav' => 'operation',
				'text' => $lang['nc_operation'],
				'list' => array(
					array('args'=>'setting,operation,operation',			    'text'=>$lang['nc_operation_set']),
					array('args'=>'groupbuy_template_list,groupbuy,operation',	'text'=>$lang['nc_groupbuy_manage']),
                    array('args'=>'index,vr_groupbuy,operation',                'text'=>$lang['nc_vrbuying_set']),
                    array('args'=>'index,wxpack,operation',                     'text'=>$lang['nc_mpweixin_redbagset']),
					array('args'=>'xianshi_apply,promotion_xianshi,operation',	'text'=>$lang['nc_promotion_xianshi']),
					array('args'=>'mansong_apply,promotion_mansong,operation',	'text'=>$lang['nc_promotion_mansong']),
					array('args'=>'bundling_list,promotion_bundling,operation',	'text'=>$lang['nc_promotion_bundling']),
					array('args'=>'goods_list,promotion_booth,operation',		'text'=>$lang['nc_promotion_booth']),
					array('args'=>'voucher_apply,voucher,operation',            'text'=>$lang['nc_voucher_price_manage']),
					array('args'=>'index,bill,operation',					    'text'=>$lang['nc_bill_manage']),
					array('args'=>'index,vr_bill,operation',					'text'=>$lang['nc_vrorder_settlement']),
					array('args'=>'activity,activity,operation',				'text'=>$lang['nc_activity_manage']),
					array('args'=>'pointprod,pointprod,operation',				'text'=>$lang['nc_pointprod']),
					array('args'=>'index,mall_consult,operation',               'text'=>$lang['nc_mall_services']),
                    array('args'=>'index,rechargecard,operation',               'text'=>$lang['nc_mall_rechargecard']),
                    array('args'=>'index,delivery,operation',                   'text'=>$lang['nc_logistics_station']),
				)
			),
			8 => array(
				'nav' => 'stat',
				'text' => $lang['nc_stat'],
				'list' => array(
			        array('args'=>'general,stat_general,stat',			'text'=>$lang['nc_statgeneral']),
					array('args'=>'scale,stat_industry,stat',			'text'=>$lang['nc_statindustry']),
			        array('args'=>'newmember,stat_member,stat',			'text'=>$lang['nc_statmember']),
					array('args'=>'newstore,stat_store,stat',			'text'=>$lang['nc_statstore']),
					array('args'=>'income,stat_trade,stat',				'text'=>$lang['nc_stattrade']),
					array('args'=>'pricerange,stat_goods,stat',			'text'=>$lang['nc_statgoods']),
					array('args'=>'promotion,stat_marketing,stat',		'text'=>$lang['nc_statmarketing']),
					array('args'=>'refund,stat_aftersale,stat',			'text'=>$lang['nc_stataftersale']),
					array('args'=>'index,exportexcel,stat',				'text'=>'导出报表'),

				)
			),
			9 =>array(
				'nav'  => 'live_modifly',
				'text' => $lang['nc_live'],
				'list' => array(
					array('args'=>'live_modifly,live,live_modifly',			'text'=>$lang['nc_live1']),
					array('args'=>'live_manage,live,live_modifly',			'text'=>$lang['nc_live2']),
					array('args'=>'live_class_a,live,live_modifly',			'text'=>$lang['nc_live3']),
					array('args'=>'on_demand,live,live_modifly',			'text'=>$lang['nc_live4']),
					
				)
			),
			10 => array(
				'nav' => 'distribution',
				'text' => '分销',
				'list' => array(
					array('args'=>'member,erwei_member,distribution',			'text'=>$lang['nc_saleman_manage']),
					array('args'=>'index,salescredit,distribution',				'text'=>$lang['nc_sale_achievement']),
					array('args'=>'index,partner,distribution',                 'text'=>$lang['nc_cooper_platform']),
					array('args'=>'index,platform_order,distribution',			'text'=>$lang['nc_uparea_manage']),
					array('args'=>'add,importorder,distribution',				'text'=>'订单导入管理'),
					array('args'=>'index,thirdface,distribution',				'text'=>$lang['nc_interface_order']),
					array('args'=>'sales_area,sales_area,distribution',			'text'=>$lang['nc_saleteam_manage']),
					array('args'=>'index,order_salearea,distribution',			'text'=>$lang['nc_sale_general_area']),
					array('args'=>'index,merchants,distribution',               'text'=>$lang['nc_business_join']),
				)
			),
			11 => array(
				'nav' => 'aliyunoss',
				'text' => '对象存储',
				'list' => array(
					array('args'=>'index,aliyunset,aliyunoss',			'text'=>'阿里云参数'),
					array('args'=>'index,oss_aliyun,aliyunoss',			'text'=>'对象存储OSS'),

				)
			),
		),
);

//开启 海关2.0+对接管理菜单
/*if(C('mess_isuse') == 1){
	$arr['top'][] = array(
				'args'	=> 'mess',
				'text'	=> $lang['nc_custom_api']);
	$arr['left'][] = array(
				'nav' => 'mess',
				'text' => $lang['nc_custom_api'],
				'list' => array(
					0 => array('args'=>'mess,mess,mess',					'text'=>$lang['nc_custom_basic_set']),
					1 => array('args'=>'mess_sku,mess_sku,mess',			'text'=>$lang['nc_custom_goods_audit']),
					2 => array('args'=>'mess_order,mess_order,mess',		'text'=>$lang['nc_custom_order_manage']),
					3 => array('args'=>'mess_payment,mess_payment,mess',	'text'=>$lang['nc_custom_payment_manage']),
					4 => array('args'=>'mess_bill,mess_bill,mess',			'text'=>$lang['nc_custom_transbill_manage']),
					5 => array('args'=>'index,mess_order_info,mess',		'text'=>'通关查询'),
					6 => array('args'=>'index,tax_rate,mess',				'text'=>'税率查询'),
				)
			);
}

if(C('flea_isuse') == 1){
	$arr['top'][] = array(
				'args'	=> 'flea',
				'text'	=> $lang['nc_flea']);
	$arr['left'][] = array(
				'nav' => 'flea',
				'text' => $lang['nc_flea'],
				'list' => array(
					0 => array('args'=>'flea_index,flea_index,flea',			'text'=>$lang['nc_flea_seoset']),
					1 => array('args'=>'flea_class,flea_class,flea',			'text'=>$lang['nc_flea_class_manage']),
					2 => array('args'=>'flea_class_index,flea_class_index,flea','text'=>$lang['nc_flea_index_class']),
					3 => array('args'=>'flea,flea,flea',						'text'=>$lang['nc_flea_manage']),
					4 => array('args'=>'flea_region,flea_region,flea',			'text'=>$lang['nc_flea_area_manage']),
					5 => array('args'=>'adv_manage,flea_index,flea',			'text'=>$lang['nc_flea_powerpoint']),
				)
			);
}

if(C('mobile_isuse')){
	$arr['top'][] = array(
				'args'	=> 'mobile',
				'text'	=> $lang['nc_mobile']);
	$arr['left'][] = array(
				'nav' => 'mobile',
				'text' => $lang['nc_mobile'],
				'list' => array(
					array('args'=>'index_edit,mb_special,mobile',				'text'=>$lang['nc_index_edit']),
					array('args'=>'special_list,mb_special,mobile',				'text'=>$lang['nc_special_set']),
					array('args'=>'mb_category_list,mb_category,mobile',		'text'=>$lang['nc_mobile_catepic']),
					array('args'=>'mb_app,mb_app,mobile',						'text'=>$lang['nc_download_set']),
                    array('args'=>'flist,mb_feedback,mobile',					'text'=>$lang['nc_mobile_feedback']),
					array('args'=>'mb_payment,mb_payment,mobile',				'text'=>$lang['nc_mobile_payment']),
				)
			);
}

if(C('microshop_isuse')  == 1){
	$arr['top'][] = array(
				'args'	=> 'microshop',
				'text'	=> $lang['nc_microshop']);
	$arr['left'][] = array(
				'nav' => 'microshop',
				'text' => $lang['nc_microshop'],
				'list' => array(
					0 => array('args'=>'manage,microshop,microshop','text'=>$lang['nc_microshop_manage']),
					1 => array('args'=>'goods_manage,microshop,microshop','text'=>$lang['nc_microshop_goods_manage']),
					2 => array('args'=>'goodsclass_list,microshop,microshop','text'=>$lang['nc_microshop_goods_class']),
					3 => array('args'=>'personal_manage,microshop,microshop','text'=>$lang['nc_microshop_personal_manage']),
					4 => array('args'=>'personalclass_list,microshop,microshop','text'=>$lang['nc_microshop_personal_class']),
					5 => array('args'=>'store_manage,microshop,microshop','text'=>$lang['nc_microshop_store_manage']),
					6 => array('args'=>'comment_manage,microshop,microshop','text'=>$lang['nc_microshop_comment_manage']),
					7 => array('args'=>'adv_manage,microshop,microshop','text'=>$lang['nc_microshop_adv_manage']),
				)
			);
}

if(C('cms_isuse') == 1){
	$arr['top'][] = array(
				'args'	=> 'cms',
				'text'	=> $lang['nc_cms']);
	$arr['left'][] = array(
				'nav' => 'cms',
				'text' => $lang['nc_cms'],
				'list' => array(
					0 => array('args'=>'cms_manage,cms_manage,cms','text'=>$lang['nc_cms_manage']),
                    1 => array('args'=>'cms_index,cms_index,cms','text'=>$lang['nc_cms_index_manage']),
                    2 => array('args'=>'cms_article_list,cms_article,cms','text'=>$lang['nc_cms_article_manage']),
                    3 => array('args'=>'cms_article_class_list,cms_article_class,cms','text'=>$lang['nc_cms_article_class']),
                    4 => array('args'=>'cms_picture_list,cms_picture,cms','text'=>$lang['nc_cms_picture_manage']),
                    5 => array('args'=>'cms_picture_class_list,cms_picture_class,cms','text'=>$lang['nc_cms_picture_class']),
                    6 => array('args'=>'cms_special_list,cms_special,cms','text'=>$lang['nc_cms_special_manage']),
                    7 => array('args'=>'cms_navigation_list,cms_navigation,cms','text'=>$lang['nc_cms_navigation_manage']),
                    8 => array('args'=>'cms_tag_list,cms_tag,cms','text'=>$lang['nc_cms_tag_manage']),
                    9 => array('args'=>'comment_manage,cms_comment,cms','text'=>$lang['nc_cms_comment_manage']),
				)
			);
}

if(C('circle_isuse') == 1){
	$arr['top'][] = array(
			'args'	=> 'circle',
			'text'	=> $lang['nc_circle']);
	$arr['left'][] = array(
			'nav'	=> 'circle',
			'text'	=> $lang['nc_circle'],
			'list'	=> array(
					0 => array('args'=>'index,circle_setting,circle','text'=>$lang['nc_circle_setting']),
					1 => array('args'=>'index,circle_memberlevel,circle','text'=>$lang['nc_circle_memberlevel']),
					2 => array('args'=>'class_list,circle_class,circle','text'=>$lang['nc_circle_classmanage']),
					3 => array('args'=>'circle_list,circle_manage,circle','text'=>$lang['nc_circle_manage']),
					4 => array('args'=>'theme_list,circle_theme,circle','text'=>$lang['nc_circle_thememanage']),
					5 => array('args'=>'member_list,circle_member,circle','text'=>$lang['nc_circle_membermanage']),
					6 => array('args'=>'inform_list,circle_inform,circle','text'=>$lang['nc_circle_informnamage']),
					7 => array('args'=>'adv_manage,circle_setting,circle','text'=>$lang['nc_circle_advmanage']),
					8 => array('args'=>'index,circle_cache,circle','text'=>$lang['nc_circle_cache'])
			)
	);
}*/

return $arr;
?>
