<?php
/**
 * 购物车逻辑层处理
 */
defined('GcWebShop') or exit('Access Invalid!');
class cartServer {
	

	
	/**
	 * [取得购物车商品]
	 * @author fulijun
	 * @dateTime 2016-09-21T16:06:44+0800
	 * @param    String                   $type      [1登录时 2不登录（备用）]
	 * @param    [Int]                    $member_id [会员ID]
	 * @return   [type]                              [description]
	 */
	public function getGoods($type='l', $member_id) {
        if ($type == '1') {
    		$data = Model()->table('cart')
			    		->where(array('buyer_id'=>$member_id))
			    		->field('buyer_id,store_id,store_name,goods_id,goods_name,goods_price,goods_num,goods_taxes,goods_image')
			    		->select();
        } 

        if(empty($data) || !is_array($data)){
        	return array();
        }
        //计算购物车总税金、总价格和总数量
	    $all_price = 0;
		$all_taxes = 0;
		
		$list = array();
		foreach ($data as $val) {
			$all_price	+= $val['goods_price'] * $val['goods_num'];
			$all_taxes += $val['goods_taxes'] * $val['goods_num'];
			$tmp[] = $val;
		}

		$list['counts']  =  count($data);
        $list['all_price'] = ncPriceFormat($all_price);
		$list['all_taxes'] = ncPriceFormat($all_taxes);
		$list['goods'] = $tmp;
		return $list;
	}


	public function addCart($goods_id,$member_id){
		 $condition = array(
            'goods_id'=>$goods_id,
            'goods_state' => 1,
            'goods_verify' => 1
        );
        $fields = 'goods_id,goods_name,goods_weight,goods_taxes,goods_image,store_id,store_name,store_from,goods_price,goods_storage,goods_name,goods_commonid';
        $goods_info = Model()->table('goods')->where($condition)->field($fields)->find();
        if(!$goods_info){
            return json_return(501,'商品已下架或未通过审核'); 
        
        }
        if($goods_info['goods_storage'] <= 0){
            return json_return(501,'库存不足'); 
        }

        if($member_id && $goods_info['store_id'] == $member_id){
             return json_return(501,'不能购买自己的商品');
        }
         //抢购
        if (C('groupbuy_allow')) {
            $goods_info['groupbuy'] = Model('groupbuy')->getGroupbuyInfoByGoodsCommonID($goods_info['goods_commonid']);
        }
        //限时折扣
        if (C('promotion_allow') && empty($goods_info['groupbuy'])) {
            $goods_info['xianshi'] = Model('p_xianshi_goods')->getXianshiGoodsInfoByGoodsID($goods_info['goods_id']);
        }

        //组织数据
        $data = array(
        	'buyer_id'=>$member_id,
        	'goods_id'=>$goods_id,
        	'goods_num'=>1,
        	'is_check'=>1,
        	'bl_id'=>0,
        	'store_id'=>$goods_info['store_id'],
        	'store_name'=>$goods_info['store_name'],
        	'store_from'=>$goods_info['store_from'],
        	'goods_name'=>$goods_info['goods_name'],
        	'goods_price'=>$goods_info['goods_price'],
        	'goods_weight'=>$goods_info['goods_weight'],
        	'goods_taxes'=>$goods_info['goods_taxes'],
        	'goods_image'=>cthumb($goods_info['goods_image'], 360, $goods_info['store_id'], $goods_info['goods_commonid'])

        );
        //判断是否存在，如果是只更新数量
        $where = array(
        	'buyer_id'=>$member_id,
        	'goods_id'=>$goods_id
        );
     	$isexit = Model()->table('cart')->where($where)->find();
     	if($isexit){
     		$issave = Model()->table('cart')->where($where)->setInc('goods_num',1);
     	}else{
     		$isadd = Model()->table('cart')->insert($data);
     	}
     	if($isexit || $isadd){
     		return json_return(400,'添加购物车成功'); 
     	}
     	return json_return(501,'添加购物车失败'); 
	}




}