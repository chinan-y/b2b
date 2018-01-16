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
.nc-login-other a.wechat {
    background: url(<?php echo SHOP_SITE_URL;?>/ectap/wechat/login/resource/images/login.png) no-repeat scroll;
}
.nc-login-other a.wechat {
    width: 88px;
    background-position: -1px 0px;
}
.nc-login-other a:hover.wechat {
    background-position: -1px -28px;
}
</style>
      <dl class="mt">
        <dd><?php echo $lang['login_index_regist_now_1'];?><a title="" href="index.php?gct=login&gp=register&ref_url=<?php echo urlencode($output['ref_url']);?>" class="register"><?php echo $lang['login_index_regist_now_2'];?></a></dd>
      </dl>
<div class="nc-login-layout">
  <div class="left-pic"><img src="<?php echo $output['lpic'];?>"  border="0"></div>
  <div class="nc-login">
    <div class="nc-login-title">
      <h3><?php echo $lang['login_index_user_login'];?></h3>
    </div>
    <div class="nc-login-content" id="demo-form-site">
      <form id="login_form" method="post" action="index.php?gct=login&gp=login"  class="bg">
        <?php Security::getToken();?>
        <input type="hidden" name="form_submit" value="ok" />
        <input name="nchash" type="hidden" value="<?php echo getNchash();?>" />
        <dl>
          <dt><?php echo $lang['login_index_username'];?></dt>
          <dd style="min-height:54px;">
            <input type="text" class="text" autocomplete="off"  name="user_name" id="user_name" placeholder="<?php echo $lang['login_index_username_content']?>" autofocus >
            <label></label>
          </dd>
        </dl>
        <dl>
          <dt><?php echo $lang['login_index_password'];?> </dt>
          <dd style="min-height:54px;">
            <input type="password" class="text" name="password" autocomplete="off"  id="password" placeholder="<?php echo $lang['login_index_password_content']?>">
            <label></label>
          </dd>
        </dl>
        <?php if(C('captcha_status_login') == '1') { ?>
        <dl>
          <dt><?php echo $lang['login_index_checkcode'];?></dt>
          <dd style="min-height:54px;">
            <input type="text" name="captcha" autocomplete="off" class="text w50 fl" id="captcha" maxlength="4" size="10" />
            <img src="<?php echo SHOP_SITE_URL?>/index.php?gct=seccode&gp=makecode&nchash=<?php echo getNchash();?>" name="codeimage" border="0" id="codeimage" class="fl ml5"> <a href="javascript:void(0)" class="ml5" onclick="javascript:document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?gct=seccode&gp=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random();"><?php echo $lang['login_index_change_checkcode'];?></a>
            <label></label>
          </dd>
        </dl>
        <?php } ?>
        <dl>
          <dt>&nbsp;</dt>
          <dd>
            <input type="submit" class="submit" value="<?php echo $lang['login_index_login'];?>">
            <a class="forget" href="index.php?gct=login&gp=forget_password"><?php echo $lang['login_index_forget_password'];?></a>
            <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">
          </dd>
        </dl>
      </form>
      <dl>
        <dd class="nc-login-other">
	   </dd>
      </dl>
    </div>
    <div class="nc-login-bottom"></div>
  </div>
</div>
<script>
$(document).ready(function(){
	$("#login_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.find('label').hide();
            error_td.append(error);
        },
        onkeyup: false,
		rules: {
			user_name: {
				required: true,
				remote: {
                        url: 'index.php?gct=login&gp=check_name',
                        type: 'post',
                        data: {
                            user_name: function() {
                                return $('#user_name').val();
                            }
                        }
				}
			},
			password: {
				required: true,
				remote: {
                        url: 'index.php?gct=login&gp=check_password',
                        type: 'post',
                        data: {
							user_name: function() {
                                return $('#user_name').val();
                            },
                            password: function() {
								
                                return $('#password').val();
                            }
                        }
				}
			},
			<?php if(C('captcha_status_login') == '1') { ?>
            captcha : {
                required : true,
                remote   : {
                    url : '<?php echo SHOP_SITE_URL?>/index.php?gct=seccode&gp=check&nchash=<?php echo getNchash();?>',
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
            }
			<?php } ?>
		},
		messages: {
			user_name: {
				required : '<?php echo $lang['login_index_input_username'];?>',
				remote	 : '<?php echo $lang['login_index_username_error'];?>'
			},
			
			password: {
				required : '<?php echo $lang['login_index_input_password'];?>',
				remote	 : '<?php echo $lang['login_index_passwd_error'];?>'
			},
			
			<?php if(C('captcha_status_login') == '1') { ?>
            captcha : {
                required : '<?php echo $lang['login_index_input_checkcode'];?>',
				remote	 : '<?php echo $lang['login_index_wrong_checkcode'];?>'
            }
			<?php } ?>
		}
	});
});
</script>
