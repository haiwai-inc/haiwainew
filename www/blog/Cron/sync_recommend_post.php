<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/blog/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class sync_recommend_post{
    function start(){
        $obj_blog_tool=load("blog_tool");
        $obj_legacy_hot_post=load("blog_legacy_hot_post");
        $obj_blog_recommend=load("blog_recommend");
        $obj_blog_legacy_202005_post=load("blog_legacy_202005_post");
        $obj_blog_legacy_blogger_haiwai=load("blog_legacy_blogger_haiwai");
        $obj_blog_wxc_postID=load("blog_wxc_postID");
        
        $rs_legacy_hot_post=$obj_legacy_hot_post->getAll("*",['id,>'=>179806,'limit'=>80,'order'=>['id'=>'DESC']]);
        $postID_legacy_hot_post=[];
        foreach($rs_legacy_hot_post as $v){
            echo $v['id']."\n";
            
            //检查是否为600人
            $check_blog_legacy_blogger_haiwai=$obj_blog_legacy_blogger_haiwai->getOne(['id'],['userid'=>$v['userid'],'id,<'=>602]);
            if(empty($check_blog_legacy_blogger_haiwai)){
                continue;
            }
            
            //检查原帖是否导入
            $check_blog_wxc_postID=$obj_blog_wxc_postID->getOne("*",['wxc_postID'=>"{$v['date']}_{$v['postid']}","wxc_userID"=>$v['userid']]);
            if(empty($check_blog_wxc_postID)){
                //原帖信息
                $rs=$obj_blog_legacy_202005_post->getOne("*",['postid'=>$v['postid']],"blog_{$v['date']}_post");
                
                //导入新数据库
                $rs['date']=substr($rs['dateline'],0,7); //=========================主贴时间
                $rs_import_post=$obj_blog_tool->import_post($rs);
                
                $check_blog_wxc_postID['postID']=$rs_import_post['article_new']['postID'];
                $check_blog_wxc_postID['userID']=$rs_import_post['user_new']['id'];
                $postID_legacy_hot_post[]=$check_blog_wxc_postID['postID'];
            }
            
            //检查推荐是否导入
            $check_blog_hot=$obj_blog_recommend->getOne(['id'],['title'=>$v['title']]);
            if(empty($check_blog_hot)){
                $fields=[
                    "postID"=>$check_blog_wxc_postID['postID'],
                    "userID"=>$check_blog_wxc_postID['userID'],
                    "title"=>$v['title'],
                    "is_publish"=>1,//$rs_import_post['article_new']['is_publish'],
                    "create_date"=>times::getTime(),
                ];
                $obj_blog_recommend->insert($fields);
            }
        }
        
        //同步ES索引
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_noindex->fetch_and_insert($postID_legacy_hot_post);
    }
}

$obj = new sync_recommend_post();
$obj->start();


































































