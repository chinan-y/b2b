<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['nc_member_predepositmanage'];?></h3>
      <ul class="tab-base">
        <li><a href="index.php?gct=predeposit&gp=predeposit"><span><?php echo $lang['admin_predeposit_rechargelist']?></span></a></li>
        <li><a href="index.php?gct=predeposit&gp=pd_cash_list"><span><?php echo $lang['admin_predeposit_cashmanage']; ?></span></a></li>    
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_member_predepositlog'];?></span></a></li>
        <li><a href="index.php?gct=predeposit&gp=predeposit_add"><span><?php echo  $lang['nc_member_predepositset'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" name="gct" value="predeposit">
    <input type="hidden" name="gp" value="pd_log_list">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label><?php echo $lang['admin_predeposit_membername'];?></label></th>
          <td><input type="text" name="mname" class="txt" value='<?php echo $_GET['mname'];?>'></td>
                    
          <th><label><?php echo $lang['admin_predeposit_maketime']; ?></label></th>
          <td><input type="text" id="stime" name="stime" class="txt date" value="<?php echo $_GET['stime'];?>" >
            <label>~</label>
            <input type="text" id="etime" name="etime" class="txt date" value="<?php echo $_GET['etime'];?>" ></td>
          
        </tr><tr><th><label><?php echo $lang['admin_predeposit_adminname'];?></label></th>
          <td><input type="text" name="aname" class="txt" value='<?php echo $_GET['aname'];?>'></td>
		  <th><?php echo '描述'; ?></th>
          <td><input type="text" id="lg_desc" name="lg_desc" class="txt2" value="<?php echo $_GET['lg_desc'];?>" ></td>
		  <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            </td></tr>
      </tbody>
    </table>
  </form>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title">
            <h5><?php echo $lang['nc_prompts'];?></h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li><?php echo $lang['admin_predeposit_log_help1'];?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <div style="text-align:right;"><a class="btns" target="_blank" href="index.php?<?php echo $_SERVER['QUERY_STRING'];?>&gp=export_mx_step1"><span><?php echo $lang['nc_export'];?>Excel</span></a></div>
  <table class="table tb-type2">
    <thead>
      <tr class="thead">
        <th><?php echo $lang['admin_predeposit_membername'];?></th>
        <th class="align-center"><?php echo $lang['admin_predeposit_changetime']; ?></th>
        <th><?php echo $lang['admin_predeposit_pricetype_available'];?>(<?php echo $lang['currency_zh'];?>)</th>
        <th><?php echo $lang['admin_predeposit_pricetype_freeze'];?>(<?php echo $lang['currency_zh'];?>)</th>
		<th class="align-center"><?php echo $lang['admin_predeposit_log_stage'];?></th>
        <th><?php echo $lang['admin_predeposit_log_desc'];?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['list_log']) && is_array($output['list_log'])){ ?>
      <?php foreach($output['list_log'] as $k => $v){?>
      <tr class="hover">
        <td><?php echo $v['lg_member_name'];?></td>
        <td class="nowarp align-center"><?php echo @date('Y-m-d H:i:s',$v['lg_add_time']);?></td>
        <td><?php echo floatval($v['lg_av_amount']) ? (floatval($v['lg_av_amount']) > 0 ? '+' : null ).$v['lg_av_amount'] : null;?></td>
        <td><?php echo floatval($v['lg_freeze_amount']) ? (floatval($v['lg_freeze_amount']) > 0 ? '+' : null ).$v['lg_freeze_amount'] : null;?></td>
        <td><?php 
		if($v['lg_type']=='order_pay'){echo "下单支付预存款";}
		if($v['lg_type']=='order_freeze'){echo "下单冻结预存款";}
		if($v['lg_type']=='order_cancel'){echo "取消订单解冻预存款";}
		if($v['lg_type']=='order_comb_pay'){echo "下单支付被冻结的预存款";}
		if($v['lg_type']=='recharge'){echo "充值";}
		if($v['lg_type']=='cash_apply'){echo "申请提现冻结预存款";}
		if($v['lg_type']=='cash_pay'){echo "提现成功";}
		if($v['lg_type']=='cash_del'){echo "取消提现申请，解冻预存款";}
		if($v['lg_type']=='refund'){echo "退款";}
		if($v['lg_type']=='sman_del_money'){echo "减少收益";}
		if($v['lg_type']=='sman_add_money'){echo "增加收益";}
		if($v['lg_type']=='sys_add_money'){echo "增加预存款";}
		if($v['lg_type']=='sys_del_money'){echo "减少预存款";}
		?>
		</td>
        <td><?php echo $v['lg_desc'];?>
        <?php if ($v['lg_admin_name'] != '') { ?>
        ( <?php echo $lang['admin_predeposit_adminname'];?> <?php echo $v['lg_admin_name'];?>  )
        <?php } ?>
        </td>
      </tr>
      <?php } ?>
      <?php }else { ?>
      <tr class="no_data">
        <td colspan="10"><?php echo $lang['nc_no_record'];?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="16" id="dataFuncs"><div class="pagination"> <?php echo $output['show_page'];?></div></td>
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
    $('#ncsubmit').click(function(){
    	$('input[name="gp"]').val('pd_log_list');$('#formSearch').submit();
    });	
});
</script>