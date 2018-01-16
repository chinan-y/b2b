<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <form id="list_form" method="get" action="index.php" target="_self">
    <table class="ncm-search-table" style="height:80px;">
      <input type="hidden" name="gct" value="member_information" />
	  <input type="hidden" name= "gp" value="member_my_profitorder" />
      <input type="hidden" name= "recycle" value="<?php echo $_GET['recycle'];?>" />
      <tr>
        <th>收益类型</th>
        <td><select name="profittype">
            <option value="" 	<?php echo $_GET['profittype']==''?'selected':''; ?>>全部收益</option>
            <option value="buy" <?php echo $_GET['profittype']=='buy'?'selected':''; ?>>消费收益</option>
			<?php if($output['is_one']==1){?><option value="A" 	<?php echo $_GET['profittype']=='A'?'selected':''; ?>>A级收益</option><?php } ?>
			<?php if($output['is_two']==1){?><option value="B" 	<?php echo $_GET['profittype']=='B'?'selected':''; ?>>B级收益</option><?php } ?>
			<?php if($output['is_three']==1){?><option value="C" <?php echo $_GET['profittype']=='C'?'selected':''; ?>>C级收益</option><?php } ?>
          </select>
		</td>
		<th><?php echo $lang['member_order_state'];?></th>
        <td><select name="state_type">
            <option value="" <?php echo $_GET['state_type']==''?'selected':''; ?>><?php echo $lang['member_order_all'];?></option>
            <option value="10" <?php echo $_GET['state_type']=='10'?'selected':''; ?>>待付款</option>
            <option value="20" <?php echo $_GET['state_type']=='20'?'selected':''; ?>>待发货</option>
            <option value="30" <?php echo $_GET['state_type']=='30'?'selected':''; ?>>待收货</option>
            <option value="40" <?php echo $_GET['state_type']=='40'?'selected':''; ?>>交易完成</option>
            <option value="0" <?php echo $_GET['state_type']=='0'?'selected':''; ?>>已取消</option>
          </select>
		</td>
		<th><?php echo $lang['member_order_sn'];?></th>
        <td class="w150"><input type="text" class="text w150" name="order_sn" value="<?php echo $_GET['order_sn']; ?>"></td>
        <th class="order_time"><?php echo $lang['member_order_time'];?></th>
        <td class="w300">
        	<input type="text" class="text w70" name="query_start_date" id="query_start_date" value="<?php echo $_GET['query_start_date']; ?>"/>
        	<label class="add-on">
        		<i class="icon-calendar"></i>
        	</label>&nbsp;&#8211;&nbsp;
        		<input type="text" class="text w70" name="query_end_date" id="query_end_date" value="<?php echo $_GET['query_end_date']; ?>"/>
	    		<label class="add-on">
	    			<i class="icon-calendar"></i>
	    		</label>
        </td>
        <td><label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>"/></label></td>
      </tr>
    </table>
  </form>
  <table class="ncm-default-table">
    <thead>
      <tr class="thead">
	    <th class="w80">订单日期</th>
        <th class="w100">订单号</th>
		<th class="w50">订单金额 </th>
		<th class="w70">买家</th>
		<th class="w60">消费奖励</th>
		<?php if($output['is_one']==1){?><th class="w70">上一级</th><th class="w50">奖励</th><?php }?>
		<?php if($output['is_two']==1){?><th class="w70">上二级</th><th class="w50">奖励</th><?php }?>
		<?php if($output['is_three']==1){?><th class="w70">上三级</th><th class="w50">奖励</th><?php }?>
		<th class="w80">收货日期</th>
        <th class="w80">订单状态 </th>
        <th class="w50 align-center">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if(count($output['order_list'])>0){?>
      <?php foreach($output['order_list'] as $order){?>
      <tr class="hover">
	    <td class="nowrap align-center"><?php echo date('Y-m-d',$order['add_time']);?></td>
        <td><?php echo $order['order_sn'];?></td>
		<td class="align-center"><?php echo $order['order_amount'];?></td>
        <td><?php echo $order['buyer_name'];?></td>

		<td class="align-center"><?php echo $order['goods_rebate_amount'];?></td>
		<?php if($output['is_one']==1){?>
        <td><?php echo $order['up1name'];?></td>
		<td class="align-center"><?php echo $order['one_rebate'];?></td><?php }?>
		<?php if($output['is_two']==1){?><td><?php echo $order['up2name'];?></td><td class="align-center"><?php echo $order['two_rebate'];?></td>
		<?php }?>
		<?php if($output['is_three']==1){?><td><?php echo $order['up3name'];?></td><td class="align-center"><?php echo $order['three_rebate'];?></td><?php }?>
	    <td class="nowrap align-center">
		<?php 
		if($order['finnshed_time']==0)
		{echo "";}
		else
		{echo date('Y-m-d',$order['finnshed_time']);}
		?>
		</td>
        <td class="align-center"><?php echo orderState($order);?></td>
        <td class="w20 align-center">
		<a href="index.php?gct=member_information&gp=show_order&order_id=<?php echo $order['order_id'];?>" target=_blank><?php echo $lang['nc_view'];?></a>
        </td>
      </tr>
      <?php }?>
      <?php }else{?>
      <tr class="no_data">
        <td colspan="15"><?php echo $lang['nc_no_record'];?></td>
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
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js" ></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript">

$(function(){
	//时间插件
	$('#query_start_date').datepicker({dateFormat: 'yy-mm-dd'});
	$('#query_end_date').datepicker({dateFormat: 'yy-mm-dd'});
	
	//初始化地区
	regionInit("region");
})
</script>