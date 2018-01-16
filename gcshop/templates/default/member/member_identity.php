<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <div class="alert alert-block">
	<h4><?php echo $lang['home_member_member_identity'];?></h4>
	<ul>
		<li><?php echo $lang['home_member_needing_attention1'];?></li>
		<li><?php echo $lang['home_member_needing_attention2'];?></li>
		<li><?php echo $lang['home_member_needing_attention3'];?></li>
	</ul>
  </div>
  <div class="ncm-user-profile">
    <div class="ncm-default-form fr">
      <form method="post" id="profile_form" action="index.php?gct=member&gp=add_identity">
        <input type="hidden" name="form_submit" value="ok" />
		<dl>
			<dt><i class="required">*</i><?php echo $lang['member_address_order_name'].$lang['nc_colon'];?></dt>
			<dd><span class="w400">
				<input type="text" class="text" maxlength="20" name="member_truename" value="<?php echo $output['member_info']['member_truename']; ?>" />
				</span>
			</dd>
        </dl>
		<dl>
			<dt><i class="required">*</i><?php echo $lang['member_address_true_idnum'].$lang['nc_colon'];?></dt>
			<dd><span class="w400">
				<input type="text" class="text" maxlength="20" name="member_code" value="<?php echo $output['member_info']['member_code']; ?>" />
				</span>
			</dd>
        </dl>
        <dl class="bottom">
          <dt></dt>
          <dd>
            <label class="submit-border">
              <input type="submit" class="submit" value="<?php echo $lang['home_member_save_identity'];?>" />
            </label>
          </dd>
        </dl>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript">
//注册表单验证
$(function(){
	regionInit("region");
	//身份证号码验证
	jQuery.validator.addMethod("isIDCode", function(value, element) {   
		var tel = /(^[1-9][\d]{5}(19|20)[\d]{2}((0[1-9])|(10|11|12))([012][\d]|(30|31))[\d]{3}[xX\d]$)|(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)/;
		return this.optional(element) || (tel.test(value));
	}, "<?php echo $lang['cart_step1_right_true_idnum'];?>");
	
    $('#profile_form').validate({
    	submitHandler:function(form){
    		if ($('select[class="valid"]').eq(0).val()>0) $('#province_id').val($('select[class="valid"]').eq(0).val());
			if ($('select[class="valid"]').eq(1).val()>0) $('#city_id').val($('select[class="valid"]').eq(1).val());
			ajaxpost('profile_form', '', '', 'onerror')
		},
        rules : {
			member_truename : {
                required : true
            },
			member_code : {
				required : true,
                isIDCode : true
            }
        },
        messages : {
			member_truename : {
                required : '<?php echo $lang['member_address_input_order'];?>'
            },
			member_code : {
                required : '<?php echo $lang['member_address_must_true_idnum'];?>',
				isIDCode : '<?php echo $lang['member_address_true_idnum_rule'];?>'
            }
        }
    });
});
</script> 
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>