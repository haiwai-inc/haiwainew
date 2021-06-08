<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/blog/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class report{
    function start(){
        
        $obj_blog_legacy_blogger_haiwai=load("blog_legacy_blogger_haiwai");
        $obj_account_user=load("account_user");
        
        $lastid=0;
        while($rs_blog_legacy_blogger_haiwai=$obj_blog_legacy_blogger_haiwai->getAll("*",['order'=>['id'=>'ASC'],'limit'=>20,'id,>'=>$lastid]) ){
            foreach($rs_blog_legacy_blogger_haiwai as $v){
                $check_account_user=$obj_account_user->getOne(['id','username'],['username'=>$v['username']]);
                if(!empty($check_account_user)){
                    $obj_blog_legacy_blogger_haiwai->update(['haiwai_userID'=>$v['id']],['username'=>$check_account_user['username']]);
                }
                $lastid=$v['id'];
            }
        }
    }
}

$obj = new report();
$obj->start();


































































