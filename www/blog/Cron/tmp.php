<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/blog/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class tmp{
    //添加海外与文学成博主
    function start1(){
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
    function start2(){
        $obj_account_user=load("account_user");
        $obj_account_follow=load("account_follow");
        
        //官方博客id
        $bloggerID=17298;
        //$bloggerID=80;
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
    
    //修复隐藏目录
    //344 207
    function start3(){
        $obj_blog_blogger=load("blog_blogger");
        $obj_blog_category=load("blog_category");
        $obj_blog_legacy_blogcat_members=load("blog_legacy_blogcat_members");
        $obj_account_user=load("account_user");
        $obj_account_legacy_user=load("account_legacy_user");
        $lastid=0;
        
        while($rs_blog_category=$obj_blog_category->getAll("*",['is_default'=>0,'order'=>['id'=>'ASC'],'limit'=>20,'id,>'=>$lastid])){
            foreach($rs_blog_category as $v){
                $lastid=$v['id'];
                
                //获取haiwai用户信息
                $rs_blog_blogger=$obj_blog_blogger->getOne("*",['id'=>$v['bloggerID']]);
                $rs_account_user=$obj_account_user->getOne("*",['id'=>$rs_blog_blogger['userID']]);
                
                //获取文学成用户信息
                $rs_blog_legacy_blogcat_members=$obj_blog_legacy_blogcat_members->getAll("*",['username'=>$rs_account_user['username']]);
                if(!empty($rs_blog_legacy_blogcat_members)){
                    debug::d($rs_blog_legacy_blogcat_members);
                }
                exit;
                echo $lastid."\n";
            }
        }
    }
    
    //恢复我的隐藏文章
    function start(){
        $obj_article_indexing=load("article_indexing");
        $obj_blog_category=load("blog_category");
        $lastid=0;
        while($rs_article_indexing=$obj_article_indexing->getAll("*",['treelevel'=>0,'order'=>['id'=>'ASC'],'limit'=>20,'id,>'=>$lastid])){
            foreach($rs_article_indexing as $v){
                $lastid=$v['id'];
                $check_blog_category=$obj_blog_category->getOne("*",['id'=>$v['categoryID'],'is_default'=>1,'bloggerID'=>$v['bloggerID']]);
                if(!empty($check_blog_category)){
                    echo $v['is_publish']."\n";
                    //$obj_article_indexing->update(['is_publish'=>1],['id'=>$v['id']]);
                }
                
                echo $v['id']."\n";
            }
        }
    }
}

$obj = new tmp();
$obj->start();


































































