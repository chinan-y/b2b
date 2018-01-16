<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['dashboard_wel_system_info'];?><?php echo $lang['dashboard_wel_lase_login'].$lang['nc_colon'];?><?php echo $output['admin_info']['admin_login_time'];?></h3>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="info-panel">
    <dl class="member">
      <dt>
        <div class="ico"><i></i><sub title="<?php echo $lang['dashboard_wel_total_member'];?>"><span><em id="statistics_member"></em></span></sub></div>
        <h3><?php echo $lang['nc_member'];?></h3>
        <h5><?php echo $lang['dashboard_wel_member_des'];?></h5>
      </dt>
      <dd>
        <ul>
          <li class="w50pre normal"><a href="index.php?gct=member&gp=member"><?php echo $lang['dashboard_wel_new_add'];?><sub><em id="statistics_week_add_member"></em></sub></a></li>
          <li class="w50pre none"><a href="index.php?gct=predeposit&gp=pd_cash_list"><?php echo $lang['dashboard_wel_predeposit_get'];?><sub><em id="statistics_cashlist">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    <dl class="gcshop">
      <dt>
        <div class="ico"><i></i><sub title="<?php echo $lang['dashboard_wel_count_store_add'];?>"><span><em id="statistics_store"></em></span></sub></div>
        <h3><?php echo $lang['nc_store'];?></h3>
        <h5><?php echo $lang['dashboard_wel_store_des'];?></h5>
      </dt>
      <dd>
        <ul>
          <li class="w20pre none"><a href="index.php?gct=store&gp=store_joinin">开店审核<sub><em id="statistics_store_joinin">0</em></sub></a></li>
          <li class="w20pre none"><a href="index.php?gct=store&gp=store_bind_class_applay_list&state=0">类目申请<sub><em id="statistics_store_bind_class_applay">0</em></sub></a></li>
          <li class="w20pre none"><a href="index.php?gct=store&gp=reopen_list&re_state=1"><?php echo $lang['renew_application'];?><sub><em id="statistics_store_reopen_applay">0</em></sub></a></li>
          <li class="w20pre none"><a href="index.php?gct=store&gp=store&store_type=expired"><?php echo $lang['dashboard_wel_expired'];?><sub><em id="statistics_store_expired">0</em></sub></a></li>
          <li class="w20pre none"><a href="index.php?gct=store&gp=store&store_type=expire"><?php echo $lang['dashboard_wel_expire'];?><sub><em id="statistics_store_expire">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    <dl class="goods">
      <dt>
        <div class="ico"><i></i><sub title="<?php echo $lang['dashboard_wel_total_goods'];?>"><span><em id="statistics_goods"></em></span></sub></div>
        <h3><?php echo $lang['nc_goods'];?></h3>
        <h5><?php echo $lang['dashboard_wel_goods_des'];?></h5>
      </dt>
      <dd>
        <ul>
          <li class="w25pre normal"><a href="index.php?gct=goods&gp=goods"><?php echo $lang['dashboard_wel_new_add'];?><sub title="<?php echo $lang['dashboard_wel_count_goods'];?>"><em id="statistics_week_add_product"></em></sub></a></li>
          <li class="w25pre none"><a href="<?php echo urlAdmin('goods','goods',array('type'=>'waitverify', 'search_verify' => 10));?>">商品审核<sub><em id="statistics_product_verify">0</em></sub></a></li>
          <li class="w25pre none"><a href="index.php?gct=inform&gp=inform_list"><?php echo $lang['dashboard_wel_inform'];?><sub><em id="statistics_inform_list">0</em></sub></a></li>
          <li class="w25pre none"><a href="index.php?gct=brand&gp=brand_apply"><?php echo $lang['dashboard_wel_brnad_applay'];?><sub><em id="statistics_brand_apply">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    <dl class="trade">
      <dt>
        <div class="ico"><i></i><sub title="<?php echo $lang['dashboard_wel_total_order'];?>"><span><em id="statistics_order"></em></span></sub></div>
        <h3><?php echo $lang['nc_trade'];?></h3>
        <h5><?php echo $lang['dashboard_wel_trade_des'];?></h5>
      </dt>
      <dd>
        <ul>
          <li class="w18pre none"><a href="index.php?gct=refund&gp=refund_manage">退款<sub><em id="statistics_refund"></em></sub></a></li>
          <li class="w18pre none"><a href="index.php?gct=return&gp=return_manage">退货<sub><em id="statistics_return"></em></sub></a></li>
          <li class="w25pre none"><a href="index.php?gct=vr_refund&gp=refund_manage">虚拟订单退款<sub><em id="statistics_vr_refund"></em></sub></a></li>
          <li class="w18pre none"><a href="index.php?gct=complain&gp=complain_new_list"><?php echo $lang['dashboard_wel_complain'];?><sub><em id="statistics_complain_new_list">0</em></sub></a></li>
          <li class="w20pre none"><a href="index.php?gct=complain&gp=complain_handle_list"><?php echo $lang['dashboard_wel_complain_handle'];?><sub><em id="statistics_complain_handle_list">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    <dl class="operation">
      <dt>
        <div class="ico"><i></i></div>
        <h3><?php echo $lang['nc_operation'];?></h3>
        <h5><?php echo $lang['dashboard_wel_stat_des'];?></h5>
      </dt>
      <dd>
        <ul>
          <li class="w15pre none"><a href="index.php?gct=groupbuy&gp=groupbuy_verify_list"><?php echo $lang['dashboard_wel_groupbuy'];?><sub><em id="statistics_groupbuy_verify_list">0</em></sub></a></li>
          <li class="w17pre none"><a href="index.php?gct=pointorder&gp=pointorder_list&porderstate=waitship"><?php echo $lang['dashboard_wel_point_order'];?><sub><em id="statistics_points_order">0</em></sub></a></li>
          <li class="w17pre none"><a href="index.php?gct=bill&gp=show_statis&os_month=&query_store=&bill_state=2"><?php echo $lang['dashboard_wel_check_billno'];?><sub><em id="statistics_check_billno">0</em></sub></a></li>
          <li class="w17pre none"><a href="index.php?gct=bill&gp=show_statis&os_month=&query_store=&bill_state=3"><?php echo $lang['dashboard_wel_pay_billno'];?><sub><em id="statistics_pay_billno">0</em></sub></a></li>
          <li class="w17pre none"><a href="<?php echo urlAdmin('mall_consult', 'index');?>">平台客服<sub><em id="statistics_mall_consult">0</em></sub></a></li>
          <li class="w17pre none"><a href="<?php echo urlAdmin('delivery', 'index', array('sign' => 'verify'));?>">服务站<sub><em id="statistics_delivery_point">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>

    <?php if (C('cms_isuse') != null) {?>
    <dl class="cms">
      <dt>
        <div class="ico"><i></i></div>
        <h3>CMS</h3>
        <h5>资讯文章/图片画报/会员评论</h5>
      </dt>
      <dd>
        <ul>
          <li class="w33pre none"><a href="<?php echo urlAdmin('cms_article', 'cms_article_list_verify');?>">文章审核<sub><em id="statistics_cms_article_verify">0</em></sub></a></li>
          <li class="w33pre none"><a href="<?php echo urlAdmin('cms_picture', 'cms_picture_list_verify');?>">画报审核<sub><em id="statistics_cms_picture_verify">0</em></sub></a></li>
          <li class="w34pre none"><a href="<?php echo urlAdmin('cms_comment', 'comment_manage');?>">评论<sub></sub></a></li>
        </ul>
      </dd>
    </dl>
    <?php }?>
    <?php if (C('circle_isuse') != null) {?>
    <dl class="circle">
      <dt>
        <div class="ico"><i></i></div>
        <h3>圈子</h3>
        <h5>申请开通/圈内话题及举报</h5>
      </dt>
      <dd>
        <ul>
          <li class="w33pre none"><a href="<?php echo urlAdmin('circle_manage', 'circle_verify');?>">圈子申请<sub><em id="statistics_circle_verify">0</em></sub></a></li>
          <li class="w33pre none"><a href="<?php echo urlAdmin('circle_theme', 'theme_list');?>">话题</a></li>
          <li class="w34pre none"><a href="<?php echo urlAdmin('circle_inform', 'inform_list');?>">举报</a></li>
        </ul>
      </dd>
    </dl>
    <?php }?>
    <?php if (C('microshop_isuse') != null){?>
    <dl class="microshop">
      <dt>
        <div class="ico"><i></i></div>
        <h3>微商城</h3>
        <h5>随心看/个人秀/店铺街</h5>
      </dt>
      <dd>
        <ul>
          <li class="w33pre none"><a href="<?php echo urlAdmin('microshop', 'goods_manage');?>">随心看</a></li>
          <li class="w33pre none"><a href="<?php echo urlAdmin('circle_theme', 'theme_list');?>">个人秀</a></li>
          <li class="w34pre none"><a href="<?php echo urlAdmin('circle_inform', 'inform_list');?>">店铺街</a></li>
        </ul>
      </dd>
    </dl>
    <?php }?>
    <dl class="system">
      <dt>
        <div class="ico"><i></i></div>
        <h3><?php echo $lang['dashboard_welcome_sys_info'];?></h3>
        <div id="system-info">
          <ul>
            <li>多用户商城 <?php echo $lang['dashboard_welcome_version'];?><span>20150315</span></li>
            <li><?php echo $lang['dashboard_welcome_install_date'];?><span><?php echo $output['statistics']['setup_date'];?></span></li>
            <li><?php echo $lang['dashboard_welcome_server_os'];?><span><?php echo $output['statistics']['os'];?></span></li>
            <li>WEB <?php echo $lang['dashboard_welcome_server'];?><span><?php echo $output['statistics']['web_server'];?></span></li>
            <li>PHP <?php echo $lang['dashboard_welcome_version'];?><span><?php echo $output['statistics']['php_version'];?></span></li>
            <li>MYSQL <?php echo $lang['dashboard_welcome_version'];?><span><?php echo $output['statistics']['sql_version'];?></span></li>
          </ul>
        </div>
      </dt>
      <dd>
        <ul>
          <li class="w50pre none"><a href="#" target="_blank">官方网站<sub></sub></a></li>
          <li class="w50pre none"><a href="#" target="_blank">官方论坛<sub></sub></a></li>
        </ul>
      </dd>
    </dl>
	
	<dl class="system">
      <dt>
        <div class="ico"><img src="<?php echo ADMIN_SITE_URL;?>/templates/default/images/merchants_join.png"/></div>
        <h3><?php echo $lang['dashboard_wel_merchants_join'];?></h3>
        <h5>XXXXXX</h5>
      </dt>
      <dd>
        <ul>
		  <li class="w50pre none"><a href="index.php?gct=merchants&gp=index"><?php echo $lang['dashboard_wel_join_check'];?><sub><em id="statistics_league">0</em></sub></a></li>
          <li class="w50pre none"><a >XXXX<sub><em id="">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
	
    <div class="clear"></div>
    <div class="system-info"></div>
  </div>
</div>
<script type="text/javascript">
var normal = ['week_add_member','week_add_product'];
var work = ['store_joinin','store_bind_class_applay','store_reopen_applay','store_expired','store_expire','brand_apply','cashlist','groupbuy_verify_list','points_order','complain_new_list','complain_handle_list', 'product_verify','inform_list','refund','return','vr_refund','cms_article_verify','cms_picture_verify','circle_verify','check_billno','pay_billno','mall_consult','delivery_point','offline','league'];
$(document).ready(function(){
	$.getJSON("index.php?gct=dashboard&gp=statistics", function(data){
	  $.each(data, function(k,v){
		  $("#statistics_"+k).html(v);
		  if (v!= 0 && $.inArray(k,work) !== -1){
			$("#statistics_"+k).parent().parent().parent().removeClass('none').addClass('high');
		  }else if (v == 0 && $.inArray(k,normal) !== -1){
			$("#statistics_"+k).parent().parent().parent().removeClass('normal').addClass('none');
		  }
	  });
	});
	//自定义滚定条
	$('#system-info').perfectScrollbar();
});
</script>
