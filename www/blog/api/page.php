<?php
class page extends Api {

    public function __construct() {
        parent::__construct();
    }

    //推荐
    public function recommend_article($lastid=0){
        $obj_blog_recommend=load("blog_recommend");
        $obj_search_article_pool=load("search_article_pool");
        
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
        $rs_search_article_pool=$obj_search_article_pool->get([57028]);
        debug::d($rs_search_article_pool);
        exit;
        */
        
        $rs=['status'=>true,'msg'=>"",'data'=>$rs_blog_recommend];
        return $rs;
    }
    
    public function hot_tag(){
        
    }
    
    public function hot_article($lastid=0){
        
    }
    
    //热门博主
    public function hot_blogger(){
        $obj_memcache = func_initMemcached('cache01');
        $rs_memcache = $obj_memcache->get("blog_hot_blogger");
        
        if(empty($rs_memcache)){
            $rs=['status'=>false,'msg'=>"",'data'=>""];
            return $rs;
        }
        
        $rs=['status'=>true,'msg'=>"",'data'=>$rs_memcache];
        return $rs;
    }
    
    public function follower_article(){
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}





































