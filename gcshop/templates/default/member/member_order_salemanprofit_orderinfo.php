<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="ncm-oredr-show">
  <div class="ncm-order-info">
    <div class="ncm-order-details">
      <div class="title"><?php echo $lang['member_show_order_info'];?>订单信息</div>
      <div class="content">
	    <dl>
		  <table width="100%">
		  <?php if($output['is_three']==1) {?>
		  <tr>
		  <td>
		  <dd><?php echo "UP3用户".$lang['nc_colon'];?><span><?php echo $output['upname']['']['up3name'];?></span></dd></td>
		  <td><dd>UP3推广奖励：<span><?php echo $output['order_info']['three_rebate'];?></span></dd></td>
		  </tr>
		  <?php } ?>
		  <?php if($output['is_two']==1) {?>
		  <tr>
		  <td>
          <dd><?php echo "UP2用户".$lang['nc_colon'];?><span><?php echo $output['upname']['']['up2name'];?></span></dd></td>
		  <td><dd>UP2推广奖励：<span><?php echo $output['order_info']['two_rebate'];?></span></dd></td>
		  </tr>
		  <?php } ?>
		  <?php if($output['is_one']==1) {?>
		  <tr>
		  <td>
          <dd><?php echo "UP1用户".$lang['nc_colon'];?><span><?php echo $output['upname']['']['up1name'];?></span></dd></td>
		  <td><dd>UP1推广奖励：<span><?php echo $output['order_info']['one_rebate'];?></span></dd></td>
		  </tr>
		  <?php } ?>
		  <tr>
		  <td>
          <dd><?php echo "买家".$lang['nc_colon'];?><span><?php echo $output['order_info']['buyer_name'];?></span></dd></td>
		  <td><dd>消费奖励：<span><?php echo $output['order_info']['goods_rebate_amount'];?></span></dd></td>
		  </tr>
		  </table>
		  
        </dl>
        <dl>
          <dt>买家留言<?php echo $lang['member_show_order_buyer_message'].$lang['nc_colon'];?></dt>
          <dd><?php echo $output['order_info']['extend_order_common']['order_message']; ?></dd>
        </dl>
        <dl class="line">
          <dt>订单编号<?php echo $lang['member_change_order_no'].$lang['nc_colon'];?></dt>
          <dd><?php echo $output['order_info']['order_sn']; ?><a href="javascript:void(0);">更多<i class="icon-angle-down"></i>
            <div class="more"><span class="arrow"></span>
              <ul>
                <?php if($output['order_info']['payment_name']) { ?>
                <li>支付方式<?php echo $lang['member_order_pay_method'].$lang['nc_colon'];?><span><?php echo $output['order_info']['payment_name']; ?>
                  <?php if($output['order_info']['payment_code'] != 'offline' && !in_array($output['order_info']['order_state'],array(ORDER_STATE_CANCEL,ORDER_STATE_NEW))) { ?>
                  (<?php echo '付款单号'.$lang['nc_colon'];?><?php echo $output['order_info']['pay_sn']; ?>)
                  <?php } ?>
                  </span> </li>
                <?php } ?>
                <li><?php echo $lang['member_order_time'].$lang['nc_colon'];?><span><?php echo date("Y-m-d H:i:s",$output['order_info']['add_time']); ?></span></li>
                <?php if(intval($output['order_info']['payment_time'])) { ?>
                <li><?php echo $lang['member_show_order_pay_time'].$lang['nc_colon'];?><span><?php echo date("Y-m-d H:i:s",$output['order_info']['payment_time']); ?></span></li>
                <?php } ?>
                <?php if($output['order_info']['extend_order_common']['shipping_time']) { ?>
                <li><?php echo $lang['member_show_order_send_time'].$lang['nc_colon'];?><span><?php echo date("Y-m-d H:i:s",$output['order_info']['extend_order_common']['shipping_time']); ?></span></li>
                <?php } ?>
                <?php if(intval($output['order_info']['finnshed_time'])) { ?>
                <li><?php echo $lang['member_show_order_finish_time'].$lang['nc_colon'];?><span><?php echo date("Y-m-d H:i:s",$output['order_info']['finnshed_time']); ?></span></li>
                <?php } ?>
              </ul>
            </div>
            </a></dd>
        </dl>
        <dl>
          <dt>商家<?php echo $lang['member_show_order_seller_info'].$lang['nc_colon'];?></dt>
          <dd><?php echo $output['order_info']['extend_store']['store_name']; ?><a href="javascript:void(0);">更多<i class="icon-angle-down"></i>
            <div class="more"><span class="arrow"></span>
              <ul>
                <li>所在地区<?php echo $lang['member_address_location'].$lang['nc_colon'];?><span><?php echo $output['order_info']['extend_store']['area_info'].'&nbsp;'.$output['order_info']['extend_store']['store_address']; ?></span></li>
                <li>联系电话：<span><?php echo $output['order_info']['extend_store']['store_phone']; ?></span></li>
              </ul>
            </div>
            </a>
            <div class="msg"> <span member_id="<?php echo $output['order_info']['extend_store']['member_id'];?>"></span>
              <?php if(!empty($output['order_info']['extend_store']['store_qq'])){?>
              <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $output['order_info']['extend_store']['store_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $order_info['extend_store']['store_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $output['order_info']['extend_store']['store_qq'];?>:52" style=" vertical-align: middle;"/></a>
              <?php }?>
              <!-- wang wang -->
              <?php if(!empty($output['order_info']['extend_store']['store_ww'])){?>
              <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo $output['order_info']['extend_store']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>"  class="vm" ><img border="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $output['order_info']['extend_store']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="Wang Wang"  style=" vertical-align: middle;"/></a>
              <?php }?>
            </div>
          </dd>
        </dl>
      </div>
    </div>
    <?php if ($output['order_info']['order_state'] == ORDER_STATE_CANCEL) { ?>
    <div class="ncm-order-condition">
      <dl>
        <dt><i class="icon-off orange"></i>订单状态：</dt>
        <dd>交易关闭</dd>
      </dl>
      <ul>
        <li><?php echo $output['order_info']['close_info']['log_role'];?> 于 <?php echo date('Y-m-d H:i:s',$output['order_info']['close_info']['log_time']);?> <?php echo $output['order_info']['close_info']['log_msg'];?></li>
      </ul>
    </div>
    <?php } ?>
    <?php if ($output['order_info']['order_state'] == ORDER_STATE_NEW) { ?>
    <div class="ncm-order-condition">
      <dl>
        <dt><i class="icon-ok-circle green"></i>订单状态：</dt>
        <dd>订单已经提交，等待买家付款</dd>
      </dl>
      <ul>
        <li>如果用户未对该笔订单进行支付操作，系统将于
          <time><?php echo date('Y-m-d H:i:s',$output['order_info']['order_cancel_day']);?></time>
          自动关闭该订单。</li>
      </ul>
    </div>
    <?php } ?>
    <?php if ($output['order_info']['order_state'] == ORDER_STATE_PAY) { ?>
    <div class="ncm-order-condition">
      <dl>
        <dt><i class="icon-ok-circle green"></i>订单状态：</dt>
        <dd>
          <?php if ($output['order_info']['payment_code'] == 'offline') { ?>
          订单已提交，等待卖家发货
          <?php } else { ?>
          已支付成功
          <?php } ?>
        </dd>
      </dl>
      <ul>
        <?php if ($output['order_info']['payment_code'] == 'offline') { ?>
        <li>1. 货到付款方式下单成功。</li>
        <li>2. 订单已提交商家进行备货发货准备。</li>
        <?php } else { ?>
        <li>1. 已使用“<?php echo orderPaymentName($output['order_info']['payment_code']);?>”方式成功对订单进行支付，支付单号 “<?php echo $output['order_info']['pay_sn'];?>”。</li>
        <li>2. 订单已提交商家进行备货发货准备。</li>
        <?php } ?>
      </ul>
    </div>
    <?php } ?>
    <?php if ($output['order_info']['order_state'] == ORDER_STATE_SEND) { ?>
    <div class="ncm-order-condition">
      <dl>
        <dt><i class="icon-ok-circle green"></i>订单状态：</dt>
        <dd>商家已发货
          <?php if ($output['order_info']['extend_order_common']['dlyo_pickup_code'] != '') { ?>
          ， 提货码：<?php echo $output['order_info']['extend_order_common']['dlyo_pickup_code'];?>
          <?php } ?>
        </dd>
      </dl>
      <ul>
        <li>1. 商品已发出；
          <?php if ($output['order_info']['shipping_code'] != '') { ?>
          物流公司：<?php echo $output['order_info']['express_info']['e_name']?>；单号：<?php echo $output['order_info']['shipping_code'];?>。
          <?php if ($output['order_info']['if_deliver']) { ?>
          查看 <a href="#order-step" class="blue">“物流跟踪”</a> 情况。
          <?php } ?>
          <?php } else { ?>
          无需要物流。
          <?php } ?>
        </li>
        <li>2. 已收到货。</li>
        <li>3. 系统将于
          <time><?php echo date('Y-m-d H:i:s',$output['order_info']['order_confirm_day']);?></time>
          自动完成“确认收货”，完成交易。</li>
      </ul>
    </div>
    <?php } ?>
    <?php if ($output['order_info']['order_state'] == ORDER_STATE_SUCCESS) { ?>
    <?php if ($output['order_info']['evaluation_state'] == 1) { ?>
    <div class="ncm-order-condition">
      <dl>
        <dt><i class="icon-ok-circle green"></i>订单状态：</dt>
        <dd>评价完成。</dd>
      </dl>
      <ul>
        <li>1. 您已对该笔订单进行了商品及交易评价，感谢您的支持，祝您购物愉快！</li>
        <li>2. 将感兴趣的<a href="index.php?gct=member_favorites&gp=fglist" class="ncm-btn-mini">收藏商品</a>加入购物车进行购买。</li>
        <li>3. 看一看<a href="<?php echo urlShop('show_store','index',array('store_id'=>$output['order_info']['store_id']), $output['order_info']['extend_store']['store_domain']);?>" class="ncm-btn-mini">该店铺</a>中有什么新商品上架。</li>
      </ul>
    </div>
    <?php } else { ?>
    <div class="ncm-order-condition">
      <dl>
        <dt><i class="icon-ok-circle green"></i>订单状态：</dt>
        <dd>已经收货。</dd>
      </dl>
      <ul>
        <li>1. 如果收到货后出现问题，可以联系商家协商解决。</li>
        <li>2. 如果商家没有履行应尽的承诺，可以申请投诉维权。</li>
        <?php if ($output['order_info']['if_evaluation']) { ?>
        <li>3. 交易已完成，等待对商品及商家的服务进行评价及晒单。</li>
        <?php } ?>
      </ul>
    </div>
    <?php } ?>
    <?php } ?>
  </div>
  <?php if ($output['order_info']['order_state'] != ORDER_STATE_CANCEL) { ?>
  <div id="order-step" class="ncm-order-step">
    <dl class="step-first <?php if ($output['order_info']['order_state'] != ORDER_STATE_CANCEL) echo 'current';?>">
      <dt>生成订单</dt>
      <dd class="bg"></dd>
      <dd class="date" title="<?php echo $lang['member_order_time'];?>"><?php echo date("Y-m-d H:i:s",$output['order_info']['add_time']); ?></dd>
    </dl>
    <?php if ($output['order_info']['payment_code'] != 'offline') { ?>
    <dl class="<?php if(intval($output['order_info']['payment_time'])) echo 'current'; ?>">
      <dt>完成付款</dt>
      <dd class="bg"> </dd>
      <dd class="date" title="<?php echo $lang['member_show_order_pay_time'];?>"><?php echo date("Y-m-d H:i:s",$output['order_info']['payment_time']); ?></dd>
    </dl>
    <?php } ?>
    <dl class="<?php if($output['order_info']['extend_order_common']['shipping_time']) echo 'current'; ?>">
    <?php if($output['order_info']['extend_order_goods'][0]['store_from'] ==1 || $output['order_info']['extend_order_goods'][0]['store_from'] ==2){?>
      <dt>推送海关</dt>
	<?php }else{?>
	  <dt>商家发货</dt>
	<?php }?>
      <dd class="bg"> </dd>
      <dd class="date" title="<?php echo $lang['member_show_order_send_time'];?>"><?php echo date("Y-m-d H:i:s",$output['order_info']['extend_order_common']['shipping_time']); ?></dd>
    </dl>
    <dl class="<?php if(intval($output['order_info']['finnshed_time'])) { ?>current<?php } ?>">
      <dt>确认收货</dt>
      <dd class="bg"> </dd>
      <dd class="date" title="<?php echo $lang['member_show_order_finish_time'];?>"><?php echo date("Y-m-d H:i:s",$output['order_info']['finnshed_time']); ?></dd>
    </dl>
    <dl class="<?php if($output['order_info']['evaluation_state'] == 1) { ?>current<?php } ?>">
      <dt>评价</dt>
      <dd class="bg"></dd>
      <dd class="date" title="评价时间"><?php echo date("Y-m-d H:i:s",$output['order_info']['extend_order_common']['evaluation_time']); ?></dd>
    </dl>
  </div>
  <?php } ?>
  <div class="ncm-order-contnet">
    <table class="ncm-default-table order">
      <thead>
        <tr>
          <th class="w10"></th>
          <th colspan="2">商品名称<?php echo $lang['member_order_goods_name'];?></th>
          <th class="w20"></th>
          <th class="w120 tl"><?php echo $lang['member_order_price'];?></th>
          <th class="w60">税金</th>
          <th class="w60">数量<?php echo $lang['member_order_amount'];?></th>
		  <th class="w120 tl">商品返利率</th>
          <th class="w100">优惠活动</th>
          <th class="w100">售后维权</th>
          <th class="w100">交易状态</th>
          <th class="w100">交易操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($output['order_info']['shipping_code'])) { ?>
        <tr>
          <th colspan="7" style="border-right: none;"> <div class="order-deliver"><span>物流公司： <a target="_blank" href="<?php echo $output['order_info']['express_info']['e_url'];?>"><?php echo $output['order_info']['express_info']['e_name'];?></a></span><span> 物流单号<?php echo $lang['member_show_order_shipping_no'].$lang['nc_colon'];?> <?php echo $output['order_info']['shipping_code'];?> </span><span><a href="javascript:void(0);" id="show_shipping">物流跟踪<i class="icon-angle-down"></i>
              <div class="more"><span class="arrow"></span>
                <ul id="shipping_ul">
                  <li>加载中...</li>
                </ul>
              </div>
              </a></span></div></th>
          <th colspan="3" style=" border-left: none;"><?php if(!empty($output['daddress_info'])) { ?>
            <dl class="daddress-info">
              <dt>发货人：</dt>
              <dd><?php echo $output['daddress_info']['seller_name']; ?><a href="javascript:void(0);">更多<i class="icon-angle-down"></i>
                <div class="more"><span class="arrow"></span>
                  <ul>
                    <li>公司名称：<span><?php echo $output['daddress_info']['company'];?></span></li>
                    <li>联系电话：<span><?php echo $output['daddress_info']['telphone'];?></span></li>
                    <li>发货地址：<span><?php echo $output['daddress_info']['area_info'];?>&nbsp;<?php echo $output['daddress_info']['address'];?></span></li>
                  </ul>
                </div>
                </a></dd>
            </dl>
            <?php } ?>
          </th>
        </tr>
        <?php } ?>
        <?php $i = 0;?>
        <?php foreach($output['order_info']['goods_list'] as $k => $goods) {?>
        <?php $i++;?>
        <tr class="bd-line">
          <td>&nbsp;</td>
          <td class="w70"><div class="ncm-goods-thumb"><a target="_blank" href="<?php echo $goods['goods_url']; ?>"><img src="<?php echo $goods['image_60_url']; ?>" onMouseOver="toolTip('<img src=<?php echo $goods['image_240_url']; ?>>')" onMouseOut="toolTip()" /></a></div></td>
          <td class="tl"><dl class="goods-name">
              <dt><a target="_blank" href="<?php echo $goods['goods_url']; ?>"><?php echo $goods['goods_name']; ?></a><br /><?php echo $goods['goods_serial']; ?></dt>
              <dd>
                <?php if (is_array($output['refund_all']) && !empty($output['refund_all'])) {?>
                退款单号：<a target="_blank" href="index.php?gct=member_refund&gp=view&refund_id=<?php echo $output['refund_all']['refund_id'];?>"><?php echo $output['refund_all']['refund_sn'];?></a>
                <?php }else if($goods['extend_refund']['refund_type'] == 1) {?>
                退款单号：<a target="_blank" href="index.php?gct=member_refund&gp=view&refund_id=<?php echo $goods['extend_refund']['refund_id'];?>"><?php echo $goods['extend_refund']['refund_sn'];?></a></dd>
              <?php }else if($goods['extend_refund']['refund_type'] == 2) {?>
              退货单号：<a target="_blank" href="index.php?gct=member_return&gp=view&return_id=<?php echo $goods['extend_refund']['refund_id'];?>"><?php echo $goods['extend_refund']['refund_sn'];?></a>
              </dd>
              <?php } ?>
            </dl></td>
          <td></td>
          <td class="tl refund"><?php echo $goods['goods_price']; ?>
          <td class="tl refund"><?php echo $goods['taxes_total']; ?>
            <p class="green">
              <?php if (is_array($output['refund_all']) && !empty($output['refund_all']) && $output['refund_all']['admin_time'] > 0) {?>
              <?php echo $goods['goods_pay_price'];?><span>退</span>
              <?php } elseif ($goods['extend_refund']['admin_time'] > 0) { ?>
              <?php echo $goods['extend_refund']['refund_amount'];?><span>退</span>
              <?php } ?>
            </p></td>
          <td><?php echo $goods['goods_num']; ?></td>
		  <td><?php echo $goods['goods_rebate_rate']*100; ?>%</td>
          <td><?php echo $goods['goods_type_cn']; ?></td>
          <td></td>
          
          <!-- S 合并TD -->
          <?php if (($output['order_info']['goods_count'] > 1 && $k ==0) || ($output['order_info']['goods_count'] == 1)){ ?>
          <td class="bdl bdr" rowspan="<?php echo $output['order_info']['goods_count'];?>"><?php echo $output['order_info']['state_desc']; ?></td>
          <td rowspan="<?php echo $output['order_info']['goods_count'];?>">
			<?php if ($output['order_info']['lock_state'] == 1) { ?>
				<p><?php echo $lang['order_state_refund_underway'];?></p>
            <?php } ?>
            
			<?php if ($output['order_info']['refund_state'] > 0 && $output['order_info']['refund_amount']) { ?>
				<p><?php echo $lang['order_state_refund_perform'];?></p>
            <?php } ?>
                        
            <!-- 已经评价 -->
            
            <?php if ($output['order_info']['evaluation_state'] == 1) { echo $lang['order_state_eval'];} ?>
            
            <!-- 分享  -->
            
            <?php if ($output['order_info']['if_share']) { ?>
            <p><a href="javascript:void(0)" nc_type="sharegoods" data-param='{"gid":"<?php echo $output['order_info']['extend_order_goods'][0]['goods_id'];?>"}'>分享商品</a></p>
            <?php } ?></td>
          <?php } ?>
          <!-- E 合并TD --> 
        </tr>
        
        <!-- S 赠品列表 -->
        <?php if (!empty($output['order_info']['zengpin_list']) && $i == count($output['order_info']['goods_list'])) { ?>
        <tr class="bd-line">
          <td>&nbsp;</td>
          <td colspan="7" class="tl"><div class="ncm-goods-gift">赠品：
              <ul>
                <?php foreach($output['order_info']['zengpin_list'] as $zengpin_info) {?>
                <li><a target="_blank" title="赠品：<?php echo $zengpin_info['goods_name'];?> * <?php echo $zengpin_info['goods_num'];?>" href="<?php echo $zengpin_info['goods_url']; ?>"><img src="<?php echo $zengpin_info['image_60_url']; ?>" onMouseOver="toolTip('<img src=<?php echo $zengpin_info['image_240_url']; ?>>')" onMouseOut="toolTip()" /></a></li>
                <?php } ?>
              </ul>
            </div></td>
        </tr>
        <?php } ?>
        <!-- E 赠品列表 -->
        
        <?php } ?>
      </tbody>
      <tfoot>
        <?php if(!empty($output['order_info']['extend_order_common']['promotion_info']) || !empty($output['order_info']['extend_order_common']['voucher_code'])){ ?>
        <tr>
          <th colspan="20"><dl class="ncm-store-sales">
              <?php if(!empty($output['order_info']['extend_order_common']['promotion_info'])){ ?>
              <dd><?php echo $output['order_info']['extend_order_common']['promotion_info'];?></dd>
              <?php } ?>
              <?php if(!empty($output['order_info']['extend_order_common']['voucher_code'])){ ?>
              <dd>使用了面额为 <strong><?php echo $output['order_info']['extend_order_common']['voucher_price'];?></strong> 元的代金券，编码：<?php echo $output['order_info']['extend_order_common']['voucher_code'];?></span></dd>
              <?php } ?>
            </dl>
          </th>
        </tr>
        <?php } ?>
        <tr>
          <td colspan="20"><dl class="freight">
              <dd>
                <?php if(!empty($output['order_info']['shipping_fee']) && $output['order_info']['shipping_fee'] != '0.00'){ ?>
                <?php echo $lang['member_show_order_tp_fee'].$lang['nc_colon'];?><span><?php echo $lang['currency'];?><?php echo $output['order_info']['shipping_fee']; ?></span>
                <?php if ($output['order_info']['shipping_name'] != ''){echo '('.$output['order_info']['shipping_name'].')';};?>
                <?php }else{?>
                <?php echo $lang['nc_common_shipping_free'];?>
                <?php }?>
              </dd>
            </dl>
            <dl class="sum">
              <dt>订单应付金额<?php echo $lang['member_order_sum'].$lang['nc_colon'];?></dt>
              <dd><em><?php echo $output['order_info']['order_amount']; ?></em>元</dd>
            </dl></td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js" ></script> 
<script type="text/javascript">
$(function(){
    $('#show_shipping').on('hover',function(){
        var_send = '<?php echo date("Y-m-d H:i:s",$output['order_info']['extend_order_common']['shipping_time']); ?>&nbsp;&nbsp;<?php echo $lang['member_show_seller_has_send'];?><br/>';
    	$.getJSON('index.php?gct=member_order&gp=get_express&e_code=<?php echo $output['order_info']['express_info']['e_code']?>&shipping_code=<?php echo $output['order_info']['shipping_code']?>&t=<?php echo random(7);?>',function(data){
    		if(data){
    			data = var_send+data.join('<br/>');
    			$('#shipping_ul').html('<li>'+data+'</li>');
    			$('#show_shipping').unbind('hover');
    		}else{
    			$('#shipping_ul').html(var_send);
    			$('#show_shipping').unbind('hover');
    		}
    	});
    });
});
</script> 
