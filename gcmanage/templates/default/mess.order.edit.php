<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>海关对接管理</h3>
      <ul class="tab-base">
		<li><a href="index.php?gct=mess_order&gp=mess_order" ><span>订单列表</span></a></li>
        <li><a href="index.php?gct=mess_order&gp=mess_order_add" ><span><?php echo $lang['nc_new'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_edit'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="mess_orderinfo_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="ORDER_ID" value="<?php echo $output['orderinfo_array']['ORDER_ID'];?>" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="ORIGINAL_ORDER_NO">原始订单编号:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['orderinfo_array']['ORIGINAL_ORDER_NO'];?>" name="ORIGINAL_ORDER_NO" id="ORIGINAL_ORDER_NO" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
		
        <tr>
          <td colspan="2" class="required"><label for="BIZ_TYPE_CODE" class="validation">业务类型:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
		  
		  <ul>
              <li>
                <input type="radio" <?php if($output['orderinfo_array']['BIZ_TYPE_CODE'] == 'I10'){ ?>checked="checked"<?php } ?> value="I10" name="BIZ_TYPE_CODE" id="BIZ_TYPE_CODE1">
                <label for="BIZ_TYPE_CODE1">直购进口</label>
              </li>
              <li>
                <input type="radio" <?php if($output['orderinfo_array']['BIZ_TYPE_CODE'] == 'I20'){ ?>checked="checked"<?php } ?> value="I20" name="BIZ_TYPE_CODE" id="BIZ_TYPE_CODE2">
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
          <td class="vatop rowform"><input type="text" value="<?php echo $output['orderinfo_array']['CUSTOMS_CODE'];?>" name="CUSTOMS_CODE" id="CUSTOMS_CODE" class="txt" readonly ></td>
          <td class="vatop tips">详细关区代码请查询 基础参数-关区代码</td>
        </tr>
		
        <tr>
          <td colspan="2" class="required"><label for="DESP_ARRI_COUNTRY_CODE" class="validation">起运国编码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['orderinfo_array']['DESP_ARRI_COUNTRY_CODE'];?>" name="DESP_ARRI_COUNTRY_CODE" id="DESP_ARRI_COUNTRY_CODE" class="txt"></td>
          <td class="vatop tips">详细国家编码请查询基础参数-国家代码</td>
        </tr>
		
        <tr>
          <td colspan="2" class="required"><label for="SHIP_TOOL_CODE" class="validation">运输方式:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
				<select name="SHIP_TOOL_CODE" id="SHIP_TOOL_CODE">
					<option selected value="<?php echo $output['orderinfo_array']['SHIP_TOOL_CODE'];?>" > 不作变更</option>
					<option value="0">非保税区</option>
					<option value="1">监管仓库</option>
					<option value="2">水路运输</option>
					<option value="3">铁路运输</option>
					<option value="4">公路运输</option>
					<option value="5">航空运输</option>
					<option value="6">邮件运输</option>
					<option value="7">保税区  </option>
					<option value="8">保税仓库</option>
					<option value="9">其它运输</option>
					<option value="A">全部运输方式</option>
					<option value="H">边境特殊海关作业区</option>
					<option value="W">物流中心</option>
					<option value="X">物流园区</option>
					<option value="Y">保税港区</option>
					<option value="Z">出口加工区</option>
				</select>
		  
		  
          <td class="vatop tips"> </td>
        </tr>
		
        <tr>
          <td colspan="2" class="required"><label for="SORTLINE_ID" class="validation">分拣线:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
		  <select name="SORTLINE_ID" id="SORTLINE_ID" >
					<option selected value="<?php echo $output['orderinfo_array']['SORTLINE_ID'];?>" > 不作变更</option>
					<option value="SORTLINE01" >寸滩空港</option>
					<option value="SORTLINE02">重庆西永</option>
					<option value="SORTLINE03">寸滩水港</option>
					<option value="SORTLINE04">邮政EMS</option>
					<option value="SORTLINE05">潍坊分拣线01</option>
				</select>
		  </td>
          <td class="vatop tips"> </td>
        </tr>

        <tr>
          <td colspan="2" class="required"><label for="RECEIVER_NAME" class="validation">收货人姓名:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['orderinfo_array']['RECEIVER_NAME'];?>" name="RECEIVER_NAME" id="RECEIVER_NAME" class="txt"></td>
          <td class="vatop tips"> </td>
        </tr>
		
		<tr>
          <td colspan="2" class="required"><label for="RECEIVER_ID_NO" class="validation">收货人身份证号码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['orderinfo_array']['RECEIVER_ID_NO'];?>" name="RECEIVER_ID_NO" id="RECEIVER_ID_NO" class="txt"></td>
          <td class="vatop tips"> </td>
        </tr>

		<tr>
          <td colspan="2" class="required"><label for="RECEIVER_ADDRESS" class="validation">收货人地址:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['orderinfo_array']['RECEIVER_ADDRESS'];?>" name="RECEIVER_ADDRESS" id="RECEIVER_ADDRESS" class="txt"></td>
          <td class="vatop tips"> </td>
        </tr>

		<tr>
          <td colspan="2" class="required"><label for="RECEIVER_TEL" class="validation">收货人联系电话:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['orderinfo_array']['RECEIVER_TEL'];?>" name="RECEIVER_TEL" id="RECEIVER_TEL" class="txt"></td>
          <td class="vatop tips"> </td>
        </tr>
		
		<tr>
          <td colspan="2" class="required"><label for="GOODS_FEE" class="validation">货款总额:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['orderinfo_array']['GOODS_FEE'];?>" name="GOODS_FEE" id="GOODS_FEE" class="txt"></td>
          <td class="vatop tips"> </td>
        </tr>

		<tr>
          <td colspan="2" class="required"><label for="TAX_FEE" class="validation">税金总额:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['orderinfo_array']['TAX_FEE'];?>" name="TAX_FEE" id="TAX_FEE" class="txt"></td>
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
    if($("#mess_orderinfo_form").valid()){
     $("#mess_orderinfo_form").submit();
	}
	});
});

$(document).ready(function(){
	$('#mess_orderinfo_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },

        rules : {
            ORIGINAL_ORDER_NO : {
                required : true,
                remote   : {                
                url :'index.php?gct=mess_order&gp=ajax&branch=check_mess_order',
                type:'get',
                data:{
                    ORIGINAL_ORDER_NO : function(){
                        return $('#ORIGINAL_ORDER_NO').val();
                    },
					ORDER_ID : '<?php echo $output['orderinfo_array']['ORDER_ID'];?>'
                  }
                }
            },
            GOODS_FEE : {
                number   : true
            },
			TAX_FEE : {
                number   : true
            }
        },
        messages : {
            ORIGINAL_ORDER_NO : {
                required : '原始订单编号不能为空！',
                remote   : '该订单号已经存在！'
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