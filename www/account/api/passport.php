<?php

/**
 * @author sida
 * ['data'=>[],'msg'=>[],'status'=>true]
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
    public function login_status($userID) {

//$obj=load("search_article_index");
//$test1=$obj->get_by_postIDs([9237]);


/*
$obj=load("search_article");
$test2=$obj->search_tags([2]);
debug::d($test2);
exit;
*/

/*
$obj=load("search_article_pool");
$obj->del(9237);
*/

/*
$obj=load("search_article_pool");
debug::d($obj->get(9237));
*/

/*
$obj=load("search_article_pool");
$test2=$obj->fetch_and_insert([9237]);
debug::d($test2);
exit;
*/
        $obj_account_user=load("account_user");
        $rs_account_user=$obj_account_user->getOne(['id','auth_group','username','description','background','avatar'],['id'=>$userID]);
        $rs_account_user['avatar']=$rs_account_user['avatar'];
        
        $_SESSION=$rs_account_user;
        $_SESSION['UserID']=$rs_account_user['id'];
        $_SESSION['UserName']=$rs_account_user['username'];
        $_SESSION['UserLevel']=$rs_account_user['auth_group'];
        
        if(empty($rs_account_user)){
            $this->error='User not logged in';
            $this->status=false;
        }
        
        return $rs_account_user;
    }
    
    /**
     * 页头
     * 用户 退出
     */
    public function user_logout(){
        
    }
    
    /**
     * 用户登录页
     * 用户 登录
     */
    public function user_login(){
        
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





































