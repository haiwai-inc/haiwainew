<?php
class user extends Api {

    public $space = true;
    
    public function __construct() {
        parent::__construct();
        
        //认证博主
        $bloggerID="1";
        $this->userAuthz($bloggerID);
    }
    
    /**
     * 博客设置页
     * 博主 信息
     */
    public function blogger_profile(){
        $obj_blog_blogger=load("blog_blogger");
        $rs_blog_blogger=$obj_blog_blogger->getOne(['id','name','description','background'],['id'=>$_SESSION['id']]);
        $rs_blog_blogger['first_letter']=substr(strings::subString($item['name'],1), 0, -3);
        
        return $rs_blog_blogger;
    }
    
    /**
     * 博主设置页
     * 博主 信息 修改
     * @param integer $name|博主名
     * @param integer $description|博主简介
     */
    public function blogger_profile_update($name,$description=""){
        $obj_blog_blogger=load("blog_blogger");
        $check_blog_blogger=$obj_blog_blogger->getOne("*",['name'=>$name]);
        if(!empty($check_blog_blogger)) {$this->error="此博主名已经被占用";$this->status=false;return false;}
        
        $fields=[
            "name"=>empty($name)?"":$name,
            "description"=>empty($description)?"":$description,
        ];
        $check_blog_blogger->update($fields,['id'=>$_SESSION['id']]);
        return true;
    }
    
    /**
     * 博主设置页
     * 博主 背景 修改
     */
    public function blogger_background_update(){
        
    }
    
    /**
     * 博客主页 编辑器页 
     * 文集 添加
     */
    public function category_add(){
        
    }
    
    /**
     * 编辑器页 
     * 文集 修改
     */
    public function category_update(){
        
    }
    
    /**
     * 编辑器页 
     * 文集 删除
     */
    public function category_delete(){
        
    }
    
    /**
     * 编辑器页
     * 文集 名字 拍重
     */
    public function category_name_check(){
        
    }
    
    /**
     * 小铃铛页
     * 关注 "关注我的"
     */
    public function follower_list_mine(){
        
    }
    
    /**
     * 二级页面
     * 关注 博主 列表
     */
    public function follower_blogger_list(){
        
    }
    
    /**
     * 二级页面
     * 关注 文章 列表
     * @param integer $followerID | 关注人的ID
     */
    public function follower_article_list($followerID=0){
        $obj_account_follower=load("account_follower");
        $obj_account_user=load("account_user");
        
        if(!empty($followerID)){
            $check_account_user=$obj_account_user->getOne(['id','username'],["id"=>$followerID,"status"=>1]);
            if(empty($check_account_user))  {$this->error="此关注用户已不存在";$this->status=false;return false;}
            
            $check_account_follower=$obj_account_follower->getOne(['id'],['userID'=>$_SESSION['id'],'followerID'=>$followerID]);
            if(empty($check_account_follower)) {$this->error="此用户未在您的关注列表";$this->status=false;return false;}
            $followerID_account_follower[]=$followerID;
        }else{
            $rs_account_follower=$obj_account_follower->getAll("*",['userID'=>$_SESSION['id']]);
            if(empty($rs_account_follower))  {$this->error="你还未关注任何用户";$this->status=false;return false;}
            foreach($rs_account_follower as $v){
                $followerID_account_follower[]=$v['followerID'];
            }
        }
        
        //索引表
        $obj_article_indexing=load("article_indexing");
        $rs_article_indexing=$obj_article_indexing->getAll(["id","postID","userID","blogID"],['visible'=>1,'OR'=>['userID'=>$followerID_account_follower]]);
        
        //添加用户信息
        $rs_article_indexing=$obj_account_user->get_basic_userinfo($rs_article_indexing,"userID");
        
        //添加ES信息
        $obj_article_noindex=load("search_article_noindex");
        $rs_article_indexing=$obj_article_noindex->get_postInfo($rs_article_indexing);
        
        //添加文章计数信息
        $rs_article_indexing=$obj_article_indexing->get_article_count($rs_article_indexing);
        
        return $rs_article_indexing;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}





































