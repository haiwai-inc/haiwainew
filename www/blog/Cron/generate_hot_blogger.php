<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/blog/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class generate_hot_blogger{
    function start(){
        $this->obj_blog_blogger=load("blog_blogger");
        $this->obj_account_user=load("account_user");
        
        $rs_blog_blogger=$this->obj_blog_blogger->getAll("*",['status'=>1,'limit'=>200,'order'=>['count_read'=>'DESC']]);
        if(!empty($rs_blog_blogger)){
            foreach($rs_blog_blogger as $v){
                $tmp_blog_blogger[]=$v['userID'];
            }
            $rs_account_user=$this->obj_account_user->getAll("*",['or'=>['id'=>$tmp_blog_blogger]]);
            foreach($rs_account_user as $v){
                $hash_account_user[$v['id']]=$v;
            }
            foreach($rs_blog_blogger as $k=>$v){
                if(!empty($hash_account_user[$v['userID']]['avatar'])){
                    $rs_blog_blogger[$k]['avatar']=$hash_account_user[$v['userID']]['avatar'];
                }else{
                    $rs_blog_blogger[$k]['avatar']="";
                }
            }
        }
        
        $obj_memcache = func_initMemcached('cache01');
        $obj_memcache->set("blog_hot_blogger",$rs_blog_blogger,false, 3600*24);
    }
}

$obj = new generate_hot_blogger();
$obj->start();


































































