<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>海关对接管理</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span>订单列表</span></a></li>
		<li><a href="index.php?gct=mess_order&gp=mess_order_add" ><span><?php echo $lang['nc_new'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" value="mess_order" name="gct">
    <input type="hidden" value="mess_order" name="gp">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td>
          <!--//ming-->
          <select name="search_field_name" >
              <option <?php if($output['search_field_name'] == 'ORIGINAL_ORDER_NO'){ ?>selected='selected'<?php } ?> value="ORIGINAL_ORDER_NO">原始订单号</option>
              <option <?php if($output['search_field_name'] == 'RECEIVER_ID_NO'){ ?>selected='selected'<?php } ?> value="RECEIVER_ID_NO">收货人身份证号码</option>
              
               <option <?php if($output['search_field_name'] == 'RECEIVER_NAME'){ ?>selected='selected'<?php } ?> value="RECEIVER_NAME">收货人姓名</option>
               
              <option <?php if($output['search_field_name'] == 'RECEIVER_TEL'){ ?>selected='selected'<?php } ?> value="RECEIVER_TEL">收货人电话号码</option>
            </select>
			</td>
			<td><input type="text" value="<?php echo $output['search_field_value'];?>" name="search_field_value" class="txt"></td>
          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            <?php if($output['search_field_value'] != '' or $output['search_sort'] != ''){?>
            <a href="index.php?gct=mess_order&gp=mess_order" class="btns "><span><?php echo $lang['nc_cancel_search']?></span></a>
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
          <th>原始订单编号</th>
          <th>收货人姓名</th>
		   <th>收货人电话</th>
		   <th>身份证号码</th>
          <th class="align-center">货款总额</th>
		   <th class="align-center">税金总额</th>
          <th class="align-center"><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['mess_order_list']) && is_array($output['mess_order_list'])){ ?>
        <?php foreach($output['mess_order_list'] as $k => $v){ ?>
		<tr class="hover edit">
			<td class="w36"><input type="checkbox" name='check_ORDER_ID[]' value="<?php echo $v['ORDER_ID'];?>" class="checkitem"></td>
			<td><?php echo $v['ORIGINAL_ORDER_NO'];?></td>
			<td><?php echo $v['RECEIVER_NAME'];?></td>
			<td><?php echo $v['RECEIVER_TEL'];?></td>
			<td><?php echo $v['RECEIVER_ID_NO'];?></td>
			<td class="align-center"><?php echo $v['GOODS_FEE'];?></td>
			<td class="align-center"><?php echo $v['TAX_FEE'];?></td>
			<td class="align-center" style="width:150px;"><span><a href="javascript:if(confirm('送检报文发送后将无法撤后，请仔细确认数据的准确性。是否确定发送？'))window.location = 'index.php?gct=mess_order&gp=mess_send&ORDER_ID=<?php echo $v['ORDER_ID'];?>';">发送报文</a>
			  |  <a href="index.php?gct=mess_order&gp=mess_order_edit&ORDER_ID=<?php echo $v['ORDER_ID'];?>"><?php echo $lang['nc_edit'];?></a> | <a href="javascript:if(confirm('<?php echo $lang['del_mess_warning'];?>'))window.location = 'index.php?gct=mess_order&gp=mess_order_del&ORDER_ID=<?php echo $v['ORDER_ID'];?>';"><?php echo $lang['nc_del'];?></a> </span></td>
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
    	$('input[name="gp"]').val('mess_order');$('#formSearch').submit();
    });	
});
</script>

