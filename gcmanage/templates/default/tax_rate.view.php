<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>商品税率</h3>
      <ul class="tab-base">
		<li><a href="index.php?gct=tax_rate&gp=index" ><span>订单列表</span></a></li>
        <li><a href="index.php?gct=tax_rate&gp=add" ><span><?php echo $lang['nc_new'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>查看</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
<form method="post" id="tax_rate_form" >
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="hsid" value="<?php echo $output['taxrate_info']['id']; ?>" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="" class="required" width="10%"><label for="hs_code" class="validation" >海关编码（HScode）:</label></td>
          <td class="vatop rowform"><?php echo $output['taxrate_info']['hs_code']; ?></td>
          <td class="vatop tips"><span class="vatop rowform">10位数字海关商品编码</span></td>
        </tr>
        <tr class="noborder">
          <td colspan="" class="required"><label for="hs_name" class="validation" >海关商品名称:</label></td>
          <td class="vatop rowform"><?php echo $output['taxrate_info']['hs_name']; ?></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="" class="required"><label for="region_id" class="validation">申报海关代码:</label></td>
          <td class="vatop rowform"><?php echo $output['taxrate_info']['region_id']; ?></td>
          <td class="vatop tips"></span></td>
        </tr>
        
        <tr>
          <td colspan="" class="required"><label for="tariff" class="validation">关税:</label></td>
          <td class="vatop rowform"><?php echo $output['taxrate_info']['tariff']; ?></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="" class="required"><label for="common_tariff" class="validation">一般关税:</label></td>
          <td class="vatop rowform"><?php echo $output['taxrate_info']['common_tariff']; ?></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="" class="required"><label for="consumption_tax" class="validation">消费税:</label></td>
          <td class="vatop rowform"><?php echo $output['taxrate_info']['consumption_tax']; ?></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        
        <tr>
          <td colspan="" class="required"><label for="vat_tax" class="validation">进口增值税:</label></td>
          <td class="vatop rowform"><?php echo $output['taxrate_info']['vat_tax']; ?></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        <tr>
          <td colspan=""><label for="KJDS_TAX_RATE" class="validation">跨境电商综合税:</label></td>
          <td class="vatop rowform"><?php echo $output['taxrate_info']['KJDS_TAX_RATE']; ?></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
		
      </tbody>
      <tfoot id="submit-holder" style="display:none;">
        <tr class="tfoot">
          <td colspan="3" ><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
