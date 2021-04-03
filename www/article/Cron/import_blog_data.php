<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/article/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class import_blog_data{
    function start(){
        $obj_blog_tool=load("blog_tool");
        $obj_blog_tool->load_all_db();
        $lastid=0;
        
        for($i=1;$i<=12;$i++){
            $month=substr('00'.$i,-2);
            while( $rs_blog_legacy_202005_post = $obj_blog_tool->obj_blog_legacy_202005_post->getAll("*",['order'=>['postid'=>'ASC'],'limit'=>20,'postid,>'=>$lastid,'visible'=>1],"blog_2020{$month}_post") ){
                $postID_legacy_hot_post=[];
                foreach($rs_blog_legacy_202005_post as $k=>$v){
                    $lastid=$v['postid'];
                    $rs_import_post=$obj_blog_tool->import_post($v);
                    $postID_legacy_hot_post[]=$rs_import_post['article_new']['postID'];
                    
                    echo $lastid."_".$month."\n";
                }
                
                //同步ES索引
                $obj_article_noindex=load("search_article_noindex");
                $obj_article_noindex->fetch_and_insert($postID_legacy_hot_post);
            }
        }
        
    }
}

$obj = new import_blog_data();
$obj->start();


































































