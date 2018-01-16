<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="ncc-main">
  <div class="ncc-title">
    <h3><?php echo $lang['cart_index_payment'];?></h3>
    <h5>订单详情内容可通过查看<a href="index.php?gct=member_order" target="_blank">我的订单</a>进行核对处理。</h5>
  </div>
      <div class="ncc-receipt-info">
      <?php if (!isset($output['payment_list'])) {?>
      <?php }else if (empty($output['payment_list'])){?>
      <div class="nopay"><?php echo $lang['cart_step2_paymentnull_1']; ?> <a href="index.php?gct=member_message&gp=sendmsg&member_id=<?php echo $output['order']['seller_id'];?>"><?php echo $lang['cart_step2_paymentnull_2'];?></a> <?php echo $lang['cart_step2_paymentnull_3'];?></div>
      <?php } else {?>
      <div class="ncc-receipt-info-title">
        <h3>支付选择<font color="red"></font></h3>
      </div>
      <ul class="ncc-payment-list">
        <?php foreach($output['payment_list'] as $val) { ?>
        <li payment_code="<?php echo $val['payment_code']; ?>"  <?php if($val['payment_code']=='gzbank' && !empty($output['bind'])){ echo 'bind="true"';} ?>>
          <label for="pay_<?php echo $val['payment_code']; ?>">
          <i></i>
          <div class="logo" for="pay_<?php echo $val['payment_id']; ?>"> <img src="<?php echo SHOP_TEMPLATES_URL?>/images/payment/<?php echo $val['payment_code']; ?>_logo.gif" /> </div>
          </label>
        </li>
        <?php } ?>
      </ul>
      <?php } ?>
    </div>
  <?php foreach($output['order_list'] as $ke=>$value){?> 
  <form action="index.php?gct=payment&gp=real_order" method="POST" id="buy_form<?php echo $value[0]['order_id'] ; ?>">
    <input type='hidden' name='cardNo' value=''>  
    <input type="hidden" name="pay_sn" value="<?php echo $value[0]['pay_sn'];?>">
    <input type="hidden" id="payment_code<?php echo $value[0]['order_id'] ; ?>" name="payment_code" value="">
    <div class="ncc-receipt-info">
      <div class="ncc-receipt-info-title">
        <h3><?php echo $output['order_remind'];?>
          <?php if ($output['pay_amount_online'] > 0) {?>
          在线支付金额：<strong>￥<?php echo $value[0]['pay_amount_online'];?></strong>
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
          <?php if (count($output['order_list'])>1) {?>
          <!--<tr>
            <th colspan="20">由于您的商品由不同商家发出，此单将分为<?php echo count($output['order_list']);?>个不同子订单配送！</th>
          </tr>-->
          <?php }?>
          <?php foreach ($value as $key => $order) {?>
          <tr>
            <td></td>
            <td class="tl"><?php echo $order['order_sn']; ?></td>
            <td class="tl"><?php echo $order['payment_state'];?></td>
            <td class="tl">￥<?php echo $order['order_amount'];?></td>
            <td>快递</td>
          </tr>
          <?php  }?>
        </tbody>
      </table>
    </div>

    <?php if ($output['pay_amount_online'] > 0) {?>
    <div class="ncc-bottom tc " ><a style='float:right; margin-right:20px;' href="javascript:void(0);" id="next_button<?php echo $value[0]['order_id'] ; ?>" class="ncc-btn ncc-btn-green"><i class="icon-shield"></i>确认提交支付</a></div>
    <?php }?>
    
	
  </form>
  <?php }?>
</div>

<div class="zhe"></div>
<!--贵州银行弹出框验证表单验证 -->
<div id="bind">
  <div class="tan_one shouhuore_tan">
    <div class="top"><i>借记卡/信用卡 </i><img src="<?php echo SHOP_TEMPLATES_URL ?>/images/close.jpg" class="close" /></div>
    <div class="province">
      <ul class="bank qie_one">
        <li><a href="#" class="current">借记卡</a></li>
        <li><a href="#">信用卡</a></li>
      </ul>
      <div class="bankTop qie_div">
        <form method="post" action="index.php?gct=payment&gp=bind" id="reg">
		<input type='hidden' name='bankCardType' value='10'>
          <p><span>借记卡名称：</span>
            <select  name='cardName' >
              <option selected="selected" value="1569">贵州银行</option>
              <option value="100">邮政储蓄</option>
              <option value="102">工商银行</option>
              <option value="103">农业银行</option>
              <option value="104">中国银行</option>
              <option value="105">建设银行</option>
              <option value="201">国家开发银行</option>
              <option value="301">交通银行</option>
              <option value="302">中信银行</option>
              <option value="303">光大银行</option>
              <option value="304">华夏银行</option>
              <option value="305">民生银行</option>
              <option value="306">广发银行</option>
              <option value="307">平安银行</option>
              <option value="308">招商银行</option>
              <option value="309">兴业银行</option>
              <option value="310">浦发银行</option>
              <option value="311">恒丰银行</option>
              <option value="313">华融湘江银行</option>
            </select>
          </p>
          <p><span>银行卡号：</span>
            <input type="text" name="bankCard" />
          </p>
          <p><span>证件类型：</span>
            <select name='codeType' >
              <option value="P00" selected="selected">1：身份证</option>
              <option value="P07">2：户口本</option>
              <option value="P05">3：护照</option>
              <option value="P01">4：军官证</option>
              <option value="P04">5：军人士兵证</option>
              <option value="P06">6：港澳居民往来内地通行证</option>
              <option value="P13">7：台湾居民来往大陆通行证</option>
              <option value="P10">8：临时身份证</option>
              <option value="P09">9：外国人永久居留证</option>
              <option value="P03">10：警官证</option>
              <option value="P99">11：其他证件</option>
              <option value="P17">12：武警士兵证</option>
              <option value="P28">13：军人文职干部证</option>
              <option value="P18">14：武警文职干部证</option>
              <option value="P21">15：驾驶证</option>
              <option value="P25">16：军人身份证</option>
              <option value="P25">17：武装警察身份证</option>
              <option value="P23">18：外国公民护照</option>
              <option value="P05">19：中国护照</option>
              <option value="P09">20：外国人居留证</option>
              <option value="P33">21：执行公务证</option>
              <option value="P34">22：学生证</option>
              <option value="P14">23：军官退休证</option>
              <option value="P15">24：文职干部退休证</option>
              <option value="P19">25：武警军官退休证</option>
              <option value="P20">26：武警文职干部退休证</option>
              <option value="P29">27：社会保障卡</option>
              <option value="P30">28：香港身份证</option>
              <option value="P31">29：澳门身份证</option>
              <option value="P32">30：台湾身份证</option>
            </select>
          </p>
          <p><span>证件号：</span>
            <input type="text" name="idCard" />
          </p>
          <p><span>中文姓名</span>
            <input type="text" name="chinaName" />
          </p>
          <p><span>手机：</span>
            <input type="text" name="mobile" />
          </p>
          <p>
            <input type="submit" id="send" value="提交" />
           
          </p>
        </form>
      </div>
      <div class="bankTop qie_div" style="display:none;">
        <form method="post" action="index.php?gct=payment&gp=bind" id="regb">
		<input type='hidden' name='bankCardType' value='20'>
          <p><span>信用卡名称：</span>
            <select  name='cardName' >
              <option selected="selected" value="1569">贵州银行</option>
              <option value="100">邮政储蓄</option>
              <option value="102">工商银行</option>
              <option value="103">农业银行</option>
              <option value="104">中国银行</option>
              <option value="105">建设银行</option>
              <option value="201">国家开发银行</option>
              <option value="301">交通银行</option>
              <option value="302">中信银行</option>
              <option value="303">光大银行</option>
              <option value="304">华夏银行</option>
              <option value="305">民生银行</option>
              <option value="306">广发银行</option>
              <option value="307">平安银行</option>
              <option value="308">招商银行</option>
              <option value="309">兴业银行</option>
              <option value="310">浦发银行</option>
              <option value="311">恒丰银行</option>
              <option value="313">华融湘江银行</option>
            </select>
          </p>
          <p><span>银行卡号：</span>
            <input type="text" name="bankCard" />
          </p>
          <p><span>证件类型：</span>
            <select name='codeType' >
              <option value="P00" selected="selected">1：身份证</option>
              <option value="P07">2：户口本</option>
              <option value="P05">3：护照</option>
              <option value="P01">4：军官证</option>
              <option value="P04">5：军人士兵证</option>
              <option value="P06">6：港澳居民往来内地通行证</option>
              <option value="P13">7：台湾居民来往大陆通行证</option>
              <option value="P10">8：临时身份证</option>
              <option value="P09">9：外国人永久居留证</option>
              <option value="P03">10：警官证</option>
              <option value="P99">11：其他证件</option>
              <option value="P17">12：武警士兵证</option>
              <option value="P28">13：军人文职干部证</option>
              <option value="P18">14：武警文职干部证</option>
              <option value="P21">15：驾驶证</option>
              <option value="P25">16：军人身份证</option>
              <option value="P25">17：武装警察身份证</option>
              <option value="P23">18：外国公民护照</option>
              <option value="P05">19：中国护照</option>
              <option value="P09">20：外国人居留证</option>
              <option value="P33">21：执行公务证</option>
              <option value="P34">22：学生证</option>
              <option value="P14">23：军官退休证</option>
              <option value="P15">24：文职干部退休证</option>
              <option value="P19">25：武警军官退休证</option>
              <option value="P20">26：武警文职干部退休证</option>
              <option value="P29">27：社会保障卡</option>
              <option value="P30">28：香港身份证</option>
              <option value="P31">29：澳门身份证</option>
              <option value="P32">30：台湾身份证</option>
            </select>
          </p>
          <p><span>证件号：</span>
            <input type="text" name="idCard" />
          </p>
		  <p><span>中文姓名</span>
            <input type="text" name="chinaName" />
          </p>
          <p><span>手机：</span>
            <input type="text" name="mobile" />
          </p>
		  <p><span>卡后三位数：</span>
            <input type="text" name="cnv2" />
          </p>
		  <p><span>有效时间：</span>
            <input type="date" name="validDate" />
          </p>
          <p>
            <input type="submit" id="send" value="提交" />
          </p>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL; ?>/js/gzpay.js" ></script>
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL; ?>/js/index.js" ></script>
<script type="text/javascript">
$(function(){
	<?php foreach($output['order_list'] as $ke=>$value){?> 
    $('.ncc-payment-list > li').on('click',function(){
    	$('.ncc-payment-list > li').removeClass('using');
        $(this).addClass('using');
        $('#payment_code<?php echo $value[0]['order_id'] ; ?>').val($(this).attr('payment_code'));
		if($(this).attr('payment_code')=='gzbank' && $(this).attr('bind') !='true' ){
			$(".zhe").show();
			center($(".shouhuore_tan"));
			$(".shouhuore_tan").show();
		}
    });
	
    $('#next_button<?php echo $value[0]['order_id'] ; ?>').on('click',function(){
		
        if ($('#payment_code<?php echo $value[0]['order_id'] ; ?>').val() == '') {
        	showDialog('请选择支付方式', 'error','','','','','','','','',2);return false;
        }
		if($('.using').attr('payment_code')=='gzbank' && $('.using').attr('bind') =='true'){
			var content='<table><tr><th class="addTh"></th><th class="addname" >姓名</th><th>发卡行</th><th>卡号</th><th style="border-right:none;">手机</th></tr>';
			<?php if($output['bind']){ ?>
			<?php foreach($output['bind'] as $kk=>$vv){ ?>
				content+='<tr><td class="addTh" ><input type="radio" name="chose" <?php if($kk==0){echo 'checked';}?> value="<?php echo $vv['cardNo'] ?>"></td><td  class="addname" ><?php echo $vv['chinaName'] ?></td><td><?php echo $vv['cardBankId']; ?></td><td><?php echo $vv['cardNo'];  ?></td><td style="border-right:none;"><?php echo $vv['mobile'];  ?></td></tr>';
			<?php } ?>
			<?php } ?>
			content+='</table>';
			sureMessage('请选择银行卡',content,function(){
				$('input[name="cardNo"]').val($('input[name="chose"]:checked').val());
				$('#buy_form<?php echo $value[0]['order_id'] ; ?>').submit();
			})
		}else{
			$('#buy_form<?php echo $value[0]['order_id'] ; ?>').submit();
		}
    });
	
	
	<?php } ?>
	$('body').on('click','#checkCode',function(){
		// var code=$(this).parent().find('input[name="code"]').val()
		// var txSNBinding=$(this).parent().find('input[name="txSNBinding"]').val()
		var formdata=new FormData($('#codeForm')[0]);
		$.ajax({
			url:'index.php?gct=payment&gp=bindTwo',
			data:formdata,
			type:'POST',
			dataType:'JSON',
			cache: false, 
			processData: false,  
            contentType: false,
			success:function(result){
				if(result.state==200){
					alert("绑定成功，你可以支付啦")
				}else{
					alert(result.message)
				}
				location.reload(true);
			}
			
        })
		return false;
	})
});
</script>