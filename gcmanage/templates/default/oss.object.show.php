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
    <input type="hidden" name="gp" value="index" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
         <th><label></label></th>
         <td><input class="txt2" type="text" name="order_sn" value="<?php echo $_GET['order_sn'];?>" /></td>
         <th></th>
         <td><input class="txt-short" type="text" name="store_name" value="<?php echo $_GET['store_name'];?>" /></td>
         <th><label></label></th>
          <td colspan="4"><select name="order_state" class="querySelect">
              <option value=""><?php echo $lang['nc_please_choose'];?></option>
            </select></td>
        
        </tr>
        <tr>
          <th><label for="query_start_time"><?php echo $lang['order_time_from'];?></label></th>
          <td><input class="txt date" type="text" value="<?php echo $_GET['query_start_time'];?>" id="query_start_time" name="query_start_time">
            <label for="query_start_time">~</label>
            <input class="txt date" type="text" value="<?php echo $_GET['query_end_time'];?>" id="query_end_time" name="query_end_time"/></td>
         <th></th>
         <td><input class="txt-short" type="text" name="buyer_name" value="<?php echo $_GET['buyer_name'];?>" /></td>
		 <th></th>
         <td><input class="txt-short" type="text" name="buyer_id" value="<?php echo $_GET['buyer_id'];?>" /></td> 
		 <th></th>
         <td>
            <select name="payment_code" class="w100">
            <option value=""><?php echo $lang['nc_please_choose'];?></option>
            </select>
         </td>
          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            
            </td>
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
            <li><?php echo $lang['order_help1'];?></li>
        </ul>
		</td>
      </tr>
    </tbody>
  </table>
  <table class="table tb-type2 nobdb">
    <thead>
      <tr class="thead">
        <th>server</th>
        <th>url</th>
        <th>content_type</th>
        <th>download_content_length</th>
        <th>filetime</th>
        <th></th>
      </tr>
    </thead>

    <tbody>
      <?php if(count($output['objectmeta'])>0){?>
      <tr class="hover">
        <td><?php echo $output['objectmeta']['server']; ?></td>
        <td><?php echo $output['objectmeta']['info']['url']; ?></td>
        <td><?php echo $output['objectmeta']['info']['content_type']; ?></td>
        <td><?php echo $output['objectmeta']['info']['download_content_length']; ?></td>
        <td><?php echo date('Y-m-d H:i:s',$output['objectmeta']['info']['filetime']); ?></td>
		<td><a href="<?php echo $output['objectmeta']['info']['url']; ?>" target=_blank>查看</a></td>
      </tr>
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
    	$('input[name="gp"]').val('index');$('#formSearch').submit();
    });
});


</script> 
