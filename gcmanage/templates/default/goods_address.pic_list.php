<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['g_album_manage']; ?></h3>
      <ul class="tab-base">
        <li><a href="index.php?gct=goods_address&gp=list"><span><?php echo $lang['g_album_list'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['g_album_pic_list'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" name="formSearch" action="index.php?gct=goods_address&gp=pic_tist">

    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="pic_name"><?php echo $lang['g_album_name']; ?></label></th>
        </tr> 
        <tr>
          <td><input class="txt" name="keyword" id="keyword" value="" type="text"></td>
        </tr>
        <tr>
          <td>
          		<input type="submit" value="添 加" />
          </td>
        </tr>
      </tbody>
    </table>
  </form>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script>
;
$(function(){
	$('a[nctype="nyroModal"]').nyroModal();
	$('a[nc_type="delete"]').bind('click',function(){

		if(!confirm('<?php echo $lang['nc_ensure_del'];?>')) return false;
		cur_note = this;
	
		$.get("index.php?gct=goods_address&gp=del_album_pic", {'key':$(this).attr('nc_key')}, function(data){
            if (data == 1)
            	$(cur_note).parent().parent().parent().remove();
            else
            	alert('<?php echo $lang['nc_common_del_fail'];?>');
        });
	});
	$('.tip').poshytip({
		className: 'tip-yellowsimple',
		//showOn: 'focus',
		alignTo: 'target',
		alignX: 'center',
		alignY: 'bottom',
		offsetX: 0,
		offsetY: 5,
		allowTipHover: false
	});
});
</script>
