<?php
class article_draft extends Model{
	protected $tableName="draft";
	protected $dbinfo=array("config"=>"article","type"=>"MySQL");
	
	function format_draft($rs_article_draft){
	    if(!empty($rs_article_draft)){
	        foreach($rs_article_draft as $k=>$v){
	            $rs_article_draft[$k]['postInfo_postID']['id']=$v['id'];
	            $rs_article_draft[$k]['postInfo_postID']['title']=$v['id'];
	            $rs_article_draft[$k]['postInfo_postID']['msgbody']=$v['id'];
	            $rs_article_draft[$k]['postInfo_postID']['tags']=empty($v['tagID'])?[]:explode(",",$v['tagID']);
	        }
	    }
	    
	    return $rs_article_draft;
	}
	
	function remove_article_indexing($rs_article_indexing){
	    if(!empty($rs_article_indexing)){
	        foreach($rs_article_indexing as $v){
	            $tmp_article_indexing[]=$v['postID'];
	        }
	        $check_article_draft=$this->getAll(['postID'],['OR'=>['postID'=>$tmp_article_indexing]]);
	        if(!empty($check_article_draft)){
	            foreach($check_article_draft as $v){
	                $tmp_article_draft[]=$v['postID'];
	            }
	            foreach($rs_article_indexing as $k=>$v){
	                if(in_array($v['postID'],$tmp_article_draft)){
	                    unset($rs_article_indexing[$k]);
	                }
	            }
	        }
	    }
	    
	    return $rs_article_indexing;
	}
}
?>















































