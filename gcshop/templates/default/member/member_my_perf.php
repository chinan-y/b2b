<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="eject_con wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
<div class="mycode-tag ">
  <table class="ncm-default-table">
    <thead>
      <tr>
        <th class="w150">时间</th>
        <th class="w100">金额(元)</th>
        <th class="w100">操作</th>
        <th class="">描述</th>
      </tr>
    </thead>
    <tbody>
      <?php  if (count($output['list_log'])>0) { ?>
      <?php foreach($output['list_log'] as $val) { ?>
      <tr class="bd-line">
        <td class="goods-time"><?php echo @date('Y-m-d H:m:s',$val['sc_addtime']);?></td>
        <td class="goods-price"><?php echo ($val['sc_points'] > 0 ? '+' : '').$val['sc_points']; ?></td>
        <td><?php 
	              	switch ($val['sc_stage']){
	              		case 'regist':
	              			echo '用户注册';
	              			break;
	              		case 'rebate':
	              			echo '推广销售';
	              			break;
	              		case 'order':
	              			echo '订单消费';
	              			break;
	              		case 'system':
	              			echo '系统操作';
	              			break;
						case 'salerebate':
	              			echo '返利提现';
	              			break;
	              	}
	              ?></td>
        <td class=""><?php echo $val['sc_desc'];?></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr class="no_data">
          <td colspan="11"><strong style="color:#360">您还没有任何销售记录。</strong></td>
        </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page'];?><!--a class="ncm-btn ml5" href="javascript:DialogManager.close('member_my_perf');">关闭</a--> </div></td>
      </tr>
    </tfoot>
  </table>
</div>
</div>