<?php
class account_user extends Model{
	protected $tableName="user";
	protected $dbinfo=array("config"=>"account","type"=>"MySQL");
	
	//在包含用户ID的数组里，补全用户基本信息
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