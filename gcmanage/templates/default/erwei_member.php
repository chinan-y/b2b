<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo "销售员管理"?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_manage']?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" value="erwei_member" name="gct">
    <input type="hidden" value="member" name="gp">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td>
          <select name="search_field_name" >
              <option <?php if($output['search_field_name'] == 'member_name'){ ?>selected='selected'<?php } ?> value="member_name"><?php echo $lang['member_index_name']?>名称</option>
			  <option <?php if($output['search_field_name'] == 'member_id'){ ?>selected='selected'<?php } ?> value="member_id">会员ID</option>
              <option <?php if($output['search_field_name'] == 'member_email'){ ?>selected='selected'<?php } ?> value="member_email"><?php echo $lang['member_index_email']?></option>
              <option <?php if($output['search_field_name'] == 'member_mobile'){ ?>selected='selected'<?php } ?> value="member_mobile">手机号码</option>
              <option <?php if($output['search_field_name'] == 'member_truename'){ ?>selected='selected'<?php } ?> value="member_truename"><?php echo $lang['member_index_true_name']?></option>
            </select></td>
          <td><input type="text" value="<?php echo $output['search_field_value'];?>" name="search_field_value" class="txt"></td>
		    <td><select name="search_sort" >
              <option value=""><?php echo $lang['nc_sort']?></option>
              <option <?php if($output['search_sort'] == 'member_login_time desc'){ ?>selected='selected'<?php } ?> value="member_login_time desc"><?php echo $lang['member_index_last_login']?></option>
              <option <?php if($output['search_sort'] == 'member_login_num desc'){ ?>selected='selected'<?php } ?> value="member_login_num desc"><?php echo $lang['member_index_login_time']?></option>
            </select></td>
          <td><select name="search_state" >
              <option <?php if($_GET['search_state'] == ''){ ?>selected='selected'<?php } ?> value=""><?php echo $lang['member_index_state']; ?></option>
              <option <?php if($_GET['search_state'] == 'no_informallow'){ ?>selected='selected'<?php } ?> value="no_informallow"><?php echo $lang['member_index_inform_deny']; ?></option>
              <option <?php if($_GET['search_state'] == 'no_isbuy'){ ?>selected='selected'<?php } ?> value="no_isbuy"><?php echo $lang['member_index_buy_deny']; ?></option>
              <option <?php if($_GET['search_state'] == 'no_isallowtalk'){ ?>selected='selected'<?php } ?> value="no_isallowtalk"><?php echo $lang['member_index_talk_deny']; ?></option>
              <option <?php if($_GET['search_state'] == 'no_memberstate'){ ?>selected='selected'<?php } ?> value="no_memberstate"><?php echo $lang['member_index_login_deny']; ?></option>
            </select></td>
          <td><select name="search_grade" >
              <option value='-1'>会员级别</option>
              <?php if ($output['member_grade']){?>
              	<?php foreach ($output['member_grade'] as $k=>$v){?>
              	<option <?php if(isset($_GET['search_grade']) && $_GET['search_grade'] == $k){ ?>selected='selected'<?php } ?> value="<?php echo $k;?>"><?php echo $v['level_name'];?></option>
              	<?php }?>
              <?php }?>
            </select></td>
          <td></td>
          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            <?php if($output['search_field_value'] != '' or $output['search_sort'] != ''){?>
            <a href="index.php?gct=erwei_member&gp=member" class="btns "><span><?php echo $lang['nc_cancel_search']?></span></a>
            <?php }?></td>
        </tr>
      </tbody>
    </table>
  </form>
  <table class="table tb-type2" id="prompt">
    <tbody>
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
          <th class="align-center">ID</th>
		  <th class="align-center">类型</th>
          <th class="align-center"><?php echo $lang['member_index_name']?></th>
          <th class="align-center"><?php echo "电子邮件";?></th>
          <th class="align-center"><?php echo "手机号码"; ?></th>
          <th class="align-center"><?php echo "会员积分";?></th>
          <th class="align-center">经验值</th>
          <th class="align-center">级别</th>
		  <th class="align-center">平台合作方</th>
          <!--th class="align-center"><?php echo $lang['member_index_login']; ?></th-->
          <th class="align-center"><?php echo $lang['nc_handle']; ?></th>
        </tr>
      <tbody>
        <?php if(!empty($output['member_list']) && is_array($output['member_list'])){ ?>
        <?php foreach($output['member_list'] as $k => $v){ ?>
        <tr class="hover member">
          <!--td class="w24"><input type="checkbox" name='del_id[]' value="<?php echo $v['member_id']; ?>" class="checkitem"></td-->
		  <td class="align-center"><?php echo $v['member_id']; ?></td>
		  <td class="align-center">
		  <?php if($v['is_rebate']==1 && $v['is_member_rebate']==0) {echo "商品返利型";} ?>
		  <?php if($v['is_rebate']==0 && $v['is_member_rebate']==1) {echo "个人返利型";} ?>
		  <?php if($v['is_rebate']==0 && $v['is_member_rebate']==0) {echo "data is error";} ?>
		  <?php if($v['is_rebate']==1 && $v['is_member_rebate']==1) {echo "data is error";} ?>
		  </td>
          <td>
		  <?php echo $v['member_name']; ?> <?php if($v['is_member_rebate']==1) {echo "[".$v['member_rebate_rate']*100;echo "%]";}?>
		  <p class="name">
		  二维码[
		  <a href="http://qr.liantu.com/api.php?w=500&m=5&text=<?php echo WAP_SITE_URL ?>/index.html?ref=<?php echo $v['member_id'] ?>" onclick="window.open(this.href,'_blank','left=50%,scrollbars=0,resizable=0,width=500px,height=500px');return false" title ="点击查看非微信使用的专属二维码图片">
		  WAP使用
		  </a>
		  |
		  <a href="http://qr.liantu.com/api.php?w=500&m=5&text=<?php echo WXSHOP_SITE_URL ?>/index.html?ref=<?php echo $v['member_id'] ?>" onclick="window.open(this.href,'_blank','left=50%,scrollbars=0,resizable=0,width=500px,height=500px');return false" title ="点击查看微信使用的专属二维码图片">
		 微信使用
		  </a>]
		  </p>            
          </td>
          <td class="w150 align-center">
				<div class="im"><span class="email" >
                <?php if($v['member_email'] != ''){ ?>
                <?php echo $v['member_email']; ?></span>
                <?php }else { ?>
                </span>
                <?php } ?>
				</div>
		  </td>
          <td class="align-center">
			   <?php if($v['member_mobile'] != ''){ ?>
               <div style="font-size:13px; padding-left:10px">&nbsp;&nbsp;<?php echo $v['member_mobile']; ?></div>
               <?php } ?>
          </td>
		  <td class="align-center"><?php echo $v['member_points']; ?></td>
          <td class="align-center"><?php echo $v['member_exppoints'];?></td>
          <td class="align-center"><?php echo $v['member_grade'];?></td>
		  <td class="align-center"><?php if($v['saleplat_id']>0){echo $v['partner_name']['partner_name'];} ?></td>
          <!--td class="align-center"><?php echo $v['member_state'] == 1?$lang['member_edit_allow']:$lang['member_edit_deny']; ?></td-->
          <td class="align-center"><a href="index.php?gct=erwei_member&gp=yonghu&member_id=<?php echo $v['member_id']; ?>&usertype=A"><?php echo  "下级用户信息";?></a> | <a href="index.php?gct=erwei_member&gp=salesman_profit&mname=<?php echo $v['member_name']; ?>"><?php echo "收益明细查询";?></a> | <a href="index.php?gct=member&gp=member_edit&member_id=<?php echo $v['member_id']; ?>"><?php echo $lang['nc_edit']?></a> | <a href="index.php?gct=notice&gp=notice&member_name=<?php echo ltrim(base64_encode($v['member_name']),'='); ?>"><?php echo $lang['member_index_to_message'];?></a></td>
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
