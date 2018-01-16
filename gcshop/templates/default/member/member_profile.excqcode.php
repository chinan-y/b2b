<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<div class="wrap">
	<div class="tabmenu">
	<?php include template('layout/submenu');?>
	</div>
	<div class="mycode-tag ">
	  <dl>
		<dt><i class="icon-tag"></i>基本信息</dt>
		 <div id="code" class="myexeqcode"></div>
		  <dd>平台用户名：<?php echo $output['member_info']['member_name']; ?></dd>
		  <dd>真实姓名：<?php echo $output['member_info']['member_truename']; ?></dd>
		  <dd>手机号码：<?php echo $output['member_info']['member_mobile']; ?></dd>
		  <dd>电子邮箱：<?php echo $output['member_info']['member_email']; ?></dd>
		  <?php if($output['member_info']['is_member_rebate']) { ?>
		  <dd>会员返利率：<?php echo $output['member_info']['member_rebate_rate']*100; ?>%</dd>
		  <?php } ?>
	  </dl>
	</div>
	<div class="clear"></div>
	<div class="mycode-tag ">
		<table class="ncm-search-table">
			<dl>
				<dt><i class="icon-tag"></i>我的收益</dt>
			</dl>
			<input type="hidden" name="gct" value="member_salescredit" />
			<tr>
				<td class="w10">&nbsp;</td>
				<td>
				<table>
					<tr>
						<td>
							<span>今日订单：</span><strong class="red"> <?php echo $output['achievement']['order_num']; ?>个</strong>
							<span>今日收益：</span><strong class="red"><?php echo $output['achievement']['income_today']; ?>元</strong>
							<span>累计收益：</span><strong class="red"><?php echo $output['member_info']['member_salescredit']; ?>元</strong>
							<span>可提现余额：</span><strong class="red"><?php echo $output['member_info']['available_predeposit']; ?>元</strong>
							<a href="<?php echo urlShop('member_security','auth',array('type'=>'pd_cash'));?>" class="pd_cash">去提现</a>
						</td>
					</tr>
				</table>
				</td>   
			</tr>
		</table>
		<div style="height:20px;"></div>
	</div>
	<div class="mycode-tag ">
		<table class="ncm-default-table">
			<dl>
				<dt><i class="icon-tag"></i>收益明细</dt>
			</dl>
			<thead>
				<tr>
					<th class="w150">时间</th>
					<th class="w100">金额(元)</th>
					<th class="w100">操作</th>
					<th class="w100">订单编号</th>
					<th class="">描述</th>
				</tr>
			</thead>
			<tbody>
				<?php   if (count($output['list_log'])>0) { ?>
				<?php foreach($output['list_log'] as $val) { ?>
				<tr class="bd-line">
					<td class="goods-time"><?php echo date('Y-m-d H:i:s',$val['sc_addtime']);?></td>
					<td class="goods-price"><?php echo ($val['sc_points'] > 0 ? '+' : '').ROUND($val['sc_points'],2); ?></td>
					<td><?php 
					switch ($val['sc_stage']){
						case 'order':
							echo '订单消费';
							break;
						case 'rebate':
							echo '推广销售';
							break;
						case 'system':
							echo '系统操作';
							break;
						case 'refund':
							echo '订单退款';
							break;
						case 'salerebate':
							echo '返利提现';
							break;
					}
					?></td>
					<td class=""><?php echo $val['sc_order_sn'];?></td>
					<td class=""><?php echo $val['sc_desc'];?></td>
				</tr>
				<?php } ?>
				<?php } else { ?>
				<tr class="no_data">
					<td colspan="11"><strong style="color:#360"><?php echo $lang['no_record']; ?></strong></td>
				</tr>
				<?php } ?>
			</tbody>
			<tfoot><tr><td colspan="20"></tr></tfoot>
		</table>
	</div>
	
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