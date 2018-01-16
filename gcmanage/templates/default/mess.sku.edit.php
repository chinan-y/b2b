<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>海关对接管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?gct=mess_sku&gp=mess_sku" ><span><?php echo $lang['mess_sku'];?></span></a></li>
        <li><a href="index.php?gct=mess_sku&gp=mess_sku_add" ><span><?php echo $lang['nc_new'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_edit'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="mess_skuinfo_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="SKU_INFO_ID" value="<?php echo $output['skuinfo_array']['SKU_INFO_ID'];?>" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="SKU"><?php echo $lang['mess_sku'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['skuinfo_array']['SKU'];?>" name="SKU" id="SKU" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
		
        <tr>
          <td colspan="2" class="required"><label for="GOODS_NAME"  class="validation"><?php echo $lang['mess_goods_name'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['skuinfo_array']['GOODS_NAME'];?>" name="GOODS_NAME" id="GOODS_NAME" class="txt"></td>
          <td class="vatop tips"> </td>
        </tr>
		
        <tr>
          <td colspan="2" class="required"><label for="GOODS_SPEC" class="validation"><?php echo $lang['mess_goods_spec'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['skuinfo_array']['GOODS_SPEC'];?>" name="GOODS_SPEC" id="GOODS_SPEC" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
		
        <tr>
          <td colspan="2" class="required"><label for="DECLARE_UNIT" class="validation"><?php echo $lang['mess_declare_unit'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['skuinfo_array']['DECLARE_UNIT'];?>" name="DECLARE_UNIT" id="DECLARE_UNIT" class="txt"></td>
          <td class="vatop tips"> </td>
        </tr>
		
        <tr>
          <td colspan="2" class="required"><label for="POST_TAX_NO" class="validation"><?php echo $lang['mess_post_tax_no'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['skuinfo_array']['POST_TAX_NO'];?>" name="POST_TAX_NO" id="POST_TAX_NO" class="txt"></td>
          <td class="vatop tips"> </td>
        </tr>

        <tr>
          <td colspan="2" class="required"><label class="validation" for="LEGAL_UNIT"><?php echo $lang['mess_legal_unit'];?>（%）:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['skuinfo_array']['LEGAL_UNIT'];?>" name="LEGAL_UNIT" id="LEGAL_UNIT" class="txt"></td>
          <td class="vatop tips"> </td>
        </tr>
		
		<tr>
          <td colspan="2" class="required"><label for="CONV_LEGAL_UNIT_NUM" class="validation"><?php echo $lang['mess_conv_legal_unit_num'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['skuinfo_array']['CONV_LEGAL_UNIT_NUM'];?>" name="CONV_LEGAL_UNIT_NUM" id="CONV_LEGAL_UNIT_NUM" class="txt"></td>
          <td class="vatop tips"> </td>
        </tr>

		<tr>
          <td colspan="2" class="required"><label for="HS_CODE" class="validation"><?php echo $lang['mess_hs_code'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['skuinfo_array']['HS_CODE'];?>" name="HS_CODE" id="HS_CODE" class="txt"></td>
          <td class="vatop tips"> </td>
        </tr>

		<tr>
          <td colspan="2" class="required"><label for="IN_AREA_UNIT" class="validation"><?php echo $lang['mess_in_area_unit'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['skuinfo_array']['IN_AREA_UNIT'];?>" name="IN_AREA_UNIT" id="IN_AREA_UNIT" class="txt"></td>
          <td class="vatop tips"> </td>
        </tr>
		
		<tr>
          <td colspan="2" class="required"><label for="CONV_IN_AREA_UNIT_NUM" class="validation"><?php echo $lang['mess_conv_in_area_unit_num'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['skuinfo_array']['CONV_IN_AREA_UNIT_NUM'];?>" name="CONV_IN_AREA_UNIT_NUM" id="CONV_IN_AREA_UNIT_NUM" class="txt"></td>
          <td class="vatop tips"> </td>
        </tr>

		<tr>
          <td colspan="2" class="required"><label for="HG_EXAMINE">商品备案审批状态:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><label name="HG_EXAMINE" id="HG_EXAMINE" class="txt">海关：XXXX</label><label name="GJ_EXAMINE" id="GJ_EXAMINE" class="txt">国检：XXXX</label>	</td>
          <td class="vatop tips">申报时间：</td>
        </tr>
	  </tbody>
      <tfoot id="submit-holder">
        <tr class="tfoot">
          <td colspan="15"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#mess_skuinfo_form").valid()){
     $("#mess_skuinfo_form").submit();
	}
	});
});

$(document).ready(function(){
	$('#mess_skuinfo_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },

        rules : {
            SKU : {
                required : true,
                remote   : {                
                url :'index.php?gct=mess_sku&gp=ajax&branch=check_mess_sku',
                type:'get',
                data:{
                    SKU : function(){
                        return $('#SKU').val();
                    },
					SKU_INFO_ID : '<?php echo $output['skuinfo_array']['SKU_INFO_ID'];?>'
                  }
                }
            },
            CONV_LEGAL_UNIT_NUM : {
                number   : true
            },
			CONV_IN_AREA_UNIT_NUM : {
                number   : true
            }
        },
        messages : {
            SKU : {
                required : '商品货号不能为空！',
                remote   : '该商品货号已经存在！'
            },
            CONV_LEGAL_UNIT_NUM  : {
                number   : '法定计量单位折算数量只能是数字'
            },
            CONV_IN_AREA_UNIT_NUM  : {
                number   : '入区计量单位折算数量只能是数字'
            }
        }
    });
});
</script>