<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['order_manage'];?>阿里云对象存储OSS</h3>
      <ul class="tab-base">
	    <li><a href="index.php?gct=oss_aliyun&gp=index"><span>对象存储</span></a></li>
        <li><a href="index.php?gct=oss_aliyun&gp=objectlist" ><span>object管理</span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>新建文件夹</span></a></li>
        <li><a href="index.php?gct=oss_aliyun&gp=uploadobjectfile"><span>上传文件</span></a></li>
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
        <tr class="noborder">
          <td colspan="2" class="required"><label for="object_name">文件夹名:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="object_name" name="object_name" value="" class="txt" type="text" /></td>
          <td class="vatop tips">
  		    <li>文件夹命名规范：</li>
			<li>- 只能包含字母，数字，中文，下划线（_）和短横线（-），小数点（.）</li>
			<li>- 只能以小数点（.）字母、数字或者中文开头</li>
			<li>- 文件夹的长度限制在 1-254 之间</li>
		  </td>
        </tr>
		
		<tr class="noborder">
          <td colspan="2" class="required"><label for="specdirectory">指定目录:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="specdirectory" name="specdirectory" value="<?php echo $_GET['path'] ?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span>填写目录名称并填写"/"，如目录不存在会自动创建，多级目录分隔符"/"。</td>
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

