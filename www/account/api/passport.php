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
        }
        //文学城登录
        elseif($login_source=="wxc"){
            $rs_user_login=$obj_account_user_login->wxc_login($login_data,$login_token);
            if(!$rs_user_login['status'])   {
                $this->error=$rs_user_login['error'];
                $this->status=false;
                return false;
            }
        }else{
            $this->error="登录不合法";
            $this->status=false;
            return false;
        }
        
        return $rs_user_login;
    }
    
    /**
     * 用户注册页
     * 用户 注册
     */
    public function user_register(){
        
    }
    
    /**
     * 用户注册页 个人设置页
     * 用户 邮箱 格式
     */
    public function user_email_check(){
        
    }
    
    /**
     * 用户注册页 个人设置页
     * 用户 密码 格式
     */
    public function user_password_check(){
        
    }
    
    /**
     * 用户忘记密码页
     * 用户 密码 发送 重置
     */
    public function user_password_send_request(){
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
}





































