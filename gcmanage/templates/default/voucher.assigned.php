<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['nc_voucher_price_manage'];?></h3>
      <ul class="tab-base">
        <?php   foreach($output['menu'] as $menu) {  if($menu['menu_key'] == $output['menu_key']) { ?>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $menu['menu_name'];?></span></a></li>
        <?php }  else { ?>
        <li><a href="<?php echo $menu['menu_url'];?>" ><span><?php echo $menu['menu_name'];?></span></a></li>
        <?php  } }  ?>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="add_form" method="post" action="index.php?gct=voucher&gp=<?php echo $output['menu_key']; ?>">
    <input type="hidden" id="form_submit" name="form_submit" value="ok"/>
    <input type="hidden" name="assigned" value="<?php echo $output['voucher_template'];?>"/>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation"><?php echo "选择可用代金券";?><?php echo $lang['nc_colon'];?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
		  <select type="text" id="voucher_t_id" name="voucher_t_id" class="txt" value="<?php echo $output['voucher_template'];?>">
                  <option value="0">选择可用代金券</option>
                  <?php foreach ($output['voucher_template'] as $voucher) {?>
                  <option value="<?php echo $voucher['voucher_t_id'];?>"><?php echo $voucher['voucher_t_id'];?>|<?php echo $voucher['voucher_t_title'];?>|<?php echo date('Y-m-d H:m:s',$voucher['voucher_t_end_date']);?></option>
				 <?php } ?>
               </select>
		  </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation"><?php echo "指定派发用户ID";?><?php echo $lang['nc_colon'];?></label></td>
        </tr>
		<tr class="noborder">
          <td class="vatop rowform">
		  <!--<select type="text" id="member_id" name="member_id" class="select" value="<?php echo $output['member'];?>">
                  <option value="0">选择用户</option>
                  <?php foreach ($output['member'] as $member) {?>
                  <option value="<?php echo $member['member_id'];?>"><?php echo $member['member_id'];?>|<?php echo $member['member_name'];?>|<?php echo $member['member_truename'];?></option>
				 <?php } ?>
               </select>-->
              <input type="text" id="member_id" name="member_id" value="" class="txt">
		  </td>
          <td class="vatop tips"></td>
        </tr>

        <tr class="noborder">
            <td>
            <input type="checkbox" name="check_msg" checked="checked" value="checked"><label class="validation_msg">短信消息提示</label>
            </td>
        </tr>
        <tr>
            <td>
            <textarea rows="10" cols="80" name="message">代金券已派送到您的账户个人中心->我的代金券中,请点击查看:www.qqbsmall.com/wap/tmpl/member/voucher_list.html</textarea></td>
        </tr>

        <tr class="noborder">
          <td colspan="2"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){
	$("#submitBtn").click(function(){
		$("#add_form").submit();
	});
	//页面输入内容验证
	$("#add_form").validate({
		errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },

        rules : {
        	voucher_price_describe: {
                required : true,
                maxlength : 255
        	},
        	voucher_price: {
                required : true,
                digits : true,
                min : 1
            },
            voucher_points: {
                digits : true
            }
        },
        messages : {
      		voucher_price_describe: {
       			required : '<?php echo $lang['admin_voucher_price_describe_error'];?>',
       			maxlength : '<?php echo $lang['admin_voucher_price_describe_lengtherror'];?>'
	    	},
	    	voucher_price: {
                required : '<?php echo $lang['admin_voucher_price_error'];?>',
                digits : '<?php echo $lang['admin_voucher_price_error'];?>',
                min : '<?php echo $lang['admin_voucher_price_error'];?>'
		    },
		    voucher_points: {
		    	digits : '<?php echo $lang['admin_voucher_price_points_error'];?>'
            }
        }
	});
});
</script>