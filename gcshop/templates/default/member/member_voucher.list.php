<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
 </div>
  <form id="voucher_list_form" method="get">
    <table class="ncm-search-table">
      <input type="hidden" id='gct' name='gct' value='member_voucher' />
      <input type="hidden" id='gp' name='gp' value='voucher_list' />
      <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo SHOP_SITE_URL ?>/index.php?gct=pointvoucher&gp=index"><span>领取代金券</span></a></td>
        <td class="w100 tr"><select name="select_detail_state">
            <option value="0" <?php if (!$_GET['select_detail_state'] == '0'){echo 'selected=true';}?>> <?php echo $lang['voucher_voucher_state']; ?> </option>
            <?php if (!empty($output['voucherstate_arr'])){?>
            <?php foreach ($output['voucherstate_arr'] as $k=>$v){?>
            <option value="<?php echo $k;?>" <?php if ($_GET['select_detail_state'] == $k){echo 'selected=true';}?>> <?php echo $v;?> </option>
            <?php }?>
            <?php }?>
          </select></td>
        <td class="w70 tc"><label class="submit-border">
            <input type="submit" class="submit" onclick="submit_search_form()" value="<?php echo $lang['nc_search'];?>" />
          </label></td>
      </tr>
    </table>
  </form>
  <table class="ncm-default-table">
    <thead>
      <tr>
        <th class="w10"></th>
        <th class="w70"></th>
        <th class="tl">代金券编码</th>
        <th class="w80">面额（元）</th>
        <th class="w200"><?php echo $lang['voucher_voucher_indate'];?></th>
        <th class="w100"><?php echo $lang['voucher_voucher_state'];?></th>
        <th class="w100"><?php echo $lang['nc_handle'];?></th>
      </tr>
    </thead>
    <tbody>
      <?php  if (count($output['list'])>0) { ?>
      <?php foreach($output['list'] as $val){ ?>
      <tr class="bd-line">
        <td></td>
        <td><div class="ncm-goods-thumb"><a href="javascript:void(0);"><img src="<?php echo $val['voucher_t_customimg'];?>" onMouseOver="toolTip('<img src=<?php echo $val['voucher_t_customimg']; ?>>')" onMouseOut="toolTip()" /></a></div></td>
        <td class="tl"><dl class="goods-name">
            <dt>
			<?php echo $val['voucher_code'];?>
			<?php if($val['voucher_design_sku']>0) { echo "[ID".$val['voucher_design_sku']."商品专用]";} ?>
			</dt>
            <dd>
			<?php if($val['store_id']==0){echo "<b>".$val['voucher_desc']."</b>";}	else { ?>
			<a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$val['store_id']));?>" title="<?php echo $lang['voucher_voucher_storename'];?>"><?php echo $val['store_name'];}?></a>（<?php echo $lang['voucher_voucher_usecondition'];?>：<?php echo $lang['voucher_voucher_usecondition_desc'].$val['voucher_limit'].$lang['currency_zh'];?>）
			</dd>
			<dd><?php if($val['member_give_name']){echo '（用户 '.$val['member_give_name'].' 赠送）'; }?>
			</dd>
          </dl>
		</td>
        <td class="goods-price"><?php echo $val['voucher_price'];?></td>
        <td class="goods-time"><?php echo date("Y-m-d",$val['voucher_start_date']).'~'.date("Y-m-d",$val['voucher_end_date']);?></td>
        <td><?php echo $val['voucher_state_text'];?></td>
        <td class="ncm-table-handle"><?php if ($val['voucher_state'] == '1'){?>		
        <span>
			<?php if($val['store_id']==0){ ?>
			<a href="<?php echo urlShop('goods', 'index', array('goods_id'=>$val['voucher_design_sku']));?>" class="btn-green" ><p>使用</p></a>
			<?php }else{ ?>
			<a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$val['store_id']));?>" class="btn-green" ><p>使用</p></a>
			<?php } ?>
			<?php if($val['voucher_give_id']==null){ ?>
				<a href="javascript:void(0);" onclick="ajax_form('arrival_notice', '赠送他人', '<?php echo urlShop('member_voucher', 'voucher_give', array('voucher_id' => $val['voucher_id']));?>', 350);" class="btn-green"><p>赠送他人</p></a>
			<?php } ?>
		</span>		
          <?php } elseif ($val['voucher_state'] == '2'){?>
          <a href="index.php?gct=member_order&gp=show_order&order_id=<?php echo $val['voucher_order_id'];?>"><?php echo $lang['voucher_voucher_vieworder'];?></a>
          <?php }?></td>
      </tr>
      <?php }?>
      <?php } else { ?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <?php  if (count($output['list'])>0) { ?>
    <tfoot>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page'];?></div></td>
      </tr>
    </tfoot>
    <?php } ?>
  </table>
</div>
