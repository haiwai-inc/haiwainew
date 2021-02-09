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
}
?>















































