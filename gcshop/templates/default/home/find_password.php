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
  <div class="left-pic"> <img src="<?php echo $output['lpic'];?>"  border="0"> </div>
  <div class="nc-login">
    <div class="nc-login-title top-title">
    </div>
  <div class="nc-login-content mobile_find">
	<form action="index.php?gct=login&gp=mobile_find_password" method ="POST" id="mobile_find_password_form">
		<?php Security::getToken();?>
        <input type="hidden" name="form_submit" value="ok" />
        <input name="nchash" type="hidden" value="<?php echo getNchash();?>" />
		<dl>
          <dt><?php echo $lang['login_register_mobile'];?></dt>
          <dd style="min-height:54px;">
            <input type="text" class="text" name="mobile" id="mobile" maxlength="11"/>
            <label></label>
          </dd>
        </dl>
		<dl>
          <dt><?php echo $lang['login_register_code'];?></dt>
          <dd style="min-height:54px;">
            <input type="text" name="captcha" class="text w50 fl" id="mob_captcha" maxlength="4" size="10" />
            <img src="index.php?gct=seccode&gp=makecode&nchash=<?php echo getNchash();?>" title="<?php echo $lang['login_index_change_checkcode'];?>" name="codeimage" border="0" id="codeimage" class="fl ml5"> <a href="javascript:void(0);" class="ml5" onclick="javascript:document.getElementById('codeimage').src='index.php?gct=seccode&gp=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random();"><?php echo $lang['login_password_change_code']; ?></a>
            <label></label>
          </dd>
        </dl>
		<dl id="dl_mobile_captcha" >
		  <dt><?php echo $lang['login_register_mobile_code'];?></dt>
          <dd style="min-height:54px;">
            <input type="text" id="mobile_captcha" name="mobile_captcha" class="text tip c_code_msg" maxlength="6" title="<?php echo $lang['login_register_input_mobile_code'];?>" />
            <span id="send_message" class="send_message"><?php echo $lang['login_register_click_to_change_mobile_code'];?></span>
			<label></label>
          </dd>
        </dl>
		<dl>
          <dt><?php echo $lang['login_password_new_password'];?></dt>
          <dd style="min-height:54px;" id="phone-pass">
            <input  type="password"  class="text" name="password" id="password" maxlength="20"/>
            <label></label>
          </dd>
		  <div class="phone-open-eye eye" style="display: none;"><a href="javascript:void(0);" ><img src="/gcshop/templates/default/images/open-eye.png"/></a></div>
		  <div class="phone-close-eye eye"><a href="javascript:void(0);" ><img src="/gcshop/templates/default/images/close-eye.png"/></a></div>
        </dl>
		<dl>
          <dt></dt>
          <dd >
            <input type="submit" class="submit" value="重置密码" name="mobile_Submit" id="mobile_Submit">
          </dd>
        </dl>
		<dl>
		  <dd class="regist"><a href="index.php?gct=login&gp=register">立即注册</a><a href="index.php?gct=login&ref_url=<?php echo urlencode($output['ref_url']); ?>" ><?php echo $lang['login_register_login_now_2'];?></a></dd>
		</dl>
		<input type="hidden" value="<?php echo $output['ref_url']?>" name="ref_url">
	</form>
  </div>
  <div class="nc-login-bottom"></div>
  </div>
</div>
<script type="text/javascript">

//密码明文隐文交替显示
	$('.phone-open-eye').click(function(){
		var temp=$("input[name=password]").val();
		document.getElementById('phone-pass').innerHTML='<input  type="password"  class="text" name="password" id="password"/>';
		document.getElementById('password').value=temp;
		$('.phone-open-eye').hide();
		$('.phone-close-eye').show();
	});

	$('.phone-close-eye').click(function(){	
		var temp=$("input[name=password]").val();
		document.getElementById('phone-pass').innerHTML='<input  type="text"  class="text" name="password" id="password"/>';
		document.getElementById('password').value=temp;
		$('.phone-close-eye').hide();
		$('.phone-open-eye').show();
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
		var tel = /^(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])\d{8}$/i;
        if (!tel.test(mobile)) {
			$("#mobile").focus();
            alert("请输入正确的手机号码！");
            return;
        }
		
		var captcha=$('#mob_captcha').val().trim();
		if(captcha == ''){
			$("#mob_captcha").focus();
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


$(function(){	
	$('#mobile_Submit').click(function(){
        if($("#mobile_find_password_form").valid()){
        	ajaxpost('mobile_find_password_form', '', '', 'onerror');
        } else{
        	document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?gct=seccode&gp=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random();
        }
    });
    $('#mobile_find_password_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.find('label').hide();
            error_td.append(error);
        },
        rules : {
			mobile : {
                required : true,
				mobile	 : true
				
            },
            captcha : {
                required : true,
                minlength: 4,
                remote   : {
                    url : 'index.php?gct=seccode&gp=check&nchash=<?php echo getNchash();?>',
                    type: 'get',
                    data:{
                        mob_captcha : function(){
                            return $('#mob_captcha').val();
                        }
                    }
                }
            },
			mobile_captcha : {
                required : true,
                minlength: 6,
				maxlength: 6,
                remote   : {
                    url : 'index.php?gct=seccode&gp=checkAuth&nchash=<?php echo getNchash();?>',
                    type: 'get',
					data:{
                        mob_captcha : function(){
                            return $('#mob_captcha').val();
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
            }
        },
        messages : {
            mobile : {
                required : '<?php echo $lang['login_register_input_mobile'];?>',
                mobile : '<?php echo $lang['login_register_invalid_mobile'];?>'
            },
			<?php if(C('captcha_status_register') == '1') { ?>
            captcha : {
                required : '<?php echo $lang['login_register_input_text_in_image'];?>',
				minlength : '<?php echo $lang['login_usersave_wrong_code'];?>',
				remote	 : '<?php echo $lang['login_register_code_wrong'];?>'
            },
			<?php } ?>
            mobile_captcha : {
                required : '<?php echo $lang['login_register_input_mobile_code'];?>',
                minlength: '<?php echo $lang['login_register_input_mobile_code'];?>',
				maxlength: '<?php echo $lang['login_register_input_mobile_code'];?>',
				remote	 : '<?php echo $lang['login_register_mobile_code_wrong'];?>'
            },
            password  : {
                required : '<?php echo $lang['login_register_input_password'];?>',
                minlength: '<?php echo $lang['login_register_password_range'];?>',
				maxlength: '<?php echo $lang['login_register_password_range'];?>'
            }
        }
    });
	
});
</script> 
