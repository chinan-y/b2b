<?php defined('GcWebShop') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
$(document).ready(function(){
    //行内ajax编辑
    $('span[nc_type="tag_sort"]').inline_edit({gct: 'cms_tag',gp: 'update_tag_sort'});
    $('span[nc_type="tag_name"]').inline_edit({gct: 'cms_tag',gp: 'update_tag_name'});
});
function submit_delete_batch(){
    /* 获取选中的项 */
    var items = '';
    $('.checkitem:checked').each(function(){
        items += this.value + ',';
    });
    if(items != '') {
        items = items.substr(0, (items.length - 1));
        submit_delete(items);
    }  
    else {
        alert('<?php echo $lang['nc_please_select_item'];?>');
    }
}
function submit_delete(id){
    if(confirm('<?php echo $lang['nc_ensure_del'];?>')) {
        $('#list_form').attr('method','post');
        $('#list_form').attr('action','index.php?gct=cms_tag&gp=cms_tag_drop');
        $('#tag_id').val(id);
        $('#list_form').submit();
    }
}
</script>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['nc_cms_tag_manage'];?></h3>
      <ul class="tab-base">
        <?php   foreach($output['menu'] as $menu) {  if($menu['menu_type'] == 'text') { ?>
        <li><a href="<?php echo $menu['menu_url'];?>" class="current"><span><?php echo $menu['menu_name'];?></span></a></li>
        <?php }  else { ?>
        <li><a href="<?php echo $menu['menu_url'];?>" ><span><?php echo $menu['menu_name'];?></span></a></li>
        <?php  } }  ?>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <!-- 操作说明 -->
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12" class="nobg"> <div class="title">
            <h5><?php echo $lang['nc_prompts'];?></h5>
            <span class="arrow"></span> </div>
        </th>
      </tr>
      <tr>
        <td><ul>
            <li><?php echo $lang['cms_article_class_list_tip1'];?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <form id="list_form" method='post'>
    <input id="tag_id" name="tag_id" type="hidden" />
    <table class="table tb-type2">
      <thead>
        <tr class="space">
          <th colspan="15" class="nobg"><?php echo $lang['nc_list'];?></th>
        </tr>
        <tr class="thead">
          <th></th>
          <th><?php echo $lang['nc_sort'];?></th>
          <th><?php echo $lang['cms_tag_name'];?></th>
          <th class="align-center"><?php echo $lang['cms_tag_count'];?></th>
          <th class="align-center"><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $val){ ?>
        <tr class="hover edit">
          <td class="w24"><input type="checkbox" value="<?php echo $val['tag_id'];?>" class="checkitem"></td>
          <td class="w48 sort"><span nc_type="tag_sort" column_id="<?php echo $val['tag_id'];?>" title="<?php echo $lang['nc_editable'];?>" class="editable "><?php echo $val['tag_sort'];?></span>
          <td class="name"><span nc_type="tag_name" column_id="<?php echo $val['tag_id'];?>" title="<?php echo $lang['nc_editable'];?>" class="editable "><?php echo $val['tag_name'];?></span></td>
          <td class="w50pre align-center"><?php echo $val['tag_count'];?></td>
          <td class="w72 align-center"><a href="javascript:submit_delete(<?php echo $val['tag_id'];?>)"><?php echo $lang['nc_del'];?></a></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="10"><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
      <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
      <tfoot>
        <tr class="tfoot">
          <td><input type="checkbox" class="checkall" id="checkall_1"></td>
          <td id="batchAction" colspan="15"><span class="all_checkbox">
            <label for="checkall_1"><?php echo $lang['nc_select_all'];?></label>
            </span>&nbsp;&nbsp; <a href="javascript:void(0)" class="btn" onclick="submit_delete_batch();"><span><?php echo $lang['nc_del'];?></span></a>
            <div class="pagination"><?php echo $output['show_page'];?></div>
        </tr>
      </tfoot>
      <?php } ?>
    </table>
  </form>
</div>