<?php
class account_user extends Model{
	protected $tableName="user";
	protected $dbinfo=array("config"=>"account","type"=>"MySQL");

	/**
	 * Get a id=>user map
	 * @param array $ids | List of ids
	 * @return array $rs | id user map
	 */
	public function get_id_user_map($ids){
		$users = $this->get_users_by_ids($ids);
		$rs = [];
		if(!empty($users)){
			foreach($users as $user){
				$rs[$user['id']] = $user;
			}
		}
		return $rs;
	}

	/**
	 * Get a list of user objects from db with list of ids
	 * @param array $ids | List of ids
	 * @param array $users | List of users
	 */
	public function get_users_by_ids($ids){
		if(empty($ids)) return [];
		if(!is_array($ids)) $ids = [$ids];
		$users = $this->getAll(["id", "username", "avatar", "description"], ["OR"=>["id"=>$ids]]);
		return $users;
	}

	public function search_by_name($keyword){
		$user_rs  = $this->getList(["id", "username", "avatar", "description"], ['username, LIKE' => "%$keyword%"]);
		return $user_rs;
	}
	
	//在包含用户ID的数组里，补全用户基本信息
	function get_basic_userinfo($rs,$hashID='id'){
	    if(!empty($rs)){
	        //名博
	        $obj_memcache = func_initMemcached('cache01');
	        $rs_memcache = $obj_memcache->get("blog_hot_blogger");
	        $userID_rs_memcache=[];
	        if(!empty($rs_memcache)){
	            foreach($rs_memcache as $v){
	                $userID_rs_memcache[]=$v['userID'];
	            }
	        }
	        
	        //如果登录 查询关注人
	        $followerID_accout_follower=[];
	        if(!empty($_SESSION['id'])){
	            $obj_accout_follower=load("account_follower");
	            $rs_accout_follower=$obj_accout_follower->getAll("*",['userID'=>$_SESSION['id'],'limit'=>200]);
	            if(!empty($rs_accout_follower)){
	                foreach($rs_accout_follower as $v){
	                    $followerID_accout_follower[]=$v['followerID'];
	                }
	            }
	        }
	        
	        foreach($rs as $v){
	            $tmp_rs_id[]=$v[$hashID];
	        }
			$hash_account_user = $this->get_id_user_map($tmp_rs_id);
	        foreach($rs as $k=>$v){
	            $item=$hash_account_user[$v[$hashID]];
	            
	            //首字母
	            $item['first_letter']=strings::subString($item['username'],1);
	            
	            //是否名博
	            $item['is_hot_blogger']=(in_array($item['id'],$userID_rs_memcache))?1:0;
	            
	            //是否关注
	            $item['is_follower']=(in_array($item['id'],$followerID_accout_follower))?1:0;
	            
	            $rs[$k]["userinfo_{$hashID}"]=$item;
	        }
	    }
	    
	    return $rs;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
?>

















