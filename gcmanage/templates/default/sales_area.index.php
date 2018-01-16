<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['sales_area'];?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['manage'];?></span></a></li>
        <li><a href="index.php?gct=sales_area&gp=sales_area_add" ><span><?php echo $lang['nc_new'];?></span></a></li>
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
          <!--th><?php echo $lang['nc_sort'];?></th-->
		  <th><?php echo $lang['sa_id'];?>区域合作方ID</th>
          <th><?php echo $lang['sales_area_name'];?>名称</th>
		  <th><?php echo "区域团队成员";?></th>
		  <th><?php echo "授权代理区域";?></th>
		  <th><?php echo $lang['sales_area_manager'];?>[ID]</th>
		  <th><?php echo $lang['sales_area_contact'];?></th>
          <th class="align-center"><?php echo $lang['sales_area_cash'];?>(<?php echo $lang['currency_zh'];?>)</th>
		  <th class="align-center"><?php echo '推广提成比率';?></th>
		  <!--th class="align-center"><?php echo $lang['sales_area_rate'];?></th-->
		  <th class="align-center">创建时间</th>
		  <th><?php echo $lang['sales_area_desc'];?></th>
          <th class="align-center"><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['sales_area_list']) && is_array($output['sales_area_list'])){ ?>
        <?php foreach($output['sales_area_list'] as $k => $v){ ?>
        <tr class="hover edit">
          <td class="w36"><input type="checkbox" name='check_sa_id[]' value="<?php echo $v['sa_id'];?>" class="checkitem"></td>
          <!--td class="w48 sort"><span title="<?php echo $lang['can_edit'];?>" ajax_branch='sales_area_sort' datatype="number" fieldid="<?php echo $v['sa_id'];?>" fieldname="sa_sort" nc_type="inline_edit" class="editable"><?php echo $v['sa_sort'];?></span></td-->
		  <td class="name"><?php echo $v['sa_id'];?></t>
          <td class="name"><?php echo $v['sa_name'];?></td>
		  <td class="name"><a href="index.php?gct=sales_area&gp=sales_area_samlist&sa_id=<?php echo $v['sa_id'];?>">[区域团队成员]</a>  </td>
		  <td class="name"><?php echo $v['sa_areaname'];?><a href="index.php?gct=sales_area&gp=order&area_id=<?php echo $v['sa_areaid'];?>"><?php if ($v['sa_areaid']>0) echo "[区域订单]";?></a></td>
		  <td><?php echo $v['sa_manager'];?>[<?php echo $v['sa_manager_id'];?>]</td>
		  <td><?php echo $v['sa_contact'];?></td>
          <td class="align-center"><?php echo $v['sa_cash'];?></td>
		  <td class="align-center"><?php echo $v['member_rebate_rate'];?>%|<?php echo $v['sa_rate'];?>%</td>
		  <!--td class="align-center"><?php echo $v['sa_rate'];?>%</td-->
		  <td class="align-center"><?php echo date('Y-m-d H:i:s',$v['add_time']);?></td>
		  <td><?php echo $v['sa_desc'];?> </td>
          <td class="align-center" style="width:180px;"><span><a href="index.php?gct=sales_area&gp=sales_area_edit&sa_id=<?php echo $v['sa_id'];?>"><?php echo $lang['nc_edit'];?></a> | <a href="javascript:if(confirm('<?php echo $lang['del_sales_area'];?>'))window.location = 'index.php?gct=sales_area&gp=sales_area_del&sa_id=<?php echo $v['sa_id'];?>';"><?php echo $lang['nc_del'];?></a> </span></td>
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