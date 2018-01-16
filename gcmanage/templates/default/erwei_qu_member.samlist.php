<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['sales_area'];?></h3>
      <ul class="tab-base">
        <li><a href="index.php?gct=erwei_qu_member&gp=member"><span><?php echo $lang['manage'];?></span></a></li>
        <li><a href="index.php?gct=erwei_qu_member&gp=sales_area_add" ><span><?php echo $lang['nc_new'];?></span></a></li>
		<li><a href="JavaScript:void(0);" class="current"><span>销售团队成员</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
    <div style="width:100%; text-align:right;padding-top:10px;">
    </div>

  <form method='post'>
    <input type="hidden" name="form_submit" value="ok" />
	  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5>销售团队成员列表</h5><span class="arrow"></span></div></th>
      </tr>
    </tbody>
  </table>

    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>&nbsp;</th>
          <th colspan="2"><?php echo $lang['member_index_name']?></th>
          <th class="align-center">专属二维码</th>
          <th class="align-center">注册时间</th>
          <th class="align-center"><?php echo $lang['member_index_last_login']?></th>
          <th class="align-center">销售业绩</th>
          <th class="align-center"><?php echo $lang['member_index_prestore'];?></th>
          <th class="align-center"><?php echo 操作;?></th>
        </tr>
      </thead>
      <tbody id="datatable">
        <?php if(!empty($output['samlist']) && is_array($output['samlist'])){ ?>
        <?php foreach($output['samlist'] as $k => $v){ ?>
        <tr class="hover member">
          <td class="w24"></td>
          <td class="w48 picture"><div class="size-44x44"><span class="thumb size-44x44"><i></i><img src="<?php if ($v['member_avatar'] != ''){ echo UPLOAD_SITE_URL.DS.ATTACH_AVATAR.DS.$v['member_avatar'];}else { echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_user_portrait');}?>?<?php echo microtime();?>"  onload="javascript:DrawImage(this,44,44);"/></span></div></td>
          <td><p class="name"><strong><?php echo $v['member_name']; ?></strong>(<?php echo $lang['member_index_true_name']?>: <?php echo $v['member_truename']; ?>)</p>
              <div class="im"><span class="email" >
                <?php if($v['member_email'] != ''){ ?>
                <a href="mailto:<?php echo $v['member_email']; ?>" class="tooltip yes" title="<?php echo $lang['member_index_email']?>:<?php echo $v['member_email']; ?>"><?php echo $v['member_email']; ?></a></span>
                <?php }else { ?>
                <a href="JavaScript:void(0);" class="tooltip" title="<?php echo $lang['member_index_null']?>" ><?php echo $v['member_email']; ?></a></span>
                <?php } ?>
                <?php if($v['member_ww'] != ''){ ?>
                <a target="_blank" href="http://web.im.alisoft.com/msg.aw?v=2&uid=<?php echo $v['member_ww'];?>&site=cnalichn&s=11" class="tooltip" title="WangWang: <?php echo $v['member_ww'];?>"><img border="0" src="http://web.im.alisoft.com/online.aw?v=2&uid=<?php echo $v['member_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" /></a>
                <?php } ?>
                <?php if($v['member_qq'] != ''){ ?>                
                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $v['member_qq'];?>&site=qq&menu=yes" class="tooltip"  title="QQ: <?php echo $v['member_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $v['member_qq'];?>:52"/></a>
                <?php } ?>
              </div></td>
			   <td class="align-center">
					<img src="http://qr.liantu.com/api.php?w=150&text=http://www.qqbsmall.com/wap/index.html?ref=<?php echo $v['member_id'] ?>"/>
			   </td>
          <td class="align-center"><?php echo $v['member_time']; ?></td>
          <td class="w150 align-center"><p><?php echo $v['member_login_time']; ?></p>
            <p><?php echo $v['member_login_ip']; ?></p></td>
          <td class="align-center"><?php echo $v['member_salescredit']; ?></td>
          <td class="align-center"><p><?php echo $lang['member_index_available'];?>:&nbsp;<strong class="red"><?php echo $v['available_predeposit']; ?></strong>&nbsp;<?php echo $lang['currency_zh']; ?></p>
            <p><?php echo $lang['member_index_frozen'];?>:&nbsp;<strong class="red"><?php echo $v['freeze_predeposit']; ?></strong>&nbsp;<?php echo $lang['currency_zh']; ?></p></td>
            <td class="align-center" style="width:180px;"><span><a href="index.php?gct=erwei_qu_member&gp=achievement&mname=<?php echo $v['member_name'];?>">业绩查询</a>  </span></td>
       
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="11"><?php echo $lang['no_record']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
      <?php if(!empty($output['samlist']) && is_array($output['samlist'])){ ?>
      <tfoot class="tfoot">
        <tr>
          <td colspan="16">
            <div class="pagination"> <?php echo $output['show_page'];?> </div></td>
        </tr>
      </tfoot>
      <?php } ?>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script language="javascript">
$(function(){
	$('#stime').datepicker({dateFormat: 'yy-mm-dd'});
	$('#etime').datepicker({dateFormat: 'yy-mm-dd'});
    $('#ncexport').click(function(){
    	$('input[name="gp"]').val('export_step1');
    	$('#formSearch').submit();
    });
    $('#ncsubmit').click(function(){
    	$('input[name="gp"]').val('salescreditlog');$('#formSearch').submit();
    });
});
</script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/jquery.qrcode.min1.js" ></script> 
