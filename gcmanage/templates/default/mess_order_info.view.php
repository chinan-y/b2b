<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>通关查询</h3>
      <ul class="tab-base">
	    <li><a href="index.php?gct=mess_order_info&gp=index"><span>订单列表</span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>订单查看</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="mess_orderinfo_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="order_id" value="<?php echo $output['mess_order_list'][0]['ORDER_ID'];?>" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td class="required"><label >企业内部订单号:</label></td>
          <td ><label ><a href="index.php?gct=order&gp=show_order&order_id=<?php echo $output['mess_order_list'][0][ORDER_ID];?>" title="查看原始订单信息" target="_blank"><?php echo $output['mess_order_list'][0]['ORDER_SN'];?></a></label></td>
          <td class="required"><label >订单编号:</label></td>
          <td ><label ><?php echo $output['mess_order_list'][0]['PAY_SN'];?></label></td>
          <td class="required"><label >海关清单编号:</label></td>
          <td ><label ><?php echo $output['mess_order_list'][0]['list_invtNo'];?></label></td>
          <td class="required"><label >物流单号:</label></td>
          <td colspan="3"><label ><?php echo $output['mess_order_list'][0]['shipping_code'];?></label></td>
        </tr>
		<tr class="noborder">
          <td class="required"><label >订单金额:</label></td>
          <td ><label ><?php echo $output['mess_order_list'][0]['ORDER_AMOUNT'];?></label></td>
          <td class="required"><label >税金金额:</label></td>
          <td ><label ><?php echo $output['mess_order_list'][0]['taxes_total'];?></label></td>
          <td class="required"><label >优惠金额:</label></td>
          <td ><label ><?php echo $output['mess_order_list'][0]['discount_total'];?></label></td>
          <td class="required"><label >运费金额:</label></td>
          <td ><label ><?php echo $output['mess_order_list'][0]['shipping_total'];?></label></td>
          <td class="required"><label >保费金额:</label></td>
          <td ><label ><?php echo $output['mess_order_list'][0]['insurance_total'];?></label></td>
        </tr>
		<tr class="noborder">
          <td class="required"><label >订购人:</label></td>
          <td ><label ><?php echo $output['mess_order_list'][0]['RECEIVER_NAME'];?></label></td>
          <td class="required"><label >订购人身份证号码:</label></td>
          <td colspan="7"><label ><?php echo $output['mess_order_list'][0]['RECEIVER_ID_NO'];?></label></td>
        </tr>
		<tr class="noborder">
          <td class="required"><label >收货人信息:</label></td>
          <td ><label ><?php echo $output['receiver_info']['receiver_name'];?></label></td>
          <td ><label ><?php echo $output['receiver_info']['mob_phone'];?></label></td>
          <td ><label ><?php echo $output['receiver_info']['phone'];?></label></td>
          <td colspan="6"><label ><?php echo $output['receiver_info']['address'];?></label></td>
        </tr>
		<tr class="noborder">
			<th class="required">订单入库回执:</th>
			<td><?php if($output['mess_order_list'][0]['OI_SUCCESS']==1){echo "成功";} elseif($output['mess_order_list'][0]['OI_SUCCESS']==0){echo "失败";}?></td>
			<th class="required">订单入库回执信息:</th>
			<td><?php echo $output['mess_order_list'][0]['OI_MEMO'];?></td>
			<th class="required">订单回执状态:</th>
			<td><?php echo $output['mess_order_list'][0]['order_status'];?></td>
			<th class="required">订单回执信息:</th>
			<td><?php echo $output['mess_order_list'][0]['order_info'];?></td>
		</tr>
		<tr class="noborder">
			<th class="required">清单入库回执:</th>
			<td><?php if($output['mess_order_list'][0]['LI_SUCCESS']==1){echo "成功";} elseif($output['mess_order_list'][0]['LI_SUCCESS']==0){echo "失败";}?></td>
			<th class="required">清单入库回执信息:</th>
			<td><?php echo $output['mess_order_list'][0]['LI_MEMO'];?></td>
			<th class="required">清单回执状态:</th>
			<td><?php echo $output['mess_order_list'][0]['list_status'];?></td>
			<th class="required">清单回执信息:</th>
			<td><?php echo $output['mess_order_list'][0]['list_info'];?></td>
		</tr>
		<tr class="noborder">
			<th class="required">是否推送仓库:</th>
			<td><?php if($output['mess_order_list'][0]['MAKE_CSV']==10){echo "否";} elseif($output['mess_order_list'][0]['MAKE_CSV']==20){echo "是";}?></td>
			<th class="required">审批信息:</th>
			<td><?php echo $output['mess_order_list'][0]['OIF_MEMO'];?></td>
		    <th class="required">保税仓及时达出货信息:</th>
		  	<td><?php echo $output['mess_order_list'][0]['OIF_ORDER_NO'];?></td>
		    <th class="required">保税仓玛斯特出货状态码:</th>
		    <td colspan="3"><?php echo $output['mess_order_list'][0]['OIF_STATUS_CODE'];?></td>
		</tr>
		
		<TR>
		<TD colspan="10">
			 <table class="table tb-type2">
				 <tbody>
					<tr class="noborder">
					  <td class="required">序号</td>
					  <td class="required">HS编码</td>
					  <td class="required">商品SPEC</td>
					  <td class="required">商品ID</td>
					  <td class="required">商品备案编码</td>
					  <td class="required">商品名称</td>
					  <td class="required">税率</td>
					  <td class="required">数量</td>
					  <td class="required">商品金额</td>
					  <td class="required">原产国</td>
					</tr>
					<?php foreach($output['mess_order_list'] as $key => $value) {?>
					<tr class="noborder">
					  <td><?php echo $key+1; ?></td>
					  <td><?php echo $value['hs_code']; ?></td>
					  <td><a title="<?php echo $value['GOODS_SPEC']; ?>"><?php echo substr($value['GOODS_SPEC'],0,30); ?>……</a></td>
					  <td><?php echo $value['GOODS_ID']; ?></td>
					  <td><?php echo $value['SKU']; ?></td>
					  <td><?php echo $value['goods_name']; ?></td>
					  <td><?php echo $value['GOODS_RATE'] *100; ?>%</td>
					  <td><?php echo intval($value['GOODS_NUM']); ?></td>
					  <td><?php echo $value['goods_total']; ?></td>
					  <td><?php echo $value['country_name']; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			 </table>

		</TD>
		</TR>
		<tr class="noborder">
		    <th class="required">推送仓库备注信息:</th>
		    <td colspan="9">
			<?php if($output['mess_order_list'][0]['MAKE_CSV']==10){ ?>
			<input type="text" value="<?php echo $output['mess_order_list'][0]['ERP_MEMO'];?>" name="noteinfo" id="noteinfo" class="txt2">
			<?php } ?>
			</td>
		</tr>

	  </tbody>
      <tfoot id="submit-holder">
        <tr class="tfoot">
		<?php if($output['mess_order_list'][0]['MAKE_CSV']==10){ ?>
          <td colspan="15"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
		<?php } ?>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#mess_orderinfo_form").valid()){
     $("#mess_orderinfo_form").submit();
	}
	});
});
</script>