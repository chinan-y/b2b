<?php if ($output['edit_goods_sign']) {?>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<?php } else {?>
<ul class="add-goods-step">
  <li><i class="icon icon-list-alt"></i>
    <h6>STEP.1</h6>
    <h2>选择商品分类</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-edit"></i>
    <h6>STEP.2</h6>
    <h2>填写商品详情</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-camera-retro "></i>
    <h6>STEP.3</h6>
    <h2>上传商品图片</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li class="current"><i class="icon icon-edit"></i>
    <h6>STEP.4</h6>
    <h2>添加价格规则</h2>
    <i class="arrow icon-angle-right"></i> 
  </li>
  <li><i class="icon icon-ok-circle"></i>
    <h6>STEP.5</h6>
    <h2>商品发布成功</h2>
  </li>
</ul>
<?php }?>
<style type="text/css">
.ncsc-default-table tbody .num input{width:50px;}
.ncsc-default-table tbody .price input{width:80px}
</style>
<div class="alert alert-info alert-block">
  <div class="faq-img"></div>
  <h4>说明：</h4>
  <ul>
    <li>1.商品批量购买价格规则；</li>
    <li>2.数量是商品的购买数量，价格是购买数量对应的商品单价；</li>
    <li>3.从上到下数量递增，最多设置5个规格，可空。</li>
  </ul>
</div>
<form method="post" action="<?php if ($output['edit_goods_sign']) { echo urlShop('store_goods_online', 'edit_save_rule'); } else { echo urlShop('store_goods_add', 'save_rule');}?>">
  <input type="hidden" name="form_submit" value="ok">
  <input type="hidden" name="ref_url" value="<?php echo $_GET['ref_url'];?>" />
  <input type="hidden" name="commonid" value="<?php echo intval($_GET['commonid']);?>" />
     
  <table class="ncsc-default-table">
  <thead>
	<tr>
	  <th class="w50">数量</th>
	  <th class="w100">价格</th>
	</tr>
  </thead>
  <tbody>
	<tr>
	  <td class="num"><input type="text" name="num1" value="<?php echo $output['rule_info']['num1'];?>"></td>
	  <td class="price"><input type="text" name="price1" value="<?php echo $output['rule_info']['price1'];?>"></td>
	</tr>
	<tr>
	  <td class="num"><input type="text" name="num2" value="<?php echo $output['rule_info']['num2'];?>"></td>
	  <td class="price"><input type="text" name="price2" value="<?php echo $output['rule_info']['price2'];?>"></td>
	</tr>
	<tr>
	  <td class="num"><input type="text" name="num3" value="<?php echo $output['rule_info']['num3'];?>"></td>
	  <td class="price"><input type="text" name="price3" value="<?php echo $output['rule_info']['price3'];?>"></td>
	</tr>
	<tr>
	  <td class="num"><input type="text" name="num4" value="<?php echo $output['rule_info']['num4'];?>"></td>
	  <td class="price"><input type="text" name="price4" value="<?php echo $output['rule_info']['price4'];?>"></td>
	</tr>
	<tr>
	  <td class="num"><input type="text" name="num5" value="<?php echo $output['rule_info']['num5'];?>"></td>
	  <td class="price"><input type="text" name="price5" value="<?php echo $output['rule_info']['price5'];?>"></td>
	</tr>
  </tbody>
</table>
  <div class="bottom tc hr32"><label class="submit-border"><input type="submit" class="submit" value="<?php if ($output['edit_goods_sign']) { echo '提交'; } else { ?><?php echo $lang['store_goods_add_next'];?>，确认商品发布<?php }?>" /></label></div>
  
</form>
