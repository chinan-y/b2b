<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>招商加盟</h3>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="reply_form" method="post" name="reply_form">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="mc_id" value="<?php echo $output['merchants_info']['league_id'];?>" />
    <table class="table merchants" >
		<tbody>
			<tr class="noborder">
				<td class="required">咨询人:</td>
			</tr>
			<tr class="noborder">
				<td class="td"><?php echo $output['merchants_info']['league_name'];?></td>
			</tr>

			<tr>
				<td class="required">手机:</td>
			</tr>
			<tr class="noborder">
				<td class="td"><?php echo $output['merchants_info']['league_mobile'];?></td>
			</tr>

			<tr>
				<td class="required">邮箱:</td>
			</tr>
			<tr class="noborder">
				<td class="td"><?php echo $output['merchants_info']['league_email'];?></td>
			</tr>

			<tr >
				<td class="required">咨询时间:</td>
			</tr>
			<tr class="noborder">
				<td class="td"><?php echo date('Y-m-d H:i:s', $output['merchants_info']['league_addtime']);?></td>
			</tr>

			<tr>
				<td colspan="2" class="required">咨询内容:</td>
			</tr>
			<tr class="noborder">
				<td class="cont"><?php echo $output['merchants_info']['league_content'];?></td>
			</tr>
		</tbody> 
		<!--<tr>
          <td colspan="2" class="required">回复: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><textarea name="reply_content" class="tarea" rows="6"><?php echo $output['merchants_info']['mc_reply'];?></textarea></td>
          <td class="vatop tips">不能超过255个字符。</td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15" ><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>-->
    </table>
  </form>
</div>