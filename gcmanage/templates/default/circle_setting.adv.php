<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['nc_circle_advmanage'];?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_circle_advmanage'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12" class="nobg"><div class="title">
            <h5><?php echo $lang['nc_prompts'];?></h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li><?php echo $lang['circle_setting_adv_prompts_one'];?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>  
  <form method="post" enctype="multipart/form-data" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="old_adv_pic1" value="<?php echo $output['list'][1]['pic'];?>" />
    <input type="hidden" name="old_adv_pic2" value="<?php echo $output['list'][2]['pic'];?>" />
    <input type="hidden" name="old_adv_pic3" value="<?php echo $output['list'][3]['pic'];?>" />
    <input type="hidden" name="old_adv_pic4" value="<?php echo $output['list'][4]['pic'];?>" />
    <input type="hidden" name="old_adv_pic5" value="<?php echo $output['list'][5]['pic'];?>" />
    <input type="hidden" name="old_adv_pic6" value="<?php echo $output['list'][6]['pic'];?>" />
    <input type="hidden" name="old_adv_pic7" value="<?php echo $output['list'][7]['pic'];?>" />
    <input type="hidden" name="old_adv_pic8" value="<?php echo $output['list'][8]['pic'];?>" />
    <input type="hidden" name="old_adv_pic9" value="<?php echo $output['list'][9]['pic'];?>" />
    <table class="table tb-type2">
      <tbody>
        <tr>
          <td class="required"><label><?php echo $lang['circle_setting_adv'];?>-01:</label></td><td><?php echo $lang['circle_setting_adv_url_address'];?>:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_CIRCLE.'/'.$output['list'][1]['pic']);?>"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png"></a>
            </span><span class="type-file-box">
            <input name="adv_pic1" type="file" class="type-file-file" id="adv_pic1" size="30" hidefocus="true">
            <input type='text' name='textfield' id='textfield1' class='type-file-text' />
            <input type='button' name='button' id='button1' value='' class='type-file-button' />
            </span></td>
          <td class="vatop tips"><input class="txt" type="text" name="adv_url1" value="<?php echo $output['list'][1]['url'];?>" style=" width:300px;">
          </td>
        </tr>
        <tr>
          <td class="required"><label><?php echo $lang['circle_setting_adv'];?>-02:</label></td><td><?php echo $lang['circle_setting_adv_url_address'];?>:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_CIRCLE.'/'.$output['list'][2]['pic']);?>"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png"></a>
            </span><span class="type-file-box">
            <input name="adv_pic2" type="file" class="type-file-file" id="adv_pic2" size="30" hidefocus="true">
            <input type='text' name='textfield' id='textfield2' class='type-file-text' />
            <input type='button' name='button' id='button2' value='' class='type-file-button' />
            </span></td>
          <td class="vatop tips"><input class="txt" type="text" name="adv_url2" value="<?php echo $output['list'][2]['url'];?>" style=" width:300px;">
          </td>
        </tr>
        <tr>
          <td class="required"><label><?php echo $lang['circle_setting_adv'];?>-03:</label></td><td><?php echo $lang['circle_setting_adv_url_address'];?>:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_CIRCLE.'/'.$output['list'][3]['pic']);?>"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png"></a>
            </span><span class="type-file-box">
            <input name="adv_pic3" type="file" class="type-file-file" id="adv_pic3" size="30" hidefocus="true">
            <input type='text' name='textfield' id='textfield3' class='type-file-text' />
            <input type='button' name='button' id='button3' value='' class='type-file-button' />
            </span></td>
          <td class="vatop tips">
            <input class="txt" type="text" name="adv_url3" value="<?php echo $output['list'][3]['url'];?>" style=" width:300px;">
          </td>
        </tr>
        <tr>
          <td class="required"><label><?php echo $lang['circle_setting_adv'];?>-04:</label></td><td><?php echo $lang['circle_setting_adv_url_address'];?>:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_CIRCLE.'/'.$output['list'][4]['pic']);?>"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png"></a>
            </span><span class="type-file-box">
            <input name="adv_pic4" type="file" class="type-file-file" id="adv_pic4" size="30" hidefocus="true">
            <input type='text' name='textfield' id='textfield4' class='type-file-text' />
            <input type='button' name='button' id='button4' value='' class='type-file-button' />
            </span></td>
          <td class="vatop tips">
            <input class="txt" type="text" name="adv_url4" value="<?php echo $output['list'][4]['url'];?>" style=" width:300px;">
          </td>
        </tr>
         <tr>
          <td class="required"><label><?php echo $lang['circle_setting_frist_page'];?>:</label></td><td><?php echo $lang['circle_setting_adv_url_address'];?>:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_CIRCLE.'/'.$output['list'][5]['pic']);?>"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png"></a>
            </span><span class="type-file-box">
            <input name="adv_pic5" type="file" class="type-file-file" id="adv_pic5" size="30" hidefocus="true">
            <input type='text' name='textfield' id='textfield5' class='type-file-text' />
            <input type='button' name='button' id='button5' value='' class='type-file-button' />
            </span></td>
          <td class="vatop tips">
            <input class="txt" type="text" name="adv_url5" value="<?php echo $output['list'][5]['url'];?>" style=" width:300px;">
          </td>
        </tr> 
	<!--光彩全球底部菜单-->
		<tr>
          <td class="required"><label><?php echo $lang['circle_setting_good_goods'];?> :</label></td><td><?php echo $lang['circle_setting_adv_url_address'];?>:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_CIRCLE.'/'.$output['list'][6]['pic']);?>"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png"></a>
            </span><span class="type-file-box">
            <input name="adv_pic6" type="file" class="type-file-file" id="adv_pic6" size="30" hidefocus="true">
            <input type='text' name='textfield' id='textfield6' class='type-file-text' />
            <input type='button' name='button' id='button6' value='' class='type-file-button' />
            </span></td>
          <td class="vatop tips"><input class="txt" type="text" name="adv_url6" value="<?php echo $output['list'][6]['url'];?>" style=" width:300px;">
          </td>
        </tr>
		<tr>
          <td class="required"><label><?php echo $lang['circle_setting_plivate_customize'];?>:</label></td><td><?php echo $lang['circle_setting_adv_url_address'];?>:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_CIRCLE.'/'.$output['list'][7]['pic']);?>"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png"></a>
            </span><span class="type-file-box">
            <input name="adv_pic7" type="file" class="type-file-file" id="adv_pic7" size="30" hidefocus="true">
            <input type='text' name='textfield' id='textfield7' class='type-file-text' />
            <input type='button' name='button' id='button7' value='' class='type-file-button' />
            </span></td>
          <td class="vatop tips"><input class="txt" type="text" name="adv_url7" value="<?php echo $output['list'][7]['url'];?>" style=" width:300px;">
          </td>
        </tr>
		<tr>
          <td class="required"><label><?php echo $lang['circle_setting_circle'];?>:</label></td><td><?php echo $lang['circle_setting_adv_url_address'];?>:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_CIRCLE.'/'.$output['list'][8]['pic']);?>"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png"></a>
            </span><span class="type-file-box">
            <input name="adv_pic8" type="file" class="type-file-file" id="adv_pic8" size="30" hidefocus="true">
            <input type='text' name='textfield' id='textfield8' class='type-file-text' />
            <input type='button' name='button' id='button8' value='' class='type-file-button' />
            </span></td>
          <td class="vatop tips"><input class="txt" type="text" name="adv_url8" value="<?php echo $output['list'][8]['url'];?>" style=" width:300px;">
          </td>
        </tr>
		<tr>
          <td class="required"><label><?php echo $lang['circle_setting_client_side'];?>:</label></td><td><?php echo $lang['circle_setting_adv_url_address'];?>:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_CIRCLE.'/'.$output['list'][9]['pic']);?>"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png"></a>
            </span><span class="type-file-box">
            <input name="adv_pic9" type="file" class="type-file-file" id="adv_pic9" size="30" hidefocus="true">
            <input type='text' name='textfield' id='textfield9' class='type-file-text' />
            <input type='button' name='button' id='button9' value='' class='type-file-button' />
            </span></td>
          <td class="vatop tips"><input class="txt" type="text" name="adv_url9" value="<?php echo $output['list'][9]['url'];?>" style=" width:300px;">
          </td>
        </tr> 
	<!--光彩全球底部菜单-->		
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.form1.submit()"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript">
// 模拟网站LOGO上传input type='file'样式
$(function(){
	$("#adv_pic1").change(function(){$("#textfield1").val($(this).val());});
	$("#adv_pic2").change(function(){$("#textfield2").val($(this).val());});
	$("#adv_pic3").change(function(){$("#textfield3").val($(this).val());});
	$("#adv_pic4").change(function(){$("#textfield4").val($(this).val());});
	$("#adv_pic5").change(function(){$("#textfield5").val($(this).val());});
	$("#adv_pic6").change(function(){$("#textfield6").val($(this).val());});
	$("#adv_pic7").change(function(){$("#textfield7").val($(this).val());});
	$("#adv_pic8").change(function(){$("#textfield8").val($(this).val());});
	$("#adv_pic9").change(function(){$("#textfield9").val($(this).val());});
// 上传图片类型
$('input[class="type-file-file"]').change(function(){
	var filepatd=$(this).val();
	var extStart=filepatd.lastIndexOf(".");
	var ext=filepatd.substring(extStart,filepatd.lengtd).toUpperCase();		
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
			alert("<?php echo $lang['circle_setting_adv_img_check'];?>");
			$(this).attr('value','');
			return false;
		}
	});
	
$('#time_zone').attr('value','<?php echo $output['list_setting']['time_zone'];?>');
$('.nyroModal').nyroModal();
});
</script>