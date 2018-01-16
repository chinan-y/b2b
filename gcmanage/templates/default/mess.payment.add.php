<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>海关对接管理</h3>
      <ul class="tab-base">
		<li><a href="index.php?gct=mess_payment&gp=mess_payment" ><span>支付单列表</span></a></li>
       <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_new'];?></span></a></li>
		
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" id="mess_paymentinfo_form" >
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
	  
        <tr class="noborder">
          <td colspan="2" class="required"><label for="PAYMENT_NO" class="validation">支付单号:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="PAYMENT_NO" name="PAYMENT_NO" value="" class="txt" type="text" /></td>
          <td class="vatop tips"></span></td>
        </tr>
	  
        <tr>
          <td colspan="2" class="required"><label for="ORIGINAL_ORDER_NO" class="validation" >原始订单编号:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="ORIGINAL_ORDER_NO" name="ORIGINAL_ORDER_NO" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
		
        <tr>
          <td colspan="2" class="required"><label for="BIZ_TYPE_CODE" class="validation">业务类型:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
			<ul>
              <li>
                <label>
                  <input type="radio" value="I10" name="BIZ_TYPE_CODE">
                  直购进口
				  </label>
              </li>
              <li>
                <label>
                  <input type="radio" value="I20" name="BIZ_TYPE_CODE"  checked="checked">
                  网购保税进口
				  </label>
              </li>
            </ul>		   
			</td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="CUSTOMS_CODE" class="validation">申报海关代码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="CUSTOMS_CODE" name="CUSTOMS_CODE" value="8012" class="txt" type="text" readonly /></td>
          <td class="vatop tips">8012 | 重庆保税</span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="PAY_AMOUNT" class="validation">支付总额:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="PAY_AMOUNT" name="PAY_AMOUNT" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="GOODS_FEE" class="validation">商品货款总额:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="GOODS_FEE" name="GOODS_FEE" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="TAX_FEE" class="validation">税款总额:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="TAX_FEE" name="TAX_FEE" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="CURRENCY_CODE" class="validation">支付币种:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="CURRENCY_CODE" name="CURRENCY_CODE" value="142" class="txt" type="text" readonly /></td>
          <td class="vatop tips">142|人民币<span class="vatop rowform"></span></td>
        </tr>
		
		 <tr>
          <td colspan="2" class="required"><label for="MEMO">备注:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
				<textarea name="MEMO" rows="4" id="MEMO" ></textarea>		  
			</td>
          <td class="vatop tips"> <span class="vatop rowform"></span></td>
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
					PAYMENT_INFO_ID : ''
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