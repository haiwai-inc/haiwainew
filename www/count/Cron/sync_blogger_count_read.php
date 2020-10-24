<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/count/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class sync_blogger_count_read{
    function start(){
        $obj_count_tool=load("count_tool");
        $obj_article_indexing=load("article_indexing");
        $obj_blog_blogger=load("blog_blogger");
        
        
        $rs_blogger_key=$obj_count_tool->obj_redis->SMEMBERS($obj_count_tool->sync_blogger_key);
        if(!empty($rs_blogger_key)){
            foreach($rs_blogger_key as $v){
                
                //count all articles
                $lastid=0;
                $count=0;
                while($rs_article_indexing=$obj_article_indexing->getAll(['postID','count_read'],['bloggerID'=>$v,'postID,>'=>$lastid,'limit'=>500,'treelevel'=>0,'visible'=>1,'order'=>['postID'=>'ASC']])){
                    foreach($rs_article_indexing as $vv){
                        $lastid=$vv['postID'];
                        $count+=$vv['count_read'];
                        echo "{$vv['postID']}\n";
                    }
                }
                
                //sync database
                $obj_blog_blogger->update(['count_read'=>$count],['id'=>$v]);
                
                //remove sync_blogger_key
                $obj_count_tool->obj_redis->srem($obj_count_tool->sync_blogger_key,$v);
                echo "========================================{$v}\n";
            }
        }
    }
}

$obj = new sync_blogger_count_read();
$obj->start();


































































