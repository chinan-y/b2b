<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/thirdface.css" rel="stylesheet" />



</head>
<!--直播审核-->
<div class="audit">
	<h2>三方订单录入</h2>
    
</div>
<div class='form'>
	<h4>导入excel表单</h4>
	<div>
		<form id='file' action='index.php?gct=thirdFace&gp=excel' method='post' enctype="multipart/form-data" >
			<span class="type-file-box">
			<input name="textfield" id="textfield1" class="type-file-text" type="text" placeholder='请选择你要导入的excel文件'>
			<input name="button" id="button1" value="" class="type-file-button" type="button">
            <input name="file" class="type-file-file" id="site_logo" size="30"  type="file">
            </span>
			<div class='btn'><input type='submit' value='提交'></div>
		</form>
	
	</div>
	<h4 >录入商品</h4>
	
	<div>
		
		<form id='data' method='post' action='index.php?gct=thirdFace&gp=join'>
			<div class='third'>
			<div class='block' ><span>三方ID：</span><input type='text' name='APPID' ></div>
			</div>
			<div class='goods'>
			<div class='block' ><span>商品货号：</span><input type='text' name='serial' ></div>
			<div class='block' ><span>商品价格：</span><input type='text' name='price' ></div>
			<div class='block' ><span>商品数量：</span><input type='text' name='num' ></div>
			<div class='block btn' ><input id='addGoods' type='button' value='增加商品' /></div>
			
			</div>
			<div class='recive'>
			<div class='block' ><span>姓名：</span><input type='text' name='name' ></div>
			<div class='block' ><span>证件号码：</span><input type='text' name='idnum' ></div>
			<div class='block' ><span>联系电话：</span><input type='text' name='mobile' ></div>
			<div class='block' ><span>省份ID：</span><input type='text' name='province' ></div>
			<div class='block' ><span>市区ID：</span><input type='text' name='city' ></div>
			<div class='block' ><span>县ID：</span><input type='text' name='area' ></div>
			<div class='block' ><span>详细地址：</span><input type='text' name='area-info' ></div>
			<div class='block' ><span>街道：</span><input type='text' name='address' ></div>
			</div>
			<div class='message'>
			<div class='block' ><span>买家留言：</span><input type='text' name='message' ></div>
			<div class='block' ><span>外部订单号：</span><input type='text' name='out_trade_no' ></div>
			</div>
			<div class='btn'><input type='submit' value='提交'></div>
		</form>

	</div>
</div>
<div class='data'>
<table>
        <tr>
            <th>光彩订单号</th>
            <th>外部订单号</th>
            <th>状态</th>
            <th>总金额</th>
        </tr>
		<?php if($output['result']){ ?>
			<?php foreach($output['result'] as $value){ ?>
			
				<tr>
					<td><?php echo $value['order_sn']; ?></td>
					<td><?php echo $value['out_trade_no']; ?></td>
					<td>待支付</td>
					<td><?php echo $value['order_amount']; ?></td>
				</tr>
			
			<?php } ?>
		<?php }?>
</table>


</div>
<script>
$(function(){
	$('#file .btn input[type="submit"]').click(function(){
		
		if($('input[type="file"]').val()!=""){
			
			$('#file').submit();
		}else{
			alert('请选择文件')
			return false;
		}
		
		
		// return false;
		// $('#file').submit();
		
	})
	var num=1;
	$('#addGoods').click(function(){
		var str="<div class='block' ><span>商品"+num+"货号：</span><input type='text' name='serial"+num+"' ></div>"+
				"<div class='block' ><span>商品"+num+"价格：</span><input type='text' name='price"+num+"' ></div>"+
				"<div class='block' ><span>商品"+num+"数量：</span><input type='text' name='num"+num+"' ></div>";
		$(this).parent().before(str)
		num++;
		
	})
	
})


</script>