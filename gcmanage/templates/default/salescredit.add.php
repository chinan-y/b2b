<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['nc_member_salescreditmanage']?></h3>
      <ul class="tab-base">
	    <li><a href="index.php?gct=salescredit&gp=index"><span>销售员订单</span></a></li>
        <li><a href="index.php?gct=salescredit&gp=salescreditlog"><span><?php echo $lang['admin_salescredit_log_title']?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>业绩调整</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="salescredit_form" method="post" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation"><?php echo $lang['admin_salescredit_membername']; ?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" name="member_name" id="member_name" class="txt" onchange="javascript:checkmember();">
            <input type="hidden" name="member_id" id="member_id" value='0'/></td>
          <td class="vatop tips"><?php echo $lang['member_index_name']?></td>
        </tr>
        <tr id="tr_memberinfo">
          <td colspan="2" style="font-weight:bold;" id="td_memberinfo"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label><?php echo $lang['admin_salescredit_operatetype']; ?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><select id="operatetype" name="operatetype">
              <option value="1"><?php echo $lang['admin_salescredit_operatetype_add']; ?></option>
              <option value="2"><?php echo $lang['admin_salescredit_operatetype_reduce'];?></option>
            </select></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation"><?php echo $lang['admin_salescredit_pointsnum']; ?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="salescreditnum" name="salescreditnum" class="txt"></td>
          <td class="vatop tips"><?php echo $lang['member_index_email']?></td>
        </tr>
		<tr>
          <td colspan="2" class="required"><label class="validation"><?php echo '订单编号'; ?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="order_sn" name="order_sn" class="txt"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label><?php echo $lang['admin_salescredit_pointsdesc']; ?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><textarea name="salescreditdesc" rows="6" class="tarea"></textarea></td>
          <td class="vatop tips"><?php echo $lang['admin_salescredit_pointsdesc_notice'];?></td>
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
function checkmember(){
	var membername = $.trim($("#member_name").val());
	if(membername == ''){
		$("#member_id").val('0');
		alert(<?php echo $lang['admin_salescredit_addmembername_error']; ?>);
		return false;
	}
	$.getJSON("index.php?gct=salescredit&gp=checkmember", {'name':membername}, function(data){
	        if (data)
	        {
		        $("#tr_memberinfo").show();
				var msg= "<?php echo $lang['admin_salescredit_member_tip_1']; ?> "+ data.name + "<?php echo $lang['admin_salescredit_member_tip_2']; ?>" + data.salescredit;
				$("#member_name").val(data.name);
				$("#member_id").val(data.id);
		        $("#td_memberinfo").text(msg);
	        }
	        else
	        {
	        	$("#member_name").val('');
	        	$("#member_id").val('0');
		        alert("<?php echo $lang['admin_salescredit_userrecord_error']; ?>");
	        }
	});
}
$(function(){
	$("#tr_memberinfo").hide();
	
    $('#salescredit_form').validate({
//        errorPlacement: function(error, element){
//            $(element).next('.field_notice').hide();
//            $(element).after(error);
//        },
        rules : {
        	member_name: {
				required : true
			},
			member_id: {
				required : true
            },
            salescreditnum   : {
                required : true,
                min : 0.01
            }
        },
        messages : {
			member_name: {
				required : '<?php echo $lang['admin_salescredit_addmembername_error'];?>'
			},
			member_id : {
				required : '<?php echo $lang['admin_salescredit_member_error_again'];?>'
            },
            salescreditnum  : {
                required : '<?php echo $lang['admin_salescredit_points_null_error']; ?>',
                min : '<?php echo $lang['admin_salescredit_points_min_error']; ?>'
            }
        }
    });
});
</script>