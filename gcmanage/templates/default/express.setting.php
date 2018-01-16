<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['express_name'];?></h3>
      <ul class="tab-base">
        <li><a href="index.php?gct=express&gp=index" ><span><?php echo $lang['express_name']?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current" ><span><?php echo $lang['acount_setting']?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <table class="table tb-type2" id="prompt">
    <tbody>
    <tr class="space odd">
      <th colspan="12"><div class="title">
          <h5><?php echo $lang['nc_prompts'];?></h5>
          <span class="arrow"></span></div></th>
    </tr>
    <tr>
      <td><ul>
          <li><?php echo $lang['acount_setting_tip'];?></li>
        </ul></td>
    </tr>
    </tbody>
  </table>
  <form id="form" method="post" enctype="multipart/form-data" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
      <tr>
        <td colspan="2" class="required"><label for="acount_kd100_id"><?php echo $lang['acount_kd100_id'];?>:</label></td>
      </tr>
      <tr class="noborder">
        <td class="vatop rowform"><input id="acount_kd100_id" name="acount_kd100_id" value="<?php echo $output['list_setting']['acount_kd100_id'];?>" class="txt" type="text" /></td>
        <td class="vatop tips"><span class="vatop rowform"><?php echo $lang['acount_kd100_id'];?></span></td>
      </tr>
      <tr>
        <td colspan="2" class="required"><label for="acount_kd100_key"><?php echo $lang['acount_kd100_key'];?>:</label></td>
      </tr>
      <tr class="noborder">
        <td class="vatop rowform"><input id="acount_kd100_key" name="acount_kd100_key" value="<?php echo $output['list_setting']['acount_kd100_key'];?>" class="txt" type="text" /></td>
        <td class="vatop tips"><span class="vatop rowform"><?php echo $lang['acount_kd100_key'];?></span></td>
      </tr>
      <tr>
        <td colspan="2" class="required"><label for="acount_ickd_id"><?php echo $lang['acount_ickd_id'];?>:</label></td>
      </tr>
      <tr class="noborder">
        <td class="vatop rowform"><input id="acount_ickd_id" name="acount_ickd_id" value="<?php echo $output['list_setting']['acount_ickd_id'];?>" class="txt" type="text" /></td>
        <td class="vatop tips"><span class="vatop rowform"><?php echo $lang['acount_ickd_id'];?></span></td>
      </tr>
      <tr>
        <td colspan="2" class="required"><label for="acount_ickd_key"><?php echo $lang['acount_ickd_key'];?>:</label></td>
      </tr>
      <tr class="noborder">
        <td class="vatop rowform"><input id="acount_ickd_key" name="acount_ickd_key" value="<?php echo $output['list_setting']['acount_ickd_key'];?>" class="txt" type="text" /></td>
        <td class="vatop tips"><span class="vatop rowform"><?php echo $lang['acount_ickd_key'];?></span></td>
      </tr>
      </tbody>
      <tfoot>
      <tr class="tfoot">
        <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.settingForm.submit()"><span><?php echo $lang['nc_submit'];?></span></a></td>
      </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript">
  //<!CDATA[
  $(function(){
    $('#form').validate({
      rules : {
        image_max_size : {
          number : true,
          maxlength : 4
        },
        image_allow_ext : {
          required : true
        }
      },
      messages : {
        image_max_size : {
          number : '<?php echo $lang['image_max_size_only_num'];?>',
          maxlength : '<?php echo $lang['image_max_size_c_num'];?>'
        },
        image_allow_ext : {
          required : '<?php echo $lang['image_allow_ext_not_null'];?>'
        }
      }
    });
  });
  //]]>
</script>