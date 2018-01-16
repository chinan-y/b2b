<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo "查询订单信息";?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['manage'];?></span></a></li>
		<li><a href="index.php?gct=importorder&gp=add"><span>订单录入</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="gct" value="importorder" />
    <input type="hidden" name="gp" value="index" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
		 <th><label><?php echo '外部订单号';?></label></th>
		 <td><input class="txt2" type="text" name="out_trade_no" id="order_sn" value="<?php echo $_GET['out_trade_no'];?>" /></td>
         <th><label><?php echo $lang['order_number'];?></label></th>
         <td><input class="txt2" type="text" name="order_sn" id="order_sn" value="<?php echo $_GET['order_sn'];?>" /></td>
         <th><?php echo $lang['store_name'];?></th>
         <td><input class="txt-short" type="text" name="store_name" value="<?php echo $_GET['store_name'];?>" /></td>
         <th><label><?php echo $lang['order_state'];?></label></th>
          <td colspan="4"><select name="order_state" class="querySelect">
              <option value=""><?php echo $lang['nc_please_choose'];?></option>
              <option value="10" <?php if($_GET['order_state'] == '10'){?>selected<?php }?>><?php echo $lang['order_state_new'];?></option>
              <option value="20" <?php if($_GET['order_state'] == '20'){?>selected<?php }?>><?php echo $lang['order_state_pay'];?></option>
              <option value="30" <?php if($_GET['order_state'] == '30'){?>selected<?php }?>><?php echo $lang['order_state_send'];?></option>
              <option value="40" <?php if($_GET['order_state'] == '40'){?>selected<?php }?>><?php echo $lang['order_state_success'];?></option>
              <option value="0" <?php if($_GET['order_state'] == '0'){?>selected<?php }?>><?php echo $lang['order_state_cancel'];?></option>
            </select></td>
        
        </tr>
        <tr>
          <th><label for="query_start_time"><?php echo $lang['order_time_from'];?></label></th>
          <td><input class="txt date" type="text" value="<?php echo $_GET['query_start_time'];?>" id="query_start_time" name="query_start_time">
            <label for="query_start_time">~</label>
            <input class="txt date" type="text" value="<?php echo $_GET['query_end_time'];?>" id="query_end_time" name="query_end_time"/></td>
         <th><?php echo $lang['buyer_name'];?></th>
         <td><input class="txt-short" type="text" name="buyer_name" value="<?php echo $_GET['buyer_name'];?>" /></td> <th>付款方式</th>
         <td>
            <select name="payment_code" class="w100">
            <option value=""><?php echo $lang['nc_please_choose'];?></option>
            <?php foreach($output['payment_list'] as $val) { ?>
            <option <?php if($_GET['payment_code'] == $val['payment_code']){?>selected<?php }?> value="<?php echo $val['payment_code']; ?>"><?php echo $val['payment_name']; ?></option>
            <?php } ?>
			<option value="ccbpay">建行支付</option>
            </select>
         </td>
          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            
            </td>
        </tr>
      </tbody>
    </table>
  </form>
    
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li><?php echo $lang['order_help1'];?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <table class="table tb-type2 nobdb">
    <thead>
      <tr class="thead">
	    <th><?php echo '外部订单号';?></th>
		<th><?php echo '商品来源';?></th>
        <th><?php echo $lang['order_number'];?></th>
        <th><?php echo $lang['store_name'];?></th>
        <th><?php echo $lang['buyer_name'];?></th>
        <th class="align-center"><?php echo $lang['order_time'];?></th>
        <th class="align-center"><?php echo $lang['order_total_price'];?></th>
        <th class="align-center"><?php echo $lang['payment'];?></th>
        <th class="align-center"><?php echo $lang['order_state'];?></th>
        <th class="align-center"><?php echo $lang['nc_handle'];?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(count($output['order_list'])>0){?>
      <?php foreach($output['order_list'] as $order){?>
	  <?php  if($order['out_trade_no']){?>
      <tr class="hover">
	    <td><?php echo $order['out_trade_no'];?></td>
		<td><?php echo $order['partner_name'];?></td>
        <td><?php echo $order['order_sn'];?></td>
        <td><?php echo $order['store_name'];?></td>
        <td><?php echo $order['buyer_name'];?></td>
        <td class="nowrap align-center"><?php echo date('Y-m-d H:i:s',$order['add_time']);?></td>
        <td class="align-center"><?php echo $order['order_amount'];?></td>
        <td class="align-center"><?php echo orderPaymentName($order['payment_code']);?></td>
        <td class="align-center"><?php echo orderState($order);?></td>
        <td class="w144 align-center"><a href="index.php?gct=order&gp=show_order&order_id=<?php echo $order['order_id'];?>"><?php echo $lang['nc_view'];?></a></td>
      </tr>
	  <?php }}?>
      <?php }else{?>
      <tr class="no_data">
        <td colspan="15"><?php echo $lang['nc_no_record'];?></td>
      </tr>
      <?php }?>
    </tbody>
    <tfoot>
      <tr class="tfoot">
        <td colspan="15" id="dataFuncs"><div class="pagination"> <?php echo $output['show_page'];?> </div></td>
      </tr>
    </tfoot>
  </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(function(){
    $('#query_start_time').datepicker({dateFormat: 'yy-mm-dd'});
    $('#query_end_time').datepicker({dateFormat: 'yy-mm-dd'});
    $('#ncsubmit').click(function(){
    	$('input[name="gp"]').val('index');$('#formSearch').submit();
    });
});
$(function(){

    $('#btn').click(function(){
    	$('input[name="gp"]').val('add');
		$('#form_submit').submit();
    });
	
});
function getcity(){
        var a = $("#pro").val();
        $.post("index.php?gct=importorder&gp=get", { node:a}, function(data) {
                var obj = eval('('+data+')');
                $("#city").empty();
                $("#city").prepend("<option value='-1'>请选择市</option>");         
                for(var p in obj){
                    $("#city").append("<option name='address2' value="+obj[p].area_id+">"+obj[p].area_name+"</option>");                    
                }
            }
        );  
    }
function getq(){
        var b = $("#city").val();
        $.post("index.php?gct=importorder&gp=showcity",{code:b},
            function(data){
                var obj = eval('('+data+')');
                $("#zone").empty();
                $("#zone").prepend("<option value='-1'>请选择区</option>");         
                for(var p in obj){
                    $("#zone").append("<option name='address3' value="+obj[p].area_id+">"+obj[p].area_name+"</option>");
                     
                }
            }
        );
    }
</script> 

<script>
$(function(){
	$('#file .btn input[type="submit"]').click(function(){
		
		if($('input[type="file"]').val()!=""){
			
			$('#file').submit();
		}else{
			alert('请选择文件')
			return false;
		}
		
		
		// return false;
		// $('#file').submit();
		
	})
	var num=1;
	$('#addGoods').click(function(){
		var str="<tr>"+
				"<th><label for='import_number'>序号</label></th><td><input type='text' name='serial_number' value='"+num+"' ></td>"+
				"<th><label for='import_number'>商品编码</label></th><td><input type='text' name='goods_serials"+num+"' ></td>"+
				"<th><label for='import_num'>商品数量	</label></th><td><input type='text' name='goods_num"+num+"' ></td>"+
				"<th><label for='import_price'>商品单价</label></th><td><input type='text' name='goods_price"+num+"' ></td>"+
				"<th><label for='import_money'>商品金额</label></th><td><input type='text' name='goods_amount"+num+"' ></td>"+
				"</tr>";

		$(this).parent().before(str)
		num++;
		
	})
	
})
</script>	