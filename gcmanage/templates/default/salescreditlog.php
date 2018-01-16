<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['admin_salescredit_log_title'];?></h3>
      <ul class="tab-base">
      <li><a href="index.php?gct=salescredit&gp=index" ><span>销售员订单</span></a></li>
      <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['admin_salescredit_log_title'];?></span></a></li>
	  <li><a href="index.php?gct=salescredit&gp=addsalescredit"><span>业绩调整</span></a></li>
	  
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" name="gct" value="salescredit">
    <input type="hidden" name="gp" value="salescreditlog">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label><?php echo $lang['admin_salescredit_membername']; ?></label></th>
          <td><input type="text" name="mname" class="txt" value='<?php echo $_GET['mname'];?>'></td><th><?php echo $lang['admin_salescredit_addtime']; ?></th>
          <td><input type="text" id="stime" name="stime" class="txt date" value="<?php echo $_GET['stime'];?>" >
            <label>~</label>
            <input type="text" id="etime" name="etime" class="txt date" value="<?php echo $_GET['etime'];?>" ></td><td></td>
          </tr><tr><th><label><?php echo $lang['admin_salescredit_adminname']; ?></label></th><td><input type="text" name="aname" class="txt" value='<?php echo $_GET['aname'];?>'></td>
		  <th><?php echo '订单编号'; ?></th>
			<td><input type="text" id="order_sn" name="order_sn" class="txt2" value="<?php echo $_GET['order_sn'];?>" ></td>
		  </th>
          <th><?php echo $lang['admin_salescredit_pointsdesc']; ?></th>
          <td><input type="text" id="description" name="description" class="txt2" value="<?php echo $_GET['description'];?>" ></td>
          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
          
            <?php if($output['search_field_value'] != '' or $output['search_sort'] != ''){?>
            <a href="index.php?gct=member&gp=member" class="btns "><span><?php echo $lang['nc_cancel_search']?></span></a>
            <?php }?></td>
        </tr>
      </tbody>
    </table>
  </form><div style="text-align:right;"><span style="font-size:14px; color:#0082FC; font-weight:600;">若需统计，请进行条件查询后-></span><a class="btns" href="javascript:void(0);" id="ncexport"><span><?php echo $lang['nc_export'];?>Excel</span></a></div>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li><?php echo $lang['admin_salescredit_log_help1'];?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <table class="table tb-type2">
    <thead>
      <tr class="thead">
        <th><?php echo $lang['admin_salescredit_sa_name']; ?></th>
        <th><?php echo $lang['admin_salescredit_membername']; ?></th>
        <th><?php echo '订单编号'; ?></th>
		<th class="align-center"><?php echo $lang['admin_salescredit_pointsnum']; ?></th>
        <th><?php echo $lang['admin_salescredit_pointsdesc']; ?></th>
        <th class="align-center"><?php echo $lang['admin_salescredit_addtime']; ?></th>
        <th><?php echo $lang['admin_salescredit_adminname']; ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['list_log']) && is_array($output['list_log'])){ ?>
      <?php foreach($output['list_log'] as $k => $v){?>
      <tr class="hover">
        <td><?php echo $v['sa_name'];?></td>
        <td><?php echo $v['sc_membername'];?></td>
        <td><?php echo $v['sc_order_sn'];?></td>
		<td class="align-center"><?php echo $v['sc_points'];?></td>
        <td><?php echo $v['sc_desc'];?></td>
        <td class="nowrap align-center"><?php echo @date('Y-m-d H:i:s',$v['sc_addtime']);?></td>
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
