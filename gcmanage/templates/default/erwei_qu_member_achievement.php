<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo 区域管理;?></h3>
      <ul class="tab-base">
       <li><a href="index.php?gct=erwei_qu_member&gp=member"><span><?php echo $lang['manage'];?></span></a></li>
        <li><a href="index.php?gct=erwei_qu_member&gp=sales_area_add" ><span><?php echo $lang['nc_new'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
 <div style="text-align:right;"><span style="font-size:14px; color:#0082FC; font-weight:600;">若需统计，请进行条件查询后-></span><a class="btns" href="javascript:void(0);" id="ncexport"><span><?php echo $lang['nc_export'];?>Excel</span></a></div>

  <table class="table tb-type2">
   
    <tbody>
      <?php if(!empty($output['list_log']) && is_array($output['list_log'])){ ?>
      <?php foreach($output['list_log'] as $k => $v){?>
      <tr class="hover">
        <td><?php echo $v['sc_membername'];?></td>
        <td><?php echo $v['sc_desc'];?></td>
        <td class="align-center"><?php echo $v['sc_points'];?></td>
        <td class="nowrap align-center"><?php echo @date('Y-m-d',$v['sc_addtime']);?></td>
        <td><?php echo $v['sc_adminname'];?></td>
      </tr>
      <?php } ?>
      <?php }else { ?>
      <tr class="no_data">
        <td colspan="15"><?php echo $lang['nc_no_record'];?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <tr class="tfoot">
        <td colspan="15"><div class="pagination"> <?php echo $output['show_page'];?> </div></td>
      </tr>
    </tfoot>
  </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script language="javascript">
$(function(){
	$('#stime').datepicker({dateFormat: 'yy-mm-dd'});
	$('#etime').datepicker({dateFormat: 'yy-mm-dd'});
    $('#ncexport').click(function(){
    	$('input[name="gp"]').val('export_step1');
    	$('#formSearch').submit();
    });
    $('#ncsubmit').click(function(){
    	$('input[name="gp"]').val('salescreditlog');$('#formSearch').submit();
    });
});
</script>
