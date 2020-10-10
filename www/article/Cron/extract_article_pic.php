<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/blog/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class extract_article_pic{
    function start(){
        
    }
}

$obj = new extract_article_pic();
$obj->start();


































































