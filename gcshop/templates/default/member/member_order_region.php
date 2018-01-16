<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <form id="list_form" method="get" action="index.php" target="_self">
    <table class="ncm-search-table">
      <input type="hidden" name="gct" value="member_order_salearea" />
      <input type="hidden" name= "gp" value="member_order_region" />
      <tr>
        <th><?php echo $lang['member_order_state'];?></th>
        <td class="w100"><select name="state_type">
            <option value="" <?php echo $_GET['state_type']==''?'selected':''; ?>><?php echo $lang['member_order_all'];?></option>
            <option value="10" <?php echo $_GET['state_type']=='state_new'?'selected':''; ?>>待付款</option>
            <option value="20" <?php echo $_GET['state_type']=='state_pay'?'selected':''; ?>>待发货</option>
            <option value="30" <?php echo $_GET['state_type']=='state_send'?'selected':''; ?>>待收货</option>
            <option value="40" <?php echo $_GET['state_type']=='state_success'?'selected':''; ?>>已完成</option>
            <option value="0" <?php echo $_GET['state_type']=='state_cancel'?'selected':''; ?>>已取消</option>
          </select>
		</td>
        <th><?php echo $lang['member_order_time'];?></th>
        <td class="w340">
        	<input type="text" class="text w70" name="query_start_date" id="query_start_date" value="<?php echo $_GET['query_start_date']; ?>"/>
        	<label class="add-on">
        		<i class="icon-calendar"></i>
        	</label>&nbsp;&#8211;&nbsp;
        		<input type="text" class="text w70" name="query_end_date" id="query_end_date" value="<?php echo $_GET['query_end_date']; ?>"/>
	    		<label class="add-on">
	    			<i class="icon-calendar"></i>
	    		</label>
        </td>
        <th><?php echo $lang['member_order_sn'];?></th>
        <td class="w160"><input type="text" class="text w150" name="order_sn" value="<?php echo $_GET['order_sn']; ?>"></td>
        <td class="w70 tc"><label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>"/></label></td>
      </tr>
      <tr>
		<th>销售区域</th>
		<td><?php echo $output['sales_area']['sa_areaname'] ?>
		<input type="hidden" value="<?php echo $output['sales_area']['sa_areaid'] ?>" name="saleregion_id" id="saleregion_id">
		</td>
		<th><?php //echo $lang['member_address_location'];?></th>
        <td class="w100">
			<span style="color:blue;"><?php echo $_REQUEST['area_info'];?></span>
			<!--div id="region">
            <input type="hidden" value="<?php //echo $output['address_info']['city_id'];?>" name="city_id" id="city_id">
            <input type="hidden" name="area_id" id="area_id" value="<?php //echo $output['address_info']['area_id'];?>" class="area_ids" />
            <input type="hidden" name="area_info" id="area_info" value="<?php //echo $output['address_info']['area_info'];?>" class="area_names" />
            <?php if(!empty($output['address_info']['area_id'])){?>
            <span><?php //echo $output['address_info']['area_info'];?></span>
            <input type="button" value="<?php //echo $lang['nc_edit'];?>" class="edit_region" />
            <select style="display:none;">
            </select>
            <?php }else{?>
            <select>
            </select>
            <?php }?>
			</div-->
		</td>
        <th>&nbsp;</th>
        <td class="w160"></td>
        <td class="w70 tc"></td>
      </tr>
    </table>
  </form>
  <table class="ncm-default-table">
    <thead>
      <tr class="thead">
	    <th class="w60">订单日期</th>
        <th class="w100">订单号</th>
		<th class="w70">销售员帐号</th>
        <th class="w70">买家账号</th>
		<!--th class="w30">省级</th>
		<th class="w30">市级</th>
		<th class="w30">县级</th-->
		<th class="w200">区域</th>
		<!--th class="w200">地址</th-->
		<th class="w150">收货人信息</th>
        <th class="w50">订单金额 </th>
		<th class="w80">预返利金额</th>
        <th class="w80">支付方式 </th>
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
        <td><?php echo $order['superior_member'];?></td>
        <td><?php echo $order['buyer_name'];?></td>
		<!--td><?php //echo $order['extend_order_common']['reciver_province_id'];?></td-->
		<!--td><?php //echo $order['extend_order_common']['reciver_city_id'];?></td-->
		<!--td><?php //echo $order['extend_order_common']['reciver_area_id'];?></td-->
	    <td><?php echo $order['extend_order_common']['reciver_info']['area'];?></td>
		<!--td><?php //echo $order['extend_order_common']['reciver_info']['address'];?></td-->
		<td>
          <div class="buyer-info"> <em></em>
            <div class="con">
              <dl>
                <dd>
				<?php echo $order['extend_order_common']['reciver_name'];?>
				&nbsp;
				<?php echo $order['extend_order_common']['reciver_info']['mob_phone'];?>
				</dd>
              </dl>
            </div>
          </div>
		</td>
        <td class="align-center"><?php echo $order['order_amount'];?></td>
		<td class="align-center"><?php echo $order['goods_rebate_amount'];?></td>
        <td class="align-center"><?php if(orderPaymentName($order['payment_code'])=='wxpay'){echo "微信支付";}else{echo orderPaymentName($order['payment_code']);}?></td>
        <td class="align-center"><?php echo orderState($order);?></td>
        <td class="w20 align-center">
		<a href="index.php?gct=member_order_salearea&gp=show_order&order_id=<?php echo $order['order_id'];?>" target=_blank><?php echo $lang['nc_view'];?></a>
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