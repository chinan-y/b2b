<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/aaa.css" rel="stylesheet" />



</head>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>红包模板</h3>
      <ul class="tab-base">
        <li><a href="index.php?gct=wxpack"  class="current"><span>模板管理</span></a></li>
        <li><a href="index.php?gct=wxpack&gp=wxpack_edit" nctype='<?php if($output['edit']){echo "checked";} ?>'  ><span>添加模板</span></a></li>
      </ul>
    </div>
  </div>
<div style='margin-top:50px;'>
  <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th>活动名称</th>
          <th>添加时间</th>
          <th>备注</th>
          <th class="w200 align-center"><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
	  <tbody>
	  <?php if(!empty($output['wxpack'])){ ?>
	  <?php foreach($output['wxpack'] as $key=>$value){ ?>
		<tr>
			<td><?php echo $value['act_name']; ?></td>
			<td><?php echo $value['time']; ?></td>
			<td><?php echo $value['remark']; ?></td>
			<td class='align-center'>
				<a href='index.php?gct=wxpack&gp=wxpack_edit<?php echo '&key='.$key; ?>' >编辑</a>
				&nbsp;|&nbsp;<a id='wxpack_delete' >删除</a>
			</td>
		</tr>
	  <?php } ?>
	  <?php }else{ ?>
		<tr>
			<td></td>
			<td>读取模板失败</td>
			<td></td>
			<td></td>
		
		</tr>
	  <?php } ?>
	  </tbody>
  </table>
</div>  
</div>

<script>
$(function(){
	
	$.each($('.tab-base li a'),function(){
		// alert($(this).attr('nctype'))
		if($(this).attr('nctype')=='checked'){
			window.location.href=this.href;
		}
		
		
	})
	$('#wxpack_delete').click(function(){
		if (confirm('<?php echo $lang['nc_ensure_del'];?>')) {
			$.ajax({
				url:'index.php?gct=wxpack&gp=wxpack_delete<?php echo '&key='.$key; ?>',
				type:'get',
				dataType:'json',
				data:{},
				success:function(re){
					if(re==1){
						
						window.location.reload(true);
					}
				}
				
			})
		
		
		}
	})
})

</script>