<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <form id="list_form" method="get" action="index.php" target="_self">
    <table class="ncm-search-table">
      <input type="hidden" name="gct" value="member_order_salearea" />
	  <input type="hidden" name= "gp" value="mgtsalescreditlog" />
      <input type="hidden" name= "recycle" value="<?php echo $_GET['recycle'];?>" />
      <tr>
	    <th>销售员</th>
        <td><select name="saleman_id">
            <option value="" <?php echo $_GET['saleman_id']==''?'selected':''; ?>>全部</option>
			<?php if(!empty($output['all_team_list']) && is_array($output['all_team_list'])){  ?>
			<?php foreach($output['all_team_list'] as $key => $val){ ?>
			<option value="<?php echo $val['member_id']; ?>"><?php echo $val['member_name']; ?></option>
			<?php }?>
		    <?php }?>
          </select>
		</td>
        <th>完成时间</th>
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
        <th>买家帐号订单编号</th>
        <td class="w160"><input type="text" class="text w150" name="order_sn" value="<?php echo $_GET['order_sn']; ?>"></td>
		<td colspan="9">&nbsp;&nbsp;
		<label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>"/></label>
		</td>
      </tr>
    </table>
  </form>
  <table class="ncm-default-table">
    <thead>
      <tr class="thead">
	    <th class="w40">完成时间</th>
	    <th class="w70">团队名称</th>
		<th class="w50">销售员帐号</th>
		<th class="w50">销售业绩</th>
        <th class="w50">&nbsp; </th>
		<th class="w400">详细信息</th>

      </tr>
    </thead>
    <tbody>
      <?php if(count($output['list_log'])>0){?>
      <?php foreach($output['list_log'] as $order){?>
      <tr class="hover">
	  	    <td class="nowrap align-center"><?php echo date('Y-m-d',$order['sc_addtime']);?></td>
	  		<td class="align-center"><?php echo $order['sa_name'];?></td>
        <td><?php echo $order['sc_membername'];?></td>
        <td><?php echo $order['sc_points'];?></td>
        <td>
		<?php 
	              	switch ($order['sc_stage']){
	              		case 'regist':
	              			echo '用户注册';
	              			break;
	              		case 'rebate':
	              			echo '推广销售';
	              			break;
	              		case 'order':
	              			echo '订单消费';
	              			break;
	              		case 'system':
	              			echo '系统操作';
	              			break;
						case 'salerebate':
	              			echo '返利提现';
	              			break;
	              	}
	    ?>
		</td>
        <td class="align-left"><?php echo $order['sc_desc'];?></td>
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