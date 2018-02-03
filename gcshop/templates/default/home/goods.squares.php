<?php defined('GcWebShop') or exit('Access Invalid!');?>
<style type="text/css">
#img{
	width: 80px;
	height: 80px;
	margin: -230px 0 0 50PX;
	display: block;
	border: solid 2px #E6387F;
	border-radius:40px;
	position: absolute;
	z-index: 999;
}
.shopMenu {
	position: fixed;
	z-index: 1;
	right: 25%;
	top: 0;
}
.lt-item{line-height: 20px;background:rgba(255,255,255,0.9);}
.lt-item em{color:#666;font-weight:600;}
.rule-num{margin-left:20px;}
.rule-price{float: right;margin-right:20px;color:#666;font-weight:600;}
</style>
<div class="squares" nc_type="current_display_mode">
    <input type="hidden" id="lockcompare" value="unlock" />
  <?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){?>
  <ul class="list_pic">
    <?php foreach($output['goods_list'] as $value){?>
	<?php  if($value['store_from']!=6){ ?>
    <li class="item">
      <div class="goods-content" nctype_goods=" <?php echo $value['goods_id'];?>" nctype_store="<?php echo $value['store_id'];?>">
        <div class="country-flag">
            <span>
			 <img src="<?php echo UPLOAD_SITE_URL?>/country/<?php echo $value['country_code'];?>.png"/>
		    </span>
        </div>
        <div class="goods-pic">
          <a href="<?php echo urlShop('goods','index',array('goods_id'=>$value['goods_id'],'ref'=>$_SESSION['member_id']));?>" target="_blank" title="<?php echo $value['goods_name'];?>"><img src="<?php echo thumb($value, 240);?>" title="<?php echo $value['goods_name'];?>" alt="<?php echo $value['goods_name'];?>" /></a>
        </div>
        <?php if (C('groupbuy_allow') && $value['goods_promotion_type'] == 1) {?>
        <div class="goods-promotion"><span>抢购商品</span></div>
        <?php } elseif (C('promotion_allow') && $value['goods_promotion_type'] == 2)  {?>
        <div class="goods-promotion"><span>限时折扣</span></div>
		<?php } ?>
        <?php if ( $value['goods_storage'] == 0 && $value['goods_salenum'] > 0) {?>
          <div class="goods-sold"></div>
        <?php }?>
        <?php if ( ($value['goods_fenlei'] == "新品") || (strpos( $value['goods_jingle'],"新品") !== false ) ) {?>
          <div class="goods-new">新品</div>
        <?php }?>
        <div class="goods-info">
		
          <div class="goods-pic-scroll-show">
            <ul>
            <?php if(!empty($value['image'])) {?>
              <?php $i=0;foreach ($value['image'] as $val) {$i++?>
              <li<?php if($i==1) {?> class="selected"<?php }?>><a href="javascript:void(0);"><img src="<?php echo thumb($val, 60);?>"/></a></li>
              <?php }?>
            <?php } else {?>
              <li class="selected"><a href="javascript:void(0);"><img src="<?php echo thumb($value, 60);?>" /></a></li>
            <?php }?>
            </ul>
          </div>
          <div class="goods-name"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$value['goods_id'],'ref'=>$_SESSION['member_id']));?>" target="_blank" title="<?php echo $value['goods_jingle'];?>"><?php  echo $value['goods_name_highlight'];?><em><?php  echo $value['goods_jingle'];?></em></a></div>
		  <?php if ($value['show_price']) {?>
			<div class="goods-price"><em class="show_note" ><?php echo $value['show_note'];?></em></div>
		  <?php }else{?>
			<div class="goods-price"> <em class="sale-price" ><?php echo ncPriceFormatForList($value['goods_promotion_price']);?></em> <em class="market-price" ><?php echo ncPriceFormatForList($value['goods_marketprice']);?></em> <!--span class="raty" data-score="<?php echo $value['evaluation_good_star'];?>"></span--> </div>
		  <?php }?>
          <div class="goods-sub">
			<span class="rate" style="display:none"><em>总奖励：</em><?php echo $value['goods_rebate_rate']*100;?>%</span>
            <?php if ($value['is_virtual'] == 1) {?>
            <span class="virtual" title="虚拟兑换商品">虚拟兑换</span>
            <?php }?>
            <?php if ($value['is_fcode'] == 1) {?>
            <span class="fcode" title="F码优先购买商品">F码优先</span>
            <?php }?>
            <?php if ($value['is_presell'] == 1) {?>
            <span class="presell" title="预售购买商品">预售</span>
            <?php }?>
            <?php if ($value['have_gift'] == 1) {?>
            <span class="gift" title="捆绑赠品">赠品</span>
            <?php }?>
            <span class="goods-compare" nc_type="compare_<?php echo $value['goods_id'];?>" data-param='{"gid":"<?php echo $value['goods_id'];?>"}'><i></i><?php echo $lang['goods_index_join_contrast'] ?></span> </div>
			<?php if($value['rule_info']['num1']){?>
				<input type="hidden" value="<?php echo $value['rule_info']['num1'];?>" class="input1"/>
				<div class="lt-item"><span class="rule-num"><em><?php echo $value['rule_info']['num1'];?></em> 件起</span><span class="rule-price"><?php echo ncPriceFormatForList($value['rule_info']['price1']);?></span></div>
			<?php }?>
			<?php if($value['rule_info']['num2']){?>
				<input type="hidden" value="<?php echo $value['rule_info']['num2'];?>" class="input2"/>
				<div class="lt-item"><span class="rule-num"><em><?php echo $value['rule_info']['num2'];?></em> 件起</span><span class="rule-price"><?php echo ncPriceFormatForList($value['rule_info']['price2']);?></span></div>
			<?php }?>
			<?php if($value['rule_info']['num3']){?>
				<input type="hidden" value="<?php echo $value['rule_info']['num3'];?>" class="input3"/>
				<div class="lt-item"><span class="rule-num"><em><?php echo $value['rule_info']['num3'];?></em> 件起</span><span class="rule-price"><?php echo ncPriceFormatForList($value['rule_info']['price3']);?></span></div>
			<?php }?>
			<?php if($value['rule_info']['num4']){?>
				<input type="hidden" value="<?php echo $value['rule_info']['num4'];?>" class="input4"/>
				<div class="lt-item"><span class="rule-num"><em><?php echo $value['rule_info']['num4'];?></em> 件起</span><span class="rule-price"><?php echo ncPriceFormatForList($value['rule_info']['price4']);?></span></div>
			<?php }?>
			<?php if($value['rule_info']['num5']){?>
				<input type="hidden" value="<?php echo $value['rule_info']['num5'];?>" class="input5"/>
				<div class="lt-item"><span class="rule-num"><em><?php echo $value['rule_info']['num5'];?></em> 件起</span><span class="rule-price"><?php echo ncPriceFormatForList($value['rule_info']['price5']);?></span></div>
			<?php }?>
		  <div class="add-cart">
            <?php if ($value['goods_storage'] == 0) {?>
				<?php if ($value['is_appoint'] == 1) {?>
				<a href="javascript:void(0);" onclick="<?php if ($_SESSION['is_login'] !== '1'){?>login_dialog();<?php }else{?>ajax_form('arrival_notice', '立即预约', '<?php echo urlShop('goods', 'arrival_notice', array('goods_id' => $value['goods_id'], 'type' => 2));?>', 350);<?php }?>"><i class="icon-bullhorn"></i>立即预约</a>
				<?php } else {?>
				<a href="javascript:void(0);" onclick="<?php if ($_SESSION['is_login'] !== '1'){?>login_dialog();<?php }else{?>ajax_form('arrival_notice', '到货通知', '<?php echo urlShop('goods', 'arrival_notice', array('goods_id' => $value['goods_id'], 'type' => 2));?>', 350);<?php }?>"><i class="icon-bullhorn"></i>到货通知</a>
				<?php }?>
            <?php } else {?>
				<?php //if(C('pc_buy')){?>
					<!--a href="javascript:void(0);" nctype="buy_now" data-param="{goods_id:<?php echo $value['goods_id'];?>}"><i class="icon-shopping-cart"></i>立即购买</a-->
				<?php //}?> 
				<?php if(C('pc_cart')){?>
					<a href="<?php echo $value['goods_href'];?>" data-param="{goods_id:<?php echo $value['goods_id'];?>, store_id:<?php echo $value['store_id'];?>}" src="<?php echo thumb($value, 240);?>"><?php if($value['add_cart']){ echo $value['add_cart'];?><?php }else{?><i class="icon-shopping-cart"></i>去下单<?php }?></a>
				<?php }?>
            <?php }?>
          </div>
        </div>
      </div>
    </li>
	 <?php }?>
    <?php }?>
    <div class="clear"></div>
  </ul>
  <?php }else{?>
  <div id="no_results" class="no-results"><i></i><?php echo $lang['index_no_record'];?></div>
  <?php }?>
</div>
<form id="buynow_form" method="post" action="<?php echo SHOP_SITE_URL;?>/index.php" target="_blank">
  <input id="gct" name="gct" type="hidden" value="buy" />
  <input id="gp" name="gp" type="hidden" value="buy_step1" />
  <input id="goods_id" name="cart_id[]" type="hidden"/>
</form>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script> 
<script type="text/javascript">
    $(document).ready(function(){
        $('.raty').raty({
            path: "<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/img",
            readOnly: true,
            width: 80,
            score: function() {
              return $(this).attr('data-score');
            }
        });
      	//初始化对比按钮
    	initCompare();
    });
</script> 
