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
        debug::d($rs_article_tag);
        exit;
    }
}

$obj = new generate_hot_tag();
$obj->start();


































































