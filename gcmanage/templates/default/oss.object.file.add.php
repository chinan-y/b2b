<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['order_manage'];?>阿里云对象存储OSS</h3>
      <ul class="tab-base">
	    <li><a href="index.php?gct=oss_aliyun&gp=index"><span>对象存储</span></a></li>
        <li><a href="index.php?gct=oss_aliyun&gp=objectlist"><span>object管理</span></a></li>
		<li><a href="index.php?gct=oss_aliyun&gp=addobjdir"><span>新建文件夹</span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>上传文件</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" enctype="multipart/form-data" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
	   <tr class="noborder">
          <td colspan="2" class="required"><label for="bucketname">存储对象名称:</label></td>
        </tr>
        <tr class="noborder">
			<td>
		  <select id="bucketname" name="bucketname" class="querySelect">
              <option value=""><?php echo $lang['nc_please_choose'];?></option>
			    <?php if(count($output['bucketList'])>0){?>
					<?php foreach($output['bucketList'] as $bucket){  ?>
					<option value="<?php echo $bucket->getName();?>" <?php if($_GET['bucketname']== $bucket->getName()) {?> selected <?php }?> ><?php echo $bucket->getName();?></option>
					<?php } ?>
				<?php } ?>
            </select>
		  
		  </td>
          <td class="vatop tips"> </td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="objectfile">上传文件:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><input type='hidden' name='num' id='num1' value='1' /></span>
		  <span class="type-file-box"><input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="objectfile1" type="file" class="type-file-file" id="objectfile1" size="30" hidefocus="true" nc_type="change_object1">
            </span></td>
          <td class="vatop tips"><span class="vatop rowform">选择上传文件，支持的文件类型：png、gif、jpg、txt、php、html、pdf、doc、xsl、mp4</span></td>
        </tr>
		
		 <tr class="noborder">
          <td colspan="2" class="required"><label for="specdirectory">指定目录:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="specdirectory" name="specdirectory" value="<?php echo $_GET['path'] ?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span>填写目录名称，如目录不存在会自动创建，多级目录分隔符"/",名称最后填写"/"</td>
        </tr>
      
        <tr>
          <td colspan="2" class="required"><label for="objectfile">继续上传:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><input type='hidden' name='num' id='num2' value='2' /></span>
		  <span class="type-file-box"><input type='text' name='textfield' id='textfield2' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="objectfile2" type="file" class="type-file-file" id="objectfile2" size="30" hidefocus="true" nc_type="change_object2">
            </span></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
		<tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><input type='hidden' name='num' id='num3' value='3' /></span>
		  <span class="type-file-box"><input type='text' name='textfield' id='textfield3' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="objectfile3" type="file" class="type-file-file" id="objectfile3" size="30" hidefocus="true" nc_type="change_object3">
            </span></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
		<tr class="noborder">
          <td class="vatop rowform">
		  	<table>
				<tr>
					<td>
					<input id='addGoods'  type='button' value='增加文件' />
					</td>
				</tr>
			</table>
		  
		  </td>
        </tr>	

      </tbody>
		  			
      <tfoot id="submit-holder">
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.form1.submit()"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui-timepicker-addon/jquery-ui-timepicker-addon.min.css"  />
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui-timepicker-addon/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('#site_notice_start_time').datetimepicker({
        controlType: 'select'
    });

    $('#site_notice_end_time').datetimepicker({
        controlType: 'select'
    });
});
</script>
<script>
$(function(){

	var num=4;
	$('#addGoods').click(function(){
		var str="<tr class='noborder'>"+
					"<td class='vatop rowform'><span class='type-file-show'><input type='hidden' name='num' id='num"+num+"' value='"+num+"' /></span>"+
					"<span class='type-file-box'><input type='text' name='textfield' id='textfield"+num+"' class='type-file-text' />"+
					"<input type='button' name='button' id='button1' class='type-file-button' />"+
					"<input name='objectfile"+num+"' type='file' class='type-file-file' id='objectfile"+num+"' size='30' hidefocus='true' nc_type='change_object"+num+"'></span>"+
					"</td>"+
				"</tr>";

		$(this).parent().before(str);
		num++;
		
	})
	
})
</script>	
<script type="text/javascript">
// 模拟网站LOGO上传input type='file'样式
$(function(){
	$("#objectfile1").change(function(){
		$("#textfield1").val($(this).val());
	});
	$("#objectfile2").change(function(){
		$("#textfield2").val($(this).val());
	});
	$("#objectfile3").change(function(){
		$("#textfield3").val($(this).val());
	});
	$("#objectfile4").change(function(){
		$("#textfield4").val($(this).val());
	});
	$("#objectfile5").change(function(){
		$("#textfield5").val($(this).val());
	});
	$("#objectfile6").change(function(){
		$("#textfield6").val($(this).val());
	});
	$("#objectfile7").change(function(){
		$("#textfield7").val($(this).val());
	});
	$("#objectfile8").change(function(){
		$("#textfield8").val($(this).val());
	});
	$("#objectfile9").change(function(){
		$("#textfield9").val($(this).val());
	});
	$("#objectfile10").change(function(){
		$("#textfield10").val($(this).val());
	});


// 上传文件类型
$('input[class="type-file-file"]').change(function(){
	var filepatd=$(this).val();
	var extStart=filepatd.lastIndexOf(".");
	var ext=filepatd.substring(extStart,filepatd.lengtd).toUpperCase();		
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"&&ext!=".TXT"&&ext!=".PHP"&&ext!=".HTML"&&ext!=".PDF"&&ext!=".DOC"&&ext!=".XSL"&&ext!=".MP4"){
			alert("不支持的文件类型！");
				$(this).attr('value','');
			return false;
		}
	});
});
</script>
