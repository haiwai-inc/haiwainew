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
        $categoryID=$obj_blog_category->insert(['bloggerID'=>$bloggerID,"name"=>"我的文章",'is_default'=>1]);
        $obj_blog_category->update(['sort'=>$categoryID],['id'=>$categoryID]);
        return true;
    }
    
    /**
     * 文学城
     * 目录 修改
     * @param string $token 
     * @param string $username
     * @param string $oldname
     * @param string $newname
     * @post token,username,oldname,newname
     */
    public function wxc_to_haiwai_category_update($token,$username,$oldname,$newname){
        //验证用户
        $obj_memcache = func_initMemcached('cache02');
        $wxc_userID=$obj_memcache->get($token);
        if(empty($wxc_userID))  {$this->error="未通过验证";$this->status=false;return false;}
        
        //验证博客用户
        $obj_account_user_auth=load("account_user_auth");
        $rs_account_user_auth=$obj_account_user_auth->getOne(['userID'],['login_data'=>$username,'login_source'=>"wxc"]);
        $obj_blog_blogger=load("blog_blogger");
        $check_blog_blogger=$obj_blog_blogger->getOne(['id'],['userID'=>$rs_account_user_auth['userID']]);
        if(empty($check_blog_blogger))    {$this->error="博客不存在";$this->status=false;return false;}
        
        //验证目录
        $obj_blog_category=load("blog_category");
        $check_old_blog_category=$obj_blog_category->getOne("*",['bloggerID'=>$check_blog_blogger['id'],'name'=>$oldname]);
        if(empty($check_old_blog_category))    {$this->error="原目录不存在";$this->status=false;return false;}
        $check_new_blog_category=$obj_blog_category->getOne("*",['bloggerID'=>$check_blog_blogger['id'],'name'=>$newname]);
        if(!empty($check_new_blog_category))    {$this->error="新目录已存在";$this->status=false;return false;}
        
        //修改目录
        $obj_blog_category->update(['name'=>$newname],['bloggerID'=>$check_blog_blogger['id'],"id"=>$check_old_blog_category['id']]);
        return true;
    }
    
    /**
     * 文学城
     * 目录 修改
     * @param string $token
     * @param string $username
     * @param string $name
     * @post token,username,name
     */
    public function wxc_to_haiwai_category_add($token,$username,$name){
        //验证用户
        $obj_memcache = func_initMemcached('cache02');
        $wxc_userID=$obj_memcache->get($token);
        if(empty($wxc_userID))  {$this->error="未通过验证";$this->status=false;return false;}
        
        //验证博客用户
        $obj_account_user_auth=load("account_user_auth");
        $rs_account_user_auth=$obj_account_user_auth->getOne(['userID'],['login_data'=>$username,'login_source'=>"wxc"]);
        $obj_blog_blogger=load("blog_blogger");
        $check_blog_blogger=$obj_blog_blogger->getOne(['id'],['userID'=>$rs_account_user_auth['userID']]);
        if(empty($check_blog_blogger))    {$this->error="博客不存在";$this->status=false;return false;}
        
        //验证目录
        $obj_blog_category=load("blog_category");
        $check_blog_category=$obj_blog_category->getOne("*",['bloggerID'=>$check_blog_blogger['id'],'name'=>$name]);
        if(!empty($check_blog_category))    {$this->error="新目录已存在";$this->status=false;return false;}
        
        //添加目录
        $categoryID=$obj_blog_category->insert(['name'=>$name,'bloggerID'=>$check_blog_blogger['id']]);
        $obj_blog_category->update(['sort'=>$categoryID],['id'=>$categoryID]);
        return true;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
}





































