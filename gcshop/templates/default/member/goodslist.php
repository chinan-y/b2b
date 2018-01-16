<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>

  <div class="clear"></div>
  
  <div class="mycode-tag ">
	<table class="ncm-default-table">
      <thead>
	  <tr>
	  <th class="w80">商品返利率</th>
	  <th class="w120">店铺名称</th>
	  <th class="w80">商品货号</th>
	  <th class="w400">名称</th>
	  <th class="w40">价格</th>
	  <?php if(C(is_storage) == 1)  { ?>
	  <th class="w40">库存</th>
	  <th class="w120">上架时间</th>
	  <?php } ?>
	  </tr>
	  </thead>
	  <tbody>
	  
	  <?php if(!empty($output['goods_list']) ){ ?>
      <?php foreach($output['goods_list'] as $k => $v){ ?>
	  <tr class="bd-line">
	  <td class="w50"><?php echo round($v['goods_rebate_rate'],5)*100; ?>%</td>
	  <td class="w80"><?php echo $v['store_name']; ?></td>
	  <td class="w80"><?php echo $v['goods_serial']; ?></td>
	  <td class="w400">
	  <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $v['goods_id']));?>" title="<?php echo $v['goods_id']; ?>" target="_blank"><?php echo $v['goods_name']; ?></a>
	  </td>
	  <td class="w40"><?php echo $v['goods_price']; ?></td>
	  <?php if(C(is_storage) == 1)  { ?>
	  <td class="w40"><?php echo $v['goods_storage']; ?></td>
	  <td class="w120"><?php echo @date('Y-m-d H:m:s',$v['goods_addtime']);?></td>
	  <?php } ?>
	  </tr>
	  <?php } ?>
	  <?php }else { ?>
        <tr class="no_data">
          <td colspan="11"><strong style="color:#360"><?php echo $lang['no_record']; ?></strong></td>
        </tr>
        <?php } ?>
	  </tbody>
    <tfoot>
      <?php  if (count($output['goods_list'])>0) { ?>
      <tr>
        <td colspan="20">
			<div class="pagination"> <?php echo $output['show_page'];?></div>
		</td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
	</div>

  
 

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<script language="javascript">
$(function(){
	$('#stime').datepicker({dateFormat: 'yy-mm-dd'});
	$('#etime').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/jquery.qrcode.min.js" ></script> 
<script>
$(function(){
	var str = "http://www.qqbsmall.com/wap/index.html?ref=<?php echo($_SESSION['member_id']) ?>";
	$('#code').qrcode(str);

	$("#sub_btn").click(function(){
		$("#code").empty();
		var str = toUtf8($("#mytxt").val());
		
		$("#code").qrcode({
			render: "table",
			width: 200,
			height:200,
			text: str
		});
	});
})
function toUtf8(str) {   
    var out, i, len, c;   
    out = "";   
    len = str.length;   
    for(i = 0; i < len; i++) {   
    	c = str.charCodeAt(i);   
    	if ((c >= 0x0001) && (c <= 0x007F)) {   
        	out += str.charAt(i);   
    	} else if (c > 0x07FF) {   
        	out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));   
        	out += String.fromCharCode(0x80 | ((c >>  6) & 0x3F));   
        	out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));   
    	} else {   
        	out += String.fromCharCode(0xC0 | ((c >>  6) & 0x1F));   
        	out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));   
    	}   
    }   
    return out;   
} 
</script> 






