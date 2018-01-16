<?php defined('GcWebShop') or exit('Access Invalid!');?>

<!--点播管理-->
<div class="page">
	<div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo '点播管理';?></h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span><?php echo '点播视频列表';?></span></a></li>
                <li><a href="index.php?gct=live&gp=add_demand"><span><?php echo '上传视频内容';?></span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
	<form method="post" action="index.php?gct=live&gp=on_demand">
		<table class="tb-type1 noborder search">
			<tbody>
			<tr>
				<th><label for="demand_title"><?php echo '视频标题';?></label></th>
				<td><input class="txt" name="demand_title" id="demand_title" type="text"></td>
				<th><label for="query_start_time"><?php echo '上传时间';?></label></th>
				<td><input class="txt date" type="text" value="<?php echo $_GET['query_start_time'];?>" id="query_start_time" name="query_start_time">
				<label for="query_start_time">~</label>
				<input class="txt date" type="text" value="<?php echo $_GET['query_end_time'];?>" id="query_end_time" name="query_end_time"/></td>
				<th><label for="demand_title"><?php echo '来源';?></label></th>
				<td>
					<select name="source" class="w100">
					<option value=""><?php echo $lang['nc_please_choose'];?></option>
					<option  value="1"><?php echo '前端'; ?></option>
					<option  value="2"><?php echo '后台'; ?></option>
					</select>
				</td>
				<td><input type="submit" class="btn-search " ></td>
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
						<li><?php echo '点播视频列表，上面可以上传点播视频链接，视频标题';?></li>
					</ul>
				</td>
			</tr>
		</tbody>
	</table>
	
	<!--点播视频列表-->
	<table class="table tb-type2">
		<thead>
			<tr>
				<th></th>
				<th>上传人用户名</th>
				<th>来源</th>
				<th>视频链接</th>
				<th>视频标题</th>
				<th>上传时间</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
		<?php if(!empty($output['demand_list']) && is_array($output['demand_list'])){ ?>
			<?php foreach($output['demand_list'] as $value){ ?>
				<tr>
					<td></td>
					<td><?php echo $value['member_name']; ?></td>
					<td><?php echo $value['source']; ?></td>
					<td><?php echo $value['demand_url']; ?></td>
					<td><?php echo $value['demand_title']; ?></td>
					<td><?php echo $value['demand_time']; ?></td>
					<td><a class='del_demand'  demand_id='<?php echo $value['demand_id']; ?>' demand_title='<?php echo $value['demand_title']; ?>' href="javascript:;">删除</a></td>
				</tr>
			<?php } ?>
		<?php }else { ?>
			<tr class="no_data">
				<td colspan="10"><?php echo $lang['nc_no_record'];?></td>
			</tr>
		<?php } ?>
		</tbody>
		<tfoot>
			<?php if(!empty($output['demand_list']) && is_array($output['demand_list'])){ ?>
				<tr colspan="15" class="tfoot">
					<td colspan="16"><div class="pagination"> <?php echo $output['page'];?> </div></td>
				</tr>
			<?php } ?>
		</tfoot>
	</table>
</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(function(){
	$('#query_start_time').datepicker({dateFormat: 'yy-mm-dd'});
    $('#query_end_time').datepicker({dateFormat: 'yy-mm-dd'});
	
	$('.del_demand').click(function(){
		if(window.confirm('你确定要删除视频吗？')){
			var demand_id=$(this).attr('demand_id');
			var demand_title=$(this).attr('demand_title');
			$.ajax({
				url:"index.php?gct=live&gp=del_demand",
				type:"post",
				data:{demand_id:demand_id, demand_title:demand_title},
				dataType:"json",
				success:function(result){
					window.location.href = window.location.href;
				}
			});
		}
	})

});
</script>
