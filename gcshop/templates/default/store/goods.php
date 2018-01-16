<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_goods.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/imagezoom/jquery.imagezoom.min.js"></script>
<style type="text/css">
.ncs-goods-picture .levelB, .ncs-goods-picture .levelC { cursor: url(<?php echo SHOP_TEMPLATES_URL;?>/images/gcshop/zoom.cur), pointer;}
.ncs-goods-picture .levelD { cursor: url(<?php echo SHOP_TEMPLATES_URL;?>/images/gcshop/hand.cur), move\9;}

#img{width: 120px;height: 120px;display: block;position: absolute;z-index: 999;}
</style>


<div id="content" class="wrapper pr">
  <input type="hidden" id="lockcompare" value="unlock" />
	
  <div class="ncs-detail<?php if ($output['store_info']['is_own_shop']) echo ' ownshop'; ?>">
	
    <!-- S 商品图片 -->
   <div id="ncs-goods-picture" class="ncs-goods-picture image_zoom">
    <!-- 商品图新版开始 -->
	<div class="box go-box">
		<div class="tb-booth tb-pic tb-s310">
			<a href="javascript:void(0);" target="_blank"><img src="<?php  echo $output["goods_image"]["0"]["1"] ?>" alt="<?php echo $output['goods']['goods_name']; ?>" rel="<?php echo $output["goods_image"]["0"]["2"] ?>" class="jqzoom"></a>
			<?php if ( $output["goods"]['goods_storage'] == 0 && $output["goods"]['goods_salenum'] > 0) {?>
				<div class="goods-sold"></div>
			<?php }?>
          <?php if ( ($output["goods"]['goods_fenlei'] == "新品") || (strpos( $output["goods"]['goods_jingle'],"新品") !== false ) ) {?>
            <div class="goods-new">新品</div>
          <?php }?>
		</div>

		<ul class="tb-thumb" id="thumblist">
		<?php foreach ($output["goods_image"] as $key => $value) { ?>
		 <!-- PC端商品详情图缩略图最多显示5张 -->
		<?php if($key<=4) {?> 
		
			<li>
				<div class="tb-pic tb-s40">
					<a href="javascript:void(0);"><img src="<?php echo $value['0'] ?>" mid="<?php echo $value['1'] ?>" big="<?php echo $value['2'] ?>"></a>
				</div>
			</li>
		<?php  }?>
		<?php  }?>
		</ul>
	</div>
    <!-- 商品图新版结束 -->
   </div>
   
    <!-- S 商品基本信息 -->
    <div class="ncs-goods-summary">
	  <div class="country">
	  <?php if($output['goods']['country_code']){ ?>
        <span>
			<img src="<?php echo UPLOAD_SITE_URL?>/country/<?php echo $output['goods']['country_code'];?>.png"/>
		</span>
	  <?php }?>
		<span class="mess_country"><?php echo $output['goods']['mess_country'];?></span>
		<span class="cut">|</span>
		<span class="brand_name"><?php echo $output['goods']['brand_name'];?></span>
	  </div>
	  
      <div class="name">
        <h1><?php echo $output['goods']['goods_name']; ?></h1>
        <span class="yes"><strong><font  ><?php echo str_replace("\n", "<br>", $output['goods']['goods_jingle']);?></font></strong> <span>
	  </div>
      <div class="ncs-meta">
       
	    <!-- S 商品参考价格 -->
        <dl class="">
		<dt><?php echo $lang['goods_index_goods_cost_price'];?><?php echo $lang['nc_colon'];?></dt>
        <dd class="cost-price"><strong><?php echo $lang['currency'].$output['goods']['goods_marketprice'];?></strong></dd>
        </dl>
        <!-- E 商品参考价格 -->
        <!-- S 商品发布价格 -->
        <dl class="inline">
          <dt><?php echo $lang['goods_index_goods_price'];?><?php echo $lang['nc_colon'];?></dt>
          <dd class="price">
            <?php if (isset($output['goods']['title']) && $output['goods']['title'] != '') {?>
            <span class="tag"><?php echo $output['goods']['title'];?></span>
            <?php }?>
            <?php if (isset($output['goods']['promotion_price']) && !empty($output['goods']['promotion_price'])) {?>
            <strong><?php echo $lang['currency'].$output['goods']['promotion_price'];?></strong><em>(原售价<?php echo $lang['nc_colon'];?><?php echo $lang['currency'].$output['goods']['goods_price'];?>)</em>
            <?php } else {?>
          	<strong><?php echo $lang['currency'].$output['goods']['goods_price'];?></strong><font style="font-size:12px; color:#FFF;"></font>&nbsp;			
			<?php }?>
          </dd>
        </dl>
        <!-- E 商品发布价格 -->
		
		
		
		<!-- S 手机购买 -->
        <dl class="goods_code inline">
			<dt ><?php echo $lang['goods_index_mobile_buy']	?><img  class="mobile_img" src="<?php echo SHOP_SITE_URL?>/templates/default/images/mobile.png"/><i><img  class="bottom_img" src="<?php echo SHOP_SITE_URL?>/templates/default/images/bottom.png"/><i></dt>
				<img class="code_img" src="<?php echo goodsQRCode($output['goods']);?>"/>
        </dl>
        <!-- E 手机购买 -->
		
        <!-- S 促销 -->
        <?php if (isset($output['goods']['promotion_type']) || $output['goods']['have_gift'] == 'gift') {?>
        <dl class="">
          <dt>促销信息：</dt>
          <dd class="promotion-info">
            <!-- S 限时折扣 -->
            <?php if ($output['goods']['promotion_type'] == 'xianshi') {?>
				<?php echo '直降：'.$lang['currency'].$output['goods']['down_price'];?>
				<?php if($output['goods']['lower_limit']) {?>
					<em><?php echo sprintf('最低%s件起',$output['goods']['lower_limit']);?></em>
				<?php } ?>
				<span><?php echo $output['goods']['explain'];?></span><br>
            <?php }?>
            <!-- E 限时折扣  -->
			
            <!-- S 抢购-->
            <?php if ($output['goods']['promotion_type'] == 'groupbuy') {?>
				<?php if ($output['goods']['upper_limit']) {?>
					<em><?php echo sprintf('最多限购%s件',$output['goods']['upper_limit']);?></em>
				<?php } ?>
				<span><?php echo $output['goods']['remark'];?></span><br>
            <?php }?>
            <!-- E 抢购 -->
			
            <!-- S 赠品 -->
            <?php if ($output['goods']['have_gift'] == 'gift') {?>
				<?php echo '赠品'?> <span>赠下方的热销商品，赠完即止</span>
            <?php }?>
            <!-- E 赠品 -->
          </dd>
        </dl>
        <?php }?>
        <!-- E 促销 -->
		<?php if ($output['goods']['store_from'] == 1 || $output['goods']['store_from'] == 2) {?>
			<div class="goods_waring">
			在光彩全球购买的保税商品仅限个人使用，不得进行二次销售。<br />
			</div>
		<?php }?>
      </div>
      <div class="ncs-plus">
        <!-- S 物流运费  预售商品不显示物流 -->
		
        <?php if ($output['goods']['is_virtual'] == 0) {?>
        <dl class="ncs-freight">
        	
          <dt>
            <?php if ($output['goods']['goods_transfee_charge'] == 1){?>
				<?php echo $lang['goods_index_freight'].$lang['nc_colon'];?>
            <?php }else{?>
				<!-- 如果买家承担运费 -->
				<!-- 如果使用了运费模板 -->
				<?php //echo $output['goods']['areaid_1'];?>
				 <?php echo $output['regions'][$output['transports'][$output['goods']['transport_id']]['region_id']]['address_name'];?>
				<?php if ($output['goods']['transport_id'] != '0'){?>
					<?php echo $lang['goods_index_trans_to'];?><a href="javascript:void(0)" id="ncrecive"><?php echo $lang['goods_index_trans_country'];//全国?></a><?php echo $lang['nc_colon'];?>
					<div class="ncs-freight-box" id="transport_pannel">
					  <?php if (is_array($output['area_list'])){?>
					  <?php foreach($output['area_list'] as $k=>$v){?>
					  <a href="javascript:void(0)" nctype="<?php echo $k;?>"><?php echo $v;?></a>
					  <?php }?>
					  <?php }?>
					</div>
				<?php }else{?>
					<?php echo $lang['goods_index_trans_zcountry'];?><?php echo $lang['nc_colon'];?>
				<?php }?>
            <?php }?>
          </dt>
          <dd id="transport_price">
            <?php if($output['goods']['promotion_type'] == 'groupbuy') { ?>
				<span id="nc_kd"><?php echo $lang['goods_index_freight'] ?><?php echo $lang['nc_colon'];?><em><?php echo $output['goods']['goods_freight'];?></em><?php echo $lang['goods_index_yuan'];?></span>
				<span class='postage'><?php echo $lang['goods_index_groupbuy_shipping_fee'];?></span>
            <?php } else { ?>
				<?php if ($output['goods']['goods_freight'] == 0){?>
					<span id="nc_kd"><?php echo $lang['goods_index_trans_for_seller'];?></span>
				<?php }else{?>
					<!-- 如果买家承担运费 -->
					<span id="nc_kd"><?php echo $lang['goods_index_freight'] ?><?php echo $lang['nc_colon'];?><em><?php echo $output['goods']['goods_freight'];?></em><?php echo $lang['goods_index_yuan'];?></span>
					<?php if (!empty($output['free_freight_list'][$output['store_id']])) {?>
						<span class='postage'><?php echo $output['free_freight_list'][$output['store_id']];?></span>
					<?php }?>
				<?php }?>
            <?php }?>
          </dd>
          <dd style="color:red;display:none" id="loading_price">loading.....</dd>
        </dl>
        <?php }?>
        <!-- E 物流运费 --->
		<dl class="ncs-freight" style="display:none;">
			<dt>税费：</dt>
			<dd class="taxes_rule">
				<span>本商品适用税率为11.9%</span>  
				<span><a href="<?php echo SHOP_SITE_URL?>/article-76.html" target="_blank">税费收取规则</a></span> 
			</dd>
		</dl>
        <!-- S 赠品 -->
        <?php if ($output['goods']['have_gift'] == 'gift') {?>
        <dl>
          <dt><?php echo $lang['goods_index_gift_goods'] ?></dt>
          <dd class="goods-gift" id="ncsGoodsGift">
            <?php if (!empty($output['gift_array'])) {?>
            <ul>
              <?php foreach ($output['gift_array'] as $val){?>
              <li>
                <div class="goods-gift-thumb"><span><img src="<?php echo cthumb($val['gift_goodsimage'], '60', $output['goods']['store_id']);?>"></span></div>
                <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['gift_goodsid'],'ref'=>$_SESSION['member_id']));?>" class="goods-gift-name" target="_blank"><?php echo $val['gift_goodsname']?></a><em>x<?php echo $val['gift_amount'];?></em> </li>
              <?php }?>
            </ul>
            <?php }?>
          </dd>
        </dl>
        <?php }?>
        <!-- S 赠品 -->
      </div>
      <?php if($output['goods']['goods_state'] != 10 && $output['goods']['goods_verify'] == 1){?>
      <div class="ncs-key">
        <!-- S 商品规格值-->
        <?php if (is_array($output['goods']['spec_name'])) { ?>
        <?php foreach ($output['goods']['spec_name'] as $key => $val) {?>
        <dl nctype="nc-spec">
          <dt><?php echo $val;?><?php echo $lang['nc_colon'];?></dt>
          <dd>
            <?php if (is_array($output['goods']['spec_value'][$key]) and !empty($output['goods']['spec_value'][$key])) {?>
            <ul nctyle="ul_sign">
              <?php foreach($output['goods']['spec_value'][$key] as $k => $v) {?>
              <?php if( $key == 1 ){?>
              <!-- 图片类型规格-->
              <li class="sp-img"><a href="javascript:void(0);" class="<?php if (isset($output['goods']['goods_spec'][$k])) {echo 'hovered';}?>" data-param="{valid:<?php echo $k;?>}" title="<?php echo $v;?>"><img src="<?php echo $output['spec_image'][$k];?>"/><?php echo $v;?><i></i></a></li>
              <?php }else{?>
              <!-- 文字类型规格-->
              <li class="sp-txt"><a href="javascript:void(0)" class="<?php if (isset($output['goods']['goods_spec'][$k])) { echo 'hovered';} ?>" data-param="{valid:<?php echo $k;?>}"><?php echo $v;?><i></i></a></li>
              <?php }?>
              <?php }?>
            </ul>
            <?php }?>
          </dd>
        </dl>
        <?php }?>
        <?php }?>
        <!-- E 商品规格值-->
         
        <?php if ($output['goods']['is_virtual'] == 1) {?>
			<dl>
			  <dt>提货方式：</dt>
			  <dd>
				<ul>
				  <li class="sp-txt"><a href="javascript:void(0)" class="hovered">电子兑换券<i></i></a></li>
				</ul>
			  </dd>
			</dl>
        <?php }?>
        <?php if ($output['goods']['is_virtual'] == 1) {?>
        <!-- 虚拟商品有效期 -->
        <dl>
          <dt>有&nbsp;效&nbsp;期：</dt>
          <dd>即日起 到 <?php echo date('Y-m-d H:i:s', $output['goods']['virtual_indate']);?></dd>
        </dl>
        <?php }else if ($output['goods']['is_presell'] == 1) {?>
        <!-- 预售商品发货时间 -->
        <dl>
          <dt>预&#12288;&#12288;售：</dt>
          <dd><ul><li class="sp-txt"><a href="javascript:void(0)" class="hovered"><?php echo date('Y-m-d', $output['goods']['presell_deliverdate']);?>&nbsp;日发货<i></i></a></li></ul></dd>
        </dl>
        <?php }?>
        <?php if ($output['goods']['is_fcode']) {?>
        <!-- 预售商品发货时间 -->
        <dl>
          <dt>购买类型：</dt>
          <dd><ul><li class="sp-txt"><a href="javascript:void(0)" class="hovered">F码优先购买<i></i></a></li></ul></dd>
        </dl>
        <?php }?>
        <!-- S 购买数量及库存 -->
        <?php if ($output['goods']['goods_state'] != 0 && $output['goods']['goods_storage'] >= 0) {?>
        <dl>
          <dt class="buy_num"><?php echo $lang['goods_index_buy_amount'];?><?php echo $lang['nc_colon'];?></dt>
          <dd class="ncs-figure-input">
			
			<a href="javascript:void(0)" class="decrease">-</a> 
            <input type="text" name="" id="quantity" value="1" size="3" maxlength="6" class="text w50" <?php if ($output['goods']['is_fcode'] == 1) {?>readonly<?php }?>>
            <?php if ($output['goods']['is_fcode'] == 1) {?>
            <span style="margin-left: 5px;">（每个F码优先购买一件商品）</span><?php if($output['goods']['goods_storage'] >0 && C('is_storage')){?>(<?php echo $lang['goods_index_stock'];?><em nctype="goods_stock"><?php echo $output['goods']['goods_storage']; ?></em><?php echo $lang['nc_jian'];?>)<?php } ?>
            <?php } else {?>
            <a href="javascript:void(0)" class="increase">+</a>
			<input type="hidden" value="<?php echo $output['goods']['goods_storage']; ?>"  nctype="goods_stock"/>
			<span class="o-goods">
			<?php if($output['goods']['goods_storage'] >0 ){?>
			  <?php if(C('is_storage')){?>
				<?php  echo $lang['goods_index_stock'];?><em nctype="goods_stock"><?php echo $output['goods']['goods_storage']; ?></em><?php echo $lang['nc_jian'];?>
			  <?php } ?>
			<?php }else{?>
				<span class="no"><i class="icon-exclamation-sign"></i>&nbsp;<?php echo $lang['goods_index_understock_prompt'];?></span>
				<a href="javascript:void(0);" nctype="arrival_notice" class="arrival">（<i class="icon-bullhorn"></i>到货通知）</a>
			<?php } ?>
            <!-- 虚拟商品限购数 -->
            <?php if ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_limit'] > 0) { ?>，每人次限购<strong>
              <!-- 虚拟抢购 设置了虚拟抢购限购数 该数小于原商品限购数 -->
              <?php echo ($output['goods']['promotion_type'] == 'groupbuy' && $output['goods']['upper_limit'] > 0 && $output['goods']['upper_limit'] < $output['goods']['virtual_limit']) ? $output['goods']['upper_limit'] : $output['goods']['virtual_limit'];?>
              </strong>件<?php } ?>
             </span><?php } ?>
          </dd>
        </dl>
        <?php }?>
        <!-- E 购买数量及库存 -->
      </div>
      <!-- S 购买按钮 -->
        <div class="ncs-btn">
          
          <div class="clear"></div>
          
          <!-- 预约 -->
          <?php if (($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) && $output['goods']['is_appoint'] == 1) {?>
          <div>销售时间：<?php echo date('Y-m-d H:i:s', $output['goods']['appoint_satedate']);?></div>
          <a href="javascript:void(0);" nctype="appoint_submit" class="addcart" title="立即预约">立即预约</a>
          <?php }?>
          <!-- 立即购买-->
		  <?php if(C('pc_buy')){?>
			<a href="javascript:void(0);" nctype="buynow_submit" class="buynow <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0 || ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_indate'] < TIMESTAMP)) {?>no-buynow<?php }?>" title="<?php echo $output['goods']['buynow_text'];?>"><?php echo $lang['goods_index_now_buy']//$output['goods']['buynow_text'];?></a>
		  <?php } ?>
          <?php //if ($output['goods']['promotion_type'] != groupbuy) {?>
          <!-- 加入购物车-->
		  <?php if(C('pc_cart')){?>
			<a href="javascript:void(0);" nctype="addcart_submit" class="addcart <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) {?>no-addcart<?php }?>" title="<?php echo $lang['goods_index_add_to_cart'];?>"><i class="icon-shopping-cart"></i><?php echo $lang['goods_index_add_to_cart'];?></a>
          <?php } ?>
          <?php //} ?>
		    <?php if (!empty($output['bundling_array'])) {?><a href="#buygroup" class="addcart">优惠套装</a><?php } ?>		
			<?php if (!empty($output['gcombo_list'])) {?><a href="#buygroup" class="addcart">推荐组合</a><?php } ?>	
			

		  <!-- E 商品图片及收藏分享 -->
		  <div class="ncs-handle">
		  <a name="buygroup"></a>
			<!-- S 对比 -->
			<a href="javascript:void(0);" class="compare" nc_type="compare_<?php echo $output['goods']['goods_id'];?>" data-param='{"gid":"<?php echo $output['goods']['goods_id'];?>"}'><img class="compare_img" src="<?php echo SHOP_SITE_URL?>/templates/default/images/compare.png"/><?php echo $lang['goods_index_join_contrast'] ?></a>
			
			<!-- S 分享 -->
			<a href="javascript:void(0);" class="share" nc_type="sharegoods" data-param='{"gid":"<?php echo $output['goods']['goods_id'];?>"}'><img class="share_img" src="<?php echo SHOP_SITE_URL?>/templates/default/images/share.png"/><i></i><?php echo $lang['goods_index_snsshare_goods'];?></a>
		  
			<!-- S 收藏 -->
			<a href="javascript:collect_goods('<?php echo $output['goods']['goods_id']; ?>','count','goods_collect');" class="favorite">
			<?php if($output['is_favorites']==1){ ?>
			<img class="collect_img" src="<?php echo SHOP_SITE_URL?>/templates/default/images/collect-y.png"/>
			<?php }else{ ?>
			<img class="collect_img" src="<?php echo SHOP_SITE_URL?>/templates/default/images/collect-n.png"/>
			<?php } ?>
			<i></i><?php echo $lang['goods_index_favorite_goods'];?></a>

			<!-- S 举报 -->
			<?php if($output['inform_switch']) { ?>
			<a href="<?php if ($_SESSION['is_login']) {?>index.php?gct=member_inform&gp=inform_submit&goods_id=<?php echo $output['goods']['goods_id'];?><?php } else {?>javascript:login_dialog();<?php }?>" title="<?php echo $lang['goods_index_goods_inform'];?>" class="inform"><img class="inform_img" src="<?php echo SHOP_SITE_URL?>/templates/default/images/report.png"/><i></i><?php echo $lang['goods_index_goods_inform'];?></a>
			<?php } ?>
		  </div>
		  
          <!-- S 加入购物车弹出提示框 -->
          <div class="ncs-cart-popup">
            <dl>
              <dt><?php echo $lang['goods_index_cart_success'];?><a title="<?php echo $lang['goods_index_close'];?>" onClick="$('.ncs-cart-popup').css({'display':'none'});">X</a></dt>
              <dd><?php echo $lang['goods_index_cart_have'];?> <strong id="bold_num"></strong> <?php echo $lang['goods_index_number_of_goods'];?> <?php echo $lang['goods_index_total_price'];?><?php echo $lang['nc_colon'];?><em id="bold_mly" class="saleP"></em></dd>
              <dd class="btns"><a href="javascript:void(0);" class="ncs-btn-mini ncs-btn-green" onClick="location.href='<?php echo SHOP_SITE_URL.DS?>index.php?gct=cart'"><?php echo $lang['goods_index_view_cart'];?></a> <a href="javascript:void(0);" class="ncs-btn-mini" value="" onClick="location.href='<?php echo SHOP_SITE_URL.DS?>index.php?gct=search&gp=index'"><?php echo $lang['goods_index_continue_shopping'];?></a></dd>
            </dl>
          </div>
		  <!-- E 加入购物车弹出提示框 -->
		  
		  <!-- S 组合和优惠套装加入购物车弹出提示框 -->
		  <div class="ncs-cart-popup1">
            <dl>
              <dt><?php echo $lang['goods_index_cart_success'];?><a title="<?php echo $lang['goods_index_close'];?>" onClick="$('.ncs-cart-popup1').css({'display':'none'});">X</a></dt>
              <dd><?php echo $lang['goods_index_cart_have'];?> <strong id="bold_num1"></strong> <?php echo $lang['goods_index_number_of_goods'];?> <?php echo $lang['goods_index_total_price'];?><?php echo $lang['nc_colon'];?><em id="bold_mly1" class="saleP"></em></dd>
              <dd class="btns"><a href="javascript:void(0);" class="ncs-btn-mini ncs-btn-green" onClick="location.href='<?php echo SHOP_SITE_URL.DS?>index.php?gct=cart'"><?php echo $lang['goods_index_view_cart'];?></a> <a href="javascript:void(0);" class="ncs-btn-mini" value="" onClick="location.href='<?php echo SHOP_SITE_URL.DS?>index.php?gct=search&gp=index'"><?php echo $lang['goods_index_continue_shopping'];?></a></dd>
            </dl>
          </div>
          <!-- E 组合和优惠套装加入购物车弹出提示框 -->

        </div>
        <!-- E 购买按钮 -->
      <?php }else{?>
      <div class="ncs-saleout">
        <dl>
          <dt><i class="icon-info-sign"></i><?php echo $lang['goods_index_is_no_show'];?></dt>
          <dd><?php echo $lang['goods_index_is_no_show_message_one'];?></dd>
          <dd><?php echo $lang['goods_index_is_no_show_message_two_1'];?>&nbsp;<a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$output['goods']['store_id']), $output['store_info']['store_domain']);?>" class="ncs-btn-mini"><?php echo $lang['goods_index_is_no_show_message_two_2'];?></a>&nbsp;<?php echo $lang['goods_index_is_no_show_message_two_3'];?> </dd>
        </dl>
      </div>
      <?php }?>
      <!--E 商品信息 -->

    </div>
	
	<?php if(!empty($output['goods_commend']) && is_array($output['goods_commend']) && count($output['goods_commend'])>1){?>
      <div class="ncs-recommend">
        <div class="title">
          <h2><?php echo $lang['goods_index_goods_commend'];?></h2>
        </div>
        <div class="content">
          <ul>
            <?php foreach($output['goods_commend'] as $goods_commend){?>
            <?php if($output['goods']['goods_id'] != $goods_commend['goods_id']){?>
            <li>
              <dl>
                <dt class="goods-name"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $goods_commend['goods_id'],'ref'=>$_SESSION['member_id']));?>" target="_blank" title="<?php echo $goods_commend['goods_jingle'];?>"><?php echo $goods_commend['goods_name'];?><em><?php echo $goods_commend['goods_jingle'];?></em></a></dt>
                <dd class="goods-pic"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $goods_commend['goods_id'],'ref'=>$_SESSION['member_id']));?>" target="_blank" title="<?php echo $goods_commend['goods_jingle'];?>"><img src="<?php echo thumb($goods_commend, 240);?>" alt="<?php echo $goods_commend['goods_name'];?>"/></a></dd>
                <dd class="goods-price"><?php echo $lang['currency'];?><?php echo $goods_commend['goods_price'];?><strong><?php echo $lang['currency'].$goods_commend['goods_marketprice'];?></strong></dd>
              
              </dl>
            </li>
            <?php }?>
            <?php }?>
          </ul>
          <div class="clear"></div>
        </div>
      </div>
      <?php }?>
	

    <!--S 店铺信息-->
    <div style=" position: absolute; z-index: 1; top: -1px; right: -1px;">
      <?php include template('store/info');?>
    </div>
    <!--E 店铺信息 -->
    <div class="clear"></div>
  </div>
  
  <div class="ncs-goods-layout expanded" >
    <div class="ncs-goods-main" id="main-nav-holder">
      <!-- S 优惠套装 -->
      <div class="ncs-promotion" id="nc-bundling" style="display:none;"></div>
      <!-- E 优惠套装 -->
      <div class="tabbar pngFix" id="main-nav">
        <div class="ncs-goods-title-nav">
          <ul id="categorymenu">
            <li class="current" id="particulars"><a id="tabGoodsIntro" href="#particulars"><?php echo $lang['goods_index_goods_info'];?></a></li>
            <li id="evaluation"><a id="tabGoodsRate" href="#evaluation"><?php echo $lang['goods_index_evaluation'];?><em>(<?php echo $output['goods_evaluate_info']['all'];?>)</em></a></li>
			 <!--
			 <li><a id="tabGoodsTraded" href="#content"><?php echo $lang['goods_index_sold_record'];?><em>(<?php echo $output['goods']['goods_salenum']; ?>)</em></a></li>
            <li><a id="tabGuestbook" href="#content"><?php echo $lang['goods_index_goods_consult'];?></a></li>
			-->
          </ul>
          <div class="switch-bar"><a href="javascript:void(0)" id="fold">&nbsp;</a></div>
        </div>
      </div>
      <div class="ncs-intro">
        <div class="content bd" id="ncGoodsIntro">

          <!--S 满就送 -->
          <?php if($output['mansong_info']) { ?>
          <div class="nc-mansong">
            <div class="nc-mansong-ico"></div>
            <dl class="nc-mansong-content">
              <dt><?php echo $output['mansong_info']['mansong_name'];?>
                <time>( <?php echo $lang['nc_promotion_time'];?><?php echo $lang['nc_colon'];?><?php echo date('Y-m-d',$output['mansong_info']['start_time']).'--'.date('Y-m-d',$output['mansong_info']['end_time']);?> )</time>
              </dt>
              <dd>
                <?php foreach($output['mansong_info']['rules'] as $rule) { ?>
                <span><?php echo $lang['nc_man'];?><em><?php echo ncPriceFormat($rule['price']);?></em><?php echo $lang['nc_yuan'];?>
                <?php if(!empty($rule['discount'])) { ?>
                ， <?php echo $lang['nc_reduce'];?><i><?php echo ncPriceFormat($rule['discount']);?></i><?php echo $lang['nc_yuan'];?>
                <?php } ?>
                <?php if(!empty($rule['goods_id'])) { ?>
                ， <?php echo $lang['nc_gift'];?> <a href="<?php echo $rule['goods_url'];?>" title="<?php echo $rule['mansong_goods_name'];?>" target="_blank"> <img src="<?php echo cthumb($rule['goods_image'], 60);?>" alt="<?php echo $rule['mansong_goods_name'];?>"> </a>&nbsp;。
                <?php } ?>
                
                </span>
                
                <?php } ?>
                
              </dd>
             	
              <dd class="nc-mansong-remark"><?php echo $output['mansong_info']['remark'];?></dd>
            </dl>
          </div>
          <?php } ?>
          <!--E 满就送 -->
          <?php if(is_array($output['goods']['goods_attr']) || isset($output['goods']['brand_name'])){?>
          <ul class="nc-goods-sort">'
            
			<?php if ($output['goods']['goods_valite_time']>0) {?>
			<li><?php echo $lang['goods_index_best_validate'] ?>：<?php echo date('Y-m-d',$output['goods']['goods_valite_time']);?></li>
			<?php }?>
            <?php if(isset($output['goods']['brand_name'])){echo '<li>'.$lang['goods_index_brand'].$lang['nc_colon'].$output['goods']['brand_name'].'</li>';}?>
            <?php if(is_array($output['goods']['goods_attr']) && !empty($output['goods']['goods_attr'])){?>
            <?php foreach ($output['goods']['goods_attr'] as $val){ $val= array_values($val);echo '<li>'.$val[0].$lang['nc_colon'].$val[1].'</li>'; }?>
			<?php if($output['goods']['store_id'] != 6){?>
            <li><?php echo $lang['goods_index_delivery_warehouse'] ?>：<?php echo $output['regions'][$output['transports'][$output['goods']['transport_id']]['region_id']]['address_name'];?></li>
            <?php   }?>
            <?php   }?>
       		
          </ul>
          <?php }?>
          <div class="ncs-goods-info-content">
			<?php if ($output['goods']['goods_video']) {?>
			<video autoplay controls style="width:100%;"><source src=<?php echo $output['goods']['goods_video']; ?> type="video/mp4"></video>
			<?php }?>
            <?php if (isset($output['plate_top'])) {?>
            <div class="top-template"><?php echo $output['plate_top']['plate_content']?></div>
            <?php }?>
            <div class="default"><?php echo $output['goods']['goods_body']; ?></div>
            <?php if (isset($output['plate_bottom'])) {?>
            <div class="bottom-template"><?php echo $output['plate_bottom']['plate_content']?></div>
            <?php }?>
			
			
          </div>
        </div>
      </div>
      <div class="ncs-comment">
        <div class="ncs-goods-title-bar hd">
          <h4><a href="javascript:void(0);"><?php echo $lang['goods_index_evaluation'];?></a></h4>
        </div>
        <div class="ncs-goods-info-content bd" id="ncGoodsRate">
          <div class="top">
            <div class="rate">
              <p><strong><?php echo $output['goods_evaluate_info']['good_percent'];?></strong><sub>%</sub><?php echo $lang['nc_eval_description_haoping'] ?></p>
              <span><?php echo $lang['nc_eval_description_total'] ?><?php echo $output['goods_evaluate_info']['all'];?><?php echo $lang['nc_eval_description_join_comment'] ?></span></div>
            <div class="percent">
              <dl>
                <dt><?php echo $lang['nc_eval_description_haoping'] ?><em>(<?php echo $output['goods_evaluate_info']['good_percent'];?>%)</em></dt>
                <dd><i style="width: <?php echo $output['goods_evaluate_info']['good_percent'];?>%"></i></dd>
              </dl>
              <dl>
                <dt><?php echo $lang['nc_eval_description_zhongping'] ?><em>(<?php echo $output['goods_evaluate_info']['normal_percent'];?>%)</em></dt>
                <dd><i style="width: <?php echo $output['goods_evaluate_info']['normal_percent'];?>%"></i></dd>
              </dl>
              <dl>
                <dt><?php echo $lang['nc_eval_description_chaping'] ?><em>(<?php echo $output['goods_evaluate_info']['bad_percent'];?>%)</em></dt>
                <dd><i style="width: <?php echo $output['goods_evaluate_info']['bad_percent'];?>%"></i></dd>
              </dl>
            </div>
            <div class="btns"><span><?php echo $lang['nc_eval_description_may_comment_goods'] ?></span>
              <p><a href="<?php if ($output['goods']['is_virtual']) { echo urlShop('member_vr_order', 'index');} else { echo urlShop('member_order', 'index');}?>" class="ncs-btn ncs-btn-red" target="_blank"><i class="icon-comment-alt"></i><?php echo $lang['nc_eval_description_comment_goods'] ?></a></p>
            </div>
          </div>
          <div class="ncs-goods-title-nav">
            <ul id="comment_tab">
              <li data-type="all" class="current"><a href="javascript:void(0);"><?php echo $lang['goods_index_evaluation'];?>(<?php echo $output['goods_evaluate_info']['all'];?>)</a></li>
              <li data-type="1"><a href="javascript:void(0);"><?php echo $lang['nc_eval_description_haoping'] ?>(<?php echo $output['goods_evaluate_info']['good'];?>)</a></li>
              <li data-type="2"><a href="javascript:void(0);"><?php echo $lang['nc_eval_description_zhongping'] ?>(<?php echo $output['goods_evaluate_info']['normal'];?>)</a></li>
              <li data-type="3"><a href="javascript:void(0);"><?php echo $lang['nc_eval_description_chaping'] ?>(<?php echo $output['goods_evaluate_info']['bad'];?>)</a></li>
            </ul>
          </div>
          <!-- 商品评价内容部分 -->
          <div id="goodseval" class="ncs-commend-main"></div>
        </div>
      </div>
      <div class="ncg-salelog" style="display:none">
        <div class="ncs-goods-title-bar hd">
          <h4><a href="javascript:void(0);"><?php echo $lang['goods_index_sold_record'];?></a></h4>
        </div>
        <div class="ncs-goods-info-content bd" id="ncGoodsTraded">
          <div class="top">
            <div class="price"><?php echo $lang['goods_index_goods_price'];?><strong><?php echo $output['goods']['goods_price'];?></strong><?php echo $lang['goods_index_yuan'];?><span><?php echo $lang['goods_index_price_note'];?></span></div>
          </div>
          <!-- 成交记录内容部分 -->
          <div id="salelog_demo" class="ncs-loading"> </div>
        </div>
      </div>
      <div class="ncs-consult" style="display:none">
        <div class="ncs-goods-title-bar hd">
          <h4><a href="javascript:void(0);"><?php echo $lang['goods_index_goods_consult'];?></a></h4>
        </div>
        <div class="ncs-goods-info-content bd" id="ncGuestbook">
          <!-- 咨询留言内容部分 -->
          <div id="consulting_demo" class="ncs-loading"> </div>
        </div>
      </div>
      
    </div>
    <div class="ncs-sidebar">
      
      
      <?php include template('store/left');?>
    </div>
  </div>
</div>
<form id="buynow_form" method="post" action="<?php echo SHOP_SITE_URL;?>/index.php">
  <input id="gct" name="gct" type="hidden" value="buy" />
  <input id="gp" name="gp" type="hidden" value="buy_step1" />
  <input name="store_id" type="hidden" value="<?php echo $output['goods']['store_id'] ;?>" />
  <input id="cart_id" name="cart_id[]" type="hidden"/>
</form>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.charCount.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js" type="text/javascript"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.F_slider.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css" id="cssfile2" />
<!-- 商品图新版 -->
<script type="text/javascript">
$(document).ready(function(){

  $(".jqzoom").imagezoom();

  $("#thumblist li a").mouseover(function () {
      $(this).parents("li").addClass("tb-selected").siblings().removeClass("tb-selected");
    $(".jqzoom").attr('src',$(this).find("img").attr("mid"));
    $(".jqzoom").attr('rel',$(this).find("img").attr("big"));
  });

	//浏览商品加积分滑动字体提醒
	var thisTop = $(".go-box").offset().top +450;
	var thisLeft = $(".go-box").offset().left+330;
	var topLength = $(".go-box").offset().top+250;
	var leftLength = $(".go-box").offset().left+330;
	var img = '+'+<?php echo C('browse_goods');?>+'积分';
	<?php if ($output['upper'] == 1 && $_SESSION['is_login'] == '1') {?>
		animatenTop(img, thisTop, thisLeft, topLength, leftLength, 120, 120, '#e6387f', 26);
	<?php }?>
});

</script>
<!-- 商品图新版结束 -->
<script type="text/javascript">


    //收藏分享处下拉操作
    jQuery.divselect = function(divselectid,inputselectid) {
      var inputselect = $(inputselectid);
      $(divselectid).mouseover(function(){
          var ul = $(divselectid+" ul");
          ul.slideDown("fast");
          if(ul.css("display")=="none"){
              ul.slideDown("fast");
          }
      });
      $(divselectid).live('mouseleave',function(){
          $(divselectid+" ul").hide();
      });
    };
$(function(){
	//赠品处滚条
	$('#ncsGoodsGift').perfectScrollbar();
    <?php if ($output['goods']['goods_state'] == 1 && $output['goods']['goods_storage'] > 0 ) {?>
    // 加入购物车
    $('a[nctype="addcart_submit"]').click(function(){
        addcart(<?php echo $output['goods']['store_id'];?>, <?php echo $output['goods']['goods_id'];?>, checkQuantity(),'addcart_callback');
    });
        <?php if (!($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_indate'] < TIMESTAMP)) {?>
        // 立即购买
        $('a[nctype="buynow_submit"]').click(function(){
            buynow(<?php echo $output['goods']['goods_id']?>,checkQuantity());
        });
        <?php }?>
    <?php }?>
    // 到货通知
    <?php if ($output['goods']['goods_storage'] == 0 || $output['goods']['goods_state'] == 0) {?>
    $('a[nctype="arrival_notice"]').click(function(){
        <?php if ($_SESSION['is_login'] !== '1'){?>
        login_dialog();
        <?php }else{?>
        ajax_form('arrival_notice', '到货通知','<?php echo urlShop('goods', 'arrival_notice', array('goods_id' => $output['goods']['goods_id'],'ref'=>$_SESSION['member_id']));?>', 350);
        <?php }?>
    });
    <?php }?>
    <?php if (($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) && $output['goods']['is_appoint'] == 1) {?>
    $('a[nctype="appoint_submit"]').click(function(){
        <?php if ($_SESSION['is_login'] !== '1'){?>
        login_dialog();
        <?php }else{?>
        ajax_form('arrival_notice', '立即预约', '<?php echo urlShop('goods', 'arrival_notice', array('goods_id' => $output['goods']['goods_id'], 'type' => 2));?>', 350);
        <?php }?>
    });
    <?php }?>
    //浮动导航  waypoints.js
    $('#main-nav').waypoint(function(event, direction) {
        $(this).parent().parent().parent().toggleClass('sticky', direction === "down");
        event.stopPropagation();
    });

    // 分享收藏下拉操作
    $.divselect("#handle-l");
    $.divselect("#handle-r");

    // 规格选择
    $('dl[nctype="nc-spec"]').find('a').each(function(){
        $(this).click(function(){
            if ($(this).hasClass('hovered')) {
                return false;
            }
            $(this).parents('ul:first').find('a').removeClass('hovered');
            $(this).addClass('hovered');
            checkSpec();
        });
    });

});

function checkSpec() {
    var spec_param = <?php echo $output['spec_list'];?>;
    var spec = new Array();
    $('ul[nctyle="ul_sign"]').find('.hovered').each(function(){
        var data_str = ''; eval('data_str =' + $(this).attr('data-param'));
        spec.push(data_str.valid);
    });
    spec1 = spec.sort(function(a,b){
        return a-b;
    });
    var spec_sign = spec1.join('|');
    $.each(spec_param, function(i, n){
        if (n.sign == spec_sign) {
            window.location.href = n.url;
        }
    });
}

// 验证购买数量
function checkQuantity(){
    var quantity = parseInt($("#quantity").val());
    if (quantity < 1) {
        alert("<?php echo $lang['goods_index_pleaseaddnum'];?>");
        $("#quantity").val('1');
        return false;
    }
    max = $('input[nctype="goods_stock"]').val();
    <?php if ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_limit'] > 0) {?>
    max = <?php echo $output['goods']['virtual_limit'];?>;
    if(quantity > max){
		showDialog('最多限购'+max+'件', 'eror','','','','','','','','',2);
        return false;
    }
    <?php } ?>
    <?php if (!empty($output['goods']['upper_limit'])) {?>
    max = <?php echo $output['goods']['upper_limit'];?>;
    if(quantity > max){
        showDialog('最多限购'+max+'件', 'eror','','','','','','','','',2);
        return false;
    }
    <?php } ?>
    if(quantity > max){
        showDialog('库存不足', 'eror','','','','','','','','',2);
        return false;
    }
    return quantity;
}

// 立即购买js
function buynow(goods_id,quantity){
<?php if ($_SESSION['is_login'] !== '1'){?>
	login_dialog();
<?php }else{?>
    if (!quantity) {
        return;
    }
    <?php if ($_SESSION['store_id'] == $output['goods']['store_id']) { ?>
    alert('不能购买自己店铺的商品');return;
    <?php } ?>
    $("#cart_id").val(goods_id+'|'+quantity);
    $("#buynow_form").submit();
<?php }?>
}

$(function(){
    //选择地区查看运费
    $('#transport_pannel>a').click(function(){
    	var id = $(this).attr('nctype');
    	if (id=='undefined') return false;
    	var _self = this,tpl_id = '<?php echo $output['goods']['transport_id'];?>';
	    var url = 'index.php?gct=goods&gp=calc&rand='+Math.random();
	    $('#transport_price').css('display','none');
	    $('#loading_price').css('display','');
	    $.getJSON(url, {'id':id,'tid':tpl_id}, function(data){
	    	if (data == null) return false;
	        if(data != 'undefined') {$('#nc_kd').html('运费<?php echo $lang['nc_colon'];?><em>' + data + '</em><?php echo $lang['goods_index_yuan'];?>');}else{'<?php echo $lang['goods_index_trans_for_seller'];?>';}
	        $('#transport_price').css('display','');
	    	$('#loading_price').css('display','none');
	        $('#ncrecive').html($(_self).html());
	    });
    });
    $("#nc-bundling").load('index.php?gct=goods&gp=get_bundling&goods_id=<?php echo $output['goods']['goods_id'];?>', function(){
        if($(this).html() != '') {
            $(this).show();
        }
    });
    $("#salelog_demo").load('index.php?gct=goods&gp=salelog&goods_id=<?php echo $output['goods']['goods_id'];?>&store_id=<?php echo $output['goods']['store_id'];?>&vr=<?php echo $output['goods']['is_virtual'];?>', function(){
        // Membership card
        $(this).find('[nctype="mcard"]').membershipCard({type:'gcshop'});
    });
	$("#consulting_demo").load('index.php?gct=goods&gp=consulting&goods_id=<?php echo $output['goods']['goods_id'];?>&store_id=<?php echo $output['goods']['store_id'];?>', function(){
		// Membership card
		$(this).find('[nctype="mcard"]').membershipCard({type:'gcshop'});
	});

/** goods.php **/
	// 商品内容部分折叠收起侧边栏控制
	$('#fold').click(function(){
  		$('.ncs-goods-layout').toggleClass('expanded');
	});
	// 商品内容介绍Tab样式切换控制
	$('#categorymenu').find("li").click(function(){
		$('#categorymenu').find("li").removeClass('current');
		$(this).addClass('current');
	});
	// 商品详情默认情况下显示全部
	$('#tabGoodsIntro').click(function(){
		$('.bd').css('display','');
		$('.hd').css('display','');
	});
	// 点击评价隐藏其他以及其标题栏
	$('#tabGoodsRate').click(function(){
		$('.bd').css('display','none');
		$('#ncGoodsRate').css('display','');
		$('.hd').css('display','none');
	});
	// 点击成交隐藏其他以及其标题
	$('#tabGoodsTraded').click(function(){
		$('.bd').css('display','none');
		$('#ncGoodsTraded').css('display','');
		$('.hd').css('display','none');
	});
	// 点击咨询隐藏其他以及其标题
	$('#tabGuestbook').click(function(){
		$('.bd').css('display','none');
		$('#ncGuestbook').css('display','');
		$('.hd').css('display','none');
	});
	//商品排行Tab切换
	$(".ncs-top-tab > li > a").mouseover(function(e) {
		if (e.target == this) {
			var tabs = $(this).parent().parent().children("li");
			var panels = $(this).parent().parent().parent().children(".ncs-top-panel");
			var index = $.inArray(this, $(this).parent().parent().find("a"));
			if (panels.eq(index)[0]) {
				tabs.removeClass("current ").eq(index).addClass("current ");
				panels.addClass("hide").eq(index).removeClass("hide");
			}
		}
	});
	//信用评价动态评分打分人次Tab切换
	$(".ncs-rate-tab > li > a").mouseover(function(e) {
		if (e.target == this) {
			var tabs = $(this).parent().parent().children("li");
			var panels = $(this).parent().parent().parent().children(".ncs-rate-panel");
			var index = $.inArray(this, $(this).parent().parent().find("a"));
			if (panels.eq(index)[0]) {
				tabs.removeClass("current ").eq(index).addClass("current ");
				panels.addClass("hide").eq(index).removeClass("hide");
			}
		}
	});

//触及显示缩略图
	$('.goods-pic > .thumb').hover(
		function(){
			$(this).next().css('display','block');
		},
		function(){
			$(this).next().css('display','none');
		}
	);

	/* 商品购买数量增减js */
	// 增加
	$('.increase').click(function(){
		num = parseInt($('#quantity').val());
	    <?php if ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_limit'] > 0) {?>
	    max = <?php echo $output['goods']['virtual_limit'];?>;
	    if(num >= max){
	        showDialog('最多限购'+max+'件', 'eror','','','','','','','','',2);
	        return false;
	    }
	    <?php } ?>
	    <?php if (!empty($output['goods']['upper_limit'])) {?>
	    max = <?php echo $output['goods']['upper_limit'];?>;
	    if(num >= max){
	        showDialog('最多限购'+max+'件', 'eror','','','','','','','','',2);
	        return false;
	    }
	    <?php } ?>
		max = $('input[nctype="goods_stock"]').val();
		if(num < max){
			$('#quantity').val(num+1);
		}else{
			showDialog('库存不足', 'eror','','','','','','','','',2);
            return false;
		}
	});
	//减少
	$('.decrease').click(function(){
		num = parseInt($('#quantity').val());
		if(num > 1){
			$('#quantity').val(num-1);
		}
	});

    //评价列表
    $('#comment_tab').on('click', 'li', function() {
        $('#comment_tab li').removeClass('current');
        $(this).addClass('current');
        load_goodseval($(this).attr('data-type'));
    });
    load_goodseval('all');
    function load_goodseval(type) {
        var url = '<?php echo urlShop('goods', 'comments', array('goods_id' => $output['goods']['goods_id'],'ref'=>$_SESSION['member_id']));?>';
        url += '&type=' + type;
        $("#goodseval").load(url, function(){
            $(this).find('[nctype="mcard"]').membershipCard({type:'gcshop'});
        });
    }
	
	$(".goods_code").hover(function() {
		
        $('.code_img').addClass("uu");
    },
      function() {
		  
        $('.code_img').removeClass("uu");
    });

    //记录浏览历史
	$.get("index.php?gct=goods&gp=addbrowse",{gid:<?php echo $output['goods']['goods_id'];?>});
	//初始化对比按钮
	initCompare();
});
/* 加入购物车后的效果函数 */
function addcart_callback(data){
	$('#bold_num').html(data.num);
    $('#bold_mly').html(price_format(data.amount));
    $('.ncs-cart-popup').fadeIn('fast');
}
</script>
