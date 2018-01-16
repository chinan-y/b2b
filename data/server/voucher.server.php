<?php
/**
 * 代金券逻辑层处理
 */
defined('GcWebShop') or exit('Access Invalid!');
class voucherServer {

	/**
	 * [验证是否能兑换]
	 * @author fulijun
	 * @dateTime 2016-11-03T16:59:03+0800
	 * @param    [type]                   $vid       [description]
	 * @param    [type]                   $member_id [description]
	 * @return   boolean                             [description]
	 */
	public function isCanExchange($vid,$member_id){
		//判断是否有代金券模板
		$tempcon = array(
			'voucher_t_state'=>1,
			'voucher_t_end_date'=>array('gt',TIMESTAMP),
			'voucher_t_id'=>$vid
		);
		$moban_info = Model()->table('voucher_template')
					->field('voucher_t_id,voucher_t_title,voucher_t_store_id,voucher_t_customimg,voucher_t_eachlimit,voucher_t_points')
					->where($tempcon)->find($vid);
		if(!$moban_info){
			return json_return(501,'代金券信息错误');
		}
		//处理图片
		if (!empty($moban_info['voucher_t_customimg'])){
            $moban_info['voucher_t_customimg'] = UPLOAD_SITE_URL.DS.ATTACH_VOUCHER.DS.$moban_info['voucher_t_store_id'].DS.$moban_info['voucher_t_customimg'];
        }else{
            $moban_info['voucher_t_customimg'] = UPLOAD_SITE_URL.DS.defaultGoodsImage(240);
        }

		//不能领取自己的代金券
		$memstore_id = Model()->table('seller')->getfby_seller_id($member_id,'store_id');
		$member_points= Model()->table('member')->getfby_member_id($member_id,'member_points');
	
		$store_id = $moban_info['voucher_t_store_id'];
		if($memstore_id == $store_id){
			return json_return(501,'不能领取自己的代金券');
		}
		//查询我的代金券
        $quancon = array(
			'voucher_owner_id'=>$member_id,
			'voucher_store_id'=>$store_id
		);
		$quan_info = Model()->table('voucher')
					->field('voucher_id,voucher_title,voucher_state,voucher_end_date')
					->where($quancon)->select();
		
		//代金券领取限制
		$num = 0;
		$eachnum = 0;
		if($quan_info){
			foreach ($quan_info as $k => $v) {
				//计算代金券数量
				if($v['voucher_state'] == 1 && $v['voucher_end_date'] > TIMESTAMP){
					$num += 1;
				}
				if ($v['voucher_t_id'] == $moban_info['voucher_t_id']){
	                $eachnum += 1;
	            }
			}
		}
	
		//代金券总数量
		$limit = (Int)C('promotion_voucher_buyertimes_limit');
        if ($num >= $limit){
            return json_return(501,'不能太贪心哦，会长胖的');
        }

        //一张代金券兑换的次数
        $eachlimit = (Int)$moban_info['voucher_t_eachlimit'];
        if ($eachlimit && $eachlimit < $eachnum){
            return json_return(501,'该代金券您已兑换'.$eachlimit.'次，不可再兑换了');
        }
   

        //验证积分够不够
        if($member_points < $moban_info['voucher_t_points']){
        	return json_return(501,'您的积分不足');
        }
        return $moban_info['voucher_t_id'];
	}


	/**
	 * [兑换操作]
	 * @author fulijun
	 * @dateTime 2016-11-03T16:58:29+0800
	 * @return   [type]                   [description]
	 */
	public function gotoExchange($member_id,$tid){
		if(!$member_id || ! $tid ){
			return json_return(501,'参数错误');
		}

		//取得模板信息
		$quan_info = Model()->table('voucher_template')
					->field('voucher_t_id,voucher_t_title,voucher_t_desc,voucher_t_end_date,voucher_t_price,voucher_t_limit,voucher_t_store_id,voucher_t_sku,voucher_t_points')
					->find($tid);
	
		//取得用户名
		$member_name= Model()->table('member')->getfby_member_id($member_id,'member_name');

		//添加代金券
		$code=$this->get_voucher_code($member_id);
		$quau_data = array(
			 'voucher_code' => $code,
	         'voucher_t_id' => $quan_info['voucher_t_id'],
	         'voucher_title' => $quan_info['voucher_t_title'],
	         'voucher_desc' => $quan_info['voucher_t_desc'],    
	         'voucher_end_date' => $quan_info['voucher_t_end_date'],
	         'voucher_price' => $quan_info['voucher_t_price'],
	         'voucher_limit' => $quan_info['voucher_t_limit'],
	         'voucher_store_id' =>$quan_info['voucher_t_store_id'],
			 'voucher_design_sku' =>$quan_info['voucher_t_sku'],
	         'voucher_state' =>1,
	         'voucher_start_date' =>TIMESTAMP,
	         'voucher_active_date' => TIMESTAMP,
	         'voucher_owner_id' => $member_id,
	         'voucher_owner_name' => $member_name
		);
		$isinsert = Model()->table('voucher')->insert($quau_data);
		if($isinsert){
			//扣除会员积分
			$credit_data = array(
				 'pl_memberid' => $member_id,
			     'pl_membername' =>$member_name,
			     'pl_points' =>$quan_info['voucher_t_points'],
			     'point_ordersn' =>$code,
			     'pl_desc' => L('home_voucher').$code.L('points_pointorderdesc')
			);
	        Model('points')->savePointsLog('app',$credit_data,true);

	        //增加兑换数 
	        $updatetid = Model()->table('voucher_template')->where(array('voucher_t_id'=>$vid))->setInc('voucher_t_giveout',1);
	        if($updatetid){
	        	return json_return(400,'领取优惠券成功');
	        }
		}
		return json_return(501,'领取优惠券失败');
		
	}

	/*
     * 获取代金券编码
     */
    public function get_voucher_code($member_id) {
		return mt_rand(10,99)
		      . sprintf('%010d',TIMESTAMP - 946656000)
		      . sprintf('%03d', (float) microtime() * 1000)
		      . sprintf('%03d', (int)$member_id % 1000);
    }
}