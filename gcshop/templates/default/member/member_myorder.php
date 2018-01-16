<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />


<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>

  <div class="clear"></div>
    <div class="mycode-tag ">
      <dl>
        <!--dt><i class="icon-tag">我的返利订单</i></dt-->
		 <!--form id="member_form" method="post"  action="index.php?gct=member_information&gp=<?php echo $output['menu_key']; ?>">
		  <select type="text" id="member_id" name="member_id" class="txt" value="<?php echo $output['memberinfo'];?>">
                 <option value="0">选择客户</option>
				 <?php if(!empty($output['memberinfo']) ){ ?>
                  <?php foreach ($output['memberinfo'] as $k => $v) {?>
                  <option value="<?php echo $v['member_id'];?>"><?php echo $v['member_name'];?>|<?php echo $v['member_truename'];?></option>
				 <?php } ?>
				 <?php }?>
          </select>
		  <a href="JavaScript:void(0);" class="btn" id="submitBtnmem"><span><?php echo $lang['nc_submit'];?></span></a>
		  </form-->
      </dl>
	<table class="ncm-default-table">

      <thead>
	  <tr>
	  <th class="w100">订单编号</th>
	  <th class="w100">买家账号</th>
	  <td class="w300">商品名称</td>
	  <th class="w80">返利率</th>
	  <th class="w80">商品金额</th>
	  <th class="w50">数量</th>
	  <th class="w80">预返利金额</th>
	  <th class="w80">订单状态</th>
	  <th class="w150">下单时间</th>
	  <th class="w80">返利方式</th>
	  </tr>
	  </thead>
	  <tbody>  
	  <?php if(!empty($output['order_list']) ){ ?>
      <?php foreach($output['order_list'] as $k => $v){  ?>
	  <tr class="bd-line">
	  <td class="w100"><?php echo $v['order_sn']; ?></td>
	  <td class="w100"><?php echo $v['buyer_name']; ?></td>
	  <td class="w300">
	  <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $v['goods_id']));?>" title="<?php echo $v['goods_id']; ?>" target="_blank">
	  <?php echo $v['goods_name']; ?>
	  </a>
	  </td>
	  <td class="w80"><?php echo $v['goods_rebate_rate']; ?></td>
	  <td class="w80"><?php echo $v['goods_price']; ?></td>
	  <td class="w50"><?php echo $v['goods_num']; ?></td>
	  <td class="w80"><?php echo $v['goods_rebate_amount']; ?></td>
	  <td class="w80"><?php if( $v['order_state']==0){echo "已取消";}elseif($v['order_state']==10){echo "未付款";}elseif($v['order_state']==20){echo "已付款";}elseif($v['order_state']==30){echo "已发货";}elseif($v['order_state']==40){echo "已收货";}else{echo "未知数";} ?></td>
	  <td class="w150"><?php echo @date('Y-m-d H:m:s',$v['add_time']);?></td>
	  <td class="w50"><?php  if($v['order_distinguish']){ echo '商品'; }else{ echo '个人'; } ?></td>             
	  </tr>
	  <?php } ?>
	  <?php }else { ?>
        <tr class="no_data">
          <td colspan="11"><strong style="color:#360"><?php echo $lang['no_record']; ?></strong></td>
        </tr>
        <?php } ?>
	  </tbody>
    <tfoot>
      <?php  if (count($output['order_list'])>0) { ?>
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

<script>
//按钮先执行验证再提交表单
$(function(){
	$("#submitBtnmem").click(function(){
		$("#member_form").submit();
	});
</script>
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






