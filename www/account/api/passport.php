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
        $obj_account_user=load("account_user");
        if(empty($_SESSION['UserID']) || !empty($userID)){
            $rs_account_user=$obj_account_user->getOne(['id','auth_group'],['id'=>$userID]);
            
            $_SESSION=$rs_account_user;
            $_SESSION['UserID']=$rs_account_user['id'];
            $_SESSION['UserLevel']=$rs_account_user['auth_group'];
            
            if(empty($rs_account_user)){
                $this->error='User not logged in';
                $this->status=false;
                return false;
            }
        }else{
            $rs_account_user=$_SESSION;
        }
        
        $rs_account_user=$obj_account_user->get_basic_userinfo([$rs_account_user])[0];
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
     * 用户 登录 海外
     * @param integer $login_data|登录信息
     * @param integer $login_token|登录凭证
     */
    public function user_login($login_data,$login_token){
        $obj_account_user_login=load("account_user_login");
        $rs_user_login=$obj_account_user_login->haiwai_login($login_data,$login_token);
        return $rs_user_login;
    }
    
    /**
     * 用户登录页
     * 用户 登录 文学城
     * @param integer $login_data|登录信息
     * @param integer $login_token|登录凭证
     */
    public function user_login_wxc($login_data,$login_token){
        $obj_account_user_login=load("account_user_login");
        $rs_user_login=$obj_account_user_login->wxc_login($login_data,$login_token);
        return $rs_user_login;
    }
    
    /**
     * 用户登录页
     * 用户 登录 google
     * @param integer $login_token|登录凭证
     * @response /account/api_response/user_login_google.txt
     */
    public function user_login_google($login_token=null){
        $obj_account_user_login=load("account_user_login");
        $rs_user_login=$obj_account_user_login->google_login($login_token);
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
        $check_account_user=$obj_account_user->getOne("*",['status'=>1,'email'=>$email]);
        if(!empty($check_account_user)){
            $error[]="此用户已经被注册";
        }
        
        //验证邮箱
        $check_email=$obj_account_user->check_email($email);
        if(empty($check_email['status'])){
            $error[]=$check_email['error'];
        }
        
        //验证密码
        $check_password=$obj_account_user->check_password($password);
        if(empty($check_password['status'])){
            $error[]=$check_password['error'];
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
            'verified'=>0,
            'ip'=>$ip,
            'login_date'=>$time,
            'create_date'=>$time,
            'update_date'=>$time,
            'update_type'=>"register_haiwai",
            'update_ip'=>$ip
        ];
        
        $userID=$obj_account_user->insert($fields);
        
        //发送确认邮件
        $obj_account_user_email=load("account_user_email");
        $token=md5($fields['username'].$fields['password']);
        $obj_memcache = func_initMemcached('cache01');
        $obj_memcache->set($token,$userID, 600);
        $obj_account_user_email->insert(['function'=>"register_verified",'name'=>$fields['username'],'email'=>$fields['email'],'data'=>serialize(['token'=>$token,'email'=>$fields['email']])]);
        
        //登录
        $this->user_login($email,$password,"haiwai");
        return true;
    }
    
    /**
     * 用户确认页
     * 用户 注册
     * @param integer $token|确认注册码
     */
    public function user_register_verified($token){
        $obj_memcache = func_initMemcached('cache01');
        $check_memcache=$obj_memcache->get($token);
        if(empty($check_memcache))  {$this->error="认证错误，请重新发送验证码";$this->status=false;return false;}
        
        $obj_account_user=load("account_user");
        $obj_account_user->update(['verified'=>1,'update_type'=>"verified"],['id'=>$check_memcache]);
        return "恭喜您成功注册海外博客";
    }
    
    /**
     * 用户登录页
     * 用户 发送 认证码
     * @param integer $email|用户邮箱
     * 
     * 1. 登录判断是否认证
     * 2. 认证时间是否过期
     * 3. 点击按钮重新发送
     * 
     */
    public function user_send_verification($email){
        $obj_account_user=load("account_user");
        $check_account_user=$obj_account_user->getOne("*",['email'=>$email]);
        if(empty($check_account_user))  {$this->error="此email的用户不存在，请重新注册";$this->status=false;return false;}
        if($check_account_user['status']==0)  {$this->error="此用户已被关闭";$this->status=false;return false;}
        if($check_account_user['verified']==1)  {$this->error="此用户已经通过认证";$this->status=false;return false;}
        
        $obj_account_user_email=load("account_user_email");
        $token=md5($check_account_user['username'].$check_account_user['password']);
        $obj_memcache = func_initMemcached('cache01');
        $obj_memcache->set($token,$check_account_user[''], 600);
        $obj_account_user_email->insert(['function'=>"register_verified",'name'=>$check_account_user['username'],'email'=>$check_account_user['email'],'data'=>serialize(['token'=>$token,'email'=>$check_account_user['email']])]);
        
        return "认证链接已发送至邮箱: ".$email;
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
     * 用户 通过微信注册或登录
     * @param string $code|用户登录微信账号获取的code
     */
    public function user_wechat_login($code){

    }
    
    
    public function init_sse(){
        
    }
    
    
    
    
    
    
    
    
}





































