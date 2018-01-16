<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/index.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/home_index.js" charset="utf-8"></script>
<meta property="wb:webmaster" content="20cb05e29d2017bf" />
<meta property="qc:admins" content="16554704576112351446375" />
<!--[if IE 6]>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ie6.js" charset="utf-8"></script>
<![endif]-->
<script type="text/javascript">
var uid = window.location.href.split("#V3");
var  fragment = uid[1];
if(fragment){
	if (fragment.indexOf("V3") == 0) {document.cookie='uid=0';}
else {document.cookie='uid='+uid[1];}
	}

</script>
<style type="text/css">
	.gao{width: 190px; margin-left: 2.5%;  position: absolute;}
	.spers{margin-left: 45px;}
	.ta{width: 190px;  position: absolute; margin-left: -12%;}
	#ads{width:175px;
    height:225px;
    position:fixed;
    right:8%;
    bottom:30%;
    _position:absolute;
    _top:expression(documentElement.scrollTop + documentElement.clientHeight-this.offsetHeight); 
}
	.us{width: 70%;}
	.pai{width: 45%; float: left; margin-left: 5%; margin-top: 5%;}
  .pai :hover {margin-left: -1px;}
<style type="text/css">
.category { display: block !important; }
</style>
<div class="clear"></div>

<!-- HomeFocusLayout Begin-->
<div class="home-focus-layout">
  <?php echo $output['web_html']['index_pic'];?>
</div>
<!--HomeFocusLayout End-->

<div class="wrapper">
  <div class="mt30">
    <div class="mt30"><?php echo loadadv(11,'html');?></div>
  </div>
</div>
<!--StandardLayout Begin--> 
<?php echo $output['web_html']['index'];?>
<!--StandardLayout End-->
<div class="wrapper" >
  <div class="mt30"><?php echo loadadv(9,'html');?></div>
  <div class="mt10">
    	<div class="showclass_title">分类导购</div>
        	<div class="showclass">
            	<table width="1200" border="0" cellspacing="0" cellpadding="0">
				  <tbody>
					<tr>
					  <td width="590"><?php echo loadadv(1047);?></td>
					  <td width="20">&nbsp;</td>
					  <td width="590"><?php echo loadadv(1048);?></td>
					</tr>
					<tr>
					  <td width="590" height="20">&nbsp;</td>
					  <td width="20">&nbsp;</td>
					  <td width="590">&nbsp;</td>
					</tr>
					<tr>
					  <td width="590"><?php echo loadadv(1049);?></td>
					  <td width="20">&nbsp;</td>
					  <td width="590"><?php echo loadadv(1050);?></td>
					</tr>
					<tr>
					  <td width="590" height="20">&nbsp;</td>
					  <td width="20">&nbsp;</td>
					  <td width="590">&nbsp;</td>
					</tr>
					<tr>
					  <td width="590"><?php echo loadadv(1051);?></td>
					  <td width="20">&nbsp;</td>
					  <td width="590"><?php echo loadadv(1052);?></td>
					</tr>
					<tr>
					  <td width="590" height="20">&nbsp;</td>
					  <td>&nbsp;</td>
					  <td width="590">&nbsp;</td>
					</tr>
					<tr>
					  <td width="590"><?php echo loadadv(1053);?></td>
					  <td>&nbsp;</td>
					  <td width="590"><?php echo loadadv(1054);?></td>
					</tr>
					<tr>
					  <td width="590" height="20">&nbsp;</td>
					  <td>&nbsp;</td>
					  <td width="590">&nbsp;</td>
					</tr>
					<tr>
					  <td width="590"><?php echo loadadv(1055);?></td>
					  <td>&nbsp;</td>
					  <td width="590"><?php echo loadadv(1056);?></td>
					</tr>
					<div style="height:10px; line-height:10px;"></div>
				
					</tbody>
					
				</table>
				</div>
				
				 <div class="mt30 showclass_rec"><?php echo loadadv(11,'html');?></div>
				 
				 <div>
                    <div class="showclass_title mt10">推荐商品</div>
                        <table width="1200" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td width="800">
								<div class="showclass_rec">
                              	<div class="mb20"><?php echo loadadv(1058); ?></div>
                                <div class="mb20"><?php echo loadadv(1059); ?></div>
                                <div class="mb20"><?php echo loadadv(1060); ?></div>
                                <div class="mb20"><?php echo loadadv(1061); ?></div>
                                <div class="mb20"><?php echo loadadv(1062); ?></div>
                                <div class="mb20"><?php echo loadadv(1063); ?></div>
                                <div class="mb20"><?php echo loadadv(1064); ?></div>
                                <div class="mb20"><?php echo loadadv(1065); ?></div>
                                <div class="mb20"><?php echo loadadv(1066); ?></div>
                                <div class="mb20"><?php echo loadadv(1067); ?></div>
                                <div class="mb20"><?php echo loadadv(1068); ?></div>
                                <div class="mb20"><?php echo loadadv(1069); ?></div>
                                <div class="mb20"><?php echo loadadv(1070); ?></div>
                                <div class="mb20"><?php echo loadadv(1071); ?></div>
                                <div class="mb20"><?php echo loadadv(1072); ?></div>
                                <div class="mb20"><?php echo loadadv(1073); ?></div>
                                <div class="mb20"><?php echo loadadv(1074); ?></div>
                                <div class="mb20"><?php echo loadadv(1075); ?></div>
                                <div class="mb20"><?php echo loadadv(1076); ?></div>
                                <div class="mb20"><?php echo loadadv(1077); ?></div>
								</div>
                              </td>
                              <td width="20">&nbsp;</td>
                              <td width="380" valign="top" class="showclass_brand">
									<!-- <?php echo loadadv(1078);?> -->

									<?php if (!empty($output['show_goods_class']) && is_array($output['show_goods_class'])) { $i = 0; ?>
									  <?php foreach ($output['show_goods_class'] as $key => $val) {  ?>

										  <?php if (!empty($val['class3']) && is_array($val['class3'])) { ?>
										  <?php foreach ($val['class3'] as $k => $v) { ?>
										  
										  <?php } ?>
										  <?php } ?>

										  <?php if (!empty($val['class2']) && is_array($val['class2'])) { ?>
										  <?php foreach ($val['class2'] as $k => $v) { ?>
										  
											  <?php if (!empty($v['class3']) && is_array($v['class3'])) { ?>
											  <?php foreach ($v['class3'] as $k3 => $v3) { ?>
											  
											  <?php } ?>
											  <?php } ?>

											  <?php if (!empty($v['brands']) && is_array($v['brands'])) { $n = 0; ?>

											  <?php foreach ($v['brands'] as $k3 => $v3) {
												if ($n++ < 10) {
												?>

												<div class="pai">
												<a href="<?php echo urlShop('brand','list',array('brand'=> $v3['brand_id'])); ?>"><img src="http://www.qqbsmall.com/data/upload/gcshop/brand/<?php echo $v3['brand_pic'];?>" /></a>
												</div>
												

											  <?php } ?>
											  <?php } ?>

											  <?php } ?>

											  <?php } ?>
										  <?php } ?>

										  
									  <?php } ?>
									  <?php } ?>

							  </td>
                            </tr>
                          </tbody>
 			             </table>


                      <div id=ads>
                    	
						<a href="http://www.qqbsmall.com/gcshop/integral.html">
						<img class="us" src="/gcshop/templates/default/images/7.png"/></a>
						<img class="us" src="/gcshop/templates/default/images/6.png"/>
					</div>
    </div>
    </div>
</div>
<script type="text/javascript">
$("#ads").hide();
$(window).scroll(function() {
	// 当滚动到最底部以上100像素时， 加载新内容

	if ($(document).height() - $(this).scrollTop() - $(this).height() < 10000) {
		$("#ads").show();
	}
	if ($(document).height() - $(this).scrollTop() - $(this).height() > 9300) {
		$("#ads").hide();
	}

	if ($(document).height() - $(this).scrollTop() - $(this).height() < 500) {
		$("#ads").hide();

	}

	//           if ($(document).height() - $(this).scrollTop() - $(this).height() < 10500) {
	////				
	//				$("#ads").show();
	//          }
});</script>


<div class="footer-line"></div>
<!--首页底部保障开始-->
<?php require_once template('layout/index_ensure');?>
<!--首页底部保障结束-->
<!--StandardLayout Begin-->
<!--<div class="nav_Sidebar">
 <a class="nav_Sidebar_1" href="javascript:;" ></a>
<a class="nav_Sidebar_2" href="javascript:;" ></a>
<a class="nav_Sidebar_3" href="javascript:;" ></a>
<a class="nav_Sidebar_4" href="javascript:;" ></a>
<a class="nav_Sidebar_5" href="javascript:;" ></a>
<a class="nav_Sidebar_6" href="javascript:;" ></a> 
<a class="nav_Sidebar_7" href="javascript:;" ></a>
<a class="nav_Sidebar_8" href="javascript:;" ></a>
</div> -->
<!--StandardLayout End-->

