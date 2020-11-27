<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/search/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class update_search{
    function __construct(){
        $this->obj_article_indexing=load("article_indexing");
        $this->obj_article_tag=load("article_tag");
        $this->obj_article_post=load("article_post");
        $this->obj_article_post_tag=load("article_post_tag");
        $this->obj_article_post_buzz=load("article_post_buzz");
        
        $this->obj_search_article=load("search_article_index");
        $this->boj_search_article_noindex=load("search_article_noindex");
    }
    
    function start(){
        $lastid=0;
        while($rs_article_indexing=$this->obj_article_indexing->getAll("*",['order'=>['postID'=>'ASC'],'limit'=>200,'postID,>'=>$lastid,'visible'=>1]) ){
            foreach($rs_article_indexing as $k=>$v){
                $lastid=$v['postID'];
                
                //添加标题内容图片
                $post_tbn=substr('0'.$v['userID'],-1);
                $rs_article_post=$this->obj_article_post->getOne("*",['id'=>$v['postID']],"post_".$post_tbn);
                if(!empty($rs_article_post)){
                    foreach($rs_article_post as $kk=>$vv){
                        $rs_article_indexing[$k][$kk]=$vv;
                    }
                    $rs_article_indexing[$k]["msgbody_origin"]=$rs_article_post['msgbody'];
                }
                
                //添加点赞
                $post_buzz_tbn=substr('0'.$v['postID'],-1);
                $rs_article_post_buzz=$this->obj_article_post_buzz->getAll("*",['id'=>$v['postID']],"post_buzz_".$post_buzz_tbn);
                $rs_article_indexing[$k]['buzz']=[];
                if(!empty($rs_article_post_buzz)){
                    foreach($rs_article_post_buzz as $kk=>$vv){
                        $rs_article_indexing[$k]['buzz'][]=$vv['userID'];
                    }
                }
                
                //添加标签
                $post_tag_tbn=substr('0'.$v['postID'],-1);
                $rs_article_post_tag=$this->obj_article_post_tag->getAll("*",['postID'=>$v['postID']],"post_tag_".$post_tag_tbn);
                $rs_article_indexing[$k]['tags']=[];
                if(!empty($rs_article_post_tag)){
                    foreach($rs_article_post_tag as $kk=>$vv){
                        $rs_article_indexing[$k]['tags'][]=$vv['tagID'];
                    }
                }
                
                echo $lastid."\n";
            }
            
            //ES 导入文章总数
            $indexed_article_number+=$this->obj_search_article->add_new_articles($rs_article_indexing);
            $not_indexed_article_number+=$this->boj_search_article_noindex->add_new_articles($rs_article_indexing);
            echo("<br>Total indexed article: ".$indexed_article_number."\n");
            echo("<br>Total not indexed article pool: ".$not_indexed_article_number."\n");
        }
    }
}

$obj=new update_search();
$obj->start();



























