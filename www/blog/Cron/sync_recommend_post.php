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
        
        $rs_legacy_hot_post=$obj_legacy_hot_post->getAll("*",['limit'=>80,'order'=>['id'=>'DESC']]);
        foreach($rs_legacy_hot_post as $v){
            //import legacy post
            $rs=$obj_blog_legacy_202005_post->getOne("*",['postid'=>$v['postid']],"blog_{$v['date']}_post");
            $rs_import_post=$obj_blog_tool->import_post($rs);
            
            $check_blog_hot=$obj_blog_recommend->getOne("*",['postID'=>$rs_import_post['article_new']['postID']]);
            if(empty($check_blog_hot)){
                $fields=[
                    "postID"=>$rs_import_post['article_new']['postID'],
                    "userID"=>$rs_import_post['user_new']['id'],
                    "title"=>$v['title'],
                    "create_date"=>times::getTime(),
                ];
                $obj_blog_recommend->insert($fields);
            }
            
            echo $v['id']."\n";
        }
    }
}

$obj = new sync_recommend_post();
$obj->start();

































































