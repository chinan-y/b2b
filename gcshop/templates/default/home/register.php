<?php defined('GcWebShop') or exit('Access Invalid!');?>
<style type="text/css">
.public-top-layout, .head-app, .head-search-bar, .head-user-menu, .public-nav-layout, .nch-breadcrumb-layout, #faq {
	display: none !important;
}
.public-head-layout {
	margin: 10px auto -10px auto;
}
.wrapper {
	width: 1000px;
}
#footer {
	border-top: none!important;
	padding-top: 30px;
}
</style>
      
<div class="nc-login-layout">
   <div class="nc-login">
		<div class="nc-login-title top-title">
		</div>
    <div class="nc-login-content">
      <form id="register_form" method="post" action="<?php echo SHOP_SITE_URL;?>/index.php?gct=login&gp=usersave">
      <?php Security::getToken();?>
		<div class="mobile-login">	
			<dl>
			  <dt class="number"><?php echo $lang['login_register_mobile'];?></dt>
			  <dd style="min-height:54px;">
				<input type="text" id="mobile" name="mobile" class="text tip" maxlength="11" title="<?php echo $lang['login_register_input_valid_mobile'];?>"/>
				<label></label>
			  </dd>
			</dl>
			<?php if(C('captcha_status_register') == '1') { ?>
			<dl class="registre_dl">
			  <dt><?php echo $lang['login_register_code'];?></dt>
			  <dd style="min-height:54px;">
				<input type="text" id="captcha" name="captcha" class="text w50 fl tip" maxlength="4" size="10" title="<?php echo $lang['login_register_input_code'];?>" />
				<img src="index.php?gct=seccode&gp=makecode&nchash=<?php echo getNchash();?>" title="" name="codeimage" border="0" id="codeimage" class="fl ml5"/> <a href="javascript:void(0)" class="ml5" onclick="javascript:document.getElementById('codeimage').src='index.php?gct=seccode&gp=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random();"><?php echo $lang['login_register_click_to_change_code'];?></a>
				<label></label>
			  </dd>
			</dl>
			<?php } ?>
			<dl id="dl_mobile_captcha">
			  <dt><?php echo $lang['login_register_mobile_code'];?></dt>
			  <dd style="min-height:54px;">
				<input type="text" id="mobile_captcha" name="mobile_captcha" class="text tip c_code_msg" maxlength="6" title="<?php echo $lang['login_register_input_mobile_code'];?>" />
				<span id="send_message" class="send_message"><?php echo $lang['login_register_click_to_change_mobile_code'];?></span>
				<label></label>
			  </dd>
			</dl>
			<dl>
			  <dt><?php echo $lang['login_register_pwd'];?></dt>
			  <dd style="min-height:54px;" id="mobile-pass">
				<input type="password" id="password" name="password" class="text tip" minlength="6" maxlength="20" title="<?php echo $lang['login_register_password_to_login'];?>" />
				<label></label>
			  </dd>
			  <div class="mobile-open-eye eye" style="display: none;"><a href="javascript:void(0);" ><img src="/gcshop/templates/default/images/open-eye.png"/></a></div>
				<div class="mobile-close-eye eye"><a href="javascript:void(0);" ><img src="/gcshop/templates/default/images/close-eye.png"/></a></div>
			</dl>
			<dl>
			  <dt>&nbsp;</dt>
			  <dd>
				<input type="submit" id="Submit" value="<?php echo $lang['login_register_regist_now'];?>" class="submit" title="<?php echo $lang['login_register_regist_now'];?>" />
				<input name="agree" type="checkbox" class="vm ml10" id="clause" value="1" checked="checked" />
				<span for="clause" class="ml5"><?php echo $lang['login_register_agreed'];?><a href="<?php echo urlShop('document', 'index',array('code'=>'agreement'));?>" target="_blank" class="agreement" title="<?php echo $lang['login_register_agreed'];?>"><?php echo $lang['login_register_agreement'];?></a></span>
			  </dd>
			</dl>
			<dl>
			  <dd class="regist">
				<a href="index.php?gct=login&ref_url=<?php echo urlencode($output['ref_url']); ?>"><?php echo '账号登录';?></a>
			  </dd>
			</dl>
			<input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">
			<input name="nchash" type="hidden" value="<?php echo getNchash();?>" />
			<input type="hidden" name="form_submit" value="ok" />
			<input type="hidden" value="<?php echo $_GET['zmr']?>" name="zmr">
		</div>
      </form>
      <div class="clear"></div>
    </div>
	
	<div class="nc-login-bottom"></div>
  </div>
  <div class="nc-login-left">
	<div class="leftpic"><img src="<?php echo $output['lpic'];?>"  border="0"></div>
    </div>
</div>
  
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script> 
<script type="text/javascript">
    //密码明文隐文交替显示
	$('.mobile-open-eye').click(function(){
		var temp=$("input[name=password]").val();
		document.getElementById('mobile-pass').innerHTML='<input  type="password"  class="text" name="password" id="password"/>';
		document.getElementById('password').value=temp;
		$('.mobile-open-eye').hide();
		$('.mobile-close-eye').show();
	});

	$('.mobile-close-eye').click(function(){	
		var temp=$("input[name=password]").val();
		document.getElementById('mobile-pass').innerHTML='<input  type="text"  class="text" name="password" id="password"/>';
		document.getElementById('password').value=temp;
		$('.mobile-close-eye').hide();
		$('.mobile-open-eye').show();
	});
	
	var left_time = '<?php echo $output['left_time'] ? $output['left_time'] : 60;?>';
	if(left_time < 60 && left_time >= 0){
		$("#send_message").removeClass("send_message1");
		var timer=setInterval(function  () {
			var code = $("#send_message");
			code.html(left_time+"&nbsp;s");
			left_time--;
			if (left_time<=0) {
				clearInterval(timer);
				code.html("<?php echo $lang['login_register_click_to_change_mobile_code'];?>");
				code.removeClass("send_message1");
				
			}else{
				code.addClass("send_message1");
			}
		},1000)
	}
	$(function  () {
		//获取短信验证码
		$("#send_message").click (function  () {
			if(left_time < 60 && left_time > 0){
				alert("请等待");
				return;
			}else{
				left_time = 60;
			}
			
			var mobile=$('#mobile').val().trim();
			if(mobile == ''){
				alert("请输入您的手机号码");
				return;
			}		
			var tel = /^(13|14|15|17|18)\d{9}$/;
			if (!tel.test(mobile)) {
				$("#mobile").focus();
				alert("请输入正确的手机号码");
				return;
			}
			
			var captcha=$('#captcha').val().trim();
			if(captcha == ''){
				$("#captcha").focus();
				alert("请输入验证码");
				return;
			}
			
			var data = {mobile:mobile, nchash:'<?php echo getNchash();?>', captcha:captcha};
					
			$.getJSON('index.php?gct=seccode&gp=makeAuthcode&type=mobile ',data,function(data){
				document.getElementById('codeimage').src='index.php?gct=seccode&gp=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random();
				if (data.state == 'true') {
					if(left_time == 60){
						$("#send_message").removeClass("send_message1");
						var timer=setInterval(function  () {
							var code = $("#send_message");
							code.html(left_time+"&nbsp;s");
							left_time--;
							if (left_time<=0) {
								clearInterval(timer);
								code.html("<?php echo $lang['login_register_click_to_change_mobile_code'];?>");
								code.removeClass("send_message1");
							}else{
								code.addClass("send_message1");
							}
						},1000)
					}
					$(".registre_dl").hide();
					alert(data.msg);
				} else {
					alert(data.msg);
				}
			});
		});
	})
	</script>
	<script>
	//注册表单提示
	$('.tip').poshytip({
		className: 'tip-yellowsimple',
		showOn: 'focus',
		alignTo: 'target',
		alignX: 'center',
		alignY: 'top',
		offsetX: 0,
		offsetY: 5,
		allowTipHover: false
	});

//注册表单验证
$(function(){
	
	jQuery.validator.addMethod("mobile", function(value, element) {
		return this.optional(element) || /^(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])\d{8}$/i.test(value);
	}, "<?php echo $lang['login_register_invalid_mobile'];?>"); 
	jQuery.validator.addMethod("lettersonly", function(value, element) {
		return this.optional(element) || /^[^:%,'\*\"\s\<\>\&]+$/i.test(value);
	}, "Letters only please"); 
	jQuery.validator.addMethod("lettersmin", function(value, element) {
		return this.optional(element) || ($.trim(value.replace(/[^\u0000-\u00ff]/g,"aa")).length>=3);
	}, "Letters min please"); 
	jQuery.validator.addMethod("lettersmax", function(value, element) {
		return this.optional(element) || ($.trim(value.replace(/[^\u0000-\u00ff]/g,"aa")).length<=15);
	}, "Letters max please");
	
    $("#register_form").validate({
           errorPlacement: function(error, element){
                var error_td = element.parent('dd');
                error_td.find('label').hide();
                error_td.append(error);
            },
            onkeyup: false,
            rules : {
                mobile : {
                    required : true,
                    mobile	 : true,
                    //minlength: 11,
                    //maxlength: 11,
                    remote   : {
                        url : 'index.php?gct=login&gp=check_mobile',
                        type: 'get',
                        data:{
                            mobile : function(){
                                return $('#mobile').val();
                            }
                        },
                        complete: function(data) {
                            if(data.responseText == 'true') {
                                $("#dl_mobile_captcha").show(300);
                            }else{
                                $("#mobile_captcha").attr("value", "")
                                $("#dl_mobile_captcha").hide(300);
                            }
                        }
                    }
                },
			<?php if(C('captcha_status_register') == '1') { ?>
                captcha : {
                    required : true,
                    remote   : {
                        url : 'index.php?gct=seccode&gp=check&nchash=<?php echo getNchash();?>',
                        type: 'get',
                        data:{
                            captcha : function(){
                                return $('#captcha').val();
                            }
                        },
                        complete: function(data) {
                            if(data.responseText == 'false') {
                                document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?gct=seccode&gp=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random();
                            }
                        }
                    }
                },
			<?php } ?>
			 mobile_captcha : {
                    required : true,
                    minlength: 6,
                    maxlength: 6,
                    remote   : {
                        url : 'index.php?gct=seccode&gp=checkAuth&nchash=<?php echo getNchash();?>',
                        type: 'get',
                        data:{
                            captcha : function(){
                                return $('#captcha').val();
                            },
                            mobile_captcha : function(){
                                return $('#mobile_captcha').val();
                            }
                        }
                    }
                },

            password : {
                required : true,
                minlength: 6,
				maxlength: 20
            },

        },
        messages : {
			mobile : {
				required : '<?php echo $lang['login_register_input_mobile'];?>',
				mobile : '<?php echo $lang['login_register_invalid_mobile'];?>',
				//minlength: '<?php echo $lang['login_register_input_mobile_length'];?>',
				//maxlength: '<?php echo $lang['login_register_input_mobile_length'];?>',
				remote	 : '<?php echo $lang['login_register_mobile_exists'];?>'
			},
			<?php if(C('captcha_status_register') == '1') { ?>
			captcha : {
				required : '<?php echo $lang['login_register_input_text_in_image'];?>',
				remote	 : '<?php echo $lang['login_register_code_wrong'];?>'
			},
			<?php } ?>
			mobile_captcha : {
				required : '<?php echo $lang['login_register_input_mobile_code'];?>',
				minlength: '<?php echo $lang['login_register_input_mobile_code'];?>',
				maxlength: '<?php echo $lang['login_register_input_mobile_code'];?>',
				remote	 : '<?php echo $lang['login_register_mobile_code_wrong'];?>'
			},
        }
    });
	
});
</script>