<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
		  <h3><?php echo '提现转账';?></h3>
		  
		</div>
	</div>
	<div class="fixed-empty"></div>
	<table class="table tb-type2" id="prompt">
    <tbody>
		<tr class="space odd">
		<th colspan="12"><div class="title">
			<h5><?php echo $lang['nc_prompts'];?></h5>
			<span class="arrow"></span></div>
		</th>
		</tr>
		<tr>
		<td>
			<ul>
				<li><?php echo '已审核的提现单，点击确认转账跳转到支付宝转账界面进行转账';?></li>
				<li style="color:red;"><?php echo '手机转账和其他方式转账成功的，点击更改状态按钮更改支付状态（转账前请看清，不要点错了）';?></li>
			</ul>
		</td>
		</tr>
	</tbody>
	</table>
	<table class="table tb-type2 nobdb">
		<thead>
		  <tr class="thead">
			<th><?php echo $lang['admin_predeposit_cs_sn'];?></th>
			<th><?php echo $lang['admin_predeposit_membername'];?></th>
			<th class="align-center"><?php echo $lang['admin_predeposit_apptime'];?></th>
			<th class="align-center"><?php echo '支付宝账号'; ?></th>
			<th class="align-center"><?php echo '真实姓名'; ?></th>
			<th class="align-center"><?php echo '申请金额'; ?>(<?php echo $lang['currency_zh']; ?>)</th>
			<th class="align-center"><?php echo '审核金额'; ?>(<?php echo $lang['currency_zh']; ?>)</th>
			<th class="align-center"><?php echo $lang['admin_predeposit_paystate']; ?></th>
			<th class="align-center" colspan="2"><?php echo '操作'; ?></th>
		  </tr>
		</thead>
		<tbody>
		  <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
			  <?php foreach($output['list'] as $k => $v){?>
			  <tr class="hover">
				<td><?php echo $v['pdc_sn']; ?></td>
				<td><?php echo $v['pdc_member_name']; ?></td>
				<td class="nowrap align-center"><?php echo @date('Y-m-d H:i:s',$v['pdc_add_time']);?></td>
				<td class="align-center"><?php echo $v['pdc_bank_no'];?></td>
				<td class="align-center"><?php echo $v['pdc_bank_user'];?></td>
				<td class="align-center"><?php echo $v['pdc_amount'];?></td>
				<td class="align-center"><?php echo $v['pdc_actual'];?></td>
				<td class="align-center"><?php echo str_replace(array('0','1','2'), array('未支付','已支付','已审核'), $v['pdc_payment_state']); ?></td>
				
				<form name=alipayment action="index.php?gct=alipay_batch&gp=batch" method=post target="_blank">
					<input type="hidden" name="serial_num" value="<?php echo $v['pdc_sn'];?>"/>
					<input type="hidden" name="account" value="<?php echo $v['pdc_bank_no'];?>"/>
					<input type="hidden" name="name" value="<?php echo $v['pdc_bank_user'];?>"/>
					<input type="hidden" name="amount" value="<?php echo $v['pdc_actual'];?>"/>
					<td class="align-center">
						<button class="new-btn-login" type="submit" style="text-align:center;">确认转账</button>
					</td>
				</form>
				<td class="align-center">
					<a href="index.php?gct=alipay_batch&gp=payment_state&pdc_id=<?php echo $v['pdc_id'];?>&pdc_sn=<?php echo $v['pdc_sn'];?>&amount=<?php echo $v['pdc_actual'];?>&member_id=<?php echo $v['pdc_member_id'];?>&name=<?php echo $v['pdc_member_name'];?>&admin=<?php echo $v['pdc_payment_admin'];?>" class="state">更改状态</a>
				</td>
			  </tr>
			  <tr>
				<td colspan="16" style="border:0px;">审核备注：<?php echo $v['pdc_payment_admin'];?></td>
			  </tr>
			  <?php } ?>
		  <?php }else { ?>
			  <tr class="no_data">
				<td colspan="10"><?php echo $lang['nc_no_record'];?></td>
			  </tr>
		  <?php } ?>
		</tbody>
		<tfoot>
		  <tr>
			<td colspan="16" id="dataFuncs"><div class="pagination"> <?php echo $output['show_page'];?> </div></td>
		  </tr>
		</tfoot>
	</table>
</div>

