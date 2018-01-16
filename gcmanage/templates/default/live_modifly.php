<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/live_modifly.css" rel="stylesheet" />


<script type="text/javascript">
$(function(){
	$(".list li h3").click(function(){
		$(this).toggleClass("sel");
		$(this).parent().siblings().children("dl").slideUp(100);
		$(this).siblings("dl").slideToggle(100);
	});
	$('.modily_LK').click(function(){
		var liveMb=$(this).attr('liveMb');
		this.href='index.php?gct=live&gp=LK&liveMb='+liveMb;
	})
	$('.modifly_success').click(function(){
		var liveMb=$(this).attr('liveMb');
		var that=$(this);
		$.ajax({
				type:'post',
				url:'index.php?gct=live&gp=success&liveMb='+liveMb,
				data:{liveMb:liveMb},
				dataType:'json',
				success: function(result) {
					if(result){
						alert('操作成功');
						that.parent().parent().remove();
						that.href='javascript:back();'
					}
				}
		})
	})
	$('.modifly_faile').click(function(){
		var liveMb=$(this).attr('liveMb');
		var that=$(this);
		$.ajax({
			type:'post',
			url:'index.php?gct=live&gp=fail&liveMb='+liveMb,
			data:{liveMb:liveMb},
			dataType:'json',
			success: function(result) {
				if(result){
					alert('操作成功')
					that.parent().parent().remove();
					that.href='javascript:back();'
				}
			}
		})	
		
	})
});
</script>
</head>
<!--直播审核-->
<div class="audit">
	<h2>直播审核</h2>
    
</div>
<ul class="list">
  <li>
    <h3><img src="<?php echo ADMIN_TEMPLATES_URL;?>/images/san02.jpg" />操作提示</h3>
    <dl>
      <dt>通过后面的操作项，对申请直播的用户</dt>
      <dt>进行查看，操作</dt>
    </dl>
  </li>
</ul>
<!--后台数据样式-->
<div class="data">
    <table>
        <tr>
            <th>申请人姓名</th>
            <th>性别</th>
            <th>电话号码</th>
            <th>身份证号码</th>
            <th>申请时间</th>
            <th>申请人QQ</th>
            <th>申请直播类别</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
		<?php if($output['modiflyInfo']){ ?>
			<?php foreach($output['modiflyInfo'] as $value){ ?>
				<tr>
					<td><?php echo $value['username']; ?></td>
					<td><?php echo $value['usersex']; ?></td>
					<td><?php echo $value['mobile']; ?></td>
					<td><?php echo $value['cade']; ?></td>
					<td><?php echo $value['time']; ?></td>
					<td><?php echo $value['qq']; ?></td>
					<td><?php echo $value['level_one']; ?>/<?php echo $value['level_two']; ?></td>
					<td>审核中</td>
					<td><a class='modily_LK'  liveMb='<?php echo $value['mobile']; ?>' href="javascript:;">查看</a> | <a  liveMb='<?php echo $value['mobile']; ?>' class='modifly_success' href="javascript:;">审核通过</a> | <a  liveMb='<?php echo $value['mobile']; ?>' class='modifly_faile' href="javascript:;">审核不通过</a></td>
				</tr>
			<?php } ?>
		<?php }else{ ?>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>没有在申请的主播</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					
				</tr>
		<?php } ?>
    </table>
</div>
