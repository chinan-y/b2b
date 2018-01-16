<?php defined('GcWebShop') or exit('Access Invalid!');?>

<ul class="cart-list">
  <?php if ($output['favorites_list']) { ?>
	  <?php foreach($output['favorites_list'] as $k => $v){ ?>
	  <li ncTpye="favor_item_<?php echo $v['goods']['goods_id'];?>">
		<div class="goods-pic">
			<a href="index.php?gct=goods&goods_id=<?php echo $v['goods']['goods_id'];?>" target="_blank";><img src="<?php echo thumb($v['goods']);?>" /></a>
		</div>
		<dl>
			<dt class="goods-name">
				<a href="index.php?gct=goods&goods_id=<?php echo $v['goods']['goods_id'];?>" target="_blank";><?php echo $v['goods']['goods_name'];?></a>
			</dt>
			<dd>
				<em class="goods-price"><?php echo $lang['currency'].$v['goods']['goods_price'];?></em>
			</dd>
		</dl>
		<a href="javascript:del_collect_goods(<?php echo $v['fav_id'];?>);" class="del" title="删除">X</a>
	  </li>
	  <?php } ?>
<script>
$(function(){
	$('.head-user-menu .my-cart').append('<div class="addcart-goods-num"><?php echo $output['cart_list']['cart_goods_num'];?></div>');
	$('#rtoobar_cart_count').html(<?php echo $output['cart_list']['cart_goods_num'];?>).show();
});
</script>
  <?php } else { ?>
	  <li>
		<dl><dd style="text-align: center; ">暂无收藏商品</dd></dl>
	  </li>
<script>
$(function(){
  	$('.addcart-goods-num').remove();
  	$('#rtoobar_cart_count').html('').hide();
});
</script>
  <?php } ?>
</ul>