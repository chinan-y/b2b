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
          <td colspan="2" class="required">对象存储OSS</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="OSS_ACCESS_ID">OSS_ACCESS_ID:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="OSS_ACCESS_ID" name="OSS_ACCESS_ID" value="<?php echo $output['list_setting']['OSS_ACCESS_ID'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
		
        <tr class="noborder">
          <td colspan="2" class="required"><label for="OSS_ACCESS_KEY">OSS_ACCESS_KEY:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="OSS_ACCESS_KEY" name="OSS_ACCESS_KEY" value="<?php echo $output['list_setting']['OSS_ACCESS_KEY'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
		
        <tr class="noborder">
          <td colspan="2" class="required"><label for="OSS_ENDPOINT">OSS_ENDPOINT:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="OSS_ENDPOINT" name="OSS_ENDPOINT" value="<?php echo $output['list_setting']['OSS_ENDPOINT'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>

        <tr class="noborder">
          <td colspan="2" class="required"><label for="OSS_TEST_BUCKET">OSS_TEST_BUCKET:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="OSS_TEST_BUCKET" name="OSS_TEST_BUCKET" value="<?php echo $output['list_setting']['OSS_TEST_BUCKET'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
      </tbody>
      <tfoot id="submit-holder">
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.form1.submit()"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
        <tr class="noborder">
          <td colspan="2">备注：对应文件data->api->aliyunoss->Config.php</td>
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
