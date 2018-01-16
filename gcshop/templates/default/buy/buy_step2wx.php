<?php defined('GcWebShop') or exit('Access Invalid!');?>
<style type="text/css">
 .ncc-receipt-info{

 } 
</style>
<div class="ncc-main">
  <div class="ncc-title">
    <h3>微信支付</h3>
    <h5>订单详情内容可通过查看<a href="index.php?gct=member_order" target="_blank">我的订单</a>进行核对处理。</h5>
  </div>
  <form action="" method="POST" id="buy_form">
    <input type="hidden" name="pay_sn" id="pay_sn"  value="<?php echo $output['pay_sn'];?>">
    <input type="hidden" name="buyer_id" id="buyer_id" value="<?php echo $output['buyer_id'];?>">
    <input type="hidden" name="price" id="price" value="<?php echo $output['price'];?>">
    <div class="ncc-receipt-info">
      <div class="ncc-receipt-info-title">
        <h3>
            请您及时付款，以便订单尽快处理！                    
            在线支付金额：
            <?php if ($output['price'] > 0) {?>
            <strong>￥<?php echo $output['price'];?></strong>
            <?php } ?>
        </h3>
      </div>
      <table class="ncc-table-style">
        <thead>
          <tr>
            <th class="w50"></th>
            <th class="w200 tl">订单号</th>
            <th class="tl w150">支付方式</th>
            <th class="tl">金额</th>
            <th class="w150">物流</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($output['order_list'] as $key => $order) {?>
            <tr>
              <td></td>
              <td class="tl"><?php echo $order['order_sn']; ?></td>
              <td class="tl">
                  <?php if ($order['payment_code'] == 'online') {?>
                        在线支付
                  <?php }else{?>
                        货到付款
                  <?php } ?>
              </td>
              <td class="tl">￥<?php echo $order['order_amount'];?></td>
              <td>快递</td>
            </tr>
          <?php  }?>
        </tbody>
      </table>
    </div>

    <div class="ncc-receipt-info" style="text-align:center;">
      <img alt="扫码支付" src="<?php echo SHOP_SITE_URL;?>/api/payment/wxpay/qrcode.php?data=<?php echo urlencode($output['url']);?>" style="width:200px;height:200px;"/> 
    </div>
  </form>
</div>

<script type="text/javascript">
/**
 * [微信二维码请求支付状态JS]
 * @author fulijun
 * @dateTime 2016-07-25T19:06:35+0800
 * @param    {[type]}                 ){                    var pay_sn [description]
 * @return   {[type]}                     [description]
 */
$(function(){
   //取得支付单号，订单价格和买家ID
    var pay_sn = $("#pay_sn").val();
    var buyer_id = $("#buyer_id").val();
    var price = $("#price").val();
    var t = 0;
    function update_native_state(){
      t++; 
      //三分钟后5秒请求一次
      if(t > 180){
        if(t % 5 != 0){return false;}
      }
      if(t > 600){
        window.clearInterval(se);
        return false;
      }

      //检测是否付款
      $.ajax({
        url:"index.php?gct=buy&gp=check_pay",
        data:{buyer_id:buyer_id,pay_sn:pay_sn},
        type:'post',
        async:false,
        success:function(data){ 
          if (data == '1') {
              clearInterval(se);
              window.location.href="index.php?gct=buy&gp=pay_ok&pay_sn="+pay_sn+"&pay_amount="+price;
              //$('#fcode_showmsg').append('<i class="icon-ok-circle"></i>'+$('#fcode').val()+'为有效的F码，您可以继续完成下单购买。');
          } else {
              //showDialog('还没有付款', 'error','','','','','','','','',2);
              
          }
        }
      })    
    }
    se = window.setInterval(update_native_state,3000);

    //失去焦点，停止输出
    window.onblur = function(){
      clearInterval(se);
    }

    //得到焦点
    window.onfocus = function(){
      se = setInterval(update_native_state,3000);
    }
});
</script>