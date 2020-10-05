<?php
class article_post_tag extends Model{
	protected $tableName="post_tag_0";
	protected $dbinfo=array("config"=>"article","type"=>"MySQL");
	
	function get_id(){
	    $obj_article_post_tag_increment=load("article_post_tag_increment");
	    $obj_article_post_tag_increment->conn->query("INSERT INTO `post_tag_increment` () VALUES()");
	    return mysqli_insert_id($obj_article_post_tag_increment->conn->conn);
	}
}
?>