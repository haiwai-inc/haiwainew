<?php
class page extends Api {
    
    public function __construct() {
        parent::__construct();
        $this->sess = true;
    }
    
    /**
     * “别人的”博主信息页
     * 博主 信息
     */
    public function blogger_info(){
        
    }
    
    /**
     * 二级页面
     * 关注 文章 默认
     */
    public function follower_article_default(){
        
    }
    
    /**
     * 二级页面 
     * 推荐 文章
     */
    public function recommend_article($lastid=0){
        $obj_blog_recommend=load("blog_recommend");
        $obj_search_article_noindex=load("search_article_noindex");
        
        $fields=[
            "limit"=>30,
            'order'=>['id'=>'DESC']
        ];
        if(!empty($lastid)){
            $fields['id,<']=$lastid;
        }
        
        $rs_blog_recommend=$obj_blog_recommend->getAll("*",$fields);
        if(empty($rs_blog_recommend)){
            $rs=['status'=>false,'msg'=>"",'data'=>""];
            return $rs;
        }
        foreach($rs_blog_recommend as $k=>$v){
            $postID_blog_recommend[]=$v['postID'];
        }
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $rs_blog_recommend=$obj_account_user->get_basic_userinfo($rs_blog_recommend,"userID");
        
        //添加ES信息
        $rs_blog_recommend=$obj_search_article_noindex->get_postInfo($rs_blog_recommend);
        
        //添加文章计数信息
        $obj_article_indexing=load("article_indexing");
        $rs_blog_recommend=$obj_article_indexing->get_article_count($rs_blog_recommend);
        
        return $rs_blog_recommend;
    }
    
    /**
     * 二级页面 
     * 推荐 博主
     */
    public function recommand_blogger(){
        $obj_memcache = func_initMemcached('cache01');
        $rs_memcache = $obj_memcache->get("blog_hot_blogger");
        
        if(empty($rs_memcache)){
            $rs=['status'=>false,'msg'=>"",'data'=>""];
            return $rs;
        }
        
        $rs=['status'=>true,'error'=>"",'data'=>$rs_memcache];
        return $rs;
    }
    
    /**
     * 二级页面 
     * 热榜 标签
     */
    public function hot_tag(){
        $obj_memcache = func_initMemcached('cache01');
        $rs_memcache = $obj_memcache->get("blog_hot_tag");
        
        if(empty($rs_memcache)){
            $rs=['status'=>false,'msg'=>"",'data'=>""];
            return $rs;
        }
        
        $rs=['status'=>true,'error'=>"",'data'=>$rs_memcache];
        return $rs;
    }
    
    /**
     * 二级页面 
     * 热榜 文章 列表
     * @param integer $tagID | 标签ID
     * @param integer $lastid | 最后一个postID
     */
    public function hot_article_list($tagID=0,$lastid=0){
        $obj_article_indexing=load("article_indexing");
        $obj_article_noindex=load("search_article_noindex");
        
        //ES搜索tag
        if(!empty($tagID)){
            $obj_article_index=load("search_article_index");
            $rs_article_index=$obj_article_index->search_tags([$tagID],$lastid);
        }else{
            $fields=[
                'limit'=>30,
                'visible'=>1,
                'ORDER'=>['count_read'=>'DESC']
            ];
            if(!empty($lastid)){
                $fields['postID,<']=$lastid;
            }
            $rs_article_index=$obj_article_indexing->getAll(['postID','userID'],$fields);
            
            //ES补全postID信息
            $rs_article_index=$obj_article_noindex->get_postInfo($rs_article_index);
        }
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $rs_article_index=$obj_account_user->get_basic_userinfo($rs_article_index,"userID");
        
        //添加文章计数信息
        $rs_article_index=$obj_article_indexing->get_article_count($rs_article_index);
        
        return $rs_article_index;
    }
    
    /**
     * 博客主页 编辑器页
     * 文章 列表 最新
     * @param integer $bloggerID
     * @param integer $lastid | 最后一个postID
     */
    public function article_list_recent($bloggerID,$lastid=0){
        if(empty($bloggerID)){
            $this->error="此博主不存在";
            $this->status=false;
            return false;
        }
        
        $obj_blog_blogger=load("blog_blogger");
        $rs_blog_blogger=$obj_blog_blogger->getOne(['id','userID'],['status'=>1]);
        if(empty($rs_blog_blogger)){
            $this->error="此博主不存在";
            $this->status=false;
            return false;
        }
        
        $obj_article_indexing=load("article_indexing");
        $fields=[
            'limit'=>30,
            'bloggerID'=>$bloggerID,
            'order'=>['edit_date'=>'DESC']
        ];
        if(!empty($lastid)){
            $fields['postID,<']=$lastid;
        }
        $rs_article_indexing=$obj_article_indexing->getAll(["userID","postID"],$fields);
        
        //ES补全postID信息
        $obj_article_noindex=load("search_article_noindex");
        $rs_article_indexing=$obj_article_noindex->get_postInfo($rs_article_indexing);
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $rs_article_indexing=$obj_account_user->get_basic_userinfo($rs_article_indexing,"userID");
        
        //添加文章计数信息
        $rs_article_indexing=$obj_article_indexing->get_article_count($rs_article_indexing);
        
        return $rs_article_indexing;
    }
    
    /**
     * 博客主页 二级页面
     * 文章 列表 最热
     * @param integer $bloggerID
     * @param integer $lastid | 最后一个postID
     */
    public function article_list_hot($bloggerID,$lastid=0){
        if(empty($bloggerID)){
            $this->error="此博主不存在";
            $this->status=false;
            return false;
        }
        
        $obj_blog_blogger=load("blog_blogger");
        $rs_blog_blogger=$obj_blog_blogger->getOne(['id','userID'],['status'=>1]);
        if(empty($rs_blog_blogger)){
            $this->error="此博主不存在";
            $this->status=false;
            return false;
        }
        
        $obj_article_indexing=load("article_indexing");
        $fields=[
            'limit'=>30,
            'bloggerID'=>$bloggerID,
            'order'=>['count_read'=>'DESC']
        ];
        if(!empty($lastid)){
            $fields['postID,<']=$lastid;
        }
        $rs_article_indexing=$obj_article_indexing->getAll(["userID","postID"],$fields);
        
        //ES补全postID信息
        $obj_article_noindex=load("search_article_noindex");
        $rs_article_indexing=$obj_article_noindex->get_postInfo($rs_article_indexing);
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $rs_article_indexing=$obj_account_user->get_basic_userinfo($rs_article_indexing,"userID");
        
        //添加文章计数信息
        $rs_article_indexing=$obj_article_indexing->get_article_count($rs_article_indexing);
        
        return $rs_article_indexing;
    }
    
    /**
     * 博客主页 二级页面
     * 评论 列表 最新
     * @param integer $bloggerID
     * @param integer $lastid | 最后一个postID
     */
    public function comment_list_recent($bloggerID,$lastid=0){
        if(empty($bloggerID)){
            $this->error="此博主不存在";
            $this->status=false;
            return false;
        }
        $obj_blog_blogger=load("blog_blogger");
        $rs_blog_blogger=$obj_blog_blogger->getOne(['id','userID'],['status'=>1]);
        if(empty($rs_blog_blogger)){
            $this->error="此博主不存在";
            $this->status=false;
            return false;
        }
        
        $obj_article_indexing=load("article_indexing");
        $fields=[
            'treelevel'=>1,
            'limit'=>30,
            'bloggerID'=>$bloggerID,
            'order'=>['edit_date'=>'DESC']
        ];
        if(!empty($lastid)){
            $fields['postID,<']=$lastid;
        }
        $rs_article_indexing=$obj_article_indexing->getAll(["userID","postID"],$fields);
        
        return $rs_article_indexing;
    }
    
    /**
     * 博客主页 二级页面
     * 评论 详情 最新
     */
    public function comment_view_recent(){
        
    }
    
    /**
     * 文章详情页 编辑器页
     * 文章 详情
     */
    public function article_view($id){
        $obj_article_post=load("article_post");
        $obj_article_indexing=load("article_indexing");
        
        $rs_article_post=$obj_article_indexing->getOne("*",['visible'=>1,'postID'=>$id]);
        if(empty($rs_article_post)){
            $rs=['status'=>false,'msg'=>"no article",'data'=>""];
            return $rs;
        }
    }
    
    /**
     * 文章详情页 
     * 文章 评论
     */
    public function article_view_comment($id){
        
    }
    
    /**
     * 文章详情页 
     * 文章 相关
     */
    public function article_view_related($id){
        
    }
    
    /**
     * 文章详情页 
     * 文章 文集
     */
    public function article_view_category($id){
        
    }
    
    /**
     * 文章详情页 
     * 文章 博主
     */
    public function article_view_blogger($id){
        
    }
    
    /**
     * 博客主页 编辑器页
     * 文集 列表
     */
    public function category_list(){
        
    }
    
    
}






































































