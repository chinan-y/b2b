<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo "销售区域订单管理";?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['manage'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>

  <div style="text-align:right;"><a class="btns" target="_blank" href="index.php?<?php echo $_SERVER['QUERY_STRING'];?>&gp=export_step1"><span><?php echo $lang['nc_export'];?>Excel</span></a></div>
  <table class="table tb-type2 nobdb">
    <thead>
      <tr class="thead">
        <th><?php echo 订单ID;?></th>
        <th><?php echo 店铺;?></th>
        <th><?php echo 用户;?></th>
		<th class="w20"><?php echo "省级";?></th>
		<th class="w20"><?php echo "市级";?></th>
		<th class="w20"><?php echo "县级";?></th>
		<th class="w100" class="align-center"><?php echo "区域";?></th>
		<!--th class="w300"><?php echo "收货信息";?></th-->
        <th class="align-center"><?php echo 时间;?></th>
        <th class="align-center"><?php echo 订单金额;?></th>
        <th class="align-center"><?php echo 付款方式;?></th>
        <th class="align-center"><?php echo 订单状态;?></th>
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
        <td class="nowrap align-center"><?php echo date('Y-m-d H:i:s',$order['add_time']);?></td>
        <td class="align-center"><?php echo $order['order_amount'];?></td>
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