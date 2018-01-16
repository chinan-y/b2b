<?php defined('GcWebShop') or exit('Access Invalid!');?>
<style>
.ncc-table-style tbody tr.item_disabled td {
	background: none repeat scroll 0 0 #F9F9F9;
	height: 30px;
	padding: 10px 0;
	text-align: center;
}

.ncc-title{height:80px;}
.ncc-title p{color:red;}
.ncc-title h5{padding-top:5px;}
.ncc-p{padding-top:8px;}
.ncc-cookie{height:35px;line-height:30px;color:red;border:1px solid #EDD28B;}
.ncc-cookie img{height:25px;margin:5px;}
.loginco{ display:inline-block;background-color: #e74649; padding: 0 8px; height:25px;line-height:25px; transition: all 0.2s ease-out 0s; vertical-align: middle;border-radius: 3px; margin-left:8px;}
.loginco a{color: #fff; text-decoration: none; }
</style>
<div class="ncc-main">
  <div class="ncc-title">
	<h3><?php echo '确认要加入以下商品到购物车的商品';?></h3>
	<?php if ($_SESSION['member_id']){?> 
	<h5><?php echo $lang['cart_index_examine_detailed_list'];?></h5>
	<p class="ncc-p"><?php echo $lang['cart_index_customs_notice'];?></p>
	<?php }else {?>
	<div class="ncc-cookie">
		<img src="/gcshop/templates/default/images/xx.jpg" />
		<?php echo $lang['cart_index_not_login'];?>
		<div class="loginco">
			<a href="<?php echo SHOP_SITE_URL;?>/index.php?gct=login&gp=index&ref_url=<?php echo urlencode($output['ref_url']);?>"><?php echo $lang['cart_index_login'];?></a>
		</div>
		<p><?php echo $lang['cart_index_customs_notice'];?></p>
	</div>
	<?php }?>
  </div>
  <form action="index.php?gct=cart" method="POST" id="form_buy" name="form_buy">
	<input type="hidden" value="<?php echo $output['split']?>" name="split" id="split">
	<input type="hidden" value="1" name="ifcart">
	<input type="hidden" value="<?php echo $output['buy_encrypt'];?>" id="buy_encrypt" name="buy_encrypt">
	<table class="ncc-table-style" nc_type="table_cart">
	  <thead>
		<tr>
		  <th class="w50"><label>
			  <input type="checkbox" checked value="1" id="selectAll">
			  全选</label></th>
		  <th class="w50"></th>
		  <th><?php echo $lang['cart_index_store_goods'];?></th>
		  <th class="w120"><?php echo $lang['cart_index_price'].'('.$lang['currency_zh'].')';?></th>
		  <th class="w120"><?php echo $lang['cart_index_amount'];?></th>
		</tr>
	  </thead>
	  
	  <tbody>

		<?php foreach($output['goods_list'] as $goods_info) {?>
		<tr class="gcshop-list " id="goods_item_<?php echo $goods_info['goods_id']?>">
			<td><input type="checkbox" name="goods_id[]" id="goods_id<?php echo $goods_info['goods_id']?>" value="<?php echo $goods_info['goods_id']?>" nc_type="eachGoodsCheckBox" checked></td>
			<td class="w60"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$goods_info['goods_id']));?>" target="_blank" class="ncc-goods-thumb"><img src="<?php echo thumb($goods_info,60);?>" alt="<?php echo $goods_info['goods_name']; ?>" /></a></td>	
			<td class="tl"><dl class="ncc-goods-info">
				<dt>
				<a href="<?php echo urlShop('goods','index',array('goods_id'=>$goods_info['goods_id']));?>" target="_blank">
				<?php echo $goods_info['goods_name']; ?></a></dt>
				</dl>
			</td>
			<td class="w120"><em id="item<?php echo $goods_info['cart_id']; ?>_price"><?php echo $goods_info['goods_price']; ?></em></td>
			<td class="w120 ws0">
				<a class="add-substract-key tip" title="减少商品件数" onclick="decrease_quantity(<?php echo $goods_info['goods_id']?>);" href="JavaScript:void(0);">-</a>
				<input type="text" nc_type="goodsnum" nc_name="<?php echo $goods_info['goods_name']; ?>" class="text w20" onkeyup="change_quantity(<?php echo $goods_info['goods_id']?>, this);" changed="1" orig="1" value="<?php echo $goods_info['goods_num']?>" id="input_item_<?php echo $goods_info['goods_id']?>">
				<a class="add-substract-key tip" title="增加商品件数" onclick="add_quantity(<?php echo $goods_info['goods_id']?>);" href="JavaScript:void(0);">+</a>
			</td>
		</tr>

		<?php } ?>
	  </tbody>

	</table>
  </form>
  <div class="ncc-bottom" ><a id="api_next_submit"  href="javascript:void(0)" class="ncc-btn ncc-btn-acidblue fr"><i class="icon-pencil" ></i><?php echo $lang['cart_index_input_next'].$lang['cart_index_ensure_order'];?></a></div>


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