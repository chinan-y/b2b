<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <?php if ($output['setting_config']['wechat_isuse'] == 1 || $output['setting_config']['wechatpc_isuse']){?>
  <div class="ncm-bind">
    <?php if (!empty($output['member_info']['member_wechatopenid']) || !empty($output['member_info']['member_wechatpcopenid'])){?>
    <div class="alert">
      <h4>提示信息：</h4>
      <ul>
        <li>您已将本站账号<em>“<?php echo $_SESSION['member_name'];?>”与微信昵称<em>“<?php echo $output['member_info']['member_wechatinfoarr']['nickname'];?>”</em><?php echo $lang['member_qqconnect_binding_tip_3'];?></li>
        <li><?php echo $lang['member_qqconnect_modpw_tip_1']; ?><em>“<?php echo $_SESSION['member_name']; ?>”</em><?php echo $lang['member_qqconnect_modpw_tip_2'];?></li>
      </ul>
    </div>
    <input type="hidden" name="form_submit" value="ok"  />
    <div class="relieve">
      <form method="post" id="editbind_form" name="editbind_form" action="index.php?gct=member_connect&gp=wechatunbind">
        <input type='hidden' id="is_editpw" name="is_editpw" value='no'/>
        <div class="ico-qq" style="background: url(<?php echo SHOP_SITE_URL;?>/ectap/wechat/login/resource/images/bind_wechat.png) no-repeat scroll;"></div>
        <p>解除已绑定账号？</p>
        <div class="bottom">
          <label class="submit-border">
            <input class="submit" type="submit" value="确认解除" />
          </label>
        </div>
      </form>
    </div>
    <div class="revise ncm-default-form ">
      <form method="post" id="editpw_form" name="editpw_form" action="index.php?gct=member_connect&gp=wechatunbind">
        <input type='hidden' id="is_editpw" name="is_editpw" value='yes'/>
        <dl>
          <dt>新密码：</dt>
          <dd>
            <input type="password"  name="new_password" id="new_password"/>
            <label for="new_password" generated="true" class="error"></label>
          </dd>
        </dl>
        <dl>
          <dt>确认密码：</dt>
          <dd>
            <input type="password"  name="confirm_password" id="confirm_password" />
            <label for="confirm_password" generated="true" class="error"></label>
          </dd>
        </dl>
        <dl class="bottom">
          <dt></dt>
          <dd>
            <label class="submit-border">
              <input class="submit" type="submit" value="修改密码并解除" />
            </label>
          </dd>
        </dl>
      </form>
    </div>
    <?php } else {?>
    <div class="relieve pt50">
      <p class="ico"><a href="<?php echo SHOP_SITE_URL;?>/ectap/wechat/login/wechat.php?wechatBind=1">绑定微信账号</a>
      <p class="hint">点击按钮，立刻绑定微信账号</p>
    </div>
    <div class="revise pt50">
      <p class="qq">使用微信账号绑定本站，您可以...</p>
      <p>用微信轻松登录</p>
      <p class="hint">无需记住本站的账号和密码，随时使用微信授权登录</p>
    </div>
    <?php }?>
  </div>
  <?php } else {?>
  <div class="warning-option"><i>&nbsp;</i><span>系统未开启微信登录功能</span></div>
  <?php }?>
</div>
<script type="text/javascript">
$(function(){
	$("#unbind").hide();

    $('#editpw_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('td').next('td');
            error_td.find('.field_notice').hide();
            error_td.append(error);
        },
        rules : {
            new_password : {
                required   : true,
                minlength  : 6,
                maxlength  : 20
            },
            confirm_password : {
                required   : true,
                equalTo    : '#new_password'
            }
        },
        messages : {
            new_password  : {
                required   : '<i class="icon-exclamation-sign"></i>新密码不能为空',
                minlength  : '<i class="icon-exclamation-sign"></i>密码长度大于等于6位小于等于20位'
            },
            confirm_password : {
                required   : '<i class="icon-exclamation-sign"></i>确认密码不能为空',
                equalTo    : '<i class="icon-exclamation-sign"></i>新密码与确认密码不相同，请从重新输入'
            }
        }
    });
});
function showunbind(){
	$("#unbind").show();
}
function showpw(){
	$("#is_editpw").val('yes');
	$("#editbinddiv").hide();
	$("#editpwul").show();
}
</script>
