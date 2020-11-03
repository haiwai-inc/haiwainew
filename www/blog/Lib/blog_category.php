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
		$categories = $this->getList("*", ["visible"=>1, "name, Like"=>$keyword]);
		
		if(empty($categories)) return $categories;
		return $this->fetch_userinfo($categories);
	}

	/**
	 * Fill in userinfo for categories
	 * @param array $categories
	 * @return array $categories_with_user
	 */
	private function fetch_userinfo($categories){
		$rs = [];
		if(empty($categories)) return [];
		$user_ids = [];
		$blogger_ids = [];
		foreach($categories as $category){
			$user_ids[] = $category['userID'];
			$blogger_ids[] = $category['bloggerID'];
		}
		$user_obj = load("account_user");
		$blogger_obj = load("blog_blogger");
		$user_map = $user_obj -> get_id_user_map($user_ids);
		$blogger_map = $blogger_obj -> get_id_blogger_map($blogger_ids);
		
		foreach($categories as $category){
			if(empty($user_map[$category['userID']]) || empty($blogger_map[$category['bloggerID']])) continue;
			$category['user'] = $user_map[$category['userID']];
			$category['blogger'] = $user_map[$category['bloggerID']];
			$rs[] = $category;
		}
		return $rs;
	}
}