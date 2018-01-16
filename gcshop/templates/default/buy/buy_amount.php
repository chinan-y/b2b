<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="ncc-bottom"> <a href="javascript:void(0)" id='submitOrder' class="ncc-btn ncc-btn-acidblue fr"><?php echo $lang['cart_index_submit_order'];?></a></div>
 
 
<script>
function submitNext(){
	
	if (!SUBMIT_FORM) return;

	if ($('input[name="cart_id[]"]').size() == 0) {
		showDialog('所购商品无效', 'error','','','','','','','','',2);
		return;
	}
    if ($('#address_id').val() == ''){
		showDialog('<?php echo $lang['cart_step1_please_set_address'];?>', 'error','','','','','','','','',2);
		return;
	}
	if ($('#buy_city_id').val() == '') {
		showDialog('正在计算运费,请稍后', 'error','','','','','','','','',2);
		return;
	}
	if (($('input[name="pd_pay"]').attr('checked') || $('input[name="rcb_pay"]').attr('checked')) && $('#password_callback').val() != '1') {
		showDialog('使用充值卡/预存款支付，需输入支付密码并使用  ', 'error','','','','','','','','',2);
		return;
	}
	if ($('input[name="fcode"]').size() == 1 && $('#fcode_callback').val() != '1') {
		showDialog('请输入并使用F码', 'error','','','','','','','','',2);
		return;
	}
	
	SUBMIT_FORM = false;

	$('#order_form').submit();
}
$(function(){
    $(document).keydown(function(e) {
        if (e.keyCode == 13) {
        	submitNext();
        	return false;
        }
    });
	$('#submitOrder').on('click',check_amount);
});

var is_click = false;
function is_click_judge(){
	is_click = true;
}
	
function check_amount(){
	
	if (is_click == true){
		// 改了地址
		var true_idnum = $('.true_idnum').html();
	}else{
		// 没改地址
		var true_idnum = $('input[id="true_idnum"]').val();
	}
	
	var check_amount = true;
	var surplus_price= 0;
	var allTotal = $('#orderTotal').html();				// 订单总金额
	var goods_num = $('input[id="goods_num1"]').val();  // 商品购买数量
	var goods_num2 = $('input[id="goods_num2"]').val(); // 商品数量
	var goods_price = $('input[id="goods_price"]').val();//商品单价
	
	//分别计算么个店铺的合计，保税进口和直购进口的商品不能超过2000一单的限额
	$('em[nc_type="eachStoreTotal"]').each(function(){
		store_id = $(this).attr('store_id');
        store_from = $(this).attr('store_from');
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
		if ($('#eachGoodsTaxes_'+store_id).length > 0) {
        	eachTotal += parseFloat($('#eachGoodsTaxes_'+store_id).html());
        }
		if(eachTotal > 2000 && (store_from ==1 || store_from ==2) ){
			check_amount = false;
			showDialog('<?php echo '保税商品单次交易限额不能超过2000元，您的订单超过此限制，请拆分后下单！';?>', 'error','','','','','','','','',10);
			return;
		}
		
		$.ajax({
			url:"index.php?gct=buy&gp=identity_info",
			data:{},
			type:"post",
			success:function (result){
				var rData = $.parseJSON(result);
				if((rData.member_truename == null || rData.member_code == null) && (store_from ==1 || store_from ==2 || store_from ==3 || store_from ==8)){
					check_amount = false;
					showDialog('<?php echo '请完善您的身份信息';?>', 'error','',function(){ window.location = 'index.php?gct=member&gp=add_identity'},'','','','','','',2);
					return;
				}
			}
		})
	});
	
	
	/*$.ajax({
		 url:"index.php?gct=buy&gp=person_date_buy_amount",
		 data:{true_idnum:true_idnum},
		 type:"post",
		 success:function (result){
			var rData = $.parseJSON(result);
			if(rData.is_toiletry){
				alert(rData.date_amount);
			}else{
				// if(allTotal > 2000){
					// check_amount = false;
					// showDialog('<?php echo '单次交易限额不能超过2000元，您的订单超过此限制，请拆分后下单！';?>', 'error','','','','','','','','',10);
					// return;
				// }
					//当天没买过商品，但是购买的商品总金额大于2000了
				//if(goods_num2 > 1){ 
					// if(allTotal > 2000){
						// check_amount = false;
						// showDialog('<?php echo $lang['cart_step1_quota_2000_1'];?>', 'error','','','','','','','','',10);
						// return;
					// }
				}else{
					if(allTotal > 2000 ){
						//超过2000的商品(商品数量为1件) 购买数量超过 1件
						if(goods_price > 2000 && goods_num > 1){
							check_amount = false;
							showDialog('<?php echo $lang['cart_step1_quota_2000_2'];?>', 'error','','','','','','','','',10);
							return;
						}
						//没超过2000的商品(商品数量为1件) 购买数量超过 1件
						if(goods_price < 2000 && goods_num > 1){
							check_amount = false;
							showDialog('<?php echo $lang['cart_step1_quota_2000_1'];?>', 'error','','','','','','','','',10);
							return;
						}
					}
				}
					//当天购买过的商品总金额已近超过2000
				if(rData.date_amount > 2000 ){
					check_amount = false;
					showDialog('<?php echo $lang['cart_step1_quota_2000_3'];?>', 'error','','','','','','','','',10);
					return;
				}else {
					//当天已经买过了商品 当前购买的商品金额超过了应该还能购买的金额
					surplus_price = 2000 - rData.date_amount;
					if(surplus_price < 2000 && allTotal > surplus_price){
						check_amount = false;
						showDialog('<?php echo $lang['cart_step1_quota_2000_4'];?>', 'error','','','','','','','','',10);
						return;
					}else{
						check_amount = true;
					}
				}
			}
		 },
		 async:false
	  })*/
	if(check_amount == true){
		submitNext();
		calcOrder();
		return true;
	}else{
		return false;
	}
}
</script>
