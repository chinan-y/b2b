<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>点播管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?gct=live&gp=on_demand"><span><?php echo '点播视频列表';?></span></a></li>
		<li><a href="javascript:void(0);" class='current'><span><?php echo '上传视频内容';?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" action="index.php?gct=live&gp=add_demand">
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="demand_title"><?php echo '视频标题'.$lang['nc_colon'];?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" name="demand_title" id="demand_title" class="txt"></td>
        </tr>
		
		<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="demand_url"><?php echo '视频链接'.$lang['nc_colon'];?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" name="demand_url" id="demand_url" class="txt"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"><input type="submit" value="提交" ></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>

