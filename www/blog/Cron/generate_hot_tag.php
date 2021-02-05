<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/blog/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class generate_hot_tag{
    function start(){
        $obj_account_user=load("account_user");
        $obj_article_tag=load("article_tag");
        $obj_article_index=load("search_article_index");
        $obj_article_indexing=load("article_indexing");
        $obj_article_noindex=load("search_article_noindex");
        
        //全部文章
        $rs_article_hot[0]=$obj_article_indexing->getAll(['postID','userID'],['limit'=>120,'visible'=>1,'order'=>['count_read'=>'DESC']]);
        
        //ES补全postID信息
        $rs_article_hot[0]=$obj_article_noindex->get_postInfo($rs_article_hot[0]);
        
        //添加用户信息
        $rs_article_hot[0]=$obj_account_user->get_basic_userinfo($rs_article_hot[0],"userID");
        
        //添加文章计数信息
        $rs_article_hot[0]=$obj_article_indexing->get_article_count($rs_article_hot[0]);
        
        //按标签区分
        $rs_article_tag=$obj_article_tag->getAll("*",['visible'=>1,'limit'=>20,'order'=>['count_article'=>'DESC']]);
        if(!empty($rs_article_tag)){
            foreach($rs_article_tag as $v){
                //读数倒排
                $rs_article_hot[$v['id']]=$obj_article_index->search_tags([$v['id']],0,["count_read"=>array("order"=>"desc")],120);
                
                //添加用户信息
                $rs_article_hot[$v['id']]=$obj_account_user->get_basic_userinfo($rs_article_hot[$v['id']],"userID");
                
                //添加文章计数信息
                $rs_article_hot[$v['id']]=$obj_article_indexing->get_article_count($rs_article_hot[$v['id']]);
                echo $v['id']."\n";
            }
        }
        
        $obj_memcache = func_initMemcached('cache01');
        $obj_memcache->set("blog_hot_tag",$rs_article_tag,3600*24);
        $obj_memcache->set("blog_hot_article",$rs_article_hot,3600*24);
    }
}

$obj = new generate_hot_tag();
$obj->start();


































































