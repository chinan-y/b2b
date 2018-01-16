<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_SITE_URL;?>/templates/default/css/cms_special.css"/>
<style type="text/css">
.no-content{ font: normal 16px/20px Arial, "microsoft yahei"; color: #999999; text-align: center; padding: 150px 0; 
}
.nc-appbar-tabs a.compare { display: none !important;}
</style>
<div id="body">
  <div class="cms-content">
    <?php require($output['special_file']); ?>
  </div>
</div>
