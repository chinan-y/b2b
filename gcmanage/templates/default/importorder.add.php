<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo "查询订单信息";?></h3>
      <ul class="tab-base">
	    <li><a href="index.php?gct=importorder&gp=index" ><span><?php echo $lang['manage'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>订单录入</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>

    <h3><?php echo "录入订单信息";?></h3>
    <form method="post" action="index.php" name="form_submit" id="form_submit">
	<input type="hidden" name="gct" value="importorder" />
    <input type="hidden" name="gp" value="add" />
	<input type="hidden" name="form_submit" value="ok" />
	<input id='addGoods' disabled type='button' value='增加订单商品' />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
			<th><?php echo '用户昵称';?></th>
			<td><input class="txt" type="text" name="buyer_member_nickname" /></td>
			<th><?php echo 'Email';?></th>
			<td><input class="txt" type="text" name="buyer_member_email" /></td>
			<th><?php echo '订单促销信息';?></th>
			<td><input class="txt2" type="text" name="promotion_info" /></td>
        </tr>
        <tr>
			<th><label for="import_number"><?php echo '订单来源*';?></label></th>
            <td>
            <select name="saleplat_id" class="w100">
            <option value=""><?php echo $lang['nc_please_choose'];?></option>
            <?php foreach($output['partner_name'] as $val) { ?>
            <option <?php if($_GET['saleplat_id'] == $val['saleplat_id']){?>selected<?php }?> value="<?php echo $val['partner_id']; ?>"><?php echo $val['partner_id']; ?><?php echo $val['partner_name']; ?></option>
            <?php } ?>
            </select>
			</td> 	
			<th><?php echo '手机号码*';?></th>
			<td><input class="txt" type="text" name="buyer_member_mobile" /></td>
			<th><?php echo '订购人姓名*';?></th>
			<td><input class="txt-short" type="text" name="buyer_member_truename" value="" /></td>
			<th><?php echo '身份证号码*';?></th>
			<td><input class="txt" type="text" name="buyer_member_code" value="" /></td>
        </tr>
		<tr>
		     <th><label for="import_number"><?php echo '三方平台订单号*';?></label></th>
			 <td><input type="text" name="out_trade_no" /></td>
			 <th><label for="import_number"><?php echo '运费';?></label></th>
			 <td><input class="pay_money" type="text" name="shipping_fee" value="0.00"/></td> 
			 <th><label for="import_number"><?php echo '优惠金额';?></label></th>
			 <td><input class="pay_money" type="text" name="discount_amount" value="0.00"/></td> 
			 <th><label for="import_number"><?php echo '订单金额/支付金额*';?></label></th>
			 <td><input class="pay_money" type="text" name="order_amount" value="" /></td> 	
		</tr>
		<tr>
			<th><label><?php echo '电商平台交易号*';?></label></th>
            <td><input class="txt" type="text" name="pay_sn" id="pay_sn"/></td>
			<th><label for="import_trade"><?php echo '支付平台交易号';?></label></th>
			<td><input  type="text" name="pay_trade_no" /></td>
			<th><label for="query_start_time"><?php echo '支付方式';?></label></th>
			<td>
            <select name="payment_code" class="w100">
            <option value=""><?php echo $lang['nc_please_choose'];?></option>
            <?php foreach($output['payment_list'] as $val) { ?>
            <option <?php if($_GET['payment_code'] == $val['payment_code']){?>selected<?php }?> value="<?php echo $val['payment_code']; ?>"><?php echo $val['payment_name']; ?></option>
            <?php } ?>
			<option value="ccbpay">建行支付</option>
			<option value="wxpaytian">微信支付(天下)</option>
            </select>
			</td>  
			<th><label for="import_number"><?php echo '支付金额';?></label></th>
			<td><input class="txt" type="text" name="order_pay_price" disabled placeholder="与订单金额一致，不填写" /></td> 			 
		</tr>
        <tr>
		 <th><?php echo '收件人姓名*';?></th>
         <td><input class="txt-short" type="text" name="recevicer_name" value="" /></td>
    	 <th><label for="import_phone"><?php echo '收件人手机*';?></label></th>
		 <td><input  type="text" name="receiver_mobile" value="" /></td> 		
          <th><label for="query_start_time"><?php echo '收货地址*';?></label></th>
         <td>
          <div>
            <select name="address1" id="pro" onchange="getcity()">
				<option>请选择省</option>
				<?php foreach($output['address_info'] as $value){ ?>
				 <option name="address1" value="<?php  echo $value['area_id']; ?>"><?php  echo $value['area_name']; ?></option>
				<?php } ?>
            </select>
			<br />
			<select name="address2" id="city" onchange="getq()">
				<option>请选择市</option>
			</select>
			<br />
			<select name="address3" id="zone">
				<option>请选择区</option>
			</select>
          </div>
         </td>
			<th><label for="import_address"><?php echo '详细地址*';?></label></th>
			 <td><input class="txt-long" type="text" name="receiver_address" value="" /></td>	 
        </tr>
		<!--订单商品 暂时设置最多5个商品 -->
		<tr>
			<th><label for="import_number"><?php echo '序号';?></label></th>	
		    <th><label for="import_number"><?php echo '商品编码*';?></label></th>
			<th><label for="import_num"><?php echo '商品数量*'; ?></label></th>
			<th><label for="import_price"><?php echo '商品不含税单价';?></label></th>
			<th><label for="import_money"><?php echo '商品含税单价';?></label></th>
		</tr>
		<tr>
			<td><input class="txt-short" type="text" name="serial_number_1" disabled value="1"/></td>
			<td><input class="txt" type="text" name="goods_serials_1" value="" /></td>
			<td><input class="txt-short" type="text" name="goods_num_1" value="" /></td>
			<td><input class="txt" type="text" name="goods_price_1" value="" /></td> 			 
			<td><input class="txt" type="text" name="goods_intax_price_1" value="" /></td>
		</tr>
		<tr>
			<td><input class="txt-short" type="text" name="serial_number_2" disabled value="2"/></td>
			<td><input class="txt" type="text" name="goods_serials_2" value="" /></td>
			<td><input class="txt-short" type="text" name="goods_num_2" value="" /></td>
			<td><input class="txt" type="text" name="goods_price_2" value="" /></td> 			 
			<td><input class="txt" type="text" name="goods_intax_price_2" value="" /></td>
		</tr>
		<tr>
			<td><input class="txt-short" type="text" name="serial_number_3" disabled value="3"/></td>
			<td><input class="txt" type="text" name="goods_serials_3" value="" /></td>
			<td><input class="txt-short" type="text" name="goods_num_3" value="" /></td>
			<td><input class="txt" type="text" name="goods_price_3" value="" /></td> 			 
			<td><input class="txt" type="text" name="goods_intax_price_3" value="" /></td>
		</tr>
		<tr>
			<td><input class="txt-short" type="text" name="serial_number_4" disabled value="4"/></td>
			<td><input class="txt" type="text" name="goods_serials_4" value="" /></td>
			<td><input class="txt-short" type="text" name="goods_num_4" value="" /></td>
			<td><input class="txt" type="text" name="goods_price_4" value="" /></td> 			 
			<td><input class="txt" type="text" name="goods_intax_price_4" value="" /></td>
		</tr>
		<tr>
			<td><input class="txt-short" type="text" name="serial_number_5" disabled value="5"/></td>
			<td><input class="txt" type="text" name="goods_serials_5" value="" /></td>
			<td><input class="txt-short" type="text" name="goods_num_5" value="" /></td>
			<td><input class="txt" type="text" name="goods_price_5" value="" /></td> 			 
			<td><input class="txt" type="text" name="goods_intax_price_5" value="" /></td>
		</tr>
		<tr>
			<td colspan="5">
			备注：<br  />
			1、跨境保税商城商品填商品不含税价格，即三方平台零售价，如建行善融商城；<br />
			2、非跨境保税商城商品填含税价格，即三方平台零售价，如手商云，彩生活等；<br />
			3、商品[不含税价]与[含税价]2选1必填，如两个都填写了以[含税价]为准计算；<br />
			4、跨境保税商品订单金额不能超过2000元；<br />
			</td>
		</tr>
        <tr class="tfoot">
          <td colspan="2" ><a  class="btn" id="btn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tbody>
    </table>
  </form>
  <div style="text-align:right;"> 
       <form id="addform" action="index.php?<?php echo $_SERVER['QUERY_STRING'];?>&gp=export_step1" method="post" enctype="multipart/form-data">
        <br/><input type="file" name="file"> <input type="submit" class="btn" id="btn" value="导入Excel">
      </form>
  </div>
    <div style="margin-left:83%;"><a class="btns" target="_blank" href="index.php?<?php echo $_SERVER['QUERY_STRING'];?>&gp=export_excel"><span><?php echo '导出录入订单信息Excel模板';?></span></a></div>
  
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