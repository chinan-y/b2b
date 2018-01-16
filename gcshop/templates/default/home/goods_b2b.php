<?php defined('GcWebShop') or exit('Access Invalid!');?>
<script src="<?php echo SHOP_RESOURCE_SITE_URL.'/js/search_goods.js';?>"></script>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/layout.css" rel="stylesheet" type="text/css">
<style type="text/css">
body {
_behavior: url(<?php echo SHOP_TEMPLATES_URL;
?>/css/csshover.htc);
}
</style>
<div style="width: 100%;position: relative;z-index: 1;margin:auto;">
<span style="width: 100%;margin:auto;"><img src="../wap/images/B2B.jpg" style="width: 100%;margin:auto;"></span>
</div>
<div class="nch-container wrapper" >
  <div class="rights">
      <nav class="sort-bar" id="main-nav">
          <div class="nch-sortbar-array"> 排序方式：
          <ul>
              <li><a href="https://www.qqbsmall.com/gcshop/index.php?gct=goods_b2b&gp=goods_b2b&curpage=1" title="默认排序">默认</a></li>
              <li><a href="http://www.qqbsmall.com/gcshop/index.php?gct=goods_b2b&gp=goods_b2b&key=1&order=2" title="点击按销量从高到低排序">时间<i></i></a></li>
			  <li><a href="http://www.qqbsmall.com/gcshop/index.php?gct=goods_b2b&gp=goods_b2b&key=3&order=2" title="点击按销量从高到低排序">人气<i></i></a></li>
         </ul>
        </div>
         <div class="pagination"><?php echo $output['show_page1']; ?> </div>
		 </nav>
    <div class="shop_con_list" id="main-nav-holder">
      <!-- 商品列表循环  -->
      <div>
        <?php require_once (BASE_TPL_PATH.'/home/goodsb2b.squares.php');?>
      </div>
      <div class="tc mt20 mb20">
        <div class="pagination"> <?php echo $output['show_page']; ?> </div>
      </div>
    </div>
  </div>
</div>


