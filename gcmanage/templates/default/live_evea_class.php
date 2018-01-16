<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/class_live.css" rel="stylesheet">
<!--<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/abixTreeList.css" rel="stylesheet">-->

<div class="audit"  >
	<h2 style='border-bottom:1px solid #CAEDFB; padding-bottom:15px;margin-left:45px;' >直播分类管理</h2>
</div>


<?php $count=0; ?>
<?php $ke; ?>
<?php $na=0; ?>
<ul class='class_live_one' >
<?php foreach($output['live_class'] as $key=>$value){ ?>
	<?php $count++; ?>
	<?php $ke=$key; ?>
	<input type='checkbox'  name='class_one' value='<?php echo $key ?>' >
	<li class='one'>
		<i class='add'></i>
		<span live='one'><?php echo intval($key)+1; ?></span>
		<input type='text' name='class_name' value='<?php echo $value['class_name']; ?>' >
		<input name='image_url' type='text' value='<?php echo $value['class_image']; ?>'>
		<a class='delet_class' evel='one' >删除</a>
		<ul class='two' style='display:none' >
		<?php foreach($output['live_class_evea'] as $k=>$v){ ?>
			<?php if($na<$v['class_evea_id']){$na=$v['class_evea_id'];} ?>
			<?php if($value['class_id']==$v['class_id']){ ?>
			
			<li>
				<b></b>
				<i class='add'></i>
				<span><?php echo $v['class_evea_id']; ?></span>
				<input type='text' name='class_evea_name' value='<?php echo $v['class_name'] ?>' >
				<a class='delet_class' evel='two' >删除</a>
			</li>
			
			<?php } ?>
			
		<?php } ?>
		<li>
			<b></b>
			<input class='two_evea_class' type='button' value='新增二级分类'>
		</li>
		</ul>
		
	</li>
	<?php if(count($output['live_class'])==(intval($key)+1)){ ?>
		<input class='one_evea_class' type='button' value='新增一级分类'>
		<input class='choseAll' type='button' value='全选' ><input class='invert' type='button' value='反选' >
<input type='submit' value='提交'>
	<?php } ?>
<?php } ?>
</ul>


<script>
$(function(){
	$('.class_live_one').on('click','i',function(){
		if(this.className=='add'){
			$(this).removeClass('add');
			$(this).addClass('jian');
		}else{
			$(this).removeClass('jian');
			$(this).addClass('add');
		}
		var ul=$(this).parent().find('ul').css('display')
		if(ul=='none'){
			$(this).parent().find('ul').css('display','block')
			
		}else{
			$(this).parent().find('ul').css('display','none')
		}
	})
	var code=<?php echo $key+1; ?>;
	var codetwo=<?php echo $na; ?>;
	$('.one_evea_class').click(function(){
		if(code<6){
		code++;
		var str="<input type='checkbox' name='class_one' value='"+(code-1)+"' ><li class='one'> <i class='add'></i><span class='codeSpan' live='one'>"+code+"</span><input class='addInput' type='text' name='class_name'  ><input class='addImage' name='image_url' type='text' ><ul style='display:none'><li><b></b><input class='two_evea_class' type='button' value='新增二级分类'></li></ul><a class='delet_class' evel='one' >删除</a></li>";
		
		$(this).before(str);
		}else{
			alert('最多增加6个分类')
		}
	})
	$('.class_live_one').on('click','.two_evea_class',function(){
		codetwo++;
		var string="<li class='addLi'>"+
				"<b></b>"+
				"<i class='add'></i>"+
				"<span>"+codetwo+"</span>"+
				"<input type='text' name='class_evea_name'  ><a class='delet_class' evel='two' >删除</a>"+
				"</li>";
		$(this).parent().before(string);		
	})
	
	$('.class_live_one').on('click','.delet_class',function(){
		var class_id=$(this).parent().children('span').text();
		var that=$(this);
		var evel=$(this).attr('evel')
		if(evel=='one'){
			evelText="你确定删除此分类以及全部下级？";
		}else if(evel=='two'){
			evelText="你确定删除此二级分类？";
		}
		if(window.confirm(evelText)){
			$.ajax({
				type:'post',
				url:"index.php?gct=live&gp=delet_class",
				data:{class_id:class_id,evel:evel},
				dataType:'json',
				success: function(result) {
					if(result){
						if(evel=='one'){
							that.parent().prev().remove();
						}
						that.parent().remove();
						// that.remove();
					}
				}
				
			})
			
		}
		
	})
	$('.invert').click(function(){
		$("input[type='checkbox']").each(function(a){
			
			// if($(this).attr("checked")==true){
				// $(this).attr("checked",false)
			// }
			$(this).attr("checked",!this.checked);
		});
		
		
	})
	$(".choseAll").click(function(){  
		$("input[type='checkbox']").each(function(){
			$(this).attr("checked",true);
		});  
	});
	var a='true';
	$("input[type='submit']").click(function(){
		var one_class=new Array();
		var two_class=new Array();
		var image_url=new Array();
		var s='';
		$('input[name="class_name"]').each(function(){
			if($(this).parent().prev("input[type='checkbox']").is(':checked')){
				one_class[$(this).prev('span').text()]=$(this).val();
				image_url[$(this).prev('span').text()]=$(this).parent().find('input[name="image_url"]').val();
				if($(this).val()==""){
					alert('名字不能为空');
					s+="a";
				}
			}
		})
		$('input[name="class_evea_name"]').each(function(){
			two_class[$(this).prev('span').text()]=$(this).val();
			if($(this).val()==""){
				alert('名字不能为空');
				s+="a";
			}
		})
		var Relationship=new Array();
		$('span[live="one"]').each(function(){
			var text=$(this).text();
			$(this).parent().find('ul').find('li').find('span').each(function(){
				Relationship[$(this).text()]=text;
			})
			
		})
		// for(var i in Relationship){alert(Relationship[i])}
		if(s!=''){
			return false
		}else{
			$.ajax({
				type:'post',
				url:"index.php?gct=live&gp=live_class",
				data:{one_class:one_class,class_two:two_class,Relationship:Relationship,image_url:image_url},
				dataType:'json',
				success: function(result) {
					if(!result){
						a='false';
					}
				}
				
				
				
			})
		}
		if(a=='true'){
			alert('操作成功')
		}
	})
	
	
})

</script>