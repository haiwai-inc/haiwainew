<?php
class article_post extends Model{
	protected $tableName="post_0";
	protected $dbinfo=array("config"=>"article","type"=>"MySQL");
	
	function get_id(){
	    $obj_article_post_increment=load("article_post_increment");
	    $obj_article_post_increment->conn->query("INSERT INTO `post_increment` () VALUES()");
	    return mysqli_insert_id($obj_article_post_increment->conn->conn);
	}
}
?>