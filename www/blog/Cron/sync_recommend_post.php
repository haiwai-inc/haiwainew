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
        $postID_legacy_hot_post=[];
        foreach($rs_legacy_hot_post as $v){
            //检查是否导入
            $check_blog_hot=$obj_blog_recommend->getOne(['id'],['title'=>$v['title']]);
            if(!empty($check_blog_hot)){
                continue;
            }
            
            //原帖信息
            $rs=$obj_blog_legacy_202005_post->getOne("*",['postid'=>$v['postid']],"blog_{$v['date']}_post");
            
            //导入新数据库
	        $rs['date']=substr($rs['dateline'],0,7); //=========================主贴时间
            $rs_import_post=$obj_blog_tool->import_post($rs);
            $check_blog_hot=$obj_blog_recommend->getOne("*",['postID'=>$rs_import_post['article_new']['postID']]);
            if(empty($check_blog_hot)){
                $fields=[
                    "postID"=>$rs_import_post['article_new']['postID'],
                    "userID"=>$rs_import_post['user_new']['id'],
                    "title"=>$v['title'],
                    "is_publish"=>1,//$rs_import_post['article_new']['is_publish'],
		            "create_date"=>times::getTime(),
                ];
                $obj_blog_recommend->insert($fields);
            }
            
            $postID_legacy_hot_post[]=$rs_import_post['article_new']['postID'];
            echo $v['id']."\n";
        }
        
        //同步ES索引
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_noindex->fetch_and_insert($postID_legacy_hot_post);
    }
}

$obj = new sync_recommend_post();
$obj->start();


































































