<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div id="transaction" class="double">
  <div class="outline">
    <div class="title">
      <h3><?php echo $lang['nc_tradeinfo'];?></h3>
      <ul>
        <li>
          <?php if ($output['home_member_info']['order_nopay_count'] > 0) { ?>
          <a href="index.php?gct=member_order&state_type=state_new"><?php echo $lang['nc_order_waitpay'];?><em><?php echo $output['home_member_info']['order_nopay_count'];?></em></a>
          <?php } else { ?>
          <?php echo $lang['nc_order_waitpay'];?><em>0</em>
          <?php } ?>
        </li>
        <li>
          <?php if ($output['home_member_info']['order_noreceipt_count'] > 0) { ?>
          <a href="index.php?gct=member_order&state_type=state_send"><?php echo $lang['nc_order_receiving'];?><em><?php echo $output['home_member_info']['order_noreceipt_count'];?></em></a>
          <?php } else { ?>
          <?php echo $lang['nc_order_receiving'];?><em>0</em>
          <?php } ?>
        </li>
        <li>
          <?php if ($output['home_member_info']['order_noeval_count'] > 0) { ?>
          <a href="index.php?gct=member_order&state_type=state_noeval"><?php echo $lang['nc_order_waitevaluate'];?><em><?php echo $output['home_member_info']['order_noeval_count'];?></em></a>
          <?php } else { ?>
          <?php echo $lang['nc_order_waitevaluate'];?><em>0</em>
          <?php } ?>
        </li>
      </ul>
    </div>
    <div class="order-list">
      <ul>
        <?php foreach($output['order_list'] as $k => $order_info) { ?>
        <li>
          <div class="ncm-goods-thumb"><a target="_blank" href="<?php echo urlShop('member_order','show_order',array('order_id'=>$order_info['order_id'])); ?>"><img src="<?php echo thumb($order_info['extend_order_goods'][0],60); ?>" /></a><em><?php echo count($order_info['extend_order_goods'])>1 ? count($order_info['extend_order_goods']) : null;?></em></div>
          <dl class="ncm-goods-info">
            <dt><a href="<?php echo urlShop('member_order','show_order',array('order_id'=>$order_info['order_id'])); ?>" target="_blank"><?php echo $order_info['extend_order_goods'][0]['goods_name']; ?></a>
              <?php if (count($order_info['extend_order_goods']) > 1) { ?>
              <span><?php echo $lang['nc_member_waitetc'] ?><strong><?php echo count($order_info['extend_order_goods']);?></strong><?php echo $lang['nc_member_kindgoods'] ?></span>
              <?php } ?>
            </dt>
            <dd><span class="order-date"><?php echo $lang['nc_member_ordertime'] ?><?php echo date('Y-m-d H:i:s',$order_info['add_time']);?></span><span class="ncm-order-price"><?php echo $lang['nc_member_orderamount'] ?><em>￥<?php echo $order_info['order_amount'];?></em></span></dd>
            <dd><span class="order-state"><?php echo $lang['nc_member_orderstatus'] ?><?php echo strip_tags(orderState($order_info));?>
              <?php if ($order_info['if_deliver']){ ?>
              <a href='index.php?gct=member_order&gp=search_deliver&order_id=<?php echo $order_info['order_id']; ?>&order_sn=<?php echo $order_info['order_sn']; ?>' target="_blank"><i class="icon-truck"></i><?php echo $lang['nc_member_looktransports'] ?></a>
              <?php } ?>
              </span> </dd>
          </dl>
          <?php if ($order_info['if_payment']) {?>
          <a href="index.php?gct=buy&gp=pay&pay_sn=<?php echo $order_info['pay_sn'];?>" target="_blank" class="ncm-btn"><?php echo $lang['nc_member_orderpayment'] ?></a>
          <?php } ?>
          <?php if ($order_info['if_receive']) { ?>
          <a href="<?php echo urlShop('member_order','show_order',array('order_id'=>$order_info['order_id'])); ?>" target="_blank" class="ncm-btn"><?php echo $lang['nc_member_confirmreceived'] ?></a>
          <?php } ?>
          <?php if ($order_info['if_evaluation']) { ?>
          <a href="index.php?gct=member_evaluate&gp=add&order_id=<?php echo $order_info['order_id']; ?>" target="_blank" class="ncm-btn"><?php echo $lang['nc_member_evaluate'] ?></a>
          <?php } ?>
          <?php if (!$order_info['if_payment'] && !$order_info['if_receive'] && !$order_info['if_evaluation']) {?>
          <a href="index.php?gct=member_order&gp=show_order&order_id=<?php echo $order_info['order_id'];?>" target="_blank" class="ncm-btn"><?php echo $lang['nc_member_lookorder'] ?></a>
          <?php } ?>
        </li>
        <?php } ?>
      </ul>
    </div>
    <?php if (empty($output['order_list'])) { ?>
    <dl class="null-tip">
      <dt></dt>
      <dd>
        <h4><?php echo $lang['nc_member_mallshopping'] ?></h4>
        <h5><?php echo $lang['nc_member_helpmessage'] ?></h5>
      </dd>
    </dl>
    <?php } ?>
  </div>
</div>
<div id="shopping" class="normal">
  <div class="outline">
    <div class="title">
      <h3><?php echo $lang['nc_cart'] ?></h3>
    </div>
    <?php if (!empty($output['cart_list']) && is_array($output['cart_list'])) { ?>
    <div class="cart-list">
      <ul>
        <?php foreach($output['cart_list'] as $cart_info) { ?>
        <li>
          <div class="ncm-goods-thumb"><a target="_blank" href="<?php echo urlShop('goods','index',array('goods_id'=>$cart_info['goods_id']));?>"><img src="<?php echo thumb($cart_info,60);?>"></a></div>
          <dl class="ncm-goods-info">
            <dt><a href="<?php echo urlShop('goods','index',array('goods_id'=>$cart_info['goods_id']));?>"><?php echo $cart_info['goods_name']; ?></a></dt>
            <dd><span class="ncm-order-price"><?php echo $lang['nc_member_mallprice']?><em>￥<?php echo $cart_info['goods_price']; ?></em></span> <span class="sale"><?php echo $lang['nc_member_promotion_xianshi']?></span></dd>
          </dl>
        </li>
        <?php } ?>
      </ul>
      <div class="more"><a href="index.php?gct=cart"><?php echo $lang['nc_member_look_cartsgoods']?></a></div>
    </div>
    <?php } else { ?>
    <dl class="null-tip">
      <dt></dt>
      <dd>
        <h4><?php echo $lang['nc_member_cartisnull']?></h4>
        <h5><?php echo $lang['nc_member_wanttoadd_cart']?></h5>
      </dd>
    </dl>
    <?php } ?>
  </div>
</div>
