<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<style>
	.mycode-tag li{list-style:none;margin-left:20px;color:#555;}
	.award-div{margin:30px 0 0 20px;}
	#submit-input{display:inline;margin-left:20px;border-width: 2px;border-style: outset;border-color: buttonface;}
	.award{width:80px; margin-right:50px;font-size:14px !important;}
</style>
<div class="wrap">
	<div class="tabmenu">
	<?php include template('layout/submenu');?>
	</div>
	<div class="mycode-tag ">
	  <dl>
		<dt><i class="icon-tag"></i>基本信息</dt>
		 <div id="code" class="myexeqcode"></div>
		 <?php if($output['partnerinfo']['partner_id']) {?>
		  <dd>平台合作方：<?php echo $output['partnerinfo']['partner_name']; ?>[<?php echo $output['partnerinfo']['partner_id']; ?>]</dd>
		  <dd>创建时间：<?php echo date('Y-m-d H:i:s',$output['partnerinfo']['add_time']); ?></dd>
		 <?php } ?>
		 <?php if($output['saleareainfo']['sa_id']) {?>
		  <dd>区域合作方：<?php echo $output['saleareainfo']['sa_name']; ?></dd>
		  <dd>授权区域：<?php echo $output['saleareainfo']['sa_areaname']; ?>(授权时间：<?php echo date('Y-m-d',$output['saleareainfo']['agent_start_time']); ?>~<?php echo date('Y-m-d',$output['saleareainfo']['agent_end_time']); ?>)</dd>
		  <dd>提成比例：<?php echo $output['saleareainfo']['sa_cash'] *100; ?>%&nbsp;&nbsp;保证金：<?php echo $output['saleareainfo']['sa_cash']; ?></dd>
		  <dd>创建时间：<?php echo date('Y-m-d H:i:s',$output['saleareainfo']['add_time']); ?></dd>
		 <?php } ?>
		  <dd>绑定用户名：<?php echo $output['memberinfo']['member_name']; ?></dd>
		  <dd>真实姓名：<?php echo $output['memberinfo']['member_truename']; ?></dd>
		  <dd>手机号码：<?php echo $output['memberinfo']['member_mobile']; ?></dd>
		  <?php if($output['memberinfo']['is_member_rebate']) { ?>
		  <dd>团队返利率：<?php echo $output['memberinfo']['member_rebate_rate']*100; ?>%</dd>
		  <?php } ?>
	  </dl>
	</div>
	<div class="clear"></div>
	<form method="post" action="index.php?gct=member_information&gp=award_rate_edit">
	<div class="mycode-tag">
		<table class="ncm-search-table">
			<dl>
				<dt><i class="icon-tag"></i>奖励分配下级用户</dt>
			</dl>
			<li>1、团队管理者将自己的收益作为奖励重新分配至自己的团队成员，产生的收益为三级成员下的单线分支用户订单；</li>
			<li>2、分配至团队顶端的一、二、三级，其中一级为团队管理者；</li>
			<li>3、团队管理者自行统一设置第二级和第三级成员的奖励比例；</li>
			<li>4、三级的总分配比例为现团队管理者固定收益的100%；</li>
			<li>5、输入0.3表示奖励团队管理者收益的30%,除去二级奖励和三级奖励后，剩下的就是团队管理者的收益；</li>
			<li>6、在用户订单付款后，分配收益直接进入三级成员用户各自的平台账户个人余额，余额依然通过提现获取收益。</li>
			<div class="award-div">
				二级奖励：<input type="text" name="one_award" value="<?php echo $output['partnerinfo']['one_award']; ?>" class="award"/>
				三级奖励：<input type="text" name="two_award" value="<?php echo $output['partnerinfo']['two_award']; ?>" class="award"/>
				<input type="submit"  value="提交" id="submit-input"/>
			</div>
		</table>
		<div style="height:20px;"></div>
	</div>
	</form>	
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<script language="javascript">
$(function(){
	$('#stime').datepicker({dateFormat: 'yy-mm-dd'});
	$('#etime').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/jquery.qrcode.min.js" ></script> 
<script>
$(function(){
	var str = "https://www.qqbsmall.com/wap/index.html?ref=<?php echo($_SESSION['member_id']) ?>";
	$('#code').qrcode(str);

	$("#sub_btn").click(function(){
		$("#code").empty();
		var str = toUtf8($("#mytxt").val());
		
		$("#code").qrcode({
			render: "table",
			width: 200,
			height:200,
			text: str
		});
	});
})
function toUtf8(str) {   
    var out, i, len, c;   
    out = "";   
    len = str.length;   
    for(i = 0; i < len; i++) {   
    	c = str.charCodeAt(i);   
    	if ((c >= 0x0001) && (c <= 0x007F)) {   
        	out += str.charAt(i);   
    	} else if (c > 0x07FF) {   
        	out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));   
        	out += String.fromCharCode(0x80 | ((c >>  6) & 0x3F));   
        	out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));   
    	} else {   
        	out += String.fromCharCode(0xC0 | ((c >>  6) & 0x1F));   
        	out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));   
    	}   
    }   
    return out;   
} 
</script> 