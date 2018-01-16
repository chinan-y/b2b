<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>海关对接管理</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['mess_base_parameter'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" enctype="multipart/form-data" name="messform">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td class="required"><label for="ebpCode"><?php echo $lang['api_customs_ebpCodeName'];?>:</label></td>
		  <td class="vatop rowform"><input id="ebpCode" name="ebpCode" value="<?php echo $output['mess_list']['api_customs_ebpCode'];?>" class="txt" type="text" /></td>
		  <td class="vatop rowform"><input id="ebpName" name="ebpName" value="<?php echo $output['mess_list']['api_customs_ebpName'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"><?php echo $lang['api_customs_ebpCodeName_descr'];?></span></td>
        </tr>
        
        <tr class="noborder">
          <td  class="required"><label for="ebcCode"><?php echo $lang['api_customs_ebcCodeName'];?>:</label></td>
		  <td class="vatop rowform"><input id="ebcCode" name="ebcCode" value="<?php echo $output['mess_list']['api_customs_ebcCode'];?>" class="txt" type="text" /></td>
		  <td class="vatop rowform"><input id="ebcName" name="ebcName" value="<?php echo $output['mess_list']['api_customs_ebcName'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"><?php echo $lang['api_customs_ebcCodeName_descr'];?></span></td>
        </tr>
		
        <tr class="noborder">
          <td class="required"><label for="copCode"><?php echo $lang['api_customs_copCodeName'];?>:</label></td>
		  <td class="vatop rowform"><input id="copCode" name="copCode" value="<?php echo $output['mess_list']['api_customs_copCode'];?>" class="txt" type="text" /></td>
		  <td class="vatop rowform"><input id="copName" name="copName" value="<?php echo $output['mess_list']['api_customs_copName'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"><?php echo $lang['api_customs_copCodeName_descr'];?></span></td>
        </tr>
		
		<tr class="noborder">
          <td class="required"><label for="agentCode"><?php echo $lang['api_customs_agentCodeName'];?>:</label></td>
		  <td class="vatop rowform"><input id="agentCode" name="agentCode" value="<?php echo $output['mess_list']['api_customs_agentCode'];?>" class="txt" type="text" /></td>
		  <td class="vatop rowform"><input id="agentName" name="agentName" value="<?php echo $output['mess_list']['api_customs_agentName'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"><?php echo $lang['api_customs_agentCodeName_descr'];?></span></td>
        </tr>
		
		<tr class="noborder">
          <td class="required"><label for="logisticsCode"><?php echo $lang['api_customs_logisticsCodeName'];?>:</label></td>
		  <td class="vatop rowform"><input id="logisticsCode" name="logisticsCode" value="<?php echo $output['mess_list']['api_customs_logisticsCode'];?>" class="txt" type="text" /></td>
		  <td class="vatop rowform"><input id="logisticsName" name="logisticsName" value="<?php echo $output['mess_list']['api_customs_logisticsName'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"><?php echo $lang['api_customs_logisticsCodeName_descr'];?></span></td>
        </tr>
		
		<tr class="noborder">
          <td class="required"><label for="payCode_a"><?php echo $lang['api_customs_pay_alipay'];?>:</label></td>
		  <td class="vatop rowform"><input id="payCode_a" name="payCode_a" value="<?php echo $output['mess_list']['api_customs_payCode_a'];?>" class="txt" type="text" /></td>
		  <td class="vatop rowform"><input id="payName_a" name="payName_a" value="<?php echo $output['mess_list']['api_customs_payName_a'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"><?php echo $lang['api_customs_pay_descr'];?></span></td>
        </tr>
		
		<tr class="noborder">
          <td class="required"><label for="payCode_w"><?php echo $lang['api_customs_pay_weixin'];?>:</label></td>
		  <td class="vatop rowform"><input id="payCode_w" name="payCode_w" value="<?php echo $output['mess_list']['api_customs_payCode_w'];?>" class="txt" type="text" /></td>
		  <td class="vatop rowform"><input id="payName_w" name="payName_w" value="<?php echo $output['mess_list']['api_customs_payName_w'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"><?php echo $lang['api_customs_pay_descr'];?></span></td>
        </tr>
		
		<tr class="noborder">
          <td class="required"><label for="areaCode_2"><?php echo $lang['api_customs_areaCodeName_2'];?>:</label></td>
		  <td class="vatop rowform"><input id="areaCode_2" name="areaCode_2" value="<?php echo $output['mess_list']['api_customs_areaCode_2'];?>" class="txt" type="text" /></td>
		  <td class="vatop rowform"><input id="areaName_2" name="areaName_2" value="<?php echo $output['mess_list']['api_customs_areaName_2'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"><?php echo $lang['api_customs_areaCodeName_descr'];?></span></td>
        </tr>
		
		<tr class="noborder">
          <td class="required"><label for="areaCode_7"><?php echo $lang['api_customs_areaCodeName_7'];?>:</label></td>
		  <td class="vatop rowform"><input id="areaCode_7" name="areaCode_7" value="<?php echo $output['mess_list']['api_customs_areaCode_7'];?>" class="txt" type="text" /></td>
		  <td class="vatop rowform"><input id="areaName_7" name="areaName_7" value="<?php echo $output['mess_list']['api_customs_areaName_7'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"><?php echo $lang['api_customs_areaCodeName_descr'];?></span></td>
        </tr>
		
		<tr class="noborder">
		  <td class="required"><label for="emsNo_2"><?php echo $lang['api_customs_emsNo'];?>:</label></td>
          <td class="vatop rowform"><input id="emsNo_2" name="emsNo_2" value="<?php echo $output['mess_list']['api_customs_emsNo_2'];?>" class="txt" type="text" /></td>
		  <td class="vatop rowform"><input id="emsNo_7" name="emsNo_7" value="<?php echo $output['mess_list']['api_customs_emsNo_7'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"><?php echo $lang['api_customs_emsNo_descr'];?></span></td>
        </tr>
		
		<tr class="noborder">
		  <td class="required"><label for="orgCode_2"><?php echo $lang['api_customs_orgCode'];?>:</label></td>
          <td class="vatop rowform"><input id="orgCode_2" name="orgCode_2" value="<?php echo $output['mess_list']['api_customs_orgCode_2'];?>" class="txt" type="text" /></td>
		  <td class="vatop rowform"><input id="orgCode_7" name="orgCode_7" value="<?php echo $output['mess_list']['api_customs_orgCode_7'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"><?php echo $lang['api_customs_orgCode_descr'];?></span></td>
        </tr>
		
		<tr class="noborder">
		  <td class="required"><label for="dxpId"><?php echo $lang['api_customs_dxpId'];?>:</label></td>
          <td class="vatop rowform"><input id="dxpId" name="dxpId" value="<?php echo $output['mess_list']['api_customs_dxpId'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"><?php echo $lang['api_customs_dxpId_descr'];?></span></td>
        </tr>
		
		<tr class="noborder">
		  <td class="required"><label for="assureCode"><?php echo $lang['api_customs_assureCode'];?>:</label></td>
          <td class="vatop rowform"><input id="assureCode" name="assureCode" value="<?php echo $output['mess_list']['api_customs_assureCode'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"><?php echo $lang['api_customs_assureCode_descr'];?></span></td>
        </tr>
		
        <tr class="noborder">
		  <td class="required"><label for="mess_APIURL"><?php echo $lang['mess_APIURL'];?>:</label></td>
          <td class="vatop rowform"><input id="mess_APIURL" name="mess_APIURL" value="<?php echo $output['mess_list']['api_customs_URL'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"><?php echo $lang['mess_APIURL_notice'];?></span></td>
        </tr>
        
        <tr class="noborder">
          <td class="required"><label for="mess_ReceiverId"><?php echo $lang['mess_ReceiverId'];?>:</label></td>
          <td class="vatop rowform"><input id="mess_ReceiverId" name="mess_ReceiverId" value="<?php echo $output['mess_list']['api_customs_ReceiverId'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"><?php echo $lang['mess_ReceiverId_notice'];?></span></td>
        </tr>
        
      </tbody>
      <tfoot id="submit-holder">
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.messform.submit()"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
  
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_flea_select.js" charset="utf-8"></script>
