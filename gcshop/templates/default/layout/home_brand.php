<?php defined('GcWebShop') or exit('Access Invalid!');?>

          <?php if (!empty($output['show_goods_class']) && is_array($output['show_goods_class'])) { $i = 0; ?>
          <?php foreach ($output['show_goods_class'] as $key => $val) {  ?>

              <?php if (!empty($val['class3']) && is_array($val['class3'])) { ?>
              <?php foreach ($val['class3'] as $k => $v) { ?>
              
              <?php } ?>
              <?php } ?>

              <?php if (!empty($val['class2']) && is_array($val['class2'])) { ?>
              <?php foreach ($val['class2'] as $k => $v) { ?>
			  
                  <?php if (!empty($v['class3']) && is_array($v['class3'])) { ?>
                  <?php foreach ($v['class3'] as $k3 => $v3) { ?>
                  
                  <?php } ?>
                  <?php } ?>

				  <?php if (!empty($v['brands']) && is_array($v['brands'])) { $n = 0; ?>

                  <?php foreach ($v['brands'] as $k3 => $v3) {
                    if ($n++ < 10) {
                    ?>
                    <a href="<?php echo urlShop('brand','list',array('brand'=> $v3['brand_id'])); ?>"><?php echo $v3['brand_name'];?></a>
                  <?php } ?>
                  <?php } ?>

				  <?php } ?>

				  <?php } ?>
              <?php } ?>

			  
          <?php } ?>
          <?php } ?>
