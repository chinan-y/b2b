<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>商品税率</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span>商品税率</span></a></li>
        <li><a href="index.php?gct=tax_rate&gp=add"><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
    <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li>**  跨境电商综合税(T) = 关税(G)*优惠(0)+消费税(C)*优惠(0.7)+增值税(Z)*优惠(0.7)　　**  跨境电商综合税率(Tr) = 0.7(消费税率(Cr)+增值税率(Zr)) / (1-消费税率(Cr))    即  Tr=0.7(Cr+Zr)/(1-Cr)</li>
            <li>**  商品含税价(Ph)  =商品不含税价(P) + 商品不含税价(P) * 跨境电商综合税率(Tr)    即Ph=P(1+Tr)</li>
            <li>**  在已知[商品含税价]的情况下，计算[商品不含税价格]和[商品跨境电商综合税税金]　　**  P = Ph(1-Cr)/(0.7(Cr+Zr)+1-Cr)  =  Ph(1-Cr)/(1-0.3Cr+0.7Zr)</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" value="tax_rate" name="gct">
    <input type="hidden" value="index" name="gp">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td>
           <select name="search_field_name" >
              <option <?php if($output['search_field_name'] == 'hs_code'){ ?>selected='selected'<?php } ?> value="hs_code">海关编码（HScode）</option>
              <option <?php if($output['search_field_name'] == 'hs_name'){ ?>selected='selected'<?php } ?> value="hs_name">海关商品名称</option>
            </select>
			</td>
			<td><input type="text" value="<?php echo $output['search_field_value'];?>" name="search_field_value" class="txt"></td>
          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            <?php if($output['search_field_value'] != '' or $output['search_sort'] != ''){?>
            <a href="index.php?gct=tax_rate&gp=index" class="btns "><span><?php echo $lang['nc_cancel_search']?></span></a>
            <?php }?>
			</td>
        </tr>
      </tbody>
    </table>
  </form>
  
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>ID</th>
          <th>HScode</th>
		  <th>海关商品名称</th>
          <th>关区</th>
		  <th>关税</th>
		  <th>一般关税</th>
		  <th>消费税</th>
		  <th>进口增值税</th>
		  <th>跨境电商综合税</th>
		  
          <th class="align-center"><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['goods_hscode_rate']) && is_array($output['goods_hscode_rate'])){ ?>
        <?php foreach($output['goods_hscode_rate'] as $k => $v){ ?>
		<tr class="hover edit">
			<td><?php echo $v['id'];?></td>
			<td><?php echo $v['hs_code'];?></td>
			<td><?php echo $v['hs_name'];?></td>
			<td><?php echo $v['region_id'];?></td>
			<td><?php echo $v['tariff'];?></td>
			<td><?php echo $v['common_tariff'];?></td>	
			<td><?php echo $v['consumption_tax'];?></td>
			<td><?php echo $v['vat_tax'];?></td>
			<td><b><?php echo $v['KJDS_TAX_RATE']*100;?>%</b></td>

			<td class="align-center" style="width:150px;">
			<span>
				<a href="index.php?gct=tax_rate&gp=view&hsid=<?php echo $v['id'];?>">查看</a> 
				| 
				<a href="index.php?gct=tax_rate&gp=edit&hsid=<?php echo $v['id'];?>">编辑</a> 
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
        <?php if(!empty($output['goods_hscode_rate']) && is_array($output['goods_hscode_rate'])){ ?>
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

