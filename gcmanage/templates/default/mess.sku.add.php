<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>海关对接管理</h3>
      <ul class="tab-base">
		<li><a href="index.php?gct=mess_sku&gp=mess_sku" ><span><?php echo $lang['mess_sku'];?></span></a></li>
       <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_new'];?></span></a></li>
		
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" id="mess_skuinfo_form" >
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="SKU" class="validation" ><?php echo $lang['mess_sku'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="SKU" name="SKU" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="GOODS_NAME" class="validation"><?php echo $lang['mess_goods_name'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="GOODS_NAME" name="GOODS_NAME" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="GOODS_SPEC" class="validation"><?php echo $lang['mess_goods_spec'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="GOODS_SPEC" name="GOODS_SPEC" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
         
        <tr>
          <td colspan="2" class="required"><label for="DECLARE_UNIT" class="validation"><?php echo $lang['mess_declare_unit'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="DECLARE_UNIT" name="DECLARE_UNIT" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
		
        <tr>
          <td colspan="2" class="required"><label for="POST_TAX_NO" class="validation"><?php echo $lang['mess_post_tax_no'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="POST_TAX_NO" name="POST_TAX_NO" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="LEGAL_UNIT" class="validation"><?php echo $lang['mess_legal_unit'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="LEGAL_UNIT" name="LEGAL_UNIT" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="CONV_LEGAL_UNIT_NUM" class="validation"><?php echo $lang['mess_conv_legal_unit_num'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="CONV_LEGAL_UNIT_NUM" name="CONV_LEGAL_UNIT_NUM" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="HS_CODE" class="validation"><?php echo $lang['mess_hs_code'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="HS_CODE" name="HS_CODE" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="IN_AREA_UNIT" class="validation"><?php echo $lang['mess_in_area_unit'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="IN_AREA_UNIT" name="IN_AREA_UNIT" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="CONV_IN_AREA_UNIT_NUM" class="validation"><?php echo $lang['mess_conv_in_area_unit_num'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="CONV_IN_AREA_UNIT_NUM" name="CONV_IN_AREA_UNIT_NUM" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="IS_EXPERIMENT_GOODS"><?php echo $lang['mess_is_experiment_goods'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          <label>
            <input name="IS_EXPERIMENT_GOODS" type="radio" id="IS_EXPERIMENT_GOODS_1" value="1" checked>
            是</label>
            <label>
            <input name="IS_EXPERIMENT_GOODS" type="radio" id="IS_EXPERIMENT_GOODS_0" value="0" >
            否</label>
          </td>
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
					SKU_INFO_ID : ''
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