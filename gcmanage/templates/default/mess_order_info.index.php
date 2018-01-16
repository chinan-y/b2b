<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>通关查询</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span>订单列表</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" value="mess_order_info" name="gct">
    <input type="hidden" value="index" name="gp">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td>
           <select name="search_field_name" >
              <option <?php if($output['search_field_name'] == 'ORDER_SN'){ ?>selected='selected'<?php } ?> value="ORDER_SN">企业内部订单编号</option>
              <option <?php if($output['search_field_name'] == 'PAY_SN'){ ?>selected='selected'<?php } ?> value="PAY_SN">订单编号</option>
              <option <?php if($output['search_field_name'] == 'list_invtNo'){ ?>selected='selected'<?php } ?> value="list_invtNo">海关清单编号</option>
              <option <?php if($output['search_field_name'] == 'shipping_code'){ ?>selected='selected'<?php } ?> value="shipping_code">物流运单编号</option>
			  <option <?php if($output['search_field_name'] == 'BUYER_NAME'){ ?>selected='selected'<?php } ?> value="BUYER_NAME">订购人</option>
              <option <?php if($output['search_field_name'] == 'BUYER_IDNUM'){ ?>selected='selected'<?php } ?> value="BUYER_IDNUM">订购人身份证号码</option>
            </select>
			</td>
			<td><input type="text" value="<?php echo $output['search_field_value'];?>" name="search_field_value" class="txt"></td>
          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            <?php if($output['search_field_value'] != '' or $output['search_sort'] != ''){?>
            <a href="index.php?gct=mess_order_info&gp=index" class="btns "><span><?php echo $lang['nc_cancel_search']?></span></a>
            <?php }?>
			</td>
        </tr>
      </tbody>
    </table>
  </form>
  
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>企业内部订单编号</th>
          <th>订单编号</th>
          <th>订购人</th>
		  <th>订购人证件号码</th>
		  <th>海关清单编号</th>
		  <th>物流运单编号</th>
		  <th>订单入库<br/>回执信息</th>
		  <th>清单入库<br/>回执信息</th>
		  <th>海关审<br/>单状态</th>
		  <th>是否推<br/>送仓库</th>
		  <th>仓库出库状态</th>
		  <th>推送仓库备注信息</th>
		  <th>重推</th>
          <th class="align-center"><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['mess_order_list']) && is_array($output['mess_order_list'])){ ?>
        <?php foreach($output['mess_order_list'] as $k => $v){ ?>
		<tr class="hover edit">
			<td><a href="index.php?gct=order&gp=show_order&order_id=<?php echo $v[ORDER_ID];?>" title="查看原始订单信息" target="_blank"><?php echo $v['ORDER_SN'];?></a></td>
			<td><?php echo $v['PAY_SN'];?></td>
			<td><?php echo $v['RECEIVER_NAME'];?></td>
			<td><?php echo $v['RECEIVER_ID_NO'];?></td>
			<td><?php echo $v['list_invtNo'];?></td>	
			<td><?php echo $v['shipping_code'];?></td>
			<td><?php echo $v['OI_MEMO'];?></td>
			<td><?php echo $v['LI_MEMO'];?></td>
			<td><a title="<?php echo $v['list_info'];?>"><?php if($v['list_status']==800){echo "成功";} else{echo "未完";}?></a></td>
			<td><?php if($v['MAKE_CSV']==10){echo "否";} elseif($v['MAKE_CSV']==20){echo "是";}?></td>
			<td>
			<?php if(!empty($v['OIF_ORDER_NO'])){echo "及时达 ".$v['OIF_MEMO'];} ?>
			<?php if(!empty($v['OIF_STATUS_CODE'])){echo "玛斯特 ".$v['OIF_MEMO'];}?>
			</td>
			<td><?php echo $v['ERP_MEMO'];?></td>
			<td>
				<?php if($v['LI_SUCCESS']==2){ ?>
					<a href="index.php?gct=mess_order_info&gp=repeat_list&order_id=<?php echo $v['ORDER_ID'];?>">清单</a>
				<?php }?>
				
				<?php if($v['LI_SUCCESS']==2 && $v['OI_SUCCESS']==2){ ?>| <?php }?>
				
				<?php if($v['OI_SUCCESS']==2){?>
				<a href="index.php?gct=mess_order_info&gp=repeat_order&order_id=<?php echo $v['ORDER_ID'];?>">订单</a> 
				<?php }?>
			</td>
			<td class="align-center" style="width:150px;">
			<span>
				<a href="index.php?gct=mess_order_info&gp=mess_order_infoview&order_id=<?php echo $v['ORDER_ID'];?>">查看</a> 
				<?php if($v['MAKE_CSV']==10){ ?>
				| <a href="index.php?gct=mess_order_info&gp=mess_order_infoview&ORDER_ID=<?php echo $v['ORDER_ID'];?>&ERP_MEMO=加急处理">加急</a> 
				<?php }else {?>
				<?php echo "　　";}?>
			 </span>
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
        <?php if(!empty($output['mess_order_list']) && is_array($output['mess_order_list'])){ ?>
        <tr id="batchAction" >
          <td colspan="20" id="dataFuncs">
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
          </td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>



  
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script>
$(function(){
    $('#ncsubmit').click(function(){
    	$('input[name="gp"]').val('mess_order');$('#formSearch').submit();
    });	
});
</script>

