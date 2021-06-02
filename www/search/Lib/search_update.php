<?php
class search_update{
    public $action;
    public $target;
    
    function __construct($action = "all", $target = "none"){
        $this->action=$action;
        $this->target=$target;
        $this->obj_article_indexing=load("article_indexing");
        $this->obj_search_article=load("search_article_index");
        $this->boj_search_article_noindex=load("search_article_noindex");
    }
    
    function start(){
        if($this->action=="all"){
            //更新全部
            $lastid=0;
        }else if($this->action=="single"){
            $this->addOneToNoIndex($this->target);
            return;
        }
        else{
            //更新10分钟以前
            $edit_date=times::getTime()-600;
            $rs_lastid=$this->obj_article_indexing->getOne(['postID'],['edit_date,>'=>$edit_date,'order'=>['postID'=>"ASC"]]);
            $lastid=empty($rs_lastid)?2147483647:$rs_lastid['postID']-1;
        }
        
        
        $count=0;
        while($rs_article_indexing=$this->obj_article_indexing->getAll("*",['order'=>['postID'=>'ASC'],'limit'=>100,'postID,>'=>$lastid]) ){
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


    function addOneToNoIndex($target){
        $rs_article_indexing=$this->obj_article_indexing->getAll('*', ['postID'=>$target]);
        foreach($rs_article_indexing as $k=>$v){
            $lastid=$v['postID'];
            
            //补全帖子所有分表信息
            $rs_article_indexing=$this->obj_article_indexing->get_basic_articleinfo($rs_article_indexing,$k,$v);
            
            echo $lastid."\n";
        }
        $this->boj_search_article_noindex->add_new_articles($rs_article_indexing);
    }
}