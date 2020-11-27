<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/search/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

class update_search{
    function __construct(){
        $this->obj_article_indexing=load("article_indexing");
        $this->obj_search_article=load("search_article_index");
        $this->boj_search_article_noindex=load("search_article_noindex");
    }
    
    function start(){
        $lastid=0;
        $count=0;
        while($rs_article_indexing=$this->obj_article_indexing->getAll("*",['order'=>['postID'=>'ASC'],'limit'=>200,'postID,>'=>$lastid,'visible'=>1]) ){
            foreach($rs_article_indexing as $k=>$v){
                $lastid=$v['postID'];
                
                //补全帖子所有分表信息
                $rs_article_indexing=$this->obj_article_indexing->get_basic_articleinfo($rs_article_indexing,$k,$v);
                
                $count++;
                echo $lastid."\n";
            }
            
            //ES 导入文章总数
            $this->obj_search_article->add_new_articles($rs_article_indexing);
            $this->boj_search_article_noindex->add_new_articles($rs_article_indexing);
        }
        
        echo "totally {$count}\n";
    }
}

$obj=new update_search();
$obj->start();



























