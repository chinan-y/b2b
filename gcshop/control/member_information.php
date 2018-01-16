<?php
/**
 * 用户中心
 ***/


defined('GcWebShop') or exit('Access Invalid!');
	

class member_informationControl extends BaseMemberControl {
	 /**
     * 每次导出订单数量
     * @var int
     */
	const EXPORT_SIZE = 1000;
	/**
	 * 用户中心
	 *
	 * @param
	 * @return
	 */
	public function indexOp() {
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
			$member_array['member_nickname']	= $_POST['member_nickname'];
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
			$update = $model_member->editMember(array('member_id'=>$_SESSION['member_id']),$member_array);

			$message = $update? $lang['nc_common_save_succ'] : $lang['nc_common_save_fail'];
			showDialog($message,'reload',$update ? 'succ' : 'error');
		}

		if($this->member_info['member_privacy'] != ''){
			$this->member_info['member_privacy'] = unserialize($this->member_info['member_privacy']);
		} else {
		    $this->member_info['member_privacy'] = array();
		}
		Tpl::output('member_info',$this->member_info);

		self::profile_menu('member','member');
		Tpl::output('menu_sign','profile');
		Tpl::output('menu_sign_url','index.php?gct=member_information&gp=member');
		Tpl::output('menu_sign1','baseinfo');
		Tpl::showpage('member_profile');
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
			if(C('OSS_IS_STORAGE') == 0){
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
			Tpl::output('newfile',$upload->thumb_image);
			Tpl::output('height',get_height(BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR.'/'.$upload->thumb_image));
			Tpl::output('width',get_width(BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR.'/'.$upload->thumb_image));
		}
		//AliyunOSS存储
		if(C('OSS_IS_STORAGE') == 1){
			$oss = Logic('oss');
			$bucketname = IMAGE_BUCKET_NAME;
			
			$extend = pathinfo($_FILES['pic']['name']); 
			$extend = strtolower($extend["extension"]); 
			$newname	= 'avatar_' . $member_id . '.' . $extend;
			$objectname = DIR_UPLOAD.DS.ATTACH_AVATAR.DS.$newname;
			$pathname 	= $_FILES['pic']['tmp_name'];
			$picinfo 	= getimagesize($pathname);
			// 三种图片格式gif、jpg、png，width:120px,height:120px； OSS图片不支持在线裁切
			if($_FILES['pic']['type'] =='image/gif' || $_FILES['pic']['type'] =='image/jpeg' || $_FILES['pic']['type'] =='image/png'){
				if($picinfo[0] == $picinfo[1] && $picinfo[0] < 500){
					 $result = $oss->uploadFile($bucketname, $objectname, $pathname, $options);
				}

			}
		Tpl::output('newfile',$newname);
		Tpl::output('height',$picinfo[0]);
		Tpl::output('width',$picinfo[1]);

		}
		
		self::profile_menu('member','avatar');
		Tpl::output('menu_sign','profile');
		Tpl::output('menu_sign_url','index.php?gct=member_information&gp=member');
		Tpl::output('menu_sign1','avatar');

		Tpl::showpage('member_profile.avatar');
	}

	/**
	 * 裁剪
	 *
	 */
	public function cutOp(){
		if (chksubmit()){
			if(C(OSS_IS_STORAGE) == 0){
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
				Model('member')->editMember(array('member_id'=>$_SESSION['member_id']),array('member_avatar'=>'avatar_'.$_SESSION['member_id'].'.jpg'));//只保存jpg格式有问题
				$_SESSION['avatar'] = 'avatar_'.$_SESSION['member_id'].'.jpg';
				redirect('index.php?gct=member_information&gp=avatar');
			}
			if(C(OSS_IS_STORAGE) == 1){//AliyunOSS存储时不裁剪
				Model('member')->editMember(array('member_id'=>$_SESSION['member_id']),array('member_avatar'=>'avatar_'.$_SESSION['member_id'].'.jpg'));//只保存jpg格式有问题
				$_SESSION['avatar'] = 'avatar_'.$_SESSION['member_id'].'.jpg';
				redirect('index.php?gct=member_information&gp=avatar');
			}
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
		$member_model = Model('member');
		$sales_model = Model('salescredit');
		$prede_model = Model('predeposit');
		
		$member_info = $member_model->getMemberInfoByID($_SESSION['member_id'],'is_seller');
		Tpl::output('is_seller',$member_info['is_seller']);
		if ($member_info['is_seller'] != 1){
			showMessage(Language::get('ref_make_qcode_ban'),'','html','error');
			redirect('index.php?gct=login&ref_url='.urlencode(request_uri()));
		}
			
		$condition_arr = array();
		$condition_arr['sc_memberid'] = $_SESSION['member_id'];
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		
		//查询销售列表
		$list_log = $sales_model->getSalescreditLogList($condition_arr, $page,'*','');
		
		//我的客户
		// $field = 'member_id,member_name,member_truename,member_time,member_mobile,member_login_num';
		// $clients_list = $member_model->getMemberList(array('refer_id'=>intval($_SESSION["member_id"])), $field, 10);
		
		//我的团队
		// $team_list = $member_model->getMemberList(array('sa_id'=>intval($_SESSION["sa_id"])),'*',10,'member_id');

		//当天订单量和收益
		$achievement =$member_model->member_earnings(array('sc_memberid' => $_SESSION["member_id"]));
		
		//信息输出
		self::profile_menu('qcode','excqcode');
		Tpl::output('menu_sign','profile');
		Tpl::output('menu_sign_url','index.php?gct=member_information&gp=excqcode');
		Tpl::output('menu_sign1','excqcode');
		Tpl::output('show_page',$page->show());
		Tpl::output('list_log',$list_log);
		Tpl::output('achievement',$achievement);
		// Tpl::output('clients_list',$clients_list);
		// Tpl::output('team_list',$team_list);
		Tpl::showpage('member_profile.excqcode');
		}
		
	//团队基本信息	
	public function member_teaminfoOp() {
		Language::read('member_home_member');
		$model_partner = Model('partner');
		$partnerinfo = $model_partner->getPartnerInfo(array('member_id'=>$_SESSION["member_id"]), '*');
		$model_salearea = Model('sales_area');
		$saleareainfo = $model_salearea->getSalesAreaInfo(array('sa_manager_id'=>$_SESSION["member_id"]),'*');
		$model_member = Model('member');
		$memberinfo = $model_member->getMemberInfoByID($_SESSION["member_id"],'*');
		self::profile_menu('qcode','member_teaminfo');
		Tpl::output('menu_sign','profile');
		Tpl::output('menu_sign_url','index.php?gct=member_information&gp=member_teaminfo');
		Tpl::output('menu_sign1','member_teaminfo');
		Tpl::output('partnerinfo',$partnerinfo);
		Tpl::output('saleareainfo',$saleareainfo);
		Tpl::output('memberinfo',$memberinfo);
		Tpl::showpage('member_teaminfo');

	}
		
	//我的销售团队（平台合作方下所有用户）	
	public function member_my_teamOp() {
		Language::read('member_home_member');
		$model_alltlist = Model('member');
		$partner = $model_alltlist->getPartnerId(array('member_id'=>$_SESSION["member_id"]), 'partner_id');
		$where = array();
		$where['saleplat_id'] = intval($partner["partner_id"]);
		$all_team_list = $model_alltlist->getMemberList($where,'*',30);
		self::profile_menu('qcode','member_my_team');
		Tpl::output('menu_sign','profile');
		Tpl::output('menu_sign_url','index.php?gct=member_information&gp=member_my_team');
		Tpl::output('menu_sign1','member_my_team');
		Tpl::output('all_team_list',$all_team_list);
		Tpl::output('show_page',$model_alltlist->showpage(2));
		Tpl::showpage('member_my_team');

	}
	
	
	/*
	//销售员收益订单
		消费收益
		上一级推广收益
		上二级推广收益
		上三级推广收益
	*/
	public function member_my_profitorderOp(){
		Language::read('member_home_member');
		$model_member = Model('member');
		$model_order = Model('order');
		$member_id = $this->member_info['member_id'];
		$is_manager = $this->member_info['is_manager'];
		$is_one = C('one_rank_rebate'); 			//是否开启一级返利
		$is_two = C('two_rank_rebate'); 			//是否开启二级返利
		$is_three = C('three_rank_rebate'); 		//是否开启三级返利

			$condition = array();
			if($_GET['profittype'] =='buy'){
				$condition['buyer_id'] = $member_id;
			}
			if($_GET['profittype'] =='A'){
					$next1_user = $model_member->getMemberList(array('refer_id' => $member_id), 'member_id');
					foreach($next1_user as $value){
					$buyer_id.=$value['member_id'].",";
					}
					$condition['buyer_id']=array('in',$buyer_id);
			}
			if($_GET['profittype'] =='B'){
					$next1_user = $model_member->getMemberList(array('refer_id' => $member_id), 'member_id');
					foreach($next1_user as $value){
					$next1_user_id.=$value['member_id'].",";
					}
					$next2_user = $model_member->getMemberList(array('refer_id' =>array('in',$next1_user_id)), 'member_id');
					foreach($next2_user as $value){
					$next2_user_id.=$value['member_id'].",";
					}
					$condition['buyer_id']=array('in',$next2_user_id);
			}
			if($_GET['profittype'] =='C'){
					$next1_user = $model_member->getMemberList(array('refer_id' => $member_id), 'member_id');
					foreach($next1_user as $value){
					$next1_user_id.=$value['member_id'].",";
					}
					$next2_user = $model_member->getMemberList(array('refer_id' =>array('in',$next1_user_id)), 'member_id');
					foreach($next2_user as $value){
					$next2_user_id.=$value['member_id'].",";
					}
					$next3_user = $model_member->getMemberList(array('refer_id' =>array('in',$next2_user_id)), 'member_id');
					foreach($next3_user as $value){
					$next3_user_id.=$value['member_id'].",";
					}
					$condition['buyer_id']=array('in',$next3_user_id);
			}
			
		
			//订单状态
			if(in_array($_GET['state_type'],array('0','10','20','30','40'))){
				$condition['order_state'] = $_GET['state_type'];
			}
			//用户收益start
			if($member_id) {
				//$member = $model_member->getMemberInfo(array('member_name'=>$_GET['member_name']), 'member_id');//用于筛选销售员条件
				if($_GET['profittype'] =='buy'){//消费收益
					$condition['buyer_id'] = $member_id;
					}
				elseif($_GET['profittype'] =='A'){//A级收益
					if($is_one == 1){
					$clients_list = $model_member->getMemberList(array('refer_id'=>$member_id), 'member_id');
					foreach($clients_list as $value){
						$buyer_id.=$value['member_id'].",";
					}
					}
					$buyer_id = substr($buyer_id,0,strlen($buyer_id)-1);
					$condition['buyer_id'] = array('in',$buyer_id);
					//var_dump($condition['buyer_id']);die;
				}
				elseif($_GET['profittype'] =='B'){
					if($is_one == 1){//B级收益
					$clients_list = $model_member->getMemberList(array('refer_id'=>$member_id), 'member_id');
					foreach($clients_list as $value){
						$buyer_id1.=$value['member_id'].",";
						if($is_two == 1){	
							$two = $model_member->getMemberList(array('refer_id'=>$value['member_id']), 'member_id');
							foreach($two as $val){
								$buyer_id2.= $val['member_id'].",";
							}
						}
					}
					}
					$buyer_id2 = substr($buyer_id2,0,strlen($buyer_id2)-1);
					$condition['buyer_id'] = array('in',$buyer_id2);
				}
				elseif($_GET['profittype'] =='C'){
				if($is_one == 1){//C级收益
					$clients_list = $model_member->getMemberList(array('refer_id'=>$member_id), 'member_id');
					foreach($clients_list as $value){
						$buyer_id1.=$value['member_id'].",";
						if($is_two == 1){
							$two = $model_member->getMemberList(array('refer_id'=>$value['member_id']), 'member_id');
							foreach($two as $val){
								$buyer_id2.= $val['member_id'].",";
								if($is_three == 1){
									$three = $model_member->getMemberList(array('refer_id'=>$val['member_id']), 'member_id');
									foreach($three as $v){
										$buyer_id3.= $v['member_id'].",";
									}
								}
							}
						}
					}
				}
					$buyer_id3 = substr($buyer_id3,0,strlen($buyer_id3)-1);
					$condition['buyer_id'] = array('in',$buyer_id3);
				}
				else{//全部收益
					if($is_one == 1){
					$clients_list = $model_member->getMemberList(array('refer_id'=>$member_id), 'member_id');
					foreach($clients_list as $value){
						$buyer_id1.=$value['member_id'].",";
						if($is_two == 1){	
							$two = $model_member->getMemberList(array('refer_id'=>$value['member_id']), 'member_id');
							foreach($two as $val){
								$buyer_id2.= $val['member_id'].",";
								if($is_three == 1){
									$three = $model_member->getMemberList(array('refer_id'=>$val['member_id']), 'member_id');
									foreach($three as $v){
										$buyer_id3.= $v['member_id'].",";
									}
								}
							}
						}
					}
					$buyer_id = substr($buyer_id,0,strlen($buyer_id)-1);
					$condition['buyer_id'] = array('in',$member_id.','.$buyer_id1.','.$buyer_id2.','.$buyer_id3);
				}
				}

			}
			//用户收益end
			
			//订单号
			if($_GET['order_sn']) {
				$condition['order_sn'] = $_GET['order_sn'];
			}
			//下单时间
			$if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
			$if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
			$start_unixtime = $if_start_time ? strtotime($_GET['query_start_date']) : null;
			$end_unixtime = $if_end_time ? strtotime($_GET['query_end_date']): null;
			if ($start_unixtime || $end_unixtime) {
				$condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
			}
			$_SESSION['condition'] = $condition;
			$order_list = $model_order->getOrderList($condition, 30, '*', 'order_id desc','', array('order_goods','order_common','member'));
			
			foreach ($order_list as $key => $order_info) {
				foreach ($order_info['extend_order_goods'] as $value) {
					$member_user = $model_member->getMemberInfo(array('member_id' => $value['buyer_id']), 'member_name, refer_id');
					$superior1 = $model_member->getMemberInfo(array('member_id' => $member_user['refer_id']));
					if(!empty($superior1['member_id'])){
					$order_list[$key]['up1name'] = $superior1['member_name'];
					$superior2 = $model_member->getMemberInfoByID($superior1['refer_id']);
						if(!empty($superior2['member_id'])){
						$order_list[$key]['up2name'] = $superior2['member_name'];
						$superior3 = $model_member->getMemberInfoByID($superior2['refer_id']);
							if(!empty($superior3['member_id'])){
							$order_list[$key]['up3name'] = $superior3['member_name'];
							}
						}
					}
				}
			}
			
		self::profile_menu('qcode','member_my_perf_order');
		Tpl::output('menu_sign','profile');
		Tpl::output('menu_sign_url','index.php?gct=member_information&gp=member_my_profitorder');
		Tpl::output('menu_sign1','member_my_profitorder');
		Tpl::output('is_one',$is_one);
		Tpl::output('is_two',$is_two);
		Tpl::output('is_three',$is_three);
		Tpl::output('order_list',$order_list);
		Tpl::output('show_page',$model_order->showpage());
		Tpl::showpage('member_order_salemanprofit');
	}
	
	
	
	/**
     * 查看订单详细
     *
     */
    public function show_orderOp() {
		Language::read('member_home_member');
        $order_id = intval($_GET['order_id']);
        if ($order_id <= 0) {
            showMessage(Language::get('member_order_none_exist'),'','html','error');
        }
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $order_info = $model_order->getOrderInfo($condition,array('order_goods','order_common','store'));
        if (empty($order_info) || $order_info['delete_state'] == ORDER_DEL_STATE_DROP) {
            showMessage(Language::get('member_order_none_exist'),'','html','error');
        }
        $model_refund_return = Model('refund_return');
        $order_list = array();
        $order_list[$order_id] = $order_info;
        $order_list = $model_refund_return->getGoodsRefundList($order_list,1);//订单商品的退款退货显示
        $order_info = $order_list[$order_id];
        $refund_all = $order_info['refund_list'][0];
        if (!empty($refund_all) && $refund_all['seller_state'] < 3) {//订单全部退款商家审核状态:1为待审核,2为同意,3为不同意
            Tpl::output('refund_all',$refund_all);
        }

        //显示锁定中
        $order_info['if_lock'] = $model_order->getOrderOperateState('lock',$order_info);

        //显示取消订单
        $order_info['if_cancel'] = $model_order->getOrderOperateState('buyer_cancel',$order_info);

        //显示退款取消订单
        $order_info['if_refund_cancel'] = $model_order->getOrderOperateState('refund_cancel',$order_info);

        //显示投诉
        $order_info['if_complain'] = $model_order->getOrderOperateState('complain',$order_info);

        //显示收货
        $order_info['if_receive'] = $model_order->getOrderOperateState('receive',$order_info);

        //显示物流跟踪
        $order_info['if_deliver'] = $model_order->getOrderOperateState('deliver',$order_info);

        //显示评价
        $order_info['if_evaluation'] = $model_order->getOrderOperateState('evaluation',$order_info);

        //显示分享
        $order_info['if_share'] = $model_order->getOrderOperateState('share',$order_info);

        //显示系统自动取消订单日期
        if ($order_info['order_state'] == ORDER_STATE_NEW) {
            //$order_info['order_cancel_day'] = $order_info['add_time'] + ORDER_AUTO_CANCEL_DAY * 24 * 3600;
			$order_info['order_cancel_day'] = $order_info['add_time'] + ORDER_AUTO_CANCEL_DAY + 7 * 24 * 3600;
        }

        //显示快递信息
        if ($order_info['shipping_code'] != '') {
            $express = rkcache('express',true);
            $order_info['express_info']['e_code'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_code'];
            $order_info['express_info']['e_name'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_name'];
            $order_info['express_info']['e_url'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_url'];
        }

        //显示系统自动收获时间
        if ($order_info['order_state'] == ORDER_STATE_SEND) {
			$order_info['order_confirm_day'] = $order_info['delay_time'] + ORDER_AUTO_RECEIVE_DAY + 15 * 24 * 3600;
        }

        //如果订单已取消，取得取消原因、时间，操作人
        if ($order_info['order_state'] == ORDER_STATE_CANCEL) {
            $order_info['close_info'] = $model_order->getOrderLogInfo(array('order_id'=>$order_info['order_id']),'log_id desc');
        }

        foreach ($order_info['extend_order_goods'] as $k=>$value) {
			$commonid = Model('goods')->getGoodsIn(array('goods_id' => $value['goods_id']), 'goods_commonid,store_from,goods_rebate_rate');
			$value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id'], $commonid[0]['goods_commonid']);
			$value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id'], $commonid[0]['goods_commonid']);
            $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
            $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
			$value['goods_rebate_rate'] =$commonid[0]['goods_rebate_rate'];
			$order_info['extend_order_goods'][$k]['store_from'] = $commonid[0]['store_from'];
            if ($value['goods_type'] == 5) {
                $order_info['zengpin_list'][] = $value;
            } else {
                $order_info['goods_list'][] = $value;
            }
        }
				

        if (empty($order_info['zengpin_list'])) {
            $order_info['goods_count'] = count($order_info['goods_list']);
        } else {
            $order_info['goods_count'] = count($order_info['goods_list']) + 1;
        }
		
		//查询上三级用户名
		$model_member = Model('member');
		$member_user = $model_member->getMemberInfoByID($value['buyer_id'], 'member_name, refer_id');
		$superior1 = $model_member->getMemberInfoByID($member_user['refer_id']);
		if(!empty($superior1['member_id'])){
		$upname[$key]['up1name'] = $superior1['member_name'];
		$superior2 = $model_member->getMemberInfoByID($superior1['refer_id']);
			if(!empty($superior2['member_id'])){
			$upname[$key]['up2name'] = $superior2['member_name'];
			$superior3 = $model_member->getMemberInfoByID($superior2['refer_id']);
				if(!empty($superior3['member_id'])){
					$upname[$key]['up3name'] = $superior3['member_name'];
				}
			}
		}
		Tpl::output('upname',$upname);
		
		//查询区域合作方和平台合作方

		if($order_info['platform_member_id'] > 0){
			$model_partner = Model('partner');
			$condition_partner['member_id'] = $order_info['platform_member_id'];
			$partner = $model_partner->getPartnerInfo($condition_partner,'*');
			Tpl::output('partner',$partner);
		}

		if($order_info['area_member_id'] > 0){
			$model_salearea = Model('sales_area');
			$condition_salearea['sa_manager_id'] = $order_info['area_member_id'];
			$salearea = $model_salearea->getSalesAreaInfo($condition_salearea,'*');
			Tpl::output('salearea',$salearea);
		}		
		
		$is_one = C('one_rank_rebate'); 			//是否开启一级返利
		$is_two = C('two_rank_rebate'); 			//是否开启二级返利
		$is_three = C('three_rank_rebate'); 		//是否开启三级返利
		
		Tpl::output('is_one',$is_one);
		Tpl::output('is_two',$is_two);
		Tpl::output('is_three',$is_three);
        Tpl::output('order_info',$order_info);

        //卖家发货信息
        if (!empty($order_info['extend_order_common']['daddress_id'])) {
            $daddress_info = Model('daddress')->getAddressInfo(array('address_id'=>$order_info['extend_order_common']['daddress_id']));
            Tpl::output('daddress_info',$daddress_info);
        }
		//代金券信息
		if (!empty($order_info['extend_order_common']['voucher_code'])) {
			$voucher_list = Model('voucher')->getVoucherList(array('voucher_code'=>$order_info['extend_order_common']['voucher_code']));
			Tpl::output('voucher_list',$voucher_list);
		}

        Tpl::showpage('member_order_salemanprofit_orderinfo');
    }
	
	//团队业绩订单
	public function member_my_teamorderOp(){
		Language::read('member_home_member');
		$model_member = Model('member');
		$model_order = Model('order');
		$member_id = $this->member_info['member_id'];
		$is_manager = $this->member_info['is_manager'];
		$is_one = C('one_rank_rebate'); 			//是否开启一级返利
		$is_two = C('two_rank_rebate'); 			//是否开启二级返利
		$is_three = C('three_rank_rebate'); 		//是否开启三级返利
		
		if($is_manager){
			$condition = array();
			//订单状态
			if(in_array($_GET['state_type'],array('0','10','20','30','40'))){
				$condition['order_state'] = $_GET['state_type'];
			}
			//销售员
			if($_GET['member_name']) {
				$member = $model_member->getMemberInfo(array('member_name'=>$_GET['member_name']), 'member_id');
				$clients_list = $model_member->getMemberList(array('refer_id'=>$member['member_id']), 'member_id');
				foreach($clients_list as $value){
					$buyer_id.=$value['member_id'].",";
					if($is_two == 1){	
						$two = $model_member->getMemberList(array('refer_id'=>$value['member_id']), 'member_id');
						foreach($two as $val){
							$buyer_id.= $val['member_id'].",";
							if($is_three == 1){
								$three = $model_member->getMemberList(array('refer_id'=>$val['member_id']), 'member_id');
								foreach($three as $v){
									$buyer_id.= $v['member_id'].",";
								}
							}
						}
					}
				}
				$buyer_id = substr($buyer_id,0,strlen($buyer_id)-1);
				$condition['buyer_id'] = array('in',$member_id.','.$buyer_id);
			}
			//下单时间
			$if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
			$if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
			$start_unixtime = $if_start_time ? strtotime($_GET['query_start_date']) : null;
			$end_unixtime = $if_end_time ? strtotime($_GET['query_end_date']): null;
			if ($start_unixtime || $end_unixtime) {
				$condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
			}
			//订单号
			if($_GET['order_sn']) {
				$condition['order_sn'] = $_GET['order_sn'];
			}

			$partner = $model_member->getPartnerId(array('member_id'=>$member_id), 'partner_id');
			if($partner['partner_id']){
				$condition['partner_id'] = $partner['partner_id'];
			}else{
				$condition['area_member_id'] = $member_id;
				$condition['area_rebate'] = array('gt',0);
			}
			$_SESSION['condition'] = $condition;
			$order_list = $model_order->getOrderList($condition, 50, '*', 'order_id desc','', array('order_goods','order_common','member'));
			
			foreach ($order_list as $key => $order_info) {
				foreach ($order_info['extend_order_goods'] as $value) {
					$member_user = $model_member->getMemberInfo(array('member_id' => $value['buyer_id']), 'member_name, refer_id');
					$superior1 = $model_member->getMemberInfo(array('member_id' => $member_user['refer_id']));
					if(!empty($superior1['member_id'])){
					$order_list[$key]['up1name'] = $superior1['member_name'];
					$superior2 = $model_member->getMemberInfoByID($superior1['refer_id']);
						if(!empty($superior2['member_id'])){
						$order_list[$key]['up2name'] = $superior2['member_name'];
						$superior3 = $model_member->getMemberInfoByID($superior2['refer_id']);
							if(!empty($superior3['member_id'])){
							$order_list[$key]['up3name'] = $superior3['member_name'];
							}
						}
					}
				}
			}
			
		//查询上三级用户名
		//var_dump($order_list);die;
		self::profile_menu('qcode','member_my_teamorder');
		Tpl::output('menu_sign','profile');
		Tpl::output('menu_sign_url','index.php?gct=member_information&gp=member_my_teamorder');
		Tpl::output('menu_sign1','member_my_teamorder');
		Tpl::output('is_one',$is_one);
		Tpl::output('is_two',$is_two);
		Tpl::output('is_three',$is_three);
		Tpl::output('order_list',$order_list);
		Tpl::output('show_page',$model_order->showpage());
		Tpl::showpage('member_order_salearea.index');
		}
	}


	 /**
     * 设置专属销售员返利率
     */
    public function rebate_editOp() {

		Language::read('member_home_member');
		$model_alltlist = Model('member');
		$where = array();
		$where['member_id'] = intval($_GET["member_id"]);
		$rebatemember = $model_alltlist->getMemberInfo($where,'*');
		
		if($_POST['form_submit']=="ok"){
		$update_array = array();
		$update_array['member_id']	= intval($_POST['member_id']);
		if(!empty($_POST['member_rebate_rate']) && $_POST['member_rebate_rate']>=0 && $_POST['member_rebate_rate']<=50)
			{$update_array['member_rebate_rate']	= floatval($_POST['member_rebate_rate'])/100;
			$result = $model_alltlist->table('member')->where(array('member_id'=>intval($_POST['member_id'])))->update($update_array);
			}
				if($result)
				{ showMessage('设置成功!','index.php?gct=member_information&gp=member_my_team');}
				else
				{showMessage('设置失败!','index.php?gct=member_information&gp=rebate_edit&member_id='.intval($_POST['member_id']));}
			
		}
			

		Tpl::output('rebatemember',$rebatemember);
		Tpl::showpage('member_rebate_edit');
    }
	
	/**
     * 团队经理设置下级销售员的奖励返利率
     */
    public function award_rate_editOp() {
		$model_partner = Model('partner');
		$data = array();
		$data['one_award'] = $_POST['one_award'];
		$data['two_award'] = $_POST['two_award'];
		if((!is_numeric($data['one_award']) && $data['one_award']) || (!is_numeric($data['two_award']) && $data['two_award'])){
			showMessage('请输入正确的数值');
		}
		if(($data['one_award'] + $data['two_award']) > 1){
			showMessage('两级奖励不能超过100%');
		}
		$rate = $model_partner->editPartner(array('member_id'=>$_SESSION['member_id']), $data);
		if($rate){showMessage('设置成功');}
	}
	
	//我的销售业绩	
	public function member_my_perfOp() {
		Language::read('member_home_member');
		$salescredit_model = Model('salescredit');
		$condition_arr = array();
		$condition_arr['sc_memberid'] = intval($_SESSION["member_id"]);
		$list_log = $salescredit_model->getSalescreditList($condition_arr, 30,'*');
		self::profile_menu('qcode','member_my_perf');
		Tpl::output('menu_sign','profile');
		Tpl::output('menu_sign_url','index.php?gct=member_information&gp=member_my_perf');
		Tpl::output('menu_sign1','member_my_perf');
		Tpl::output('list_log',$list_log);
		Tpl::output('show_page',Model('salescredit_log')->showpage(2));
		Tpl::showpage('member_my_perf');

	}
	
	//我的用户	
	public function member_my_clientOp() {
		Language::read('member_home_member');
		$model_clist = Model('member');
		$is_two = C('two_rank_rebate'); 			//是否开启二级返利
		$is_three = C('three_rank_rebate'); 		//是否开启三级返利
		$where = array();
		$where['refer_id'] = $this->member_info['member_id'];
		$field = 'member_id,member_name, member_mobile, member_time, member_login_num' ;
		$clients_list = $model_clist->getMemberList($where, $field);
		if($is_two == 1){
			foreach($clients_list as $value){
				$two = $model_clist->getMemberList(array('refer_id'=>$value['member_id']), $field);
				foreach($two as $val){
					$two_list[] = $val;
					if($is_three == 1){
						$three = $model_clist->getMemberList(array('refer_id'=>$val['member_id']), $field);
						foreach($three as $v){
							$three_list[] = $v;
						}
					}
				}
			}
		}
		self::profile_menu('qcode','member_my_client');
		Tpl::output('menu_sign','profile');
		Tpl::output('menu_sign_url','index.php?gct=member_information&gp=member_my_client');
		Tpl::output('menu_sign1','member_my_client');
		Tpl::output('clients_list',$clients_list);
		Tpl::output('two_list',$two_list);
		Tpl::output('is_two',$is_two);
		Tpl::output('is_three',$is_three);
		Tpl::output('three_list',$three_list);
		// Tpl::output('show_page',$model_clist->showpage(2));
		Tpl::showpage('member_my_client');


	}

	//返利商品	
	public function goodslistOp() {
		Language::read('member_home_member');
		$goods_info = Model();
		$goods_list =$goods_info->table('goods')->page(30)->where('goods_state=1')->order('goods_rebate_rate desc')->select();
		
		self::profile_menu('qcode','goodslist');
		Tpl::output('menu_sign','profile');
		Tpl::output('menu_sign_url','index.php?gct=member_information&gp=excqcode');
		Tpl::output('menu_sign1','goodslist');
		Tpl::output('goods_list',$goods_list);
		Tpl::output('show_page',Model('goods')->where('goods_state=1')->page(30)->showpage(2));
		Tpl::showpage('goodslist');

	}
		//返利商品订单	
	public function member_myorderOp() {
		Language::read('member_home_member');

		$referid = intval($_SESSION["member_id"]);
		$memberinfo=Model('member')->where(array('refer_id'=>$referid))->select();///getfby_member_id($referid,'member_id');  

		$order_info  = Model();
		$where= "refer_id='".intval($referid)."' OR member_id='".intval($referid)."' AND order.goods_rebate_amount > '0'";
		// $where['refer_id'] = intval($referid);
		// $where['order.goods_rebate_amount'] = array('gt',0);
		
		$order_list = $order_info->table('order,member,order_goods')->join('inner,inner')->on('order.buyer_id=member_id,order.order_id=order_goods.order_id')->where($where)->order('order.order_id desc')->page(20)->select();

		self::profile_menu('qcode','member_myorder');
		Tpl::output('menu_sign','profile');
		Tpl::output('menu_sign_url','index.php?gct=member_information&gp=member_myorder');
		Tpl::output('menu_sign1','member_myorder');
		Tpl::output('memberinfo',$memberinfo);
		Tpl::output('order_list',$order_list);
		Tpl::output('show_page',$order_info->showpage(2)) ;
		Tpl::showpage('member_myorder');

	}

/**
	 * 导出
	 *
	 */
	public function export_step1Op(){
		$lang	= Language::getLangContent();
		$model_member = Model('member');
		$model_order = Model('order');
		$member_id = $this->member_info['member_id'];
		$is_manager = $this->member_info['is_manager'];
		$is_one = C('one_rank_rebate'); 			//是否开启一级返利
		$is_two = C('two_rank_rebate'); 			//是否开启二级返利
		$is_three = C('three_rank_rebate'); 		//是否开启三级返利

        	$condition = array();
			//订单状态
			if(in_array($_GET['state_type'],array('0','10','20','30','40'))){
				$condition['order_state'] = $_GET['state_type'];
			}
			//销售员
			if($_GET['member_name']) {
				$member = $model_member->getMemberInfo(array('member_name'=>$_GET['member_name']), 'member_id');
				$clients_list = $model_member->getMemberList(array('refer_id'=>$member['member_id']), 'member_id');
				foreach($clients_list as $value){
					$buyer_id.=$value['member_id'].",";
					if($is_two == 1){	
						$two = $model_member->getMemberList(array('refer_id'=>$value['member_id']), 'member_id');
						foreach($two as $val){
							$buyer_id.= $val['member_id'].",";
							if($is_three == 1){
								$three = $model_member->getMemberList(array('refer_id'=>$val['member_id']), 'member_id');
								foreach($three as $v){
									$buyer_id.= $v['member_id'].",";
								}
							}
						}
					}
				}
				$buyer_id = substr($buyer_id,0,strlen($buyer_id)-1);
				$condition['buyer_id'] = array('in',$member_id.','.$buyer_id);
			}
			//下单时间
			$if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
			$if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
			$start_unixtime = $if_start_time ? strtotime($_GET['query_start_date']) : null;
			$end_unixtime = $if_end_time ? strtotime($_GET['query_end_date']): null;
			if ($start_unixtime || $end_unixtime) {
				$condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
			}
			//订单号
			if($_GET['order_sn']) {
				$condition['order_sn'] = $_GET['order_sn'];
			}

			$partner = $model_member->getPartnerId(array('member_id'=>$member_id), 'partner_id');
			if($partner['partner_id']){
				$condition['partner_id'] = $partner['partner_id'];
			}else{
				$condition['area_member_id'] = $member_id;
				$condition['area_rebate'] = array('gt',0);
			}
			$_SESSION['condition'] = $condition;

		if (!is_numeric($_GET['curpage'])){
			$count = $model_order->getOrderCount($condition);
			$array = array();
			if ($count > self::EXPORT_SIZE ){	//显示下载链接
				$page = ceil($count/self::EXPORT_SIZE);
				for ($i=1;$i<=$page;$i++){
					$limit1 = ($i-1)*self::EXPORT_SIZE + 1;
					$limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
					$array[$i] = $limit1.' ~ '.$limit2 ;
				}
				Tpl::output('list',$array);
				Tpl::output('murl','index.php?gct=order&gp=index');
				Tpl::showpage('export.excel');
			}else{	//如果数量小，直接下载
				$data = $model_order->getOrderList($condition,'','*','order_id desc','', array('order_goods','order_common','member'),self::EXPORT_SIZE);
			foreach ($data as $key => $order_info) {
				foreach ($order_info['extend_order_goods'] as $value) {
					$member_user = $model_member->getMemberInfo(array('member_id' => $value['buyer_id']), 'member_name, refer_id');
					$superior1 = $model_member->getMemberInfo(array('member_id' => $member_user['refer_id']));
					if(!empty($superior1['member_id'])){
					$data[$key]['up1name'] = $superior1['member_name'];
					$superior2 = $model_member->getMemberInfoByID($superior1['refer_id']);
						if(!empty($superior2['member_id'])){
						$data[$key]['up2name'] = $superior2['member_name'];
						$superior3 = $model_member->getMemberInfoByID($superior2['refer_id']);
							if(!empty($superior3['member_id'])){
							$data[$key]['up3name'] = $superior3['member_name'];
							}
						}
					}
				}
			}				
				$this->createExcel($data);
			}
		}else{	//下载
			$limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
			$limit2 = self::EXPORT_SIZE;
			$data = $model_order->getOrderList($condition,'','*','order_id desc','', array('order_goods','order_common','member'),"{$limit1},{$limit2}");
			foreach ($data as $key => $order_info) {
				foreach ($order_info['extend_order_goods'] as $value) {
					$member_user = $model_member->getMemberInfo(array('member_id' => $value['buyer_id']), 'member_name, refer_id');
					$superior1 = $model_member->getMemberInfo(array('member_id' => $member_user['refer_id']));
					if(!empty($superior1['member_id'])){
					$data[$key]['up1name'] = $superior1['member_name'];
					$superior2 = $model_member->getMemberInfoByID($superior1['refer_id']);
						if(!empty($superior2['member_id'])){
						$data[$key]['up2name'] = $superior2['member_name'];
						$superior3 = $model_member->getMemberInfoByID($superior2['refer_id']);
							if(!empty($superior3['member_id'])){
							$data[$key]['up3name'] = $superior3['member_name'];
							}
						}
					}
				}
			}	
			$this->createExcel($data);
		}
	}

	/**
	 * 生成excel
	 *
	 * @param array $data
	 */
	private function createExcel($data = array()){
		Language::read('export');
		import('libraries.excel');
		$excel_obj = new Excel();
		$excel_data = array();
		//设置样式
		$excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
		//header
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'下单日期');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'订单编号');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'店铺名称');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'购买人帐号');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'订单金额');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'商品金额');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'税金');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'运费');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'消费奖励');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'上一级');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'奖励');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'推广提成');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'支付类型');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'完成时间');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'状态');

		//data
		foreach ((array)$data as $k=>$v){
			$tmp = array();
			$tmp[] = array('data'=>date('Y-m-d H:i:s',$v['add_time']));
			$tmp[] = array('data'=>$v['order_sn']);
			$tmp[] = array('data'=>$v['store_name']);
			$tmp[] = array('data'=>$v['buyer_name']);
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['order_amount']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['goods_amount']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['order_tax']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['shipping_fee']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['goods_rebate_amount']));
			$tmp[] = array('data'=>$v['up1name']);
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['one_rebate']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['platform_rebate']));
			$tmp[] = array('data'=>orderPaymentName($v['payment_code']));
			$tmp[] = array('data'=>date('Y-m-d H:i:s',$v['finnshed_time']));
			$tmp[] = array('data'=>orderState($v));
			
			$excel_data[] = $tmp;
		}
		$excel_data = $excel_obj->charset($excel_data,CHARSET);
		$excel_obj->addArray($excel_data);
		$excel_obj->addWorksheet($excel_obj->charset('收益订单',CHARSET));
		$excel_obj->generateXML($excel_obj->charset('团队收益订单',CHARSET).$_GET['curpage'].'-'.date('YmdHis',time()));
	}
		
		
	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_type,$menu_key='') {

		$model = Model('member');
		$where = array();
		$where['member_id'] = $_SESSION['member_id'];
		$is_manager = $model->getMemberInfo($where,'is_manager');  
		$is_seller = $model->getMemberInfo($where,'is_seller');  
		$is_rebate = $model->getMemberInfo($where,'is_rebate'); 
		$is_member_rebate = $model->getMemberInfo($where,'is_member_rebate');
		$is_manager = $is_manager['is_manager'];		
		$is_seller = $is_seller['is_seller'];		
		$is_rebate = $is_rebate['is_rebate'];		
		$is_member_rebate = $is_member_rebate['is_member_rebate'];		
		if($is_seller == 1){
			if( $is_manager==1){
				$menu_array		= array();
				switch ($menu_type) {
				case 'member':
				$menu_array	= array(
				1=>array('menu_key'=>'member',	'menu_name'=>Language::get('home_member_base_infomation'),'menu_url'=>'index.php?gct=member_information&gp=member'),
				2=>array('menu_key'=>'more',	'menu_name'=>Language::get('home_member_more'),'menu_url'=>'index.php?gct=member_information&gp=more'),
				3=>array('menu_key'=>'avatar',	'menu_name'=>Language::get('home_member_modify_avatar'),'menu_url'=>'index.php?gct=member_information&gp=avatar'));
				break;

				case 'qcode':
				$menu_array	= array(
				1=>array('menu_key'=>'excqcode','menu_name'=>'基本信息','menu_url'=>'index.php?gct=member_information&gp=excqcode'),
				2=>array('menu_key'=>'member_my_client','menu_name'=>'我的用户','menu_url'=>'index.php?gct=member_information&gp=member_my_client'),
				3=>array('menu_key'=>'member_my_perf','menu_name'=>'我的收益','menu_url'=>'index.php?gct=member_information&gp=member_my_perf'),
				4=>array('menu_key'=>'member_my_perf_order','menu_name'=>'收益订单','menu_url'=>'index.php?gct=member_information&gp=member_my_profitorder'),
				5=>array('menu_key'=>'goodslist','menu_name'=>'商品返利率','menu_url'=>'index.php?gct=member_information&gp=goodslist'),
				6=>array('menu_key'=>'member_teaminfo','menu_name'=>'团队基本信息','menu_url'=>'index.php?gct=member_information&gp=member_teaminfo'),
				7=>array('menu_key'=>'member_my_team','menu_name'=>'团队用户','menu_url'=>'index.php?gct=member_information&gp=member_my_team'),
				8=>array('menu_key'=>'member_my_teamorder','menu_name'=>'团队收益订单','menu_url'=>'index.php?gct=member_information&gp=member_my_teamorder'));
				break;
				}
			}
			if( $is_manager==0){
				$menu_array		= array();
				switch ($menu_type) {
				case 'member':
				$menu_array	= array(
				1=>array('menu_key'=>'member',	'menu_name'=>Language::get('home_member_base_infomation'),'menu_url'=>'index.php?gct=member_information&gp=member'),
				2=>array('menu_key'=>'more',	'menu_name'=>Language::get('home_member_more'),'menu_url'=>'index.php?gct=member_information&gp=more'),
				3=>array('menu_key'=>'avatar',	'menu_name'=>Language::get('home_member_modify_avatar'),'menu_url'=>'index.php?gct=member_information&gp=avatar'));
				break;

				case 'qcode':
				$menu_array	= array(
				1=>array('menu_key'=>'excqcode','menu_name'=>'基本信息','menu_url'=>'index.php?gct=member_information&gp=excqcode'),
				2=>array('menu_key'=>'member_my_client','menu_name'=>'我的用户','menu_url'=>'index.php?gct=member_information&gp=member_my_client'),
				3=>array('menu_key'=>'member_my_perf','menu_name'=>'我的收益','menu_url'=>'index.php?gct=member_information&gp=member_my_perf'),
				4=>array('menu_key'=>'member_my_perf_order','menu_name'=>'收益订单','menu_url'=>'index.php?gct=member_information&gp=member_my_profitorder'),
				5=>array('menu_key'=>'goodslist','menu_name'=>'商品返利率','menu_url'=>'index.php?gct=member_information&gp=goodslist'));
				break;
				}
			}
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}

	/**
	 * 修改返利率
	 * 
	 */
	public function member_rebate_rateOp(){
		$member_array = array(); 
		$member_array['member_id'] = $_GET['member_id'];
		$member_array['rebate_rate'] = $_GET['rebate_rate'];
		$member_array['is_manager'] = $_GET['is_manager'];
		$member = Model('member');
		if($member_array['is_manager'] == 1){
			exit(json_encode(array('state'=>'false','msg'=>'设置失败!区域经理不能给区域经理设置返利。')));
		}
		$member_array =$member ->member_rebate_rate($member_array);

		if($member_array){
			exit(json_encode(array('state'=>'true','msg'=>'修改成功')));
		}else{
			exit(json_encode(array('state'=>'false','msg'=>'修改失败。')));
		}
	}
}
