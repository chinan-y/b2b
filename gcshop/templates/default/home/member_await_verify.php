<?php defined('GcWebShop') or exit('Access Invalid!');?>
<style type="text/css">
.public-top-layout, .head-app, .head-search-bar, .head-user-menu, .public-nav-layout, .nch-breadcrumb-layout, #faq {
	display: none !important;
}
.public-head-layout {
	margin: 10px auto -10px auto;
}
.wrapper {
	width: 1000px;
}
#footer {
	border-top: none!important;
	padding-top: 30px;
}
.nc-login-content{height:290px;}
.await_verify{text-align: center;line-height: 238px;font-size: 16px;color: #666;}
.disagree{text-align:center;line-height: 110px;font-size: 16px;color: #666;}
.disagree_content{text-align:center;font-size:13px;color: #666;height:100px;}
.disagree_a{text-align: right;margin: 20px;}
.disagree_a a{padding:5px 8px;background:#e6387f;color:#fff;border-radius:2px;font-size:14px;}
</style>
<div class="nc-login-layout">
   <div class="nc-login">
	<div class="nc-login-title">
      <h3><?php echo '认证审核';?></h3>
    </div>
    <div class="nc-login-content">
	<?php if($output['member_info']['member_examine'] == 0){ ?>
		<div class="await_verify">资料已提交，请等待管理员审核</div>
	<?php }else if($output['member_info']['member_examine'] == 2){ ?>
		<div class="disagree">审核不通过</div>
		<div class="disagree_content"><?php echo $output['member_info']['disagree_content']?></div>
		<div class="disagree_a"><a href="index.php?gct=login&gp=member_verify"><?php echo '重新提交'?></a></div>
	<?php }else{ ?>
		<div class="await_verify">审核成功</div>
	<?php } ?>
      
    </div>
	<div class="nc-login-bottom"></div>
  </div>
  <div class="nc-login-left">
    <div class="leftpic"><img src="<?php echo $output['lpic'];?>"  border="0"></div>
    </div>
</div>
  
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script> 
