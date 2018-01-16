<?php defined('GcWebShop') or exit('Access Invalid!'); ?>

<div class="eject_con">
  <div class="tabmenu">
    <?php
	include  template('layout/submenu');
?>
  </div>
    <form id="list_form" method="get" action="index.php" target="_self">
    <table class="ncm-search-table">
      <input type="hidden" name="gct" value="member_order_salearea" />
      <input type="hidden" name= "gp" value="member_my_user" />
      <tr>
       
        <th>团队拓展用户</th>
        <td class="w100">
        
        		<select name="member_id">
        			<option value="" <?php echo $_GET['member_id'] = ''; ?>>所有销售员</option>
      			<?php if(!empty($output['member_eam_list']) && is_array($output['member_eam_list'])){  ?>
	      			<?php foreach($output['member_eam_list'] as $key => $val){ ?>
		            <option value="<?php echo $val['member_id']; ?>" ><?php echo $val['member_name'] ?></option>
		          <?php } ?>
	          <?php } ?>
          	 </select>
        </td>
        <td class="w160"><label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['nc_search']; ?>"/></label></td>
       
      </tr>
    </table>
  </form>
<div class="mycode-tag ">
	<table class="ncm-default-table">
      <thead>
	  <tr>
	  <th class="w90">用户名</th>
	  <th class="w90">真实姓名</th>
	  <th class="w100">手机号码</th>
	  <th class="w200">注册时间</th>
	  <th class="w50">登录次数</th>
	  <th class="w90">电子邮件</th>
	  <th class="w90">销售员</th>
	  <th class="w90">销售员电话</th>
	  </tr>
	  </thead>
	  <tbody>
	  
	  <?php if(!empty($output['all_team_list']) && is_array($output['all_team_list'])){  ?>
      <?php foreach($output['all_team_list'] as $key => $val){ ?>
      	
	  <tr class="bd-line">
		 	<td class="w140"><strong><?php echo $val['member_name'] ?></strong></td>
		  <td class="w90"><strong><?php echo $val['member_truename'] ?></strong></td>
		  <td class="w90"><?php echo $val['member_mobile']; ?></td>
		  <td class="w90"><?php echo date("Y-m-d h:i:s", $val['member_time']); ?></td>
		  <td class="w90"><?php echo $val['member_login_num'] ?></td>
		  <td class="w190"><?php echo $val['member_email'] ?></td>
			<td class="w190"><?php echo $val['refer_member_name'] ?></td>
			<td class="w190"><?php echo $val['refer_member_mobile'] ?></td>
	  </tr>

	  <?php } ?>
	  <?php }else { ?>
        <tr class="no_data">
          <td colspan="11">您的团队暂无成员，要努力发展哦~</td>
        </tr>
        <?php } ?>
	  </tbody>
    <tfoot>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?><!--a class="ncm-btn ml5" href="javascript:DialogManager.close('member_my_team');">关闭</a--> </div></td>
      </tr>
    </tfoot>
  </table>
</div>
</div>
