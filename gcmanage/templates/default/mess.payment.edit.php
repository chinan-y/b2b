<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>海关对接管理</h3>
      <ul class="tab-base">
		<li><a href="index.php?gct=mess_payment&gp=mess_payment" ><span>订单列表</span></a></li>
        <li><a href="index.php?gct=mess_payment&gp=mess_payment_add" ><span><?php echo $lang['nc_new'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_edit'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="mess_paymentinfo_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="PAYMENT_INFO_ID" value="<?php echo $output['paymentinfo_array']['PAYMENT_INFO_ID'];?>" />
    <table class="table tb-type2">
      <tbody>
	  
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="PAYMENT_NO">原始订单编号:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['paymentinfo_array']['PAYMENT_NO'];?>" name="PAYMENT_NO" id="PAYMENT_NO" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
	  
        <tr>
          <td colspan="2" class="required"><label class="validation" for="ORIGINAL_ORDER_NO">原始订单编号:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['paymentinfo_array']['ORIGINAL_ORDER_NO'];?>" name="ORIGINAL_ORDER_NO" id="ORIGINAL_ORDER_NO" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
		
        <tr>
          <td colspan="2" class="required"><label for="BIZ_TYPE_CODE" class="validation">业务类型:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
		  
		  <ul>
              <li>
                <input type="radio" <?php if($output['paymentinfo_array']['BIZ_TYPE_CODE'] == 'I10'){ ?>checked="checked"<?php } ?> value="I10" name="BIZ_TYPE_CODE" id="BIZ_TYPE_CODE1">
                <label for="BIZ_TYPE_CODE1">直购进口</label>
              </li>
              <li>
                <input type="radio" <?php if($output['paymentinfo_array']['BIZ_TYPE_CODE'] == 'I20'){ ?>checked="checked"<?php } ?> value="I20" name="BIZ_TYPE_CODE" id="BIZ_TYPE_CODE2">
                <label for="BIZ_TYPE_CODE2">网购保税进口</label>
              </li>
            </ul>

			</td>
          <td class="vatop tips"> </td>
        </tr>
		
        <tr>
          <td colspan="2" class="required"><label for="CUSTOMS_CODE" class="validation">申报海关代码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['paymentinfo_array']['CUSTOMS_CODE'];?>" name="CUSTOMS_CODE" id="CUSTOMS_CODE" class="txt" readonly ></td>
          <td class="vatop tips">详细关区代码请查询 基础参数-关区代码</td>
        </tr>
		
        <tr>
          <td colspan="2" class="required"><label for="PAY_AMOUNT" class="validation">支付总额:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['paymentinfo_array']['PAY_AMOUNT'];?>" name="PAY_AMOUNT" id="PAY_AMOUNT" class="txt"></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
		
		<tr>
          <td colspan="2" class="required"><label for="GOODS_FEE" class="validation">商品货款总额:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['paymentinfo_array']['GOODS_FEE'];?>" name="GOODS_FEE" id="GOODS_FEE" class="txt"></td>
          <td class="vatop tips"> </td>
        </tr>

		<tr>
          <td colspan="2" class="required"><label for="TAX_FEE" class="validation">税金总额:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['paymentinfo_array']['TAX_FEE'];?>" name="TAX_FEE" id="TAX_FEE" class="txt"></td>
          <td class="vatop tips"></td>
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
    if($("#mess_paymentinfo_form").valid()){
     $("#mess_paymentinfo_form").submit();
	}
	});
});

$(document).ready(function(){
	$('#mess_paymentinfo_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },

        rules : {
            PAYMENT_NO : {
                required : true,
                remote   : {                
                url :'index.php?gct=mess_payment&gp=ajax&branch=check_mess_payment',
                type:'get',
                data:{
                    PAYMENT_NO : function(){
                        return $('#PAYMENT_NO').val();
                    },
					ORDER_ID : '<?php echo $output['paymentinfo_array']['PAYMENT_INFO_ID'];?>'
                  }
                }
            },
			PAY_AMOUNT : {
                number   : true
            },
            GOODS_FEE : {
                number   : true
            },
			TAX_FEE : {
                number   : true
            }
        },
        messages : {
            PAYMENT_NO : {
                required : '支付单号不能为空！',
                remote   : '该支付单号已经存在！'
            },
			PAY_AMOUNT  : {
                number   : '支付总额只能是数字'
            },
            GOODS_FEE  : {
                number   : '货款总额只能是数字'
            },
            TAX_FEE  : {
                number   : '税金总额只能是数字'
            }
        }
    });
});
</script>