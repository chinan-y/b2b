<?php defined('GcWebShop') or exit('Access Invalid!');?>

<style type="text/css">
.cancelData{ width:1200px; border:2px solid #c9e3f7; margin:50px auto; }
.cancelData .one{ height:50px; text-align:center;line-height:50px;font-size:18px;}
.cancelData table{ width:1200px; position:relative;} 
.cancelData table tr{ height:50px;}
.cancelData table tr th{ text-align:center;}
.cancelData table tr td{ color:#727272; text-align:center;font-size: 14px;}
.cancelData table tr td input{ height:30px; width:300px;}
.cancelData table tr td input.button{ width:60px; height:30px; position:absolute; left:47.5%; bottom:9px;font-size: 14px;}
</style>

<div class="cancelData">
	<form action="index.php?gct=customs&gp=repeal" method="post" >
    <table>
        <div class="one">清单撤销申请</div>
        <tr>
            <th>订单编号</th>
            <th>撤销原因</th>
        </tr>
        <tr>
        	<td><input type="text" name="order_sn"  id="order_sn" value="" /></td>
            <td><input type="text"  name="cause" id="cause" value="" /></td>
        </tr>       
        <tr>
        	<td><input type="submit" value="提交" class="button" /></td>
        </tr>        
    </table>
	</form>
</div>

<div class="cancelData">
	<form action="index.php?gct=customs&gp=request_info" method="post" >
    <table>
        <div class="one">请求查询回执</div>
        <tr>
            <th>订单编号（请求）</th>
            <th>支付单号（查询）</th>
        </tr>
        <tr>
        	<td><input type="text" name="order_sn_c"   value="" /></td>
        	<td><input type="text" name="order_sn"  value="" /></td>
        </tr>       
        <tr>
        	<td><input type="submit" value="提交" class="button" /></td>
        </tr>        
    </table>
	</form>
</div>

<div class="cancelData">
	<form action="index.php?gct=customs&gp=emsSend" method="post" >
    <table>
        <div class="one">统一版EMS运单重推</div>
        <tr>
            <th>订单编号</th>
        </tr>
        <tr>
        	<td><input type="text" name="order_sn"   value="" /></td>
        </tr>       
        <tr>
        	<td><input type="submit" value="提交" class="button" /></td>
        </tr>        
    </table>
	</form>
</div>

<div class="cancelData">
	<form action="index.php?gct=customs&gp=yundaSend" method="post" >
    <table>
        <div class="one">韵达运单申报和获取运单号</div>
        <tr>
            <th>订单编号</th>
        </tr>
        <tr>
        	<td><input type="text" name="order_sn"   value="" /></td>
        </tr>       
        <tr>
        	<td><input type="submit" value="提交" class="button" /></td>
        </tr>        
    </table>
	</form>
</div>