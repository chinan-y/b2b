<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <div class="ncm-friend-find"> 
    <div class="ncm-recommend-tag">
      <?php if(!empty($output['inviter_list'])){?>
      <dl>
        <dt><i class="icon-tag"></i>我的感恩人</dt>
		 <div class="avatar">
		 <a href="index.php?gct=member_snshome&mid=<?php echo $output['inviter_list']['member_id'];?>" target="_blank" data-param="{'id':<?php echo $output['inviter_list']['member_id'];?>}" nctype="mcard"><img src="<?php if ($v['member_avatar']!='') { echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR.DS.$v['member_avatar']; } else { echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_user_portrait'); } ?>" alt="<?php echo $output['inviter_list']['member_name']; ?>"/>
		 </a>
		 </div>
		<dt>昵称：<?php echo $output['inviter_list']['member_name'];?></dt>
		<dt>姓名：<?php echo $output['inviter_list']['member_truename'];?></dt>
		<dt>手机：<?php echo $output['inviter_list']['member_mobile'];?></dt>
		<dt>邮箱：<?php echo $output['inviter_list']['member_email'];?></dt>
		<dd>
			<form method="post" id="delrelation_form" action="index.php?gct=member_snsfriend&gp=inviter">
			<div class="search-form">
				<div class="normal">
				 <input type="hidden" name="delrelation" id="delrelation"  value="delrelation">
				 <a class="ncm-btn ncm-btn-green" nctype="del_submit">解除关系</a>
				</div>
			</div>
			</form>
		</dd>
      </dl>
      <?php } else {?>

	  <form method="post" id="search_form" action="index.php?gct=member_snsfriend&gp=inviter">
      <div class="search-form">
        <div class="normal"> 请输入您感恩人的手机号码：
          <input type="text" class="text w200" name="inviter_mobile" id="inviter_mobile" maxlength="13" placeholder="11位手机号码" value="<?php echo $_POST['inviter_mobile'];?>">
          <a class="ncm-btn ncm-btn-green" nctype="search_submit">确认提交</a>
		</div>
      </div>
      </form>

      <?php }?>
		<div class="invite-rules">
		<ul>
		<li><em>您成功购物后，您的感恩人将获得<?php echo C('points_rebate');?>%</em>的返利积分<em></li>
		<li><em>积分兑换比例：消费<?php echo C('points_orderrate');?>单位货币</em>赠送1积分<em></li>
		<li>如订单发生退款、退货等问题时，积分将不予退还。</li>
		</ul>
        </div>

    </div>
  </div>
</div>


<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/sns_friend.js" charset="utf-8"></script> 
<script type="text/javascript">
$(function(){
	$('a[nctype="search_submit"]').click(function(){
		// 验证是否为空
		if($('#inviter_mobile').val() != ''){
		    $('#search_form').submit();
		}else{
			$('#inviter_mobile').addClass('error').focus();
		}
	});

	$('a[nctype="del_submit"]').click(function(){
		// 验证是否为空
		if($('#delrelation').val() != ''){
		    $('#delrelation_form').submit();
		}else{
			$('#delrelation').addClass('error').focus();
		}
	});
		
});
</script> 
