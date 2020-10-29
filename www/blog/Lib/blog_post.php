<?php
class blog_post extends Model{
	protected $tableName="post";
    protected $dbinfo=array("config"=>"article","type"=>"MySQL");
    
    protected $subTbLen=1;
}