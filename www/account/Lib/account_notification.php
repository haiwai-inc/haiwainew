<?php
class account_notification extends Model{
	protected $tableName="notification_0";
	protected $dbinfo=array("config"=>"account","type"=>"MySQL");
	
	//插入消息
    function notification_add($type,$typeID){
	    if(empty($_SESSION['id'])){
	       return;
	    }
	    $where=[
	        "userID"=>$_SESSION['id'],
	        "type"=>$type,
	        "typeID"=>$typeID,
	        "is_read"=>0
	    ];
	    $tbn=substr('0'.$_SESSION['id'],-1);
	    $this->insert($where,"notification_".$tbn);
	}
	
	//取消消息
	function notification_check($type,$typeID){
	    if(empty($_SESSION['id'])){
	        return;
	    }
	    $where=[
	        "userID"=>$_SESSION['id'],
	        "type"=>$type,
	        "typeID"=>$typeID,
	    ];
	    $tbn=substr('0'.$_SESSION['id'],-1);
	    $check_notification=$this->getOne(['id'],$where,"notification_".$tbn);
	    if(!empty($check_notification)){
	        $this->remove(['id'=>$check_notification['id'],'userID'=>$_SESSION['id']],"notification_".$tbn);
	    }
	}
}
?>






















