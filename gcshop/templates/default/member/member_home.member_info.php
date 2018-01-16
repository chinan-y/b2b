<?php defined('GcWebShop') or exit('Access Invalid!');?>

  <div id="account" class="double">
    <div class="outline">
      <div class="user-account">
        <ul>
          <li id="pre-deposit"><a href="index.php?gct=predeposit&gp=pd_log_list" title="<?php echo $lang['nc_member_path_look_predepositnum']?>">
            <h5><?php echo $lang['nc_predepositnum'];?></h5>
            <span class="icon"></span> <span class="value">￥<em><?php echo $output['member_info']['available_predeposit'];?></em><?php echo $lang['nc_yuan'];?></span></a> </li>
          <li id="voucher"><a href="index.php?gct=member_voucher&gp=index" title="<?php echo $lang['nc_member_path_look_voucher']?>">
            <h5><?php echo $lang['nc_voucher'];?></h5>
            <span class="icon"></span> <span class="value"><em><?php echo $output['home_member_info']['voucher_count']?$output['home_member_info']['voucher_count']:0;?></em><?php echo $lang['nc_zhang'];?></span></a> </li>
          <li id="points"><a href="index.php?gct=member_points&gp=index" title="<?php echo $lang['nc_member_path_look_points']?>">
            <h5><?php echo $lang['nc_pointsnum'];?></h5>
            <span class="icon"></span> <span class="value"><em><?php echo $output['member_info']['member_points'];?></em><?php echo $lang['nc_fen'];?></span></a> </li>
        </ul>
      </div>
    </div>
  </div>
  <div id="security" class="normal">
    <div class="outline">
      <div class="SAM">
        <h5><?php echo $lang['nc_member_security'];?></h5>
        <?php if ($output['home_member_info']['security_level'] <= 1) { ?>
        <div id="low" class="SAM-info"><strong><?php echo $lang['nc_member_low'];?></strong><span><em></em></span>
        <?php } elseif ($output['home_member_info']['security_level'] == 2) {?>
        <div id="normal" class="SAM-info"><strong><?php echo $lang['nc_member_middle'];?></strong><span><em></em></span>
        <?php }else {?>
        <div id="high" class="SAM-info"><strong><?php echo $lang['nc_member_high'];?></strong><span><em></em></span>
        <?php } ?>
        <?php if ($output['home_member_info']['security_level'] < 3) {?>
        <a href="<?php echo urlShop('member_security','index');?>" title="<?php echo $lang['nc_member_setting'];?>"><?php echo $lang['nc_member_up'];?>></a>
        <?php } ?>
        </div>
        <div class="SAM-handle"><span><i class="mobile"></i><?php echo $lang['nc_member_mobile'];?>：
        <?php if ($output['home_member_info']['member_mobile_bind'] == 1) {?>
        <em><?php echo $lang['nc_member_binded'];?></em>
        <?php  } else {?>
        <a href="<?php echo urlShop('member_security','auth',array('type'=>'modify_mobile'));?>" title="<?php echo $lang['nc_member_bind_mobile'];?>"><?php echo $lang['nc_member_unbind'];?></a>
        <?php }?></span>
        <span><i class="mail"></i><?php echo $lang['nc_member_email'];?>：
        <?php if ($output['home_member_info']['member_email_bind'] == 1) {?>
        <em><?php echo $lang['nc_member_binded'];?></em>
        <?php  } else {?>
        <a href="<?php echo urlShop('member_security','auth',array('type'=>'modify_email'));?>" title="<?php echo $lang['nc_member_bind_email'];?>"><?php echo $lang['nc_member_unbind'];?></a>
        <?php }?></span>
        </div>
      </div>
    </div>
  </div>