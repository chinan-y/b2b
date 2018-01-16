<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>合作平台管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?gct=partner&gp=index"><span>合作平台列表</span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>合作平台增加</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="partner_form" method="post" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation">合作平台名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" name="partner_name" id="partner_name" class="txt">
          </td>
          <td class="vatop tips">合作平台名称</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">合作类型:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><select id="type" name="type">
              <option value="0">0三方交易|光彩支付</option>
              <option value="1">1三方交易|三方支付</option>
            </select></td>
          <td class="vatop tips">在第三方交易平台下单交易，在[光彩/三方]平台上完成支付。</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">appid:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="appid" name="appid" class="txt" placeholder="不可编辑，自动生成" disabled></td>
		  <td class="vatop tips">appid值</td>
        </tr>
		<tr>
          <td colspan="2" class="required"><label class="validation">appkey:</label></td>
        </tr>
		<tr class="noborder">
          <td class="vatop rowform"><input type="text" id="appkey" name="appkey" class="txt" value="<?php echo MD5(TIME()); ?>"></td>
		  <td class="vatop tips">当前时间的MD5加密字符串</td>
        </tr>
		
		<tr>
          <td colspan="2" class="required"><label>显式通知地址:</label></td>
        </tr>
		<tr class="noborder">
          <td class="vatop rowform"><input type="text" id="notify_url" name="notify_url" class="txt"></td>
		  <td class="vatop tips">显式通知地址</td>
        </tr>
		
		<tr>
          <td colspan="2" class="required"><label>隐式通知地址:</label></td>
        </tr>
		<tr class="noborder">
          <td class="vatop rowform"><input type="text" id="notify_url_1" name="notify_url_1" class="txt"></td>
		  <td class="vatop tips">隐式通知地址</td>
        </tr>
		
		<tr>
          <td colspan="2" ><label>注册会员ID:</label></td>
        </tr>
		<tr class="noborder">
          <td class="vatop rowform"><input type="text" id="member_id" name="member_id" class="txt"></td>
		  <td class="vatop tips">注册会员ID，如实填写</td>
        </tr>

      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.form1.submit()"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript">
$(function(){
	$("#tr_memberinfo").hide();
	
    $('#partner_form').validate({
//        errorPlacement: function(error, element){
//            $(element).next('.field_notice').hide();
//            $(element).after(error);
//        },
        rules : {
        	partner_name: {
				required : true
			},
            appkey   : {
                required : true
            }
        },
        messages : {
			partner_name: {
				required : '必填'
			},
            appkey  : {
                required : '必填'
            }
        }
    });
});
</script>