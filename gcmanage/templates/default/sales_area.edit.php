<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['sales_area'];?></h3>
      <ul class="tab-base">
        <li><a href="index.php?gct=sales_area&gp=sales_area" ><span><?php echo $lang['manage'];?></span></a></li>
        <li><a href="index.php?gct=sales_area&gp=sales_area_add" ><span><?php echo $lang['nc_new'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_edit'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="sales_area_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="sa_id" value="<?php echo $output['area_array']['sa_id'];?>" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="sa_name"><?php echo $lang['sales_area_name'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['area_array']['sa_name'];?>" name="sa_name" id="sa_name" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
				
		<tr>
          <td colspan="1" class="required"><label class="validation" for="sa_manager"><?php echo $lang['sales_area_manager_id'];?><?php echo $lang['sales_area_manager'];?>:</label></td>
          <td class="vatop rowform">
		  <input type="text" value="<?php if(is_array($output['manager_list'])){foreach($output['manager_list'] as $k => $v){if($v['is_manager'] == 1){echo $v['member_id'];}}} ?>" name="sa_manager_id" id="sa_manager_id" class="txt" readonly>
		  <br />
		  <input type="text" value="<?php if(is_array($output['manager_list'])){foreach($output['manager_list'] as $k => $v){if($v['is_manager'] == 1){echo $v['member_name'];}}} ?>"  class="txt" readonly>
		  </td>
          <td class="vatop tips">被设置为区域管理者的用户，不可编辑。</td>
        </tr>
		
        <tr>
          <td colspan="1" class="required"><label class="validation" for="sa_rate"><?php echo $lang['sales_area_rate'];?>（%）:</label></td>
          <td class="vatop rowform"><input type="text" value="<?php echo $output['area_array']['sa_rate'];?>" name="sa_rate" id="sa_rate" class="txt"></td>
          <td class="vatop tips">推广提成比例。</td>
        </tr>
		
		<tr>
          <td colspan="1" class="required"><label class="member_areainfo">销售代理区域: </label>
		  <span><?php if($output['area_array']['sa_areaid']>0 ){ ?><?php echo $output['area_array']['sa_areaid'];?>｜<?php echo $output['area_array']['sa_areaname'];?><?php } ?></span>
		   </td>
          <td class="vatop rowform">
		  	<span id="region" class="w400">
            <input type="hidden" value="<?php if($output['area_array']['sa_areaid']>0){echo $output['area_array']['sa_areaid'];} else {echo $output['area_array']['member_areaid'];} ?>" name="area_id" id="area_id" class="area_ids" />
            <input type="hidden" value="<?php if($output['area_array']['sa_areaid']>0){echo $output['area_array']['sa_areaname'];} else {echo $output['area_array']['member_areainfo'];} ?>" name="area_info" id="area_info" class="area_names" />
            <?php if(!empty($output['area_array']['member_areaid'])){?>
            <span><?php echo $output['area_array']['member_areainfo'];?></span>
            <input type="button" disabled value="<?php echo $lang['nc_edit'];?>" style="background-color: #F5F5F5; width: 60px; height: 32px; border: solid 1px #E7E7E7; cursor: pointer" class="edit_region" />
            <select style="display:hidden;">
            </select>
            <?php }else{?>
            <select>
            </select>
            <?php }?>
            </span>
			<a href="index.php?gct=sales_area&gp=sales_area_cancel&sa_id=<?php echo $output['area_array']['sa_id'];?>">取消授权区域</a>
		</td>
		<td class="vatop tips">按收货地址区分的销售区域</td>
        </tr>
		<tr>
          <td colspan="1" class="required"><label >授权时间:</label></td>
          <td class="vatop rowform">
          <?php echo date('Y-m-d H:i:s',$output['area_array']['agent_start_time']);?> ~ <?php echo date('Y-m-d H:i:s',$output['area_array']['agent_end_time']);?>
          </td>
          <td class="vatop tips"></td>
        </tr>

		<tr class="noborder">
          <td colspan="1" class="required"><label for="sa_manager_id"><?php echo $lang['sales_area_member_id'];?>:</label></td>
          <td class="vatop rowform"><input type="text" value="<?php echo $output['area_array']['sa_manager_id'];?>" name="sa_manager_id" id="sa_manager_id" class="txt">
		  <a href="index.php?gct=sales_area&gp=sales_area_cancel&sa_manager_id=<?php echo $output['area_array']['sa_manager_id'];?>">取消管理者</a>
		  </td>
          <td class="vatop tips">区域管理者ID</td>
        </tr>
		<tr class="noborder">
          <td colspan="1" class="required"><label for="sa_manager"><?php echo $lang['sales_area_member'];?>区域管理者名称:</label></td>
          <td class="vatop rowform"><input type="text" value="<?php echo $output['area_array']['sa_manager'];?>" name="sa_manager" id="sa_manager" class="txt"></td>
          <td class="vatop tips">区域管理者</td>
        </tr>
        <tr>
          <td colspan="1" class="required"><label for="sa_contact"><?php echo $lang['sales_area_contact'];?>:</label></td>
          <td class="vatop rowform"><input type="text" value="<?php if(is_array($output['manager_list'])){ 
              foreach($output['manager_list'] as $k => $v){if($v['is_manager'] == 1) {echo $v['member_mobile'];}}} ?>" name="sa_contact" id="sa_contact" class="txt"></td>
          <td class="vatop tips">区域管理者联系方式。</td>
        </tr>
		
        <tr>
          <td colspan="1" class="required"><label for="sa_cash"><?php echo $lang['sales_area_cash'];?>:</label></td>
          <td class="vatop rowform"><input type="text" value="<?php echo $output['area_array']['sa_cash'];?>" name="sa_cash" id="sa_cash" class="txt"></td>
          <td class="vatop tips">区域保证金</td>
        </tr>
		
		<tr>
          <td colspan="1" class="required"><label for="sa_sort"><?php echo $lang['nc_sort'];?>:</label></td>
          <td class="vatop rowform"><input type="text" value="<?php echo $output['area_array']['sa_sort'];?>" name="sa_sort" id="sa_sort" class="txt"></td>
          <td class="vatop tips"><?php echo $lang['update_sort'];?></td>
        </tr>

		<tr>
          <td colspan="1" class="required"><label for="sa_desc"><?php echo $lang['sales_area_desc'];?>:</label></td>
          <td class="vatop rowform">
          <textarea name="sa_desc" rows="4" id="sa_desc" ><?php echo $output['area_array']['sa_desc'];?></textarea>
          </td>
          <td class="vatop tips"></td>
        </tr>
		<tr>
          <td colspan="1" class="required"><label >创建时间:</label></td>
          <td class="vatop rowform">
          <?php echo date('Y-m-d H:i:s',$output['area_array']['add_time']);?>
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
//
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
                    sa_id : '<?php echo $output['area_array']['sa_id'];?>'
                  }
                }
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


<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript">
$(function(){

	regionInit("region");

$("#submitBtn").click(function(){

     $("#user_form").submit();

	});
});
</script> 