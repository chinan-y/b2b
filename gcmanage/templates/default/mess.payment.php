<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>海关对接管理</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span>支付单列表</span></a></li>
		<li><a href="index.php?gct=mess_payment&gp=mess_payment_add" ><span><?php echo $lang['nc_new'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" value="mess_payment" name="gct">
    <input type="hidden" value="mess_payment" name="gp">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td>
          <!--//ming-->
          <select name="search_field_name" >
              <option <?php if($output['search_field_name'] == 'PAYMENT_NO'){ ?>selected='selected'<?php } ?> value="PAYMENT_NO">支付单号</option>
              <option <?php if($output['search_field_name'] == 'ORIGINAL_ORDER_NO'){ ?>selected='selected'<?php } ?> value="ORIGINAL_ORDER_NO">原始订单编号</option>
              
               <option <?php if($output['search_field_name'] == 'PAY_AMOUNT'){ ?>selected='selected'<?php } ?> value="PAY_AMOUNT">支付金额</option>
               
              <option <?php if($output['search_field_name'] == 'MEMO'){ ?>selected='selected'<?php } ?> value="MEMO">备注</option>
            </select>
			</td>
			<td><input type="text" value="<?php echo $output['search_field_value'];?>" name="search_field_value" class="txt"></td>
          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            <?php if($output['search_field_value'] != '' or $output['search_sort'] != ''){?>
            <a href="index.php?gct=mess_payment&gp=mess_payment" class="btns "><span><?php echo $lang['nc_cancel_search']?></span></a>
            <?php }?>
			</td>
        </tr>
      </tbody>
    </table>
  </form>
  
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th><input type="checkbox" class="checkall" id="checkall_1"></th>
          <th>支付单号</th>
          <th>原始订单编号</th>
		   <th class="align-center">支付总额</th>
          <th class="align-center">货款总额</th>
		   <th class="align-center">税金总额</th>
          <th class="align-center"><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['mess_payment_list']) && is_array($output['mess_payment_list'])){ ?>
        <?php foreach($output['mess_payment_list'] as $k => $v){ ?>
		<tr class="hover edit">
			<td class="w36"><input type="checkbox" name='check_PAYMENT_INFO_ID[]' value="<?php echo $v['PAYMENT_INFO_ID'];?>" class="checkitem"></td>
			<td><?php echo $v['PAYMENT_NO'];?></td>
			<td><?php echo $v['ORIGINAL_ORDER_NO'];?></td>
			<td class="align-center"><?php echo $v['PAY_AMOUNT'];?></td>
			<td class="align-center"><?php echo $v['GOODS_FEE'];?></td>
			<td class="align-center"><?php echo $v['TAX_FEE'];?></td>
			<td class="align-center" style="width:150px;"><span><a href="javascript:if(confirm('送检报文发送后将无法撤后，请仔细确认数据的准确性。是否确定发送？'))window.location = 'index.php?gct=mess_payment&gp=mess_send&PAYMENT_INFO_ID=<?php echo $v['PAYMENT_INFO_ID'];?>';">发送报文</a>  |  <a href="index.php?gct=mess_payment&gp=mess_payment_edit&PAYMENT_INFO_ID=<?php echo $v['PAYMENT_INFO_ID'];?>"><?php echo $lang['nc_edit'];?></a> | <a href="javascript:if(confirm('<?php echo $lang['del_mess_warning'];?>'))window.location = 'index.php?gct=mess_payment&gp=mess_payment_del&PAYMENT_INFO_ID=<?php echo $v['PAYMENT_INFO_ID'];?>';"><?php echo $lang['nc_del'];?></a> </span></td>
			<a href="javascript:if(confirm('<?php echo $lang['send_mess_warning'];?>'))window.location = 'index.php?gct=mess_payment&gp=mess_payment_del&PAYMENT_INFO_ID=<?php echo $v['PAYMENT_INFO_ID'];?>';">
		</tr>
		<?php } ?>
		<?php }else { ?>
        <tr class="no_data">
          <td colspan="10"><?php echo $lang['nc_no_record'];?></td>
        </tr>
		<?php } ?>
      </tbody>
      <tfoot>
        <?php if(!empty($output['mess_payment_list']) && is_array($output['mess_payment_list'])){ ?>
        <tr id="batchAction" >
          <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
          <td colspan="16" id="dataFuncs"><label for="checkallBottom"><?php echo $lang['nc_select_all']; ?></label>
            &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="if(confirm('<?php echo $lang['del_mess_warning'];?>')){$('form:first').submit();}"><span><?php echo $lang['nc_del'];?></span></a>
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
    	$('input[name="gp"]').val('mess_payment');$('#formSearch').submit();
    });	
});
</script>

