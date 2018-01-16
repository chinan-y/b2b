<?php
/**
 * 商品图片统一调用函数//zmr>v30
 *
 *
 *
 */

defined('GcWebShop') or exit('Access Invalid!');

/**
 * 取得商品缩略图的完整URL路径，接收商品信息数组，返回所需的商品缩略图的完整URL
 *
 * @param array $goods 商品信息数组
 * @param string $type 缩略图类型  值为60,240,360,1280
 * AliyunOss- 指定缩略的模式：
	- lfit： 等比缩放，限制在设定在指定w与h的矩形内的最大图片。
	- mfit： 等比缩放，延伸出指定w与h的矩形框外的最小图片。
	- fill： 固定宽高，将延伸出指定w与h的矩形框外的最小图片进行居中裁剪。
	- pad：	 固定宽高，缩略填充。
	- fixed：固定宽高，强制缩略
 * @return string
 */
function thumb($goods = array(), $type = ''){
    $type_array = explode(',_', ltrim(GOODS_IMAGES_EXT, '_'));
    if (!in_array($type, $type_array)) {
        $type = '240';
    }
    if (empty($goods)){
        return UPLOAD_SITE_URL.'/'.defaultGoodsImage($type);
    }
    if (array_key_exists('apic_cover', $goods)) {
        $goods['goods_image'] = $goods['apic_cover'];
    }
    if (empty($goods['goods_image'])) {
        return UPLOAD_SITE_URL.'/'.defaultGoodsImage($type);
    }
    $search_array = explode(',', GOODS_IMAGES_EXT);
    $file = str_ireplace($search_array,'',$goods['goods_image']);
    $fname = basename($file);
    //取店铺ID
    if (preg_match('/^(\d+_)/',$fname)){
        $store_id = substr($fname,0,strpos($fname,'_'));
    }else{
        $store_id = $goods['store_id'];
    }
	//取商品ID
    if (preg_match('/^(\d+_)/',$fname)){
       $goods_commonid = $goods['goods_commonid'];
    }
	if(empty($goods_commonid)){
		$goods_commonid = 0;
	}


	
	if(C(OSS_IS_STORAGE) == 0){
		$file = $type == '' ? $file : str_ireplace('.', '_' . $type . '.', $file);
		if (!file_exists(BASE_UPLOAD_PATH.'/'.ATTACH_GOODS.'/'.$store_id.'/'.$goods_commonid.'/'.$file)){
			if(file_exists(BASE_UPLOAD_PATH.'/'.ATTACH_GOODS.'/'.$store_id.'/'.$file)){
				$thumb_host = UPLOAD_SITE_URL.'/'.ATTACH_GOODS;
				return $thumb_host.'/'.$store_id.'/'.$file;
			}else{
				return UPLOAD_SITE_URL.'/'.defaultGoodsImage($type);
			}
		}
		$thumb_host = UPLOAD_SITE_URL.'/'.ATTACH_GOODS;
		return $thumb_host.'/'.$store_id.'/'.$goods_commonid.'/'.$file;
	}
	//AliyunOSS存储
	if(C(OSS_IS_STORAGE) == 1){
		$thumb_host = UPLOAD_SITE_URL.'/'.ATTACH_GOODS;
		if($goods_commonid >= 0){
			return $thumb_host . '/' . $store_id . '/' . $goods_commonid . '/' . $file.'?x-oss-process=image/resize,m_pad,w_'.$type;	
		}else{
			return $thumb_host . '/' . $store_id . '/' . $file.'?x-oss-process=image/resize,m_pad,w_'.$type;	
		}
		
	}


}
/**
 * 取得商品缩略图的完整URL路径，接收图片名称与店铺ID
 *
 * @param string $file 图片名称
 * @param string $type 缩略图尺寸类型，值为60,240,360,1280
 * AliyunOss- 指定缩略的模式：
	- lfit： 等比缩放，限制在设定在指定w与h的矩形内的最大图片。
	- mfit： 等比缩放，延伸出指定w与h的矩形框外的最小图片。
	- fill： 固定宽高，将延伸出指定w与h的矩形框外的最小图片进行居中裁剪。
	- pad：	 固定宽高，缩略填充。
	- fixed：固定宽高，强制缩略
 * @param mixed $store_id 店铺ID 如果传入，则返回图片完整URL,如果为假，返回系统默认图
 * @return string
 */
function cthumb($file, $type = '', $store_id = false , $goods_commonid = false) {
    $type_array = explode(',_', ltrim(GOODS_IMAGES_EXT, '_'));
    if (!in_array($type, $type_array)) {
        $type = '240';
    }
    if (empty($file)) {
        return UPLOAD_SITE_URL . '/' . defaultGoodsImage ( $type );
    }
    $search_array = explode(',', GOODS_IMAGES_EXT);
    $file = str_ireplace($search_array,'',$file);
    $fname = basename($file);

    // 取店铺ID
    if ($store_id === false || !is_numeric($store_id)) {
        $store_id = substr ( $fname, 0, strpos ( $fname, '_' ) );
    }
	 
    // 本地存储时，增加判断文件是否存在，用默认图代替
	if(C(OSS_IS_STORAGE) == 0){
		// 取商品ID
		if ($goods_commonid === false || !is_numeric($goods_commonid)) {
		   $goods_commonid = substr ( $fname, 0, strpos ( $fname, '_' ) );
		}
		if ( !file_exists(BASE_UPLOAD_PATH . '/' . ATTACH_GOODS . '/' . $store_id . '/' . $goods_commonid . '/' . ($type == '' ? $file : str_ireplace('.', '_' . $type . '.', $file)))){
			if(file_exists(BASE_UPLOAD_PATH . '/' . ATTACH_GOODS . '/' . $store_id . '/' . ($type == '' ? $file : str_ireplace('.', '_' . $type . '.', $file)) )){
				$thumb_host = UPLOAD_SITE_URL . '/' . ATTACH_GOODS;
				return $thumb_host . '/' . $store_id . '/' . ($type == '' ? $file : str_ireplace('.', '_' . $type . '.', $file));
			}else{
				return UPLOAD_SITE_URL.'/'.defaultGoodsImage($type);
			}
		}
		
		$thumb_host = UPLOAD_SITE_URL . '/' . ATTACH_GOODS;
		return $thumb_host . '/' . $store_id . '/' . $goods_commonid . '/' . ($type == '' ? $file : str_ireplace('.', '_' . $type . '.', $file));	
	}
	//AliyunOSS存储
	if(C(OSS_IS_STORAGE) == 1){
		$goods_commonid = str_replace('c', '',strstr($fname, 'c'));//遵循上传图片的命名规则
		if(substr($goods_commonid,0,1) == 1){
			$goods_commonid = substr($goods_commonid,0,6);	
		}else{
			$goods_commonid = 0;	
		}

		$thumb_host = UPLOAD_SITE_URL . '/' . ATTACH_GOODS;	
		if($goods_commonid >= 0){
			return $thumb_host . '/' . $store_id . '/' . $goods_commonid . '/' . $file.'?x-oss-process=image/resize,m_pad,w_'.$type;	
		}else{
			return $thumb_host . '/' . $store_id . '/' . $file.'?x-oss-process=image/resize,m_pad,w_'.$type;	
		}
	}

	
}
/**
 * 商品二维码
 * @param array $goods_info
 * @return string
 */
function goodsQRCode($goods_info) {
	if(C(OSS_IS_STORAGE) == 0){
		if (!file_exists(BASE_UPLOAD_PATH. '/' . ATTACH_STORE . '/' . $goods_info['store_id'] . '/' . $goods_info['goods_id'] . '.png' )) {
			return UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.'default_qrcode.png';
		}
		return UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.$goods_info['store_id'].DS.$goods_info['goods_id'].'.png';	
	}
	
	if(C(OSS_IS_STORAGE) == 1){
		return BASE_SITE_URL.DS.'data'.DS.'upload'.DS.ATTACH_STORE.DS.$goods_info['store_id'].DS.$goods_info['goods_id'].'.png';	
	}
}


/**
 * 店铺二维码
 * @param array $goods_info
 * @return string
 */
function storeQRCode($store_id) {
	if(C(OSS_IS_STORAGE) == 0){
		if (!file_exists(BASE_UPLOAD_PATH. '/' . ATTACH_STORE . '/' . $store_id . '/' . $store_id . '_store.png' )) {
			return UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.'default_qrcode.png';
		}
		return UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.$store_id.DS.$store_id.'_store.png';
	}
	
	if(C(OSS_IS_STORAGE) == 1){
		return BASE_SITE_URL.DS.'data'.DS.'upload'.DS.ATTACH_STORE.DS.$store_id.DS.$store_id.'_store.png';
	}
}
//zmr<<<

/**
 * 取得抢购缩略图的完整URL路径
 *
 * @param string $imgurl 商品名称
 * @param string $type 缩略图类型  值为small,mid,max
 * @return string
 */
function gthumb($image_name = '', $type = ''){
	if (empty($image_name)){
		return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
	}
	if(C(OSS_IS_STORAGE) == 0){
		if (!in_array($type, array('small','mid','max'))) $type = 'small';
		list($base_name, $ext) = explode('.', $image_name);
		list($store_id) = explode('_', $base_name);
		$file_path = ATTACH_GROUPBUY.DS.$store_id.DS.$base_name.'_'.$type.'.'.$ext;
		if(!file_exists(BASE_UPLOAD_PATH.DS.$file_path)) {
			return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
		}
		return UPLOAD_SITE_URL.DS.$file_path;
	}
	if(C(OSS_IS_STORAGE) == 1){
		if (!in_array($type, array('32','210','530'))) $type = '32';
		list($store_id) = explode('_', $image_name);
		$file_path = ATTACH_GROUPBUY.DS.$store_id.DS.$image_name.'?x-oss-process=image/resize,m_lift,w_'.$type;
		return UPLOAD_SITE_URL.DS.$file_path;
	}
}

/**
 * 取得买家缩略图的完整URL路径
 *
 * @param string $imgurl 商品名称
 * @param string $type 缩略图类型  值为240,1024
 * @return string
 */
function snsThumb($image_name = '', $type = ''){
	if (!in_array($type, array('240','1024'))) $type = '240';
	if (empty($image_name)){
		return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
    }

    list($member_id) = explode('_', $image_name);
    $file_path = ATTACH_MALBUM.DS.$member_id.DS.str_ireplace('.', '_'.$type.'.', $image_name);
    if(!file_exists(BASE_UPLOAD_PATH.DS.$file_path)) {
		return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
	}
	return UPLOAD_SITE_URL.DS.$file_path;
}

/**
 * 取得积分商品缩略图的完整URL路径
 *
 * @param string $imgurl 商品名称
 * @param string $type 缩略图类型  值为small
 * @return string
 */
function pointprodThumb($image_name = '', $type = ''){
	if (!in_array($type, array('small','mid'))) $type = '';
	if (empty($image_name)){
		return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
    }

    if($type) {
		if(C(OSS_IS_STORAGE) == 0){
		    $file_path = ATTACH_POINTPROD.DS.str_ireplace('.', '_'.$type.'.', $image_name);	
		}
		if(C(OSS_IS_STORAGE) == 1){
		    $file_path = ATTACH_POINTPROD.DS.$image_name.'?x-oss-process=image/resize,m_pad,w_240';	
		}
    } else {
        $file_path = ATTACH_POINTPROD.DS.$image_name;
    }
	if(C(OSS_IS_STORAGE) == 0){
		if(!file_exists(BASE_UPLOAD_PATH.DS.$file_path)) {
			return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
		}
	}
	return UPLOAD_SITE_URL.DS.$file_path;
}

/**
 * 取得品牌图片
 *
 * @param string $image_name
 * @return string
 */
function brandImage($image_name = '') {
    if ($image_name != '') {
        return UPLOAD_SITE_URL.'/'.ATTACH_BRAND.'/'.$image_name;
    }
    return UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/default_brand_image.gif';
}

/**
* 取得订单状态文字输出形式
*
* @param array $order_info 订单数组
* @return string $order_state 描述输出
*/
function orderState($order_info) {
    switch ($order_info['order_state']) {
        case ORDER_STATE_CANCEL:
            $order_state = L('order_state_cancel');
        break;
        case ORDER_STATE_NEW:
            $order_state = L('order_state_new');
        break;
        case ORDER_STATE_PAY:
            $order_state = L('order_state_pay');
        break;
        case ORDER_STATE_SEND:
            $order_state = L('order_state_send');
        break;
        case ORDER_STATE_SUCCESS:
            $order_state = L('order_state_success');
        break;
    }
	if($order_info['lock_state'] ==1){
		$order_state = L('order_state_refund_no');
	}
	if($order_info['refund_state'] >0 && $order_info['refund_amount']){
		$order_state = L('order_state_refund_yes');
	}
    return $order_state;
}

/**
 * 取得订单支付类型文字输出形式
 *
 * @param array $payment_code
 * @return string
 */
function orderPaymentName($payment_code) {
    return str_replace(
            array('offline','online','alipay','wxpay','wxapppay','wxminipay','wxapp_ys','wxpaytian','gzbank','lakala','tenpay','chinabank','bocomm','predeposit','tonglian','ccbpay','czypay'),
            array('货到付款','在线付款','支付宝','微信公众号支付','微信APP支付','微信小程序支付','原生APP微信支付','微信天下一家支付','贵州银行','拉卡拉','财付通','网银在线','银联支付','站内余额支付','通联支付','建行支付','彩之云支付'),
            $payment_code);
}

/**
 * 取得订单商品销售类型文字输出形式
 *
 * @param array $goods_type
 * @return string 描述输出
 */
function orderGoodsType($goods_type) {
    return str_replace(
            array('1','2','3','4','5'),
            array('','抢购','限时折扣','优惠套装','赠品'),
            $goods_type);
}

/**
 * 取得结算文字输出形式
 *
 * @param array $bill_state
 * @return string 描述输出
 */
function billState($bill_state) {
    return str_replace(
            array('1','2','3','4'),
            array('已出账','商家已确认','平台已审核','结算完成'),
            $bill_state);
}
?>
