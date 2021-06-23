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
     * @param string $wxc_userID
     * @param string $username
     * @param string $oldname
     * @param string $newname
     * @post token,wxc_userID,username,oldname,newname
     */
    public function wxc_to_haiwai_category_update($token,$wxc_userID,$username,$oldname,$newname){
        //验证用户
        $obj_memcache = func_initMemcached('cache02');
        $wxc_userID_cache=$obj_memcache->get($token);
        if(empty($wxc_userID_cache) || $wxc_userID_cache!=$wxc_userID)  {$this->error="未通过验证";$this->status=false;return false;}
        
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
     * @param string $wxc_userID
     * @param string $username
     * @param string $name
     * @post token,wxc_userID,username,name
     */
    public function wxc_to_haiwai_category_add($token,$wxc_userID,$username,$name){
        //验证用户
        $obj_memcache = func_initMemcached('cache02');
        $wxc_userID_cache=$obj_memcache->get($token);
        if(empty($wxc_userID_cache) || $wxc_userID_cache!=$wxc_userID)  {$this->error="未通过验证";$this->status=false;return false;}
        
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
    
    /**
     * 文学城
     * 文章 添加
     * @param string $token
     * @param string $wxc_userID
     * @param string $username
     * @param string $wxc_postID
     * @param string $title
     * @param string $msgbody
     * @param string $category_name
     * @param string $is_publish
     * @param string $tagname
     * @post token,wxc_userID,username,wxc_postID,title,msgbody,category_name,is_publish,tagname
     */
    public function wxc_to_haiwai_article_add($token,$wxc_userID,$username,$wxc_postID,$title,$msgbody,$category_name,$is_publish,$tagname=""){
        //验证用户
        $obj_memcache = func_initMemcached('cache02');
        $wxc_userID_cache=$obj_memcache->get($token);
        if(empty($wxc_userID_cache) || $wxc_userID_cache!=$wxc_userID)  {$this->error="未通过验证";$this->status=false;return false;}
        
        //验证博客用户
        $obj_account_user_auth=load("account_user_auth");
        $rs_account_user_auth=$obj_account_user_auth->getOne(['userID'],['login_data'=>$username,'login_source'=>"wxc"]);
        $obj_blog_blogger=load("blog_blogger");
        $check_blog_blogger=$obj_blog_blogger->getOne(['id'],['userID'=>$rs_account_user_auth['userID']]);
        if(empty($check_blog_blogger))    {$this->error="博客不存在";$this->status=false;return false;}
        
        //验证目录
        $obj_blog_category=load("blog_category");
        $check_blog_category=$obj_blog_category->getOne("*",['bloggerID'=>$check_blog_blogger['id'],'name'=>$category_name]);
        if(empty($check_blog_category)){
            $check_blog_category=$obj_blog_category->getOne("*",['bloggerID'=>$check_blog_blogger['id'],'is_default'=>1]);
        }
        
        //图片域名处理
        $obj_article_indexing=load("article_indexing");
        $msgbody=$obj_article_indexing->add_image_domian(urldecode($msgbody));
        
        //生成数据
        $article_data=[
            "title"=>empty($title)?"":urldecode($title),
            "msgbody"=>empty($msgbody)?"":$msgbody,
            "tagname"=>[],
            "typeID"=>1,
            'tagname'=>empty($tagname)?[]:explode(",",urldecode($tagname)),
            "is_comment"=>1,
            "is_publish"=>$is_publish,
        ];
        $module_data=[
            "bloggerID"=>$check_blog_blogger['id'],
            "categoryID"=>$check_blog_category['id']
        ];
        
        //添加文章 post
        $obj_article_post=load("article_post");
        $article_data['postID']=$obj_article_post->get_id();
        $time=times::getTime();
        $fields_indexing=[
            "postID"=>$article_data['postID'],
            "typeID"=>$article_data['typeID'],
            "basecode"=>$article_data['postID'],
            "userID"=>$rs_account_user_auth['userID'],
            "treelevel"=>0,
            "create_date"=>$time,
            "edit_date"=>$time,
            "is_comment"=>empty($article_data['is_comment'])?0:1,
            'is_publish'=>$is_publish,
        ];
        $obj_article_indexing->insert($fields_indexing);
        $post_tbn=substr('0'.$rs_account_user_auth['userID'],-1);
        $fields_post=[
            "id"=>$article_data['postID'],
            "title"=>$article_data['title'],
            "msgbody"=>$article_data['msgbody'],
        ];
        $obj_article_post->insert($fields_post,"post_{$post_tbn}");
        
        //添加文章 tag
        $obj_article_tag=load("article_tag");
        $obj_article_tag->article_tag_add($article_data);
        
        //转文章为博客类型
        if($article_data['typeID']==1){
            $obj_blog_blogger=load("blog_blogger");
            $obj_blog_blogger->add_blog_article($article_data,$module_data);
        }
        
        //同步ES索引
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_noindex->fetch_and_insert([$article_data['postID']]);
        
        //记录wxc_postID
        $obj_blog_wxc_postID=load("blog_wxc_postID");
        $obj_blog_wxc_postID->insert(["postID"=>$article_data['postID'],"wxc_postID"=>$wxc_postID,'userID'=>$rs_account_user_auth['userID'],'wxc_userID'=>$wxc_userID]);
        
        return true;
    }
    
    /**
     * 文学城
     * 文章 修改
     * @param string $token
     * @param string $wxc_userID
     * @param string $username
     * @param string $wxc_postID
     * @param string $title
     * @param string $msgbody
     * @param string $category_name
     * @param string $is_publish
     * @param string $tagname
     * @post token,wxc_userID,username,wxc_postID,title,msgbody,category_name,is_publish,tagname
     */
    public function wxc_to_haiwai_article_edit($token,$wxc_userID,$username,$wxc_postID,$title,$msgbody,$category_name,$is_publish,$tagname=""){
        //验证用户
        $obj_memcache = func_initMemcached('cache02');
        $wxc_userID_cache=$obj_memcache->get($token);
        if(empty($wxc_userID_cache) || $wxc_userID_cache!=$wxc_userID)  {$this->error="未通过验证";$this->status=false;return false;}
        
        //验证博客用户
        $obj_account_user_auth=load("account_user_auth");
        $rs_account_user_auth=$obj_account_user_auth->getOne(['userID'],['login_data'=>$username,'login_source'=>"wxc"]);
        $obj_blog_blogger=load("blog_blogger");
        $check_blog_blogger=$obj_blog_blogger->getOne(['id'],['userID'=>$rs_account_user_auth['userID']]);
        if(empty($check_blog_blogger))    {$this->error="博客不存在";$this->status=false;return false;}
        
        //验证目录
        $obj_blog_category=load("blog_category");
        $check_blog_category=$obj_blog_category->getOne("*",['bloggerID'=>$check_blog_blogger['id'],'name'=>$category_name]);
        if(empty($check_blog_category)){
            $check_blog_category=$obj_blog_category->getOne("*",['bloggerID'=>$check_blog_blogger['id'],'is_default'=>1]);
        }
        
        //查询映射wxc_postID
        $obj_blog_wxc_postID=load("blog_wxc_postID");
        $check_blog_wxc_postID=$obj_blog_wxc_postID->getOne("*",['wxc_postID'=>$wxc_postID,'userID'=>$rs_account_user_auth['userID'],'wxc_userID'=>$wxc_userID]);
        if(empty($check_blog_wxc_postID)) {$this->error="博文不存在";$this->status=false;return false;}
        
        //查询博客文章
        $obj_article_indexing=load("article_indexing");
        $check_article_indexing=$obj_article_indexing->getOne(['postID'],['visible'=>1,'postID'=>$check_blog_wxc_postID['postID']]);
        if(empty($check_article_indexing)) {$this->error="博文不存在";$this->status=false;return false;}
        
        //图片域名处理
        $msgbody=$obj_article_indexing->add_image_domian(urldecode($msgbody));
        
        //生成数据
        $article_data=[
            "title"=>empty($title)?"":urldecode($title),
            "msgbody"=>empty($msgbody)?"":$msgbody,
            "tagname"=>[],
            "postID"=>$check_article_indexing['postID'],
            "typeID"=>1,
            'tagname'=>empty($tagname)?[]:explode(",",urldecode($tagname)),
            "is_comment"=>1,
            "is_publish"=>$is_publish,
        ];
        $module_data=[
            "bloggerID"=>$check_blog_blogger['id'],
            "categoryID"=>$check_blog_category['id']
        ];
        
        //修改文章 post
        $obj_article_post=load("article_post");
        $time=times::getTime();
        $fields_indexing=[
            "is_pic"=>0,
            "edit_date"=>$time,
            "is_comment"=>empty($article_data['is_comment'])?0:1,
            'is_publish'=>$is_publish,
        ];
        $obj_article_indexing->update($fields_indexing,['postID'=>$article_data['postID']]);
        $post_tbn=substr('0'.$rs_account_user_auth['userID'],-1);
        $fields_post=[
            "title"=>$article_data['title'],
            "msgbody"=>$article_data['msgbody'],
        ];
        $obj_article_post->update($fields_post,['id'=>$check_article_indexing['postID']],"post_{$post_tbn}");
        
        //添加文章 tag
        $obj_article_tag=load("article_tag");
        $obj_article_tag->article_tag_add($article_data);
        
        //修改博客类型文章
        if($article_data['typeID']==1){
            $obj_blog_blogger=load("blog_blogger");
            $obj_blog_blogger->update_blog_article($article_data,$module_data);
        }
        
        //同步ES索引
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_noindex->fetch_and_insert([$article_data['postID']]);
        
        return true;
    }
    
    
    
    
    
    
    
    
    
}





































