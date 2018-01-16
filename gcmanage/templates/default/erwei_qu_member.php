<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['sales_area'];?></h3>
      <ul class="tab-base">
	    <li><a href="index.php?gct=platform_order&gp=index"><span>平台合作方订单</span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['manage'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12" class="nobg"><div class="title">
            <h5><?php echo $lang['nc_prompts'];?></h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li><?php echo $lang['sales_area_help1'];?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <form method='post'>
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th><input type="checkbox" class="checkall" id="checkall_1"></th>
          <th><?php echo $lang['nc_sort'];?></th>
          <th><?php echo $lang['sales_area_name'];?>名称</th>
		  <th>销售代理区域</th>
		  <th><?php echo $lang['sales_area_manager'];?>名称</th>
		  <th><?php echo $lang['sales_area_contact'];?></th>
          <th class="align-center"><?php echo $lang['sales_area_cash'];?>(<?php echo $lang['currency_zh'];?>)</th>
		  <th class="align-center"><?php echo $lang['sales_area_rate'];?></th>
		  <th><?php echo $lang['sales_area_desc'];?></th><!-- liu merge new -->
          <th class="align-center"><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['sales_area_list']) && is_array($output['sales_area_list'])){ ?>
        <?php foreach($output['sales_area_list'] as $k => $v){ ?>
        <tr class="hover edit">
          <td class="w36"><input type="checkbox" name='check_sa_id[]' value="<?php echo $v['sa_id'];?>" class="checkitem"></td>
          <td class="w48 sort"><span title="<?php echo $lang['can_edit'];?>" ajax_branch='sales_area_sort' datatype="number" fieldid="<?php echo $v['sa_id'];?>" fieldname="sa_sort" nc_type="inline_edit" class="editable"><?php echo $v['sa_sort'];?></span></td>
          <td class="name"><?php echo $v['sa_name'];?></td>
		  <td class="name"><?php echo $v['sa_areaname'];?></td>
		  <td><?php echo $v['sa_manager'];?></td>
		  <td><?php echo $v['sa_contact'];?></td>
          <td class="align-center"><?php echo $v['sa_cash'];?></td>
		  <td class="align-center"><?php echo $v['sa_rate'];?>%</td>
		  <td><?php echo $v['sa_desc'];?> </td>
          <td class="align-center" style="width:180px;"><span><a href="index.php?gct=erwei_qu_member&gp=order&area_id=<?php echo $v['sa_areaid'];?>">销售订单</a>  |  <a href="index.php?gct=erwei_qu_member&gp=sales_area_samlist&sa_id=<?php echo $v['sa_id'];?>">团队成员</a>  |  <a href="index.php?gct=erwei_qu_member&gp=sales_area_edit&sa_id=<?php echo $v['sa_id'];?>"><?php echo $lang['nc_edit'];?></a> | <a href="javascript:if(confirm('<?php echo $lang['del_sales_area'];?>'))window.location = 'index.php?gct=sales_area&gp=sales_area_del&sa_id=<?php echo $v['sa_id'];?>';"><?php echo $lang['nc_del'];?></a> </span></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="10"><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <?php if(!empty($output['sales_area_list']) && is_array($output['sales_area_list'])){ ?>
        <tr id="batchAction" >
          <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
          <td colspan="16" id="dataFuncs"><label for="checkallBottom"><?php echo $lang['nc_select_all']; ?></label>
            &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="if(confirm('<?php echo $lang['del_sales_area'];?>')){$('form:first').submit();}"><span><?php echo $lang['nc_del'];?></span></a>
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
            </td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.sales_area.js" charset="utf-8"></script> 