<?php defined('GcWebShop') or exit('Access Invalid!');?>
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/manage.css" rel="stylesheet" />

<!--直播审核-->
<div class="audit">
	<h2>直播审核</h2>
   
</div>
<!--后台数据样式-->
<div class="data">
    <table>
        <tr class="one">
            <th colspan="4">申请人信息</th>
        </tr>
        <tr>
        	<td>姓名：</td>
            <td colspan="3" class="two"><?php echo $output['one_modiflyInfo'][0]['username']; ?></td>
        </tr>
        <tr>
        	<td>联系电话：</td>
            <td class="two"><?php echo $output['one_modiflyInfo'][0]['mobile']; ?></td>
            <td>身份证号码：</td>
            <td class="two"><?php echo $output['one_modiflyInfo'][0]['cade']; ?></td>
        </tr>
		<tr>
        	<td>申请人性别：</td>
            <td class="two"><?php echo $output['one_modiflyInfo'][0]['usersex']; ?></td>
            <td>申请人QQ：</td>
            <td class="two"><?php echo $output['one_modiflyInfo'][0]['qq']; ?></td>
        </tr>
        <tr>
        	<td>申请直播类别：</td>
            <td class="two"><?php echo $output['one_modiflyInfo'][0]['level_one']; ?>/<?php echo $output['one_modiflyInfo'][0]['level_two']; ?></td>
            <td>申请时间：</td>
            <td class="two"><?php echo $output['one_modiflyInfo'][0]['time']; ?></td>
        </tr>
		
        <tr>
        	<td class="three">申请人证件照：</td>
            <td colspan="3" class="two">
                <img src="<?php echo $output['one_modiflyInfo'][0]['image_url']; ?>" />
                <img src="<?php echo $output['one_modiflyInfo'][0]['image_url']; ?>" />
            </td>
        </tr>
    </table>
</div>

