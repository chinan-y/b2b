<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>海关对接管理</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['mess_sku'];?></span></a></li>
		<li><a href="index.php?gct=mess_sku&gp=mess_sku_add" ><span><?php echo $lang['nc_new'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" value="mess_sku" name="gct">
    <input type="hidden" value="mess_sku" name="gp">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td>
          <!--//ming-->
          <select name="search_field_name" >
              <option <?php if($output['search_field_name'] == 'SKU'){ ?>selected='selected'<?php } ?> value="SKU">商品货号</option>
              <option <?php if($output['search_field_name'] == 'GOODS_NAME'){ ?>selected='selected'<?php } ?> value="GOODS_NAME">商品名称</option>
              
               <option <?php if($output['search_field_name'] == 'POST_TAX_NO'){ ?>selected='selected'<?php } ?> value="POST_TAX_NO">行邮税号</option>
               
              <option <?php if($output['search_field_name'] == 'member_truename'){ ?>selected='selected'<?php } ?> value="member_truename">商品HS编码</option>
            </select>
			</td>
			<td><input type="text" value="<?php echo $output['search_field_value'];?>" name="search_field_value" class="txt"></td>
          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            <?php if($output['search_field_value'] != '' or $output['search_sort'] != ''){?>
            <a href="index.php?gct=mess_sku&gp=mess_sku" class="btns "><span><?php echo $lang['nc_cancel_search']?></span></a>
            <?php }?>
			</td>
        </tr>
      </tbody>
    </table>
  </form>
  
<!--    <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12" class="nobg"><div class="title">
            <h5><?php echo $lang['nc_prompts'];?></h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li><?php echo $lang['mess_skulist_help1'];?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>
-->
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th><input type="checkbox" class="checkall" id="checkall_1"></th>
          <th>商品货号</th>
          <th>商品名称</th>
		   <th>规格型号</th>
		   <th>行邮税号</th>
          <th>商品HS编码</th>
		   <th class="align-center">审批状态</th>
          <th class="align-center"><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['mess_sku_list']) && is_array($output['mess_sku_list'])){ ?>
        <?php foreach($output['mess_sku_list'] as $k => $v){ ?>
		<tr class="hover edit">
			<td class="w36"><input type="checkbox" name='check_SKU_INFO_ID[]' value="<?php echo $v['SKU_INFO_ID'];?>" class="checkitem"></td>
			<td><?php echo $v['SKU'];?></td>
			<td><?php echo $v['GOODS_NAME'];?></td>
			<td><?php echo $v['GOODS_SPEC'];?></td>
			<td><?php echo $v['POST_TAX_NO'];?></td>
			<td><?php echo $v['HS_CODE'];?></td>
			<td class="align-center">海关： | 国检： </td>
			<td class="align-center" style="width:150px;"><span><a href="javascript:if(confirm('送检报文发送后将无法撤后，请仔细确认数据的准确性。是否确定发送？'))window.location = 'index.php?gct=mess_sku&gp=mess_send&SKU_INFO_ID=<?php echo $v['SKU_INFO_ID'];?>';">发送报文</a>  |  <a href="index.php?gct=mess_sku&gp=mess_sku_edit&SKU_INFO_ID=<?php echo $v['SKU_INFO_ID'];?>"><?php echo $lang['nc_edit'];?></a> | <a href="javascript:if(confirm('<?php echo $lang['del_mess_warning'];?>'))window.location = 'index.php?gct=mess_sku&gp=mess_sku_del&SKU_INFO_ID=<?php echo $v['SKU_INFO_ID'];?>';"><?php echo $lang['nc_del'];?></a> </span></td>
		</tr>
		<?php } ?>
		<?php }else { ?>
        <tr class="no_data">
          <td colspan="10"><?php echo $lang['nc_no_record'];?></td>
        </tr>
		<?php } ?>
      </tbody>
      <tfoot>
        <?php if(!empty($output['mess_sku_list']) && is_array($output['mess_sku_list'])){ ?>
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
    	$('input[name="gp"]').val('mess_sku');$('#formSearch').submit();
    });	
});
</script>

