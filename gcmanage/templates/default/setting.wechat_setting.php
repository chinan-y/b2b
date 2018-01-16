<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['account_syn'];?></h3>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label>是否启用微信登陆功能:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="wechat_isuse_1" class="cb-enable <?php if($output['list_setting']['wechat_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['qq_isuse_open'];?>"><span><?php echo $lang['qq_isuse_open'];?></span></label>
            <label for="wechat_isuse_0" class="cb-disable <?php if($output['list_setting']['wechat_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['qq_isuse_close'];?>"><span><?php echo $lang['qq_isuse_close'];?></span></label>
            <input type="radio" id="wechat_isuse_1" name="wechat_isuse" value="1" <?php echo $output['list_setting']['wechat_isuse']==1?'checked=checked':''; ?>>
            <input type="radio" id="wechat_isuse_0" name="wechat_isuse" value="0" <?php echo $output['list_setting']['wechat_isuse']==0?'checked=checked':''; ?>></td>
          <td class="vatop tips">开启后可使用微信登录商城系统</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="wechat_appcode">域名验证信息:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><textarea name="wechat_appcode" rows="6" class="tarea" id="wechat_appcode"><?php echo $output['list_setting']['wechat_appcode'];?></textarea></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="wechat_appid">AppID:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="wechat_appid" name="wechat_appid" value="<?php echo $output['list_setting']['wechat_appid'];?>" class="txt" type="text">
            </td>
          <td class="vatop tips"><a style="color:#ffffff; font-weight:bold;" target="_blank" href="https://mp.weixin.qq.com">立即在线申请</a></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="wechat_appkey">AppSecret:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="wechat_appkey" name="wechat_appkey" value="<?php echo $output['list_setting']['wechat_appkey'];?>" class="txt" type="text"></td>
          <td class="vatop tips">&nbsp;</td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.settingForm.submit()"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
