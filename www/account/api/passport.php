<?php

/**
 * @author sida
 */
class passport extends Api {

    public function __construct() {
        parent::__construct();
        $this->sess = true;
    }

    /**
     * 所有页面
     * 用户 认证
     */
    public function login_status($userID=0) {
        if(empty($_SESSION['UserID']) || !empty($userID)){
            $obj_account_user=load("account_user");
            $rs_account_user=$obj_account_user->getOne(['id','auth_group','username','description','background','avatar'],['id'=>$userID]);
            
            $_SESSION=$rs_account_user;
            $_SESSION['UserID']=$rs_account_user['id'];
            $_SESSION['UserLevel']=$rs_account_user['auth_group'];
            
            if(empty($rs_account_user)){
                $this->error='User not logged in';
                $this->status=false;
            }
        }else{
            $rs_account_user=$_SESSION;
        }
        
        return $rs_account_user;
    }
    
    /**
     * 页头
     * 用户 退出
     */
    public function user_logout(){
        session_unset();
        unset($_COOKIE['wxc_login']);
        setcookie('wxc_login','', time()- 3600,conf()['session']['sessionpath'],conf()['session']['sessiondomain']); 
        return true;
    }
    
    /**
     * 用户登录页
     * 用户 登录
     * @param integer $login_data|登录信息
     * @param integer $login_token|登录凭证
     * @param integer $login_source|登录类型
     */
    public function user_login($login_data,$login_token,$login_source){
        $obj_account_user_login=load("account_user_login");
        
        //海外登录
        if($login_source=="haiwai"){
            $rs_user_login=$obj_account_user_login->haiwai_login($login_data,$login_token);
        }
        //文学城登录
        elseif($login_source=="wxc"){
            $rs_user_login=$obj_account_user_login->wxc_login($login_data,$login_token);
        }
        
        //非法登录
        if(empty($rs_user_login['status']))   {
            $this->error=empty($rs_user_login['error'])?"无效登录":$rs_user_login['error'];
            $this->status=false;
            return false;
        }
        
        return $rs_user_login;
    }
    
    /**
     * 用户注册页
     * 用户 注册
     * @param integer $email|邮箱
     * @param integer $password|密码
     */
    public function user_register($email,$password){
        $email=urldecode($email);
        $password=urldecode($password);
        
        $obj_account_user=load("account_user");
        $check_account_user=$obj_account_user->getOne("*",['status'=>1,'email'=>$email,'login_source'=>"haiwai"]);
        if(!empty($check_account_user)){$this->error="此用户已经被注册";$this->status=false;return false;}
        
        //验证邮箱
        $check_email=$obj_account_user->check_email($email);
        if(empty($check_email['status'])){
            $error['email']=$check_email['error'];
        }
        
        //验证密码
        $check_password=$obj_account_user->check_password($password);
        if(empty($check_password['status'])){
            $error['password']=$check_password['error'];
        }
        
        //报错
        if(!empty($error)){
            $this->error=$error;$this->status=false;return false;
        }
        
        //插表
        $time=times::gettime();
        $ip=http::getIP();
        $fields=[
            'username'=>strstr($email,'@',true),
            'password'=>md5($password),
            'email'=>$email,
            'verified'=>1,
            'ip'=>$ip,
            'login_date'=>$time,
            'create_date'=>$time,
            'update_date'=>$time,
            'update_type'=>"register",
            'update_ip'=>$ip,
            'login_source'=>'haiwai',
        ];
        
        $obj_account_user->insert($fields);
        
        //发送邮件
        $obj_account_user_email=load("account_user_email");
        $obj_account_user_email->insert(['function'=>"register",'name'=>$fields['username'],'email'=>$fields['email'],'data'=>serialize(['email'=>$fields['email']])]);
        
        //登录
        $this->user_login($email,$password,"haiwai");
        
        return true;
    }
    
    /**
     * 用户注册页 个人设置页
     * 用户 邮箱 格式
     * @param integer $email|邮箱
     */
    public function user_email_check($email){
        $obj_account_user=load("account_user");
        $rs_account_user=$obj_account_user->check_email($email);
        if(empty($rs_account_user['status']))   {$this->error=$rs_account_user['error'];$this->status=false;return false;}
        
        return true;
    }
    
    /**
     * 用户注册页 个人设置页
     * 用户 密码 格式
     * @param integer $password|密码
     */
    public function user_password_check($password){
        $obj_account_user=load("account_user");
        $rs_account_user=$obj_account_user->check_password($password);
        if(empty($rs_account_user['status']))   {$this->error=$rs_account_user['error'];$this->status=false;return false;}
        
        return true;
    }
    
    /**
     * 用户忘记密码页
     * 用户 密码 发送 重置
     */
    public function user_password_send_request(){
        
    }
    
    /**
     * 用户注册页
     * 用户 通过Google注册或登录
     * @param string $token|用户登录Google账号获取的token
     */
    public function user_google_login($token){
        $client = new Google_Client(['client_id' => GOOGLE_CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($token);
        if ($payload) {
            $userid = $payload['sub'];
            // debug::d($payload);
            // If request specified a G Suite domain:
            //$domain = $payload['hd'];
        } else {
            // Invalid ID token
        }
    }

    /**
     * 用户注册页
     * 用户 通过微信注册或登录
     * @param string $code|用户登录微信账号获取的code
     */
    public function user_wechat_login($code){

    }
    
    
    public function init_sse(){
        
    }
    
    
    
    
    
    
    
    
}





































