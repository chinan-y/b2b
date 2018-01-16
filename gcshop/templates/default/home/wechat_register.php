<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_login.css" rel="stylesheet" type="text/css">
<style type="text/css">
.head-search-bar, .head-user-menu, .public-nav-layout  {
	display: none !important;
} /*屏蔽头部搜索及导航菜单*/
</style>
<div class="nc-login-layout">
  <div class="left-pic"><img src="<?php echo SHOP_SITE_URL;?>/ectap/wechat/login/resource/images/login_wechat.jpg" />
    <p><a href="#register_form"><?php echo $output['qquser_info']['nickname']; ?></a></p>
  </div>
  <div class="nc-login" id="rotate">
    <ul>
      <li class="w400"><a href="#register_form">完善账号信息<!-- 完善账号信息 --></a></li>
    </ul>
    <div class="nc-login-content">
      <form name="register_form" id="register_form" method="post" action="index.php?gct=wechat&gp=register">
        <input type="hidden" value="ok" name="form_submit">
        <input type='hidden' name="loginnickname" value="<?php echo $output['wechat_user_info']['nickname'];?>"/>
        <dl>
          <dt><img style="width: 78px; height: 78px;" src="<?php echo $output['wechat_user_info']['headimgurl'];?>" /></dt>
          <dd>
            <label>注册成功，也可以使用下面的账号登录，建议重新设置密码并完善邮箱</label>
          </dd>
        </dl>
        <dl class="mt20">
          <dt>用户名: </dt>
          <dd>
            <label><?php echo $_SESSION['member_name'];?></label>
          </dd>
        </dl>
        <dl>
          <dt>登陆密码: </dt>
          <dd>
            <input type="text" value="<?php echo $output['user_passwd'];?>" id="password" name="password" class="text tip" title="您的登陆密码"/>
            <label></label>
          </dd>
        </dl>
        <dl>
          <dt>邮箱: </dt>
          <dd>
            <input type="text" id="email" name="email" class="text tip" title="请输入常用的邮箱，将用来找回密码、接受订单通知等"/>
            <label></label>
          </dd>
        </dl>
        <dl>
          <dt>&nbsp;</dt>
          <dd>
            <input type="submit" name="submit" value="确认提交" class="submit fl"/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="index.php">以后再说，返回首页</a></span>
            <label></label>
          </dd>
        </dl>
      </form>
      <div class="clear"></div>
    </div>
    <div class="nc-login-bottom"></div>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script> 
<script type="text/javascript">
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
    $(function() {
        $('#register_form').validate({
            errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.find('label').hide();
            error_td.append(error);
        },
            rules: {
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                },
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: 'index.php?gct=login&gp=check_email',
                        type: 'get',
                        data: {
                            email: function() {
                                return $('#email').val();
                            }
                        }
                    }
                }
        },
        messages : {
            password  : {
                required : '密码不能为空',
                minlength: '密码长度应在6-20个字符之间',
				maxlength: '密码长度应在6-20个字符之间'
            },
            email : {
                required : '电子邮箱不能为空',
                email    : '这不是一个有效的电子邮箱',
				remote	 : '该电子邮箱已经存在'
            }
        }
    });
});
</script>
<?php echo $output['nc_synlogin_script'];?>