<?php
class page extends Api {

    public function __construct() {
        parent::__construct();
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
        
        /*
        $rs_search_article_noindex=$obj_search_article_noindex->get([57028]);
        debug::d($rs_search_article_noindex);
        exit;
        */
        
        $rs=['status'=>true,'msg'=>"",'data'=>$rs_blog_recommend];
        return $rs;
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
        
        $rs=['status'=>true,'msg'=>"",'data'=>$rs_memcache];
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
        
        $rs=['status'=>true,'msg'=>"",'data'=>$rs_memcache];
        return $rs;
    }
    
    /**
     * 二级页面 
     * 热榜 文章 列表
     */
    public function hot_article_list($lastid=0){
        
    }
    
    /**
     * 博客主页 编辑器页
     * 文章 列表 最新
     */
    public function article_list_recent(){
        
    }
    
    /**
     * 博客主页
     * 文章 列表 最热
     */
    public function article_list_hot(){
        
    }
    
    /**
     * 博客主页
     * 评论 列表 最新
     */
    public function comment_list_recent(){
        
    }
    
    /**
     * 博客主页
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






































































