<?php
defined('GcWebShop') or exit('Access Invalid!');
/**
 * ，Export Language Pack, only in the implementation of the export action is invoked
 */

//
$lang['exp_brandid']		= 'IDbrand ID';
$lang['exp_brand']			= 'brand';
$lang['exp_brand_cate']		= 'category';
$lang['exp_brand_img']		= 'Logo';

//product
$lang['exp_product']		= 'product';
$lang['exp_pr_cate']		= 'classification';
$lang['exp_pr_brand']		= 'brand';
$lang['exp_pr_price']		= 'price';
$lang['exp_pr_serial']		= 'Item No.';
$lang['exp_pr_state']		= 'status';
$lang['exp_pr_type']		= 'type';
$lang['exp_pr_addtime']		= 'release date';
$lang['exp_pr_store']		= 'shop';
$lang['exp_pr_storeid']		= 'IDshop ID';
$lang['exp_pr_wgxj']		= 'uneshelve for violation';
$lang['exp_pr_sj']			= 'shelve';
$lang['exp_pr_xj']			= 'unshelve';
$lang['exp_pr_new']			= 'new';
$lang['exp_pr_old']			= 'secondhand';

//type
$lang['exp_type_name']		= 'type';

//specification
$lang['exp_spec']			= 'specification';
$lang['exp_sp_content']		= 'specification content';

//shop
$lang['exp_store']			= 'shop';
$lang['exp_st_name']		= 'owner account';
$lang['exp_st_sarea']		= 'location';
$lang['exp_st_grade']		= 'grade';
$lang['exp_st_adtime']		= 'set-up date';
$lang['exp_st_yxq']			= 'alidity period';
$lang['exp_st_state']		= 'state';
$lang['exp_st_xarea']		= 'detailed addrese ';
$lang['exp_st_post']		= 'postcoad';
$lang['exp_st_tel']			= 'contact number';
$lang['exp_st_kq']			= 'turn on';
$lang['exp_st_shz']			= 'auditing';
$lang['exp_st_close']		= 'turn off';

//membership
$lang['exp_member']			= 'member';
$lang['exp_mb_name']		= 'real name';
$lang['exp_mb_jf']			= 'integral';
$lang['exp_mb_yck']			= 'prepaid deposit';
$lang['exp_mb_jbs']			= 'checkgold';
$lang['exp_mb_sex']			= 'gender';
$lang['exp_mb_ww']			= 'AliTM';
$lang['exp_mb_dcs']			= 'login number';
$lang['exp_mb_rtime']		= 'register time';
$lang['exp_mb_ltime']		= 'last login';
$lang['exp_mb_storeid']		= 'shop ID';
$lang['exp_mb_nan']			= 'man';
$lang['exp_mb_nv']			= 'woman';

//integral details
$lang['exp_pi_member']		= 'membership';
$lang['exp_pi_system']		= 'manager';
$lang['exp_pi_point']		= 'integral value ';
$lang['exp_pi_time']		= 'time';
$lang['exp_pi_jd']			= 'opration ';
$lang['exp_pi_ms']			= 'description';
$lang['exp_pi_jfmx']		= 'pintegral detail';

//performance details
$lang['exp_sc_member']		= 'membership name';
$lang['exp_sc_system']		= 'manager';
$lang['exp_sc_point']		= 'rebate amount';
$lang['exp_sc_time']		= 'time';
$lang['exp_sc_jd']			= 'opration';
$lang['exp_sc_ms']			= 'description';
$lang['exp_sc_jfmx']		= 'membership performance details table';

//prepaid deposit charge
$lang['exp_yc_no']			= 'charge number';
$lang['exp_yc_member']		= 'membership name';
$lang['exp_yc_money']		= 'charge amount';
$lang['exp_yc_pay']			= 'payment mode';
$lang['exp_yc_ctime']		= 'establish time';
$lang['exp_yc_ptime']		= 'payment date';
$lang['exp_yc_paystate']	= 'payment state';
$lang['exp_yc_memberid']	= 'memberID';
$lang['exp_yc_yckcz']		= 'prepaid deposit charge';

//pre - deposit withdrawal
$lang['exp_tx_no']			= 'withdrawl number';
$lang['exp_tx_member']		= 'membership name';
$lang['exp_tx_money']		= 'withdrawal amount';
$lang['exp_tx_type']		= 'withdrawal mode';
$lang['exp_tx_ctime']		= 'application date';
$lang['exp_tx_state']		= 'withdrawal state';
$lang['exp_tx_memberid']	= 'membership ID';
$lang['exp_tx_title']		= 'pre - deposit withdrawal';

//prepaid deposit details
$lang['exp_mx_member']		= 'membership';
$lang['exp_mx_ctime']		= 'modify time';
$lang['exp_mx_money']		= 'amount';
$lang['exp_mx_av_money']	= 'available amount';
$lang['exp_mx_freeze_money']= 'blocked amount';
$lang['exp_mx_type']		= 'amount type';
$lang['exp_mx_system']		= 'manager';
$lang['exp_mx_stype']		= 'event type';
$lang['exp_mx_mshu']		= 'description';
$lang['exp_mx_rz']			= 'prepaid deposit modify logs';

//order
$lang['exp_od_no']			= 'order number';
$lang['exp_od_store']		= 'shop';
$lang['exp_od_buyer']		= 'buyer';
$lang['exp_od_xtimd']		= 'order time';
$lang['exp_od_count']		= 'order totall amount';
$lang['exp_od_yfei']		= 'freight';
$lang['exp_od_paytype']		= 'payment mode';
$lang['exp_od_state']		= 'order state';
$lang['exp_od_storeid']		= 'shop ID';
$lang['exp_od_selerid']		= 'sel ID';
$lang['exp_od_buyerid']		= 'buyer ID';
$lang['exp_od_bemail']		= 'buyer Email';
$lang['exp_od_sta_qx']		= 'canceled';
$lang['exp_od_sta_dfk']		= 'to be paid';
$lang['exp_od_sta_dqr']		= 'paid already,to be confirmed';
$lang['exp_od_sta_yfk']		= 'paid';
$lang['exp_od_sta_yfh']		= 'delivered';
$lang['exp_od_sta_yjs']		= 'cleared';
$lang['exp_od_sta_dsh']		= 'to be audited';
$lang['exp_od_sta_yqr']		= 'comfirmed';
$lang['exp_od_order']		= 'order';

//gold perchase record
$lang['exp_jbg_member']		= 'membership name';
$lang['exp_jbg_store']		= 'shop';
$lang['exp_jbg_jbs']		= 'gold perchase amount';
$lang['exp_jbg_money']		= 'amount required';
$lang['exp_jbg_gtime']		= 'perchase time';
$lang['exp_jbg_paytype']	= 'payment mode';
$lang['exp_jbg_paystate']	= 'payment state';
$lang['exp_jbg_storeid']	= 'shop ID';
$lang['exp_jbg_memberid']	= 'memberID';
$lang['exp_jbg_wpay']		= 'to be paid';
$lang['exp_jbg_ypay']		= 'paid';
$lang['exp_jbg_jbgm']		= 'pershase gold';

//gold logs
$lang['exp_jb_member']		= 'membership';
$lang['exp_jb_store']		= 'shop';
$lang['exp_jb_jbs']			= 'gold amount';
$lang['exp_jb_type']		= 'change type';
$lang['exp_jb_btime']		= 'change time';
$lang['exp_jb_mshu']		= 'description';
$lang['exp_jb_storeid']		= 'shop ID';
$lang['exp_jb_memberid']	= 'membership ID';
$lang['exp_jb_add']			= 'add';
$lang['exp_jb_del']			= 'minus';
$lang['exp_jb_log']			= 'gold logs';


?>