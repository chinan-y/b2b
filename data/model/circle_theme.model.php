<?php
/**
 *  圈子话题
 *
 *
 *
 *
 
 */


defined('GcWebShop') or exit('Access Invalid!');

class circle_themeModel extends Model{
    public function __construct(){
        parent::__construct('circle_theme');
    }
	public function theme_list($condition,$page){
		$prefix='cache_list_theme';
		$theme_list=rcache($page,$prefix);
		if(!empty($theme_list)) {
            return unserialize($theme_list['theme']);
        }
		$model=Model();
		$theme_list=$model->table('circle_theme')->where($condition)->order('theme_addtime desc')->page(30)->select();
		$cache=array('theme'=>serialize($theme_list));
		wcache($page,$cache,$prefix);
		
		return $theme_list;
		
	}
	public function theme_list_one(){
		$model=Model();
		$theme_list= $model->table('circle_theme')->order('theme_addtime desc')->limit(1000)->select();
		foreach($theme_list as $k=>$v){
			$theme_list[$k]['member_avatar']=getMemberAvatarForID($v['member_id']);
		}
		return $theme_list;
		
	}
	public function themeType($condition){
		
		$model=Model();
		$_circle=$model->table('circle')->where($condition)->field('circle_id')->select();
		$array=array();
		foreach($_circle as $key=>$value){
			$array[$key]=$value['circle_id'];
		}
		// var_dump($array);die;
		$where=array('circle_id'=>array('in',$array));
		
		$result=$model->table('circle_theme')->where($where)->order('theme_addtime desc')->limit(1000)->select();
		foreach($result as $k=>$v){
			$result[$k]['member_avatar']=getMemberAvatarForID($v['member_id']);
		}
		// var_dump($result);die;
		
		return $result;
		
		
	}
	public function circle_fouce($my_member_id,$firend_member_id){
		$model=Model();
		$data=array();
		$firendMbmeberId=array();
		$firendMbmeberId['friend_frommid']   = $my_member_id;
		$firendMbmeberId['friend_tomid']     = $firend_member_id;
		$isfirend = $model->table('sns_friend')->where($firendMbmeberId)->find();
		$isfirend?$data['isfriend']=true:$data['isfriend']=false;
		$hFouce   = $model ->table('sns_friend')->where(array('friend_tomid'=>$firend_member_id))->count();
		$data['fouce_Num']=$hFouce;
		//
		$circle_id=array();
		$id=$model->table('circle')->where(array('circle_masterid'=>$firend_member_id))->field('circle_id')->select();
		foreach($id as $value){
			$circle_id[]=$value['circle_id'];
		}
		$themeNum=$model->table('circle_theme')->where(array('circle_id'=>array('in',$circle_id)))->count();
		$data['themeNum']=$themeNum;
		$theme=$model->table('circle_theme')->where(array('circle_id'=>array('in',$circle_id)))->select();
		foreach($theme as $key=>$value){
			$theme[$key]['theme_addtime']=date("Y/m/d",$value['theme_addtime']);
		}
		$data['theme']=$theme;
		//
		$member=$model->table('member')->where(array('member_id'=>$firend_member_id))->field('member_name,member_avatar,member_id')->find();
		$member['member_avatar']=getMemberAvatarForID($firend_member_id);
		$data['member']=$member;
		
		return $data;
	}
	public function circle_list($member_id,$circle_id){
		$model=Model();
		$datas=array();
		$result=$model->table('circle')->where(array('circle_id'=>$circle_id))->find();
		if($result['circle_desc']==''){
			$result['circle_desc']='暂无描述';
		}
		if($result['circle_img']==''){
			$result['circle_img']=circleLogoM($result['circle_id']);
		}
		$datas['circle']=$result;
		$circle_theme=$model->table('circle_theme')->where(array('circle_id'=>$result['circle_id']))->order('theme_addtime desc')->select();
		foreach($circle_theme as $key=>$value){
			$circle_theme[$key]['member_avatar']=getMemberAvatarForID($value['member_id']);
			$circle_theme[$key]['theme_addtime']=date("Y-m-d",$value['theme_addtime']);
			$circle_theme[$key]['theme_content']=replaceUBBTagM($value['theme_content']);
		}
		$isjoin =Model()->table('circle_member')->where(array('member_id'=>$member_id,'circle_id'=>$circle_id))->find();
		$isjoin?$datas['isjoin']=1:$datas['isjoin']=0;
		$datas['circle_theme']=$circle_theme;
		return $datas;
	}
	public function select_circle(){
		$model=Model();
		$result=$model->table('circle')->order('circle_addtime desc')->limit(1000)->select();
		
		foreach($result as $key=>$value){
			$result[$key]['circle_img']=circleLogoM($value['circle_id']);
			$result[$key]['circle_member']=$model->table('circle_member')->where(array('circle_id'=>$value['circle_id']))->count();
			$result[$key]['theme_num']=$model->table('circle_theme')->where(array('circle_id'=>$value['circle_id']))->count();
			$isjoin=$model->table('circle_member')->where(array('member_id'=>$_SESSION['member_id'],'circle_id'=>$value['circle_id']))->find();
			$isjoin?$result[$key]['isjoin']=true:$result[$key]['isjoin']=false;
			if($value['circle_masterid']==$_SESSION['member_id']){
				$result[$key]['isMaster']=true;
			}else{
				$result[$key]['isMaster']=false;
			}
		}
		// var_dump($result);die;
		return $result;
		
	}	
	public function makefriend($where,$insert){
		$model=Model();
		$is=$model->table('sns_friend')->where($where)->find();
		$is?$insert['friend_followstate']=2:$insert['friend_followstate']=1;
		if($is)$model->table('sns_friend')->update(array('friend_followstate'=>2));
		$result=$model->table('sns_friend')->insert($insert);
		if($result){return true;}else{return false;}
		
	} 

	public function deletFocus($where,$delet){
		$model=Model();
		$is=$model->table('sns_friend')->where($where)->find();
		if($is)$model->table('sns_friend')->where($where)->update(array('friend_followstate'=>1));
		$result=$model->table('sns_friend')->where($delet)->delete();
		if($result){
			return true;
		}else{
			return false;
		}
		
	}
	public function searchFriend($member_id){
		$model=Model();
		$result=$model->table('sns_friend')->where(array('friend_frommid'=>$member_id))->select();
		foreach($result as $key =>$value){
			$member_sex=$model->table('member')->where(array('member_id'=>$value['friend_tomid']))->field('member_sex')->find();
			$result[$key]['circle_member']     = $model->table('sns_friend')->where(array('friend_tomid'=>$value['friend_tomid']))->count();
			$result[$key]['theme_num']         = $model->table('circle_theme')->where(array('member_id'=>$value['friend_tomid']))->count();
			$result[$key]['friend_tomavatar']  = getMemberAvatarForID($value['friend_tomid']);
			switch($member_sex['member_sex']){
				case null :
				$result[$key]['member_sex']        = false;
				break;
				case 1 :
				$result[$key]['member_sex']        = 'images'.DS.'circle_1'.DS.'boy.png';
				break;
				case 2 :
				$result[$key]['member_sex']        = 'images'.DS.'circle_1'.DS.'girl.png';
				break;
				case 3 :
				$result[$key]['member_sex']        = false;
			}
			// $result[$key]['member_sex']        = $member_sex['member_sex'];
		}
		return $result;
	}
	public function shar(){
		$model=Model();
		$result=$model->table('sns_sharegoods')->limit(1000)->order('share_addtime desc')->select();
		return $result;
	}
	public function shareComment($share_id){
		$model=Model();
		$result=$model->table('sns_conmentbask')->where(array('share_id'=>$share_id))->select();
		return $result;
	}
	public function updateSharContent($content,$share_id){
		$model=Model();
		$beforeContent=$model->table('sns_sharegoods')->where(array('share_id'=>$share_id))->field('share_content')->find();
		$afterContent=$beforeContent['share_content'].$content;
		$result=$model->table('sns_sharegoods')->where(array('share_id'=>$share_id))->update(array('share_content'=>$afterContent));
		return $result;
	}
	public function shareGoods(){
		$model=Model();
		$nearGoods=$model->table('order_goods,order')
						->field('order_goods.goods_id,order_goods.goods_name,order_goods.goods_image,order_goods.goods_price,order.store_id as store_id')
						->join('inner join')->on('order_goods.order_id=order.order_id')
						->where(array('order.buyer_id'=>$_SESSION['member_id'], 'order.order_state'=>40, 'finnshed_time'=>array('gt',time()-60*60*24*30*3)))
						->distinct(true)->select();
		$myGoods=$model->table('goods,favorites')
						->field('goods.goods_id,goods.goods_name,goods.goods_image,goods.goods_price as goods_price,goods.store_id')
						->join('inner join')->on('goods.goods_id=favorites.fav_id')
						->where(array('favorites.fav_type'=>'goods', 'favorites.member_id'=>$_SESSION['member_id']))
						->distinct(true)->select();
						
		$data=array_merge($nearGoods,$myGoods)	;			
		return $data;
	}
	public function create_theme($insert){
		$model=Model();
		$circle_name=$model->table('circle')->where(array('circle_id'=>$insert['circle_id']))->field('circle_name')->find();
		$insert['circle_name']=$circle_name['circle_name'];
		$result=$model->table('circle_theme')->insert($insert);
		return $result;
	}	
	public function theme_detail($theme_id){
		$data=array();
		$model=Model();
		$result=$model->table('circle_theme')->where(array('theme_id'=>$theme_id))->find();
		$result['theme_addtime']=date("Y-m-d",$result['theme_addtime']);
		$result['theme_content']=replaceUBBTagM($result['theme_content']);
		$result['member_avatar']=getMemberAvatarForID($result['member_id']);
		
		$data['theme']=$result;
		$like_id=$model->table('circle_like')->where(array('theme_id'=>$theme_id))->select();
		if($like_id){
			foreach($like_id as $key =>$value){
				$like_id[$key]['like_img']=getMemberAvatarForID($value['member_id']);
			}
		}
		$data['like']=$like_id;
		$threply=$model->table('circle_threply')->where(array('theme_id'=>$theme_id))->order('reply_addtime desc')->select();
		if($threply){
			foreach($threply as $key =>$value){
				$threply[$key]['member_avatar']=getMemberAvatarForID($value['member_id']);
				$threply[$key]['reply_content']=replaceUBBTagM($value['reply_content']);
				$threply[$key]['reply_addtime']=date("Y-m-d",$value['reply_addtime']);
			}
		}
		
		$data['threply']=$threply;
		
		$join=$model->table('circle_member')->where(array('member_id'=>$_SESSION['member_id'],'circle_id'=>$result['circle_id']))->find();
		$join?$data['join']=true:$data['join']=false;
		
		$like=$model->table('circle_like')->where(array('theme_id'=>$theme_id,'member_id'=>$_SESSION['member_id']))->find();
		$like?$data['islike']=1:$data['islike']=0;
		return $data;
		
	}
	
	
	public function like($theme_id,$circle_id,$member_id,$like){
		$model=Model();
		if($like){
			$deletlike=$model->table('circle_like')->where(array('theme_id'=>$theme_id,'member_id'=>$member_id))->delete();
			if($deletlike){
				$result=$model->table('circle_theme')->where(array('theme_id'=>$theme_id))->update(array('theme_likecount'=>array('exp','theme_likecount-1')));
				if($result){return true;}else{return false;}
			}else{return false;}
		}else{
			$insert=array();
			$insert['theme_id']         = $theme_id;
			$insert['circle_id']        = $circle_id;
			$insert['member_id']        = $member_id;
			$insertlike=$model->table('circle_like')->insert($insert);
			if($insertlike){
				$result=$model->table('circle_theme')->where(array('theme_id'=>$theme_id))->update(array('theme_likecount'=>array('exp','theme_likecount+1')));
				if($result){return true;}else{return false;}
			}else{return false;}
		}
	}
	public function exit_circle($circle_id){
		$model=Model();
		$result=$model->table('circle_member')->where(array('circle_id'=>$circle_id, 'member_id'=>$_SESSION['member_id']))->delete();
		if($result){
			$chang=$model->table('circle')->where(array('circle_id'=>$circle_id))->update(array('circle_mcount'=>array('exp','circle_mcount-1')));
			if($chang){return true;}else{return false;}
			
		}else{return false;}
	}
	public function updateContent($theme_id,$imgurl){
		$model=Model();
		$content=$model->table('circle_theme')->where(array('theme_id'=>$theme_id))->field('theme_content')->find();
		$upcontent=$content['theme_content'].$imgurl;
		$result=$model->table('circle_theme')->where(array('theme_id'=>$theme_id))->update(array('theme_content'=>$upcontent));
		
		if($result){
			return true;
			
		}else{
			return false;
		}
		
	}
	public function updatecover($theme_id,$theme_cover){
		$model=Model();
		$result=$model->table('circle_theme')->where(array('theme_id'=>$theme_id))->update(array('theme_cover'=>$theme_cover));
		if($result){
			return true;
		}else{
			return false;
		}
	}
	public function my_circle($member_id){
		$model=Model();
		$circle_id=array();
		$data=array();
		$myAddCircle=$model->table('circle_member')->where(array('member_id'=>$member_id))->field('circle_id')->select();
		foreach($myAddCircle as $value){
			$circle_id[]=$value['circle_id'];
		}
		$myCircle=$model->table('circle')->where(array('circle_masterid'=>$member_id))->select();
		$myAdd=$model->table('circle')->where(array('circle_id'=>array('in',$circle_id)))->select();
		// $result=array_merge_recursive($myAdd,$myCircle);
		foreach($myAdd as $k=>$v){
			$myAdd[$k]['circle_log']=circleLogoM($v['circle_id']);
			if($v['circle_masterid']==$member_id){
				$myAdd[$k]['ismy']=true;
			}else{
				$myAdd[$k]['ismy']=false;
			}
			
		}
		$data['circle']=$myAdd;
		$data['info']=array(
		'member_avatar'   => getMemberAvatarForID($member_id),
		'myCount'         => count($myCircle),
		'joinCount'       => count($myAdd),
		'member_name'     => Model('member')->getfby_member_id($_SESSION['member_id'],'member_name')
		);
		return $data;
	}
	public function circle_info($circle_id){
		$model=Model();
		$result=$model->table('circle')->where(array('circle_id'=>$circle_id))->find();
		return $result;
		
	}
}