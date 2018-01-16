<?php defined('GcWebShop') or exit('Access Invalid!');?>

	<script>
		//$(function(){
			//$("body").width($(window).width());	//设置适应屏幕的宽度 
			//$("body").height($(window).height());
		//})
	</script>
	<style type="text/css">
		* {margin: 0;padding: 0;}
		.hide img{width: 100%;border: 0; background-repeat:}
	    .ap{width: 100%; top: 0%;position: relative;}
	    
	    .win{width:13%;margin-left:50.5%; margin-top: -23%;position: absolute;}
	    .an{width:13%;margin-left:66.5%; margin-top: -23%;position: absolute;}
		.hide{overflow-x:hidden;}  
		.public-head .site-logo{width:500px; height:200px; margin:0 auto; padding-top:20px;}
		.we-chat{width:10%;margin-left:66.8%; margin-top: -14%;position: absolute;}
		.app{width:10%;margin-left:52.8%; margin-top: -14%;position: absolute;}
		.kong{height:1px;background:#FFA8C9;}
	</style>

<div class="kong"></div>
	<div class="hide">
		<img src="<?php echo SHOP_TEMPLATES_URL;?>/home/public/APP1_01.jpg" class="ap"/>
		
		<div class="win zhe">
			<a href="https://itunes.apple.com/cn/app/guang-cai-quan-qiu-bao-shui/id1084204548?mt=8">
				<img src="<?php echo SHOP_TEMPLATES_URL;?>/home/public/APP1_05.jpg"/>
			</a>
		</div>
		
		<div class="an zhe">
			<a href="http://www.qqbsmall.com/gcshop/templates/default/home/xiazai/GuangCaiQuanQiuBaoShui_Android.apk">
				<img src="<?php echo SHOP_TEMPLATES_URL;?>/home/public/APP1_06.jpg"/>
			</a>
		</div>
		
		<div class="we-chat"><img src="<?php echo SHOP_TEMPLATES_URL;?>/home/public/we-chat.jpg"/></div>
		
		<div class="app"><img src="<?php echo SHOP_TEMPLATES_URL;?>/home/public/app.jpg"/></div>
		
		<img src="<?php echo SHOP_TEMPLATES_URL;?>/home/public/APP1_03.png" class="nan"/>
		<img src="<?php echo SHOP_TEMPLATES_URL;?>/home/public/APP1_02.png" class="nan1"/>
		<img src="<?php echo SHOP_TEMPLATES_URL;?>/home/public/APP1_04.png" class="nan2"/>
		
	
	</div>

<script type="text/javascript">

	$(".zhe").hover(function() {
		$(this).fadeTo("show", 0.5);
	},
	function() {
		$(this).fadeTo("show", 1);
	})
	
</script>

