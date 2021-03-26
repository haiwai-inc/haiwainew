<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/count/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class sync_article_count_read{
    function start(){
        $obj_count_tool=load("count_tool");
        $obj_article_indexing=load("article_indexing");
        
        $rs_article_key=$obj_count_tool->obj_redis->SMEMBERS($obj_count_tool->sync_article_key);
        if(!empty($rs_article_key)){
            foreach($rs_article_key as $v){
                $rs_article_indexing=$obj_article_indexing->getOne(['bloggerID','count_read'],['postID'=>$v]);
                if($count_read<$rs_article_indexing['count_read']){
                    continue;
                }
                
                $count_read=$obj_count_tool->view_article($v);
                
                //sync database
                $obj_article_indexing->update(['count_read'=>$count_read],['postID'=>$v]);
                
                //remove sync_article_key
                $obj_count_tool->obj_redis->srem($obj_count_tool->sync_article_key,$v);
                
                //add sync blogger key
                $obj_count_tool->obj_redis->sAdd($obj_count_tool->sync_blogger_key, $rs_article_indexing['bloggerID']);
                
                echo "{$v}\n";
            }
        }
    }
}

$obj = new sync_article_count_read();
$obj->start();


































































