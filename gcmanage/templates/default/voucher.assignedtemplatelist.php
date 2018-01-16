<?php defined('GcWebShop') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"/>
<div class="page">
  <!-- 页面导航 -->
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['nc_voucher_price_manage'];?></h3>
      <ul class="tab-base">
        <?php   foreach($output['menu'] as $menu) {  if($menu['menu_key'] == $output['menu_key']) { ?>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $menu['menu_name'];?></span></a></li>
        <?php }  else { ?>
        <li><a href="<?php echo $menu['menu_url'];?>" ><span><?php echo $menu['menu_name'];?></span></a></li>
        <?php  } }  ?>
      </ul>
      </ul>
    </div>
  </div>
  <!--  搜索 -->
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch">
    <input type="hidden" name="gct" value="voucher">
    <input type="hidden" name="gp" value="assignedtemplatelist">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="store_name"><?php echo $lang['admin_voucher_storename'];?></label></th>
          <td><input type="text" value="<?php echo $_GET['store_name'];?>" name="store_name" id="store_name" class="txt" style="width:100px;"></td>      <th><label for="voucher_number">代金券编码</label></th>
          <td><input type="text" value="<?php echo $_GET['voucher_number'];?>" name="voucher_number" id="voucher_number" class="txt" style="width:100px;"></td>
		  <th><label for="member_name">用户名</label></th>
          <td><input type="text" value="<?php echo $_GET['member_name'];?>" name="member_name" id="member_name" class="txt" style="width:100px;"></td>
          <th><label for="store_name"><?php echo $lang['admin_voucher_template_adddate'];?></label></th>
          <td><input type="text" id="sdate" name="sdate" class="txt date" value="<?php echo $_GET['sdate'];?>" >~<input type="text" id="edate" name="edate" class="txt date" value="<?php echo $_GET['edate'];?>" ></td>
          <th><label><?php echo $lang['nc_state'];?></label></th>
          <td>
          	<select name="state">
          		<option value="0" <?php if(0 === intval($_GET['state'])) echo 'selected';?>><?php echo $lang['nc_status'];?></option>
          		<?php if(is_array($output['uservoucher_arr'])) { ?>
          		<?php foreach($output['uservoucher_arr'] as $key=>$val) { ?>
          			<option value="<?php echo $val[0];?>" <?php if(intval($val[0]) === intval($_GET['state'])) echo 'selected';?>><?php echo $val[1];?></option>
          		<?php } ?>
          		<?php } ?>
            </select></td>
          <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a></td>
        </tr>
      </tbody>
    </table>
  </form>
  <!-- 帮助 -->
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12" class="nobg"><div class="title">
            <h5><?php echo $lang['nc_prompts'];?></h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul><li><?php echo $lang['admin_voucher_template_list_tip'];?></li></ul></td>
      </tr>
    </tbody>
  </table>
  <!-- 列表 -->
  <form id="list_form" method="post">
    <input type="hidden" id="object_id" name="object_id"  />
    <table class="table tb-type2">
      <thead>
          <tr class="thead">
          	  <th class="w24">&nbsp;</th>
              <th class="align-center"><span>代金券编码</span></th>
			  <th class="align-center"><span>店铺名称</span></th>
              <th class="align-left"><span>代金券名称</span></th>
              <th class="align-center"><span>用户名</span></th>
			  <th class="align-center"><span>面额</span></th>
              <th class="align-center"><span><?php echo $lang['admin_voucher_template_orderpricelimit'];?></span></th>
              <th class="align-center"><span><?php echo $lang['admin_voucher_template_enddate'];?></span></th>
              <th class="align-center"><span>发放时间</span></th>
              <th class="align-center"><span><?php echo $lang['nc_state'];?></span></th>
			  <th class="align-center">商品编码</th>
			  <th class="align-center">订单编号</th>
              <th class="align-center">类别</th>
              <th class="align-center"><span><?php echo $lang['nc_handle'];?></span></th>
          </tr>
      </thead>
      <tbody id="treet1">
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $k => $val){ ?>
        <tr class="hover">
        	<td>&nbsp;</td>
            <td class="align-left"><?php echo $val['voucher_code'];?></td>
		    <td class="align-left"><span>
			<a href="<?php echo urlShop('show_store','index', array('store_id'=>$val['voucher_store_id']));?>" ><span><?php echo $val['store_name'];?></span></a>
            </td>
            <td class="align-left"><span><?php echo $val['voucher_title'];?></span></td>
            <td class="align-center"><span><?php echo $val['voucher_owner_name'];?></span></td>
			<td class="align-center"><span><?php echo $val['voucher_price'];?></span></td>
            <td class="align-center"><span><?php echo $val['voucher_limit'];?></span></td>
            <td class="align-center"><span><?php echo @date('Y-m-d',$val['voucher_start_date']);?>~<?php echo @date('Y-m-d',$val['voucher_end_date']);?></span></td>
            <td class="align-center"><span><?php echo @date('Y-m-d H:i:s',$val['voucher_active_date']);?></span></td>
            <td class="align-center">
			<span>
			<?php
				switch($val['voucher_state']){
				case 1:	echo "未用";break;
				case 2:	echo "已用";break;
				case 3:	echo "过期";break;
				case 4:	echo "收回";break;
				default:echo "Other";
			}
			?>
			</span>
			</td>
			<td class="align-center"><?php echo $val['goods_serial'];?></td>
			<td class="align-center"><?php echo $val['order_sn'];?></td>
            <td class="align-center"><?php echo $val['voucher_type'];?></td>
            <td class="nowrap align-center"><a href="index.php?gct=voucher&gp=assignedtemplatelist&tid=<?php echo $val['voucher_id'];?>">
			<?php if($val['voucher_state']==1){echo "收回"; }?>
			</a></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="16"><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
      <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
      <tfoot>
        <tr class="tfoot">
          <td colspan="16">
            <div class="pagination"> <?php echo $output['show_page'];?> </div>
          </td>
        </tr>
      </tfoot>
      <?php } ?>
    </table>
  </form>
</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>

<script language="javascript">
$(function(){
	$('#sdate').datepicker({dateFormat: 'yy-mm-dd'});
	$('#edate').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
