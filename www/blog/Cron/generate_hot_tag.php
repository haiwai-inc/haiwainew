<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/blog/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class generate_hot_tag{
    function start(){
        $this->obj_article_tag=load("article_tag");
        
        $rs_article_tag=$this->obj_article_tag->getAll("*",['visible'=>1,'limit'=>20,'order'=>['count_article'=>'DESC']]);
        if(!empty($rs_article_tag)){
            foreach($rs_article_tag as $v){
                $obj_article_index=load("search_article_index");
                $rs_article_index=$obj_article_index->search_tags([$tagID],$lastID);
            }
        }
        
        
        debug::D($rs_article_tag);
        exit;
        
        
        $obj_memcache = func_initMemcached('cache01');
        $obj_memcache->set("blog_hot_tag",$rs_article_tag,3600*24);
    }
}

$obj = new generate_hot_tag();
$obj->start();


































































