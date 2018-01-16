<?php defined('GcWebShop') or exit('Access Invalid!');?>
<style>
.ncc-table-style tbody tr.item_disabled td {
	background: none repeat scroll 0 0 #F9F9F9;
	height: 30px;
	padding: 10px 0;
	text-align: center;
}

.ncc-title p{color:red;}
.ncc-title h5{padding-top:5px;}
.ncc-p{padding-top:5px;}
.ncc-p a{text-decoration:none;}
.ncc-cookie{color:red;}
.ncc-cookie img{height:25px;margin:5px;}
.loginco{ display:inline-block;background-color: #e74649; padding: 0 8px; height:25px;line-height:25px; transition: all 0.2s ease-out 0s; vertical-align: middle;border-radius: 3px; margin-left:8px;}
.loginco a{color: #fff; text-decoration: none; }
</style>
<div class="ncc-main">
  <div class="ncc-title">
    
	<?php if ($_SESSION['member_id']){?> 
	<p class="ncc-p"><a target="_blank" href="http://gss.mof.gov.cn/zhengwuxinxi/zhengcefabu/201603/t20160324_1922968.html"><?php echo $lang['cart_index_import_duty_notice'];?></a><?php echo $lang['cart_index_customs_notice'];?></p>
	<?php }else {?>
    <div class="ncc-cookie">
		<img src="/gcshop/templates/default/images/xx.jpg" />
		<?php echo $lang['cart_index_not_login'];?>
		<div class="loginco">
			<a href="<?php echo SHOP_SITE_URL;?>/index.php?gct=login&gp=index&ref_url=<?php echo urlencode($output['ref_url']);?>"><?php echo $lang['cart_index_login'];?></a>
		</div>
		<p class="ncc-p"><a target="_blank" href="http://gss.mof.gov.cn/zhengwuxinxi/zhengcefabu/201603/t20160324_1922968.html"><?php echo $lang['cart_index_import_duty_notice'];?></a><?php echo $lang['cart_index_customs_notice'];?></p>
	</div>
	<?php }?>
  </div>
  <form action="<?php echo urlShop('buy','buy_step1');?>" method="POST" id="form_buy" name="form_buy">
    <input type="hidden" value="<?php echo $output['split']?>" name="split" id="split">
    <input type="hidden" value="1" name="ifcart">
	<input type="hidden" value="<?php echo $output['buy_encrypt'];?>" id="buy_encrypt" name="buy_encrypt">
    <table class="ncc-table-style" nc_type="table_cart">
      <thead>
        <tr>
          <th class="w50">
			<label><input type="checkbox" checked value="1" id="selectAll"><?php echo $lang['cart_index_check_all'];?></label>
		  </th>
          <!--<th class="w50">分类</th>-->
		  <th class="w70"><a href="javascript:void(0)" onclick="empty_cart_item();" ><?php echo $lang['cart_index_clear_cart'];?></a></th>
          <th><?php echo $lang['cart_index_store_goods'];?></th>
          <th class="w120"><?php echo $lang['cart_index_price'].'('.$lang['currency_zh'].')';?></th>
          <th class="w120"><?php echo $lang['cart_index_amount'];?></th>
          <th class="w120"><?php echo $lang['cart_index_sum'].'('.$lang['currency_zh'].')';?></th>
          <th class="w80"><?php echo $lang['cart_index_handle'];?></th>
        </tr>
      </thead>
	  
	  
<?php if($output['split']){ ?>


      <?php foreach($output['store_cart_list'] as $store_id => $cart_list) {?>
	  <input type="hidden" value="<?php echo $store_id; ?>" name="store_id">
      <tbody class="transport_table">
        <tr>
          <th colspan="20"><strong><a href="<?php echo urlShop('show_store','index',array('store_id'=>$store_id), $output['store_list'][$store_id]['store_domain']);?>"><?php echo $cart_list[0]['store_name']; ?></a></strong>
		  <span member_id="<?php echo $output['store_list'][$store_id]['member_id'];?>"></span>
		  
            <?php if (!empty($output['free_freight_list'][$store_id])) {?>
            <div class="store-sale"><em><i class="icon-gift"></i><?php echo $output['free_freight_list'][$store_id];?></em>&emsp;</div>
            <?php } ?>
          </th>
        </tr>

<?php
$transport_cart_list = array();
foreach($cart_list as $v){
	$transport_cart_list[$v['transport_id']][] = $v;
}
?>
<?php 
foreach($transport_cart_list as $transport_id=>$transport_cart){
?>
		<!-- S one warehouse list -->
		<?php if(!$transport_id && $transport_cart[0]['bl_id']){ ?>
			<tr class="transport_tr">
			  <th colspan="20"><label><input type="radio" id="transport_id_<?php echo $store_id;?>_<?php echo $transport_id;?>" name="transport_id_<?php echo $store_id;?>" value="<?php echo $transport_id;?>"/> 组合套装</label>
			  </th>
			</tr>
		<?php }else{?>
			<tr class="transport_tr">
			  <th colspan="20"><label><input type="radio" id="transport_id_<?php echo $store_id;?>_<?php echo $transport_id;?>" name="transport_id_<?php echo $store_id;?>" value="<?php echo $transport_id;?>"/> <?php echo $output['regions'][$output['transports'][$transport_id]['region_id']]['address_name'];?></label>
			  </th>
			</tr>
		<?php }?>
        <!-- S one store list -->
        <?php foreach($transport_cart as $cart_info) {?>
		
        <tr id="cart_item_<?php echo $cart_info['goods_id'];?>" nc_group="<?php echo $cart_info['cart_id'];?>" class="gcshop-list <?php echo $cart_info['state'] ? '' : 'item_disabled';?>">
          <td><input transport_id="transport_id_<?php echo $store_id;?>_<?php echo $transport_id;?>" style="display:none;" type="checkbox" nc_type="eachGoodsCheckBox" value="<?php echo $cart_info['cart_id'].'|'.$cart_info['goods_num'];?>" id="cart_id<?php echo $cart_info['cart_id'];?>" name="cart_id[]"></td>

         <!-- <td><?php //echo $cart_info['store_from']; by liu显示店铺下的分类名称?></td>-->

          <?php if ($cart_info['goods_image']) { // 后期有套装组合后 可能会更改?>
          <td class="w60"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$cart_info['goods_id']));?>" target="_blank" class="ncc-goods-thumb"><img src="<?php echo thumb($cart_info,60);?>" alt="<?php echo $cart_info['goods_name']; ?>" /></a></td>
          <?php } ?>
          <td class="tl" <?php if (!$cart_info['goods_image']) {// 后期有套装组合后 可能会更改?>colspan="2"<?php }?>><dl class="ncc-goods-info">
              <dt>
			  <a href="<?php echo urlShop('goods','index',array('goods_id'=>$cart_info['goods_id']));?>" target="_blank">
			  <?php echo $cart_info['goods_name']; ?></a></dt>
              <?php if (!empty($cart_info['xianshi_info'])) {?>
              <dd> <span class="xianshi">满<strong><?php echo $cart_info['xianshi_info']['lower_limit'];?></strong>件，单价直降<em>￥<?php echo $cart_info['xianshi_info']['down_price']; ?></em></span> </dd>
              <?php }?>
              <?php if ($cart_info['ifgroupbuy']) {?>
              <dd> <span class="groupbuy">抢购<?php if ($cart_info['upper_limit']) {?>，最多限购<strong><?php echo $cart_info['upper_limit']; ?></strong>件<?php } ?></span></dd>
              <?php }?>
              <?php if ($cart_info['bl_id'] != '0' && isset($cart_info['bl_id']) ){?>
              <dd><span class="buldling">优惠套装，单套直降<em>￥<?php echo $cart_info['down_price']; ?></em></span></dd>
              <?php }?>

              <!-- S gift list -->
              <?php if (!empty($cart_info['gift_list'])) {?>
              <dd><span class="ncc-goods-gift">赠</span>
                <ul class="ncc-goods-gift-list">
                  <?php foreach ($cart_info['gift_list'] as $goods_info) { ?>
                  <li nc_group="<?php echo $cart_info['cart_id'];?>"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$goods_info['gift_goodsid']));?>" target="_blank" class="thumb" title="赠品：<?php echo $goods_info['gift_goodsname']; ?> * <?php echo $goods_info['gift_amount'] * $cart_info['goods_num']; ?>"><img src="<?php echo cthumb($goods_info['gift_goodsimage'],60,$store_id);?>" alt="<?php echo $goods_info['gift_goodsname']; ?>" /></a>
                    <?php } ?>
                  </li>
                </ul>
              </dd>
              <?php  } ?>
              <!-- E gift list -->
            </dl></td>
			
				<td class="w120"><em id="item<?php echo $cart_info['cart_id']; ?>_price"><?php echo $cart_info['goods_price']; ?></em></td>
			
          <?php if ($cart_info['state']) {?>
			<?php if($cart_info['cart_id']) {?>
				<td class="w120 ws0"><a href="JavaScript:void(0);" onclick="decrease_quantity(<?php echo $cart_info['cart_id']; ?>);" title="<?php echo $lang['cart_index_reduse'];?>" class="add-substract-key tip">-</a>
				<input id="input_item_<?php echo $cart_info['cart_id']; ?>" value="<?php echo $cart_info['goods_num']; ?>" orig="<?php echo $cart_info['goods_num']; ?>" changed="<?php echo $cart_info['goods_num']; ?>" onkeyup="change_quantity(<?php echo $cart_info['cart_id']; ?>, this);" type="text" class="text w20" nc_type="goodsnum"/>
				<a href="JavaScript:void(0);" onclick="add_quantity(<?php echo $cart_info['cart_id']; ?>);" title="<?php echo $lang['cart_index_increase'];?>" class="add-substract-key tip" >+</a></td>
			<?php }else{?>
				<td class="w120 ws0"><a href="JavaScript:void(0);" onclick="decrease_quantity_goods(<?php echo $cart_info['goods_id']; ?>);" title="<?php echo $lang['cart_index_reduse'];?>" class="add-substract-key tip">-</a>
				<input id="input_item_<?php echo $cart_info['goods_id']; ?>" value="<?php echo $cart_info['goods_num']; ?>" orig="<?php echo $cart_info['goods_num']; ?>" changed="<?php echo $cart_info['goods_num']; ?>" onkeyup="change_quantity_goods(<?php echo $cart_info['goods_id']; ?>, this, <?php echo $cart_info['goods_price'];?>);" type="text" class="text w20" nc_type="goodsnum"/>
				<a href="JavaScript:void(0);" onclick="add_quantity_goods(<?php echo $cart_info['goods_id']; ?>);" title="<?php echo $lang['cart_index_increase'];?>" class="add-substract-key tip" >+</a></td>
			<?php }?>
          <?php } else {?>
          <td class="w120">无效
            <input type="hidden" value="<?php echo $cart_info['cart_id']; ?>" name="invalid_cart[]"></td>
          <?php }?>
          <td class="w120"><?php if ($cart_info['state']) {?>
			<?php if($cart_info['cart_id']) {?>
				<em id="item<?php echo $cart_info['cart_id']; ?>_subtotal" nc_type="eachGoodsTotal"><?php echo $cart_info['goods_total']; ?></em>
			<?php }else{?>
				<em id="item<?php echo $cart_info['goods_id']; ?>_subtotal" nc_type="eachGoodsTotal"><?php echo $cart_info['goods_total']; ?></em>
			<?php }?>
          <?php }?></td>
			
          <td class="w80"><?php if ($cart_info['bl_id'] == '0') {?>
            <a href="javascript:void(0)" onclick="collect_goods('<?php echo $cart_info['goods_id']; ?>');"><?php echo $lang['cart_index_favorite'];?></a><br/>
			<a href="javascript:void(0)" onclick="drop_cart_item(<?php echo $cart_info['cart_id']; ?>);"><?php echo $lang['cart_index_del'];?></a></td>
            <?php }else{ ?>
           
            <a href="javascript:void(0)" onclick="drop_cart_item(<?php echo $cart_info['cart_id']; ?>);"><?php echo $lang['cart_index_del'];?></a></td>
			<?php }?>
        </tr>

        <!-- S bundling goods list -->
        <?php if (is_array($cart_info['bl_goods_list'])) {?>
        <?php foreach ($cart_info['bl_goods_list'] as $goods_info) { ?>
        <tr class="gcshop-list <?php echo $cart_info['state'] ? '' : 'item_disabled';?>" nc_group="<?php echo $cart_info['cart_id'];?>">
          <td></td>
          <td class="w60"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$goods_info['goods_id']));?>" target="_blank" class="ncc-goods-thumb"><img src="<?php echo cthumb($goods_info['goods_image'],60,$store_id);?>" alt="<?php echo $goods_info['goods_name']; ?>" /></a></td>
          <td class="tl"><dl class="ncc-goods-info">
              <dt><a href="<?php echo urlShop('goods','index',array('goods_id'=>$goods_info['goods_id']));?>" target="_blank"><?php echo $goods_info['goods_name']; ?></a> </dt>
            </dl></td>

          <td><em><?php echo $goods_info['bl_goods_price'];?></em></td>
          <td><?php echo $cart_info['state'] ? '' : '无效';?></td>
          <td></td>
          <td><a href="javascript:void(0)" onclick="collect_goods('<?php echo $goods_info['goods_id']; ?>');"><?php echo $lang['cart_index_favorite'];?></a><br/></td>
        </tr>
        <?php } ?>
        <?php  } ?>
        <!-- E bundling goods list -->

        <?php } ?>
        <!-- E one store list -->

<?php } ?>
		<!-- S one warehouse list -->
		
		
        <!-- S mansong list -->
        <?php if (!empty($output['mansong_rule_list'][$store_id]) && is_array($output['mansong_rule_list'][$store_id])) {?>
        <tr nc_group="<?php echo $cart_info['cart_id'];?>">
          <td></td>
          <td class="tl" colspan="10"><div class="store-sale"><em> <i class="icon-gift"></i> 满即送 </em><?php echo implode('<br/>', $output['mansong_rule_list'][$store_id]);?></div></td>
        </tr>
        <?php }?>
        <!-- E mansong list -->

        <tr style="display:none;">
          <td class="tr" colspan="20"><div class="ncc-store-account">
				<a href="https://www.qqbsmall.com/gcshop/index.php?gct=pointvoucher&gp=index" target="_blank">兑换代金券</a><!--　|　
				<a href="https://www.qqbsmall.com/gcshop/index.php?gct=special&gp=special_detail&special_id=17" target="_blank">选取赠送面膜</a>-->
          </td>
        </tr>
		<tr>
          <td class="tr" colspan="20"><div class="ncc-store-account">
              <dl>
				<dt style="font-size:14px;">商品合计：</dt>
                <dd><em nc_type="eachStoreTotal"></em><?php echo $lang['currency_zh'];?></dd>
              </dl>
          </td>
        </tr>
        <?php }?>

      </tbody>
	  
<script>
/* 增加点选按钮 */
$(function(){
	$(".transport_table .transport_tr input[type='radio']").click(function (){
		var transport_id = $(this).attr("id");
		var transport_table = $(this).parent().parent().parent().parent();

		$(transport_table).find('input[nc_type="eachGoodsCheckBox"]').each(function(){
			if($(this).attr("transport_id") == transport_id){
				$(this).attr("checked",true);
				$(this).attr("disabled",false);
				$(this).show();
			}else{
				$(this).attr("checked",false);
				$(this).attr("disabled",true);
				$(this).hide();
			}
		});
		calc_cart_price();
	})
	
	$(".transport_table").each(function(index){
		$(this).find('input[type="radio"]').first().click();
	})
})
</script>
<?php }else{?>
	  
	  
	  <?php foreach($output['store_cart_list'] as $store_id => $cart_list) {?>
	  <input type="hidden" value="<?php echo $store_id; ?>" name="store_id">
      <tbody>
        <tr>
          <th colspan="20"><strong><a href="<?php echo urlShop('show_store','index',array('store_id'=>$store_id), $output['store_list'][$store_id]['store_domain']);?>"><?php echo $cart_list[0]['store_name']; ?></a></strong>
		  <span member_id="<?php echo $output['store_list'][$store_id]['member_id'];?>"></span>
		  
            <?php if (!empty($output['free_freight_list'][$store_id])) {?>
			<div class="store-sale"><em><i class="icon-gift"></i><?php echo $output['free_freight_list'][$store_id];?></em>&emsp;</div>
            <?php } ?>
          </th>
        </tr>

        <!-- S one store list -->
        <?php foreach($cart_list as $cart_info) {?>
		<input type="hidden" value =<?php echo $cart_info['store_from']; ?> id="store_from" />
        <tr id="cart_item_<?php echo $cart_info['goods_id'];?>" nc_group="<?php echo $cart_info['cart_id'];?>" class="gcshop-list <?php echo $cart_info['state'] ? '' : 'item_disabled';?>">
          <td><input type="checkbox" <?php echo $cart_info['state'] ? 'checked' : 'disabled';?> nc_type="eachGoodsCheckBox" value="<?php echo $cart_info['cart_id'].'|'.$cart_info['goods_num'];?>" id="cart_id<?php echo $cart_info['cart_id'];?>" name="cart_id[]"></td>

         <!-- <td><?php //echo $cart_info['store_from']; by liu显示店铺下的分类名称?></td>-->

          <?php if ($cart_info['goods_image']) { // 后期有套装组合后 可能会更改?>
          <td class="w60"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$cart_info['goods_id']));?>" target="_blank" class="ncc-goods-thumb"><img src="<?php echo thumb($cart_info,60);?>" alt="<?php echo $cart_info['goods_name']; ?>" /></a></td>
          <?php } ?>
          <td class="tl" <?php if (!$cart_info['goods_image']) {// 后期有套装组合后 可能会更改?>colspan="2"<?php }?>><dl class="ncc-goods-info">
              <dt>
			  <a href="<?php echo urlShop('goods','index',array('goods_id'=>$cart_info['goods_id']));?>" target="_blank">
			  <?php echo $cart_info['goods_name']; ?></a></dt>
              <?php if (!empty($cart_info['xianshi_info'])) {?>
              <dd> <span class="xianshi">满<strong><?php echo $cart_info['xianshi_info']['lower_limit'];?></strong>件，单价直降<em>￥<?php echo $cart_info['xianshi_info']['down_price']; ?></em></span> </dd>
              <?php }?>
              <?php if ($cart_info['ifgroupbuy']) {?>
              <dd> <span class="groupbuy">抢购<?php if ($cart_info['upper_limit']) {?>，最多限购<strong><?php echo $cart_info['upper_limit']; ?></strong>件<?php } ?></span></dd>
              <?php }?>
              <?php if ($cart_info['bl_id'] != '0' && isset($cart_info['bl_id']) ){?>
              <dd><span class="buldling">优惠套装，单套直降<em>￥<?php echo $cart_info['down_price']; ?></em></span></dd>
              <?php }?>

              <!-- S gift list -->
              <?php if (!empty($cart_info['gift_list'])) {?>
              <dd><span class="ncc-goods-gift">赠</span>
                <ul class="ncc-goods-gift-list">
                  <?php foreach ($cart_info['gift_list'] as $goods_info) { ?>
                  <li nc_group="<?php echo $cart_info['cart_id'];?>"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$goods_info['gift_goodsid']));?>" target="_blank" class="thumb" title="赠品：<?php echo $goods_info['gift_goodsname']; ?> * <?php echo $goods_info['gift_amount'] * $cart_info['goods_num']; ?>"><img src="<?php echo cthumb($goods_info['gift_goodsimage'],60,$store_id);?>" alt="<?php echo $goods_info['gift_goodsname']; ?>" /></a>
                    <?php } ?>
                  </li>
                </ul>
              </dd>
              <?php  } ?>
              <!-- E gift list -->
            </dl></td>
			
				<td class="w120"><em id="item<?php echo $cart_info['cart_id']; ?>_price"><?php echo $cart_info['goods_price']; ?></em></td>
			
          <?php if ($cart_info['state']) {?>
			<?php if($cart_info['cart_id']) {?>
				<td class="w120 ws0"><a href="JavaScript:void(0);" onclick="decrease_quantity(<?php echo $cart_info['cart_id']; ?>);" title="<?php echo $lang['cart_index_reduse'];?>" class="add-substract-key tip">-</a>
				<input id="input_item_<?php echo $cart_info['cart_id']; ?>" value="<?php echo $cart_info['goods_num']; ?>" orig="<?php echo $cart_info['goods_num']; ?>" changed="<?php echo $cart_info['goods_num']; ?>" onkeyup="change_quantity(<?php echo $cart_info['cart_id']; ?>, this);" type="text" class="text w20" nc_type="goodsnum"/>
				<a href="JavaScript:void(0);" onclick="add_quantity(<?php echo $cart_info['cart_id']; ?>);" title="<?php echo $lang['cart_index_increase'];?>" class="add-substract-key tip" >+</a></td>
			<?php }else{?>
				<td class="w120 ws0"><a href="JavaScript:void(0);" onclick="decrease_quantity_goods(<?php echo $cart_info['goods_id']; ?>);" title="<?php echo $lang['cart_index_reduse'];?>" class="add-substract-key tip">-</a>
				<input id="input_item_<?php echo $cart_info['goods_id']; ?>" value="<?php echo $cart_info['goods_num']; ?>" orig="<?php echo $cart_info['goods_num']; ?>" changed="<?php echo $cart_info['goods_num']; ?>" onkeyup="change_quantity_goods(<?php echo $cart_info['goods_id']; ?>, this, <?php echo $cart_info['goods_price'];?>);" type="text" class="text w20" nc_type="goodsnum"/>
				<a href="JavaScript:void(0);" onclick="add_quantity_goods(<?php echo $cart_info['goods_id']; ?>);" title="<?php echo $lang['cart_index_increase'];?>" class="add-substract-key tip" >+</a></td>
			<?php }?>
          <?php } else {?>
          <td class="w120">无效
            <input type="hidden" value="<?php echo $cart_info['cart_id']; ?>" name="invalid_cart[]"></td>
          <?php }?>
          <td class="w120"><?php if ($cart_info['state']) {?>
			<?php if($cart_info['cart_id']) {?>
				<em id="item<?php echo $cart_info['cart_id']; ?>_subtotal" nc_type="eachGoodsTotal"><?php echo $cart_info['goods_total']; ?></em>
			<?php }else{?>
				<em id="item<?php echo $cart_info['goods_id']; ?>_subtotal" nc_type="eachGoodsTotal"><?php echo $cart_info['goods_total']; ?></em>
			<?php }?>
          <?php }?></td>
			
          <td class="w80"><?php if ($cart_info['bl_id'] == '0') {?>
            <a href="javascript:void(0)" onclick="collect_goods('<?php echo $cart_info['goods_id']; ?>');"><?php echo $lang['cart_index_favorite'];?></a><br/>
			<a href="javascript:void(0)" onclick="drop_cart_item(<?php echo $cart_info['cart_id']; ?>);"><?php echo $lang['cart_index_del'];?></a></td>
            <?php }else{ ?>
           
            <a href="javascript:void(0)" onclick="drop_cart_item(<?php echo $cart_info['cart_id']; ?>);"><?php echo $lang['cart_index_del'];?></a></td>
			<?php }?>
        </tr>

        <!-- S bundling goods list -->
        <?php if (is_array($cart_info['bl_goods_list'])) {?>
        <?php foreach ($cart_info['bl_goods_list'] as $goods_info) { ?>
        <tr class="gcshop-list <?php echo $cart_info['state'] ? '' : 'item_disabled';?>" nc_group="<?php echo $cart_info['cart_id'];?>">
          <td></td>
          <td class="w60"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$goods_info['goods_id']));?>" target="_blank" class="ncc-goods-thumb"><img src="<?php echo cthumb($goods_info['goods_image'],60,$store_id,$goods_info['goods_commonid']);?>" alt="<?php echo $goods_info['goods_name']; ?>" /></a></td>
          <td class="tl"><dl class="ncc-goods-info">
              <dt><a href="<?php echo urlShop('goods','index',array('goods_id'=>$goods_info['goods_id']));?>" target="_blank"><?php echo $goods_info['goods_name']; ?></a> </dt>
            </dl></td>

          <td><em><?php echo $goods_info['bl_goods_price'];?></em></td>
          <td><?php echo $cart_info['state'] ? '' : '无效';?></td>
          <td></td>
          <td><a href="javascript:void(0)" onclick="collect_goods('<?php echo $goods_info['goods_id']; ?>');"><?php echo $lang['cart_index_favorite'];?></a><br/></td>
        </tr>
        <?php } ?>
        <?php  } ?>
        <!-- E bundling goods list -->

        <?php } ?>
        <!-- E one store list -->

        <!-- S mansong list -->
        <?php if (!empty($output['mansong_rule_list'][$store_id]) && is_array($output['mansong_rule_list'][$store_id])) {?>
        <tr nc_group="<?php echo $cart_info['cart_id'];?>">
          <td></td>
          <td class="tl" colspan="10"><div class="store-sale"><em> <i class="icon-gift"></i> 满即送 </em><?php echo implode('<br/>', $output['mansong_rule_list'][$store_id]);?></div></td>
        </tr>
        <?php }?>
        <!-- E mansong list -->

        <tr style="display:none;">
          <td class="tr" colspan="20"><div class="ncc-store-account">
				<a href="https://www.qqbsmall.com/gcshop/index.php?gct=pointvoucher&gp=index" target="_blank">兑换代金券</a><!--　|　
				<a href="https://www.qqbsmall.com/gcshop/index.php?gct=special&gp=special_detail&special_id=17" target="_blank">选取赠送面膜</a>-->
          </td>
        </tr>
		<tr>
          <td class="tr" colspan="20"><div class="ncc-store-account">
              <dl>
				<dt style="font-size:14px;">商品合计：</dt>
                <dd><em nc_type="eachStoreTotal"></em><?php echo $lang['currency_zh'];?></dd>
              </dl>
          </td>
        </tr>
        <?php }?>

      </tbody>
	  
	  
<?php } ?>
	  
	  
      <tfoot>
        <tr>
          <td colspan="20"><div class="ncc-all-account"><em style="float: left;font-size:14px;"><a href="https://www.qqbsmall.com/gcshop/index.php?gct=pointvoucher&gp=index" target="_blank">兑换代金券</a></em><?php echo $lang['cart_index_goods_sumary'];?><span style="font-size:12px; color:#333;">（不含运费和税金）</span>：<em id="cartTotal"><?php echo $output['cart_totals']; ?></em><?php echo $lang['currency_zh'];?></div></td>
        </tr>
      </tfoot>
    </table>
  </form>
  <div class="ncc-bottom" ><a id="next_submit"  href="javascript:void(0)" class="ncc-btn ncc-btn-acidblue fr"><i class="icon-pencil" ></i><?php echo $lang['cart_index_input_next'].$lang['cart_index_ensure_info'];?></a></div>


  <!-- 猜你喜欢 -->
  <div id="guesslike_div"></div>
</div>
<script type="text/javascript">
$(function(){
	//猜你喜欢
	$('#guesslike_div').load('<?php echo urlShop('search', 'get_guesslike', array()); ?>', function(){
        $(this).show();
    });
});
</script>