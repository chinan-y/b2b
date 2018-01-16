<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="ncc-form-default">
  <form method="POST" name="addr_form" id="addr_form" action="index.php">
    <input type="hidden" value="buy" name="gct">
    <input type="hidden" value="add_addr" name="gp">
    <input type="hidden" name="form_submit" value="ok"/>
     
	<dl>
      <dt><i class="required">*</i><?php echo $lang['cart_step1_input_true_name'].$lang['nc_colon'];?></dt>
      <dd>
        <input type="text" class="text w100" name="true_name" maxlength="20" id="true_name" value=""/>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i><?php echo $lang['cart_step1_area'].$lang['nc_colon'];?></dt>
      <dd>
        <div id="region">
          <select class="w110">
          </select>
          <input type="hidden" value="" name="city_id" id="city_id">
          <input type="hidden" name="area_id" id="area_id" class="area_ids"/>
          <input type="hidden" name="area_info" id="area_info" class="area_names"/>
        </div>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i><?php echo $lang['cart_step1_whole_address'].$lang['nc_colon'];?></dt>
      <dd>
        <input type="text" class="text w500" name="address" id="address" maxlength="80" value=""/>
        <p class="hint"><?php echo $lang['cart_step1_true_address'];?></p>
      </dd>
    </dl>
    <dl>
      <dt> <i class="required">*</i><?php echo $lang['cart_step1_mobile_num'].$lang['nc_colon'];?></dt>
      <dd>
        <input type="text" class="text w200" name="mob_phone" id="mob_phone" maxlength="15" value=""/>
        &nbsp;&nbsp;(或)&nbsp;<?php echo $lang['cart_step1_phone_num'].$lang['nc_colon'];?>
        <input type="text" class="text w200" id="tel_phone" name="tel_phone" maxlength="20" value=""/>
      </dd>
    </dl>
    
  </form>
</div>

<script type="text/javascript">
$(document).ready(function(){
	regionInit("region");

	//身份证号码验证
	jQuery.validator.addMethod("isIDCode", function(value, element) {   
		var tel = /(^[1-9][\d]{5}(19|20)[\d]{2}((0[1-9])|(10|11|12))([012][\d]|(30|31))[\d]{3}[xX\d]$)|(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)/;
		return this.optional(element) || (tel.test(value));
	}, "<?php echo $lang['cart_step1_right_true_idnum'];?>");
	
    $('#addr_form').validate({
        rules : {
            true_name : {
                required : true
            },
            area_id : {
                required : true,
                min   : 1,
                checkarea:true
            },
            address : {
                required : true
            },
            mob_phone : {
                required : checkPhone,
                minlength : 11,
				maxlength : 11,
                digits : true
            },
            tel_phone : {
                required : checkPhone,
                minlength : 6,
				maxlength : 20
            }
			
        },
        messages : {
            true_name : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_input_receiver'];?>'
            },
            area_id : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_choose_area'];?>',
                min  : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_choose_area'];?>',
                checkarea : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_choose_area'];?>'
            },
            address : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_input_address'];?>'
            },
            mob_phone : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_telphoneormobile'];?>',
                minlength: '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_mobile_num_error'];?>',
				maxlength: '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_mobile_num_error'];?>',
                digits : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_mobile_num_error'];?>'
            },
            tel_phone : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_telphoneormobile'];?>',
                minlength: '<i class="icon-exclamation-sign"></i><?php echo $lang['member_address_phone_rule'];?>',
				maxlength: '<i class="icon-exclamation-sign"></i><?php echo $lang['member_address_phone_rule'];?>'
            }
			
        },
        groups : {
            phone:'mob_phone tel_phone'
        }
    });
});

function checkPhone(){
    return ($('input[name="mob_phone"]').val() == '' && $('input[name="tel_phone"]').val() == '');
}

function submitAddAddr(){
    if ($('#addr_form').valid()){
        $('#buy_city_id').val($('#region').find('select').eq(1).val());
        $('#city_id').val($('#region').find('select').eq(1).val());
        var datas=$('#addr_form').serialize();
        $.post('index.php',datas,function(data){
            if (data.state){
                var true_name = $.trim($("#true_name").val());
				var true_idnum = $.trim($("#true_idnum").val());
                var tel_phone = $.trim($("#tel_phone").val());
                var mob_phone = $.trim($("#mob_phone").val());
            	var area_info = $.trim($("#area_info").val());
            	var address = $.trim($("#address").val());
            	showShippingPrice($('#city_id').val(),$('#area_id').val());
            	hideAddrList(data.addr_id,true_name,area_info+'&nbsp;&nbsp;'+address,(mob_phone != '' ? mob_phone : tel_phone));
				var is_default = 1;
				$.ajax({
					url:"index.php?gct=member_address&gp=default_address",
					data:{is_default:is_default,address_id:data.addr_id},
					type:"post",
				});
				window.location.reload();
            }else{
                alert(data.msg);
            }
        },'json');
    }else{
        return false;
    }
}
</script>