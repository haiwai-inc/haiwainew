<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/blog/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class generate_recent_article{
    function start(){
        $obj_article_indexing=load("article_indexing");
        $fields=[
            'typeID'=>1,
            'is_publish'=>1,
            'treelevel'=>0,
            'visible'=>1,
            'limit'=>300,
            'order'=>['create_date'=>'DESC']
        ];
        $rs_article_indexing=$obj_article_indexing->getAll(["userID","postID","create_date"],$fields);
        
        //最新缓存
        $obj_memcache = func_initMemcached('cache03');
        $obj_memcache->set("blog_recent_article",$rs_article_indexing,3600*24);
    }
}

$obj = new generate_recent_article();
$obj->start();


































































