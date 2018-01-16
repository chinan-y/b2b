<?php defined('GcWebShop') or exit('Access Invalid!');?>

<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script>
<form method="post" id="order_form" name="order_form" action="index.php">
<input type="hidden" value="<?php echo $output['buy_encrypt'];?>" id="buy_encrypt" name="buy_encrypt">
<?php include template('buy/buy_fcode');?>
<div class="ncc-main">
  <div class="ncc-title">
	<p class="ncc-p"><a target="_blank" href="http://gss.mof.gov.cn/zhengwuxinxi/zhengcefabu/201603/t20160324_1922968.html
"><?php echo $lang['cart_index_import_duty_notice'];?></a><?php echo $lang['cart_index_customs_notice'];?></p>
  </div>
    <?php include template('buy/buy_address');?>
    <?php include template('buy/buy_payment');?>
    <?php //include template('buy/buy_invoice');?>
    <?php include template('buy/buy_goods_list');?>
    <?php include template('buy/buy_amount');?>
    <input value="buy" type="hidden" name="gct">
    <input value="buy_step2" type="hidden" name="gp">
    <!-- 来源于购物车标志 -->
    <input value="<?php echo $output['ifcart'];?>" type="hidden" name="ifcart">
	
	<!-- 积分抵扣第一个订单的店铺ID和积分数额 -->
	<input value="<?php echo $output['points_store_id'];?>" type="hidden" name="points_store_id">
	<input value="" type="hidden" id="points" name="member_points">

    <!-- offline/online -->
    <input value="online" name="pay_name" id="pay_name" type="hidden">

    <!-- 是否保存增值税发票判断标志 -->
    <input value="<?php echo $output['vat_hash'];?>" name="vat_hash" type="hidden">

    <!-- 收货地址ID -->
    <input value="<?php echo $output['address_info']['address_id'];?>" name="address_id" id="address_id" type="hidden">

    <!-- 城市ID(运费) -->
    <input value="" name="buy_city_id" id="buy_city_id" type="hidden">

    <!-- 记录所选地区是否支持货到付款 第一个前端JS判断 第二个后端PHP判断 -->
    <input value="" id="allow_offpay" name="allow_offpay" type="hidden">
    <input value="" id="allow_offpay_batch" name="allow_offpay_batch" type="hidden">
    <input value="" id="offpay_hash" name="offpay_hash" type="hidden">
    <input value="" id="offpay_hash_batch" name="offpay_hash_batch" type="hidden">
	<input value="<?php echo $output['store_id'];?>" id="store_id" name="store_id" type="hidden">

    <!-- 默认使用的发票 -->
    <input value="<?php echo $output['inv_info']['inv_id'];?>" name="invoice_id" id="invoice_id" type="hidden">
    <input value="<?php echo getReferer();?>" name="ref_url" type="hidden">
	
</div>
</form>
<script type="text/javascript">
var SUBMIT_FORM = true;
//计算总运费和每个店铺小计
function calcOrder() {
    var allTotal = 0;
    $('em[nc_type="eachStoreTotal"]').each(function(){
        store_id = $(this).attr('store_id');
        points_store_id = $(this).attr('points_store_id');
        var eachTotal = 0;
        if ($('#eachStoreFreight_'+store_id).length > 0) {
        	eachTotal += parseFloat($('#eachStoreFreight_'+store_id).html());
	    }
        if ($('#eachStoreGoodsTotal_'+store_id).length > 0) {
        	eachTotal += parseFloat($('#eachStoreGoodsTotal_'+store_id).html());
	    }
        if ($('#eachStoreManSong_'+store_id).length > 0) {
        	eachTotal += parseFloat($('#eachStoreManSong_'+store_id).html());
	    }
        if ($('#eachStoreVoucher_'+store_id).length > 0) {
        	eachTotal += parseFloat($('#eachStoreVoucher_'+store_id).html());
        }
		if ($('input[name="points_pay"]').attr('checked')) {
			if(points_store_id == store_id){
				var goods_total = parseFloat($('#eachStoreGoodsTotal_'+store_id).html());
				var voucher_total = parseFloat($('#eachStoreVoucher_'+store_id).html());
				var points_pay = parseFloat($('#points_pay_'+points_store_id).html());
				if(voucher_total && points_pay-voucher_total+1 > goods_total){
					showDialog('积分抵扣加代金券抵扣的金额不能大于第一个订单的商品合计，最低需支付一元钱', 'error','','','','','','','','',6);
					$('input[name="points_pay"]').attr('checked',false);
					$('#points_note').hide();
				}else if(points_pay+1 > goods_total){
					showDialog('积分抵扣金额不能大于第一个订单的商品合计，最低需支付一元钱', 'error','','','','','','','','',5);
					$('input[name="points_pay"]').attr('checked',false);
					$('#points_note').hide();
				}else{
					eachTotal += parseFloat('-'+$('#points_pay_'+points_store_id).html());
					$('#points').val(<?php echo $output['member_points'];?>);
				}
			}
			$('input[name="pd_pay"]').attr('checked',false).attr('disabled',true);
        }else{
			$('#points').val('');
			$('input[name="pd_pay"]').attr('disabled',false);
		}
		if ($('#eachGoodsTaxes_'+store_id).length > 0) {
			
        	eachTotal += parseFloat($('#eachGoodsTaxes_'+store_id).html());
        }
        $(this).html(eachTotal.toFixed(2));
        allTotal += eachTotal;
    });
    // $('#orderTotal').html(number_format(allTotal,2));
	//站内余额小于订单支付金额就隐藏
	var available = parseFloat($('#available').html());
	if(available < number_format(allTotal,2)){
		$('#pd_panel').hide();
	}
    $('#orderTotal').html(allTotal.toFixed(2));           //解决有时候结算显示的和结算后的钱差1分钱的问题

}
$(function(){
    $.ajaxSetup({
        async : false
    });
    $('select[nctype="voucher"]').on('change',function(){
        if ($(this).val() == '') {
        	$('eachStoreVoucher_'+items[1]).html('-0.00');
        } else {
            var items = $(this).val().split('|');
            $('#eachStoreVoucher_'+items[1]).html('-'+number_format(items[2],2));
        }
        calcOrder();
    });
	
	$('input[name="points_pay"]').on('change',function(){
		if ($('input[name="points_pay"]').attr('checked')) {
			points_store_id = $(this).attr('points_store_id');
			$('#points_pay_'+points_store_id).html(number_format($('#member_points').html()/<?php echo C('exchange_rate'); ?>,2));
			$('#points_note').show();
		}else{
			$('#points_note').hide();
		}
		calcOrder();
	});

    <?php if (!empty($output['available_pd_amount']) || !empty($output['available_rcb_amount'])) { ?>
    function showPaySubmit() {
        if ($('input[name="pd_pay"]').attr('checked') || $('input[name="rcb_pay"]').attr('checked')) {
        	$('#pay-password').val('');
        	$('#password_callback').val('');
        	$('#pd_password').show();
			$('input[name="points_pay"]').attr('checked',false).attr('disabled',true);
        } else {
        	$('#pd_password').hide();
			$('input[name="points_pay"]').attr('disabled',false);
        }
    }

    $('#pd_pay_submit').on('click',function(){
        if ($('#pay-password').val() == '') {
        	showDialog('请输入支付密码', 'error','','','','','','','','',2);return false;
        }
        $('#password_callback').val('');
		$.get("index.php?gct=buy&gp=check_pd_pwd", {'password':$('#pay-password').val()}, function(data){
            if (data == '1') {
            	$('#password_callback').val('1');
            	$('#pd_password').hide();
            } else {
            	$('#pay-password').val('');
            	showDialog('支付密码码错误', 'error','','','','','','','','',2);
            }
        });
    });
    <?php } ?>

    <?php if (!empty($output['available_rcb_amount'])) { ?>
    $('input[name="rcb_pay"]').on('change',function(){
    	showPaySubmit();
    	if ($(this).attr('checked') && !$('input[name="pd_pay"]').attr('checked')) {
        	if (<?php echo $output['available_rcb_amount']?> >= parseFloat($('#orderTotal').html())) {
            	$('input[name="pd_pay"]').attr('checked',false).attr('disabled',true);
        	}
    	} else {
    		$('input[name="pd_pay"]').attr('disabled',false);
    	}
    });
    <?php } ?>

    <?php if (!empty($output['available_pd_amount'])) { ?>
    $('input[name="pd_pay"]').on('change',function(){
    	showPaySubmit();
    	if ($(this).attr('checked') && !$('input[name="rcb_pay"]').attr('checked')) {
        	if (<?php echo $output['available_pd_amount']?> >= parseFloat($('#orderTotal').html())) {
            	$('input[name="rcb_pay"]').attr('checked',false).attr('disabled',true);
        	}
    	} else {
    		$('input[name="rcb_pay"]').attr('disabled',false);
    	}    	
    });
    <?php } ?>

});
function disableOtherEdit(showText){
	$('a[nc_type="buy_edit"]').each(function(){
	    if ($(this).css('display') != 'none'){
			$(this).after('<font color="#B0B0B0">' + showText + '</font>');
		    $(this).hide();
	    }
	});
	disableSubmitOrder();
}
function ableOtherEdit(){
	$('a[nc_type="buy_edit"]').show().next('font').remove();
	ableSubmitOrder();

}
function ableSubmitOrder(){
	$('#submitOrder').on('click',function(){check_amount()}).css('cursor','').addClass('ncc-btn-acidblue');
}
function disableSubmitOrder(){
	$('#submitOrder').unbind('click').css('cursor','not-allowed').removeClass('ncc-btn-acidblue');
}
</script> 
