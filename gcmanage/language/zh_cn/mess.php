<?php
defined('GcWebShop') or exit('Access Invalid!');
$lang['mess_api_unable']			= '系统未开海关接口功能';
$lang['mess_base_parameter']		= '基本参数设定';
$lang['mess_order']			= '订单管理';
$lang['mess_paysn']			= '支付单管理';
$lang['mess_bill']			= '运单管理';

$lang['mess_UserNo']			= '用户名';
$lang['mess_Password']			= '密码';
$lang['mess_ESHOP_ENT_NAME']	= '电商企业名称';
$lang['mess_APIURL']			= '接口地址';
$lang['mess_ReceiverId']			= '接收者ID';

$lang['mess_sku']					= '商品货号';
$lang['mess_goods_name']			= '商品名称';
$lang['mess_goods_spec']			= '规格型号';
$lang['mess_declare_unit']			= '申报计量单位';
$lang['mess_post_tax_no']			= '行邮税号';
$lang['mess_legal_unit']			= '法定计量单位';
$lang['mess_conv_legal_unit_num']			= '法定计量单位折算数量';
$lang['mess_hs_code']			= '商品HS编码';
$lang['mess_in_area_unit']			= '入区计量单位';
$lang['mess_conv_in_area_unit_num']			= '入区计量单位折算数量';
$lang['mess_is_experiment_goods']			= '是否试点商品';

$lang['mess_sku_name_no_null']			= '商品货号不能为空';
$lang['continue_add_mess_sku']		= '继续添加商品备案';
$lang['back_mess_sku_list']		= '返回商品备案列表';

$lang['continue_add_mess_order']		= '继续添加订单';
$lang['back_mess_order_list']		= '返回订单列表';

$lang['continue_add_mess_payment']		= '继续添加支付单';
$lang['back_mess_payment_list']		= '返回支付单列表';

$lang['mess_UserNo_notice']			= '电商在海关获得的十位编码';
$lang['mess_Password_notice']		= '密码';
$lang['mess_ESHOP_ENT_NAME_notice']	= '电商企业名称（在海关备案时的企业全称）';
$lang['mess_APIURL_notice']			= '报文发送接口地址';
$lang['mess_ReceiverId_notice']		= '此项固定值为 CQITC';

$lang['mess_skulist_help1']		= '列表右侧编辑按钮可查看详细信息及该商品在海关的备案情况';
$lang['del_mess_warning']  = '删除后将不能恢复，确认删除吗';
$lang['send_mess_warning']  = '请反复确认数据的正确性，送检报文发送后无法撤，确定要发送吗？';

$lang['api_customs_ebpCodeName']  		= '电商平台代码-ebpCode/电商平台名称-ebpName'; //新添加
$lang['api_customs_ebcCodeName']  		= '电商企业代码-ebcCode/电商企业名称-ebcName'; //新添加
$lang['api_customs_copCodeName']  		= '传输企业代码-copCode/传输企业名称-copName'; //新添加
$lang['api_customs_agentCodeName']  	= '申报企业代码-agentCode/申报企业名称-agentName'; //新添加
$lang['api_customs_logisticsCodeName']  = '物流企业代码-logisticsCode/物流企业名称-logisticsName'; //新添加
$lang['api_customs_areaCodeName_2']  	= '区内企业代码-areaCode/区内企业名称-areaName(及时达)'; //新添加
$lang['api_customs_areaCodeName_7']  	= '区内企业代码-areaCode/区内企业名称-areaName(玛斯特)'; //新添加
$lang['api_customs_pay_alipay']  		= '支付企业代码-payCode/支付企业名称-payName(支付宝)'; 
$lang['api_customs_pay_weixin']  		= '支付企业代码-payCode/支付企业名称-payName(微信)'; 
$lang['api_customs_orgCode']  			= '施检机构代码-orgCode(空港/西永)'; //新添加
$lang['api_customs_emsNo']  			= '账册编号-emsNo(及时达/玛斯特)'; 
$lang['api_customs_dxpId']  			= '报文传输编号-dxpId'; //新添加
$lang['api_customs_assureCode']  		= '担保企业编号-assureCode'; //新添加

$lang['api_customs_ebpCodeName_descr']  	= '电商平台在海关注册登记的编号/名称，以中国电子口岸发布的电商平台标识编号/名称为准'; //新添加
$lang['api_customs_ebcCodeName_descr']  	= '电商企业在海关注册登记的编号/名称'; //新添加
$lang['api_customs_copCodeName_descr']  	= '报文传输的企业代码/名称（需要与接入客户端的企业身份一致）'; //新添加
$lang['api_customs_agentCodeName_descr']  	= '申报单位在海关注册登记的编号/名称'; //新添加
$lang['api_customs_logisticsCodeName_descr']= '物流企业在海关注册登记的编号/名称'; //新添加
$lang['api_customs_areaCodeName_descr']  	= '区内仓储企业在海关注册登记的编号/名称，保税模式必填'; //新添加
$lang['api_customs_orgCode_descr']  		= '施检机构代码'; //新添加
$lang['api_customs_dxpId_descr']  			= '向中国电子口岸数据中心申请数据交换平台的用户编号'; //新添加
$lang['api_customs_assureCode_descr']  		= '担保扣税的企业海关注册登记编号'; //新添加
$lang['api_customs_emsNo_descr']  			= '填写区内仓储企业在海关备案的账册编号'; 
$lang['api_customs_pay_descr']  			= '支付企业的海关注册登记编号/名称'; 


