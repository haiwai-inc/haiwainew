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

	//获取博客基本信息
	function get_basic_bloggerinfo($rs,$hashID='id'){
	    if(!empty($rs)){
	        foreach($rs as $v){
	            $tmp_rs_id[]=$v[$hashID];
	        }
			
	        $rs_blog_bloggers = $this->getAll(["id", "name", "userID", "background", "description", "count_follower", "count_read", "count_article", "count_buzz", "count_comment"], ["OR"=>["id"=>$tmp_rs_id]]);       
	        if(!empty($rs_blog_bloggers)){
	            foreach($rs_blog_bloggers as $v){
	                $hash_blog_blogger[$v['id']]=$v;
	            }
	        }
			
	        foreach($rs as $k=>$v){
				$item=$hash_blog_blogger[$v[$hashID]];
	            $item['o_avatar']=$item['background'];
	            $item['background']=str_replace("{$v[$hashID]}_background","{$item['id']}_background_750_420",$item['background']);
	            $rs[$k]['bloggerinfo_'.$hashID]=$item;
	        }
	    }
	    
	    return $rs;
	}
}
?>











