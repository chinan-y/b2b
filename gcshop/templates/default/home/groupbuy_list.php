<?php defined('GcWebShop') or exit('Access Invalid!');?>
<?php require('groupbuy_head.php');?>
<form id="search_form">
  <input name="gct" type="hidden" value="show_groupbuy" />
  <input name="gp" type="hidden" value="<?php echo $_GET['gp'];?>" />
  <input id="groupbuy_class" name="groupbuy_class" type="hidden" value="<?php echo $_GET['groupbuy_class'];?>"/>
  <input id="groupbuy_price" name="groupbuy_price" type="hidden" value="<?php echo $_GET['groupbuy_price'];?>"/>
  <input id="groupbuy_order_key" name="groupbuy_order_key" type="hidden" value="<?php echo $_GET['groupbuy_order_key'];?>"/>
  <input id="groupbuy_order" name="groupbuy_order" type="hidden" value="<?php echo $_GET['groupbuy_order'];?>"/>
</form>
<div class="nch-breadcrumb-layout" style="display: none;">
  <div class="nch-breadcrumb wrapper"> <i class="icon-home"></i> <span> <a href="<?php echo urlShop(); ?>">首页</a> </span> <span class="arrow">></span> <span>限时特卖</span></div>
</div>
<div class="index-width">
	<div class="ncg-content" style="display:none;">
		<div class="ncg-nav">
			<ul>
				<li<?php if ($output['current'] == 'online') echo ' class="current"'; ?>><a href="<?php echo urlShop('show_groupbuy', 'groupbuy_list');?>">正在进行</a></li>
				<li<?php if ($output['current'] == 'soon') echo ' class="current"'; ?>><a href="<?php echo urlShop('show_groupbuy', 'groupbuy_soon');?>">即将开始</a></li>
				<li<?php if ($output['current'] == 'history') echo ' class="current"'; ?>><a href="<?php echo urlShop('show_groupbuy', 'groupbuy_history');?>">已经结束</a></li>
			</ul>
		</div>
	</div>

	<?php if (!empty($output['groupbuy_list'])) { ?>
	<div class="home-sale-layout xstm_index index-width mt30">
		<div class="group-list">
			<ul>
			<?php foreach ($output['groupbuy_list'] as $xianshi) { ?>
				<li class="<?php echo $output['current'];?>">
					<div class="ncg-list-content1"> 
						<a title="<?php echo $xianshi['goods_name'];?>" href="<?php echo $xianshi['goods_url'];?>" class="pic-thumb" target="_blank">
							<img src="<?php echo cthumb($xianshi['goods_image'],'mid',$store_id, $xianshi['goods_commonid']);?>" alt="<?php echo $xianshi['goods_name'];?>">
						</a>  
						<div class="title">
							<a title="<?php echo $xianshi['xianshi_name'];?>" href="<?php echo $xianshi['goods_url'];?>" target="_blank"><?php echo $xianshi['goods_name'];?></a>
						</div>
						<?php list($integer_part, $decimal_part) = explode('.', $xianshi['xianshi_price']);?>
						<div class="item-prices"> 
							<span class="price">
								<del class="orig-price"><?php echo $lang['currency'].$xianshi['goods_marketprice'];?></del>
								<i><?php echo $lang['currency'];?></i><?php echo $integer_part;?>.<?php echo $decimal_part;?>
								<span class="limit-num"><?php echo number_format(10*$xianshi['xianshi_price']/$xianshi['goods_marketprice'],1);?>&nbsp;<?php echo $lang['text_zhe'];?></span> 
							</span> 
						</div>
						<hr>
						<p class="sold-num"><?php echo $xianshi['xianshi_title'];?></p>
					</div>
				</li>
			<?php } ?>
			</ul>
		</div>
	</div>
	<div class="tc mt20 mb20">
		<div class="pagination"><?php echo $output['show_page'];?></div>
	</div>
	<?php } else { ?>
		<div class="no-content"><?php echo $lang['no_groupbuy_info'];?></div>
	<?php } ?>
</div>
