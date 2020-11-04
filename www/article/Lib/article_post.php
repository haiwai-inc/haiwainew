<?php
class article_post extends Model{
	protected $tableName="post_0";
	protected $dbinfo=array("config"=>"article","type"=>"MySQL");
	
	//生成postid
	function get_id(){
	    $obj_article_post_increment=load("article_post_increment");
	    $obj_article_post_increment->conn->query("INSERT INTO `post_increment` () VALUES()");
	    return mysqli_insert_id($obj_article_post_increment->conn->conn);
	}
	
	//在包含postID的帖子中，获取帖子基本信息
	function get_basic_userinfo($rs,$hashID="id"){
	    if(!empty($rs)){
	        foreach($rs as $v){
	            $tmp_rs_id[]=$v[$hashID];
	        }
	        $obj_search_article_noindex=load("search_article_noindex");
	        $rs_search_article_noindex=$obj_search_article_noindex->get_by_postIDs($tmp_rs_id);
	        if(!empty($rs_search_article_noindex)){
	            foreach($rs_search_article_noindex as $v){
	                $hash_search_article_noindex[$v['postID']]=$v;
	            }
	            foreach($rs as $k=>$v){
	                $rs[$k]['basic_postinfo']=empty($hash_search_article_noindex[$v[$hashID]])?[]:$hash_search_article_noindex[$v[$hashID]];
	            }
	        }
	    }
	    
	    return $rs;
	}
}
?>















































