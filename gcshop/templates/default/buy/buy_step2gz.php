<?php defined('GcWebShop') or exit('Access Invalid!');?>
<style type="text/css">
 .ncc-receipt-input{text-align:right;} 
 .ncc-receipt-input .input1{margin-right:20px;height:24px;width:100px;} 
 .ncc-receipt-input .input2{float:right;height:30px;width:100px;border:1px solid #52A452;background:#5BB75B;color:#fff;font:normal 14px/20px arial,"microsoft yahei";} 
 .ncc-receipt-input .input2:hover{background:#51A351;cursor:pointer;}
</style>
<div class="ncc-main">
  <div class="ncc-title">
    <h3>贵州银行支付</h3>
    <h5>订单详情内容可通过查看<a href="index.php?gct=member_order" target="_blank">我的订单</a>进行核对处理。</h5>
  </div>
  <form action="index.php?gct=payment&gp=gz_pay" method="POST" id="buy_form">
    <input type="hidden" name="pay_sn" value="<?php echo $output['data']['pay_sn'];?>">
    <input type="hidden" name="cardNo" value="<?php echo $output['data']['cardNo'];?>">
    <input type="hidden" name="order_id" value="<?php echo $output['order_list'][0]['order_id'];?>">
    <div class="ncc-receipt-info">
      <div class="ncc-receipt-info-title">
        <h3>
            请您及时付款，以便订单尽快处理！                    
            在线支付金额：
            <?php if ($output['data']['price'] > 0) {?>
            <strong>￥<?php echo $output['data']['price'];?></strong>
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

    <div class="ncc-receipt-info ncc-receipt-input">
       <input class="input1" type='text' name='code' placeholder='请输入短信验证码' ><input class="input2" type='submit' value='确认支付'>
    </div>
  </form>
</div>

<script type="text/javascript">

</script>