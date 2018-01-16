<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['order_manage'];?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['manage'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="gct" value="order" />
    <input type="hidden" name="gp" value="index" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
         <th><label><?php echo $lang['order_number'];?></label></th>
         <td><input class="txt2" type="text" name="order_sn" value="<?php echo $_GET['order_sn'];?>" /></td>
         <th><?php echo $lang['store_name'];?></th>
         <td><input class="txt-short" type="text" name="store_name" value="<?php echo $_GET['store_name'];?>" /></td>
         <th><label><?php echo $lang['order_state'];?></label></th>
          <td colspan="4"><select name="order_state" class="querySelect">
              <option value=""><?php echo $lang['nc_please_choose'];?></option>
              <option value="10" <?php if($_GET['order_state'] == '10'){?>selected<?php }?>><?php echo $lang['order_state_new'];?></option>
              <option value="20" <?php if($_GET['order_state'] == '20'){?>selected<?php }?>><?php echo $lang['order_state_pay'];?></option>
              <option value="30" <?php if($_GET['order_state'] == '30'){?>selected<?php }?>><?php echo $lang['order_state_send'];?></option>
              <option value="40" <?php if($_GET['order_state'] == '40'){?>selected<?php }?>><?php echo $lang['order_state_success'];?></option>
              <option value="0" <?php if($_GET['order_state'] == '0'){?>selected<?php }?>><?php echo $lang['order_state_cancel'];?></option>
            </select></td>
        
        </tr>
        <tr>
          <th><label for="query_start_time"><?php echo $lang['order_time_from'];?></label></th>
          <td><input class="txt date" type="text" value="<?php echo $_GET['query_start_time'];?>" id="query_start_time" name="query_start_time">
            <label for="query_start_time">~</label>
            <input class="txt date" type="text" value="<?php echo $_GET['query_end_time'];?>" id="query_end_time" name="query_end_time"/></td>
         <th><?php echo $lang['buyer_name'];?></th>
         <td><input class="txt-short" type="text" name="buyer_name" value="<?php echo $_GET['buyer_name'];?>" /></td>
		 <th><?php echo $lang['buyer_id'];?></th>
         <td><input class="txt-short" type="text" name="buyer_id" value="<?php echo $_GET['buyer_id'];?>" /></td> 
		 <th>付款方式</th>
         <td>
            <select name="payment_code" class="w100">
            <option value=""><?php echo $lang['nc_please_choose'];?></option>
            <?php foreach($output['payment_list'] as $val) { ?>
            <option <?php if($_GET['payment_code'] == $val['payment_code']){?>selected<?php }?> value="<?php echo $val['payment_code']; ?>"><?php echo $val['payment_name']; ?></option>
            <?php } ?>
			<option <?php if($_GET['payment_code'] == 'ccbpay'){?>selected<?php }?> value="ccbpay"><?php echo '建行支付';?></option>
			<option <?php if($_GET['payment_code'] == 'wxapppay'){?>selected<?php }?> value="wxapppay"><?php echo '微信APP支付';?></option>
			<option <?php if($_GET['payment_code'] == 'wxminipay'){?>selected<?php }?> value="wxminipay"><?php echo '微信小程序支付';?></option>
			<option <?php if($_GET['payment_code'] == 'wxapp_ys'){?>selected<?php }?> value="wxapp_ys"><?php echo '原生APP微信支付';?></option>
			<option <?php if($_GET['payment_code'] == 'czypay'){?>selected<?php }?> value="czypay"><?php echo '彩之云支付';?></option>
			<option <?php if($_GET['payment_code'] == 'tonglian'){?>selected<?php }?> value="tonglian"><?php echo '通联支付';?></option>
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
            <li><?php echo $lang['order_help2'];?></li>
            <li><?php echo $lang['order_help3'];?></li>
			<li>待申报是针对保税商品 如果是大贸商品就表示已经设置发货，<em style="color:#30c5ab;">已申报</em>是订单推送到了海关，<em style="color:#0468e8;">申报成功</em>是订单已经海关审核通过，<em style="color:#9007f5;">已入库</em>是订单推送到了保税仓库，<em style="color:#f5077e;">待收货</em>是保税仓的货物已出库，<em style="color:red;">撤单成功</em>是海关放行的订单用户需退款 撤销清单成功</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <!--<div style="text-align:right;"><a class="btns" target="_blank" href="index.php?<?php echo $_SERVER['QUERY_STRING'];?>&gp=export_step1"><span><?php echo $lang['nc_export'];?>Excel</span></a></div> -->
  <table class="table tb-type2 nobdb">
    <thead>
      <tr class="thead">
        <th><?php echo $lang['order_number'];?></th>
        <th><?php echo $lang['store_name'];?></th>
        <th><?php echo $lang['buyer_name'];?></th>
        <th><?php echo $lang['buyer_id'];?></th>
        <th class="align-center"><?php echo $lang['order_time'];?></th>
        <th class="align-center"><?php echo $lang['order_total_price'];?></th>
        <th class="align-center"><?php echo $lang['payment'];?></th>
        <th class="align-center"><?php echo $lang['order_state'];?></th>
        <th class="align-center"><?php echo $lang['order_rebate'];?></th>
        <th class="align-center"><?php echo '一级';?></th>
		<?php if(C('two_rank_rebate')){ ?>
			<th class="align-center"><?php echo '二级';?></th>
		<?php }?>
		<?php if(C('three_rank_rebate')){ ?>
			<th class="align-center"><?php echo '三级';?></th>
		<?php }?>
        <th class="align-center"><?php echo '平台';?></th>
        <th class="align-center"><?php echo '区域';?></th>
        <th class="align-center"><?php echo $lang['nc_handle'];?></th>
      </tr>
    </thead>

    <tbody>
      <?php if(count($output['order_list'])>0){?>
    <?php foreach($output['order_list'] as $order){  ?>
      <tr class="hover">
        <td><?php echo $order['order_sn'];?></td>
        <td><?php echo $order['store_name'];?></td>
        <td><?php echo $order['buyer_name'];?></td>
        <td><?php echo $order['buyer_id'];?></td>
        <td class="nowrap align-center"><?php echo date('Y-m-d H:i:s',$order['add_time']);?></td>
        <td class="align-center"><?php echo $order['order_amount'];?></td>
        <td class="align-center"><?php echo orderPaymentName($order['payment_code']);?></td>
		<?php if($order['lib_state'] && $order['order_state'] != '40'){ ?>
			<?php if($order['order_state'] == '30' && $order['mess_state'] == '20' && $order['lib_state']['list_status'] != '800' && $order['lib_state']['list_status'] != '399'){?>
				<td class="align-center" style="color:#30c5ab;"><?php echo '已申报';?></td>
				
			<?php }else if($order['lib_state']['list_status'] == '800' && $order['lib_state']['MAKE_CSV'] == '10'){?>
				<td class="align-center" style="color:#0468e8;"><?php echo '申报成功';?></td>
				
			<?php }else if($order['lib_state']['MAKE_CSV'] == '20' && $order['lib_state']['OIF_MEMO'] == null && $order['lib_state']['list_status'] != '399'){?>
				<td class="align-center" style="color:#9007f5;"><?php echo '已入库';?></td>
				
			<?php }else if($order['lib_state']['MAKE_CSV'] == '20' && $order['lib_state']['OIF_MEMO']) { ?>
				<td class="align-center" style="color:#f5077e;"><?php echo '待收货';?></td>
				
			<?php }else if($order['lib_state']['list_status'] == '399') { ?>
				<td class="align-center" style="color:red;"><?php echo '撤单成功';?></td>
			<?php } ?>
			
		<?php }else{ ?>
			<td class="align-center"><?php echo orderState($order);?></td>
		<?php } ?>
         <td class="align-center"><?php echo $order['goods_rebate_amount'];?></td>
         <td class="align-center"><?php echo $order['one_rebate'];?></td>
		 <?php if(C('two_rank_rebate')){ ?>
			<td class="align-center"><?php echo $order['two_rebate'];?></td>
		 <?php }?>
		 <?php if(C('three_rank_rebate')){ ?>
			<td class="align-center"><?php echo $order['three_rebate'];?></td>
		 <?php }?>
         <td class="align-center"><?php echo $order['platform_rebate'];?></td>
         <td class="align-center"><?php echo $order['area_rebate'];?></td>
        <td class="w144 align-center"><a href="index.php?gct=order&gp=show_order&order_id=<?php echo $order['order_id'];?>"><?php echo $lang['nc_view'];?></a>

        <!-- 取消订单 -->
    		<?php if($order['if_cancel']) {?>
        	| <a href="javascript:void(0)" onclick="if(confirm('<?php echo $lang['order_confirm_cancel'];?>')){location.href='index.php?gct=order&gp=change_state&state_type=cancel&order_id=<?php echo $order['order_id']; ?>'}">
        	<?php echo $lang['order_change_cancel'];?></a>
        	<?php }?>

        	<!-- 收款 -->
    		<?php if($order['if_system_receive_pay']) {?>
	        	| <a href="index.php?gct=order&gp=change_state&state_type=receive_pay&order_id=<?php echo $order['order_id']; ?>">
	        	<?php echo $lang['order_change_received'];?></a> 
    		<?php }?>
    			<?php if(($order['goods_rebate_amount'] >0 || $order['platform_rebate'] >0 || $order['area_rebate'] >0) && $order['order_state'] < 40 && orderState($order) != '已取消'){?>
    			| <a href="javascript:void(0)" onclick ="var reason = prompt('请输入<?php echo $order['order_sn'];?>订单取消的理由:'); if(reason != null){ location.href='index.php?gct=order&gp=reason&reason='+ reason +'&order_id=<?php echo $order['order_id']; ?>' }"  ><?php echo $lang['order_cancel_rebate']; ?></a>
        		<?php }?>
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
