<?php
defined('GcWebShop') or exit('Access Invalid!');
/**
 * index
 */
$lang['member_index_is_admin']		= '该会员是管理员,不能删除';
$lang['member_index_manage']		= '会员管理';
$lang['member_index_name']			= '会员';
$lang['member_index_mobile']		= '手机号码';
$lang['member_index_email']			= '电子邮箱';
$lang['member_index_true_name']		= '真实姓名';
$lang['member_index_reg_time']		= '注册时间';
$lang['member_index_last_login']	= '最后登录';
$lang['member_index_login_time']	= '登录次数';
$lang['member_index_im']			= '即时通讯';
$lang['member_index_if_admin']		= '是否是管理员';
$lang['member_index_set_admin']		= '设为管理员';
$lang['member_index_store']			= '店铺';
$lang['member_index_to_store']		= '访问店铺';
$lang['member_index_edit_store']		= '编辑店铺';
$lang['member_index_to_message']	= '通知';
$lang['member_index_points']		= '积分';
$lang['member_index_inform']		= '举报商品';
$lang['member_index_prestore']		= '预存款';
$lang['member_index_available']	    = '可用';
$lang['member_index_frozen']		= '冻结';
$lang['member_index_help1']			= '通过会员管理，你可以进行查看、编辑会员资料等操作';
$lang['member_index_help2']			= '你可以根据条件搜索会员，然后选择相应的操作';
$lang['member_index_null']	    	= '会员尚未填写此项信息';
$lang['member_index_state']	    	= '会员状态';
$lang['member_index_inform_deny']	= '禁止举报';
$lang['member_index_buy_deny']		= '禁止购买';
$lang['member_index_talk_deny']		= '禁止发表言论';
$lang['member_index_login_deny']	= '禁止登录';
$lang['member_index_login']			= '登录';
$lang['member_index_source']		= '来源';
/**
 * 编辑
 */
$lang['member_edit_valid_email']	= '请您填写有效的电子邮箱';
$lang['member_edit_valid_mobile']	= '請您填寫有效的手机号码';
$lang['member_edit_mobile_null']		= '手机号码不能為空';
$lang['member_edit_mobile_exists']	= '手机号码有重复，請您換一個';
$lang['member_edit_back_to_list']	= '返回会员列表';
$lang['member_edit_again']			= '重新编辑该会员';
$lang['member_edit_succ']			= '编辑会员成功';
$lang['member_edit_fail']			= '编辑会员失败';
$lang['member_edit_password']		= '密码';
$lang['member_edit_password_keep']	= '留空表示不修改密码';
$lang['member_edit_password_tip']	= '密码长度应在6-20个字符之间';
$lang['member_edit_email_null']		= '电子邮箱不能为空';
$lang['member_edit_email_exists']	= '邮件地址有重复，请您换一个';
$lang['member_edit_support']		= '支持格式';
$lang['member_edit_sex']			= '性别';
$lang['member_edit_secret']			= '保密';
$lang['member_edit_male']			= '男';
$lang['member_edit_female']			= '女';
$lang['member_edit_allow']			= '允许';
$lang['member_edit_deny']			= '禁止';
$lang['member_edit_pic']			= '头像';
$lang['member_edit_is_member_use_adjustrate']= '是否对销售员使用商品调节参数'; //新添加
$lang['member_edit_use']			= '使用'; //新添加
$lang['member_edit_nonuse']			= '不使用'; //新添加
$lang['member_edit_qq_wrong']       = '请输入正确的QQ号码';
$lang['member_edit_wangwang']		= '阿里旺旺';
$lang['member_edit_allowbuy']		= '允许购买商品';
$lang['member_edit_allowbuy_tip']	= '如果禁止该项则会员不能在前台进行下单操作';
$lang['member_edit_allowtalk']		= '允许发表言论';
$lang['member_edit_allowtalk_tip']	= '如果禁止该项则会员不能发表咨询和发送站内信';
$lang['member_edit_allowlogin']		= '允许登录';
$lang['member_edit_is_seller']		= '是否开启专属二维码';
$lang['member_edit_is_manager']		= '设置为区域经理';
$lang['member_edit_is_seller_tip']	= '开启后，其它用户通过扫描该销售人员专属二维码进行购物后，该销售人员将得到相应销售提成';
$lang['member_edit_is_manager_tip']	= '设置为区域经理后，用户可以个人后台管理下属的销售团队';
$lang['member_edit_sale_area']		= '所属销售区域';
/**
 * 会员添加
 */
$lang['member_add_back_to_list']	= '返回会员列表';
$lang['member_add_again']			= '继续新增会员';
$lang['member_add_succ']			= '新增会员成功';
$lang['member_add_fail']			= '新增会员失败';
$lang['member_add_name_exists']		= '会员名有重复，请您换一个';
$lang['member_add_name_null']		= '会员名不能为空';
$lang['member_add_name_length']     = '用户名必须在3-20字符之间';

/**
 * 业绩功能公用
 */
$lang['admin_salescredit_unavailable']	 		= '系统未开启销售返利功能';
$lang['admin_salescredit_mod_tip']				= '修改业绩';
$lang['admin_salescredit_system_desc']			= '管理员手动操作业绩';
$lang['admin_salescredit_userrecord_error']		= '会员信息错误，或该会员不是销售人员';
$lang['admin_salescredit_membername']			= '会员名称';
$lang['admin_salescredit_operatetype']			= '增减类型';
$lang['admin_salescredit_operatetype_add']		= '增加';
$lang['admin_salescredit_operatetype_reduce']	= '减少';
$lang['admin_salescredit_pointsnum']			= '返利金额';
$lang['admin_salescredit_pointsdesc']			= '描述';
$lang['admin_salescredit_pointsdesc_notice']		= '描述信息将显示在业绩明细相关页，会员和管理员都可见';
