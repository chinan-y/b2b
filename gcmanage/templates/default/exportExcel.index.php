<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>导出报表</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span>首页</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  
  	<form method="post" action="index.php" id="form_login">
	    <input type="hidden" name="gct" value="exportExcel" />
		<input type="hidden" name="gp" value="index" />
		<div style="width:400px; float:left; margin:50px 50px;">
			<input type="hidden" name="action" value="order_list_new">
			<div style="width:300px; margin:10px 0 ;color:#e6387f; font-weight:600;">订单流水表</div>
			<input type="text" name="start_time" value="<?php echo date('Y-m').'-01';?>" placeholder="请输入开始日期" style="height:30px;">
			<input type="text" name="end_time" value="<?php echo date('Y-m-d');?>" placeholder="请输入结束日期" style="height:30px;">
			<div style="width:200px; height:40px; line-height:40px;">日期格式：<?php echo date('Y-m-d', time()); ?></div>
			<div class="input"><input type="submit" value="导出订单流水表" class="login-submit"></div>
		</div>	
	</form>
	<form method="post" action="index.php" id="form_login">
	    <input type="hidden" name="gct" value="exportExcel" />
		<input type="hidden" name="gp" value="index" />
		<div style="width:400px; float:left; margin:50px 50px;">
			<input type="hidden" name="action" value="order_list_goods">
			<div style="width:300px; margin:10px 0 ;color:#e6387f; font-weight:600;">订单商品流水表</div>
			<input type="text" name="start_time" value="<?php echo date('Y-m').'-01';?>" placeholder="请输入开始日期" style="height:30px;">
			<input type="text" name="end_time" value="<?php echo date('Y-m-d');?>" placeholder="请输入结束日期" style="height:30px;">
			<div style="width:200px; height:40px; line-height:40px;">日期格式：<?php echo date('Y-m-d', time()); ?></div>
			<div class="input"><input type="submit" value="导出订单商品流水表" class="login-submit"></div>
		</div>	
	</form>
	<form method="post" action="index.php" id="form_login">
	    <input type="hidden" name="gct" value="exportExcel" />
		<input type="hidden" name="gp" value="index" />
		<div style="width:400px; float:left; margin:50px 50px;">
			<input type="hidden" name="action" value="member_num">
			<div style="width:300px; margin:10px 0; color:#e6387f; font-weight:600;">会员注册量</div>
			<input type="text" name="start_time" value="<?php echo date('Y-m').'-01';?>" placeholder="请输入开始日期" style="height:30px;">
			<input type="text" name="end_time" value="<?php echo date('Y-m-d');?>" placeholder="请输入结束日期" style="height:30px;">
			<div style="width:250px; height:40px; line-height:40px;">日期格式：<?php echo date('Y-m-d', time()); ?></div>
			<div class="input"><input type="submit" value="导出会员注册量表" class="login-submit"></div>
		</div>	
	</form>
	<form method="post" action="index.php" id="form_login">
	    <input type="hidden" name="gct" value="exportExcel" />
		<input type="hidden" name="gp" value="index" />
		<div style="width:400px; float:left; margin:50px 50px;">
			<input type="hidden" name="action" value="specified">
			<div style="width:300px; margin:10px 0; color:#e6387f; font-weight:600;">指定信息订单表</div>
			<input type="text" name="reciver_name" placeholder="请输入收货人" style="height:30px;"><em style="color:#e6387f;"> AND </em>
			<input type="text" name="reciver_address" placeholder="请输入收货地区" style="height:30px;">
			<div style="width:350px; height:40px; line-height:40px;">收货地区样例：广东 深圳市(中间空格)</div>
			<div class="input"><input type="submit" value="导出指定信息订单表" class="login-submit"></div>
		</div>	
	</form>
	<form method="post" action="index.php" id="form_login">
	    <input type="hidden" name="gct" value="exportExcel" />
		<input type="hidden" name="gp" value="index" />
		<div style="width:400px; float:left; margin:50px 50px;">
			<input type="hidden" name="action" value="goods_pricing">
			<div style="width:300px; margin:10px 0; color:#e6387f; font-weight:600;">产品定价表</div>
			<div style="height:70px; line-height:70px;">
			<input type="radio" value="1" name="shape">不含下架商品</input>
			</div>
			<div class="input"><input type="submit" value="导出产品定价表" class="login-submit"></div>
		</div>	
	</form>
	<form method="post" action="index.php" id="form_login">
	    <input type="hidden" name="gct" value="exportExcel" />
		<input type="hidden" name="gp" value="index" />
		<div style="width:400px; float:left; margin:50px 50px;">
			<input type="hidden" name="action" value="goods_storage">
			<div style="width:300px; margin:10px 0; color:#e6387f; font-weight:600;">商品库存表</div>
			<div style="height:70px; line-height:70px;">
			<input type="radio" value="1" name="shape">不含下架商品</input>
			</div>
			<div class="input"><input type="submit" value="导出商品库存表" class="login-submit"></div>
		</div>	
	</form>

  <!--<div style="text-align:right;"><a class="btns" target="_blank" href="index.php?<?php echo $_SERVER['QUERY_STRING'];?>&gp=export_step1"><span><?php echo $lang['nc_export'];?>Excel</span></a></div> -->

</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(function(){
    $('#query_start_time').datepicker({dateFormat: 'yy-mm-dd'});
    $('#query_end_time').datepicker({dateFormat: 'yy-mm-dd'});
    $('#ncsubmit').click(function(){
    	$('input[name="gp"]').val('index');$('#form_login').submit();
    });
    
  
    
});


</script> 
