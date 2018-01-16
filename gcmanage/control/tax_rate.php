<?php
defined('GcWebShop') or exit('Access Invalid!');

class tax_rateControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('mess');
		$this->search_arr = $_REQUEST;
		/**
		 * 判断系统是否开启海关接口管理
		 */
		if ($GLOBALS['setting_config']['mess_isuse'] != 1 ){
			showMessage(Language::get('mess_api_unable'),'index.php?gct=dashboard&gp=welcome');
		}
	}
	
	/**
	 * hscode商品列表查询
	 */
	    /*
		********************************************************************************************************
		**  跨境电商综合税(T) = 关税(G)*优惠(0)+消费税(C)*优惠(0.7)+增值税(Z)*优惠(0.7)
		**  跨境电商综合税率(Tr) = 0.7(消费税率(Cr)+增值税率(Zr)) / (1-消费税率(Cr))    即  Tr=0.7(Cr+Zr)/(1-Cr)
		**  商品含税价(Ph)  =商品不含税价(P) + 商品不含税价(P) * 跨境电商综合税率(Tr)    即Ph=P(1+Tr)
		**  在已知[商品含税价]的情况下，计算[商品不含税价格]和[商品跨境电商综合税税金]
		**  P = Ph(1-Cr)/(0.7(Cr+Zr)+1-Cr)  =  Ph(1-Cr)/(1-0.3Cr+0.7Zr)
		********************************************************************************************************
		*/

	public function indexOp(){
		$lang	= Language::getLangContent();
		$goods_taxrate = Model('tax_rate');
		
		//删除
		if (chksubmit()){
			if (!empty($_POST['check_ORDER_ID']) && is_array($_POST['check_ORDER_ID']) ){
			    $result = $goods_taxrate->delMessOrder(array('ORDER_ID'=>array('in',$_POST['ORDER_ID'])));
				if ($result) {
			        $this->log(L('nc_del,mess_order').'[ID:'.implode(',',$_POST['check_ORDER_ID']).']',1);
				    showMessage($lang['nc_common_del_succ']);
				}
			}
		    showMessage($lang['nc_common_del_fail']);
		}
		
		if ($_GET['search_field_value'] != '') {
    		switch ($_GET['search_field_name']){
    			case 'hs_code':
    				$condition['hs_code'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    			case 'hs_name':
    				$condition['hs_name'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    		}
		}
		
		$goods_hscode_rate = $goods_taxrate->getHsTaxList($condition,'10','id DESC','*');
		foreach($goods_hscode_rate as $k=>$v){
			$goods_hscode_rate[$k]['KJDS_TAX_RATE'] = 0 * $v['tariff'] + 0.7 * ($v['consumption_tax']+$v['vat_tax'])/(1-$v['consumption_tax']);
		}

		Tpl::output('search_field_name',trim($_GET['search_field_name']));
		Tpl::output('search_field_value',trim($_GET['search_field_value']));

		Tpl::output('goods_hscode_rate',$goods_hscode_rate);
		Tpl::output('page',$goods_taxrate->showpage());
		
		Tpl::showpage('tax_rate.index');
	}
	
	/**
	 * 详情查看
	 */

	public function viewOp(){
		$lang	= Language::getLangContent();
		$model_taxrate = Model('tax_rate');
		
		
		if ($_GET['hsid'] != '') {
			$condition = array();
			$condition['id'] = 	trim($_GET['hsid']);
		}
		
		$taxrate_info = $model_taxrate->getHsTaxInfo($condition,'*');
		$taxrate_info['KJDS_TAX_RATE'] = 0 * $taxrate_info['tariff'] + 0.7 * ($taxrate_info['consumption_tax']+$taxrate_info['vat_tax'])/(1-$taxrate_info['consumption_tax']);
		
		Tpl::output('taxrate_info',$taxrate_info);
		Tpl::output('page',$model_taxrate->showpage());
		
		Tpl::showpage('tax_rate.view');
	}

	/**
	 * 添加
	 */

	public function addOp(){
		$lang	= Language::getLangContent();
		$model_addtaxrate = Model('tax_rate');
		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["hs_code"], "require"=>"true", "message"=>'海关HS编码不能为空'),
			array("input"=>$_POST["hs_name"], "require"=>"true", "message"=>'海关商品名称不能为空'),
			array("input"=>$_POST["tariff"], "require"=>"true", "message"=>'关税不能为空'),
			array("input"=>$_POST["common_tariff"], "require"=>"true", "message"=>'一般关税不能为空'),
			array("input"=>$_POST["consumption_tax"], "require"=>"true", "message"=>'消费税不能为空'),
			array("input"=>$_POST["vat_tax"], "require"=>"true", "message"=>'进口增值税不能为空')
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$insert_array = array();
				$insert_array['hs_code'] = trim($_POST['hs_code']);
				$insert_array['hs_name'] = trim($_POST['hs_name']);
				$insert_array['region_id'] = trim($_POST['region_id']);
				$insert_array['tariff'] = trim($_POST['tariff']);
				$insert_array['common_tariff'] = trim($_POST['common_tariff']);
				$insert_array['consumption_tax'] = trim($_POST['consumption_tax']);
				$insert_array['vat_tax'] = trim($_POST['vat_tax']);
				
				$is_hscode = $model_addtaxrate ->getHsTaxInfo(array('hs_code'=>trim($_POST['hs_code'])),'hs_code');
				if(!empty($is_hscode)){
					showMessage('海关HS编码已存在！');
				}
				
				$result = $model_addtaxrate->addHsTaxRate($insert_array);
				if ($result){
					$url = array(
					array(
					'url'=>'index.php?gct=tax_rate&gp=add',
					'msg'=>'继续增加',
					),
					array(
					'url'=>'index.php?gct=tax_rate&gp=index',
					'msg'=>'查看列表',
					)
					);
					showMessage($lang['nc_common_save_succ'],$url,'html','succ',1,5000);
				}else {
					showMessage($lang['nc_common_save_fail']);
				}
			}
		}

		Tpl::showpage('tax_rate.add');
	}

	/**
	 * 编辑
	 */
	public function editOp(){
		$lang	= Language::getLangContent();

		$model_taxrate = Model('tax_rate');

		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["hs_code"], "require"=>"true", "message"=>'海关HS编码不能为空'),
			array("input"=>$_POST["hs_name"], "require"=>"true", "message"=>'海关商品名称不能为空'),
			array("input"=>$_POST["tariff"], "require"=>"true", "message"=>'关税不能为空'),
			array("input"=>$_POST["common_tariff"], "require"=>"true", "message"=>'一般关税不能为空'),
			array("input"=>$_POST["consumption_tax"], "require"=>"true", "message"=>'消费税不能为空'),
			array("input"=>$_POST["vat_tax"], "require"=>"true", "message"=>'进口增值税不能为空')
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$update_array = array();
				$update_array['hs_code'] = trim($_POST['hs_code']);
				$update_array['hs_name'] = trim($_POST['hs_name']);
				$update_array['region_id'] = trim($_POST['region_id']);
				$update_array['tariff'] = trim($_POST['tariff']);
				$update_array['common_tariff'] = trim($_POST['common_tariff']);
				$update_array['consumption_tax'] = trim($_POST['consumption_tax']);
				$update_array['vat_tax'] = trim($_POST['vat_tax']);
				
				$is_hscode = $model_taxrate ->getHsTaxInfo(array('hs_code'=>trim($_POST['hs_code'])),'hs_code');
				if(!empty($is_hscode)){
					showMessage('海关HS编码已存在！');
				}
				
				$result = $model_taxrate->editHsTaxRate($update_array,array('id'=>intval($_POST['hsid'])));
				if ($result){
					showMessage($lang['nc_common_save_succ'],'index.php?gct=tax_rate&gp=index');
				}else {
					showMessage($lang['nc_common_save_fail']);
				}
			}
		}

		$taxrate_array = $model_taxrate->getHsTaxInfo(array('id'=>intval($_GET['hsid'])));
		if (empty($taxrate_array)){
			showMessage($lang['illegal_parameter']);
		}

		Tpl::output('taxrate_array',$taxrate_array);
		Tpl::showpage('tax_rate.edit');
	}

	/**
	 * 删除
	 */
	public function delOp(){
		$lang	= Language::getLangContent();
		$model_orderinfo = Model('mess_order_info');
		if (intval($_GET['ORDER_ID']) > 0){
			$array = array(intval($_GET['ORDER_ID']));
			$result = $model_orderinfo->delMessOrder(array('ORDER_ID'=>intval($_GET['ORDER_ID'])));
			if ($result) {
			     $this->log(L('nc_del,mess_order').'[ID:'.$_GET['ORDER_ID'].']',1);
			     showMessage($lang['nc_common_del_succ'],getReferer());
			}
		}
		showMessage($lang['nc_common_del_fail'],'index.php?gct=mess_order&gp=mess_order');
	}
	
	public function ajaxOp(){
		switch ($_GET['branch']){
			/**
			 * 验证是否重复
			 */
			case 'check_mess_order':
				$model_mess = Model('mess_order');
				$condition['ORIGINAL_ORDER_NO']	= $_GET['ORIGINAL_ORDER_NO'];
				$condition['ORDER_ID']	= array('neq',intval($_GET['ORDER_ID']));
				$order_list = $model_mess->getMessOrderInfo($condition);
				if (empty($order_list)){
					echo 'true';exit;
				}else {
					echo 'false';exit;
				}
				break;
		}
	}
	 	 
}
