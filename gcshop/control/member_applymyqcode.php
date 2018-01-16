<?php
/**
 * 用户中心
 ***/


defined('GcWebShop') or exit('Access Invalid!');
	

class member_applymyqcodeControl extends BaseMemberControl {
	/**
	 * 用户中心
	 *
	 * @param
	 * @return
	 */
	public function indexOp() {
		$model		= Model('member');
		$isseller	= $model->getfby_member_id($_SESSION['member_id'],'is_seller');   
		if($isseller==1)
		{showDialog('亲爱的创神，您已经有自己的专属二维码了', 'notice','','','3','','','','','',1);}
		$this->memberOp();
	}
	/**
	 * 我的资料【用户中心】
	 *
	 * @param
	 * @return
	 */
	public function memberOp() {

		Language::read('member_home_member');
		$lang	= Language::getLangContent();

		$model_member	= Model('member');

		if (chksubmit()){

			$member_array	= array();
			$member_array['member_truename']	= $_POST['member_truename'];
			$member_array['member_sex']			= $_POST['member_sex'];
			$member_array['member_qq']			= $_POST['member_qq'];
			$member_array['member_ww']			= $_POST['member_ww'];
			$member_array['member_areaid']		= $_POST['area_id'];
			$member_array['member_cityid']		= $_POST['city_id'];
			$member_array['member_provinceid']	= $_POST['province_id'];
			$member_array['member_areainfo']	= $_POST['area_info'];
			if (strlen($_POST['birthday']) == 10){
				$member_array['member_birthday']	= $_POST['birthday'];
			}
			$member_array['member_privacy']		= serialize($_POST['privacy']);

			$member_array['member_mobile']		= $_POST['member_mobile'];
			$member_array['member_email']		= $_POST['member_email'];
			$member_array['member_code']		= $_POST['member_code'];
			$member_array['member_accountname']	= $_POST['member_accountname'];
			$member_array['member_account']		= $_POST['member_account'];
			$member_array['member_bankname']	= $_POST['member_bankname'];
			$member_array['is_seller']			= 0;//默认是0

			$update = $model_member->editMember(array('member_id'=>$_SESSION['member_id']),$member_array);

			$message = $update? $lang['nc_common_save_succ']."　请等待审核" : $lang['nc_common_save_fail'];
			showDialog($message,'reload',$update ? 'succ' : 'error');
		}

		if($this->member_info['member_privacy'] != ''){
			$this->member_info['member_privacy'] = unserialize($this->member_info['member_privacy']);
		} else {
		    $this->member_info['member_privacy'] = array();
		}
		Tpl::output('member_info',$this->member_info);

		self::profile_menu('member','member');
		Tpl::output('menu_sign','applymyqcode');
		Tpl::output('menu_sign_url','index.php?gct=member_applymyqcode&gp=member');
		Tpl::output('menu_sign1','baseinfo');
		Tpl::showpage('member_applymyqcode');
	}
	/**
	 * 我的资料【更多个人资料】
	 *
	 * @param
	 * @return
	 */
	public function moreOp(){
		/**
		 * 读取语言包
		 */
		Language::read('member_home_member');

		// 实例化模型
		$model = Model();

		if(chksubmit()){
			$model->table('sns_mtagmember')->where(array('member_id'=>$_SESSION['member_id']))->delete();
			if(!empty($_POST['mid'])){
				$insert_array = array();
				foreach ($_POST['mid'] as $val){
					$insert_array[] = array('mtag_id'=>$val,'member_id'=>$_SESSION['member_id']);
				}
				$model->table('sns_mtagmember')->insertAll($insert_array,'',true);
			}
			showDialog(Language::get('nc_common_op_succ'),'','succ');
		}

		// 用户标签列表
		$mtag_array = $model->table('sns_membertag')->order('mtag_sort asc')->limit(1000)->select();

		// 用户已添加标签列表。
		$mtm_array = $model->table('sns_mtagmember')->where(array('member_id'=>$_SESSION['member_id']))->select();
		$mtag_list	= array();
		$mtm_list	= array();
		if(!empty($mtm_array) && is_array($mtm_array)){
			// 整理
			$elect_array = array();
			foreach($mtm_array as $val){
				$elect_array[]	= $val['mtag_id'];
			}
			foreach ((array)$mtag_array as $val){
				if(in_array($val['mtag_id'], $elect_array)){
					$mtm_list[] = $val;
				}else{
					$mtag_list[] = $val;
				}
			}
		}else{
			$mtag_list = $mtag_array;
		}
		Tpl::output('mtag_list', $mtag_list);
		Tpl::output('mtm_list', $mtm_list);

		self::profile_menu('member','more');
		Tpl::output('menu_sign','profile');
		Tpl::output('menu_sign_url','index.php?gct=member_information&gp=member');
		Tpl::output('menu_sign1','baseinfo');
		Tpl::showpage('member_profile.more');
	}

	public function uploadOp() {
		if (!chksubmit()){
			redirect('index.php?gct=member_information&gp=avatar');
		}
		import('function.thumb');
		Language::read('member_home_member,cut');
		$lang	= Language::getLangContent();
		$member_id = $_SESSION['member_id'];

		//上传图片
		$upload = new UploadFile();
		$upload->set('thumb_width',	500);
		$upload->set('thumb_height',499);
		$ext = strtolower(pathinfo($_FILES['pic']['name'], PATHINFO_EXTENSION));
		$upload->set('file_name',"avatar_$member_id.$ext");
		$upload->set('thumb_ext','_new');
		$upload->set('ifremove',true);
		$upload->set('default_dir',ATTACH_AVATAR);
		if (!empty($_FILES['pic']['tmp_name'])){
			$result = $upload->upfile('pic');
			if (!$result){
				showMessage($upload->error,'','html','error');
			}
		}else{
			showMessage('上传失败，请尝试更换图片格式或小图片','','html','error');
		}
		self::profile_menu('member','avatar');
		Tpl::output('menu_sign','profile');
		Tpl::output('menu_sign_url','index.php?gct=member_information&gp=member');
		Tpl::output('menu_sign1','avatar');
		Tpl::output('newfile',$upload->thumb_image);
		Tpl::output('height',get_height(BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR.'/'.$upload->thumb_image));
		Tpl::output('width',get_width(BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR.'/'.$upload->thumb_image));
		Tpl::showpage('member_profile.avatar');
	}

	/**
	 * 裁剪
	 *
	 */
	public function cutOp(){
		if (chksubmit()){
			$thumb_width = 120;
			$x1 = $_POST["x1"];
			$y1 = $_POST["y1"];
			$x2 = $_POST["x2"];
			$y2 = $_POST["y2"];
			$w = $_POST["w"];
			$h = $_POST["h"];
			$scale = $thumb_width/$w;
			$_POST['newfile'] = str_replace('..', '', $_POST['newfile']);
			if (strpos($_POST['newfile'],"avatar_{$_SESSION['member_id']}_new.") !== 0) {
			    redirect('index.php?gct=member_information&gp=avatar');
			}
			$src = BASE_UPLOAD_PATH.DS.ATTACH_AVATAR.DS.$_POST['newfile'];
			$avatarfile = BASE_UPLOAD_PATH.DS.ATTACH_AVATAR.DS."avatar_{$_SESSION['member_id']}.jpg";
			import('function.thumb');
			$cropped = resize_thumb($avatarfile, $src,$w,$h,$x1,$y1,$scale);
			@unlink($src);
			Model('member')->editMember(array('member_id'=>$_SESSION['member_id']),array('member_avatar'=>'avatar_'.$_SESSION['member_id'].'.jpg'));
			$_SESSION['avatar'] = 'avatar_'.$_SESSION['member_id'].'.jpg';
			redirect('index.php?gct=member_information&gp=avatar');
		}
	}

	/**
	 * 更换头像
	 *
	 * @param
	 * @return
	 */
	public function avatarOp() {
		Language::read('member_home_member,cut');
		$member_info = Model('member')->getMemberInfoByID($_SESSION['member_id'],'member_avatar');
		Tpl::output('member_avatar',$member_info['member_avatar']);
		self::profile_menu('member','avatar');
		Tpl::output('menu_sign','profile');
		Tpl::output('menu_sign_url','index.php?gct=member_information&gp=member');
		Tpl::output('menu_sign1','avatar');
		Tpl::showpage('member_profile.avatar');
	}
	
	//专属二维码
	public function excqcodeOp() {
		Language::read('member_home_member');
		$member_info = Model('member')->getMemberInfoByID($_SESSION['member_id'],'is_seller');
		Tpl::output('is_seller',$member_info['is_seller']);
			if ($member_info['is_seller'] <> 1)
			{
			showMessage(Language::get('ref_make_qcode_ban'),'','html','error');
			redirect('index.php?gct=login&ref_url='.urlencode(request_uri()));
			
			}
			
		$condition_arr = array();
		$condition_arr['sc_memberid'] = $member_info['member_id'];
		//分页
		$page	= new Page();
		$page->setEachNum(5);
		$page->setStyle('admin');
		
		//查询销售列表
		$salescredit_model = Model('salescredit');
		$list_log = $salescredit_model->getSalescreditLogList($condition_arr, $page,'*','');
		
		
		//我的客户
		$model_clist = Model('member');
		$condition = array();
		$condition['refer_id'] = intval($_SESSION["member_id"]);
		$clients_list = $model_clist->getMemberList($condition, '', 5);
		
		$model_tlist = Model('member');
		$where = array();
		$where['sa_id'] = intval($_SESSION["sa_id"]);
		$order = 'member_id';
		$team_list = $model_tlist->getMemberList($where,'*',5,$order);

		//信息输出
		self::profile_menu('member','excqcode');
		Tpl::output('menu_sign','profile');
		Tpl::output('menu_sign_url','index.php?gct=member_information&gp=member');
		Tpl::output('menu_sign1','excqcode');
		Tpl::output('show_page',$page->show());
		Tpl::output('list_log',$list_log);
		Tpl::output('clients_list',$clients_list);
		Tpl::output('team_list',$team_list);
		Tpl::showpage('member_profile.excqcode');
		}
		
	//我的销售团队	
	public function member_my_teamOp() {
		$model_alltlist = Model('member');
		$where = array();
		$where['sa_id'] = intval($_SESSION["sa_id"]);
		$order = 'member_id';
		$all_team_list = $model_alltlist->getMemberList($where,'*',0,$order);

		Tpl::output('all_team_list',$all_team_list);
		Tpl::showpage('member_my_team','null_layout');

	}
	
	//我的销售业绩	
	public function member_my_perfOp() {
		
		$salescredit_model = Model('salescredit');
		$condition_arr = array();
		$condition_arr['sc_memberid'] = intval($_SESSION["member_id"]);
		$list_log = $salescredit_model->getSalescreditLogList($condition_arr, 0,'*','');

		Tpl::output('list_log',$list_log);
		Tpl::showpage('member_my_perf','null_layout');

	}
	
	//我的客户	
	public function member_my_clientOp() {
		
		$model_clist = Model('member');
		$where = array();
		$where['refer_id'] = intval($_SESSION["member_id"]);
		$clients_list = $model_clist->getMemberList($where,'*',0,$order);

		Tpl::output('clients_list',$clients_list);
		Tpl::showpage('member_my_client','null_layout');

	}
		
		
	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_type,$menu_key='') {
		$menu_array		= array();
		switch ($menu_type) {
			case 'member':
				$menu_array	= array(
				1=>array('menu_key'=>'member',	'menu_name'=>'专属二维码创业启动','menu_url'=>'index.php?gct=member_applymyqcode&gp=member')
				);
				break;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
