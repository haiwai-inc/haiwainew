<?php
class account_notification extends Model{
	protected $tableName="notification_0";
	protected $dbinfo=array("config"=>"account","type"=>"MySQL");
	
	//插入消息
    function notification_add($userID,$type,$typeID,$msgtype){
        $obj_account_user=load("account_user");
        $rs_account_user=$obj_account_user->getOne(['id','username'],['id'=>$_SESSION['id']]);
	    if($type=="follow"){
	        if($msgtype=="add"){
	            $msgbody=$rs_account_user['username']." 关注了您";
	        }
	        if($msgtype=="delete"){
	            $msgbody=$rs_account_user['username']." 取消了对您的关注";
	        }
	    }
	    if($type=="buzz"){
	        $obj_article_post=load("article_post");
	        $rs_article_post=$obj_article_post->getOne(['title'],['id'=>$typeID],"post_".substr('0'.$userID,-1));
	        if($msgtype=="add"){
	            $msgbody=$rs_account_user['username']." 赞了您的文章: ". $rs_article_post['title'];
	        }
	    }
	    if($type=="reply"){
	        //查询主贴ID
	        $obj_article_indexing=load("article_indexing");
	        $rs_article_indexing=$obj_article_indexing->getOne(['postID','basecode','treelevel'],['postID'=>$typeID]);
	        if($rs_article_indexing['treelevel']==2){
	            $rs_article_indexing=$obj_article_indexing->getOne(['postID','basecode','treelevel'],['postID'=>$rs_article_indexing['basecode']]);
	        }
	        $id=$rs_article_indexing['basecode'];
	        
	        $obj_article_post=load("article_post");
	        $rs_article_post=$obj_article_post->getOne(['title'],['id'=>$id],"post_".substr('0'.$userID,-1));
	        if($msgtype=="add"){
	            $msgbody=$rs_account_user['username']." 评论了您的文章: ". $rs_article_post['title'];
	        }
	    }
	    if($type=="qqh"){
	        if($msgtype=="add"){
	            $msgbody=$rs_account_user['username']." 发送了您一条悄悄话";
	        }
	    }
	    
	    $where=[
	        "userID"=>$userID,
	        "from_userID"=>$rs_account_user['id'],
	        "type"=>$type,
	        "typeID"=>$typeID,
	        "msgbody"=>$msgbody,
	        "is_read"=>0,
	        "create_date"=>times::gettime()
	    ];
	    $this->insert($where,"notification_".substr('0'.$userID,-1));
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






















