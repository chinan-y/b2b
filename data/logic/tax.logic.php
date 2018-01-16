<?php
/**
 * 税金计算行为
 *
 * 
 */
defined('GcWebShop') or exit('Access Invalid!');

class taxLogic {

	private $person_information;

	/**
	 * 个人单次购买商品总额不超过2000元
	 */
	private $single_goods_money = 2000;

	/**
	 * 个人年度购买商品总额不得超过20000元
	 */
	private $year_goods_money = 20000;

	/**
	 * 单件商品总额不得超过2000元
	 */
	private $singleton_goods_money = 2000;	
	
	/**
	 * 海关总署公告2016年第55号文件 调整增加的化妆品进口环节消费税的HS编码
	 */
	private $hscode = array('3304990098','3304990097','3304990096','3304990094','3304990093','3304990092','3304990013','3304990012','3304990011','3304910000','3304300003','3304300002','3304300001','3304200093','3304200092','3304200091','3304200013','3304200012','3304200011','3304100093','3304100092','3304100091','3304100013','3304100012','3304100011','3303000020','3303000010');

	/**
	 * 计算每个(单件)商品的税金
	 *
	 */
	public function single_times_allow_2000($cart_info){
		if($cart_info['goods_id'] == '1791'){
			$cart_info['shipping_fee'] = 0;
		}
		
		//海关价格(成交价格 + 运费 + 保险费 - 优惠)
		if($cart_info['mansong_discount'] && $cart_info['mansong_discount'] > 0){
			$mess_price = ($cart_info['goods_price'] + $cart_info['shipping_fee'] + 0 - $cart_info['mansong_discount']);
		}else{
			$mess_price = ($cart_info['goods_price'] + $cart_info['shipping_fee'] + 0 - 0);
		}

		$tax_rate_info = Model()->table('tax_rate')->where(array('hs_code'=>$cart_info['goods_hscode']))->select();
		
		$re = Model('goods')->getGoodeCommonInfo(array('goods_commonid'=>$cart_info['goods_commonid']),'goods_pack, goods_con_num');
		
		if(in_array($cart_info['goods_hscode'], $this->hscode)){
			if($re['goods_pack']==1 || $re['goods_pack']==2){
				//大于等于10元/毫升、克
				$pack = ncPriceFormat($mess_price / $re['goods_con_num']);
				if($pack >= 10){
					$tax_rate_info[0]['consumption_tax'] = '0.15' ;
				}
			}else if($re['goods_pack']==3 || $re['goods_pack']==4){
				//大于等于15元/片、张
				$pack = ncPriceFormat($mess_price / $re['goods_con_num']);
				if($pack >= 15){
					$tax_rate_info[0]['consumption_tax'] = '0.15' ;
				}
			}
		}
		
		if(!$tax_rate_info){
			return ($mess_price * 0.119);
		}
		
		//应征关税(2000以内暂免税):（海关价格）* 关税税率 * 0
		$tariff = $mess_price * $tax_rate_info[0]['tariff'] * 0;
		
		//应征消费税:(海关价格) / (1 - 消费税率) * 消费税率 * 0.7
		$consumption_tax = $mess_price / (1 - $tax_rate_info[0]['consumption_tax']) * $tax_rate_info[0]['consumption_tax'] * 0.7;
		
		//正常消费税:(海关价格) / (1 - 消费税率) * 消费税率 
		$normal_consumption_tax = $mess_price / (1 - $tax_rate_info[0]['consumption_tax']) * $tax_rate_info[0]['consumption_tax'] ;
		
		//应征增值税为:((海关价格 + 正常消费税) * 增值税率 * 0.7
		$vat_tax =($mess_price + $normal_consumption_tax) * $tax_rate_info[0]['vat_tax'] * 0.7 ;
		
		//商品税率结果(关税 + 消费税 + 增值税)
		$goods_tax = ($tariff + $consumption_tax + $vat_tax);
		if(!$goods_tax){
			return ($mess_price * 0.119);
		}
		return $goods_tax;
	}

	/**
	 * 计算单次或者单个商品购买超过2000元的
	 *
	 */
	public function single_goods_allow() {
		return true;
	}

	/**
	 * 计算年度从购买金额超过20000元
	 *
	 */
	public function single_year_allow() {
		return true;
	}

}
