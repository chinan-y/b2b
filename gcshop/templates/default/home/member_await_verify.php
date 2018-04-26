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
</style>
<div class="nc-login-layout">
   <div class="nc-login">
	<div class="nc-login-title">
      <h3><?php echo '等待审核';?></h3>
    </div>
    <div class="nc-login-content">
      <div class="await_verify">资料已提交，请等待管理员审核</div>
    </div>
	<div class="nc-login-bottom"></div>
  </div>
  <div class="nc-login-left">
    <div class="leftpic"><img src="<?php echo $output['lpic'];?>"  border="0"></div>
    </div>
</div>
  
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script> 
