<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/blog/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class generate_recent_article{
    function start(){
        $obj_article_indexing=load("article_indexing");
        $obj_blog_recommend=load("blog_recommend");
        
        
        $lastid=times::gettime();
        $fields=[
            'typeID'=>1,
            'is_publish'=>1,
            'treelevel'=>0,
            'visible'=>1,
            'limit'=>300,
            "create_date,<"=>$lastid,
            'order'=>['create_date'=>'DESC']
        ];
        
        $rs=[];
        $count=0;
        while($rs_article_indexing=$obj_article_indexing->getAll(["userID","postID","create_date"],$fields)){
            if($count>300){
                break;
            }
            foreach($rs_article_indexing as $v){
                //查看是否推荐
                $check_blog_recommend=$obj_blog_recommend->getOne(['id'],['postID'=>$v['postID']]);
                if(!empty($check_blog_recommend)){
                    continue;
                }else{
                    $rs[]=$v;
                    $count++;
                }
                
                echo $v['postID']."\n";
            }
        }
        
        //最新缓存
        $obj_memcache = func_initMemcached('cache03');
        $obj_memcache->set(FILE_DOMAIN."blog_recent_article",$rs_article_indexing,3600*24);
    }
}

$obj = new generate_recent_article();
$obj->start();


































































