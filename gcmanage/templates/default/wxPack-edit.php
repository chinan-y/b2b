<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/aaa.css" rel="stylesheet" />



</head>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>红包模板</h3>
      <ul class="tab-base">
        <li><a href="index.php?gct=wxpack"  ><span>模板管理</span></a></li>
        <li><a href="index.php?gct=wxpack"  class="current"><span>添加模板</span></a></li>
      </ul>
    </div>
  </div>
<div class="fixed-empty"></div>
  <form id="wxpack" method="post"  action="index.php?gct=wxpack&gp=wxpack_edit">
	<input type='hidden' name='editKey' value='<?php echo $output['editKey']; ?>'>
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="class_name">活动名称<?php echo $lang['nc_colon']; ?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" name="act_name" id="act_name" class="txt" value='<?php echo $output['wxPack']['act_name'] ?>'></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="parent_class_id">发放金额<?php echo $lang['nc_colon']; ?></label></td>
        </tr>
        <tr class="noborder">
           <td class="vatop rowform"><input id="total_amount" name="total_amount" type="text" class="txt" value='<?php echo $output['wxPack']['total_amount'] ?>' /></td>
          <td class="vatop tips"></td>
        </tr> 
		<tr>
          <td colspan="2" class="required"><label for="parent_class_id">红包个数<?php echo $lang['nc_colon']; ?></label></td>
        </tr>
        <tr class="noborder">
           <td class="vatop rowform"><input id="total_num" name="total_num" type="text" class="txt" value='<?php echo $output['wxPack']['total_num'] ?>' /></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="class_sort" class="validation">祝福语<?php echo $lang['nc_colon'];?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="class_sort" name="wishing" id='wishing' type="text" class="txt" value='<?php echo $output['wxPack']['wishing'] ?>'  /></td>
          <td class="vatop tips"><?php echo $lang['class_sort_explain'];?></td>
        </tr>
		<tr>
          <td colspan="2" class="required"><label for="class_sort"  class="validation">备注<?php echo $lang['nc_colon'];?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="class_sort" id='remark' name="remark" type="text" class="txt" value='<?php echo $output['wxPack']['remark'] ?>' /></td>
          <td class="vatop tips"><?php echo $lang['class_sort_explain'];?></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"><a id="submit" href="javascript:void(0)" class="btn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>

<script>
$(function(){
	$('#submit').click(function(){
		$('#wxpack').submit();
		
	})
	$('#wxpack').validate({
        errorPlacement: function(error, element){
            error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        success: function(label){
            label.addClass('valid');
        },
        rules : {
            act_name: {
                required : true,
                maxlength : 255
            },
            total_amount: {
                required : true,
				digits: true,
				maxlength : 7
            },
			wishing: {
                required : true,
				maxlength : 255
            },
			remark: {
                required : true,
				maxlength : 255
            },
			total_num: {
                required : true,
				digits: true,
				maxlength : 4
            },
        },
        messages : {
            act_name: {
                required : "活动名称不能为空",
                maxlength : jQuery.validator.format("活动名称太长啦")
            },
			total_amount: {
                required : "金额不能为空",
				 digits: "金额必须为数字",
                maxlength : jQuery.validator.format("金额在1-200元之间")
            },
			wishing: {
                required : "祝福语不能为空",
                maxlength : jQuery.validator.format("祝福语太长啦")
            },
            remark: {
                required : "备注不能为空",
                maxlength : jQuery.validator.format("备注太长啦")
            },
            total_num: {
                required : "红包个数不能为空",
				 digits: "红包个数只能为数字",
                maxlength : jQuery.validator.format("红包个数为1-10个")
            },
            
        }
    });
})

</script>