<?php

/**
 * 导出订单流水表
 *
 */
defined('GcWebShop') or exit('Access Invalid!');

class exportExcelControl extends SystemControl{
    private $links = array(
        array('url'=>'gct=stat_aftersale&gp=refund','lang'=>'stat_refund'),
        array('url'=>'gct=stat_aftersale&gp=evalstore','lang'=>'stat_evalstore'),
    );

    private $search_arr;//处理后的参数

    public function __construct(){
        parent::__construct();
        Language::read('stat');
        import('function.statistics');
        import('function.datehelper');

        $model = Model('stat');
        //存储参数
		$this->search_arr = $_REQUEST;
		//处理搜索时间
		if (in_array($this->search_arr['gp'],array('refund'))){
		    $this->search_arr = $model->dealwithSearchTime($this->search_arr);
    		//获得系统年份
    		$year_arr = getSystemYearArr();
    		//获得系统月份
    		$month_arr = getSystemMonthArr();
    		//获得本月的周时间段
    		$week_arr = getMonthWeekArr($this->search_arr['week']['current_year'], $this->search_arr['week']['current_month']);
    		Tpl::output('year_arr', $year_arr);
    		Tpl::output('month_arr', $month_arr);
    		Tpl::output('week_arr', $week_arr);
		}
		Tpl::output('search_arr', $this->search_arr);
    }

	
	public function indexOp(){

            $model = Model('order');
            $model->beginTransaction();
			
			//导出CSV 
			if ($_POST['action']=='order_list_new') {
				$start_time = $_POST['start_time'];
				$end_time = $_POST['end_time'];
				if(!$start_time || !$end_time){
					exit("请务必输入起止时间");
				}
				$statlist = array();
				
				$order_list = DB::getAll("select 
FROM_UNIXTIME(o.add_time,'%Y-%m-%d %H:%i:%S') 订单时间,
o.store_name 店铺名称,
o.order_sn '订单编号',
o.out_trade_no 外部订单号,
CASE o.order_distinguish 
WHEN '0' THEN '个人返利'
WHEN '1' THEN '商品返利'
WHEN '2' THEN '其他'
END 订单返利方式,
case o.is_rebate
WHEN '0' THEN '否'
WHEN '1' THEN '是'
END 订单是否已返利,
o.buyer_name 买家, 
o.goods_rebate_amount 消费奖励,
mup1.member_name UP1用户,
o.one_rebate UP1推广奖励,
o.two_rebate UP2推广奖励,
o.three_rebate UP3推广奖励,
pa.partner_name 平台合作方,
m1.member_rebate_rate 平台提成比例,
o.platform_rebate 平台合作方推广提成,
sa.sa_name 区域合作方,
sa.sa_areaname 授权区域,
sa.sa_rate 区域提成比例,
o.area_rebate 区域合作方推广提成,
o.order_amount 订单总金额（已减代金券）,
o.order_tax 订单税金,
rcb_amount 充值卡支付金额,
pd_amount 预付款支付金额,
shipping_fee 运费,
CASE o.order_state 
WHEN '0' THEN '已取消'
WHEN '10' THEN '未付款'
WHEN '20' THEN '已付款'
WHEN '30' THEN '已发货'
WHEN '40' THEN '已收货'
END 订单状态,
					CASE o.refund_state 
					WHEN '1' THEN '部分退款'
					WHEN '2' THEN '全部退款'
					END 是否退款,
FROM_UNIXTIME(finnshed_time,'%Y-%m-%d %H:%i:%S') 收货时间,
CONCAT('Pay-',pay_sn) 'Pay-支付单号',
CONCAT('Tra-',trade_no) 'Tra-交易号',
case o.payment_code
WHEN 'online' THEN '在线支付'
WHEN 'bocomm' THEN '银联支付'
WHEN 'alipay' THEN '支付宝'
WHEN 'wxpay' THEN '微信公众号支付'
WHEN 'wxapppay' THEN '微信APP支付'
WHEN 'offline' THEN '货到付款'
WHEN 'chinabank' THEN '网银在线'
WHEN 'predeposit' THEN '站内余额支付'
WHEN 'lakala' THEN '拉卡拉支付'
WHEN 'ccbpay' THEN '建行支付'
WHEN 'tonglian' THEN '通联支付'
ELSE '其他支付方式'
END 支付方式,
FROM_UNIXTIME(o.payment_time,'%Y-%m-%d %H:%i:%S')  付款时间,

order_amount 买家支付金额,
CONCAT('DJQ-',oc.voucher_code) 'DJQ-代金券编码',oc.voucher_price 代金券面额,
CONCAT(e.e_name,'-',shipping_code) 物流公司及单号,FROM_UNIXTIME(shipping_time,'%Y-%m-%d %H:%i:%S') 发货时间,
a1.area_name 收货省,a2.area_name 收货市,a3.area_name 收货区域,oc.reciver_name 收货人,
g.goods_serial 商品编码,g.goods_name 商品名称,g.goods_rebate_rate 商品最新返利率, g.goods_hscode HS编码,
CASE g.store_from 
WHEN 1 THEN '网购保税进口'
WHEN 2 THEN '直购进口'
WHEN 3 THEN '海外行邮直邮'
WHEN 4 THEN '外贸进口'
WHEN 5 THEN '国内贸易'
WHEN 6 THEN 'B2B跨境进口'
WHEN 8 THEN '三方保税供货'
END 商品来源,
og.goods_price 商品价格,
og.goods_num 商品数量,
og.goods_price*og.goods_num 商品货款,
og.goods_taxes 商品税金,og.goods_pay_price 商品成交金额,g.goods_storage 商品最新库存,su.supplier_name 供货商,

CASE og.goods_type
WHEN 1 THEN '默认'
WHEN 2 THEN '团购'
WHEN 3 THEN '限时折扣商品'
WHEN 4 THEN '组合套装'
WHEN 5 THEN '赠品'
END 商品类型,
re.address_name 发货仓库


from 33hao_order o LEFT JOIN 33hao_order_goods og ON o.order_id=og.order_id
LEFT JOIN 33hao_goods g ON g.goods_id=og.goods_id
LEFT JOIN 33hao_order_common oc ON oc.order_id=o.order_id
LEFT JOIN 33hao_express e ON e.id=oc.shipping_express_id
LEFT JOIN 33hao_member m ON m.member_id = o.buyer_id 
LEFT JOIN 33hao_member mup1 ON m.refer_id = mup1.member_id 
LEFT JOIN 33hao_partner pa ON o.platform_member_id=pa.member_id 
LEFT JOIN 33hao_member m1 ON m1.member_id =pa.member_id
LEFT JOIN 33hao_sales_area sa ON o.area_member_id = sa.sa_manager_id
LEFT JOIN 33hao_area a1 ON a1.area_id = oc.reciver_province_id
LEFT JOIN 33hao_area a2 ON a2.area_id = oc.reciver_city_id
LEFT JOIN 33hao_area a3 ON a3.area_id = reciver_area_id
LEFT JOIN 33hao_mess_supplier_code su ON su.code_id=g.supplier_code
LEFT JOIN 33hao_transport tr ON transport_id=tr.id
LEFT JOIN 33hao_region re ON re.id=tr.region_id


					where 
					o.order_state >-1
					AND o.add_time > UNIX_TIMESTAMP('".$start_time." 00:00:00')
					AND o.add_time < UNIX_TIMESTAMP('".$end_time." 23:59:59')
					ORDER BY o.add_time,o.store_id"); 

					
				$statlist['headertitle'] = array('订单时间','店铺名称','订单编号','外部订单号','订单返利方式','订单是否已返利','买家','消费奖励','UP1用户','UP1推广奖励','UP2推广奖励','UP3推广奖励','平台合作方','平台提成比例','平台合作方推广提成','区域合作方','授权区域','区域提成比例','区域合作方推广提成','订单总金额（已减代金券）','订单税金','充值卡支付金额','预付款支付金额','运费','订单状态','是否退款','订单收货时间','Pay-支付单号','Tra-交易号','支付方式','付款时间','买家支付金额','DJQ-代金券编码','代金券面额','物流公司及单号','发货时间','收货省','收货市','收货区域','收货人','商品编码','商品名称','商品最新返利率','HS编码','商品来源','商品价格','商品数量','商品货款','商品税金','商品成交金额','商品最新库存','供货商','商品类型','发货仓库');

				foreach($order_list as $k => $val){
					$refer_id = Model('member')->superior_member(array('member_id'=>$val['销售员ID']));
					
					if($refer_id[0]['is_seller'] == 0 && $refer_id[0]['refer_id']){
						$ref_id = Model('member')->superior_member(array('member_id'=>$refer_id[0]['refer_id']));
						$order_list[$k]['销售员ID'] = $ref_id[0]['member_id'];
						$order_list[$k]['销售员'] = $ref_id[0]['member_name'];
					}else if($refer_id[0]['is_seller'] == 0 && $refer_id[0]['refer_id'] == 0){
						$order_list[$k]['销售员ID'] = '';
						$order_list[$k]['销售员'] = '';
					}
					
				}
				$statlist['data']  = $order_list;
				
				//导出Excel
				import('libraries.excel');
				$excel_obj = new Excel();
				$excel_data = array();
				//设置样式
				$excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'11','Bold'=>'1')));
				
				//头部列标题
				foreach ($statlist['headertitle'] as $v){
					$excel_data[0][] = array('styleid'=>'s_title','data'=>$v);
				}

				
				foreach ($statlist['data'] as $k=>$v){
					$excel_data[$k+1][] = array('data'=>$v['订单时间']);
					$excel_data[$k+1][] = array('data'=>$v['店铺名称']);
					$excel_data[$k+1][] = array('data'=>$v['订单编号']);
					$excel_data[$k+1][] = array('data'=>$v['外部订单号']);
					$excel_data[$k+1][] = array('data'=>$v['订单返利方式']);
					$excel_data[$k+1][] = array('data'=>$v['订单是否已返利']);
					$excel_data[$k+1][] = array('data'=>$v['买家']);
					$excel_data[$k+1][] = array('data'=>$v['消费奖励']);
					$excel_data[$k+1][] = array('data'=>$v['UP1用户']);
					$excel_data[$k+1][] = array('data'=>$v['UP1推广奖励']);
					$excel_data[$k+1][] = array('data'=>$v['UP2推广奖励']);
					$excel_data[$k+1][] = array('data'=>$v['UP3推广奖励']);
					$excel_data[$k+1][] = array('data'=>$v['平台合作方']);
					$excel_data[$k+1][] = array('data'=>$v['平台提成比例']);
					$excel_data[$k+1][] = array('data'=>$v['平台合作方推广提成']);
					$excel_data[$k+1][] = array('data'=>$v['区域合作方']);
					$excel_data[$k+1][] = array('data'=>$v['授权区域']);
					$excel_data[$k+1][] = array('data'=>$v['区域提成比例']);
					$excel_data[$k+1][] = array('data'=>$v['区域合作方推广提成']);
					$excel_data[$k+1][] = array('data'=>$v['订单总金额（已减代金券）']);
					$excel_data[$k+1][] = array('data'=>$v['订单税金']);
					$excel_data[$k+1][] = array('data'=>$v['充值卡支付金额']);
					$excel_data[$k+1][] = array('data'=>$v['预付款支付金额']);
					$excel_data[$k+1][] = array('data'=>$v['运费']);
					$excel_data[$k+1][] = array('data'=>$v['订单状态']);
					$excel_data[$k+1][] = array('data'=>$v['是否退款']);
					$excel_data[$k+1][] = array('data'=>$v['收货时间']);
					$excel_data[$k+1][] = array('data'=>$v['Pay-支付单号']);
					$excel_data[$k+1][] = array('data'=>$v['Tra-交易号']);
					$excel_data[$k+1][] = array('data'=>$v['支付方式']);
					$excel_data[$k+1][] = array('data'=>$v['付款时间']);
					$excel_data[$k+1][] = array('data'=>$v['买家支付金额']);
					$excel_data[$k+1][] = array('data'=>$v['DJQ-代金券编码']);
					$excel_data[$k+1][] = array('data'=>$v['代金券面额']);
					$excel_data[$k+1][] = array('data'=>$v['物流公司及单号']);
					$excel_data[$k+1][] = array('data'=>$v['发货时间']);
					$excel_data[$k+1][] = array('data'=>$v['收货省']);
					$excel_data[$k+1][] = array('data'=>$v['收货市']);
					$excel_data[$k+1][] = array('data'=>$v['收货区域']);
					$excel_data[$k+1][] = array('data'=>$v['收货人']);
					$excel_data[$k+1][] = array('data'=>$v['商品编码']);
					$excel_data[$k+1][] = array('data'=>$v['商品名称']);
					$excel_data[$k+1][] = array('data'=>$v['商品最新返利率']);
					$excel_data[$k+1][] = array('data'=>$v['HS编码']);
					$excel_data[$k+1][] = array('data'=>$v['商品来源']);
					$excel_data[$k+1][] = array('data'=>$v['商品价格']);
					$excel_data[$k+1][] = array('data'=>$v['商品数量']);
					$excel_data[$k+1][] = array('data'=>$v['商品货款']);
					$excel_data[$k+1][] = array('data'=>$v['商品税金']);
					$excel_data[$k+1][] = array('data'=>$v['商品成交金额']);
					$excel_data[$k+1][] = array('data'=>$v['商品最新库存']);
					$excel_data[$k+1][] = array('data'=>$v['供货商']);
					$excel_data[$k+1][] = array('data'=>$v['商品类型']);
					$excel_data[$k+1][] = array('data'=>$v['发货仓库']);
				}
				// $excel_data[count($statlist['data'])+1][] = array('data'=>'总计');
				// $excel_data[count($statlist['data'])+1][] = array('format'=>'Number','data'=>$count_arr['up']);
				// $excel_data[count($statlist['data'])+1][] = array('format'=>'Number','data'=>$count_arr['curr']);
				// $excel_data[count($statlist['data'])+1][] = array('data'=>$count_arr['tbrate']);
				$excel_data = $excel_obj->charset($excel_data,CHARSET);
				$excel_obj->addArray($excel_data);
				$excel_obj->addWorksheet($excel_obj->charset('订单流水表',CHARSET));
				$excel_obj->generateXML($excel_obj->charset('订单流水表',CHARSET).$start_time.'至'.$end_time);
				exit();
				
			}elseif ($_POST['action']=='order_list') {
				$start_time = $_POST['start_time'];
				$end_time = $_POST['end_time'];
				if(!$start_time || !$end_time){
					exit("请务必输入起止时间");
				}
				$statlist = array();
				
				$order_list = DB::getAll("select 
					FROM_UNIXTIME(a.add_time,'%Y-%m-%d %H:%i:%S') 订单时间,
					a.store_name 店铺名称,
					a.order_sn 订单编号,
					a.out_trade_no 外部订单号（三方分销）,
					pa.partner_name 三方分销平台合作方,
					CASE a.order_distinguish 
					WHEN '0' THEN '个人返利'
					WHEN '1' THEN '商品返利'
					WHEN '2' THEN '其他'
					END 订单返利方式,
					sa.sa_id 销售团队ID,sa.sa_name 销售团队名称,
					CASE f.is_manager 
					WHEN '0' THEN '否'
					WHEN '10' THEN '是'
					END 销售员是否管理者,
					f.member_id 销售员ID,
					f.member_name 销售员,
					a.goods_rebate_amount 订单返利金额,
					case a.is_rebate
					WHEN '0' THEN '否'
					WHEN '1' THEN '是'
					END 订单是否已返利,
					a.buyer_name 买家, 
					order_amount 订单总金额（已减代金券）,
					order_tax 订单税金,
					rcb_amount 充值卡支付金额,
					pd_amount 预付款支付金额,
					shipping_fee 运费,
					CASE a.order_state 
					WHEN '0' THEN '已取消'
					WHEN '10' THEN '未付款'
					WHEN '20' THEN '已付款'
					WHEN '30' THEN '已发货'
					WHEN '40' THEN '已收货'
					END 订单状态,
					
					CASE a.refund_state 
					WHEN '1' THEN '部分退款'
					WHEN '2' THEN '全部退款'
					END 是否退款,

					FROM_UNIXTIME(finnshed_time,'%Y-%m-%d %H:%i:%S') 订单收货时间,
					CONCAT('Pay-',pay_sn) 'Pay-支付单号',
					CONCAT('Tra-',trade_no) 'Tra-支付宝交易号',
					case payment_code
					WHEN 'online' THEN '在线支付online'
					WHEN 'bocomm' THEN '银联支付bocomm'
					WHEN 'alipay' THEN '支付宝alipay'
					WHEN 'wxpay' THEN '微信支付wxpay'
					WHEN 'offline' THEN '货到付款offline'
					WHEN 'chinabank' THEN '网银在线chinabank'
					WHEN 'predeposit' THEN '站内余额支付predeposit'
					WHEN 'lakala' THEN '拉卡拉支付'
					WHEN 'ccbpay' THEN '建行支付'
					WHEN 'tonglian' THEN '通联支付'
					ELSE '其他支付方式'
					END 支付方式,
					FROM_UNIXTIME(payment_time,'%Y-%m-%d %H:%i:%S')  付款时间,

					order_amount 买家支付金额,
					CONCAT('DJQ-',d.voucher_code) 'DJQ-代金券编码',d.voucher_price 代金券面额,
					CONCAT(e.e_name,'-',shipping_code) 物流公司及单号,FROM_UNIXTIME(shipping_time,'%Y-%m-%d %H:%i:%S') 发货时间,
					a1.area_name 收货省,a2.area_name 收货市,a3.area_name 收货区域,d.reciver_name 收货人,
					su.supplier_name 供货商,
					c.goods_serial 商品编码,c.goods_name 商品名称,b.goods_name 订单商品名称,c.post_tax_no 行邮税号,c.goods_hscode HS编码,
					CASE c.store_from 
					WHEN 1 THEN '网购保税进口'
					WHEN 2 THEN '直购进口'
					WHEN 3 THEN '海外行邮直邮'
					WHEN 4 THEN '外贸进口'
					WHEN 5 THEN '国内贸易'
					WHEN 6 THEN 'B2B跨境进口'
					WHEN 8 THEN '三方保税供货'
					END 商品来源,
					b.goods_price 商品价格,
					b.goods_num 商品数量,
					b.goods_price*b.goods_num 商品货款,
					b.taxes_total 商品税金,b.goods_pay_price 商品成交金额（已减代金券金额）,c.goods_storage 商品最新库存,

					CASE b.goods_type
					WHEN 1 THEN ''
					WHEN 2 THEN '天天特价'
					WHEN 3 THEN '超低折扣'
					WHEN 4 THEN '组合套装'
					WHEN 5 THEN '赠品'
					WHEN 6 THEN '满减活动'
					END 商品活动类型,
					re.address_name 发货仓库

					from 33hao_order a LEFT JOIN 33hao_order_goods b ON a.order_id=b.order_id
					LEFT JOIN 33hao_goods c ON c.goods_id=b.goods_id    -- 33hao_goods_repeat
					LEFT JOIN 33hao_order_common d ON d.order_id=a.order_id
					LEFT JOIN 33hao_express e ON e.id=d.shipping_express_id
					LEFT JOIN 33hao_member f ON f.member_id = a.buyer_id
					LEFT JOIN 33hao_member m1 ON f.refer_id = m1.member_id
					LEFT JOIN 33hao_sales_area sa ON sa.sa_id = f.sa_id
					LEFT JOIN 33hao_area a1 ON a1.area_id = d.reciver_province_id
					LEFT JOIN 33hao_area a2 ON a2.area_id = d.reciver_city_id
					LEFT JOIN 33hao_area a3 ON a3.area_id = reciver_area_id
					LEFT JOIN 33hao_mess_supplier_code su ON su.code_id=c.supplier_code
					LEFT JOIN 33hao_transport tr ON c.transport_id=tr.id
					LEFT JOIN 33hao_region re ON re.id=tr.region_id
					LEFT JOIN 33hao_partner pa ON a.partner_id=pa.partner_id
					where 
					a.order_state >-1
					#AND goods_serial NOT LIKE 'YHT%'
					AND a.add_time > UNIX_TIMESTAMP('".$start_time." 00:00:01')
					AND a.add_time < UNIX_TIMESTAMP('".$end_time." 23:59:59')
					ORDER BY a.add_time,a.store_name"); 
					
				$statlist['headertitle'] = array('订单时间','店铺名称','订单编号','外部订单号','三方分销平台合作方','订单返利方式','销售团队名称','销售员是否管理者','销售员ID','销售员','订单返利金额','订单是否已返利','买家','订单总金额（已减代金券）','订单税金','充值卡支付金额','预付款支付金额','运费','订单状态','是否退款','订单收货时间','Pay-支付单号','Tra-支付宝交易号','支付方式','付款时间','买家支付金额','DJQ-代金券编码','代金券面额','物流公司及单号','发货时间','收货省','收货市','收货区域','收货人','供货商','商品编码','商品名称','订单商品名称','行邮税号','HS编码','商品来源','商品价格','商品数量','商品最终返利率','商品返利金额','商品货款','商品税金','商品成交金额（已减代金券金额）','商品最新库存','商品类型','发货仓库');

				foreach($order_list as $k => $val){
					$refer_id = Model('member')->superior_member(array('member_id'=>$val['销售员ID']));
					
					if($refer_id[0]['is_seller'] == 0 && $refer_id[0]['refer_id']){
						$ref_id = Model('member')->superior_member(array('member_id'=>$refer_id[0]['refer_id']));
						$order_list[$k]['销售员ID'] = $ref_id[0]['member_id'];
						$order_list[$k]['销售员'] = $ref_id[0]['member_name'];
					}else if($refer_id[0]['is_seller'] == 0 && $refer_id[0]['refer_id'] == 0){
						$order_list[$k]['销售员ID'] = '';
						$order_list[$k]['销售员'] = '';
					}
					
					if($val['销售团队名称'] == null){

						if($refer_id[0]['refer_id']){
							$sa_id = Model('member')->superior_member(array('member_id'=>$refer_id[0]['refer_id']));
							$order_list[$k]['销售员ID'] = $sa_id[0]['member_id'];
							$order_list[$k]['销售员'] = $sa_id[0]['member_name'];
							$sa_id = $sa_id[0]['sa_id'];
							if($sa_id ){
								$re = Model('sales_area')->getSalesAreaInfo(array('sa_id'=> $sa_id));
								if(!empty($re)){
									$order_list[$k]['销售团队名称'] = $re['sa_name'];
								}
							}
						}
						
					}
				}
				$statlist['data']  = $order_list;
				
				//导出Excel
				import('libraries.excel');
				$excel_obj = new Excel();
				$excel_data = array();
				//设置样式
				$excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
				
				//头部列标题
				foreach ($statlist['headertitle'] as $v){
					$excel_data[0][] = array('styleid'=>'s_title','data'=>$v);
				}
				
				foreach ($statlist['data'] as $k=>$v){
					$excel_data[$k+1][] = array('data'=>$v['订单时间']);
					$excel_data[$k+1][] = array('data'=>$v['店铺名称']);
					$excel_data[$k+1][] = array('data'=>$v['订单编号']);
					$excel_data[$k+1][] = array('data'=>$v['外部订单号']);
					$excel_data[$k+1][] = array('data'=>$v['三方分销平台合作方']);
					$excel_data[$k+1][] = array('data'=>$v['订单返利方式']);
					$excel_data[$k+1][] = array('data'=>$v['销售团队名称']);
					$excel_data[$k+1][] = array('data'=>$v['销售员是否管理者']);
					$excel_data[$k+1][] = array('data'=>$v['销售员ID']);
					$excel_data[$k+1][] = array('data'=>$v['销售员']);
					$excel_data[$k+1][] = array('data'=>$v['订单返利金额']);
					$excel_data[$k+1][] = array('data'=>$v['订单是否已返利']);
					$excel_data[$k+1][] = array('data'=>$v['买家']);
					$excel_data[$k+1][] = array('data'=>$v['订单总金额（已减代金券）']);
					$excel_data[$k+1][] = array('data'=>$v['订单税金']);
					$excel_data[$k+1][] = array('data'=>$v['充值卡支付金额']);
					$excel_data[$k+1][] = array('data'=>$v['预付款支付金额']);
					$excel_data[$k+1][] = array('data'=>$v['运费']);
					$excel_data[$k+1][] = array('data'=>$v['订单状态']);
					$excel_data[$k+1][] = array('data'=>$v['是否退款']);
					$excel_data[$k+1][] = array('data'=>$v['订单收货时间']);
					$excel_data[$k+1][] = array('data'=>$v['Pay-支付单号']);
					$excel_data[$k+1][] = array('data'=>$v['Tra-支付宝交易号']);
					$excel_data[$k+1][] = array('data'=>$v['支付方式']);
					$excel_data[$k+1][] = array('data'=>$v['付款时间']);
					$excel_data[$k+1][] = array('data'=>$v['买家支付金额']);
					$excel_data[$k+1][] = array('data'=>$v['DJQ-代金券编码']);
					$excel_data[$k+1][] = array('data'=>$v['代金券面额']);
					$excel_data[$k+1][] = array('data'=>$v['物流公司及单号']);
					$excel_data[$k+1][] = array('data'=>$v['发货时间']);
					$excel_data[$k+1][] = array('data'=>$v['收货省']);
					$excel_data[$k+1][] = array('data'=>$v['收货市']);
					$excel_data[$k+1][] = array('data'=>$v['收货区域']);
					$excel_data[$k+1][] = array('data'=>$v['收货人']);
					$excel_data[$k+1][] = array('data'=>$v['供货商']);
					$excel_data[$k+1][] = array('data'=>$v['商品编码']);
					$excel_data[$k+1][] = array('data'=>$v['商品名称']);
					$excel_data[$k+1][] = array('data'=>$v['订单商品名称']);
					$excel_data[$k+1][] = array('data'=>$v['行邮税号']);
					$excel_data[$k+1][] = array('data'=>$v['HS编码']);
					$excel_data[$k+1][] = array('data'=>$v['商品来源']);
					$excel_data[$k+1][] = array('data'=>$v['商品价格']);
					$excel_data[$k+1][] = array('data'=>$v['商品数量']);
					$excel_data[$k+1][] = array('data'=>$v['商品最终返利率']);
					$excel_data[$k+1][] = array('data'=>$v['商品返利金额']);
					$excel_data[$k+1][] = array('data'=>$v['商品货款']);
					$excel_data[$k+1][] = array('data'=>$v['商品税金']);
					$excel_data[$k+1][] = array('data'=>$v['商品成交金额（已减代金券金额）']);
					$excel_data[$k+1][] = array('data'=>$v['商品最新库存']);
					$excel_data[$k+1][] = array('data'=>$v['商品类型']);
					$excel_data[$k+1][] = array('data'=>$v['发货仓库']);
				}
				// $excel_data[count($statlist['data'])+1][] = array('data'=>'总计');
				// $excel_data[count($statlist['data'])+1][] = array('format'=>'Number','data'=>$count_arr['up']);
				// $excel_data[count($statlist['data'])+1][] = array('format'=>'Number','data'=>$count_arr['curr']);
				// $excel_data[count($statlist['data'])+1][] = array('data'=>$count_arr['tbrate']);
				$excel_data = $excel_obj->charset($excel_data,CHARSET);
				
				$excel_obj->addArray($excel_data);
				$excel_obj->addWorksheet($excel_obj->charset('订单流水表',CHARSET));
				$excel_obj->generateXML($excel_obj->charset('订单流水表',CHARSET).$start_time.'至'.$end_time);
				exit();
				
			}else if($_POST['action']=='member_num'){
				$start_time = $_POST['start_time'];
				$end_time = $_POST['end_time'];
				if(!$start_time || !$end_time){
					exit("请务必输入起止时间");
				}
				$statlist = array();  
				$member_num = DB::getAll("SELECT 
					m.member_id 会员ID,
					m.member_name 会员名称,
					m.member_mobile 手机号码,
					FROM_UNIXTIME(m.member_time,'%Y-%m-%d %H:%i:%S') 注册时间, 
					m.refer_id 上级会员ID,
					m1.member_name 上级会员名称				
					FROM 33hao_member m 
					left join 33hao_member m1 on m.refer_id= m1.member_id
					WHERE m.member_time > UNIX_TIMESTAMP('".$start_time."')
					AND m.member_time < UNIX_TIMESTAMP('".$end_time."')");
					
				$statlist['headertitle'] = array('会员ID','会员名称','手机号码','注册时间','上级会员ID','上级会员名称');
				$statlist['data']  = $member_num;
				//导出Excel
				import('libraries.excel');
				$excel_obj = new Excel();
				$excel_data = array();
				//设置样式
				$excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
				
				//头部列标题
				foreach ($statlist['headertitle'] as $v){
					$excel_data[0][] = array('styleid'=>'s_title','data'=>$v);
				}
				$num = 0;	
				foreach ($statlist['data'] as $k=>$v){
					$excel_data[$k+1][] = array('data'=>$v['会员ID']);
					$excel_data[$k+1][] = array('data'=>$v['会员名称']);
					$excel_data[$k+1][] = array('data'=>$v['手机号码']);
					$excel_data[$k+1][] = array('data'=>$v['注册时间']);
					$excel_data[$k+1][] = array('data'=>$v['上级会员ID']);
					$excel_data[$k+1][] = array('data'=>$v['上级会员名称']);
					$num++ ;
				}
				
				$excel_data[count($statlist['data'])+1][] = array('data'=>'总计注册量');
				$excel_data[count($statlist['data'])+1][] = array('format'=>'Number','data'=>$num);
				$excel_data = $excel_obj->charset($excel_data,CHARSET);
				$excel_obj->addArray($excel_data);
				$excel_obj->addWorksheet($excel_obj->charset('会员注册量',CHARSET));
				$excel_obj->generateXML($excel_obj->charset('会员注册量',CHARSET).$start_time.'至'.$end_time);
				exit();
				
			}else if($_POST['action']=='goods_pricing'){
				$statlist = array();  
				$goods_pricing = DB::getAll("SELECT 
					a.goods_serial 商品编码,
					a.goods_name 商品名称,
					a.goods_price 售价,
					a.goods_marketprice 市场价,
					CONCAT(b.gc_name,'-',e.gc_name,'-',f.gc_name) 分类,
					case a.transport_id 
						WHEN '2' THEN '光彩全球重庆空港保税仓'
						WHEN '6' THEN '光彩全球重庆仓'
						WHEN '7' THEN '光彩全球重庆西永保税仓'
						WHEN '8' THEN '光彩全球进口B2B'
						WHEN '9' THEN '光彩全球香港直邮店'
						WHEN '10' THEN '光彩全球法国直邮店'
						WHEN '11' THEN '光彩全球进口车旗舰店'
						WHEN '12' THEN '光彩谋思局日本店'
						WHEN '13' THEN '光彩全球寸滩保税仓'
						WHEN '14' THEN '光彩全球美国直邮'
						WHEN '15' THEN '光彩全球西永仓1号店'
						WHEN '16' THEN '泰国素万乳胶寝具'
						WHEN '17' THEN '光彩绵阳仓'
					ELSE '重庆保税仓'
					END 发货仓,
					a.goods_rebate_rate 返利率,
					a.goods_weight 商品重量,
					a.goods_jingle 卖点,
					d.name 进口国,
					a.goods_hscode HS编码,
					c.consumption_tax 消费税,
					c.vat_tax 增值税 
					from 33hao_goods a LEFT JOIN 33hao_goods_class b ON a.gc_id_1=b.gc_id 
					LEFT JOIN 33hao_goods_class e ON a.gc_id_2=e.gc_id
					LEFT JOIN 33hao_goods_class f ON a.gc_id_3=f.gc_id
					LEFT JOIN 33hao_tax_rate c ON a.goods_hscode=c.hs_code
					LEFT JOIN 33hao_mess_country_code d ON a.country_code=d.code
					where 
					a.goods_state =1 
					AND goods_serial NOT LIKE 'YHT%'");
					
				$statlist['headertitle'] = array('商品编码','商品名称','售价','市场价','分类','发货仓','返利率','商品重量','卖点','进口国','HS编码','消费税','增值税','备注');
				foreach($goods_pricing as $key =>$val){
					$goods_pricing[$key]['分类'] = str_replace("&amp;gt;","",$val['分类']);
				}
				$statlist['data']  = $goods_pricing;
				//导出Excel
				import('libraries.excel');
				$excel_obj = new Excel();
				$excel_data = array();
				//设置样式
				$excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
				
				//头部列标题
				foreach ($statlist['headertitle'] as $v){
					$excel_data[0][] = array('styleid'=>'s_title','data'=>$v);
				}	
				foreach ($statlist['data'] as $k=>$v){
					$excel_data[$k+1][] = array('data'=>$v['商品编码']);
					$excel_data[$k+1][] = array('data'=>$v['商品名称']);
					$excel_data[$k+1][] = array('data'=>$v['售价']);
					$excel_data[$k+1][] = array('data'=>$v['市场价']);
					$excel_data[$k+1][] = array('data'=>$v['分类']);
					$excel_data[$k+1][] = array('data'=>$v['发货仓']);
					$excel_data[$k+1][] = array('data'=>$v['返利率']);
					$excel_data[$k+1][] = array('data'=>$v['商品重量']);
					$excel_data[$k+1][] = array('data'=>$v['卖点']);
					$excel_data[$k+1][] = array('data'=>$v['进口国']);
					$excel_data[$k+1][] = array('data'=>$v['HS编码']);
					$excel_data[$k+1][] = array('data'=>$v['消费税']);
					$excel_data[$k+1][] = array('data'=>$v['增值税']);
					$excel_data[$k+1][] = array('data'=>$v['备注']);
				}
				$excel_data = $excel_obj->charset($excel_data,CHARSET);
				$excel_obj->addArray($excel_data);
				$excel_obj->addWorksheet($excel_obj->charset('产品定价表',CHARSET));
				$excel_obj->generateXML($excel_obj->charset('产品定价表',CHARSET).date('Y-m-d', time()));
				exit();
			}else if($_POST['action']=='goods_storage'){
				$statlist = array();  
				$goods_storage = DB::getAll("SELECT 
					a.goods_name 商品名称,
					a.goods_serial 商品货号,
					a.goods_price 商品价格,
					a.goods_storage 商品库存,
					a.goods_storage_alarm 库存预警值,
					case a.transport_id 
						WHEN '2' THEN '光彩全球重庆空港保税仓'
						WHEN '6' THEN '光彩全球重庆仓'
						WHEN '7' THEN '光彩全球重庆西永保税仓'
						WHEN '8' THEN '光彩全球进口B2B'
						WHEN '9' THEN '光彩全球香港直邮店'
						WHEN '10' THEN '光彩全球法国直邮店'
						WHEN '11' THEN '光彩全球进口车旗舰店'
						WHEN '12' THEN '光彩谋思局日本店'
						WHEN '13' THEN '光彩全球寸滩保税仓'
						WHEN '14' THEN '光彩全球美国直邮'
						WHEN '15' THEN '光彩全球西永仓1号店'
						WHEN '16' THEN '泰国素万乳胶寝具'
						WHEN '17' THEN '光彩绵阳仓'
						ELSE '重庆保税仓'
					END 仓库名称,
					CONCAT(b.gc_name,'-',c.gc_name,'-',d.gc_name) 商品分类,
					a.goods_valite_time 有效日期,
					/*CASE a.goods_valite_time 
					WHEN 0 THEN ''
					ELSE FROM_UNIXTIME(a.goods_valite_time,'%Y-%m-%d %H:%i:%S')
					END 有效日期,*/
					
					FROM_UNIXTIME(a.goods_addtime,'%Y-%m-%d %H:%i:%S') 上架时间,
					CASE a.store_from 
						WHEN 1 THEN '网购保税进口'
						WHEN 2 THEN '直购进口'
						WHEN 3 THEN '海外直邮'
						WHEN 4 THEN '外贸进口'
						WHEN 5 THEN '国内贸易'
						WHEN 6 THEN 'B2B跨境进口'
						WHEN 8 THEN '三方保税供货'
					END 商品来源,
					e.NAME 包装单位,
					f.name 原产国
					
					from 33hao_goods a LEFT JOIN 33hao_goods_class b ON a.gc_id_1 = b.gc_id 
							LEFT JOIN 33hao_goods_class c ON a.gc_id_2 = c.gc_id
							LEFT JOIN 33hao_goods_class d ON a.gc_id_3 = d.gc_id
							LEFT JOIN 33hao_legal_unit  e ON a.pack_units = e.CODE
							LEFT JOIN 33hao_mess_country_code f ON a.country_code=f.code
					where a.goods_state =1 ");
					
				$statlist['headertitle'] = array('商品名称','商品货号','商品价格','商品库存','库存预警值','仓库名称','商品分类','剩余有效期天数','上架时间','商品来源','包装单位','原产国');
				foreach($goods_storage as $k => $val){
					if($val['有效日期'] >0){
						$goods_storage[$k]['有效日期'] = round(($val['有效日期']-time())/3600/24);
					}else{
						$goods_storage[$k]['有效日期'] = '';
					}
				}
				$statlist['data']  = $goods_storage;
				//导出Excel
				import('libraries.excel');
				$excel_obj = new Excel();
				$excel_data = array();
				//设置样式
				$excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
				
				//头部列标题
				foreach ($statlist['headertitle'] as $v){
					$excel_data[0][] = array('styleid'=>'s_title','data'=>$v);
				}	
				foreach ($statlist['data'] as $k=>$v){
					$excel_data[$k+1][] = array('data'=>$v['商品名称']);
					$excel_data[$k+1][] = array('data'=>$v['商品货号']);
					$excel_data[$k+1][] = array('data'=>$v['商品价格']);
					$excel_data[$k+1][] = array('data'=>$v['商品库存']);
					$excel_data[$k+1][] = array('data'=>$v['库存预警值']);
					$excel_data[$k+1][] = array('data'=>$v['仓库名称']);
					$excel_data[$k+1][] = array('data'=>$v['商品分类']);
					$excel_data[$k+1][] = array('data'=>$v['有效日期']);
					$excel_data[$k+1][] = array('data'=>$v['上架时间']);
					$excel_data[$k+1][] = array('data'=>$v['商品来源']);
					$excel_data[$k+1][] = array('data'=>$v['包装单位']);
					$excel_data[$k+1][] = array('data'=>$v['原产国']);
				}
				$excel_data = $excel_obj->charset($excel_data,CHARSET);
				$excel_obj->addArray($excel_data);
				$excel_obj->addWorksheet($excel_obj->charset('商品库存表',CHARSET));
				$excel_obj->generateXML($excel_obj->charset('商品库存表',CHARSET).date('Y-m-d', time()));
				exit();
				
			}else if($_POST['action']=='specified'){
				$reciver_name = $_POST['reciver_name'];
				$reciver_address = $_POST['reciver_address'];
				
				if(!$reciver_name && !$reciver_address){
					exit("请输入收货人或者收货地区");
				}
				
				$statlist = array();  
				$specified = DB::getAll("SELECT 
					FROM_UNIXTIME(a.add_time,'%Y-%m-%d %H:%i:%S') 订单时间,
					a.order_sn 订单编号,
					b.reciver_name 收货人,
					b.reciver_info 收货信息,
					c.goods_name 商品名称,
					d.goods_serial 商品货号,
					c.goods_num 商品数量,
				    c.goods_price 商品价格
					From 33hao_order a LEFT JOIN 33hao_order_common b ON a.order_id = b.order_id 
					LEFT JOIN 33hao_order_goods c ON a.order_id = c.order_id 
					LEFT JOIN 33hao_goods d ON d.goods_id = c.goods_id 
					WHERE b.reciver_name ='".$reciver_name."' or reciver_info LIKE '".$reciver_address."'");
				
				$statlist['headertitle'] = array('订单时间','订单编号','收货人','收货地址','联系电话','商品名称','商品货号','商品数量','商品价格');
				
				foreach($specified as $k =>$val){
					$reciver_info = unserialize($val['收货信息']);
					$specified[$k]['收货地址'] = $reciver_info['address'];
					$specified[$k]['联系电话'] = $reciver_info['phone'];
				}
				$statlist['data']  = $specified;
				//导出Excel
				import('libraries.excel');
				$excel_obj = new Excel();
				$excel_data = array();
				//设置样式
				$excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
				
				//头部列标题
				foreach ($statlist['headertitle'] as $v){
					$excel_data[0][] = array('styleid'=>'s_title','data'=>$v);
				}	
				foreach ($statlist['data'] as $k=>$v){
					$excel_data[$k+1][] = array('data'=>$v['订单时间']);
					$excel_data[$k+1][] = array('data'=>$v['订单编号']);
					$excel_data[$k+1][] = array('data'=>$v['收货人']);
					$excel_data[$k+1][] = array('data'=>$v['收货地址']);
					$excel_data[$k+1][] = array('data'=>$v['联系电话']);
					$excel_data[$k+1][] = array('data'=>$v['商品名称']);
					$excel_data[$k+1][] = array('data'=>$v['商品货号']);
					$excel_data[$k+1][] = array('data'=>$v['商品数量']);
					$excel_data[$k+1][] = array('data'=>$v['商品价格']);
				}
				$excel_data = $excel_obj->charset($excel_data,CHARSET);
				$excel_obj->addArray($excel_data);
				$excel_obj->addWorksheet($excel_obj->charset('指定信息订单表',CHARSET));
				$excel_obj->generateXML($excel_obj->charset('指定信息订单表',CHARSET).date('Y-m-d', time()));
				exit();
				
			}
		Tpl::showpage('exportexcel.index');
		//set_time_limit(300);
		//header("Content-type: text/html; charset=utf-8");
        //try {
        //}catch (Exception $e){
        //    $model->rollback();
        //    die($e->getMessage());
        //}

    }
}


