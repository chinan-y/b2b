<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo "设置返利率";?></h3>
      <ul class="tab-base">
        <li></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="user_form"  method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="member_id" value="<?php echo $output['rebatemember']['member_id'];?>" />
    <input type="hidden" name="member_name" value="<?php echo $output['rebatemember']['member_name'];?>" />
    <table class="table tb-type2">
      <tbody>
        <tr>
          <td colspan="2" class="required"><label class="validation" ><?php echo "用户名";?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" disabled value="<?php echo $output['rebatemember']['member_name'];?>" id="member_name" name="member_name" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <!--tr>
          <td colspan="2" class="required"><label for="member_truename"><?php echo "姓名"?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" disabled value="<?php echo $output['rebatemember']['member_truename'];?>" id="member_truename" name="member_truename" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
          <tr>
          <td colspan="2" class="required"><label class="member_mobile">手机号码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" disabled value="<?php echo $output['rebatemember']['member_mobile'];?>" id="member_mobile" name="member_mobile" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
         <tr>
          <td colspan="2" class="required"><label class="member_areainfo">所在地区:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"  colspan="2">
        <span id="region" class="w400">
            <input type="hidden" value="<?php echo $output['rebatemember']['member_provinceid'];?>" name="province_id" id="province_id">
            <input type="hidden" value="<?php echo $output['rebatemember']['member_cityid'];?>" name="city_id" id="city_id">
            <input type="hidden" value="<?php echo $output['rebatemember']['member_areaid'];?>" name="area_id" id="area_id" class="area_ids" />
            <input type="hidden" value="<?php echo $output['rebatemember']['member_areainfo'];?>" name="area_info" id="area_info" class="area_names" />
            <?php if(!empty($output['rebatemember']['member_areaid'])){?>
            <span><?php echo $output['rebatemember']['member_areainfo'];?></span>
            <input type="button" disabled value="<?php echo $lang['nc_edit'];?>" style="background-color: #F5F5F5; width: 60px; height: 32px; border: solid 1px #E7E7E7; cursor: pointer" class="edit_region" />
            <select style="display:none;">
            </select>
            <?php }else{?>
            <select>
            </select>
            <?php }?>
            </span>
        </td>
        </tr-->

		 <tr>
          <td colspan="2" class="required"><label class="validation"><?php echo "返利率"?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['rebatemember']['member_rebate_rate']*100;?>" id="member_rebate_rate" name="member_rebate_rate" class="txt">％</td>
          <td class="vatop tips">返利率填写百分比</td>
        </tr>

      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15"><a href="JavaScript:void(0);" class="btn" id="submitBtn" ><span><?php echo "提交修改确认";?></span></a></td>
        </tr>
		<tr>
          <td colspan="15" class="required"><label class="validation">说明：<br/>1、单独商品返利和二维码销售员个人返利只适用其中一种返利方式；<br />2、为0时适用单独商品返利率；<br />3、大于0且小于等于50%时适用二维码销售员返利率。</label>
		  </td>
		</tr>
      </tfoot>
    </table>
  </form>
</div>
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