<?php
/**
 * 登录逻辑层处理
 */
defined('GcWebShop') or exit('Access Invalid!');
class loginServer {

    /**
     * [登录逻辑处理]
     * @author fulijun
     * @dateTime 2016-09-13T10:42:35+0800
     * @param    [String]                   $username [用户名]
     * @param    [String]                   $password [密码]
     * @param    [String]                   $client   [客户端]
     * @return   [type]                               [description]
     */
    public function login($username,$password,$client){
         $memberModel = Model('member');
         //用户名、手机、电子邮箱任意三选一登录
         $where=array();
         $where['member_name']   =$username;
         $where['member_mobile'] =$username;
         $where['member_email']  =$username;
         $where['_op']='OR';
         $memberInfo = $memberModel->where($where)->find();

         //查询条件
         $condition = array();
         $condition['member_passwd'] = md5($password);
         switch ($username) {
             case $memberInfo['member_mobile']:
                 $condition['member_mobile'] = $username;
                 break;
             case $memberInfo['member_email']:
                 $condition['member_email'] = $username;
                 break;
             default:
                 $condition['member_name'] = $username;
                 break;
         }

        $member_info = $memberModel->getMemberInfo($condition);

        //放SESSION里用户信息
        $memberModel->createSession($member_info);

        //判断是否是卖家
        $is_seller = (int)$member_info['is_seller'];
        if($is_seller == 1){
            setNcCookie('ref', $member_info['member_id'],2*3600);
        }else{
            setNcCookie('ref', $member_info['refer_id'],2*3600);
        }
       
        if(!empty($member_info)) {
            $token = $this->getToken($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token){
                //COOKIE信息
                setNcCookie('username', $member_info['member_name'],2*3600);
                setNcCookie('is_seller', $member_info['is_seller'],2*3600);
                setNcCookie('is_manager', $member_info['is_manager'],2*3600);
                setNcCookie('member_id', $member_info['member_id'],2*3600);
                setNcCookie('key', $token,2*3600);
                //组织数据
                $data['key'] = $token;
                $data['member_info'] = $member_info;
               return json_return(400,'登录成功',$data); 
            } else {
                return json_return(501,'登录失败');
            }   
        } else {
            return json_return(501,'用户名或密码错误');
        }
    
    }

    /**
     * 登录生成token
     */
    public function getToken($member_id, $member_name, $client) {

        $model_mb_user_token = Model('mb_user_token');

        //生成新的token
        $mb_user_token_info = array();
        $token = md5($member_name . strval(TIMESTAMP) . strval(rand(0,999999)));
        $mb_user_token_info['member_id'] = $member_id;
        $mb_user_token_info['member_name'] = $member_name;
        $mb_user_token_info['token'] = $token;
        $mb_user_token_info['login_time'] = TIMESTAMP;
        $mb_user_token_info['client_type'] = $_POST['client'] == null ? 'Android' : $_POST['client'] ;

        $result = $model_mb_user_token->addMbUserToken($mb_user_token_info);

        if($result) {
            return $token;
        } else {
            return null;
        }
    }
}
