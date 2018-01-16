<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_goods.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/imagezoom/jquery.imagezoom.min.js"></script>
<style type="text/css">
.ncs-goods-picture .levelB, .ncs-goods-picture .levelC { cursor: url(<?php echo SHOP_TEMPLATES_URL;?>/images/gcshop/zoom.cur), pointer;}
.ncs-goods-picture .levelD { cursor: url(<?php echo SHOP_TEMPLATES_URL;?>/images/gcshop/hand.cur), move\9;}
</style>


<div id="content" class="wrapper pr">
  <input type="hidden" id="lockcompare" value="unlock" />
	
  <div class="ncs-detail<?php if ($output['store_info']['is_own_shop']) echo ' ownshop'; ?>">
	
    <!-- S 商品图片 -->
   <div id="ncs-goods-picture" class="ncs-goods-picture image_zoom">
    <!-- 商品图新版开始 -->
	<div class="box">
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
		<div class="goods_waring">
		针对该商品我们诚招各省市代理商<br />
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
      </div>
      <!-- S 加盟按钮 -->
        <div class="ncs-btn">        
          <div class="clear"></div>
         <div class="merchants_join">
          <a href="javascript:void(0)" class="ncm-btn ncm-btn-orange" nc_type="dialog" dialog_title="申请代理" dialog_id="my_address_edit" uri="index.php?gct=goodsdetail&amp;gp=join_b2b" dialog_width="550" title="申请代理"><i class="icon-map-marker"></i>申请代理</a>
         </div>
        </div>
        <!-- E 加盟按钮 -->
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
	
	<?php if(!empty($output['goods_commend']) && is_array($output['goods_commend']) && count($output['goods_commend'])>1&&$output['goods']['store_id']==8){?>
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
                <dt class="goods-name"><a href="index.php?gct=goodsdetail&gp=goodsdetail&goods_id=<?php echo $goods_commend['goods_id'];?>" target="_blank" title="<?php echo $goods_commend['goods_jingle'];?>"><?php echo $goods_commend['goods_name'];?><em><?php echo $goods_commend['goods_jingle'];?></em></a></dt>
                <dd class="goods-pic"><a href="index.php?gct=goodsdetail&gp=goodsdetail&goods_id=<?php echo $goods_commend['goods_id'];?>" target="_blank" title="<?php echo $goods_commend['goods_jingle'];?>"><img src="<?php echo thumb($goods_commend, 240);?>" alt="<?php echo $goods_commend['goods_name'];?>"/></a></dd>
              </dl>
            </li>
            <?php }?>
            <?php }?>
          </ul>
          <div class="clear"></div>
        </div>
      </div>
      <?php }?>
  </div>
  
  <div class="ncs-goods-layout expanded" >
    <div class="ncs-goods-main" id="main-nav-holder">
      <div class="tabbar pngFix" id="main-nav">
        <div class="ncs-goods-title-nav">
          <ul id="categorymenu">
            <li class="current" id="particulars"><a id="tabGoodsIntro" href="#particulars"><?php echo $lang['goods_index_goods_info'];?></a></li>
			 <!-- 20150706 by ming
			 <li><a id="tabGoodsTraded" href="#content"><?php echo $lang['goods_index_sold_record'];?><em>(<?php echo $output['goods']['goods_salenum']; ?>)</em></a></li>
            <li><a id="tabGuestbook" href="#content"><?php echo $lang['goods_index_goods_consult'];?></a></li>
			-->
          </ul>
          <div class="switch-bar"><a href="javascript:void(0)" id="fold">&nbsp;</a></div>
        </div>
      </div>
      <div class="ncs-intro">
        <div class="content bd" id="ncGoodsIntro">
          <?php if(is_array($output['goods']['goods_attr']) || isset($output['goods']['brand_name'])){?>
          <ul class="nc-goods-sort">'
            
			<?php if ($output['goods']['goods_valite_time']>0) {?>
			<li>最佳使用/食用期：<?php echo date('Y年m月d日',$output['goods']['goods_valite_time']);?>前</li>
			<?php }?>
            <?php if(isset($output['goods']['brand_name'])){echo '<li>'.$lang['goods_index_brand'].$lang['nc_colon'].$output['goods']['brand_name'].'</li>';}?>
            <?php if(is_array($output['goods']['goods_attr']) && !empty($output['goods']['goods_attr'])){?>
            <?php foreach ($output['goods']['goods_attr'] as $val){ $val= array_values($val);echo '<li>'.$val[0].$lang['nc_colon'].$val[1].'</li>'; }?>
			<?php if($output['goods']['store_id'] != 6){?>
            <li>发货仓库：<?php echo $output['regions'][$output['transports'][$output['goods']['transport_id']]['region_id']]['address_name'];?></li>
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

      <div class="ncg-salelog" style="display:none">
        <div class="ncs-goods-title-bar hd">
          <h4><a href="javascript:void(0);"><?php echo $lang['goods_index_sold_record'];?></a></h4>
        </div>
        <div class="ncs-goods-info-content bd" id="ncGoodsTraded">
          <div class="top">
            <div class="price"><?php echo $lang['goods_index_goods_price'];?><strong><?php echo $output['goods']['goods_price'];?></strong><?php echo $lang['goods_index_yuan'];?><span><?php echo $lang['goods_index_price_note'];?></span></div>
          </div>

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
</script>
<script type="text/javascript">
//申请加盟弹出框
 $('*[nc_type="dialog"]').click(function(){
        var id = $(this).attr('dialog_id');
        var title = $(this).attr('dialog_title') ? $(this).attr('dialog_title') : '';
        var url = $(this).attr('uri');
        var width = $(this).attr('dialog_width');
        CUR_DIALOG = ajax_form(id, title, url, width,0);
        return false;
  });
  
 $(function(){
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
</script>
<script type="text/javascript">
$(function(){
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

</script>
