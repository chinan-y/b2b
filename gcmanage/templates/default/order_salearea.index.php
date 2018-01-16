<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo "销售总代理区域订单管理";?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['manage'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="gct" value="order_salearea" />
    <input type="hidden" name="gp" value="index" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
         <th><label><?php echo $lang['order_number'];?></label></th>
         <td><input class="txt2" type="text" name="order_sn" value="<?php echo $_GET['order_sn'];?>" /></td>
         <th><?php echo $lang['store_name'];?></th>
         <td><input class="txt-short" type="text" name="store_name" value="<?php echo $_GET['store_name'];?>" /></td>
         <th><label><?php echo $lang['order_state'];?></label></th>
          <td colspan="4"><select name="order_state" class="querySelect">
              <option value=""><?php echo $lang['nc_please_choose'];?></option>
              <option value="10" <?php if($_GET['order_state'] == '10'){?>selected<?php }?>><?php echo $lang['order_state_new'];?></option>
              <option value="20" <?php if($_GET['order_state'] == '20'){?>selected<?php }?>><?php echo $lang['order_state_pay'];?></option>
              <option value="30" <?php if($_GET['order_state'] == '30'){?>selected<?php }?>><?php echo $lang['order_state_send'];?></option>
              <option value="40" <?php if($_GET['order_state'] == '40'){?>selected<?php }?>><?php echo $lang['order_state_success'];?></option>
              <option value="0"	 <?php if($_GET['order_state'] == '0'){?>selected<?php }?>> <?php echo $lang['order_state_cancel'];?></option>
            </select></td>
        
        </tr>
        <tr>
          <th><label for="query_start_time"><?php echo $lang['order_time_from'];?></label></th>
          <td><input class="txt date" type="text" value="<?php echo $_GET['query_start_time'];?>" id="query_start_time" name="query_start_time">
            <label for="query_start_time">~</label>
            <input class="txt date" type="text" value="<?php echo $_GET['query_end_time'];?>" id="query_end_time" name="query_end_time"/></td>
         <th><?php echo $lang['buyer_name'];?></th>
         <td><input class="txt-short" type="text" name="buyer_name" value="<?php echo $_GET['buyer_name'];?>" /></td> <th>付款方式</th>
         <td>
            <select name="payment_code" class="w100">
            <option value=""><?php echo $lang['nc_please_choose'];?></option>
            <?php foreach($output['payment_list'] as $val) { ?>
            <option <?php if($_GET['payment_code'] == $val['payment_code']){?>selected<?php }?> value="<?php echo $val['payment_code']; ?>"><?php echo $val['payment_name']; ?></option>
            <?php } ?>
            </select>
         </td>
		<tr>
		  <th>销售区域</th>
		  <td colspan="1">
		   <div id="region">
            <input type="hidden" name="province_id" id="province_id" value="<?php echo $output['address_info']['province_id'];?>">
			<input type="hidden" name="city_id" id="city_id" value="<?php echo $output['address_info']['city_id'];?>">
            <input type="hidden" name="area_id" id="area_id" value="<?php echo $output['address_info']['area_id'];?>" class="area_ids" />
            <input type="hidden" name="area_info" id="area_info" value="<?php echo $output['address_info']['area_info'];?>" class="area_names" />
			<br />
            <?php if(!empty($output['address_info']['area_id'])){?>
            <span><?php echo $output['address_info']['area_info'];?></span>
            <input type="button" value="<?php echo $lang['nc_edit'];?>" class="edit_region" />
            <select style="display:none;">
            </select>
            <?php }else{?>
            <select>
            </select>
            <?php }?>
		   </div>	 
          </td>
		  <th><label>区域合作方</label></th>
         <td><input class="txt-short" type="text" name="area_name" value="<?php echo $_GET['area_name'];?>" />
		 <a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a></td>
        </tr>
      </tbody>
    </table>
  </form>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li><?php echo $lang['order_help1'];?></li>
            <li><?php echo $lang['order_help2'];?></li>
            <li><?php echo $lang['order_help3'];?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <div style="text-align:right;"><a class="btns" target="_blank" href="index.php?<?php echo $_SERVER['QUERY_STRING'];?>&gp=export_step1"><span><?php echo $lang['nc_export'];?>Excel</span></a></div>
  <table class="table tb-type2 nobdb">
    <thead>
      <tr class="thead">
        <th><?php echo $lang['order_number'];?></th>
        <th><?php echo $lang['store_name'];?></th>
        <th><?php echo $lang['buyer_name'];?></th>
		<?php if(C('salescredit_isuse') == 1) { ?> <th class="align-center"><?php echo "消费奖励";	?></th><?php } ?>
		<!--
		<th class="w20"><?php echo "省级";?></th>
		<th class="w20"><?php echo "市级";?></th>
		<th class="w20"><?php echo "县级";?></th>
		-->
		<th class="w100" class="align-center"><?php echo "收货区域";?></th>
		<!--th class="w300"><?php echo "收货信息";?></th-->
		<th class="align-center"><?php echo $lang['order_total_price'];?></th>
        <th class="align-center"><?php echo $lang['order_time'];?></th>
		<?php if(C('one_rank_rebate') == 1){?> <th class="align-center"><?php echo "UP1";?></th><th class="align-center"><?php echo "奖励";?></th><?php }?>
		<?php if(C('two_rank_rebate') == 1){?><th class="align-center"><?php echo "UP2";?></th><th class="align-center"><?php echo "奖励";?></th><?php } ?>
		<?php if(C('three_rank_rebate') == 1){?> <th class="align-center"><?php echo "UP3";?></th><th class="align-center"><?php echo "奖励";?></th><?php } ?>
		
		<th class="align-center">区域合作方</th>
		<th class="align-center">推广提成</th>
		<th class="align-center">授权区域</th>
        <th class="align-center"><?php echo $lang['payment'];?></th>
        <th class="align-center"><?php echo $lang['order_state'];?></th>
        <th class="align-center"><?php echo $lang['nc_handle'];?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(count($output['order_list'])>0){?>
      <?php foreach($output['order_list'] as $order){?>
      <tr class="hover">
        <td><?php echo $order['order_sn'];?></td>
        <td><?php echo $order['store_name'];?></td>
        <td><?php echo $order['buyer_name'];?></td>
		<?php if(C('salescredit_isuse') == 1) { ?> <td class="align-center"><?php echo $order['goods_rebate_amount'];?></td><?php } ?>
		<!--
		<td> 
		<dl>
           <dd><?php echo $order['extend_order_common']['reciver_province_id'];?></dd>
        </dl>
		</td>
		<td>
		<dl>
          <dd><?php echo $order['extend_order_common']['reciver_city_id'];?></dd>
        </dl>
		</td>
		<td>
		<dl>
          <dd><?php echo $order['extend_order_common']['reciver_area_id'];?></dd>
        </dl>
		</td>
		-->
	    <td>
			<?php echo $order['extend_order_common']['reciver_info']['area'];?>
		</td>
		<!--td>
          <div class="buyer-info"> <em></em>
            <div class="con">
              <dl>
                <dd>姓名：<?php echo $order['extend_order_common']['reciver_name'];?>
				&nbsp;&nbsp;<?php echo $order['extend_order_common']['reciver_idnum'];?></dd>
              </dl>
              <dl>
                <dd>手机：<?php echo $order['extend_order_common']['reciver_info']['mob_phone'];?></dd>
              </dl>
              <dl>
                <dd>地址：<?php echo $order['extend_order_common']['reciver_info']['address'];?></dd>
              </dl>
			  <dl>
                <dd>区域：<?php echo $order['extend_order_common']['reciver_info']['area'];?></dd>
              </dl>
            </div>
          </div>
		</td-->
		<td class="align-center"><?php echo $order['order_amount'];?></td>
        <td class="nowrap align-center"><?php echo date('Y-m-d H:i:s',$order['add_time']);?></td>
		<?php if(C('one_rank_rebate') == 1){?><td><?php echo $order['one_name'];?></td><td class="align-center"><?php echo $order['one_rebate'];?></td><?php } ?>
		<?php if(C('two_rank_rebate') == 1){?><td><?php echo $order['two_name'];?></td><td class="align-center"><?php echo $order['two_rebate'];?></td><?php } ?>
		<?php if(C('three_rank_rebate') == 1){?> <td><?php echo $order['three_name'];?></td><td class="align-center"><?php echo $order['three_rebate'];?></td><?php } ?>
		<td class="align-center"><?php echo $order['salesarea_name'];?></td>
		<td class="align-center"><?php echo $order['area_rebate'];?></td>
		<td class="align-center"><?php echo $order['agentarea_name'];?></td>
        <td class="align-center"><?php echo orderPaymentName($order['payment_code']);?></td>
        <td class="align-center"><?php echo orderState($order);?></td>
        <td class="w20 align-center"><a href="index.php?gct=order&gp=show_order&order_id=<?php echo $order['order_id'];?>"><?php echo $lang['nc_view'];?></a>
        	</td>
      </tr>
      <?php }?>
      <?php }else{?>
      <tr class="no_data">
        <td colspan="15"><?php echo $lang['nc_no_record'];?></td>
      </tr>
      <?php }?>
    </tbody>
    <tfoot>
      <tr class="tfoot">
        <td colspan="15" id="dataFuncs"><div class="pagination"> <?php echo $output['show_page'];?> </div></td>
      </tr>
    </tfoot>
  </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(function(){
    $('#query_start_time').datepicker({dateFormat: 'yy-mm-dd'});
    $('#query_end_time').datepicker({dateFormat: 'yy-mm-dd'});
    $('#ncsubmit').click(function(){
    	$('input[name="gp"]').val('index');$('#formSearch').submit();
    });
});
</script> 






<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript">
var SITEURL = "<?php echo SHOP_SITE_URL; ?>";
$(document).ready(function(){
	regionInit("region");
    $('#address_form').validate({
    	submitHandler:function(form){
    		if ($('select[class="valid"]').eq(1).val()>0) $('#city_id').val($('select[class="valid"]').eq(1).val());
    		ajaxpost('address_form', '', '', 'onerror');
    	},
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
           var errors = validator.numberOfInvalids();
           if(errors)
           {
               $('#warning').show();
           }
           else
           {
               $('#warning').hide();
           }
        },
        rules : {
            true_name : {
                required : true
            },
			true_idnum : {
                required : true,
                minlength : 18,
				maxlength : 18
            },
            area_id : {
                required : true,
                min   : 1,
                checkarea : true
            },
            address : {
                required : true
            },
            tel_phone : {
                required : check_phone,
                minlength : 6,
				maxlength : 20
            },
            mob_phone : {
                required : check_phone,
                minlength : 11,
				maxlength : 11,                
                digits : true
            }
			
        },
        messages : {
            true_name : {
                required : '<?php echo $lang['member_address_input_receiver'];?>'
            },
			true_idnum : {
                required : '<?php echo $lang['member_address_must_true_idnum'];?>',
                minlength: '<?php echo $lang['member_address_true_idnum_rule'];?>',
				maxlength: '<?php echo $lang['member_address_true_idnum_rule'];?>'
            },
            area_id : {
                required : '<?php echo $lang['member_address_choose_location'];?>',
                min  : '<?php echo $lang['member_address_choose_location'];?>',
                checkarea  : '<?php echo $lang['member_address_choose_location'];?>'
            },
            address : {
                required : '<?php echo $lang['member_address_input_address'];?>'
            },
            tel_phone : {
                required : '<?php echo $lang['member_address_phone_and_mobile'];?>',
                minlength: '<?php echo $lang['member_address_phone_rule'];?>',
				maxlength: '<?php echo $lang['member_address_phone_rule'];?>'
            },
            mob_phone : {
                required : '<?php echo $lang['member_address_phone_and_mobile'];?>',
                minlength: '<?php echo $lang['member_address_wrong_mobile'];?>',
				maxlength: '<?php echo $lang['member_address_wrong_mobile'];?>',
                digits : '<?php echo $lang['member_address_wrong_mobile'];?>'
            }
			
        },
        groups : {
            phone:'tel_phone mob_phone'
        }
    });

});

</script>