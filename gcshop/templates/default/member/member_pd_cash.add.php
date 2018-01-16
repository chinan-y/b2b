<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <div class="ncm-default-form">
    <form method="post" id="cash_form" action="index.php?gct=predeposit&gp=pd_cash_add">
      <input type="hidden" name="form_submit" value="ok" />
      <dl>
        <dt><i class="required">*</i>提现金额：</dt>
        <dd><input name="pdc_amount" type="text" class="text w80" id="pdc_amount" maxlength="10" ><em class="add-on">
			<i class="icon-renminbi"></i></em> （当前可用金额：<strong class="orange"><?php echo floatval($output['member_info']['available_predeposit']); ?></strong>&nbsp;&nbsp;元）<span></span>
			<p class="hint mt5"></p>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>支付宝账号：</dt>
        <dd><input name="alipay_no" type="text" class="text w200" id="alipay_no" /><span></span>
          <p class="hint">收款支付宝的账号</p>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>真实姓名：</dt>
        <dd><input name="alipay_user" type="text" class="text w200" id="alipay_user" /><span></span>
        <p class="hint">收款支付宝的账户名，<em style="color:red;">必须要实名认证的支付宝账户</em></p>
          </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>网站支付密码：</dt>
        <dd><input name="password" type="password" class="text w200" id="password" maxlength="20"/><span></span>
        <p class="hint">
              <?php if (!$output['member_info']['member_paypwd']) {?>
              <strong class="red">还未设置支付密码</strong><a href="<?php echo SHOP_SITE_URL;?>/index.php?gct=member_security&gp=auth&type=modify_paypwd" class="ncm-btn-mini ncm-btn-acidblue vm ml10" target="_blank">马上设置</a>
              <?php } ?>
        </p>
          </dd>
      </dl>
      <dl class="bottom"><dt>&nbsp;</dt>
          <dd><label class="submit-border"><input type="submit"  class="submit" value="确认提现" /></label><a class="ncm-btn ml10" href="javascript:history.go(-1);">取消并返回</a></dd>
      </dl>
    </form>
  </div>
</div>
<script type="text/javascript">
$(function(){
	$('#cash_form').validate({
    	submitHandler:function(form){
			ajaxpost('cash_form', '', '', 'onerror')
		},
         errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span');
            error_td.append(error);
        },
        rules : {
	        pdc_amount      : {
	        	required  : true,
	            number    : true,
	            min       : 0.01,
	            max       : <?php echo floatval($output['member_info']['available_predeposit']); ?>
            },
            alipay_no : {
            	required  : true
            },
            alipay_user : {
	        	required  : true
	        },
	        password : {
	        	required  : true
	        }
        },
        messages : {
        	pdc_amount	  : {
            	required  :'<i class="icon-exclamation-sign"></i>请正确输入提现金额',
            	number    :'<i class="icon-exclamation-sign"></i>请正确输入提现金额',
            	min    	  :'<i class="icon-exclamation-sign"></i>请正确输入提现金额',
            	max       :'<i class="icon-exclamation-sign"></i>请正确输入提现金额'
            },
            alipay_no : {
            	required   :'<i class="icon-exclamation-sign"></i>请输入支付宝账号'
            },
            alipay_user : {
	        	required  : '<i class="icon-exclamation-sign"></i>请输入支付宝账户名'
	        },
	        password : {
		        required : '<i class="icon-exclamation-sign"></i>请输入支付密码'
		    }
        }
    });
});
</script>