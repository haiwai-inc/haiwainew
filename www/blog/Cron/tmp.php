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
                $rs_blog_legacy_blogcat_members=$obj_blog_legacy_blogcat_members->getOne("*",['category'=>$v['name'],'username'=>$rs_account_user['username']]);
                if(!empty($rs_blog_legacy_blogcat_members)){
                    $obj_blog_category->update(['is_publish'=>$rs_blog_legacy_blogcat_members['visible']],['id'=>$v['id']]);
                }
                echo $lastid."\n";
            }
        }
    }
    
    //恢复我的隐藏文章
    function start4(){
        $obj_article_indexing=load("article_indexing");
        $obj_blog_category=load("blog_category");
        $lastid=0;
        while($rs_article_indexing=$obj_article_indexing->getAll("*",['treelevel'=>0,'order'=>['id'=>'ASC'],'limit'=>20,'id,>'=>$lastid])){
            foreach($rs_article_indexing as $v){
                $lastid=$v['id'];
                $check_blog_category=$obj_blog_category->getOne("*",['id'=>$v['categoryID'],'is_default'=>1,'bloggerID'=>$v['bloggerID']]);
                if(!empty($check_blog_category)){
                    //echo $v['is_publish']."\n";
                    $obj_article_indexing->update(['is_publish'=>1],['id'=>$v['id']]);
                }
                
                echo $v['id']."\n";
            }
        }
    }
    
    //修复5月以后的文章内容
    function start5(){
        $obj_article_indexing_wxc=load("article_indexing_wxc");
        $obj_blog_legacy_202005_post=load("blog_legacy_202005_post");
        $obj_blog_legacy_202005_msg=load("blog_legacy_202005_msg");
        $obj_article_indexing=load("article_indexing");
        $obj_article_post=load("article_post");
        
        $lastid=1406117;
        while($rs_article_indexing_wxc=$obj_article_indexing_wxc->getAll("*",['id,>'=>$lastid,'limit'=>20,'order'=>['id'=>'ASC']])){
            $postID_legacy_hot_post=[];
            foreach($rs_article_indexing_wxc as $v){
                $lastid=$v['id'];
                //获取wxc postid
                $wxc_postid_array=explode("_",$v['wxc_postid']);
                $wxc_date_array=explode("-",$wxc_postid_array[0]);
                $wxc_date=$wxc_date_array[0].$wxc_date_array[1];
                $wxc_postid=$wxc_postid_array[2];
                
                //查询wxc内容
                $rs_blog_legacy_202005_post=$obj_blog_legacy_202005_post->getOne("*",['treelevel'=>0,'postid'=>$wxc_postid],"blog_{$wxc_date}_post");
                if(empty($rs_blog_legacy_202005_post)){
                    continue;
                }
                $rs_blog_legacy_202005_msg=$obj_blog_legacy_202005_msg->getOne("*",['postid'=>$rs_blog_legacy_202005_post['postid']],"blog_{$wxc_date}_msg");
                if(empty($rs_blog_legacy_202005_msg)){
                    continue;
                }
                
                //替换haiwai内容
                $rs_article_indexing=$obj_article_indexing->getOne("*",['postID'=>$v['postID']]);
                
                $post_tbn=substr('0'.$rs_article_indexing['userID'],-1);
                //$rs_article_post=$obj_article_post->getOne("*",['id'=>$rs_article_indexing['postID']],"post_{$post_tbn}");
                
                $msgbody=str_replace("/upload/album/","http://cdn.wenxuecity.com/upload/album/",$rs_blog_legacy_202005_msg['msgbody']);
                $obj_article_post->update(['msgbody'=>$msgbody],['id'=>$rs_article_indexing['postID']],"post_{$post_tbn}");
                
                $postID_legacy_hot_post[]=$rs_article_indexing['postID'];
                echo $v['id']."\n";
            }
            
            //同步ES索引
            $obj_article_noindex=load("search_article_noindex");
            $obj_article_noindex->fetch_and_insert($postID_legacy_hot_post);
        }
    }
}

$obj = new tmp();
$obj->start5();


































































