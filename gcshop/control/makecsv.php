<?php

/**
 * 导出报文
 *
 */
defined('GcWebShop') or exit('Access Invalid!');

class makecsvControl extends BaseApiControl{
	
	/* 
	 * 导出报文
	 */
	public function indexOp(){
		set_time_limit(300);
		header("Content-type: text/html; charset=utf-8");

		//获取发货仓
		$model_region = Model('region');
		$regions = $model_region->getRegions();
		//Tpl::output('regions', $regions);

		//获取模版
		$model_transport = Model('transport');
		$transports = $model_transport->getTransports();
		//Tpl::output('transports', $transports);
		
		//获取计量单位
		$model_unit = Model('legal_unit');
		$unit = $model_unit->getLegalUnit();
		
        try {
            $model = Model('order');
            $model->beginTransaction();
			
			
			
			//导出CSV 
			if ($_POST['action']=='export') {
			
				$orders = DB::getAll("select * from 33hao_mess_order_info where MAKE_CSV = '10' and OIF_ORDER_NO<>'' and OIF_BAR_CODE<>'' order by ORDER_SN asc"); 
				//$head_string = chr(0xEF).chr(0xBB).chr(0xBF);
				$head_string .= "客户,订单号,海关条码,真实快递单号,收货人,收货人城市,收货人详细地址,邮编,联系人,联系电话,订单总金额,订单总税款金额,是否加急,货品,数量,单价,总价,税款金额,2.0订单号,EXT01,EXT02,EXT03,EXT04,EXT05,包装单位\n"; 

				$orders = !empty($orders) ? $orders : array();
				$body_string = array();
				if ($orders) {

					$model_goods = Model('goods');
					foreach($orders as $row){
						//查询发货仓信息
						$region_info = $transport_info = array();
						$goods_info = $model_goods->getGoodsInfo(array('goods_id'=>$row['GOODS_ID']), 'transport_id');
						if(isset($goods_info['transport_id'])){
							$transport_info = $transports[$goods_info['transport_id']];
							if(isset($regions[$transport_info['region_id']])){
								$region_info = $regions[$transport_info['region_id']];
							}
						}

						$RECEIVER_INFO = unserialize($row['RECEIVER_INFO']);

						$RECEIVER_AREA = str_replace('	','',str_replace(' ', '', $RECEIVER_INFO['area']));
						$RECEIVER_ADDRESS = str_replace('	','',str_replace(' ', '', $RECEIVER_INFO['address']));
						$RECEIVER_TEL = $RECEIVER_INFO['mob_phone'];
						$CITYCODE = '000000';
						$ORDER_SN = $row['ORDER_SN'];
						$GOODS_NUM = intval($row['GOODS_NUM']);
						$OIF_BAR_CODE = $row['OIF_BAR_CODE'];
						$OIF_ORDER_NO = $row['OIF_ORDER_NO'];
						foreach($unit as $val){
							if($row['GOODS_UNITS'] == $val['CODE']){
								$GOODS_UNITS = $val['NAME'];
							}
						}
						$sel_tax_fee = DB::getAll("select order_sn,CAST(SUM(taxes_total) AS DECIMAL(10,2)) AS TAX_FEE FROM 33hao_mess_order_info WHERE 33hao_mess_order_info.ORDER_SN = '".$ORDER_SN."' group by order_sn");
						$tax = $sel_tax_fee[0];
						/*if (floatval($tax['TAX_FEE']) <= 50){
							$TAX_FEE = 0.00	;
							$ORDER_AMOUNT = floatval($row['ORDER_AMOUNT']);
						} else{
							$TAX_FEE = floatval($tax['TAX_FEE']);
							//$ORDER_AMOUNT = floatval($row['ORDER_AMOUNT'] + $TAX_FEE);订单中已经包含税项
							$ORDER_AMOUNT = floatval($row['ORDER_AMOUNT']);
						}*/
						$TAX_FEE = floatval($tax['TAX_FEE']);
						$ORDER_AMOUNT = floatval($row['ORDER_AMOUNT']);
						$GOODS_FEE = floatval($row['goods_total']);
						$GOODS_TAX = floatval($row['taxes_total']);

						preg_match('/([a-zA-Z]*)([0-9]*)(\-?)([0-9]*)/', $row['SKU'],  $match);
						$SKU = $match[2];
						//临时修改紫菜货号导出报文加 -1
						if($SKU == '8809281480158'){
							$SKU = '8809281480158-1' ;
						}
						$body_string[$region_info['customs_id']] .= "GCEC".",".$ORDER_SN.",".$OIF_BAR_CODE.",".$row['shipping_code'].",".$row['RECEIVER_NAME'].",".$RECEIVER_AREA.",".$RECEIVER_ADDRESS.",".$CITYCODE.",".$row['RECEIVER_NAME'].",".$RECEIVER_TEL.",".$ORDER_AMOUNT.",".$TAX_FEE.","."3".",".$SKU.",".$GOODS_NUM.",".$row['GOODS_PRICE'].",".$GOODS_FEE.",".$GOODS_TAX.",".$OIF_ORDER_NO.","."光彩全球保税".","."".","."".","."".","."".",".$GOODS_UNITS."\n"; 
							
						$update_csv_status = DB::query("update 33hao_mess_order_info set MAKE_CSV = '20' where 33hao_mess_order_info.ORDER_SN = '".$ORDER_SN."' ");
					}
					if(count($body_string) > 1){
						include(BASE_PATH.'/api/mess/common.php');
						@mkdir(BASE_DATA_PATH.'/cache/csv');
						foreach($body_string as $k=>$v){
							$filename = 'GCEC-'.$k.'-'.date('YmdHi').'.csv';
							$file = fopen(BASE_DATA_PATH.'/cache/csv/'.$filename, 'w');
							$output_string = $head_string.$v;
							fwrite($file, $output_string);
							fclose($file);
						}
						$zip = new PHPZip();
						$zip->ZipAndDownload(BASE_DATA_PATH.'/cache/csv', "GCEC-".date('YmdHi').".zip"); //打包并下载 
						$this->deldir(BASE_DATA_PATH.'/cache/csv');
					}else{
						foreach($body_string as $k=>$v){
							$filename = 'GCEC-'.$k.'-'.date('YmdHi').'.csv';
							$output_string .= $head_string.$v;
						}
						$this->export_csv($filename,$output_string); //导出
					}

				
				} else {
					echo "暂无订单数据！";
				}
				
			}else{
				?>
<form method="post" action="" id="form_login">
	<input type="hidden" name="action" value="export">
	<!--<div class="input">
		<label>输入需要导入的ORDER_ID，以换行问分割</label><br/>
		<textarea name="ORDER_SN"></textarea>
	</div>-->
	<div class="input">
		<input type="submit" value="导出CSV文件" class="login-submit">
	</div>
</form>
				<?php
			}
			
			
			
			

			

            $model->commit();
        }catch (Exception $e){
            $model->rollback();
            die($e->getMessage());
        }
    }
	
	/* 
	 * 导出报文2
	 */
	public function index2Op(){
		set_time_limit(300);
		header("Content-type: text/html; charset=utf-8");

		//获取发货仓
		$model_region = Model('region');
		$regions = $model_region->getRegions();
		//Tpl::output('regions', $regions);

		//获取模版
		$model_transport = Model('transport');
		$transports = $model_transport->getTransports();
		//Tpl::output('transports', $transports);

        try {
            $model = Model('order');
            $model->beginTransaction();
			
			
			
			//导出CSV 
			$order_sn = array();
			$_POST['ORDER_SN'] = explode("\n", $_POST['ORDER_SN']);
			foreach($_POST['ORDER_SN'] as $k=>$v){
				if($v){
					$order_sn[] = '\''.stripslashes(trim($v)).'\'';
				}
			}
			$order_sn_str = join(',', $order_sn);
			if($order_sn_str){
			
				$orders = DB::getAll("select * from 33hao_mess_order_info where ORDER_SN in ($order_sn_str) order by ORDER_SN asc"); 
				//$head_string = chr(0xEF).chr(0xBB).chr(0xBF);
				$head_string .= "客户,订单号,海关条码,真实快递单号,收货人,收货人城市,收货人详细地址,邮编,联系人,联系电话,订单总金额,订单总税款金额,是否加急,货品,数量,单价,总价,税款金额,2.0订单号,EXT01,EXT02,EXT03,EXT04,EXT05,包装单位\n"; 

				$orders = !empty($orders) ? $orders : array();
				$body_string = array();
				if ($orders) {

					$model_goods = Model('goods');
					foreach($orders as $row){
						//查询发货仓信息
						$region_info = $transport_info = array();
						$goods_info = $model_goods->getGoodsInfo(array('goods_id'=>$row['GOODS_ID']), 'transport_id');
						if(isset($goods_info['transport_id'])){
							$transport_info = $transports[$goods_info['transport_id']];
							if(isset($regions[$transport_info['region_id']])){
								$region_info = $regions[$transport_info['region_id']];
							}
						}
						//echo '<pre>';print_r($region_info);exit;

						$RECEIVER_INFO = unserialize($row['RECEIVER_INFO']);

						$RECEIVER_AREA = str_replace('	','',str_replace(' ', '', $RECEIVER_INFO['area']));
						$RECEIVER_ADDRESS = str_replace('	','',str_replace(' ', '', $RECEIVER_INFO['address']));
						$RECEIVER_TEL = $RECEIVER_INFO['mob_phone'];
						$CITYCODE = '000000';
						$ORDER_SN = $row['ORDER_SN'];
						$GOODS_NUM = intval($row['GOODS_NUM']);
						$OIF_BAR_CODE = $row['OIF_BAR_CODE'];
						$OIF_ORDER_NO = $row['OIF_ORDER_NO'];
						$GOODS_UNITS = $row['GOODS_UNITS'];

						$sel_tax_fee = DB::getAll("select order_sn,CAST(SUM(GOODS_RATE*GOODS_PRICE*GOODS_NUM) AS DECIMAL(10,2)) AS TAX_FEE FROM 33hao_mess_order_info WHERE 33hao_mess_order_info.ORDER_SN = '".$ORDER_SN."' group by order_sn");
						$tax = $sel_tax_fee[0];
						if (floatval($tax['TAX_FEE']) <= 50){
							$TAX_FEE = 0.00	;
							$ORDER_AMOUNT = floatval($row['ORDER_AMOUNT']);
						} else{
							$TAX_FEE = floatval($tax['TAX_FEE']);
							//$ORDER_AMOUNT = floatval($row['ORDER_AMOUNT'] + $TAX_FEE);订单中已经包含税项
							$ORDER_AMOUNT = floatval($row['ORDER_AMOUNT']);
						}


						$GOODS_FEE = floatval($row['GOODS_PRICE']*$GOODS_NUM);
						$GOODS_TAX = floatval($GOODS_FEE*$row['GOODS_RATE']);

						preg_match('/([a-zA-Z]*)([0-9]*)(\-?)([0-9]*)/', $row['SKU'],  $match);
						$SKU = $match[2];

							$body_string[$region_info['customs_id']] .= "GCEC".",".$ORDER_SN.",".$OIF_BAR_CODE.","."".",".$row['RECEIVER_NAME'].",".$RECEIVER_AREA.",".$RECEIVER_ADDRESS.",".$CITYCODE.
							
							",".$row['RECEIVER_NAME'].",".$RECEIVER_TEL.",".$ORDER_AMOUNT.",".$TAX_FEE.","."3".",".$SKU.",".$GOODS_NUM.",".$row['GOODS_PRICE'].",".$GOODS_FEE.",".$GOODS_TAX.",".$OIF_ORDER_NO.","."光彩全球保税".","."".","."".","."".","."".",".$GOODS_UNITS."\n"; 
							
						$update_csv_status = DB::query("update 33hao_mess_order_info set MAKE_CSV = '20' where 33hao_mess_order_info.ORDER_SN = '".$ORDER_SN."' ");
					}
					
					if(count($body_string) > 1){
						include(BASE_PATH.'/api/mess/common.php');
						@mkdir(BASE_DATA_PATH.'/cache/csv');
						foreach($body_string as $k=>$v){
							$filename = 'GCEC-'.$k.'-'.date('YmdHi').'.csv';
							$file = fopen(BASE_DATA_PATH.'/cache/csv/'.$filename, 'w');
							$output_string = $head_string.$v;
							fwrite($file, $output_string);
							fclose($file);
						}
						$zip = new PHPZip();
						$zip->ZipAndDownload(BASE_DATA_PATH.'/cache/csv', "GCEC-".date('YmdHi').".zip"); //打包并下载 
						$this->deldir(BASE_DATA_PATH.'/cache/csv');
					}else{
						foreach($body_string as $k=>$v){
							$filename = 'GCEC-'.$k.'-'.date('YmdHi').'.csv';
							$output_string .= $head_string.$v;
						}
						$this->export_csv($filename,$output_string); //导出
					}

				
				} else {
					echo "暂无订单数据！";
				}
				
			}else{
				?>
<form method="post" action="" id="form_login">
	<input type="hidden" name="action" value="export">
	<div class="input">
		<label>输入需要导入的ORDER_ID，以换行问分割</label><br/>
		<textarea name="ORDER_SN"></textarea>
	</div>
	<div class="input">
		<input type="submit" value="导出CSV文件" class="login-submit">
	</div>
</form>
				<?php
			}
			
			
			
			

			

            $model->commit();
        }catch (Exception $e){
            $model->rollback();
            die($e->getMessage());
        }
    }

	/* 
	 * 导出文件
	 */
    function export_csv($filename,$data) { 
        header("Content-type:text/csv"); 
        header("Content-Disposition:attachment;filename=".$filename); 
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0'); 
        header('Expires:0'); 
        header('Pragma:public'); 
        echo $data; 
        exit;
         
    }
	
	/* 
	 * 导出文件
	 */
	function deldir($dir) {
		//先删除目录下的文件：
		$dh=opendir($dir);
		while ($file=readdir($dh)) {
			if($file!="." && $file!="..") {
				$fullpath=$dir."/".$file;
				if(!is_dir($fullpath)) {
					unlink($fullpath);
				} else {
					deldir($fullpath);
				}
			}
		}

		closedir($dh);
		//删除当前文件夹：
		if(rmdir($dir)) {
			return true;
		} else {
			return false;
		}
	}

}
