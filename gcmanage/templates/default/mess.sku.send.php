<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>海关对接管理</h3>
      <ul class="tab-base">
		<li><a href="index.php?gct=mess_sku&gp=mess_sku" ><span><?php echo $lang['mess_sku'];?></span></a></li>
       <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_new'];?></span></a></li>
		
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" name="mess_sku" action = "<?php echo SHOP_SITE_URL ?>/api/mess/mess_send.php">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="MessageType" id="MessageType" value="SKU_INFO" />
    <input type="hidden" name="ActionType" id="ActionType" value="1" />
    <input type="hidden" name="mess_UserNo" id="mess_UserNo" value="<?php echo $output['mess_list']['mess_UserNo'];?>" />
    <input type="hidden" name="mess_Password" id="mess_Password" value="<?php echo $output['mess_list']['mess_Password'];?>" />
    <input type="hidden" name="mess_ESHOP_ENT_NAME" id="mess_ESHOP_ENT_NAME" value="<?php echo $output['mess_list']['mess_ESHOP_ENT_NAME'];?>" />
    <input type="hidden" name="mess_APIURL" id="mess_APIURL" value="<?php echo $output['mess_list']['mess_APIURL'];?>" />
    
    
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="SKU"><?php echo $lang['mess_sku'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="SKU" name="SKU" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="GOODS_NAME"><?php echo $lang['mess_goods_name'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="GOODS_NAME" name="GOODS_NAME" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="GOODS_SPEC"><?php echo $lang['mess_goods_spec'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="GOODS_SPEC" name="GOODS_SPEC" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
         
        <tr>
          <td colspan="2" class="required"><label for="DECLARE_UNIT"><?php echo $lang['mess_declare_unit'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="DECLARE_UNIT" name="DECLARE_UNIT" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
		
        <tr>
          <td colspan="2" class="required"><label for="POST_TAX_NO"><?php echo $lang['mess_post_tax_no'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="POST_TAX_NO" name="POST_TAX_NO" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="LEGAL_UNIT"><?php echo $lang['mess_legal_unit'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="LEGAL_UNIT" name="LEGAL_UNIT" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="CONV_LEGAL_UNIT_NUM"><?php echo $lang['mess_conv_legal_unit_num'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="CONV_LEGAL_UNIT_NUM" name="CONV_LEGAL_UNIT_NUM" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="HS_CODE"><?php echo $lang['mess_hs_code'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="HS_CODE" name="HS_CODE" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="IN_AREA_UNIT"><?php echo $lang['mess_in_area_unit'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="IN_AREA_UNIT" name="IN_AREA_UNIT" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="CONV_IN_AREA_UNIT_NUM"><?php echo $lang['mess_conv_in_area_unit_num'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="CONV_IN_AREA_UNIT_NUM" name="CONV_IN_AREA_UNIT_NUM" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="IS_EXPERIMENT_GOODS"><?php echo $lang['mess_is_experiment_goods'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          <label>
            <input name="IS_EXPERIMENT_GOODS" type="radio" id="IS_EXPERIMENT_GOODS_1" value="1" checked>
            是</label>
            <label>
            <input name="IS_EXPERIMENT_GOODS" type="radio" id="IS_EXPERIMENT_GOODS_0" value="0" >
            否</label>
          </td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
      </tbody>
      <tfoot id="submit-holder">
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.mess_sku.submit()"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
  
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
