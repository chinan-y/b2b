<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>下级用户信息</h3>
	    <ul class="tab-base">
		<?php if(C('one_rank_rebate') == 1) { ?>
		<li><a href="index.php?gct=erwei_member&gp=yonghu&member_id=<?php echo $_GET['member_id'];?>&usertype=A" ><span>A级用户</span></a></li>
		<?php } ?>
		<?php if(C('two_rank_rebate') == 1) { ?>
        <li><a href="index.php?gct=erwei_member&gp=yonghu&member_id=<?php echo $_GET['member_id'];?>&usertype=B" ><span>B级用户</a></li>
		<?php }?>
		<?php if(C('three_rank_rebate') == 1) { ?>
	    <li><a href="index.php?gct=erwei_member&gp=yonghu&member_id=<?php echo $_GET['member_id'];?>&usertype=C"><span>C级用户</span></a></li>
		<?php }?>
	    <li><a href="index.php?gct=erwei_member&gp=member"><span>返回</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
 
  <table class="table tb-type2" id="prompt">
    <tbody>
	  <tr>
        <th colspan="12"><div>
            <span></span></div>
			<strong>会员：<?php echo $output['master_member']['member_name']." [ID:".$_GET['member_id']."]";?>；查询用户类型：<?php echo $_GET['usertype'];?>级用户</strong></th>
      </tr>
      <tr class="space odd">
        <th colspan="12"><div class="title">
            <h5><?php echo $lang['nc_prompts'];?></h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li><?php echo $lang['member_index_help1'];?></li>
            <li><?php echo $lang['member_index_help2'];?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <form method="post" id="form_member">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>ID</th>
          <th colspan="2"><?php echo $lang['member_index_name']?></th>
		  <th class="align-center"><span fieldname="logins" nc_type="order_by"><?php echo $lang['member_index_login_time']?></span></th>
          <th class="align-center"><span fieldname="last_login" nc_type="order_by"><?php echo $lang['member_index_last_login']?></span></th>
          <th class="align-center"><?php echo $lang['member_index_points']; ?></th>
          <th class="align-center"><?php echo $lang['member_index_prestore'];?></th>
          <th class="align-center">经验值</th>
          <th class="align-center">级别</th>
          <th class="align-center"><?php echo $lang['member_index_login']; ?></th>
          <th class="align-center"><?php echo $lang['nc_handle']; ?></th>
        </tr>
      <tbody>
        <?php if(!empty($output['member_list']) && is_array($output['member_list'])){ ?>
        <?php foreach($output['member_list'] as $k => $v){ ?>
        <tr class="hover member">
          <!--td class="w24"><input type="checkbox" name='del_id[]' value="<?php echo $v['member_id']; ?>" class="checkitem"></td-->
		  <td class="align-center"><?php echo $v['member_id']; ?></td>
          <td class="w48 picture"><div class="size-44x44"><span class="thumb size-44x44"><i></i><img src="<?php if ($v['member_avatar'] != ''){ echo UPLOAD_SITE_URL.DS.ATTACH_AVATAR.DS.$v['member_avatar'];}else { echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_user_portrait');}?>?<?php echo microtime();?>"  onload="javascript:DrawImage(this,44,44);"/></span>
          </div></td>
          <td><p class="name"><strong><?php echo $v['member_name']; ?></strong>(<?php echo $lang['member_index_true_name']?>: <?php echo $v['member_truename']; ?>)</p>
            <p class="smallfont"><?php echo $lang['member_index_reg_time']?>:&nbsp;<?php echo $v['member_time']; ?></p>
            
              <div class="im"><span class="email" >
                <?php if($v['member_email'] != ''){ ?>
                <a href="mailto:<?php echo $v['member_email']; ?>" class=" yes" title="<?php echo $lang['member_index_email']?>:<?php echo $v['member_email']; ?>"><?php echo $v['member_email']; ?></a><?php echo $v['member_email']; ?></span>
                <?php }else { ?>
                <a href="JavaScript:void(0);" class="" title="<?php echo $lang['member_index_null']?>" ><?php echo $v['member_email']; ?></a></span>
                <?php } ?>
                <?php if($v['member_ww'] != ''){ ?>
                <a target="_blank" href="http://web.im.alisoft.com/msg.aw?v=2&uid=<?php echo $v['member_ww'];?>&site=cnalichn&s=11" class="" title="WangWang: <?php echo $v['member_ww'];?>"><img border="0" src="http://web.im.alisoft.com/online.aw?v=2&uid=<?php echo $v['member_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" /></a>
                <?php } ?>
                <?php if($v['member_qq'] != ''){ ?>                
                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $v['member_qq'];?>&site=qq&menu=yes" class=""  title="QQ: <?php echo $v['member_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $v['member_qq'];?>:52"/></a>
                <?php } ?>
                <!--//zmr>v30-->
               <?php if($v['member_mobile'] != ''){ ?>
               <div style="font-size:13px; padding-left:10px">&nbsp;&nbsp;<?php echo $v['member_mobile']; ?></div>
               <?php } ?>
              </div></td>
		  <td class="align-center"><?php echo $v['member_login_num']; ?></td>
          <td class="w150 align-center"><p><?php echo $v['member_login_time']; ?></p>
            <p><?php echo $v['member_login_ip']; ?></p></td>
          <td class="align-center"><?php echo $v['member_points']; ?></td>
          <td class="align-center"><p><?php echo $lang['member_index_available'];?>:&nbsp;<strong class="red"><?php echo $v['available_predeposit']; ?></strong>&nbsp;<?php echo $lang['currency_zh']; ?></p>
            <p><?php echo $lang['member_index_frozen'];?>:&nbsp;<strong class="red"><?php echo $v['freeze_predeposit']; ?></strong>&nbsp;<?php echo $lang['currency_zh']; ?></p>
          </td>
          <td class="align-center"><?php echo $v['member_exppoints'];?></td>
          <td class="align-center"><?php echo $v['member_grade'];?></td>
          <td class="align-center"><?php echo $v['member_state'] == 1?$lang['member_edit_allow']:$lang['member_edit_deny']; ?></td>
          <td class="align-center"><a href="index.php?gct=member&gp=member_edit&member_id=<?php echo $v['member_id']; ?>"><?php echo $lang['nc_edit']?></a> | <a href="index.php?gct=notice&gp=notice&member_name=<?php echo ltrim(base64_encode($v['member_name']),'='); ?>"><?php echo $lang['member_index_to_message'];?></a></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="11"><?php echo $lang['nc_no_record']?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot class="tfoot">
        <?php if(!empty($output['member_list']) && is_array($output['member_list'])){ ?>
        <tr>
        <!--td class="w24"><input type="checkbox" class="checkall" id="checkallBottom"></td-->
          <td colspan="16">
          <label for="checkallBottom"><?php echo $lang['nc_select_all']; ?></label>
            &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="if(confirm('<?php echo $lang['nc_ensure_del']?>')){$('#form_member').submit();}"><span><?php echo $lang['nc_del'];?></span></a>
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
  </form>
</div>
<script>
$(function(){
    $('#ncsubmit').click(function(){
    	$('input[name="gp"]').val('member');$('#formSearch').submit();
    });	
});
</script>
