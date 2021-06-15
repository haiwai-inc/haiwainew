<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/blog/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class tmp{
    //添加海外与文学成博主
    function _start(){
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
    
    //添加官博
    function start(){
        $obj_account_user=load("account_user");
        $obj_account_follow=load("account_follow");
        
        //$bloggerID=17298;
        $bloggerID=80;
        $lastid=0;
        $time=times::gettime();
        while($rs_account_user=$obj_account_user->getAll("*",['status'=>1,'order'=>['id'=>'ASC'],'limit'=>20,'id,>'=>$lastid])){
            foreach($rs_account_user as $v){
                $lastid=$v['id'];
                $check_account_follow=$obj_account_follow->getOne("*",['followerID'=>$v['id'],'followingID'=>$bloggerID]);
                if(empty($check_account_follow)){
                    $obj_account_follow->insert(['followerID'=>$v['id'],'followingID'=>$bloggerID,'follower_update'=>$time,'following_update'=>$time]);
                }
                echo $lastid."\n";
            }
        }
    }
}

$obj = new tmp();
$obj->start();


































































