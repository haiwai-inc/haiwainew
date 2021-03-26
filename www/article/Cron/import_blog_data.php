<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/article/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class import_blog_data{
    function start(){
        $obj_blog_tool=load("blog_tool");
        $obj_blog_tool->load_all_db();
        $lastid=0;
        while( $rs_blog_legacy_202005_post = $obj_blog_tool->obj_blog_legacy_202005_post->getAll("*",['order'=>['postid'=>'ASC'],'limit'=>20,'postid,>'=>$lastid,'visible'=>1],"blog_202001_post") ){                      
            foreach($rs_blog_legacy_202005_post as $k=>$v){
                $lastid=$v['postid'];
                $obj_blog_tool->import_post($v);
                
                echo $lastid."\n";
            }
        }
    }
}

$obj = new import_blog_data();
$obj->start();


































































