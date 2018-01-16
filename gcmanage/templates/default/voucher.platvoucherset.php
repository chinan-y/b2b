<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <!-- 页面导航 -->
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['nc_voucher_price_manage'];?></h3>
      <ul class="tab-base">
        <?php   foreach($output['menu'] as $menu) {  if($menu['menu_key'] == $output['menu_key']) { ?>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $menu['menu_name'];?></span></a></li>
        <?php }  else { ?>
        <li><a href="<?php echo $menu['menu_url'];?>" ><span><?php echo $menu['menu_name'];?></span></a></li>
        <?php  } }  ?>
      </ul>
    </div>
  </div>
  
  <div class="fixed-empty"></div>
   <form id="add_form" method="post" action="index.php?gct=voucher&gp=platvoucherset">
    <input type="hidden" id="form_submit" name="form_submit" value="ok"/>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td  class="required"><label class="validation">平台通用代金券名称</label></td>
          <td class="vatop rowform">
				<input type="text" id="voucher_t_title" name="voucher_t_title" class="txt">
          </td>
          <td class="vatop tips">光彩全球网（www.qqbsmall.com）平台通用代金券，所有店铺的商品均可使用通用代金券抵扣，属于平台优惠活动，店铺代金卷为店铺商家优惠活动。</td>
        </tr>
        <tr class="noborder">
          <td class="required"><label class="validation">代金券有效使用天数</label></td>
            <td class="vatop rowform">
                <input type="text" id="voucher_t_end_date" name="voucher_t_end_date" class="txt" value="" >
            </td>
            <td class="vatop tips">天</td>
        </tr>
        <tr class="noborder">
          <td  class="required"><label class="validation">代金券面额</label></td>
            <td class="vatop rowform">
			<!--select id="voucher_t_price" name="voucher_t_price" class="w80 vt">
	          <?php if(!empty($output['pricelist'])) { ?>
	          	<?php foreach($output['pricelist'] as $voucher_price) {?>
	          	<option value="<?php echo $voucher_price['voucher_price'];?>" <?php echo $output['t_info']['voucher_t_price'] == $voucher_price['voucher_price']?'selected':'';?>><?php echo $voucher_price['voucher_price'];?></option>
	          <?php } } ?>
	        </select-->
			<input type="text" id="voucher_t_price" name="voucher_t_price" value="" class="txt">
            </td>
            <td class="vatop tips">元</td>
        </tr>
		<tr class="noborder">
          <td  class="required"><label class="validation">代金券兑换积分</label></td>
            <td class="vatop rowform">
                <input type="text" id="voucher_t_points" name="voucher_t_points" value="" class="txt">
            </td>
            <td class="vatop tips">分</td>
        </tr>
		<tr class="noborder">
          <td  class="required"><label class="validation">可发放代金券总数量</label></td>
            <td class="vatop rowform">
                <input type="text" id="voucher_t_total" name="voucher_t_total" value="" class="txt">
            </td>
            <td class="vatop tips">张</td>
        </tr>
		<tr class="noborder">
          <td  class="required"><label class="validation">每人限额</label></td>
            <td class="vatop rowform">
	      	<select name="voucher_t_eachlimit" class="w80">
	      		<option value="0">限领张数</option>
	      		<?php for($i=1;$i<=intval(C('promotion_voucher_buyertimes_limit'));$i++){?>
	      		<option value="<?php echo $i;?>" <?php echo $output['t_info']['voucher_t_eachlimit'] == $i?'selected':'';?>><?php echo $i;?></option>
	      		<?php }?>
	        </select>
            </td>
            <td class="vatop tips">张</td>
        </tr>
		<tr class="noborder">
          <td  class="required"><label class="validation">最低消费金额</label></td>
            <td class="vatop rowform">
                <input type="text" id="voucher_t_limit" name="voucher_t_limit" value="" class="txt">
            </td>
            <td class="vatop tips">元</td>
        </tr>
		<tr class="noborder">
          <td  class="required"><label class="validation">代金券详细描述</label></td>
            <td class="vatop rowform">
                <input type="text" id="voucher_t_desc" name="voucher_t_desc" value="平台通用代金券" class="txt">
            </td>
            <td class="vatop tips">代金券详细描述</td>
        </tr>
		<tr class="noborder">
          <td  class="required"><label class="validation">指定商品ID</label></td>
            <td class="vatop rowform">
                <input type="text" id="voucher_t_sku" name="voucher_t_sku" value="0" class="txt">
            </td>
            <td class="vatop tips">指定使用该代金券的商品SKU，填写商品ID，默认为0，为不指定具体商品。</td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
$(document).ready(function(){    
    $("#submitBtn").click(function(){
        $("#add_form").submit();
    });
    //页面输入内容验证
	$("#add_form").validate({
		errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },

        rules : {
        	promotion_voucher_price: {
                required : true,
                digits : true,
                min : 1
            },
            promotion_voucher_storetimes_limit: {
                required : true,
                digits : true,
                min : 1
            },
            promotion_voucher_buyertimes_limit: {
                required : true,
                digits : true,
                min : 1
            }
        },
        messages : {
        	promotion_voucher_price: {
       			required : '<?php echo $lang['admin_voucher_setting_price_error'];?>',
       			digits : '<?php echo $lang['admin_voucher_setting_price_error'];?>',
                min : '<?php echo $lang['admin_voucher_setting_price_error'];?>'
	    	},
	    	promotion_voucher_storetimes_limit: {
                required : '<?php echo $lang['admin_voucher_setting_storetimes_error'];?>',
                digits : '<?php echo $lang['admin_voucher_setting_storetimes_error'];?>',
                min : '<?php echo $lang['admin_voucher_setting_storetimes_error'];?>'
            },
            promotion_voucher_buyertimes_limit: {
                required : '<?php echo $lang['admin_voucher_setting_buyertimes_error'];?>',
                digits : '<?php echo $lang['admin_voucher_setting_buyertimes_error'];?>',
                min : '<?php echo $lang['admin_voucher_setting_buyertimes_error'];?>'
            }
        }
	});
});
</script>

