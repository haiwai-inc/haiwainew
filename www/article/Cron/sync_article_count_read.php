<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/blog/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

//article: count_read
//blogger: count_read

class sync_article_count_read{
    function start(){
        
    }
}

$obj = new sync_article_count_read();
$obj->start();


































































