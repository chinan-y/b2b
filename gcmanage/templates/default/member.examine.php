<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['member_index_manage']?></h3>
      <ul class="tab-base">
        <li><a href="index.php?gct=member&gp=member" ><span><?php echo $lang['nc_manage']?></span></a></li>
        <li><a href="index.php?gct=member&gp=member_add" ><span><?php echo $lang['nc_new']?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_edit'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="user_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="member_id" value="<?php echo $output['member_array']['member_id'];?>" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="1" class="required"><label><?php echo $lang['member_index_name']?>:</label></td>
          <td class="vatop rowform"><?php echo $output['member_array']['member_name'];?></td>
        </tr>  
        <tr>
          <td colspan="1" class="required"><label class=""  for="member_mobile">手机号码:</label></td>
          <td class="vatop rowform"><?php echo $output['member_array']['member_mobile'];?></td>
		  <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="1" class="required"><label for="member_truename"><?php echo $lang['member_index_true_name']?>:</label></td>
          <td class="vatop rowform"><?php echo $output['member_array']['member_truename'];?></td>
          <td class="vatop tips"></td>
        </tr>
		<tr>
          <td colspan="1" class="required"><label class="member_code">身份证号码:</label></td>
          <td class="vatop rowform"><?php echo $output['member_array']['member_code'];?></td>
		  <td class="vatop tips"></td>
        </tr>
		<tr>
          <td colspan="1" class="required"><label>公司名称:</label></td>
          <td class="vatop rowform"><?php echo $output['member_array']['member_company_name'];?></td>
		  <td class="vatop tips"></td>
        </tr>
		
		<tr>
          <td colspan="1" class="required"><label>营业执照:</label></td>
          <td class="vatop rowform">
			<img src="<?php echo BASE_SITE_URL;?><?php echo $output['member_array']['member_license'];?>" style=" max-width: 1200px;"/>
		  </td>
		  <td class="vatop tips"></td>
        </tr>
		<tr>
          <td class="vatop tips"></td>
		  <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo '审核';?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript">
$("#submitBtn").click(function(){
	if(confirm('确认审核？')){
		$("#user_form").submit();
	}
});
</script> 