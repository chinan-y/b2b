<?php defined('GcWebShop') or exit('Access Invalid!');?>
<?php include template('layout/common_layout');?>
<?php include template('layout/cur_local');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/member.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/member.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ToolTip.js"></script>
<script>
//sidebar-menu
$(document).ready(function() {
    $.each($(".side-menu > a"), function() {
        $(this).click(function() {
            var ulNode = $(this).next("ul");
            if (ulNode.css('display') == 'block') {
            	$.cookie(COOKIE_PRE+'Mmenu_'+$(this).attr('key'),1);
            } else {
            	$.cookie(COOKIE_PRE+'Mmenu_'+$(this).attr('key'),null);
            }
			ulNode.slideToggle();
				if ($(this).hasClass('shrink')) {
					$(this).removeClass('shrink');
				} else {
					$(this).addClass('shrink');
				}
        });
    });
	$.each($(".side-menu-quick > a"), function() {
        $(this).click(function() {
            var ulNode = $(this).next("ul");
			ulNode.slideToggle();
				if ($(this).hasClass('shrink')) {
					$(this).removeClass('shrink');
				} else {
					$(this).addClass('shrink');
				}
        });
    });
});
$(function() {
	//展开关闭常用菜单设置
	$('.set-btn').bind("click",
	function() {
		$(".set-container-arrow").show("fast");
		$(".set-container").show("fast");
	});
	$('[nctype="closeCommonOperations"]').bind("click",
	function() {
		$(".set-container-arrow").hide("fast");
		$(".set-container").hide("fast");
	});

    $('dl[nctype="checkcCommonOperations"]').find('input').click(function(){
        var _this = $(this);
        var _dd = _this.parents('dd:first');
        var _type = _this.is(':checked') ? 'add' : 'del';
        var _value = _this.attr('name');
        var _operations = $('[nctype="commonOperations"]');

        // 最多添加5个
        if (_operations.find('li').length >= 5 && _type == 'add') {
            showError('<?php echo $lang['right_choose_menu_max5']?>');
            return false;
        }
        $.getJSON('<?php echo urlShop('member', 'common_operations')?>', {type : _type, value : _value}, function(data){
            if (data) {
                if (_type == 'add') {
                    _dd.addClass('checked');
                    if (_operations.find('li').length == 0) {
                        _operations.fadeIn('slow');
                    }
                    _operations.find('ul').append('<li style="display : none;" nctype="' + _value + '"><a href="' + _this.attr('data-value') + '">' + _this.attr('data-name') + '</a></li>');
                    _operations.find('li[style]').fadeIn('slow');
                } else {
                    _dd.removeClass('checked');
                    _operations.find('li[nctype="' + _value + '"]').fadeOut('slow', function(){
                        $(this).remove();
                        if (_operations.find('li').length == 0) {
                            _operations.fadeOut('slow');
                        }
                    });
                }
            }
        });
    });
});

</script>
<div class="ncm-container">
  <div class="left-layout">
    <div class="ncm-l-top">
      <h2><a href="index.php?gct=member&gp=home" title="<?php echo $lang['right_member_center']?>"><?php echo $lang['right_member_center']?></a></h2>
      <a href="javascript:void(0)" title="<?php echo $lang['right_menu_setting']?>" class="set-btn"></a>
      <div class="set-container-arrow"></div>
      <div class="set-container">
        <div class="title">
          <h3><?php echo $lang['right_menu_setting']?></h3>
          <a href="javascript:void(0)" title="<?php echo $lang['right_close']?>" class="close-btn close-container" nctype="closeCommonOperations"></a></div>
        <div class="tip"><?php echo $lang['right_choose_menu_max5']?></div>
        <div class="menu-list">
          <?php if (!empty($output['menu_list'])) {?>
          <?php foreach ($output['menu_list'] as $value) {?>
          <dl class="collapsed" nctype="checkcCommonOperations">
            <dt><?php echo $value['name'];?></dt>
            <?php if (is_array($value['child'])) {?>
            <?php foreach ($value['child'] as $key => $val) {?>
            <dd <?php if ($val['selected']) {?>class="checked"<?php }?>>
              <label>
                <input name="<?php echo $key?>" data-value="<?php echo $val['url'];?>" data-name="<?php echo $val['name'];?>" type="checkbox" class="checkbox" <?php if ($val['selected']) {?>checked="checked"<?php }?> />
                <?php echo $val['name'];?></label>
            </dd>
            <?php }?>
            <?php }?>
          </dl>
          <?php }?>
          <?php }?>
        </div>
        <div class="bottom">
          <input type="submit" value="<?php echo $lang['right_confirm']?>" class="setting" nctype="closeCommonOperations">
        </div>
      </div>
    </div>
    <div class="ncm-user-info">
      <div class="avatar"><img src="<?php echo getMemberAvatar($output['member_info']['member_avatar']);?>">
        <div class="frame"></div>
        <?php if (intval($output['message_num']) > 0){ ?>
        <a href="index.php?gct=member_message&gp=message" class="new-message" title="<?php echo $lang['right_newmessage']?>"><?php echo intval($output['message_num']); ?></a>
        <?php }?>
      </div>
      <div class="handle"><a href="index.php?gct=member_information&gp=avatar" title="<?php echo $lang['right_update_head']?>"><i class="icon-camera"></i><?php echo $lang['right_update_head']?></a><a href="index.php?gct=member_information&gp=member" title="<?php echo $lang['right_update_infomation']?>"><i class="icon-pencil"></i><?php echo $lang['right_update_infomation']?></a><a href="index.php?gct=login&gp=logout" title="<?php echo $lang['right_logout']?>"><i class="icon-off"></i><?php echo $lang['right_logout']?></a></div>
      <div class="name">
		<?php if($output['member_info']['member_nickname']){?>
			<?php echo $output['member_info']['member_nickname'];?>
		<?php }else{?>
			<?php echo $_SESSION['member_name'];?>
		<?php }?>&nbsp;
        <?php if ($output['member_info']['level_name']){ ?>
        <div class="nc-grade-mini" style="cursor:pointer;" onclick="javascript:go('<?php echo urlShop('pointgrade','index');?>');"><?php echo $output['member_info']['level_name'];?></div>
        <?php } ?>
		<div class="member_sign"><a href="index.php?gct=login&gp=sign" title="<?php echo $lang['right_signin_points']?>"><?php echo $lang['right_signin']?></a></div>
      </div>
    </div>
    <ul class="ncm-sidebar ncm-quick-menu">
      <li class="side-menu-quick" nctype="commonOperations" <?php if (empty($output['common_menu_list'])) {?>style="display: none;"<?php }?>> <a href="javascript:void(0)">
        <h3><?php echo $lang['right_operation']?></h3>
        </a>
        <ul>
          <?php if (!empty($output['common_menu_list'])) {?>
          <?php foreach ($output['common_menu_list'] as $key=>$value) {?>
          <li nctype="<?php echo $value['key'];?>"> <a href="<?php echo $value['url'];?>"><?php echo $value['name'];?></a></li>
          <?php }?>
          <?php }?>
        </ul>
      </li>
    </ul>
    <ul id="sidebarMenu" class="ncm-sidebar">
      <?php if (!empty($output['menu_list'])) {?>
      <?php foreach ($output['menu_list'] as $key => $value) {?>
      <li class="side-menu"><a href="javascript:void(0)" key="<?php echo $key;?>" <?php if (cookie('Mmenu_'.$key) == 1) echo 'class="shrink"';?>>
        <h3><?php echo $value['name'];?></h3>
        </a>
        <?php if (!empty($value['child'])) {?>
        <ul <?php if (cookie('Mmenu_'.$key) == 1) echo 'style="display:none"';?>>
          <?php foreach ($value['child'] as $key => $val) {?>
          <li <?php if ($key == $output['menu_highlight']) {?>class="selected"<?php }?>><a href="<?php echo $val['url'];?>"><?php echo $val['name'];?></a></li>
          <?php }?>
        </ul>
        <?php }?>
      </li>
      <?php }?>
      <?php }?>
    </ul>
  </div>
  <div class="right-layout">
    <?php require_once($tpl_file);?>
  </div>
  <div class="clear"></div>
</div>
<?php require_once template('footer');?>
</body></html>