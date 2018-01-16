<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="eject_con wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
<div class="mycode-tag ">
	<table class="ncm-default-table">
      <thead>
	  <tr>
		  <th class="w200">会员ID</th>
		  <th class="w150">会员名称</th>
		  <th class="w200">手机号码</th>
		  <th class="w100">会员收益</th>
		  <th class="w100">注册时间</th>
	  </tr>
	  </thead>
	  <tbody>
	  
	  <?php if(!empty($output['all_team_list']) && is_array($output['all_team_list'])){  ?>
      <?php foreach($output['all_team_list'] as $key => $val){ ?>
      	
	  <tr class="bd-line">
	  	<td class="w200">
		  <a href="http://qr.liantu.com/api.php?w=200&m=5&text=https://www.qqbsmall.com/wap/index.html?ref=<?php echo $val['member_id'] ?>" target="_blank">
		  <strong><?php echo $val['member_id'] ?></strong>
		  </a>
		  </td>
		  <td class="w150"><?php echo $val['member_name'] ?></td>
		  <td class="w200"><?php echo $val['member_mobile'] ? substr_replace($val['member_mobile'], '****', 3, 4):''; ?></td>
		  <td class="w100"><?php echo $val['member_salescredit']; ?></td>
		  <td class="w100"><?php echo @date('Y-m-d H:i:s',$val['member_time']); ?></td>
	  </tr>
	  <?php } ?>
	  <?php }else { ?>
        <tr class="no_data">
          <td colspan="11"></td>
        </tr>
        <?php } ?>
	  </tbody>
    <tfoot>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page'];?><!--a class="ncm-btn ml5" href="javascript:DialogManager.close('member_my_team');">关闭</a--> </div></td>
      </tr>
    </tfoot>
  </table>
</div>
</div>
