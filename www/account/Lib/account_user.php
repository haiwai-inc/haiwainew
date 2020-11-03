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
		foreach($users as $user){
			$rs[$user['id']] = $user;
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
	
	function get_basic_userinfo($rs,$hashID='id'){
	    if(!empty($rs)){
	        foreach($rs as $v){
	            $tmp_rs_id[]=$v[$hashID];
	        }
	        $rs_account_user=$this->getAll(['id','username','avatar'],['OR'=>['id'=>$tmp_rs_id]]);
	        if(!empty($rs_account_user)){
	            foreach($rs_account_user as $v){
	                $hash_account_user[$v['id']]=$v;
	            }
	        }
	        foreach($rs as $k=>$v){
	            $rs[$k]['basic_userinfo']=$hash_account_user[$v[$hashID]];
	        }
	    }
	    
	    return $rs;
	}
}
?>