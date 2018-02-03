<?php defined('GcWebShop') or exit('Access Invalid!');?>
<style>
	.rowform{width:100%;}
	.label{float: left;margin-right:10px;}
	.label-l{float: left;margin: 0 10px 0 20px;}
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['web_set'];?></h3>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" id="settingForm" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
		<tr class="space">
			<th colspan="16"><?php echo '购买方式开关'; ?>:</th>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff">
		    <label class="required label"><?php echo $lang['pc_buy_switch'];?>:</label>
			<label for="pc_buy_enable" class="cb-enable <?php if($output['list_setting']['pc_buy'] == '1'){ ?>selected<?php } ?>" ><span><?php echo $lang['open'];?></span></label>
            <label for="pc_buy_disabled" class="cb-disable <?php if($output['list_setting']['pc_buy'] == '0'){ ?>selected<?php } ?>" ><span><?php echo $lang['close'];?></span></label>
            <input id="pc_buy_enable" name="pc_buy" <?php if($output['list_setting']['pc_buy'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="pc_buy_disabled" name="pc_buy" <?php if($output['list_setting']['pc_buy'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
			
			<label class="required label-l"><?php echo $lang['pc_cart_switch'];?>:</label>
			<label for="pc_cart_enable" class="cb-enable <?php if($output['list_setting']['pc_cart'] == '1'){ ?>selected<?php } ?>" ><span><?php echo $lang['open'];?></span></label>
            <label for="pc_cart_disabled" class="cb-disable <?php if($output['list_setting']['pc_cart'] == '0'){ ?>selected<?php } ?>" ><span><?php echo $lang['close'];?></span></label>
            <input id="pc_cart_enable" name="pc_cart" <?php if($output['list_setting']['pc_cart'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="pc_cart_disabled" name="pc_cart" <?php if($output['list_setting']['pc_cart'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
			
			<!--label class="required label-l"><?php echo $lang['wap_buy_switch'];?>:</label>
			<label for="wap_buy_enable" class="cb-enable <?php if($output['list_setting']['wap_buy'] == '1'){ ?>selected<?php } ?>" ><span><?php echo $lang['open'];?></span></label>
            <label for="wap_buy_disabled" class="cb-disable <?php if($output['list_setting']['wap_buy'] == '0'){ ?>selected<?php } ?>" ><span><?php echo $lang['close'];?></span></label>
            <input id="wap_buy_enable" name="wap_buy" <?php if($output['list_setting']['wap_buy'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="wap_buy_disabled" name="wap_buy" <?php if($output['list_setting']['wap_buy'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
			
			<label class="required label-l"><?php echo $lang['wap_cart_switch'];?>:</label>
			<label for="wap_cart_enable" class="cb-enable <?php if($output['list_setting']['wap_cart'] == '1'){ ?>selected<?php } ?>" ><span><?php echo $lang['open'];?></span></label>
            <label for="wap_cart_disabled" class="cb-disable <?php if($output['list_setting']['wap_cart'] == '0'){ ?>selected<?php } ?>" ><span><?php echo $lang['close'];?></span></label>
            <input id="wap_cart_enable" name="wap_cart" <?php if($output['list_setting']['wap_cart'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="wap_cart_disabled" name="wap_cart" <?php if($output['list_setting']['wap_cart'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio"-->
		  </td>
        </tr>
		
		<!--tr class="space">
			<th colspan="16"><?php echo '注册方式开关'; ?>:</th>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff">
		    <label class="required label"><?php echo $lang['wap_register_way'];?>:</label>
		    <label for="register_way_enable" class="cb-enable <?php if($output['list_setting']['register_way'] == '1'){ ?>selected<?php } ?>" ><span><?php echo $lang['open'];?></span></label>
            <label for="register_way_disabled" class="cb-disable <?php if($output['list_setting']['register_way'] == '0'){ ?>selected<?php } ?>" ><span><?php echo $lang['close'];?></span></label>
            <input id="register_way_enable" name="register_way" <?php if($output['list_setting']['register_way'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="register_way_disabled" name="register_way" <?php if($output['list_setting']['register_way'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
		  </td>
        </tr>
		
		<tr class="space">
			<th colspan="16"><?php echo '微信端登录开关'; ?>:</th>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff">
		    <label class="required label"><?php echo '是否开启微信端普通账号登录';?>:</label>
		    <label for="weixin_logon_enable" class="cb-enable <?php if($output['list_setting']['weixin_logon'] == '1'){ ?>selected<?php } ?>" ><span><?php echo $lang['open'];?></span></label>
            <label for="weixin_logon_disabled" class="cb-disable <?php if($output['list_setting']['weixin_logon'] == '0'){ ?>selected<?php } ?>" ><span><?php echo $lang['close'];?></span></label>
            <input id="weixin_logon_enable" name="weixin_logon" <?php if($output['list_setting']['weixin_logon'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="weixin_logon_disabled" name="weixin_logon" <?php if($output['list_setting']['weixin_logon'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
		  </td>
        </tr-->
		
		<tr class="space">
			<th colspan="16"><?php echo '库存显示开关'; ?>:</th>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff">
		    <label class="required label"><?php echo '是否开启商品库存的显示';?>:</label>
		    <label for="is_storage_enable" class="cb-enable <?php if($output['list_setting']['is_storage'] == '1'){ ?>selected<?php } ?>" ><span><?php echo $lang['open'];?></span></label>
            <label for="is_storage_disabled" class="cb-disable <?php if($output['list_setting']['is_storage'] == '0'){ ?>selected<?php } ?>" ><span><?php echo $lang['close'];?></span></label>
            <input id="is_storage_enable" name="is_storage" <?php if($output['list_setting']['is_storage'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="is_storage_disabled" name="is_storage" <?php if($output['list_setting']['is_storage'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
		  </td>
        </tr>
		
		<!--tr class="space">
			<th colspan="16"><?php echo '销售员开通方式开关'; ?>:</th>
        </tr>
		<tr class="noborder">
		  <td><label class="required label"><?php echo '购买指定商品成为光彩商品级销售员';?>:</label>商品ID：<input type="text" value="<?php echo $output['list_setting']['seller_goods']; ?>" name="seller_goods" > 如果多个商品ID用英文的逗号隔开，中间不能包含空格</td>
        </tr-->
		
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.settingForm.submit()"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
