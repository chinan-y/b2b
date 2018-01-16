<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['sales_area'];?></h3>
      <ul class="tab-base">
        <li><a href="index.php?gct=sales_area&gp=sales_area"><span><?php echo $lang['manage'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_new'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="sales_area_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="sa_name"><?php echo $lang['sales_area_name'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" name="sa_name" id="sa_name" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
		<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="sa_manager_id"><?php echo $lang['sales_area_member_id'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" name="sa_manager_id" id="sa_manager_id" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
		
		<tr>
          <td colspan="1" class="required"><label class="validation" for="sa_rate"><?php echo $lang['sales_area_rate'];?>（%）:</label></td>
          <td class="vatop rowform"><input type="text" value="0" name="sa_rate" id="sa_rate" class="txt"></td>
          <td class="vatop tips">销售提成比例。</td>
        </tr>
		
        <tr>
          <td colspan="1" class="required"><label for="sa_manager"><?php echo $lang['sales_area_manager'];?>:</label></td>
          <td class="vatop rowform"><input type="text" value="" name="sa_manager" id="sa_manager" class="txt"></td>
          <td class="vatop tips">区域经理姓名</td>
        </tr>
		
        <tr>
          <td colspan="1" class="required"><label for="sa_contact"><?php echo $lang['sales_area_contact'];?>:</label></td>
          <td class="vatop rowform"><input type="text" value="" name="sa_contact" id="sa_contact" class="txt"></td>
          <td class="vatop tips">区域经理联系方式</td>
        </tr>
		
        <tr>
          <td colspan="1" class="required"><label for="sa_cash"><?php echo $lang['sales_area_cash'];?>:</label></td>
          <td class="vatop rowform"><input type="text" value="0" name="sa_cash" id="sa_cash" class="txt"></td>
          <td class="vatop tips">区域保证金，默认为0元。</td>
        </tr>
		
        <tr>
          <td colspan="1" class="required"><label for="sa_sort"><?php echo $lang['nc_sort'];?>:</label></td>
          <td class="vatop rowform"><input type="text" value="0" name="sa_sort" id="sa_sort" class="txt"></td>
          <td class="vatop tips"><?php echo $lang['update_sort'];?></td>
        </tr>
        
        <tr>
          <td colspan="1" class="required"><label for="sa_sort"><?php echo $lang['nc_er'];?>:</label></td>
          <td class="vatop rowform">
          	<select name="sa_er">  
			  <option value ="0">商品级返利类型</option>  
			  <option value ="1" selected>会员级返利类型</option>  
			</select> 
		  </td>
        </tr>
        
		<tr>
          <td colspan="1"><label for="sa_desc"><?php echo $lang['sales_area_desc'];?>:</label></td>
          <td class="vatop rowform">
          <textarea name="sa_desc" rows="1" id="sa_desc" ></textarea>
          </td>
          <td class="vatop tips"></td>
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
$(function(){$("#submitBtn").click(function(){
    if($("#sales_area_form").valid()){
     $("#sales_area_form").submit();
	}
	});
});

$(document).ready(function(){
	$('#sales_area_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },

        rules : {
            sa_name : {
                required : true,
                remote   : {                
                url :'index.php?gct=sales_area&gp=ajax&branch=check_area_name',
                type:'get',
                data:{
                    sa_name : function(){
                        return $('#sa_name').val();
                    },
					sa_id : ''
                  }
                }
            },
			sa_manager_id : {
                required : true
            },
            sa_sort : {
                number   : true
            },
            sa_rate : {
                number   : true
            },
			 sa_cash : {
                number   : true
            }
        },
        messages : {
            sa_name : {
                required : '<?php echo $lang['sales_area_name_no_null'];?>',
                remote   : '<?php echo $lang['sales_area_name_is_there'];?>'
            },
			sa_manager_id : {
                required : '<?php echo $lang['sales_area_memberid_no_null'];?>'
            },
            sa_sort  : {
                number   : '<?php echo $lang['sales_area_sort_only_number'];?>'
            },
            sa_rate  : {
                number   : '<?php echo $lang['sales_area_rate_only_number'];?>'
            },
            sa_cash  : {
                number   : '<?php echo $lang['sales_area_cash_only_number'];?>'
            }
        }
    });
});
</script>