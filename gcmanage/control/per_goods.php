<?php
/**
 * 个人定制管理
 *
 *
 *
 ***/

defined('GcWebShop') or exit('Access Invalid!');
class per_goodsControl extends SystemControl{
//    const EXPORT_SIZE = 1000;
    public function __construct(){
        parent::__construct();
        Language::read('per_goods');//读取语言包
    }

    /**
     * 定制列表
     */
    public function per_goodsOp(){
        $lang	= Language::getLangContent();
        $model_custo = Model();
        if(!isset($_POST['search_per_name'])){
            $result=$model_custo->table("per_goods")->page(10)->select();
            Tpl::output("result",$result);
            Tpl::output("page",$model_custo->showpage());
            Tpl::showpage("per_goods.index");
        }else{
            $per_name=$_POST['search_per_name'];
            $condition['per_name']=array('like','%'.$per_name.'%');
            $result=$result=$model_custo->table("per_goods")->where($condition)->page(10)->select();
            Tpl::output("result",$result);
            Tpl::output("page",$model_custo->showpage());
            Tpl::showpage("per_goods.index");
        }

    }

    /**
     * 修改状态
     */
    public function up_goodsOp(){
        $lang	= Language::getLangContent();
        $model_custo = Model();
        $tes=$_POST["tes"];
        $uid=intval($_POST["uid"]);
        $data=array("per_status"=>$tes);
        $result=$model_custo->table("per_goods")->where(array("per_id"=>$uid))->update($data);
        if($result){
            echo $tes;
        }else{
            echo "更新失败";
        }
    }

    public function del_goodsOp(){
        $lang	= Language::getLangContent();
        $model_custo = Model();
        $uid=$_POST["uid"];
        $res=$model_custo->table("per_goods")->where(array("per_id"=>$uid))->delete();
        if($res){
            echo $uid;
        }
    }

    public function del_allOp(){
        $lang	= Language::getLangContent();
        $model_custo = Model();
        $per_ids=$_POST["del_per_id"];
        $per_ids=implode(",",$per_ids);
        $res=$model_custo->table("per_goods")->where(array("per_id"=>array('in',$per_ids)))->delete();
        $this->per_goodsOp();
    }

    public function up_resOp(){
        $lang	= Language::getLangContent();
        $model_custo = Model();
        $tes=$_POST["tes"];
        $uid=intval($_POST["uid"]);
        $data=array("per_res"=>$tes);
        $result=$model_custo->table("per_goods")->where(array("per_id"=>$uid))->update($data);
        if($result){
            echo $tes;
        }else{
            echo "更新失败";
        }
    }

}
