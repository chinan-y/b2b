<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>海关对接管理</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['mess_bill'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch">
	<input type="hidden" name="gct" value="mess">
	<input type="hidden" name="gp" value="mess">
  </form>
  
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_flea_select.js" charset="utf-8"></script>
<script type="text/javascript">
var SITE_URL = "<?php echo SiteUrl; ?>";
$(function(){
	gcategoryInit("gcategory");
});

function submit_form(type){
	if(type=='del'){
		if(!confirm('<?php echo $lang['goods_index_ensure_handle'];?>?')){
			return false;
		}
	}
	$('#type').val(type);
	$('#form_goods').submit();
}
</script>