<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="eject_con wrap">
	<div class="tabmenu">
		<?php include template('layout/submenu');?>
	</div>
	<?php if($output['is_two'] == 1){ ?>
	<div class="nc-rank-title top-title">
		<div class="one"><a href="javascript:void(0);" class="one" id="one_rank"><?php echo 'A级用户'?></a></div>
		<div class="two"><a href="javascript:void(0);" id="two_rank"><?php echo 'B级用户'?></a></div>
		<?php if($output['is_three'] == 1){ ?>
		<div class="three"><a href="javascript:void(0);" id="three_rank" ><?php echo 'C级用户'?></a></div>
		<?php } ?>
	</div>
	<?php } ?>
	<div class="mycode-tag one_rank">
		<table class="ncm-default-table">
			<thead>
				<tr>
					<th class="w150">用户名称</th>
					<th class="w200">手机号码</th>
					<th class="w200">注册时间</th>
					<th class="w200">登录次数</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($output['clients_list']) && is_array($output['clients_list'])){ ?>
					<?php foreach($output['clients_list'] as $k => $v){ ?>
					<tr class="bd-line">
						<td class="w150"><strong style="color:#360"><?php echo $v['member_name'] ?></strong></td>
						<td class="w200"><?php echo $v['member_mobile'] ? substr_replace($v['member_mobile'], '****', 3, 4):''; ?></td>
						<td class="w200"><?php echo @date('Y-m-d H:m:s',$v['member_time']);?></td>
						<td class="w200"><?php echo $v['member_login_num'] ?></td>
					</tr>
					<?php } ?>
				<?php }else { ?>
					<tr class="no_data">
						<td colspan="11"><strong style="color:#360">您还没有下级用户，要努力发展哦</strong></td>
					</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="20"><div class="pagination"> <?php echo $output['show_page'];?></div></td>
				</tr>
			</tfoot>
		</table>
	</div>
	
	<div class="mycode-tag two_rank">
		<table class="ncm-default-table">
			<thead>
				<tr>
					<th class="w150">用户名称</th>
					<th class="w200">手机号码</th>
					<th class="w200">注册时间</th>
					<th class="w200">登录次数</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($output['two_list']) && is_array($output['two_list'])){ ?>
					<?php foreach($output['two_list'] as $k => $v){ ?>
					<tr class="bd-line">
						<td class="w150"><strong style="color:#360"><?php echo $v['member_name'] ?></strong></td>
						<td class="w200"><?php echo $v['member_mobile'] ? substr_replace($v['member_mobile'], '****', 3, 4):''; ?></td>
						<td class="w200"><?php echo @date('Y-m-d H:m:s',$v['member_time']);?></td>
						<td class="w200"><?php echo $v['member_login_num'] ?></td>
					</tr>
					<?php } ?>
				<?php }else { ?>
					<tr class="no_data">
						<td colspan="11"><strong style="color:#360">您暂时还没有B级用户</strong></td>
					</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="20"><div class="pagination"> <?php echo $output['show_page'];?></div></td>
				</tr>
			</tfoot>
		</table>
	</div>
	
	<div class="mycode-tag three_rank">
		<table class="ncm-default-table">
			<thead>
				<tr>
					<th class="w150">用户名称</th>
					<th class="w200">手机号码</th>
					<th class="w200">注册时间</th>
					<th class="w200">登录次数</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($output['three_list']) && is_array($output['three_list'])){ ?>
					<?php foreach($output['three_list'] as $k => $v){ ?>
					<tr class="bd-line">
						<td class="w150"><strong style="color:#360"><?php echo $v['member_name'] ?></strong></td>
						<td class="w200"><?php echo $v['member_mobile'] ? substr_replace($v['member_mobile'], '****', 3, 4):''; ?></td>
						<td class="w200"><?php echo @date('Y-m-d H:m:s',$v['member_time']);?></td>
						<td class="w200"><?php echo $v['member_login_num'] ?></td>
					</tr>
					<?php } ?>
				<?php }else { ?>
					<tr class="no_data">
						<td colspan="11"><strong style="color:#360">您暂时还没有C级用户</strong></td>
					</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="20"><div class="pagination"> <?php echo $output['show_page'];?></div></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
<script type="text/javascript">
	var one_rank = document.getElementById('one_rank');
	var two_rank = document.getElementById('two_rank');
	var three_rank = document.getElementById('three_rank');
	$('.two_rank').hide();
    $('.three_rank').hide();
	$('.one').click(function(){	
		$('.two_rank').hide();
		$('.three_rank').hide();
		$('.one_rank').show();
		one_rank.style.color="#e6387f";
		two_rank.style.color="#777";
		three_rank.style.color="#777";
	});
	
	$('.two').click(function(){
		$('.one_rank').hide();
		$('.three_rank').hide();
		$('.two_rank').show();
		one_rank.style.color="#777";
		two_rank.style.color="#E6387F";
		three_rank.style.color="#777";
	});
	
	$('.three').click(function(){	
		$('.one_rank').hide();
		$('.two_rank').hide();
		$('.three_rank').show();
		one_rank.style.color="#777";
		two_rank.style.color="#777";
		three_rank.style.color="#E6387F";
	});
</script>
