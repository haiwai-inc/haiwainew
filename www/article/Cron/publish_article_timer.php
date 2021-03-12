<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/article/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class publish_article_timer{
    function start(){
        $obj_article_draft=load("article_draft");
        $obj_article_tag=load("article_tag");
        $obj_article_post=load("article_post");
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_indexing=load("article_indexing");
        
        $rs_article_draft=$obj_article_draft->getAll("*",['is_timer'=>1,'order'=>['id'=>"DESC"],'limit'=>200]);
        if(!empty($rs_article_draft)){
            foreach($rs_article_draft as $v){
                //生成文章数据
                $tagname=$obj_article_tag->get_article_tag_name($v);
                $article_data=[
                    "userID"=>$v['userID'],
                    'title'=>$v['title'],
                    'msgbody'=>$v['msgbody'],
                    'tagname'=>$tagname,
                    "typeID"=>$v['typeID'],
                    "draftID"=>$v['id'],
                ];
                $module_data=[
                    "add"=>true,
                    "bloggerID"=>$v['bloggerID'],
                    "categoryID"=>$v['categoryID'],
                ];
                
                //插入文章
                $article_data['postID']=$module_data['postID']=$obj_article_post->get_id();
                $time=times::getTime();
                $fields_indexing=[
                    "postID"=>$article_data['postID'],
                    "typeID"=>$article_data['typeID'],
                    "basecode"=>$article_data['postID'],
                    "userID"=>$article_data['userID'],
                    "treelevel"=>0,
                    "create_date"=>$time,
                    "edit_date"=>$time,
                ];
                $obj_article_indexing->insert($fields_indexing);
                $post_tbn=substr('0'.$article_data['userID'],-1);
                $fields_post=[
                    "id"=>$article_data['postID'],
                    "title"=>$article_data['title'],
                    "msgbody"=>$article_data['msgbody'],
                ];
                $obj_article_post->insert($fields_post,"post_{$post_tbn}");
                
                //转文章为博客类型
                if($article_data['typeID']==1){
                    $obj_blog_blogger=load("blog_blogger");
                    $obj_blog_blogger->to_blog_article($article_data['postID'],$module_data);
                }
                
                //同步ES索引
                $obj_article_noindex->fetch_and_insert([$article_data['postID']]);
                
                //删除草稿
                if(!empty($article_data['draftID'])){
                    $obj_article_draft=load("article_draft");
                    $obj_article_draft->remove(['id'=>$article_data['draftID']]);
                }
            }
        }
    }
}

$obj = new publish_article_timer();
$obj->start();


































































