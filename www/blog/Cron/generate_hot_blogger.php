<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/blog/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class generate_hot_blogger{
    function start(){
        $this->obj_blog_blogger=load("blog_blogger");
        $rs_blog_blogger=$this->obj_blog_blogger->getAll(['id','userID','count_follower','count_buzz','count_article','count_comment','count_read','description'],['status'=>1,'limit'=>200,'order'=>['count_read'=>'DESC']]);
        if(!empty($rs_blog_blogger)){
            foreach($rs_blog_blogger as $k=>$v){
                $rs_blog_blogger[$k]['bloggerID']=$v['id'];
            }
            
            $obj_memcache = func_initMemcached('cache01');
            $obj_memcache->set("blog_hot_blogger",$rs_blog_blogger, 3600*24);
        }
    }
}

$obj = new generate_hot_blogger();
$obj->start();


































































