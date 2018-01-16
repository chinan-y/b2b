<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>阿里云参数设置</h3>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" enctype="multipart/form-data" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
	  	<tr class="noborder">
          <td colspan="2" class="required"><label>是否开启Aliyun对象存储:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="OSS_IS_STORAGE_ENABLE" class="cb-enable <?php if($output['list_setting']['OSS_IS_STORAGE'] == '1'){ ?>selected<?php } ?>" ><span><?php echo $lang['open'];?></span></label>
            <label for="OSS_IS_STORAGE_DISABLED" class="cb-disable <?php if($output['list_setting']['OSS_IS_STORAGE'] == '0'){ ?>selected<?php } ?>" ><span><?php echo $lang['close'];?></span></label>
            <input id="OSS_IS_STORAGE_ENABLE" name="OSS_IS_STORAGE" <?php if($output['list_setting']['OSS_IS_STORAGE'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="OSS_IS_STORAGE_DISABLED" name="OSS_IS_STORAGE" <?php if($output['list_setting']['OSS_IS_STORAGE'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
		  </td>
		  <td class="vatop tips"><span class="vatop rowform">data->config->confi.ini.php->$config['image_bucket_name']='imageqqbsmall'；配合$config['upload_site_url']一起使用，默认关闭</span></td>
        </tr>
      </tbody>
      <tfoot id="submit-holder">
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.form1.submit()"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
        <tr class="noborder">
          <td colspan="2"></td>
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
