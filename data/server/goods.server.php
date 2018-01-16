<?php
/**
 * 商品逻辑层处理
 */
defined('GcWebShop') or exit('Access Invalid!');
class goodsServer {


	/**
	 * [取得商品五张图片]
	 * @author fulijun
	 * @dateTime 2016-09-23T15:30:55+0800
	 * @param    [Array]                   $commonid_array [goods_commonid数组]
	 * @param    [Int]                     $color_id       [要比对的颜色规格id]
	 * @param    [Int]                     $store_id       [要比对的店铺id]
	 * @param    [Int]                     $goods_commonid [要比对的goods_commonid]
	 * @return   [Array]                                   [返回符合条件比对后的商品图片数组]
	 */
	public function getFiveImagesByCommonid($commonid_array,$color_id,$store_id,$goods_commonid){
		if(!$commonid_array || !is_array($commonid_array)){
			return false;
		}
		//取得公共ID集合的图片数组
		$condition = array();
		$condition['goods_commonid'] = array('IN',$commonid_array);
		$tmp = Model()->table('goods_images')->where($condition)->order('is_default DESC,goods_image_sort ASC')->select();

		//最多五张
		$data = array();
		$n = 0;
		foreach ($tmp as $k => $v) {
			if($v['goods_commonid'] == $goods_commonid && $v['color_id'] == $color_id && $v['store_id'] == $store_id){
				$n++;	
				//$data[] = $v['goods_image'];
				$data[] = cthumb($v['goods_image'], 360, $v['store_id'], $v['goods_commonid']);
				if($n > 5)break;
			}
		}
		return $data;
	}	

	/**
	 * [取得商品]
	 * @author fulijun
	 * @dateTime 2016-09-24T14:16:13+0800
	 * @param    Array                     $condition [查询条件]
	 * @param    [Array]                   $fields    [查询字段]
	 * @param    [String]                  $order     [排序]
	 * @param    [String]                  $limit     [分页，如：'1,2',表示第1页，每页2条]
	 * @return   [Array]                              [返回符合条件的数组]
	 */
	public function getGoods($condition=array(),$fields,$order,$limit){
		$condition['goods_state'] = 1;
		$condition['goods_verify'] = 1;
        //组织数据
        $data = array();
        $fields = "CONCAT(goods_commonid,',',color_id) as nc_distinct ," . $fields;
        $data['count'] = Model()->table('goods')->where($condition)->count1();
        $data['list'] = Model()->table('goods')->where($condition)->field($fields)->group('nc_distinct')->limit($limit)->order($order)->select();
		return $data;
	}


	/**
	 * [套装商品按仓库分类]
	 * @author fulijun
	 * @dateTime 2016-10-08T17:07:36+0800
	 * @param    [Array]                   $packages [套装商品或普通商品]
	 * @return   [type]                              [description]
	 */
	public function sortPackageBybusi($packages){
		if (empty($packages) || !is_array($packages)) return $packages;
	    $goods_list = array();
	    $i = 0;
        foreach ($packages as $key => $val) {
            if (!$val['state'] || !$val['storage_state']) continue;
            if (!intval($val['bl_id'])) {
                //普通商品
                $goods_list[$i]['goods_num'] = $val['goods_num'];
                $goods_list[$i]['goods_weight'] = $val['goods_weight'];
                $goods_list[$i]['goods_id'] = $val['goods_id'];
                $goods_list[$i]['store_id'] = $val['store_id'];
                $goods_list[$i]['goods_name'] = $val['goods_name'];
                $goods_list[$i]['goods_price'] = $val['goods_price'];
				$goods_list[$i]['goods_taxes'] = $val['goods_taxes'];
                $goods_list[$i]['store_name'] = $val['store_name'];
                $goods_list[$i]['goods_image'] = $val['goods_image'];    
                $goods_list[$i]['goods_freight'] = $val['goods_freight'];
                $goods_list[$i]['bl_id'] = 0;
                $i++;
            } else {
                //优惠套装
                foreach ($val['bl_goods_list'] as $v) {
                    $goods_list[$i]['goods_num'] = $val['goods_num'];
                    $goods_list[$i]['goods_id'] = $v['goods_id'];
                    $goods_list[$i]['store_id'] = $val['store_id'];
                    $goods_list[$i]['goods_name'] = $v['goods_name'];
                    $goods_list[$i]['goods_price'] = $v['goods_price'];
					$goods_list[$i]['goods_taxes'] = $v['goods_taxes'];
                    $goods_list[$i]['store_name'] = $v['store_name'];
                    $goods_list[$i]['goods_image'] = $v['goods_image'];
                    $goods_list[$i]['goods_freight'] = $v['goods_freight'];
                    $goods_list[$i]['bl_id'] = $val['bl_id'];
                    $i++;
                }
            }
        }
        //按仓库分类
        $busi_goods = array();
        foreach ($goods_list as $k => $v) {
        	$busi_goods[$v['store_id']][] = $v;
        }
        $data['busi_goods'] = $busi_goods;
        $data['goods_list'] = $goods_list;
        return $data;
	}


	/**
	 * [根据id取得商品详情]
	 * @author fulijun
	 * @dateTime 2016-10-13T19:03:41+0800
	 * @param    [Int]                   $id     [取得商品ID]
	 * @param    [String]                $fields [要显示的字段]
	 * @param    [Int]                   $is_all [是否全显示字段：1是 0否，默认1]
	 * @return   [Array]                         [返回商品详情的数组]
	 */
	public function getGoodsinfoById($id,$fields="*",$is_all=1){
		$model = Model();
		$goods_info = $model->table("goods")->field($fields)->find($id);

		$fields_arr = explode(',', $fields);
		if(in_array('is_presell', $fields_arr) & in_array('is_fcode', $fields_arr)){
			// 立即购买文字显示
	        $goods_info['buynow_text'] = '立即购买';
	        if ($goods_info['is_presell'] == 1) {
	            $goods_info['buynow_text'] = '预售购买';
	        } elseif ($goods_info['is_fcode'] == 1) {
	            $goods_info['buynow_text'] = 'F码购买';
	        }
	        if($is_all == 0){
	        	unset($goods_info['is_presell']);
	        	unset($goods_info['is_fcode']);
	        }   
		}

		if(in_array('is_virtual',$fields_arr)){
			// 验证是否允许送赠品
	        if($goods_info['is_virtual'] !== 1){
	        	$is_havegift= Model()->table('goods_gift')->where(array('goods_id'=>$goods_info['goods_id']))->select();
	            $goods_info['is_havegift'] = $is_havegift?1:0;  
	            if($is_all == 0){
	            	unset($goods_info['is_virtual']);	
	            }   
	        }
		}
		if(in_array('goods_valite_time',$fields_arr)){
			if($goods_info['goods_valite_time']){
				$goods_info['goods_valite_time'] = date('Y年m月d日',$goods_info['goods_valite_time']);
			}
		}
        
        // 商品受关注次数加1:暂定
        if(in_array('goods_click',$fields_arr)){
        	$goods_info['goods_click'] = $goods_info['goods_click'] + 1;
        	$model->table('goods')->where(array('goods_id'=>$goods_info['goods_id']))->setInc('goods_click',1);
        }
        //处理图片
        if(in_array('goods_image',$fields_arr) & in_array('store_id',$fields_arr) & in_array('goods_commonid',$fields_arr)){
        	$goods_info['goods_image'] = cthumb($goods_info['goods_image'], 240, $goods_info['store_id'], $goods_info['goods_commonid']);
        }
		return $goods_info;
	}



	
	/**
	 * [取得抢购和团购信息]
	 * @author fulijun
	 * @dateTime 2016-10-10T19:01:19+0800
	 * @param    [Int]                   $id       [商品ID]
	 * @param    String                   $field    [抢购信息的字段]
	 * @param    [Int]                   $commonid [公共ID]
	 * @param    String                   $comfield [团购信息的字段]
	 * @return   [type]                             [description]
	 */
	public function getPromoGroupById($id,$field="*",$commonid,$comfield="*"){
		$data = array();
		$goods_info = Model()->table('goods')->field('goods_image,store_id')->find($id);

		//抢购信息
		if($id && C('promotion_allow')){
			$condition = array();
            $condition['state'] = 1;
            $condition['end_time'] = array('gt', TIMESTAMP);
            $condition['goods_id'] = $id;
            $data['promo_info'] = Model()->table('p_xianshi_goods')->where($condition)->order('start_time asc')->field($field)->select();
            //取得礼品图片
            foreach ($data['promo_info'] as $k => $v) {
            	 $data['promo_info'][$k]['xianshi_img']=cthumb($goods_info['goods_image'], $goods_info['store_id']);
            }
           
		}
	
		//团购信息
		if($commonid && C('groupbuy_allow')){
			$condition = array();
            $condition['state'] = 1;
            $condition['end_time'] = array('gt', TIMESTAMP);
            $condition['goods_commonid'] = $commonid;
            $data['groupbuy_info'] = Model()->table('groupbuy')->where($condition)->order('start_time asc')->field($comfield)->select();
		}
		return $data;
	}
	
}