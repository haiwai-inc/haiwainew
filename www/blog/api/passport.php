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
     * 用户注册后
     * 博主 注册
     */
    public function blog_register(){
        if(empty($_SESSION['id']))  {$this->error="用户未登录";$this->status=false;return false;}
        
        $obj_blog_blogger=load("blog_blogger");
        $check_blog_blogger=$obj_blog_blogger->getOne("*",['userID'=>$_SESSION['id']]);
        if(!empty($check_blog_blogger)) {$this->error="此用户已经注册博客";$this->status=false;return false;}
        
        $obj_account_user=load("account_user");
        $rs_account_user=$obj_account_user->getOne("*",['id'=>$_SESSION['id']]);
        
        //博主表
        $time=times::gettime();
        $ip=http::getIP();
        $fields=[
            "userID"=>$_SESSION['id'],
            "name"=>$rs_account_user['username']."的博客",
            "create_date"=>$time,
            "update_date"=>$time,
            "update_type"=>"register",
            "update_ip"=>$ip
        ];
        $bloggerID=$obj_blog_blogger->insert($fields);
        
        //文集表
        $obj_blog_category=load("blog_category");
        $obj_blog_category->insert(['bloggerID'=>$bloggerID,"name"=>"日记"]);
        
        return true;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}





































