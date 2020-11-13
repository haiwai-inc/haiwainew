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
            $this->obj_blog_category=load("blog_category");
            $this->obj_article_indexing=load("article_indexing");
            $this->obj_article_tag=load("article_tag");
            $this->obj_blog_blogger=load("blog_blogger");
            $this->obj_blog_hot=load("blog_hot");
            $this->obj_article_post=load("article_post");
            $this->obj_article_post_tag=load("article_post_tag");
            $this->obj_account_notification=load("account_notification");
            $this->obj_account_blacklist=load("account_blacklist");
            $this->obj_account_bookmark=load("account_bookmark");
            $this->account_follower=load("account_follower");
            $this->account_qqh=load("account_qqh");
            $this->account_qqh_post=load("account_qqh_post");
            $this->blog_recommend=load("account_blog_recommend");
            
            
            $this->obj_account_user->exec("TRUNCATE TABLE `user`");
            $this->obj_blog_category->exec("TRUNCATE TABLE `category`");
            $this->obj_article_indexing->exec("TRUNCATE TABLE `indexing`");
            $this->obj_article_tag->exec("TRUNCATE TABLE `tag`");
            $this->obj_blog_blogger->exec("TRUNCATE TABLE `blogger`");
            $this->obj_blog_hot->exec("TRUNCATE TABLE `hot`");
            $this->obj_account_blacklist->exec("TRUNCATE TABLE `blacklist`");
            $this->obj_account_bookmark->exec("TRUNCATE TABLE `bookmark`");
            $this->account_follower->exec("TRUNCATE TABLE `follower`");
            $this->account_qqh->exec("TRUNCATE TABLE `qqh`");
            $this->account_qqh_post->exec("TRUNCATE TABLE `qqh_post`");
            $this->blog_recommend->exec("TRUNCATE TABLE `recommend`");
            
            
            for ($i = 0; $i <= 9; $i++) {
                $this->obj_article_post->exec("TRUNCATE TABLE `post_{$i}`");
                $this->obj_article_post_tag->exec("TRUNCATE TABLE `post_tag_{$i}`");
                $this->obj_article_post_tag->exec("TRUNCATE TABLE `post_tag_{$i}`");
                $this->obj_account_notification->exec("TRUNCATE TABLE `notification_{$i}`");
            }
        }
    }
}



$argv[1] = empty($argv[1])?"":$argv[1];
$obj = new clear_blog_data($argv[1]);


































































