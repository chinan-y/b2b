<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>商品税率</h3>
      <ul class="tab-base">
		<li><a href="index.php?gct=tax_rate&gp=index" ><span>订单列表</span></a></li>
        <li><a href="index.php?gct=tax_rate&gp=add" ><span><?php echo $lang['nc_new'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_edit'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
<form method="post" id="tax_rate_form" >
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="hsid" value="<?php echo $output['taxrate_array']['id']; ?>" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="hs_code" class="validation" >海关编码（HScode）:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="hs_code" name="hs_code" value="<?php echo $output['taxrate_array']['hs_code']; ?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">10位数字海关商品编码</span></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="hs_name" class="validation" >海关商品名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="hs_name" name="hs_name" value="<?php echo $output['taxrate_array']['hs_name']; ?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="region_id" class="validation">申报海关代码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="region_id" name="region_id" value="<?php echo $output['taxrate_array']['region_id']; ?>" class="txt" type="text" readonly /></td>
          <td class="vatop tips">8012 | 重庆保税</span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="tariff" class="validation">关税:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="tariff" name="tariff" value="<?php echo $output['taxrate_array']['tariff']; ?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="common_tariff" class="validation">一般关税:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="common_tariff" name="common_tariff" value="<?php echo $output['taxrate_array']['common_tariff']; ?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="consumption_tax" class="validation">消费税:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="consumption_tax" name="consumption_tax" value="<?php echo $output['taxrate_array']['consumption_tax']; ?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="vat_tax" class="validation">进口增值税:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="vat_tax" name="vat_tax" value="<?php echo $output['taxrate_array']['vat_tax']; ?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
		
      </tbody>
      <tfoot id="submit-holder">
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#tax_rate_form").valid()){
     $("#tax_rate_form").submit();
	}
	});
});

$(document).ready(function(){
	$('#tax_rate_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },

        rules : {
            hs_code : {
                required : true,
            }, 
			hs_name : {
                required : true,
            },
            tariff : {
                number   : true
            }, 
			consumption_tax : {
                number   : true
            },
			vat_tax : {
                number   : true
            }
        },
        messages : {
            hs_code : {
                required : '海关HS编码不能为空',
            },
            hs_name : {
                required : '海关商品名称不能为空',
            },
            tariff  : {
                number   : '关税只能是数字'
            },
            consumption_tax  : {
                number   : '消费税只能是数字'
            },
            vat_tax  : {
                number   : '增值税只能是数字'
            }
        }
    });
});
</script>