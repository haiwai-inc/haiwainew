<?php
class account_follow extends Model{
	protected $tableName="follow";
	protected $dbinfo=array("config"=>"account","type"=>"MySQL");
	
	public $host="cache02";
	public $sync_follower_update_key="sync_follower_update";
	
	function init(){
	    $this->obj_redis=func_initRedis($this->host);
	}
	
	//更新关注时间缓存
	function sync_follower_update($followingID){
	    $this->init();
	    
	    //查看是否关注
	    $check_account_follow=$this->getOne("*",['followerID'=>$_SESSION['id'],'followingID'=>$followingID]);
	    
	    //未关注
	    if(empty($check_account_follow)){
	        return;
	    }
	    
        //已经更新关注时间
        if($check_account_follow['follower_update']>=$check_account_follow['following_update']){
            return;
        }
        
        //添加关注更新ID
        $this->obj_redis->sAdd($this->sync_follower_update_key, $_SESSION['id']."_".$followingID); 
	}
}
?>





































