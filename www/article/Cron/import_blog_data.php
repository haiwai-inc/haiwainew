<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/article/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

//汪老师https://blog.wenxuecity.com/myoverview/69027/
//润涛阎https://blog.wenxuecity.com/myoverview/1666/

class import_blog_data{
    function start($year){
        $obj_blog_tool=load("blog_tool");
        $obj_blog_tool->load_all_db();
        $lastid=0;
        
        $obj_article_noindex=load("search_article_noindex");
        
        for($i=1;$i<=12;$i++){
            $month=substr('00'.$i,-2);
            $lastid=0;
            $rs_blog_legacy_202005_post=[];
            while( $rs_blog_legacy_202005_post = $obj_blog_tool->obj_blog_legacy_202005_post->getAll("*",['treelevel'=>0,'order'=>['postid'=>'ASC'],'limit'=>20,'postid,>'=>$lastid,'visible,!='=>0],"blog_{$year}{$month}_post") ){
                $postID_legacy_hot_post=[];
                foreach($rs_blog_legacy_202005_post as $k=>$v){
                    $lastid=$v['postid'];
                    echo $lastid."_".$month."\n";
                    
                    //汪老师 userID=942857 blogid=69027 basecode=211   7324
                    if($v['userid']==659595){
                        //主贴
                        $v['date']=substr($v['dateline'],0,7); //=========================主贴时间
                        $rs_import_post=$obj_blog_tool->import_post($v);
                        $postID_legacy_hot_post[]=$rs_import_post['article_new']['postID'];
                        
                        //查询评论
                        $rs_reply=$obj_blog_tool->obj_blog_legacy_202005_post->getAll('*',['limit'=>100,'basecode'=>$v['basecode'],'visible,!='=>0,'treelevel'=>1,'order'=>['postid'=>'ASC']],"blog_{$year}{$month}_post");
                        if(!empty($rs_reply)){
                            $postID_legacy_hot_post_reply=[];
                            foreach($rs_reply as $vv){
                                $vv['date']=substr($v['dateline'],0,7); //=========================主贴时间
                                $rs_import_post_reply=$obj_blog_tool->import_post($vv);
                                $postID_legacy_hot_post_reply[]=$rs_import_post_reply['article_new']['postID'];
                                echo $vv['postid']."_".$month."_reply \n";
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
$obj = new import_blog_data();
$obj->start($year);


































































