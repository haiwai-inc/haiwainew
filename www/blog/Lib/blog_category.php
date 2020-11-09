<?php
class blog_category extends Model{
	protected $tableName="category";
	protected $dbinfo=array("config"=>"blog","type"=>"MySQL");

	function category(){
		
	}

	/**
	 * Search by category name
	 * @param string $keyword
	 * @return array $categories
	 */
	public function search_by_name($keyword){
		$categories = $this->getList("*", ["visible"=>1, "name,like"=>"%".$keyword."%"]);
		if(empty($categories)) return $categories;
		// return $this->fetch_userinfo($categories);
		$user_obj = load("account_user");
		$blogger_obj = load("blog_blogger");
		$categories = $user_obj->get_basic_userinfo($categories, "userID");
		$categories = $blogger_obj->get_basic_bloggerinfo($categories, "bloggerID");
		return $categories;
	}

}