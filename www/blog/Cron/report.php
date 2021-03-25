<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/blog/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class report{
    function start(){
        $this->obj_blog_legacy_blogger=load("blog_legacy_blogger");
        $this->obj_blog_legacy_202005_post=load("blog_legacy_202005_post");
        $this->obj_blog_legacy_hot_blogger=load("blog_legacy_hot_blogger");
        
        $lastid=0;
        
        $count_hot_blogger=0;
        while($rs_post = $this->obj_blog_legacy_202005_post->getAll("*",['treelevel'=>0,'order'=>['postid'=>'ASC'],'limit'=>20,'postid,>'=>$lastid,'visible'=>1]) ){
            foreach($rs_post as $v){
                $check_hot_blogger=$this->obj_blog_legacy_hot_blogger->getAll("*",['blogid'=>$v['blogid']]);
                if(!empty($check_hot_blogger)){
                    $count_hot_blogger++;
                }
                $lastid=$v['postid'];
            }
        }
        
        debug::d($count_hot_blogger);
        exit;
    }
}

$obj = new report();
$obj->start();


































































