<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['nc_member_predepositmanage'];?></h3>
      <ul class="tab-base">
        <li><a href="index.php?gct=predeposit&gp=predeposit"><span><?php echo $lang['admin_predeposit_rechargelist']?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['admin_predeposit_cashmanage']; ?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="user_form"  method="post">
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label><?php echo $lang['admin_predeposit_cs_sn'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['info']['pdc_sn']; ?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label><?php echo $lang['admin_predeposit_membername'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['info']['pdc_member_name']; ?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label><?php echo $lang['admin_predeposit_cash_price'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['info']['pdc_amount']; ?>&nbsp;<?php echo $lang['currency_zh'];?></td>
          <td class="vatop tips"></td>
        </tr>
		
		<tr>
          <td colspan="2" class="required"><label><?php echo '实该支付';?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" name="actual" id="actual" value="<?php echo $output['info']['pdc_actual']; ?>"/></td>
          <td class="vatop tips"></td>
        </tr>
		
		<tr>
          <td colspan="2" class="required"><label><?php echo $lang['admin_predeposit_remark'];?>:</label></td>
        </tr>
        <tr class="noborder">
          
		  <td class="vatop rowform"><textarea name="remark" ><?php echo $output['info']['pdc_payment_admin'];?></textarea></td>
           
		  <td class="vatop tips"></td>
        </tr>
		
        <tr>
          <td colspan="2" class="required"><label><?php echo $lang['admin_predeposit_cash_shoukuanbank']; ?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['info']['pdc_bank_name']; ?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label><?php echo $lang['admin_predeposit_cash_shoukuanaccount'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['info']['pdc_bank_no']; ?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label><?php echo $lang['admin_predeposit_cash_shoukuanname']?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['info']['pdc_bank_user']; ?></td>
          <td class="vatop tips"></td>
        </tr>
        <?php if (intval($output['info']['pdc_payment_time'])) {?>
        <tr>
          <td colspan="2" class="required"><label><?php echo $lang['admin_predeposit_paytime']; ?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo @date('Y-m-d H:i:s',$output['info']['pdc_payment_time']); ?> 
          ( <?php echo $lang['admin_predeposit_adminname'];?>: <?php echo $output['info']['pdc_payment_admin'];?> ) </td>
          <td class="vatop tips"></td>
        </tr>
        <?php } ?>
      </tbody>
      <?php if (!intval($output['info']['pdc_payment_state'])) {?>
        <tfoot id="submit-holder">
        <tr class="tfoot">
        <td colspan="2">
        <!--<a class="btn" href="javascript:if (confirm('<?php echo $lang['admin_predeposit_cash_confirm'];?>')){window.location.href='index.php?gct=predeposit&gp=pd_cash_audit&id=<?php echo $output['info']['pdc_id']; ?>';}else{}"><span><?php echo $lang['admin_predeposit_payed'];?></span></a>-->
		
		<a class="btn"><span><?php echo $lang['admin_predeposit_cash_check'];?></span></a>
        </td>
        </tr>
        </tfoot>
     <?php } ?>
    </table>
  </form>
</div>
<script type="text/javascript">
$(function(){
	$('.btn').click(function(){
		$('#user_form').validate({
			errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
			},
			rules : {
				actual : {
					min: '0.01'
				}
			},
			messages : {
				actual: {
					min : '<?php echo '请输入实该支付金额！'?>'
				}
			}
		});
		var remark = $("textarea[name=remark]").val();
		var actual = $("input[name=actual]").val();
		if($("#user_form").valid()){
			$.ajax({
				url:'index.php?gct=predeposit&gp=pd_cash_audit&id='+<?php echo $output['info']['pdc_id'];?>,
				data:{remark:remark,actual:actual},
				type:"post",
				success:function(result){
					if(result.result =1){
						alert('提现信息修改成功');
						location.href='index.php?gct=predeposit&gp=pd_cash_list';
					}
				}
			})
		}
	});
	
});
</script>


