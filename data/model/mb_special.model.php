<?php
/**
 * 手机专题模型
 *
 *
 *
 *
 
 */
defined('GcWebShop') or exit('Access Invalid!');
class mb_specialModel extends Model{

    //专题项目不可用状态
    const SPECIAL_ITEM_UNUSABLE = 0;
    //专题项目可用状态
    const SPECIAL_ITEM_USABLE = 1;
    //首页特殊专题编号
    const INDEX_SPECIAL_ID = 0;

    public function __construct() {
        parent::__construct('mb_special');
    }

	/**
	 * 读取专题列表
	 * @param array $conditionv
	 *
	 */
	public function getMbSpecialList($condition, $page='', $order='special_id desc', $field='*') {
        $list = $this->table('mb_special')->field($field)->where($condition)->page($page)->order($order)->select();
        return $list;
	}

	/*
	 * 增加专题
	 * @param array $param
	 * @return bool
     *
	 */
    public function addMbSpecial($param){
        return $this->table('mb_special')->insert($param);
    }

	/*
	 * 更新专题
	 * @param array $update
	 * @param array $condition
	 * @return bool
     *
	 */
    public function editMbSpecial($update, $special_id) {
        $special_id = intval($special_id);
        if($special_id <= 0) {
            return false;
        }

        $condition = array();
        $condition['special_id'] = $special_id;
        $result = $this->table('mb_special')->where($condition)->update($update);
        if($result) {
            //删除缓存
            $this->_delMbSpecialCache($special_id);
            return $special_id;
        } else {
            return false;
        }
    }

	/*
	 * 删除专题
	 * @param int $special_id
	 * @return bool
     *
	 */
    public function delMbSpecialByID($special_id) {
        $special_id = intval($special_id);
        if($special_id <= 0) {
            return false;
        }

        $condition = array();
        $condition['special_id'] = $special_id;

        $this->delMbSpecialItem($condition, $special_id);

        return $this->table('mb_special')->where($condition)->delete();
    }

    /**
     * 专题项目列表（用于后台编辑显示所有项目）
	 * @param int $special_id
     *
     */
    public function getMbSpecialItemListByID($special_id) {
        $condition = array();
        $condition['special_id'] = $special_id;

        return $this->_getMbSpecialItemList($condition);
    }

    /**
     * 专题可用项目列表（用于前台显示仅显示可用项目）
	 * @param int $special_id
     *
     */
    public function getMbSpecialItemUsableListByID($special_id) {
        $prefix = 'mb_special';

        $item_list = rcache($special_id, $prefix);
        
        //缓存有效
        if(!empty($item_list)) {
            return unserialize($item_list['special']);
        }

        //缓存无效查库并缓存
        $condition = array();
        $condition['special_id'] = $special_id;
        $condition['item_usable'] = self::SPECIAL_ITEM_USABLE;
        $item_list = $this->_getMbSpecialItemList($condition);

        if(!empty($item_list)) {
            $new_item_list = array();
            foreach ($item_list as $value) {
                //处理图片
                $item_data = $this->_formatMbSpecialData($value['item_data'], $value['item_type']);
                $new_item_list[] = array($value['item_type'] => $item_data);
            }
            $item_list = $new_item_list;
        }
        $cache = array('special' => serialize($item_list));
        wcache($special_id, $cache, $prefix);
        return $item_list;
    }

    /**
     * 首页专题
     */
    public function getMbSpecialIndex() {
        return $this->getMbSpecialItemUsableListByID(self::INDEX_SPECIAL_ID);
    }

    /**
     * 处理专题数据，拼接图片URL
     */
    private function _formatMbSpecialData($item_data, $item_type) {
        switch ($item_type) {
            case 'home1':
            case 'home1_1':
                $item_data['image'] = getMbSpecialImageUrl($item_data['image']);
                break;
            case 'home2':
            case 'home4':
			case 'home5':
			case 'home6':
			case 'home7':
			    $item_data['square_image'] = getMbSpecialImageUrl($item_data['square_image']);
                $item_data['rectangle1_image'] = getMbSpecialImageUrl($item_data['rectangle1_image']);
                $item_data['rectangle2_image'] = getMbSpecialImageUrl($item_data['rectangle2_image']);
				$item_data['rectangle3_image'] = getMbSpecialImageUrl($item_data['rectangle3_image']);
                $item_data['rectangle4_image'] = getMbSpecialImageUrl($item_data['rectangle4_image']);
				$item_data['rectangle5_image'] = getMbSpecialImageUrl($item_data['rectangle5_image']);
                $item_data['rectangle6_image'] = getMbSpecialImageUrl($item_data['rectangle6_image']);
				$item_data['rectangle7_image'] = getMbSpecialImageUrl($item_data['rectangle7_image']);
                $item_data['rectangle8_image'] = getMbSpecialImageUrl($item_data['rectangle8_image']);
				$item_data['rectangle9_image'] = getMbSpecialImageUrl($item_data['rectangle9_image']);
                $item_data['rectangle10_image'] = getMbSpecialImageUrl($item_data['rectangle10_image']);
            break;
            case 'goods':
                $new_item = array();
				$favorites_model = Model('favorites');
				$member_id = $_SESSION['member_id'];
                foreach ((array) $item_data['item'] as $value) {
                	$favorites_info = $favorites_model->getOneFavorites(array('fav_id'=>$value['goods_id'],'fav_type'=>'goods','member_id'=>$member_id));
					if($favorites_info) {
			            $value['state'] = 0;
					}else{
						$value['state'] = 1;
					}	
                    $value['goods_image'] = cthumb($value['goods_image'], 240, $value['store_id'], $value['goods_commonid']);
                    $new_item[] = $value;
                }
                $item_data['item'] = $new_item;
                break;
			 case 'goods3n':
                $new_item = array();
				$favorites_model = Model('favorites');
				$member_id = $_SESSION['member_id'];
                foreach ((array) $item_data['item'] as $value) {
                	$favorites_info = $favorites_model->getOneFavorites(array('fav_id'=>$value['goods_id'],'fav_type'=>'goods','member_id'=>$member_id));
					if($favorites_info) {
			            $value['state'] = 0;
					}else{
						$value['state'] = 1;
					}	
                    $value['goods_image'] = cthumb($value['goods_image'], 180, $value['store_id'], $value['goods_commonid']);
                    $new_item[] = $value;
                }
                $item_data['item'] = $new_item;
                break;
            case 'groupbuy':
                $new_item = array();
                foreach ((array) $item_data['item'] as $value) {
                    //$value['format_time'] = '20h';
                    //$value['groupbuy_image1'] = gthumb($value['groupbuy_image1']);
					$value['groupbuy_rebate'] = number_format($value['groupbuy_rebate'],1);
                    $new_item[] = $value;
                }
                $item_data['item'] = $new_item;
                break;
			case 'groupbuy1':
                $item_data['image'] = getMbSpecialImageUrl($item_data['image']);
                break;
            case 'promotion_xianshi':
                $new_item = array();
                foreach ((array) $item_data['item'] as $value) {
                    $value['goods_image'] = cthumb($value['goods_image']);
                    $new_item[] = $value;
                }
                $item_data['item'] = $new_item;
                break;
            default:
                $new_item = array();
                foreach ((array) $item_data['item'] as $key => $value) {
                    $value['image'] = getMbSpecialImageUrl($value['image']);
                    $new_item[] = $value;
                }
                $item_data['item'] = $new_item;
        }
        return $item_data;
    }

    /**
     * 查询专题项目列表
     */
    public function _getMbSpecialItemList($condition, $order = 'item_sort asc') {
        $item_list = $this->table('mb_special_item')->where($condition)->order($order)->select();

        foreach ($item_list as $key => $value) {
            $item_list[$key]['item_data'] = $this->_initMbSpecialItemData($value['item_data'], $value['item_type']);

            if($value['item_usable'] == self::SPECIAL_ITEM_USABLE) {
                $item_list[$key]['usable_class'] = 'usable';
                $item_list[$key]['usable_text'] = '禁用';
            } else {
                $item_list[$key]['usable_class'] = 'unusable';
                $item_list[$key]['usable_text'] = '启用';
            }
        }

        return $item_list;
    }

    /**
     * 检查专题项目是否存在
	 * @param array $condition
     *
     */
    public function isMbSpecialItemExist($condition) {
        $item_list = $this->table('mb_special_item')->where($condition)->select();
        if($item_list) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取项目详细信息
     * @param int $item_id
     *
     */
    public function getMbSpecialItemInfoByID($item_id) {
        $item_id = intval($item_id);
        if($item_id <= 0) {
            return false;
        }

        $condition = array();
        $condition['item_id'] = $item_id;
        $item_info = $this->table('mb_special_item')->where($condition)->find();
        $item_info['item_data'] = $this->_initMbSpecialItemData($item_info['item_data'], $item_info['item_type']);

        return $item_info;
    }

    /**
     * 整理项目内容
     *
     */
    private function _initMbSpecialItemData($item_data, $item_type) {

        if(!empty($item_data)) {
            $item_data = unserialize($item_data);
            if($item_type == 'goods' || $item_type == 'goods3n'|| $item_type == 'groupbuy' || $item_type == 'promotion_xianshi') {
                $item_data = $this->_initMbSpecialItemGoodsData($item_data, $item_type);
            }else if($item_type == 'groupbuy1'){
				$model_groupbuy = Model('groupbuy');
                $groupbuy_list = $model_groupbuy->getIndexGroupbuyCommendedList(1000);
				foreach ($groupbuy_list as $k=>$v) {
					if(($v['end_time'] - time()) / 3600  > 1){
						$time = floor(($v['end_time'] - time()) / 3600 );
					}else{
						$time = 0;
					}
					$item_data['format_time'] = $time;
				}
			}
			
        }else{
            $item_data = $this->_initMbSpecialItemNullData($item_type);
        }
        return $item_data;
    }

    /**
     * 处理goods类型内容
     */
    private function _initMbSpecialItemGoodsData($item_data, $item_type) {

        $goods_id_string = '';
        if(!empty($item_data['item'])) {
            switch ($item_type) {
                case 'groupbuy':
                    foreach ($item_data['item'] as $value) {
                        $goods_id_string .= $value . ',';
                    }
                    $goods_id_string = rtrim($goods_id_string, ',');

                    //查询商品信息
                    $condition['groupbuy_id'] = array('in', $goods_id_string);
                    $model_groupbuy = Model('groupbuy');
                    $groupbuy_list = $model_groupbuy->getIndexGroupbuyCommendedList(1000);
					foreach ($groupbuy_list as $k=>$v) {
						if(($v['end_time'] - time()) / 3600  > 1){
							$time = floor(($v['end_time'] - time()) / 3600 );
						}else{
							$time = 0;
						}
						$item_data['format_time'] = $time;
						$item_data['count_down_text'] = $v['count_down_text'];
						$re = Model('goods')->getGoodsInfo(array('goods_id'=> $val['goods_id']),'goods_jingle, country_code');
						$groupbuy_list[$k]['goods_jingle'] = $re['goods_jingle'];
						$groupbuy_list[$k]['country_code'] = $re['country_code'];
						$groupbuy_list[$k]['format_time'] = $time;
						$groupbuy_list[$k]['groupbuy_image'] = gthumb($groupbuy_list[$k]['groupbuy_image'],'mid');
						$groupbuy_list[$k]['groupbuy_image1'] = gthumb($groupbuy_list[$k]['groupbuy_image1'],'mid');
					}
                    $groupbuy_list = array_under_reset($groupbuy_list, 'groupbuy_id');
					
					$groupbuy = $model_groupbuy->userGroupbuyOnlineList(array('is_vr' => 0), 9 , 'time_num asc', 'start_time_text, end_time_text');
					foreach($groupbuy as $val){
						// $val['time_s'] = trim(substr($val['start_time_text'], strpos($val['start_time_text'],' ')));
						// $val['time_e'] = trim(substr($val['end_time_text'], strpos($val['end_time_text'],' ')));
						$val['time_d'] = date('d日', $val['start_time']);
						$val['time_h'] = date('H点', $val['start_time']);
						$all_groupbuy[$val['time_num']][] = $val;
					}
					
                    //整理商品数据
                    $new_groupbuy_list = array();
                    foreach ($item_data['item'] as $value) {
                        if(!empty($groupbuy_list[$value])) {
                            $new_groupbuy_list[] = $groupbuy_list[$value];
                        }
                    }
                    $item_data['item'] = $new_groupbuy_list;
                    $item_data['nav'] = $all_groupbuy;
                    break;
                //case 'promotion_xianshi':
                default:
                    foreach ($item_data['item'] as $value) {
                        $goods_id_string .= $value . ',';
                    }
                    $goods_id_string = rtrim($goods_id_string, ',');

                    //查询商品信息
                    $condition['goods_id'] = array('in', $goods_id_string);
                    $model_goods = Model('goods');
                    $goods_list = $model_goods->getGoodsList($condition, 'goods_id,goods_commonid,goods_name,store_id,store_name,goods_promotion_price,goods_marketprice,goods_image,goods_storage,goods_rebate_rate');
                    $goods_list = array_under_reset($goods_list, 'goods_id');
                    foreach ($goods_list as $s => $u){
						if($u['goods_marketprice']>0){
                        $goods_list[$s]['discount'] =  number_format($u['goods_promotion_price'] / $u['goods_marketprice'] * 10,1);
						$goods_list[$s]['goods_image_url'] = cthumb($u['goods_image'], 240, $u['store_id'], $u['goods_commonid']);
						}
                    }
                    //整理商品数据
                    $new_goods_list = array();
                    foreach ($item_data['item'] as $value) {
                        if(!empty($goods_list[$value])) {
                            $new_goods_list[] = $goods_list[$value];
                        }
                    }
                    $item_data['item'] = $new_goods_list;
                    break;
            }

        }
        return $item_data;
    }

    /**
     * 初始化空项目内容
     */
    private function _initMbSpecialItemNullData($item_type) {
        $item_data = array();
        switch ($item_type) {
        case 'home1':
        case 'home1_1':
            $item_data = array(
                'title' => '',
                'image' => '',
                'type' => '',
                'data' => '',
            );
            break;
		case 'groupbuy1':
            $item_data = array(
                'title' => '',
                'image' => '',
                'type' => '',
                'data' => '',
            );
            break;
		case 'goods3n':
            $item_data = array(
                'title' => '',
                'image' => '',
                'type' => '',
                'data' => '',
            );
            break;
        case 'home2':
        case 'home4':
		case 'home5':
		case 'home6':
		case 'home7':
            $item_data= array(
                'title' => '',
                'square_image' => '',
                'square_type' => '',
                'square_data' => '',
                'rectangle1_image' => '',
                'rectangle1_type' => '',
                'rectangle1_data' => '',
                'rectangle2_image' => '',
                'rectangle2_type' => '',
                'rectangle2_data' => '',
				'rectangle3_image' => '',
                'rectangle3_type' => '',
                'rectangle3_data' => '',
			    'rectangle4_image' => '',
                'rectangle4_type' => '',
                'rectangle4_data' => '',
			    'rectangle5_image' => '',
                'rectangle5_type' => '',
                'rectangle5_data' => '',
			    'rectangle6_image' => '',
                'rectangle6_type' => '',
                'rectangle6_data' => '',
			    'rectangle7_image' => '',
                'rectangle7_type' => '',
                'rectangle7_data' => '',
			    'rectangle8_image' => '',
                'rectangle8_type' => '',
                'rectangle8_data' => '',
				'rectangle9_image' => '',
                'rectangle9_type' => '',
                'rectangle9_data' => '',
				'rectangle10_image' => '',
                'rectangle10_type' => '',
                'rectangle10_data' => '',
            );
            break;
        default:
        }
        return $item_data;
    }

    /*
     * 增加专题项目
     * @param array $param
     * @return array $item_info
     *
     */
    public function addMbSpecialItem($param) {
        $param['item_usable'] = self::SPECIAL_ITEM_UNUSABLE;
        $param['item_sort'] = 255;
        $result = $this->table('mb_special_item')->insert($param);
        //删除缓存
        if($result) {
            //删除缓存
            $this->_delMbSpecialCache($param['special_id']);
            $param['item_id'] = $result;
            return $param;
        } else {
            return false;
        }
    }

    /**
     * 编辑专题项目
     * @param array $update
     * @param int $item_id
     * @param int $special_id
     * @return bool
     *
     */
    public function editMbSpecialItemByID($update, $item_id, $special_id) {
        if(isset($update['item_data'])) {
            $update['item_data'] = serialize($update['item_data']);
        }
        $condition = array();
        $condition['item_id'] = $item_id;

        //删除缓存
        $this->_delMbSpecialCache($special_id);

        return $this->table('mb_special_item')->where($condition)->update($update);
    }

    /**
     * 编辑专题项目启用状态
     * @param string usable-启用/unsable-不启用
     * @param int $item_id
     * @param int $special_id
     *
     */
    public function editMbSpecialItemUsableByID($usable, $item_id, $special_id) {
        $update = array();
        if($usable == 'usable') {
            $update['item_usable'] = self::SPECIAL_ITEM_USABLE;
        } else {
            $update['item_usable'] = self::SPECIAL_ITEM_UNUSABLE;
        }
        return $this->editMbSpecialItemByID($update, $item_id, $special_id);
    }

	/*
	 * 删除
	 * @param array $condition
	 * @return bool
     *
	 */
    public function delMbSpecialItem($condition, $special_id) {
        //删除缓存
        $this->_delMbSpecialCache($special_id);

        return $this->table('mb_special_item')->where($condition)->delete();
    }

    /**
     * 获取专题URL地址
	 * @param int $special_id
     *
     */
    public function getMbSpecialHtmlUrl($special_id) {
        return UPLOAD_SITE_URL . DS . ATTACH_MOBILE . DS . 'special_html' . DS . md5('special' . $special_id) . '.html';
    }

    /**
     * 获取专题静态文件路径
	 * @param int $special_id
     *
     */
    public function getMbSpecialHtmlPath($special_id) {
        return BASE_UPLOAD_PATH . DS . ATTACH_MOBILE . DS . 'special_html' . DS . md5('special' . $special_id) . '.html';
    }

    /**
     * 获取专题模块类型列表
     * @return array
     *
     */
    public function getMbSpecialModuleList() {
        $module_list = array();
        $module_list['adv_list'] = array('name' => 'adv_list' , 'desc' => '广告条版块');
        $module_list['adv_list1'] = array('name' => 'adv_list1' , 'desc' => '广告条版块1');
        $module_list['home1'] = array('name' => 'home1' , 'desc' => '模型版块布局A');
        $module_list['home1_1'] = array('name' => 'home1_1' , 'desc' => '模型版块布局A_1');
        $module_list['groupbuy1'] = array('name' => 'groupbuy1' , 'desc' => '天天特价板块');
        $module_list['home2'] = array('name' => 'home2' , 'desc' => '模型版块布局B');
        $module_list['home3'] = array('name' => 'home3' , 'desc' => '模型版块布局C');
        $module_list['home4'] = array('name' => 'home4' , 'desc' => '模型版块布局D');
		$module_list['home5'] = array('name' => 'home5' , 'desc' => '模型版块布局E(2*4布局)');
	    $module_list['home6'] = array('name' => 'home6' , 'desc' => '模型版块布局F(1*3布局)');
		$module_list['home7'] = array('name' => 'home7' , 'desc' => '模型版块布局G(2*5布局)');
		$module_list['goods'] = array('name' => 'goods' , 'desc' => '商品版块2*N');
		$module_list['goods3n'] = array('name' => 'goods3n' , 'desc' => '商品版块3*N');
        $module_list['groupbuy'] = array('name' => 'groupbuy' , 'desc' => '抢购模块');
        $module_list['promotion_xianshi'] = array('name' => 'promotion_xianshi' , 'desc' => '限时版块');
        return $module_list;
    }

    /**
     * 清理缓存
     */
    private function _delMbSpecialCache($special_id) {
        //清理缓存
        dcache($special_id, 'mb_special');

        //删除静态文件
        $html_path = $this->getMbSpecialHtmlPath($special_id);
        if(is_file($html_path)) {
            @unlink($html_path);
        }
    }
}
