<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="ncs-sidebar-container ncs-top-bar">
  <div class="title">
    <h4><?php echo $lang['nc_goods_rankings'];?></h4>
  </div>
  <div class="content">
    <div id="hot_sales_list" class="ncs-top-panel">
      <?php if(is_array($output['hot_sales']) && !empty($output['hot_sales'])){?>
      <ol>
        <?php foreach($output['hot_sales'] as $val){?>
        <li>
          <dl>
            <dt><a href="<?php echo urlShop('goods', 'index',array('goods_id'=>$val['goods_id'],'ref' => $_SESSION['member_id']));?>"><?php echo $val['goods_name']?></a></dt>
            <dd class="goods-pic"><a href="<?php echo urlShop('goods', 'index', array('goods_id'=>$val['goods_id'],'ref' => $_SESSION['member_id']));?>"><span class="thumb size40"><i></i><img src="<?php echo thumb($val, 60);?>"  onload="javascript:DrawImage(this,40,40);"></span></a>
              <p><span class="thumb size100"><i></i><img src="<?php echo thumb($val, 240);?>" onload="javascript:DrawImage(this,100,100);" title="<?php echo $val['goods_name']?>"><big></big><small></small></span></p>
            </dd>
            <dd class="price pngFix"><?php echo $val['goods_promotion_price']?></dd>
            <dd class="selled pngFix" style="display:none"><?php echo $lang['nc_sell_out'];?><strong><?php echo $val['goods_salenum'];?></strong><?php echo $lang['nc_bi'];?></dd>
          </dl>
        </li>
        <?php }?>
      </ol>
      <?php }?>
    </div>

    <p><a href="<?php echo urlShop('show_store', 'goods_all', array('store_id' => $output['store_info']['store_id'],'ref' => $_SESSION['member_id']));?>"><?php echo $lang['nc_look_more_store_goods'];?></a></p>
  </div>
</div>
