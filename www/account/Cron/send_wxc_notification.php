<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/account/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class send_wxc_notification{
    function start(){
        $obj_account_notification=load("account_notification");
        $time=times::gettime()-86400;
        $dateline=date("Y-m-d H:i:s",times::getTime());
        
        $send_pool=[];
        for($i=0;$i<=9;$i++){
            $lastid=0;
            while($rs_account_notification=$obj_account_notification->getAll("*",['create_date,>'=>$time,'id,>'=>$lastid,'limit'=>100,'order'=>['id'=>'ASC']],"notification_{$i}")){                
                foreach($rs_account_notification as $v){
                    $lastid=$v['id'];
                    if(empty($send_pool[$v['userID']])){
                        $send_pool[$v['userID']]="以下是过去一天您在海外网的通知一览，请前往 <a href='".FILE_DOMAIN."/notices?id=0' target='_blank'>Haiwai.com</a> 查看详情。

".$v['msgbody']."\n";
                    }else{
                        $send_pool[$v['userID']].=$v['msgbody']."\n";
                    }
                }
            }
        }
        
        //发送qqh
        $obj_account_user_auth=load("account_user_auth");
        $obj_account_legacy_user=load("account_legacy_user");
        $obj_legacy_pm_inbox_post=load("account_legacy_pm_inbox_post");
        $obj_legacy_pm_inbox_msg=load("account_legacy_pm_inbox_msg");
        foreach($send_pool as $k=>$v){
            //检查是否绑定wxc
            $rs_account_user_auth=$obj_account_user_auth->getOne("*",['login_source'=>"wxc",'userID'=>$k]);
            if(empty($rs_account_user_auth)){
                continue;
            }
            
            //检查是否有效wxc用户
            $rs_account_legacy_user=$obj_account_legacy_user->getOne(['userid'],['status'=>1,'verified'=>'Yes','username'=>$rs_account_user_auth['login_data']]);
            if(empty($rs_account_legacy_user)){
                continue;
            }
            
            //检查是否发送 12小时内
            $last_dateline=date("Y-m-d H:i:s",times::getTime()-46400);
            $check_legacy_pm_inbox_post=$obj_legacy_pm_inbox_post->getOne("*",['dateline,>'=>$last_dateline,'title'=>'来自海外名博的消息','userid'=>356756,'touserid'=>$rs_account_legacy_user['userid']]);
            if(!empty($check_legacy_pm_inbox_post)){
                continue;
            }
            
            //测试用户 sidatesting wxc123456
            if($rs_account_legacy_user['userid']!="866909"){
                continue;
            }
            
            //插入悄悄话
            $fields=[
                "username"=>"论坛管理",
                "userid"=>"356756",
                "touserid"=>$rs_account_legacy_user['userid'],
                "title"=>"来自海外 Haiwai.com 的通知",
                "dateline"=>$dateline,
                "size"=>strlen($v),
                'flag'=>0,
                'view'=>0,
                'reply'=>0,
                'visible'=>1,
                'outbox_visible'=>1,
            ];
            
            $postid=$obj_legacy_pm_inbox_post->insert($fields);
            $obj_legacy_pm_inbox_msg->insert(['postid'=>$postid,'msgbody'=>nl2br($v)]);
            echo $k."\n";
        }
    }
}

$obj = new send_wxc_notification();
$obj->start();


































































