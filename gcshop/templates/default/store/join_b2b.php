<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="merchants_titles">
	<div class="reminder">
	    <h1 class="title">针对该商品我们诚招各省市代理商</h1>
		<img src="<?php echo SHOP_TEMPLATES_URL;?>/home/public/message.png" class="ap"/>
		<span>留下您的联系方式，我们会尽快给您回复</span>
	</div>
</div>
<div class="merchants-forms">
    <form id="submit_form" method="post" action="<?php echo urlShop('goodsdetail', 'save_merchants');?>">
	<input type="hidden" name="form_submit" value="ok" />
		  <dl>
			<dt>姓名</dt>
			<dd>
				<input type="text" id="username" name="username" placeholder="请输入您的姓名" />
				<label></label>
			</dd>
		  </dl>
			
		  <dl>
			<dt>电话</dt>
			<dd>
				<input type="text" id="mobile" name="mobile" placeholder="请输入联系电话" />
				<label></label>
			</dd>
		  </dl>
		  <dl>
			<dt>邮箱</dt>
			<dd>
				<input type="text" id="email" name="email" placeholder="请输入邮箱" />
				<label></label>
			</dd>
		  </dl>
		  <dl>
			<dt>说明</dt>
			<dd>
				<textarea id="merch_content" name="merch_content" class="textarea w400"  placeholder="请输入申请代理说明"></textarea>
				<label></label>
			</dd>
		  </dl>
		<div class="bottom">
			<input type="submit" class="submit" value="申请提交" />
			<a href="" class="ncm-btn ">取消并返回</a>
		</div>
    </form>
</div>
<script>
//申请代理提交
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
              required : true,
                email    : true
            }, 
            merch_content : {
                required : true
            }
        },
        messages : {
			username : {
                required : '姓名不能为空'
				//lettersonly: '<?php echo $lang['merchants_join_name_not_null'];?>'
            },
			mobile : {
                required : '联系电话不能为空',
                mobile : '请输入正确的联系电话'
            },
			 email : {
                required : '邮箱不能为空',
                email    : '请输入正确的邮箱'
            }, 
            merch_content : {
                required : '申请代理说明不能为空'
            }
        }
    });
});
</script>