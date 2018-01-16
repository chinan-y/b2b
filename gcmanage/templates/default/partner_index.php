<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>合作平台管理</h3>
      <ul class="tab-base">
      <li><a href="JavaScript:void(0);" class="current"><span>合作平台列表</span></a></li>
	  <li><a href="index.php?gct=partner&gp=addpartner"><span>合作平台新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" name="gct" value="partner">
    <input type="hidden" name="gp" value="index">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label>合作平台名称</label></th>
          <td><input type="text" name="partner_name" class="txt" value='<?php echo $_GET['partner_name'];?>'></td>
		  <th><?php echo $lang['admin_salescredit_addtime']; ?></th>
          <td>
		    <input type="text" id="stime" name="stime" class="txt date" value="<?php echo $_GET['stime'];?>" >
            <label>~</label>
            <input type="text" id="etime" name="etime" class="txt date" value="<?php echo $_GET['etime'];?>" >
		  </td>
		  <td></td>
          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            <?php if($output['search_field_value'] != '' or $output['search_sort'] != ''){?>
            <a href="index.php?gct=partner&gp=index" class="btns "><span><?php echo $lang['nc_cancel_search']?></span></a>
            <?php }?></td>
        </tr>
      </tbody>
    </table>
  </form>
 
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
			<li><?php echo WAP_SITE_URL ?>/index.html?ref=member_id; &nbsp;&nbsp; <?php echo WXSHOP_SITE_URL ?>/index.html?ref=member_id</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <table class="table tb-type2">
    <thead>
      <tr class="thead">
		<th class="align-center">合作平台ID</th>
        <th class="align-center">合作平台名称</th>
		<th class="align-center">关联帐号ID</th>       
	    <th class="align-center">二维码</th>
        <th class="align-center">合作类型</th>
        <th class="align-center">appid</th>
        <th class="align-center">appkey</th>
        <th class="align-center">显式通知地址</th>
		<th class="align-center">隐式通知地址</th>
		<th class="align-center">创建时间</th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['list_partner']) && is_array($output['list_partner'])){ ?>
      <?php foreach($output['list_partner'] as $k => $v){?>
      <tr class="hover">
        <td><?php echo $v['partner_id'];?></td>
        <td><?php echo $v['partner_name'];?></td>
		<td><?php echo $v['member_id'];?></td>
        <td>
		  <a href="http://qr.liantu.com/api.php?w=500&m=5&text=<?php echo WAP_SITE_URL ?>/index.html?ref=<?php echo $v['member_id'] ?>" onclick="window.open(this.href,'_blank','left=50%,scrollbars=0,resizable=0,width=500px,height=500px');return false" title ="点击查看非微信使用的专属二维码图片">
		  WAP使用
		  </a>
		  |
		  <a href="http://qr.liantu.com/api.php?w=500&m=5&text=<?php echo WXSHOP_SITE_URL ?>/index.html?ref=<?php echo $v['member_id'] ?>" onclick="window.open(this.href,'_blank','left=50%,scrollbars=0,resizable=0,width=500px,height=500px');return false" title ="点击查看微信使用的专属二维码图片">
		 微信使用
		  </a>
		</td>
        <td>
		<?php if($v['type']==0){echo "三方交易|光彩支付";}?>
		<?php if($v['type']==1){echo "三方交易|三方支付";}?>
		</td>
        <td class="align-center"><?php echo $v['appid'];?></td>
        <td class="nowrap align-center"><?php echo $v['appkey'];?></td>
        <td><?php echo $v['notify_url'];?></td>
		<td><?php echo $v['notify_url_1'];?></td>
		<td><?php echo date('Y-m-d H:i:s',$v['add_time']);?></td>
      </tr>
      <?php } ?>
      <?php }else { ?>
      <tr class="no_data">
        <td colspan="15"><?php echo $lang['nc_no_record'];?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <tr class="tfoot">
        <td colspan="15"><div class="pagination"> <?php echo $output['show_page'];?> </div></td>
      </tr>
    </tfoot>
  </table>
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
    	$('input[name="gp"]').val('partner');$('#formSearch').submit();
    });
});
</script>
