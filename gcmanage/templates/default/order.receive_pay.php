<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['order_manage'];?></h3>
      <ul class="tab-base">
        <li><a href="index.php?gct=<?php echo $_GET['gct'];?>&gp=index"><span><?php echo $lang['manage'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>确认收款</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" name="form1" id="form1" action="index.php?gct=<?php echo $_GET['gct'];?>&gp=change_state&state_type=receive_pay&order_id=<?php echo intval($_GET['order_id']);?>">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" value="<?php echo getReferer();?>" name="ref_url">
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="site_name">订单编号<?php echo $lang['nc_colon'];?> </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['order_info']['order_sn'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <?php if ($_GET['gct'] == 'order') { ?>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="site_name">支付单号<?php echo $lang['nc_colon'];?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['order_info']['pay_sn'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <?php } ?>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="site_name">订单总金额 <?php echo $lang['nc_colon'];?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['order_info']['order_amount'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="site_name">付款时间<?php echo $lang['nc_colon'];?></label></td>
        </tr>
        <tr class="noborder">
          
          <td class="vatop rowform"><input type="text" name="datetime" id="payment_time" class="payment_time" value=""></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="site_name">付款方式 <?php echo $lang['nc_colon'];?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <select name="payment_code" class="querySelect">
            <option value=""><?php echo $lang['nc_please_choose'];?></option>
			<option value="wxpai">微信支付</option>
            <?php foreach($output['payment_list'] as $val) { ?>
            <option value="<?php echo $val['payment_code']; ?>"><?php echo $val['payment_name']; ?></option>
            <?php } ?>
            </select>
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="closed_reason">第三方支付平台交易号<?php echo $lang['nc_colon'];?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" class="txt2" name="trade_no" id="trade_no" maxlength="40"></td>
          <td class="vatop tips"><span class="vatop rowform">支付宝等第三方支付平台交易号</span></td>
        </tr>
      </tbody>
      <tfoot id="submit-holder">
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" id="ncsubmit" class="btn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="http://www.qqbsmall.com/data/resource/js/jquery-ui/jquery.ui.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui-timepicker-addon/jquery-ui-timepicker-addon.min.js"></script>

<link type="text/css" href="http://www.qqbsmall.com/data/resource/js/jq_time/css/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
<link type="text/css" href="http://www.qqbsmall.com/data/resource/js/jq_time/css/jquery-ui-timepicker-addon.css" rel="stylesheet" />
<script type="text/javascript" src="http://www.qqbsmall.com/data/resource/js/jq_time/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="http://www.qqbsmall.com/data/resource/js/jq_time/js/jquery-ui-1.8.17.custom.min.js"></script>
<script type="text/javascript" src="http://www.qqbsmall.com/data/resource/js/jq_time/js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="http://www.qqbsmall.com/data/resource/js/jq_time/js/jquery-ui-timepicker-zh-CN.js"></script>

<script type="text/javascript">
$(function(){
        $(".payment_time").datetimepicker({
            //showOn: "button",
            //buttonImage: "./css/images/icon_calendar.gif",
            //buttonImageOnly: true,
            showSecond: true,
            timeFormat: 'hh:mm:ss',
            dateFormat: 'yy-mm-dd',
            maxDate: '<?php echo date('Y-m-d',TIMESTAMP);?>',
            stepHour: 1,
            stepMinute: 1,
            stepSecond: 1
        })


    $('#ncsubmit').click(function(){
        var payment_time = $("#payment_time").val();
        var payment_code = $(".querySelect").val();
        var trade_no = $("#trade_no").val();
        if(payment_time =""){
            alert("请填写付款准确时间");
            return false;
        }
        if(payment_code =""){
            alert("请选择付款方式");
            return false;
        }
        if(trade_no =""){
            alert("请填写第三方支付平台交易号");
            return false;
        }
    
        	if (confirm("操作提醒：\n该操作不可撤销\n提交前请务必确认是否已收到付款\n继续操作吗?")){
        	}else{
        		return false;
        	}
        	$('#form1').submit();

        	
    });

});
</script> 