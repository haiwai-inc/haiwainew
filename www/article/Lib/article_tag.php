<?php
class article_tag extends Model{
	protected $tableName="tag";
	protected $dbinfo=array("config"=>"article","type"=>"MySQL");

	public function search_by_name($keyword){
		return $this->getAll("*", ["name, Like"=>"%".$keyword."%", "limit"=>10]);
	}
}
?>