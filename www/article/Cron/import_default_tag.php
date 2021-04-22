<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/article/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

//汪老师https://blog.wenxuecity.com/myoverview/69027/
//润涛阎https://blog.wenxuecity.com/myoverview/1666/

class import_default_tag{
    function start(){
        $obj_article_tag=load("article_tag");
        $default_tag=conf("search.default_tag");
        foreach($default_tag as $v){
            $obj_article_tag->insert(['id'=>$v['id'],"name"=>$v['name']]);
        }
    }
}

$obj = new import_default_tag();
$obj->start();


































































