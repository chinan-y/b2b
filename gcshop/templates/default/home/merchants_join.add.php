<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="merchants_img">
	<img src="<?php echo SHOP_TEMPLATES_URL;?>/home/public/league1.jpg" class="ap"/>
	<img src="<?php echo SHOP_TEMPLATES_URL;?>/home/public/league2.jpg" class="ap"/>
	<img src="<?php echo SHOP_TEMPLATES_URL;?>/home/public/league3.jpg" class="ap"/>
	<img src="<?php echo SHOP_TEMPLATES_URL;?>/home/public/league4.jpg" class="ap"/>
	<img src="<?php echo SHOP_TEMPLATES_URL;?>/home/public/league5.jpg" class="ap"/>
	<img src="<?php echo SHOP_TEMPLATES_URL;?>/home/public/league6.jpg" class="ap"/>
	<img src="<?php echo SHOP_TEMPLATES_URL;?>/home/public/league7.jpg" class="ap"/>
	<img src="<?php echo SHOP_TEMPLATES_URL;?>/home/public/league8.jpg" class="ap"/>
</div>
<div class="merchants_title">
	<h3><?php //echo $lang['merchants_join_contact_us'];?></h3>
	<div class="reminder">
		<img src="<?php echo SHOP_TEMPLATES_URL;?>/home/public/message.png" class="ap"/>
		<span>留下您的联系方式，我们会尽快给您回复</span>
	</div>
</div>
<div class="merchants-default-form">
    <form id="submit_form" method="post" action="<?php echo urlShop('merchants_joinin', 'save_merchants');?>">
	<input type="hidden" name="form_submit" value="ok" />
		  <dl>
			<dt><?php echo $lang['merchants_join_submit_name'].$lang['nc_colon'];?></dt>
			<dd>
				<input type="text" id="username" name="username" placeholder="请输入姓名" />
				<label></label>
			</dd>
		  </dl>
			
		  <dl>
			<dt><?php echo $lang['merchants_join_submit_mobile'].$lang['nc_colon'];?></dt>
			<dd>
				<input type="text" id="mobile" name="mobile" placeholder="请输入联系电话" />
				<label></label>
			</dd>
		  </dl>
		  <dl>
			<dt><?php echo $lang['merchants_join_submit_email'].$lang['nc_colon'];?></dt>
			<dd>
				<input type="text" id="email" name="email" placeholder="请输入邮箱" />
				<label></label>
			</dd>
		  </dl>
		  <dl>
			<dt><?php echo $lang['merchants_join_submit_explain'].$lang['nc_colon'];?></dt>
			<dd>
				<textarea id="merch_content" name="merch_content" class="textarea w400"  placeholder="请输入申请加盟说明"></textarea>
				<label></label>
			</dd>
		  </dl>
		<div class="bottom">
			<input type="submit" class="submit" value="<?php echo $lang['merchants_join_submit'];?>" />
			<a href="" class="ncm-btn "><?php echo $lang['merchants_join_cancel_return'];?></a>
		</div>
    </form>
</div>
<img style="width:100%; border:0;"src="<?php echo SHOP_TEMPLATES_URL;?>/home/public/league9.jpg" class="ap"/>
<script>
//注册表单验证
$(function(){
	jQuery.validator.addMethod("mobile", function(value, element) {
		return this.optional(element) || /^(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])\d{8}$/i.test(value);
	}, "<?php echo $lang['login_register_invalid_mobile'];?>"); 
	jQuery.validator.addMethod("lettersonly", function(value, element) {
		return this.optional(element) || /^[^:%,'\*\"\s\<\>\&]+$/i.test(value);
	}, "Letters only please"); 
	
    $("#submit_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.find('label').hide();
            error_td.append(error);
        },
        onkeyup: false,
        rules : {
            username : {
                required : true,
             // lettersonly : true
            },
			mobile : {
                required : true,
				mobile	 : true
            },
			email : {
             // required : true,
                email    : true
            }, 
            merch_content : {
                required : true
            }
        },
        messages : {
			username : {
                required : '<?php echo $lang['merchants_join_name_not_null'];?>'
				//lettersonly: '<?php echo $lang['merchants_join_name_not_null'];?>'
            },
			mobile : {
                required : '<?php echo $lang['merchants_join_mobile_not_null'];?>',
                mobile : '<?php echo $lang['merchants_join_right_mobile'];?>'
            },
			 email : {
                //required : '<?php echo $lang['merchants_join_submit'];?>',
                email    : '<?php echo $lang['merchants_join_right_email'];?>'
            }, 
            merch_content : {
                required : '<?php echo $lang['merchants_join_content_not_null'];?>'
            }
        }
    });
});
</script>
