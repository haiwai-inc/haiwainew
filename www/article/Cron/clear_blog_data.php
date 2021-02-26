<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/article/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();


class clear_blog_data{
    function __construct($password){
        if($password=="wxc123456"){
            $this->obj_account_user=load("account_user");
            $this->obj_account_user_login=load("account_user_login");
            $this->obj_account_user_auth=load("account_user_auth");
            $this->obj_blog_category=load("blog_category");
            $this->obj_article_indexing=load("article_indexing");
            $this->obj_article_tag=load("article_tag");
            $this->obj_blog_blogger=load("blog_blogger");
            $this->obj_article_draft=load("article_draft");
            $this->obj_article_post=load("article_post");
            $this->obj_article_post_tag=load("article_post_tag");
            $this->obj_account_notification=load("account_notification");
            $this->obj_account_blacklist=load("account_blacklist");
            $this->obj_account_bookmark=load("account_bookmark");
            $this->obj_account_follow=load("account_follow");
            $this->obj_account_qqh=load("account_qqh");
            $this->obj_account_qqh_post=load("account_qqh_post");
            $this->obj_blog_recommend=load("blog_recommend");
            $this->obj_article_post_buzz=load("article_post_buzz");
            $this->obj_article_post_increment=load("article_post_increment");
            $this->obj_article_post_tag_increment=load("article_post_tag_increment");
            $this->obj_account_user_email=load("account_user_email");
            
            
            $this->obj_account_user->exec("TRUNCATE TABLE `user`");
            $this->obj_account_user_login->exec("TRUNCATE TABLE `user_login`");
            $this->obj_account_user_auth->exec("TRUNCATE TABLE `user_auth`");
            $this->obj_blog_category->exec("TRUNCATE TABLE `category`");
            $this->obj_article_draft->exec("TRUNCATE TABLE `draft`");
            $this->obj_article_indexing->exec("TRUNCATE TABLE `indexing`");
            $this->obj_article_tag->exec("TRUNCATE TABLE `tag`");
            $this->obj_blog_blogger->exec("TRUNCATE TABLE `blogger`");
            $this->obj_account_blacklist->exec("TRUNCATE TABLE `blacklist`");
            $this->obj_account_bookmark->exec("TRUNCATE TABLE `bookmark`");
            $this->obj_account_follow->exec("TRUNCATE TABLE `follow`");
            $this->obj_account_qqh->exec("TRUNCATE TABLE `qqh`");
            $this->obj_account_qqh_post->exec("TRUNCATE TABLE `qqh_post`");
            $this->obj_blog_recommend->exec("TRUNCATE TABLE `recommend`");
            $this->obj_article_post_increment->exec("TRUNCATE TABLE `post_increment`");
            $this->obj_article_post_tag_increment->exec("TRUNCATE TABLE `post_tag_increment`");
            $this->obj_account_user_email->exec("TRUNCATE TABLE `user_email`");
            
            for ($i = 0; $i <= 9; $i++) {
                $this->obj_article_post->exec("TRUNCATE TABLE `post_{$i}`");
                $this->obj_article_post_tag->exec("TRUNCATE TABLE `post_tag_{$i}`");
                $this->obj_account_notification->exec("TRUNCATE TABLE `notification_{$i}`");
                $this->obj_article_post_buzz->exec("TRUNCATE TABLE `post_buzz_{$i}`");
            }
        }
    }
}



$argv[1] = empty($argv[1])?"":$argv[1];
$obj = new clear_blog_data($argv[1]);


































































