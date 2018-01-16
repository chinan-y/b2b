<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <div class="ncm-user-profile">
    <div class="user-avatar"><span><img src="<?php echo getMemberAvatar($output['member_info']['member_avatar']);?>"></span></div>
    <div class="ncm-default-form fr">
      <form method="post" id="profile_form" action="index.php?gct=member_applymyqcode&gp=member">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="old_member_avatar" value="<?php echo $output['member_info']['member_avatar']; ?>" />
        <dl>
          <dt>ID：</dt>
          <dd>
              <span class="w400"><?php echo $output['member_info']['member_id']; ?>　　
			  <?php if($output['member_info']['is_seller']==1){echo "<font color=red>已开通</font>";} else {echo "<font color=red>未开通</font>";} ?>
			  </span>
         </dd>
        </dl>
		<dl>
          <dt><?php echo $lang['home_member_username'].$lang['nc_colon'];?></dt>
          <dd>
              <span class="w400"><?php echo $output['member_info']['member_name']; ?>&nbsp;&nbsp;
              <?php if ($output['member_info']['level_name']){ ?>
              <div class="nc-grade-mini" style="cursor:pointer;" onclick="javascript:go('<?php echo urlShop('pointgrade','index');?>');"><?php echo $output['member_info']['level_name'];?></div>
              <?php } ?>
              </span>

         </dd>
        </dl>

		 <dl>
          <dt><?php echo $lang['home_member_mobile'].$lang['nc_colon'];?></dt>
          <dd><span class="w400">
		    <input type="text" class="text" maxlength="20" name="member_mobile" value="<?php echo $output['member_info']['member_mobile']; ?>" />
            <?php if ($output['member_info']['member_mobile_bind'] == '1') { ?>
            <a href="index.php?gct=member_security&gp=auth&type=modify_mobile">更换手机</a>
            <?php } else { ?>
            <a href="index.php?gct=member_security&gp=auth&type=modify_mobile">绑定手机</a>
            <?php } ?></span><span>

            </span>
          </dd>
        </dl>

        <dl>
          <dt><?php echo $lang['home_member_email'].$lang['nc_colon'];?></dt>
          <dd><span class="w400">
		    <input type="text" class="text" maxlength="20" name="member_email" value="<?php echo $output['member_info']['member_email']; ?>" />
            <?php if ($output['member_info']['member_email_bind'] == '1') { ?>
            <a href="index.php?gct=member_security&gp=auth&type=modify_email">更换邮箱</a>
            <?php } else { ?>
            <a href="index.php?gct=member_security&gp=auth&type=modify_email">绑定邮箱</a>
            <?php } ?></span><span>

            </span>
          </dd>
        </dl>
        <dl>
          <dt><?php echo $lang['home_member_truename'].$lang['nc_colon'];?></dt>
          <dd><span class="w400">
            <input type="text" class="text" maxlength="20" name="member_truename" value="<?php echo $output['member_info']['member_truename']; ?>" />
            </span><span>
            </span></dd>
        </dl>
		<dl>
          <dt>身份证号码<?php echo $lang['nc_colon'];?></dt>
          <dd><span class="w400">
            <input type="text" class="text" maxlength="18" name="member_code" id="member_code" value="<?php echo $output['member_info']['member_code']; ?>" />
            </span><span>
            </span></dd>	
        </dl>
        <dl>
          <dt><?php echo $lang['home_member_areainfo'].$lang['nc_colon'];?></dt>
          <dd><span id="region" class="w400">
            <input type="hidden" value="<?php echo $output['member_info']['member_provinceid'];?>" name="province_id" id="province_id">
            <input type="hidden" value="<?php echo $output['member_info']['member_cityid'];?>" name="city_id" id="city_id">
            <input type="hidden" value="<?php echo $output['member_info']['member_areaid'];?>" name="area_id" id="area_id" class="area_ids" />
            <input type="hidden" value="<?php echo $output['member_info']['member_areainfo'];?>" name="area_info" id="area_info" class="area_names" />
            <?php if(!empty($output['member_info']['member_areaid'])){?>
            <span><?php echo $output['member_info']['member_areainfo'];?></span>
            <input type="button" value="<?php echo $lang['nc_edit'];?>" style="background-color: #F5F5F5; width: 60px; height: 32px; border: solid 1px #E7E7E7; cursor: pointer" class="edit_region" />
            <select style="display:none;">
            </select>
            <?php }else{?>
            <select>
            </select>
            <?php }?>
            </span><span>

            </span></dd>
        </dl>
        <dl>
          <dt>银行户名：</dt>
          <dd><span class="w400">
            <input name="member_accountname" type="text" class="text" maxlength="30"  id="member_accountname" value="<?php echo $output['member_info']['member_accountname']; ?>" />
            </span>
			</dd>
        </dl>
        <dl>
          <dt>银行账号：</dt>
          <dd><span class="w400">
            <input name="member_account" type="text" class="text" maxlength="50" id="member_account" value="<?php echo $output['member_info']['member_account'];?>" />
            </span><span>
            </span></dd>
        </dl>
		<dl>
          <dt>开户银行名称：</dt>
          <dd><span class="w400">
            <input name="member_bankname" type="text" class="text" maxlength="100" id="member_bankname" value="<?php echo $output['member_info']['member_bankname'];?>" />
            </span><span>
            </span></dd>
        </dl>
        <dl>
          <dt></dt>
          <dd>
           <span>
              说明：申请开通专属二维码，请等待审核开通，一旦开通，表示您同意遵守光彩全球的任何规定及要求。<br />
			  专属二维码开通需签订合同协议，并交纳保证金，请及时与光彩全球联系！
            </span>
          </dd>
        </dl>
		<dl class="bottom">
          <dt></dt>
          <dd>
            <label class="submit-border">
              <input type="submit" class="submit" value="申请开通专属二维码，开启创业之路" />
            </label>
          </dd>
        </dl>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript">
//注册表单验证
$(function(){
	regionInit("region");
	$('#birthday').datepicker({dateFormat: 'yy-mm-dd'});
    $('#profile_form').validate({
    	submitHandler:function(form){
    		if ($('select[class="valid"]').eq(0).val()>0) $('#province_id').val($('select[class="valid"]').eq(0).val());
			if ($('select[class="valid"]').eq(1).val()>0) $('#city_id').val($('select[class="valid"]').eq(1).val());
			ajaxpost('profile_form', '', '', 'onerror')
		},
        rules : {
            member_truename : {
				minlength : 2,
                maxlength : 20
            },
            member_qq : {
				digits  : true,
                minlength : 5,
                maxlength : 12
            }
        },
        messages : {
            member_truename : {
				minlength : '<?php echo $lang['home_member_username_range'];?>',
                maxlength : '<?php echo $lang['home_member_username_range'];?>'
            },
            member_qq  : {
				digits    : '<?php echo $lang['home_member_input_qq'];?>',
                minlength : '<?php echo $lang['home_member_input_qq'];?>',
                maxlength : '<?php echo $lang['home_member_input_qq'];?>'
            }
        }
    });
});
</script> 
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>