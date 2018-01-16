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
</style>

<div class="squares" nc_type="current_display_mode">
    <input type="hidden" id="lockcompare" value="unlock" />
  <?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){?>
  <ul class="list_pics">
    <?php foreach($output['goods_list'] as $value){?>
    <li class="item">
      <div class="goods-content" nctype_goods=" <?php echo $value['goods_id'];?>" nctype_store="<?php echo $value['store_id'];?>">
        <div class="country-flag">
            <span>
			 <img src="<?php echo UPLOAD_SITE_URL?>/country/<?php echo $value['country_code'];?>.png"/>
		    </span>
        </div>
        <div class="goods-pic">
          <a href="<?php echo urlShop('goodsdetail','goodsdetail',array('goods_id'=>$value['goods_id']),null);?>" target="_blank" title="<?php echo $value['goods_name'];?>"><img src="<?php echo thumb($value, 240);?>" title="<?php echo $value['goods_name'];?>" alt="<?php echo $value['goods_name'];?>" /></a>
        </div>
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
          <div class="goods-name"><a href="<?php echo urlShop('goodsdetail','goodsdetail',array('goods_id'=>$value['goods_id']),null);?>" target="_blank" title="<?php echo $value['goods_jingle'];?>"><?php echo $value['goods_name'];?><em><?php  echo $value['goods_jingle'];?></em></a></div>
           <div class="goods-price">
		      <span class="raty">
		      <img src="http://www.qqbsmall.com/data/resource/js/jquery.raty/img/star-on.png" alt="1" title="很满意">
			  <img src="http://www.qqbsmall.com/data/resource/js/jquery.raty/img/star-on.png" alt="1" title="很满意">
			  <img src="http://www.qqbsmall.com/data/resource/js/jquery.raty/img/star-on.png" alt="1" title="很满意">
			  <img src="http://www.qqbsmall.com/data/resource/js/jquery.raty/img/star-on.png" alt="1" title="很满意">
			  <img src="http://www.qqbsmall.com/data/resource/js/jquery.raty/img/star-on.png" alt="1" title="很满意">
			  </span>
		   </div>    
          <div class="store"><a href="" title="<?php echo $value['store_name'];?>" class="name"><?php echo $value['store_name'];?></a></div>
          <a href="javascript:void(0)" class="join ncm-btn-orange" nc_type="dialog" dialog_title="申请代理" dialog_id="my_address_edit" uri="index.php?gct=goodsdetail&amp;gp=join_b2b" dialog_width="550" title="申请代理"><i class="icon-map-marker"></i>申请代理?</a>
          <div class="add-carts">
		   <a class="a"  href="<?php echo urlShop('goodsdetail','goodsdetail',array('goods_id'=>$value['goods_id']),null);?>">查看商品详细信息</a>			
          </div>
      </div>
    </li>
    <?php }?>
    <div class="clear"></div>
  </ul>
  <?php }?>
</div>
<script type="text/javascript">
//申请加盟弹出
 $('*[nc_type="dialog"]').click(function(){
        var id = $(this).attr('dialog_id');
        var title = $(this).attr('dialog_title') ? $(this).attr('dialog_title') : '';
        var url = $(this).attr('uri');
        var width = $(this).attr('dialog_width');
        CUR_DIALOG = ajax_form(id, title, url, width,0);
        return false;
  });

</script>
