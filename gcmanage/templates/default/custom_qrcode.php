<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo "自定义二维码生成器"?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_manage']?></span></a></li>
        <li><a href="#" ><span><?php echo "其他";?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" value="custom_qrcode" name="gct">
    <input type="hidden" value="qrcode" name="gp">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
		<td><input type="text" placeholder="api调用地址" value="http://qr.topscan.com/api.php" name="search_field_api" class="txt"></td>
          <td>
          <select name="search_field_name" >
              <option <?php if($output['search_field_name'] == 'member_name'){ ?>selected='selected'<?php } ?> value="member_name">网址信息</option>
              <option <?php if($output['search_field_name'] == 'member_email'){ ?>selected='selected'<?php } ?> value="member_email">电子邮箱</option>
              <option <?php if($output['search_field_name'] == 'member_mobile'){ ?>selected='selected'<?php } ?> value="member_mobile">文本信息</option>
            </select></td>
          <td><input type="text" placeholder="可输入网址、文本信息或电子邮箱" value="<?php echo $output['search_field_value'];?>" name="search_field_value" class="txt"></td>
          <td></td>
          <td></td>
          <td></td>
          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            <?php if($output['search_field_value'] != '' or $output['search_sort'] != ''){?>
            <a href="index.php?gct=custom_qrcode&gp=qrcode" class="btns "><span><?php echo $lang['nc_cancel_search']?></span></a>
            <?php }?></td>
        </tr>
		<tr>
		<td><input type="text" placeholder="背景颜色 fefefe" value="<?php echo $output['search_field_bgcolor'];?>" name="search_field_bgcolor" class="txt" ></td>
		<td><input type="text" placeholder="前景颜色 555555" value="<?php echo $output['search_field_fgcolor'];?>" name="search_field_fgcolor" class="txt"></td>
		<td><input type="text" placeholder="外边距大小 5" value="<?php echo $output['search_field_px'];?>" name="search_field_px" class="txt"></td>
		<td><input type="text" placeholder="宽度高度 200" value="<?php echo $output['search_field_width'];?>" name="search_field_width" class="txt"></td>
		<td><input type="text" placeholder="logo图片网址" value="<?php echo $output['search_field_logo'];?>" name="search_field_logo" class="txt"></td>
		<td></td>
		<td></td>
		<td></td>
		</tr>
		
      </tbody>
    </table>
  </form>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title">
            <h5><?php echo $lang['nc_prompts'];?></h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
			<li>http://qr.liantu.com/api.php?w=500&m=5&text=X</li>
			<li>
			引用参数,以下参数可用于引用二维码图片时定义图片样式<br />
			参数引用例子：http://qr.topscan.com/api.php?&bg=ffffff&fg=cc0000&text=x<br />
			参数	描述	赋值例子<br />
			<B>bg</B>	背景颜色	bg=颜色代码，例如：bg=ffffff;
			<B>fg</B>	前景颜色	fg=颜色代码，例如：fg=cc0000;
			<B>gc</B>	渐变颜色	gc=颜色代码，例如：gc=cc00000;
			<B>el</B>	纠错等级	el可用值：h\q\m\l，例如：el=h<br />
			<B>w</B>	尺寸大小	w=数值（像素），例如：w=300;
			<B>m</B>	静区（外边距）	m=数值（像素），例如：m=30;
			<B>pt</B>	定位点颜色（外框）	pt=颜色代码，例如：pt=00ff00;
			<B>inpt</B>	定位点颜色（内点）	inpt=颜色代码，例如：inpt=000000<br />
			<B>logo</B>	logo图片	logo=图片地址，例如：logo=http://www.topscan.com/images/2013/sample.jpg<br />
			<li/>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <form method="post" id="form_member">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th class="align-center">二维码</th>
		  <th class="align-center">链接地址</th>
        </tr>
      <tbody>
        <tr class="hover member">
		  <td><p class="name">
			<img src="<?php echo $_GET['search_field_api'] ?>?w=<?php echo $_GET['search_field_width'] ?>&bg=<?php echo $_GET['search_field_bgcolor'] ?>&fg=<?php echo $_GET['search_field_fgcolor'] ?>&m=<?php echo $_GET['search_field_px'] ?>&logo=<?php echo $_GET['search_field_logo'] ?>&el=h&text=<?php echo $_GET['search_field_value'] ?>" />
		  </td>
          <td><p class="name">
			实现功能：<?php echo $_GET['search_field_value'] ?><br /><br />
			api地址：<?php echo $_GET['search_field_api'] ?>?w=<?php echo $_GET['search_field_width'] ?>&bg=<?php echo $_GET['search_field_bgcolor'] ?>&fg=<?php echo $_GET['search_field_fgcolor'] ?>&m=<?php echo $_GET['search_field_px'] ?>&logo=<?php echo $_GET['search_field_logo'] ?>&el=h&text=<?php echo $_GET['search_field_value'] ?>
		  </td>
		</tr>  
      </tbody>
      <tfoot class="tfoot">

      </tfoot>
    </table>

  </form>
</div>
<script>
$(function(){
    $('#ncsubmit').click(function(){
    	$('input[name="gp"]').val('qrcode');$('#formSearch').submit();
    });	
});
</script>
