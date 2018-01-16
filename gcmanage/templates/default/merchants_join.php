<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['merchants_join'];?></h3>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="gct" value="merchants" />
    <input type="hidden" name="gp" value="index" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="member_name"><?php echo $lang['merchants_join_check_name'];?></label></th>
          <td><input class="txt" type="text" name="member_name" id="member_name" value="<?php echo $output['league_name'];?>" /></td>
          
          <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            <?php if($output['form_submit'] == 'ok'){?>
            <a class="btns " href="<?php echo urlAdmin('merchants', 'index');?>" title="<?php echo $lang['nc_cancel_search'];?>"><span><?php echo $lang['nc_cancel_search'];?></span></a>
            <?php }?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
  <form method="post" action="<?php echo urlAdmin('merchants', 'del_consult_batch');?>" onsubmit="" name="form1">
    <table class="table tb-type2" style="table-layout: fixed;">
		<thead>
			<tr class="thead">
				<th class="w24"></th>
				<th class="w96 align-center"><?php echo $lang['merchants_join_check_name'];?></th>
				<th class="w156 align-center"><?php echo $lang['merchants_join_check_mobile'];?></th>
				<th class="w156 align-center"><?php echo $lang['merchants_join_check_email'];?></th>
				<th class="w156 align-center"><?php echo $lang['merchants_join_check_time'];?></th>
				<!--<th class="w60 align-center">状态</th>-->
				<th class="w100pre align-center"><?php echo $lang['merchants_join_check_content'];?></th>
				<th class="w96 align-center"><?php echo $lang['nc_handle'];?> </th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($output['consult_list'])){ ?>
				<?php foreach($output['consult_list'] as $val){?>
				<tr class="space">
					<td class="w24"><input type="checkbox" class="checkitem" name="id[]" value="<?php echo $val['league_id'];?>" /></td>
					<td class="align-center"><?php echo $val['league_name'];?></td>
					<td class="align-center"><?php echo $val['league_mobile'];;?></td>
					<td class="align-center"><?php echo $val['league_email'];;?></td>
					<td class="align-center"><?php echo date('Y-m-d H:i:s', $val['league_addtime']);?></td>
					<!--<td class="align-center"><?php echo $output['state'][$val['is_reply']];?></td>-->
					<td><?php echo $val['league_content'];?></td>
					<td>
						<a href="<?php echo urlAdmin('merchants', 'merchants_reply', array('id' => $val['league_id']));?>"><?php if ($val['is_reply'] == 0) {?><?php echo $lang['merchants_join_check_look'];?><?php } else {?><?php echo $lang['merchants_join_check_already_look'];?><?php }?></a> | 
						<a href="javascript:if(confirm('<?php echo $lang['nc_ensure_del'];?>')){location.href='<?php echo urlAdmin('merchants', 'del_consult', array('id' => $val['league_id']));?>';}" class="normal" ><?php echo $lang['nc_del'];?></a>
					</td>
				</tr>
				<?php }?>
			<?php }else{?>
				<tr class="no_data">
					<td colspan="20"><?php echo $lang['nc_no_record'];?></td>
				</tr>
			<?php }?>
		</tbody>
		<tfoot>
			<?php if(!empty($output['consult_list'])){?>
				<tr class="tfoot">
					<td colspan="16">
						<div class="pagination"><?php echo $output['show_page'];?></div>
					</td>
				</tr>
			<?php }?>
		</tfoot>
    </table>
  </form>
</div>

