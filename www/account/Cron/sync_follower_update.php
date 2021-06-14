<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/account/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';

class sync_follower_update{
    function start(){
        $obj_account_follow=load("account_follow");
        $obj_account_follow->init();
        
        $rs_follower_update_key=$obj_account_follow->obj_redis->SMEMBERS($obj_account_follow->sync_follower_update_key);
        if(!empty($rs_follower_update_key)){
            foreach($rs_follower_update_key as $v){
                $rs_account_follow=explode("_",$v);
                
                //同步数据库
                $time=times::gettime();
                $obj_account_follow->update(['follower_update'=>$time],['followerID'=>$rs_account_follow[0],'followingID'=>$rs_account_follow[1]]);
                
                //移除更新ID
                $obj_account_follow->obj_redis->srem($obj_account_follow->sync_follower_update_key,$v);
                
                echo "{$v}\n";
            }
        }
    }
}

$obj = new sync_follower_update();
$obj->start();


































































