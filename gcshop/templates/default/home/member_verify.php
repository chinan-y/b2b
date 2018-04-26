<?php defined('GcWebShop') or exit('Access Invalid!');?>
<style type="text/css">
.public-top-layout, .head-app, .head-search-bar, .head-user-menu, .public-nav-layout, .nch-breadcrumb-layout, #faq {
	display: none !important;
}
.public-head-layout {
	margin: 10px auto -10px auto;
}
.wrapper {
	width: 1000px;
}
#footer {
	border-top: none!important;
	padding-top: 30px;
}
.nc-login-content{height:290px;}
</style>
<div class="nc-login-layout">
   <div class="nc-login">
		<div class="nc-login-title">
      <h3><?php echo '提交认证';?></h3>
    </div>
    <div class="nc-login-content">
      <form id="register_form" method="post" action="<?php echo SHOP_SITE_URL;?>/index.php?gct=login&gp=submit_verify" enctype='multipart/form-data'>
      <?php Security::getToken();?>
		<div class="mobile-login">	
			<dl>
			  <dt class="number"><?php echo '公司名称';?></dt>
			  <dd style="min-height:54px;">
				<input type="text" id="company_name" name="company_name" class="text tip" />
				<label></label>
			  </dd>
			</dl>
			<dl>
			  <dt class="number"><?php echo '营业执照';?></dt>
			  <dd style="min-height:54px;">
				<img src="<?php echo SHOP_SITE_URL;?>/templates/default/images/member/default_image.png" width="100px" height="141px" class="img_xiang" />
				<a href="#" class="file_img" style="margin-left: 20px;font-size: 15px;">点击上传</a>
				<input type="file" id="license" name="license" class="text tip" style="display:none" />
				<label></label>
			  </dd>
			</dl>
			<dl>
			  <dt>&nbsp;</dt>
			  <dd>
				<input type="submit" id="Submit" value="<?php echo '提交认证';?>" class="submit" title="<?php echo '提交认证';?>" />
				<label></label>
			  </dd>
			</dl>
		</div>
      </form>
      <div class="clear"></div>
    </div>
	
	<div class="nc-login-bottom"></div>
  </div>
  <div class="nc-login-left">
    <div class="leftpic"><img src="<?php echo $output['lpic'];?>"  border="0"></div>
    </div>
</div>
  
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script> 

<script>

//注册表单验证
$(function(){
	
	jQuery.validator.addMethod("mobile", function(value, element) {
		return this.optional(element) || /^(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])\d{8}$/i.test(value);
	}, "<?php echo $lang['login_register_invalid_mobile'];?>"); 
	jQuery.validator.addMethod("lettersonly", function(value, element) {
		return this.optional(element) || /^[^:%,'\*\"\s\<\>\&]+$/i.test(value);
	}, "Letters only please"); 
	jQuery.validator.addMethod("lettersmin", function(value, element) {
		return this.optional(element) || ($.trim(value.replace(/[^\u0000-\u00ff]/g,"aa")).length>=3);
	}, "Letters min please"); 
	jQuery.validator.addMethod("lettersmax", function(value, element) {
		return this.optional(element) || ($.trim(value.replace(/[^\u0000-\u00ff]/g,"aa")).length<=15);
	}, "Letters max please");
	
    $("#register_form").validate({
	   errorPlacement: function(error, element){
			var error_td = element.parent('dd');
			error_td.find('label').hide();
			error_td.append(error);
		},
		onkeyup: false,
		rules : {
			company_name : {
				required : true
			},
			license : {
				required : true
			},

        },
        messages : {
			company_name : {
				required : '<?php echo '需要填写公司名称';?>'
			},
			license : {
				required : '<?php echo '需要上传营业执照图片';?>'
			},
        }
    });
	
});

$(".file_img").click(function(){
	$("#license").click();
	return false;
})

$("#license").change(function(){
	filefujianChange(this)
	var objUrl = getObjectURL(this.files[0]) ;
	console.log("objUrl = "+objUrl) ;
	if (objUrl) {
		$(".img_xiang").attr("src", objUrl) ;
	}
	objUrl=readFileFirefox(this.files[0]);
}) ;
	
function getObjectURL(file) {
	var url = null ; 
	if (window.createObjectURL!=undefined) { // basic
		url = window.createObjectURL(file) ;
	} else if (window.URL!=undefined) { // mozilla(firefox)
		url = window.URL.createObjectURL(file) ;
	} else if (window.webkitURL!=undefined) { // webkit or chrome
		url = window.webkitURL.createObjectURL(file) ;
	}
	return url ;
}

function filefujianChange(target) {
	var fileSize = 0;         
	if ( !target.files) {     
		var filePath = target.value;     
		var fileSystem = new ActiveXObject("Scripting.FileSystemObject");        
		var file = fileSystem.GetFile (filePath);     
		fileSize = file.Size;    
	} else {    
		fileSize = target.files[0].size;     
	}   
	var size = fileSize / 1024;    
	if(size>2000){  
		alert("附件不能大于2M");
		target.value="";
		return
	}
	var name=target.value;
	var fileName = name.substring(name.lastIndexOf(".")+1).toLowerCase();
	if(fileName !="jpg" && fileName !="jpeg" && fileName !="pdf" && fileName !="png" && fileName !="dwg" && fileName !="gif" ){
		alert("请选择图片格式文件上传(jpg,png,gif,dwg,pdf,gif等)！");
		target.value="";
		return
	}
}
</script>