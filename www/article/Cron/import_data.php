<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/article/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();


class import_data{
    function start(){
        $obj_account_user=load("account_user");
        $obj_account_legacy_user=load("account_legacy_user");
        
        $obj_article_category=load("article_category");
        $obj_article_indexing=load("article_indexing");
        $obj_article_tag=load("article_tag");
        $obj_article_post=load("article_post_0");
        $obj_article_post_tag=load("article_post_tag_0");
        
        $obj_blog_blogger=load("blog_blogger");
        
        $obj_blog_legacy_blogger=load("blog_legacy_blogger");
        $obj_blog_legacy_blogcat=load("blog_legacy_blogcat");
        $obj_blog_legacy_blogcat_members=load("blog_legacy_blogcat_members");
        $obj_blog_legacy_202005_post=load("blog_legacy_202005_post");
        $obj_blog_legacy_202005_msg=load("blog_legacy_202005_msg");
        
        
        
        debug::d($obj_account_legacy_user);
        exit;
    }
}




$obj = new import_data();
$obj->start();











