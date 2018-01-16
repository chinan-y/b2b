<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['order_manage'];?>阿里云对象存储OSS</h3>
      <ul class="tab-base">
	    <li><a href="index.php?gct=oss_aliyun&gp=index"><span>对象存储</span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>object管理</span></a></li>
		<li><a href="index.php?gct=oss_aliyun&gp=addobjdir"><span>新建文件夹</span></a></li>
		<li><a href="index.php?gct=oss_aliyun&gp=uploadobjectfile"><span>上传文件</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="gct" value="oss_aliyun" />
    <input type="hidden" name="gp" value="objectlist" />
    <table class="tb-type1 noborder search">
      <tbody>
        <!--
        <tr>
         <th><label></label></th>
         <td><input class="txt2" type="text" name="order_sn" value="" /></td>
         <th></th>
         <td><input class="txt-short" type="text" name="store_name" value="" /></td>
         <th><label></label></th>
          <td colspan="4"><select name="order_state" class="querySelect">
              <option value=""><?php echo $lang['nc_please_choose'];?></option>
            </select></td>
        </tr>
		-->
        <tr>
		<!--
          <th><label for="query_start_time"><?php echo $lang['order_time_from'];?></label></th>
          <td><input class="txt date" type="text" value="<?php echo $_GET['query_start_time'];?>" id="query_start_time" name="query_start_time">
            <label for="query_start_time">~</label>
            <input class="txt date" type="text" value="<?php echo $_GET['query_end_time'];?>" id="query_end_time" name="query_end_time"/></td>
         <th></th>
         <td><input class="txt-short" type="text" name="buyer_name" value="<?php echo $_GET['buyer_name'];?>" /></td>
		 <th></th>
         <td><input class="txt-short" type="text" name="buyer_id" value="<?php echo $_GET['buyer_id'];?>" /></td> 
		 <th></th>
		 -->
         <td>对象存储名称：
            <select name="bucketname" class="w100">
            <option value=""><?php echo $lang['nc_please_choose'];?></option>
			<?php if(count($output['bucketList'])>0){?>
			<?php foreach($output['bucketList'] as $bucket){  ?>
            <option value="<?php echo $bucket->getName();?>" <?php if($_GET['bucketname'] == $bucket->getName()) echo selected; ?>><?php echo $bucket->getName();?></option>
			<?php }?>
			<?php }?>
            </select>
         </td>
          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a></td>
        </tr>
      </tbody>
    </table>
  </form>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li>不同位置区域的不能同时访问</li>
        </ul>
		</td>
      </tr>
    </tbody>
  </table>
  <div style="text-align:left;">
  <a class="btns" href="index.php?gct=oss_aliyun&gp=uploadobjectfile&bucketname=<?php echo $output['bucket'] ?>&path=<?php echo $output['options']['prefix'];?>"><span style="color:green;">上传文件</span></a>
  <a class="btns" href="index.php?gct=oss_aliyun&gp=addobjdir&bucketname=<?php echo $output['bucket'] ?>&path=<?php echo $output['options']['prefix'];?>"><span>新建文件夹</span></a>
  </div> 
  <table class="table tb-type2 nobdb">
    <thead>
      <tr class="thead">
        <th>
		<?php if(empty($output['options']['prefix'])) {?>
		<?php echo  "对象存储名称：".$output['bucket'] ?><br />当前目录(根目录)
		<?php }else { ?>
		<?php echo  "对象存储名称：".$output['bucket'] ?><br />-当前目录(<?php echo $output['options']['prefix'];?>)
		<?php } ?>
		&nbsp;&nbsp;
		<?php 
		$pos = $output['options']['prefix'];
		$pos2 =substr(substr($pos,0,strrpos($pos,'/')),0,strrpos(substr($pos,0,strrpos($pos,'/')),'/'));
		?>
		<a href="index.php?gct=oss_aliyun&gp=objectlist&bucketname=<?php echo $output['bucket'] ?>&prefix=<?php echo $pos2;?>">返回上级目录</a>
		|
		<a href="index.php?gct=oss_aliyun&gp=objectlist&bucketname=<?php echo $output['bucket'] ?>&prefix=">根目录</a></th>
        <th class="align-center"></th>
      </tr>
    </thead>

    <tbody>
      <?php if(count($output['listPrefix'])>0){?>
      <?php foreach($output['listPrefix'] as $listPrefix){  ?>
      <tr class="hover">
        <td>
		<a href="index.php?gct=oss_aliyun&gp=objectlist&bucketname=<?php echo $output['bucket'] ?>&prefix=<?php echo $listPrefix->getprefix();?>"><?php echo $listPrefix->getprefix();?></a>
		</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="w144 align-center"> <a href="index.php?gct=oss_aliyun&gp=delobjdir&bucketname=<?php echo $output['bucket'] ?>&object_name=<?php echo $listPrefix->getprefix();?>">删除</a></td>
      </tr>
      <?php }?>
      <?php }?>
	  
	  <?php if(count($output['listObject'])>0){?>
      <?php foreach($output['listObject'] as $listObject){  ?>
		  <?php if($listObject->getsize()>0) {?>
		  <tr class="hover">
			<td><?php echo $listObject->getkey();?></td>
			<td><?php echo $listObject->getlastModified();?></td>
			<td><?php echo $listObject->gettype();?></td>
			<td><?php echo $listObject->getsize();?></td>
			<td class="w144 align-center"> <a href="index.php?gct=oss_aliyun&gp=downobject&bucketname=<?php echo $output['bucket'] ?>&object_name=<?php echo $listObject->getkey();?>"><?php echo $lang['nc_view'];?></a></td>
			<td class="w144 align-center"> <a href="index.php?gct=oss_aliyun&gp=delobjdir&bucketname=<?php echo $output['bucket'] ?>&object_name=<?php echo $listObject->getkey();?>">删除</a></td>
		  </tr>
		  <?php }?>
      <?php }?>
      <?php }?>
    </tbody>
    <tfoot>
      <tr class="tfoot">
        <td colspan="15" id="dataFuncs"><div class="pagination"> <?php echo $output['show_page'];?> </div></td>
      </tr>
    </tfoot>
  </table>
</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(function(){
    $('#query_start_time').datepicker({dateFormat: 'yy-mm-dd'});
    $('#query_end_time').datepicker({dateFormat: 'yy-mm-dd'});
    $('#ncsubmit').click(function(){
    	$('input[name="gp"]').val('objectlist');$('#formSearch').submit();
    });
});
</script> 
