<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['sales_area'];?></h3>
      <ul class="tab-base">
        <li><a href="index.php?gct=erwei_qu_member&gp=sales_area" ><span><?php echo $lang['manage'];?></span></a></li>
        <li><a href="index.php?gct=erwei_qu_member&gp=sales_area_add" ><span><?php echo $lang['nc_new'];?></span></a></li>
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
          <td colspan="2" class="required"><label for="sa_manager"><?php echo $lang['sales_area_manager'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['area_array']['sa_manager'];?>" name="sa_manager" id="sa_manager" class="txt"></td>
          <td class="vatop tips">区域经理</td>
        </tr>

		<tr>
          <td colspan="2" class="required"><label class="member_areainfo">销售代理区域:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"  colspan="2">
		  <span><?php echo $output['area_array']['sa_areaid'];?>｜<?php echo $output['area_array']['sa_areaname'];?></span>
		  <br />
			<span id="region" class="w400">
            <input type="hidden" value="<?php echo $output['area_array']['member_provinceid'];?>"	name="province_id"	id="province_id">
            <input type="hidden" value="<?php echo $output['area_array']['member_cityid'];?>"		name="city_id"		id="city_id">
            <input type="hidden" value="<?php echo $output['area_array']['member_areaid'];?>"		name="area_id"		id="area_id" class="area_ids" />
            <input type="hidden" value="<?php echo $output['area_array']['member_areainfo'];?>"		name="area_info"	id="area_info" class="area_names" />
            <?php if(!empty($output['area_array']['member_areaid'])){?>
            <span><?php echo $output['area_array']['member_areainfo'];?></span>
            <input type="button" disabled value="<?php echo $lang['nc_edit'];?>" style="background-color: #F5F5F5; width: 60px; height: 32px; border: solid 1px #E7E7E7; cursor: pointer" class="edit_region" />
            <select style="display:none;">
            </select>
            <?php }else{?>
            <select>
            </select>
            <?php }?>
            </span>
        </td>
        </tr>
		
		
        <tr>
          <td colspan="2" class="required"><label for="sa_manager_id"><?php echo $lang['sales_area_manager_id'];?>及用户名:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
				<?php if(is_array($output['manager_list'])){ ?>
              <?php foreach($output['manager_list'] as $k => $v){ ?>
				<?php if($v['is_manager'] == 1) { ?>
				<span><?php echo $v['member_id'] ?>|<?php echo $v['member_name'] ?></span>
				<?php } ?>
              <?php } ?>
              <?php } ?>
		  </td>
          <td class="vatop tips">被设置为区域经理的用户，登录商城个人后台可查看下属销售团队信息。</td>
        </tr>
		
        <tr>
          <td colspan="2" class="required"><label for="sa_contact"><?php echo $lang['sales_area_contact'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['area_array']['sa_contact'];?>" name="sa_contact" id="sa_contact" class="txt"></td>
          <td class="vatop tips">区域经理联系方式</td>
        </tr>
		
        <tr>
          <td colspan="2" class="required"><label for="sa_cash"><?php echo $lang['sales_area_cash'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['area_array']['sa_cash'];?>" name="sa_cash" id="sa_cash" class="txt"></td>
          <td class="vatop tips">区域保证金</td>
        </tr>

        <tr>
          <td colspan="2" class="required"><label class="validation" for="sa_rate"><?php echo $lang['sales_area_rate'];?>（%）:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['area_array']['sa_rate'];?>" name="sa_rate" id="sa_rate" class="txt"></td>
          <td class="vatop tips">销售分成百分比。</td>
        </tr>
		
		<tr>
          <td colspan="2" class="required"><label for="sa_sort"><?php echo $lang['nc_sort'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['area_array']['sa_sort'];?>" name="sa_sort" id="sa_sort" class="txt"></td>
          <td class="vatop tips"><?php echo $lang['update_sort'];?></td>
        </tr>

		<tr>
          <td colspan="2"><label for="sa_desc"><?php echo $lang['sales_area_desc'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          <textarea name="sa_desc" rows="4" id="sa_desc" ><?php echo $output['area_array']['sa_desc'];?></textarea>
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
                url :'index.php?gct=erwei_qu_member&gp=ajax&branch=check_area_name',
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