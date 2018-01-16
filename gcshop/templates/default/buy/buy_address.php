<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="ncc-receipt-info">
  <div class="ncc-receipt-info-title">
    <h3>收货信息</h3>
    <a href="javascript:void(0)" nc_type="buy_edit" id="edit_reciver">[修改]</a></div>
  <div id="addr_list" class="ncc-candidate-items">
    <ul>
	  <input type="hidden" value="<?php echo $output['address_info']['true_idnum'];?>" class="true_idnum" id="true_idnum" />
      <li><span class="true-name"><?php echo $output['address_info']['true_name'];?></span><span class="phone"><i class="icon-mobile-phone"></i>
	  <?php echo $output['address_info']['mob_phone'] ? $output['address_info']['mob_phone'] : $output['address_info']['tel_phone'];?>
	  </span>
	  <span class="address">
	  <?php echo intval($output['address_info']['dlyp_id']) ? '[自提服务站] ' : '';?>
	  <?php echo $output['address_info']['area_info'],$output['address_info']['address'];?></span></li>
    </ul>
  </div>
</div>
<script type="text/javascript">
//隐藏收货地址列表
function hideAddrList(addr_id,true_name,true_idnum,address,phone) {
	$.ajax({
		url:"index.php?gct=buy&gp=person_date_buy_amount",
		data:{true_idnum:true_idnum},
		type:"post",
	})

    $('#edit_reciver').show();
	$("#address_id").val(addr_id);
	$("#addr_list").html('<ul><li><span class="true-name">'+true_name+'</span><span class="true_idnum">'+true_idnum+'</span><span class="address">'+address+'</span><span class="phone"><i class="icon-mobile-phone"></i>'+phone+'</span></li></ul>');
	$('.current_box').removeClass('current_box');
	ableOtherEdit();
	$('#edit_payment').click();
}
//加载收货地址列表
$('#edit_reciver').on('click',function(){
    $(this).hide();
    disableOtherEdit('如需修改，请先保存收货人信息 ');
    $(this).parent().parent().addClass('current_box');
    $('#addr_list').load(SITEURL+'/index.php?gct=buy&gp=load_addr');
});
//异步显示每个店铺运费 city_id计算运费area_id计算是否支持货到付款
function showShippingPrice(city_id,area_id) {
	$('#buy_city_id').val('');
    $.post(SITEURL + '/index.php?gct=buy&gp=change_addr', {'freight_hash':'<?php echo $output['freight_hash'];?>',city_id:city_id,'area_id':area_id}, function(data){
    	if(data.state == 'success') {
    	    $('#buy_city_id').val(city_id);
    	    $('#allow_offpay').val(data.allow_offpay);
            if (data.allow_offpay_batch) {
                var arr = new Array();
                $.each(data.allow_offpay_batch, function(k, v) {
                    arr.push('' + k + ':' + (v ? 1 : 0));
                });
                $('#allow_offpay_batch').val(arr.join(";"));
            }
    	    $('#offpay_hash').val(data.offpay_hash);
    	    $('#offpay_hash_batch').val(data.offpay_hash_batch);
    	    var content = data.content;
    	    var amount = 0;
            for(var i in content){
            	$('#eachStoreFreight_'+i).html(number_format(content[i],2));
            	amount = amount + parseFloat(content[i]);
            }
            calcOrder();
    	}

    },'json');
}
$(function(){
    <?php if (!empty($output['address_info']['address_id'])) {?>
    showShippingPrice(<?php echo $output['address_info']['city_id'];?>,<?php echo $output['address_info']['area_id'];?>);
    <?php } else {?>
    $('#edit_reciver').click();
    <?php }?>
});
</script>