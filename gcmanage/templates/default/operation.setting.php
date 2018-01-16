<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['nc_operation_set']?></h3>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" name="settingForm" id="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
		<!-- 销售返利开启 -->
        <tr class="noborder">
          <td colspan="2" class="required"><label><?php echo $lang['operate_sell_rebate'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="salescredit_isuse_1" class="cb-enable <?php if($output['list_setting']['salescredit_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['gold_isuse_open'];?>"><span><?php echo $lang['points_isuse_open'];?></span></label>
            <label for="salescredit_isuse_0" class="cb-disable <?php if($output['list_setting']['salescredit_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['gold_isuse_close'];?>"><span><?php echo $lang['points_isuse_close'];?></span></label>
            <input type="radio" id="salescredit_isuse_1" name="salescredit_isuse" value="1" <?php echo $output['list_setting']['salescredit_isuse'] ==1?'checked=checked':''; ?>>
            <input type="radio" id="salescredit_isuse_0" name="salescredit_isuse" value="0" <?php echo $output['list_setting']['salescredit_isuse'] ==0?'checked=checked':''; ?>>
            <label style="padding-left:15px; font-weight:600;"><?php echo $lang['operate_consume_rebate'];?>:</label>
            <input id="salescredit_comments" name="salescredit_rebate" value="<?php echo $output['list_setting']['salescredit_rebate'];?>" class="txt" type="text" style="width:35px;">%
          <td class="vatop tips"><?php echo $lang['operate_sell_rebate_memo'];?></td>
        </tr>
		
		<!-- 一级返利 -->
		<tr class="noborder">
          <td colspan="2" class="required"><label><?php echo $lang['operate_onerank_rebate'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="one_rank_rebate_1" class="cb-enable <?php if($output['list_setting']['one_rank_rebate'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['gold_isuse_open'];?>"><span><?php echo $lang['points_isuse_open'];?></span></label>
            <label for="one_rank_rebate_0" class="cb-disable <?php if($output['list_setting']['one_rank_rebate'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['gold_isuse_close'];?>"><span><?php echo $lang['points_isuse_close'];?></span></label>
            <input type="radio" id="one_rank_rebate_1" name="one_rank_rebate" value="1" <?php echo $output['list_setting']['one_rank_rebate'] ==1?'checked=checked':''; ?>>
            <input type="radio" id="one_rank_rebate_0" name="one_rank_rebate" value="0" <?php echo $output['list_setting']['one_rank_rebate'] ==0?'checked=checked':''; ?>>
            <label style="padding-left:15px; font-weight:600;"><?php echo $lang['operate_rebate_rate'];?>:</label>
            <input id="salescredit_comments" name="one_rebate_rate" value="<?php echo $output['list_setting']['one_rebate_rate'];?>" class="txt" type="text" style="width:35px;">%
          <td class="vatop tips"><?php echo $lang['operate_onerank_rebate_memo'];?></td>
        </tr>
		
		<!-- 二级返利 -->
		<tr class="noborder">
          <td colspan="2" class="required"><label><?php echo $lang['operate_tworank_rebate'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="two_rank_rebate_1" class="cb-enable <?php if($output['list_setting']['two_rank_rebate'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['gold_isuse_open'];?>"><span><?php echo $lang['points_isuse_open'];?></span></label>
            <label for="two_rank_rebate_0" class="cb-disable <?php if($output['list_setting']['two_rank_rebate'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['gold_isuse_close'];?>"><span><?php echo $lang['points_isuse_close'];?></span></label>
            <input type="radio" id="two_rank_rebate_1" name="two_rank_rebate" value="1" <?php echo $output['list_setting']['two_rank_rebate'] ==1?'checked=checked':''; ?>>
            <input type="radio" id="two_rank_rebate_0" name="two_rank_rebate" value="0" <?php echo $output['list_setting']['two_rank_rebate'] ==0?'checked=checked':''; ?>>
            <label style="padding-left:15px; font-weight:600;"><?php echo $lang['operate_rebate_rate'];?>:</label>
            <input id="salescredit_comments" name="two_rebate_rate" value="<?php echo $output['list_setting']['two_rebate_rate'];?>" class="txt" type="text" style="width:35px;">%
          <td class="vatop tips"><?php echo $lang['operate_tworank_rebate_memo'];?></td>
        </tr>
		
		<!-- 三级返利 -->
		<tr class="noborder">
          <td colspan="2" class="required"><label><?php echo $lang['operate_threerank_rebate'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="three_rank_rebate_1" class="cb-enable <?php if($output['list_setting']['three_rank_rebate'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['gold_isuse_open'];?>"><span><?php echo $lang['points_isuse_open'];?></span></label>
            <label for="three_rank_rebate_0" class="cb-disable <?php if($output['list_setting']['three_rank_rebate'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['gold_isuse_close'];?>"><span><?php echo $lang['points_isuse_close'];?></span></label>
            <input type="radio" id="three_rank_rebate_1" name="three_rank_rebate" value="1" <?php echo $output['list_setting']['three_rank_rebate'] ==1?'checked=checked':''; ?>>
            <input type="radio" id="three_rank_rebate_0" name="three_rank_rebate" value="0" <?php echo $output['list_setting']['three_rank_rebate'] ==0?'checked=checked':''; ?>>
            <label style="padding-left:15px; font-weight:600;"><?php echo $lang['operate_rebate_rate'];?>:</label>
            <input id="salescredit_comments" name="three_rebate_rate" value="<?php echo $output['list_setting']['three_rebate_rate'];?>" class="txt" type="text" style="width:35px;">%
          <td class="vatop tips"><?php echo $lang['operate_threerank_rebate_memo'];?></td>
        </tr>
	  
        <!-- 促销开启 -->
        <tr>
          <td colspan="2" class="required"><label><?php echo $lang['promotion_allow'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="promotion_allow_1" class="cb-enable <?php if($output['list_setting']['promotion_allow'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><span><?php echo $lang['open'];?></span></label>
            <label for="promotion_allow_0" class="cb-disable <?php if($output['list_setting']['promotion_allow'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><span><?php echo $lang['close'];?></span></label>
            <input type="radio" id="promotion_allow_1" name="promotion_allow" value="1" <?php echo $output['list_setting']['promotion_allow'] ==1?'checked=checked':''; ?>>
            <input type="radio" id="promotion_allow_0" name="promotion_allow" value="0" <?php echo $output['list_setting']['promotion_allow'] ==0?'checked=checked':''; ?>>
          <td class="vatop tips"><?php echo $lang['promotion_notice'];?></td>
        </tr>
		
		
        <tr>
          <td colspan="2" class="required"><?php echo $lang['groupbuy_allow'];?>: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="groupbuy_allow_1" class="cb-enable <?php if($output['list_setting']['groupbuy_allow'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><span><?php echo $lang['open'];?></span></label>
            <label for="groupbuy_allow_0" class="cb-disable <?php if($output['list_setting']['groupbuy_allow'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><span><?php echo $lang['close'];?></span></label>
            <input id="groupbuy_allow_1" name="groupbuy_allow" <?php if($output['list_setting']['groupbuy_allow'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="groupbuy_allow_0" name="groupbuy_allow" <?php if($output['list_setting']['groupbuy_allow'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio"></td>
          <td class="vatop tips"><?php echo $lang['groupbuy_isuse_notice'];?></td>
        </tr>
		
		<!--tr>
          <td colspan="2" class="required"><?php echo $lang['tuangou_allow'];?>: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="tuangou_allow_1" class="cb-enable <?php if($output['list_setting']['tuangou_allow'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><span><?php echo $lang['open'];?></span></label>
            <label for="tuangou_allow_0" class="cb-disable <?php if($output['list_setting']['tuangou_allow'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><span><?php echo $lang['close'];?></span></label>
            <input id="tuangou_allow_1" name="tuangou_allow" <?php if($output['list_setting']['tuangou_allow'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="tuangou_allow_0" name="tuangou_allow" <?php if($output['list_setting']['tuangou_allow'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio"></td>
          <td class="vatop tips"><?php echo $lang['tuangou_isuse_notice'];?></td>
        </tr-->

        <tr>
          <td colspan="2" class="required"><label><?php echo $lang['points_isuse'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="points_isuse_1" class="cb-enable <?php if($output['list_setting']['points_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['gold_isuse_open'];?>"><span><?php echo $lang['points_isuse_open'];?></span></label>
            <label for="points_isuse_0" class="cb-disable <?php if($output['list_setting']['points_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['gold_isuse_close'];?>"><span><?php echo $lang['points_isuse_close'];?></span></label>
            <input type="radio" id="points_isuse_1" name="points_isuse" value="1" <?php echo $output['list_setting']['points_isuse'] ==1?'checked=checked':''; ?>>
            <input type="radio" id="points_isuse_0" name="points_isuse" value="0" <?php echo $output['list_setting']['points_isuse'] ==0?'checked=checked':''; ?>>
          <td class="vatop tips"><?php echo $lang['points_isuse_notice'];?></td>
        </tr>
		
		
        <tr>
          <td colspan="2" class="required"><?php echo $lang['open_pointshop_isuse'];?>: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="pointshop_isuse_1" class="cb-enable <?php if($output['list_setting']['pointshop_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_open'];?>"><span><?php echo $lang['nc_open'];?></span></label>
            <label for="pointshop_isuse_0" class="cb-disable <?php if($output['list_setting']['pointshop_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_close'];?>"><span><?php echo $lang['nc_close'];?></span></label>
            <input id="pointshop_isuse_1" name="pointshop_isuse" <?php if($output['list_setting']['pointshop_isuse'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="pointshop_isuse_0" name="pointshop_isuse" <?php if($output['list_setting']['pointshop_isuse'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio"></td>
          <td class="vatop tips"><?php echo sprintf($lang['open_pointshop_isuse_notice'],"index.php?gct=setting&gp=pointshop_setting");?></td>
        </tr>
		
		
        <tr>
          <td colspan="2" class="required"><?php echo $lang['open_pointprod_isuse'];?>: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="pointprod_isuse_1" class="cb-enable <?php if($output['list_setting']['pointprod_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><span><?php echo $lang['open'];?></span></label>
            <label for="pointprod_isuse_0" class="cb-disable <?php if($output['list_setting']['pointprod_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><span><?php echo $lang['close'];?></span></label>
            <input id="pointprod_isuse_1" name="pointprod_isuse" <?php if($output['list_setting']['pointprod_isuse'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="pointprod_isuse_0" name="pointprod_isuse" <?php if($output['list_setting']['pointprod_isuse'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio"></td>
          <td class="vatop tips"><?php echo $lang['open_pointprod_isuse_notice'];?></td>
        </tr>
		
        <tr>
          <td colspan="2" class="required"><?php echo $lang['voucher_allow'];?>: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="voucher_allow_1" class="cb-enable <?php if($output['list_setting']['voucher_allow'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><span><?php echo $lang['open'];?></span></label>
            <label for="voucher_allow_0" class="cb-disable <?php if($output['list_setting']['voucher_allow'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><span><?php echo $lang['close'];?></span></label>
            <input id="voucher_allow_1" name="voucher_allow" <?php if($output['list_setting']['voucher_allow'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="voucher_allow_0" name="voucher_allow" <?php if($output['list_setting']['voucher_allow'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio"></td>
          <td class="vatop tips"><?php echo $lang['voucher_allow_notice'];?></td>
        </tr>
		
		<tr>
          <td colspan="2" class="required">贵州银行支付折扣设置: </td>
        </tr>
		<tr class="noborder">
          <td class="vatop rowform onoff"><label for="GZ_RETURN_allow_1" class="cb-enable <?php if($output['list_setting']['GZ_RETURN'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><span><?php echo $lang['open'];?></span></label>
            <label for="GZ_RETURN_allow_0" class="cb-disable <?php if($output['list_setting']['GZ_RETURN'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><span><?php echo $lang['close'];?></span></label>
            <input id="GZ_RETURN_allow_1" name="GZ_RETURN_allow" <?php if($output['list_setting']['GZ_RETURN'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="GZ_RETURN_allow_0" name="GZ_RETURN_allow" <?php if($output['list_setting']['GZ_RETURN'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
			<label style="padding-left:15px; font-weight:600;">活动折扣:</label>
			<input id="GZ_RETURN_allow" name="GZ_RETURN_discount" value="<?php echo $output['list_setting']['GZ_RETURN_discount'];?>" class="txt" type="text" style="width:35px;">%
		  </td>
			<td class="vatop tips"><?php echo $lang['GZ_ZHEKOU'];?></td>
        </tr>

        <tr>
          <td class="" colspan="2"><table class="table tb-type2 nomargin">
              <thead>
                <tr class="space">
                  <th colspan="16"><?php echo $lang['points_ruletip']; ?>:</th>
                </tr>
                <tr class="thead">
                  <th><?php echo $lang['points_item']; ?></th>
                  <th><?php echo $lang['points_number']; ?></th>
                </tr>
              </thead>
              <tbody>
                <tr class="hover">
                  <td class="w200"><?php echo $lang['points_number_reg']; ?></td>
                  <td><input id="points_reg" name="points_reg" value="<?php echo $output['list_setting']['points_reg'];?>" class="txt" type="text" style="width:60px;"></td>
                </tr>
				<tr class="hover">
                  <td class="w200"><?php echo $lang['points_number_sign']; ?></td>
                  <td><input id="points_sign" name="points_sign" value="<?php echo $output['list_setting']['points_sign'];?>" class="txt" type="text" style="width:60px;"></td>
                </tr>
                <tr class="hover">
                  <td><?php echo $lang['points_number_login'];?></td>
                  <td><input id="points_login" name="points_login" value="<?php echo $output['list_setting']['points_login'];?>" class="txt" type="text" style="width:60px;"></td>
                </tr>
                <tr class="hover">
                  <td><?php echo $lang['points_number_comments']; ?></td>
                  <td><input id="points_comments" name="points_comments" value="<?php echo $output['list_setting']['points_comments'];?>" class="txt" type="text" style="width:60px;"></td>
                </tr>
                 <tr class="hover">
                  <td>邀请注册</td>
                  <td><input id="points_comments" name="points_invite" value="<?php echo $output['list_setting']['points_invite'];?>" class="txt" type="text" style="width:60px;">邀请非会员注册时给邀请人的积分数</td>
                </tr>
				  <tr class="hover">
                  <td>返利比例</td>
                  <td><input id="points_comments" name="points_rebate" value="<?php echo $output['list_setting']['points_rebate'];?>" class="txt" type="text" style="width:35px;">% &nbsp;&nbsp;&nbsp;被邀请会员购买商品时给邀请人返的积分数(例如设为5%，被邀请人购买100元商品，返给邀请人5积分)</td>
                </tr>
              </tbody>
            </table>
            <table class="table tb-type2 nomargin">
              <thead>
                <tr class="thead">
                  <th colspan="2"><?php echo $lang['points_number_order']; ?></th>
                </tr>
              </thead>
            <tbody>
                <tr class="hover">
                  <td class="w200"><?php echo $lang['points_number_orderrate'];?></td>
                  <td><input id="points_orderrate" name="points_orderrate" value="<?php echo $output['list_setting']['points_orderrate'];?>" class="txt" type="text" style="width:60px;">
                    <?php echo $lang['points_number_orderrate_tip']; ?></td>
                </tr>
                <tr class="hover">
                  <td><?php echo $lang['points_number_ordermax']; ?></td>
                  <td><input id="points_ordermax" name="points_ordermax" value="<?php echo $output['list_setting']['points_ordermax'];?>" class="txt" type="text" style="width:60px;">
                    <?php echo $lang['points_number_ordermax_tip'];?></td>
                </tr>
			</tbody>
	  
			<thead>
				<tr class="thead">
				  <th colspan="2"><?php echo $lang['points_number_browse']; ?></th>
				</tr>
			</thead>
			<tbody>
				<tr class="hover">
				  <td class="w200"><?php echo $lang['points_number_browse_goods'];?></td>
				  <td><input id="browse_goods" name="browse_goods" value="<?php echo $output['list_setting']['browse_goods'];?>" class="txt" type="text" style="width:60px;">
					<?php echo $lang['points_number_browse_goods_one']; ?></td>
				</tr>
				<tr class="hover">
				  <td><?php echo $lang['points_number_browse_goods_day']; ?></td>
				  <td><input id="browse_goods_up" name="browse_goods_up" value="<?php echo $output['list_setting']['browse_goods_up'];?>" class="txt" type="text" style="width:60px;">
					<?php echo $lang['points_number_browse_goods_up'];?></td>
				</tr>
		  </tbody>
		  
		  <thead>
				<tr class="thead">
				  <th colspan="2"><?php echo '消费比例'; ?></th>
				</tr>
			</thead>
			<tbody>
				<tr class="noborder">
				  <td class="vatop rowform onoff"><label for="exchange_isuse_1" class="cb-enable <?php if($output['list_setting']['exchange_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_open'];?>"><span><?php echo $lang['nc_open'];?></span></label>
					<label for="exchange_isuse_0" class="cb-disable <?php if($output['list_setting']['exchange_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_close'];?>"><span><?php echo $lang['nc_close'];?></span></label>
					<input id="exchange_isuse_1" name="exchange_isuse" <?php if($output['list_setting']['exchange_isuse'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
					<input id="exchange_isuse_0" name="exchange_isuse" <?php if($output['list_setting']['exchange_isuse'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio"></td>
				  <td class="vatop tips"><?php echo '是否打开积分订单抵现支付';?></td>
				</tr>
				<tr class="hover">
				  <td class="w200"><?php echo '与人民币的兑换比例';?></td>
				  <td><input id="exchange_rate" name="exchange_rate" value="<?php echo $output['list_setting']['exchange_rate'];?>" class="txt" type="text" style="width:60px;">
					<?php echo '例:设置为100，表明100积分兑换为1元人民币的比例'; ?></td>
				</tr>
		  </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>

$(function(){$("#submitBtn").click(function(){
    if($("#settingForm").valid()){
     $("#settingForm").submit();
	}
	});
});
//
$(document).ready(function(){
	$("#settingForm").validate({
		errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
        },
        messages : {
        }
	});
});
</script>
