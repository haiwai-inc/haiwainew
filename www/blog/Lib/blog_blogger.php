<?php
class blog_blogger extends Model{
	protected $tableName="blogger";
	protected $dbinfo=array("config"=>"blog","type"=>"MySQL");

	/**
	 * Search blogger with keyword
	 * @param string $keyword
	 * @return $for_article
	 */
	public function search_by_name($keyword, $for_article = false){
		//TODO lastid filter
		$where = ['name, LIKE' => "%$keyword%"];
		if(empty($for_article)){
			$blogger_rs  = $this->getList(["id", "name", "userID", "background", "description", "count_follower", "count_read", "count_article", "count_buzz", "count_comment"], $where);
		}
		else{
			$where['limit'] = 3;
			$blogger_rs  = $this->getAll(["id", "name", "userID", "background", "description", "count_follower", "count_read", "count_article", "count_buzz", "count_comment"], $where);
		}
		$user_obj = load("account_user");
		$bloggers = $user_obj->get_basic_userinfo($blogger_rs, "userID");
		return $bloggers;
	}

	// public function fetch_userinfo($blogger_rs){
	// 	if(empty($blogger_rs)) return [];
	// 	$user_ids = [];
	// 	foreach($blogger_rs as $blogger){
	// 		$user_ids[] = $blogger["userID"];
	// 	}

	// 	$user_obj = load("account_user");
	// 	$users_map = $user_obj -> get_id_user_map($user_ids);

	// 	$bloggers = [];
	// 	foreach($blogger_rs as $blogger){
	// 		if(empty($users_map[$blogger['userID']])) continue;
	// 		$blogger['user'] = $users_map[$blogger['userID']];
	// 		$bloggers[] = $blogger;
	// 	}
	// 	return $bloggers;
	// }

	/**
	 * Get a id=>blogger map
	 * @param array $ids | List of ids
	 * @return array $rs | id blogger map
	 */
	public function get_id_blogger_map($ids){
		$bloggers = $this->get_bloggers_by_ids($ids);
		
		$rs = [];
		if(!empty($bloggers)){
			foreach($bloggers as $v){
			    $rs[$v['id']] = $v;
			}
		}
		return $rs;
	}

	/**
	 * Get a list of blogger objects from db with list of ids
	 * @param array $ids | List of ids
	 * @param array $users | List of bloggers
	 */
	public function get_bloggers_by_ids($ids){
		if(empty($ids)) return [];
		if(!is_array($ids)) $ids = [$ids];
		$bloggers = $this->getAll(["id", "name", "userID", "background", "description", "count_follower", "count_read", "count_article", "count_buzz", "count_comment"], ["OR"=>["id"=>$ids]]);
		return $bloggers;
	}

	function get_basic_bloggerinfo($rs,$hashID='id'){
	    if(!empty($rs)){
	        foreach($rs as $v){
	            $tmp_rs_id[]=$v[$hashID];
	        }
			$hash_blog_blogger = $this->get_id_blogger_map($tmp_rs_id);
	        foreach($rs as $k=>$v){
	            $item=$hash_blog_blogger[$v[$hashID]];
	            $item['o_avatar']=$item['background'];
	            $item['background']=str_replace("{$v['id']}_background","{$item['id']}_background_750_420",$item['background']);
	            $rs[$k]['bloggerinfo_'.$hashID]=$item;
	        }
	    }
	    
	    return $rs;
	}
}
?>











