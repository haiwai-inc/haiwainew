<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/article/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

//汪老师https://blog.wenxuecity.com/myoverview/69027/
//润涛阎https://blog.wenxuecity.com/myoverview/1666/

class import_blog_data{
    function start($year,$month){
        $obj_blog_tool=load("blog_tool");
        $obj_blog_tool->load_all_db();
        $lastid=0;
        
        $obj_article_noindex=load("search_article_noindex");
        
        for($i=1;$i<=12;$i++){
            $month_tbn=substr('00'.$i,-2);
            if(!empty($month) && $month!=$month_tbn){
                continue;
            }
            
            $lastid=0;
            $rs_blog_legacy_202005_post=[];
            while( $rs_blog_legacy_202005_post = $obj_blog_tool->obj_blog_legacy_202005_post->getAll("*",['treelevel'=>0,'order'=>['postid'=>'ASC'],'limit'=>20,'postid,>'=>$lastid,'visible,!='=>0],"blog_{$year}{$month_tbn}_post") ){
                $postID_legacy_hot_post=[];
                foreach($rs_blog_legacy_202005_post as $k=>$v){
                    $lastid=$v['postid'];
                    echo $lastid."_".$month_tbn."\n";
                    
                    //热门博主搜索
                    $obj_blog_legacy_blogger_haiwai=load("blog_legacy_blogger_haiwai");
                    $check_blog_legacy_blogger_haiwai=$obj_blog_legacy_blogger_haiwai->getOne(['id'],['userid'=>$v['userid']]);
                    
                    
                    debug::d($check_blog_legacy_blogger_haiwai);
                    exit;
                    
                    
                    if(!empty($check_blog_legacy_blogger_haiwai)){
                        //主贴
                        $v['date']=substr($v['dateline'],0,7); //=========================主贴时间
                        $rs_import_post=$obj_blog_tool->import_post($v);
                        $postID_legacy_hot_post[]=$rs_import_post['article_new']['postID'];
                        
                        //查询评论
                        $rs_reply=$obj_blog_tool->obj_blog_legacy_202005_post->getAll('*',['limit'=>100,'basecode'=>$v['basecode'],'visible,!='=>0,'treelevel'=>1,'order'=>['postid'=>'ASC']],"blog_{$year}{$month_tbn}_post");
                        if(!empty($rs_reply)){
                            $postID_legacy_hot_post_reply=[];
                            foreach($rs_reply as $vv){
                                $vv['date']=substr($v['dateline'],0,7); //=========================主贴时间
                                $rs_import_post_reply=$obj_blog_tool->import_post($vv);
                                $postID_legacy_hot_post_reply[]=$rs_import_post_reply['article_new']['postID'];
                                echo $vv['postid']."_".$month_tbn."_reply \n";
                            }
                            
                            //同步ES索引回复
                            $obj_article_noindex->fetch_and_insert($postID_legacy_hot_post_reply);
                        }
                    }
                }
                
                //同步ES索引主贴
                $obj_article_noindex->fetch_and_insert($postID_legacy_hot_post);
            }
        }
    }
}

$year = empty($argv[1])?"2021":$argv[1];
$month = empty($argv[2])?"":$argv[2];
$obj = new import_blog_data();
$obj->start($year,$month);


































































