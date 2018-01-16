<?php
/**
 * 注册逻辑层处理
 */
defined('GcWebShop') or exit('Access Invalid!');
class registerServer {

    /**
     * [发送验证码逻辑层]
     * @author fulijun
     * @dateTime 2016-09-19T19:56:55+0800
     * @param    [String]                   $account [邮箱或者手机号]
     * @param    [Int]                      $type    [类型：1手机 2邮箱]
     * @return   [Array]                             [返回取得验证码的数组]
     */
    public function sendmsg($account,$type){
        if(!$type){
            return json_return(501,'请选择类型');  
        }
     
        if(isset($type) && !in_array($type,array(1,2))){
            return json_return(501,'类型错误'); 
        }
        // 验证
        $validate_obj = new Validate();
        $model = Model();
        if($type == 1){
            if(!$account){
             return json_return(501,'手机号码不能为空');  
            }
            //检查手机格式
            $validate_obj->validateparam = array(
                    array(
                        "input"=>$account,
                        "require"=>"true",
                        "validator"=>"mobile",
                        "message"=>'手机格式不正确'
                    )
            );
            $error = $validate_obj->validate();
            if ($error != ''){
                return json_return(501,'手机格式错误'); 
            }
            //是否有这个用户
            /*$ismember = Model()->table('member')->where(array('member_mobile'=>$account))->find();
            if($ismember){
                return json_return(501,'该手机号码已经存在！'); 
            }*/
            //取得上次操作时间
            $isreg= $model->table('app_regverify')->where(array('mobile'=>$account))->order('created DESC')->select();
            if($isreg && TIMESTAMP - $isreg[0]['created']   < 60){
                return json_return(501,'操作太频繁，请稍候再试！'); 
            } 
        }elseif($type == 2){
            if(!$account){
             return json_return(501,'邮箱不能为空');  
            }
            //检查手机格式
            $validate_obj->validateparam = array(
                    array(
                        "input"=>$account,
                        "require"=>"true",
                        "validator"=>"email",
                        "message"=>'邮箱格式不正确'
                    )
            );
            $error = $validate_obj->validate();
            if ($error != ''){
                return json_return(501,'邮箱格式错误'); 
            }
            //是否有这个用户
            /*$ismember = $model->table('member')->where(array('member_email'=>$account))->find();
            if($ismember){
                return json_return(501,'该邮箱已经存在！'); 
            } */
            //取得上次操作时间
            $isreg= Model()->table('app_regverify')->where(array('email'=>$account))->order('created DESC')->select();
            if($isreg && TIMESTAMP - $isreg[0]['created']   < 60){
                return json_return(501,'操作太频繁，请稍候再试！'); 
            } 

        }
        
        //我的模板信息
        $tpl_info = Model('mail_templates')->getTplInfo(array('code'=>'authenticate'));
      
        //构造发送信息主体：标题，内容,发送人，
        $data = array();
        $verify_code= substr(uniqid(rand()),0,6);
        $data['send_time'] = date('Y-m-d H:i',TIMESTAMP);
        $data['verify_code'] = $verify_code;
        $data['site_name'] = C('site_name');
        $subject = ncReplaceText($tpl_info['title'],$data);
        $message = ncReplaceText($tpl_info['content'],$data);

        $regverify_data = array();
        if ($type == 2) {
            $email  = new Email();
            $result = $email->send_sys_email($account,$subject,$message);
            //组织邮箱数据
            $regverify_data['email'] = $account;
            $regverify_data['type'] = 2;
        } elseif ($type == 1) {
            $sms = new Sms();
            $result = $sms->send($account,$message);
            //组织手机数据  
            $regverify_data['mobile'] = $account;
            $regverify_data['type'] = 1;
        }  
        if($result){
            //加入数据库 
            $regverify_data['code'] = $verify_code;
            $regverify_data['created'] = TIMESTAMP; 
            $isinsert = Model()->table('app_regverify')->insert($regverify_data);
            if($isinsert){
                return json_return(400,'验证码发送成功，请注意查收！',$regverify_data); 
            }
        }
        return json_return(501,'验证码发送失败！'); 
        
    } 


    




}
