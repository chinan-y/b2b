<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/manage.css" rel="stylesheet" />
<script type="text/javascript">
$(function(){
	$(".list li h3").click(function(){
		$(this).toggleClass("sel");
		$(this).parent().siblings().children("dl").slideUp(100);
		$(this).siblings("dl").slideToggle(100);
	});
});
</script>



<div class="audit">
	<h2>直播间管理</h2>
    
</div>
<div class="check">
	<span>房间搜索</span>
    <input type="text"  class="text" placeholder='输入搜素条件' />
    <input type="button" value="" class="button" />
</div>
<ul class="list">
  <li>
    <h3><img src="<?php echo ADMIN_TEMPLATES_URL;?>/images/san02.jpg" />操作提示</h3>
    <dl>
      <dt>查看该直播间信息</dt>
      <dt>进行关闭，开启改直播间操作</dt>
    </dl>
  </li>
</ul>
<div class="data">
    <table>
        <tr>
            <th>主播姓名</th>
            <th>电话号码</th>
            <th>身份证号码</th>
            <th>房间号</th>
            <th>直播类别</th>
            <th>性别</th>
			<th>状态</th>
			<th>是否推荐</th>
            <th>操作</th>
        </tr>
		<?php if($output['live_info']){ ?>
			<?php foreach($output['live_info'] as $key=>$value ){ ?>
			<tr class='live'>
				<td><?php echo $value['live_name'] ?></td>
				<td><?php echo $value['live_mobile'] ?></td>
				<td><?php echo $value['live_cade'] ?></td>
				<td><?php echo $value['live_id'] ?></td>
				<td><?php echo $value['live_class_name'] ?>/<?php echo $value['class_evea_name'] ?></td>
				<td><?php echo $value['live_usersex'] ?></td>
				<td><?php if($value['live_state']){echo '开启';}else{echo '关闭';}  ?></td>
				<td><?php if($value['live_recomend']){echo '是';}else{echo '否';} ?></td>
				<td><a live_RM='<?php echo $value['live_id'] ?>' class='close_live_room' href="javascript:;">关闭直播间</a> | <a  live_RM='<?php echo $value['live_id'] ?>' class='open_live_room' href="javascript:;">开启直播间</a> | <a live_RM='<?php echo $value['live_id'] ?>' recomend='<?php echo $value['live_recomend'] ?>' class='recomend' href='javascript:;'>推荐</a></td>
			</tr>
			<?php } ?>
        <?php } ?>
    </table>
</div>
<script>
	$('.open_live_room').click(function(){
		var live_RM=$(this).attr('live_RM');
		$.ajax({
				type:'post',
				url:'index.php?gct=live&gp=open_live_room',
				data:{live_RM:live_RM},
				dataType:'json',
				success: function(result) {
					if(result){
						alert('操作成功')
						this.href='javascript:back();'
					}
				}
		})
	})
	$('.close_live_room').click(function(){
		var live_RM=$(this).attr('live_RM');
		$.ajax({
				type:'post',
				url:'index.php?gct=live&gp=close_live_room',
				data:{live_RM:live_RM},
				dataType:'json',
				success: function(result) {
					if(result){
						alert('操作成功')
						this.href='javascript:back();'
					}
				}
		})
	})	
	$('.button').click(function(){
		var keyword=$('.text').val();
		if(keyword!=''){
			$.ajax({
				type:'post',
				url:'index.php?gct=live&gp=searchLive',
				data:{keyword:keyword},
				dataType:'json',
				success: function(result) {
					if(result){
						$('.live').remove();
						var str='';
						for(var i in result){
							if(result[i].live_state==1){var state='开启'}else{var state='关闭'}
							if(result[i].live_recomend==1){var recomend='是'}else{var recomend='否'}
							str+='<tr class="live">'
							str+='<td>'+result[i].live_name+'</td>'
							str+='<td>'+result[i].live_mobile+'</td>'
							str+='<td>'+result[i].live_cade+'</td>'
							str+='<td>'+result[i].live_id+'</td>'
							str+='<td>'+result[i].live_class_name+'/'+result[i].class_evea_name+'</td>'
							str+='<td>'+result[i].live_usersex+'</td>'
							str+='<td>'+state+'</td>'
							str+='<td>'+recomend+'</td>'
							str+='<td><a live_RM="'+result[i].live_id+'" class="close_live_room" href="javascript:;">关闭直播间</a> | <a  live_RM="'+result[i].live_id+'" class="open_live_room" href="javascript:;">开启直播间</a> | <a live_RM="'+result[i].live_id+'" recomend="'+result[i].live_recomend+'" class="recomend" href="javascript:;">推荐</a></td>'
							str+='</tr>'
						}
						
						$('table').append(str);
					}else{
						$('.live').remove();
						$('table').append('<tr class="live"><td></td><td></td><td></td><td>没有相关条件的主播</td><td></td><td></td><td></td></tr>')
					
					}
				}
			})
		}
	})
	$('.recomend').click(function(){
		var recomend=$(this).attr('recomend');
		var live_RM=$(this).attr('live_RM');
		$.ajax({
				type:'post',
				url:'index.php?gct=live&gp=recomend',
				data:{live_RM:live_RM,recomend:recomend},
				dataType:'json',
				success: function(result) {
					if(result){
						alert('操作成功')
						location.reload(true);
					}
				}
		})
	})



</script>

