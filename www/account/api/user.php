<?php

/**
 * @author sida
 * [
 *  'data'=>[],
 *  'error'=>[
 *      ['name'=>'user','msg'=>''],
 *      ['name'=>'password','msg'=>'']
 *  ],
 *  'status'=>true
 *  ]
 */
class user extends Api {

    public $space = true;
    
    public function __construct() {
        parent::__construct();
        $this->sess = true;
    }
    
    /**
     * 用户设置页
     * 用户 修改
     */
    public function user_update(){
        
    }

    /**
     * 很多页面
     * 黑名单 添加
     * @param integer $blockID|屏蔽人的ID
     */
    public function blacklist_add($blockID){
        $obj_account_user=load("account_user");
        $check_account_user=$obj_account_user->getOne(['id'],['status'=>1,'id'=>$blockID]);
        if(empty($check_account_user)){
            $this->error="不是有效的屏蔽用户";
            $this->status=false;
            return false;
        }else{
            $obj_account_blacklist=load("account_blacklist");
            $check_account_blacklist=$obj_account_blacklist->getOne(['id'],['userID'=>$_SESSION['id'],'blockID'=>$blockID]);
            if(!empty($check_account_blacklist)){
                $this->error="此用户已经被屏蔽";
                $this->status=false;
                return false;
            }else{
                $obj_account_blacklist->insert(['userID'=>$_SESSION['id'],'blockID'=>$blockID]);
            }
        }
        
        return "已屏蔽此用户";
    }
    
    /**
     * 很多页面
     * 黑名单 删除
     * @param integer $blockID|屏蔽人的ID
     */
    public function blacklist_delete($blockID){
        $obj_account_user=load("account_user");
        $check_account_user=$obj_account_user->getOne(['id'],['status'=>1,'id'=>$blockID]);
        if(empty($check_account_user)){
            $this->error="不是有效的屏蔽用户";
            $this->status=false;
            return false;
        }else{
            $obj_account_blacklist=load("account_blacklist");
            $check_account_blacklist=$obj_account_blacklist->getOne(['id'],['userID'=>$_SESSION['id'],'blockID'=>$blockID]);
            if(empty($check_account_blacklist)){
                $this->error="此用户未被列入黑名单";
                $this->status=false;
                return false;
            }else{
                $obj_account_blacklist->remove(['userID'=>$_SESSION['id'],'blockID'=>$blockID]);
            }
        }
        
        return "已取消屏蔽此用户";
    }
    
    /**
     * 个人设置黑名单页
     * 黑名单 列表
     * @param integer $last_blacklistID|最后黑名单的ID
     */
    public function blacklist_list($last_blacklistID=0){
        $obj_account_blacklist=load("account_blacklist");
        $fields=[
            'userID'=>$_SESSION['id']
        ];
        if(!empty($last_blacklistID)){
            $fields['id,<']=$last_blacklistID;
        }
        $rs_account_blacklist=$obj_account_blacklist->getAll("*",$fields);
        
        //加入用户信息
        if(!empty($rs_account_blacklist)){
            $obj_account_user=load("account_user");
            $rs_account_blacklist=$obj_account_user->get_basic_userinfo($rs_account_blacklist,'blockID');
        }
        
        return $rs_account_blacklist;
    }
    
    /**
     * 文章详情页
     * 书签 添加
     * @param integer $postID|收藏文章的postID
     */
    public function bookmark_add($postID){
        $obj_article_indexing=load("article_indexing");
        $check_article_indexing=$obj_article_indexing->getOne(['id'],['treelevel'=>0,'visible'=>1,'postID'=>$postID]);
        if(empty($check_article_indexing)){
            $this->error="此文章不存在";
            $this->status=false;
            return false;
        }else{
            $obj_account_bookmark=load("account_bookmark");
            $check_account_book=$obj_account_bookmark->getOne("*",['userID'=>$_SESSION['id'],'postID'=>$postID]);
            if(!empty($check_account_book)){
                $this->error="此文章已经加入到了书签";
                $this->status=false;
                return false;
            }else{
                $obj_account_bookmark->insert(['userID'=>$_SESSION['id'],'postID'=>$postID]);
            }
        }
        
        return "已经加入此文章到书签";
    }
    
    /**
     * 文章详情页
     * 书签 删除
     * @param integer $postID|收藏文章的postID
     */
    public function bookmark_delete($postID){
        $obj_account_bookmark=load("account_bookmark");
        $check_account_book=$obj_account_bookmark->getOne("*",['userID'=>$_SESSION['id'],'postID'=>$postID]);
        if(empty($check_account_book)){
            $this->error="书签里没有此文章";
            $this->status=false;
            return false;
        }else{
            $obj_account_bookmark->remove(['userID'=>$_SESSION['id'],'postID'=>$postID]);
        }
        
        return "已在书签删除此文章";
    }
    
    /**
     * 收藏页
     * 书签 列表
     * @param integer $last_bookmarkID|最后书签的ID
     */
    public function bookmark_list($last_bookmarkID=0){
        $obj_account_bookmark=load("account_bookmark");
        $obj_article_post=load("article_post");
        
        $fields=[
            'userID'=>$_SESSION['id']
        ];
        if(!empty($last_blockID)){
            $fields['id,<']=$last_bookmarkID;
        }
        $rs_account_bookmark=$obj_account_bookmark->getAll("*",$fields);
        $rs_account_bookmark=$obj_article_post->get_basic_userinfo($rs_account_bookmark,"postID");
        
        return $rs_account_bookmark;
    }
    
    /**
     * 很多页面
     * 悄悄话 发送
     * @param integer $touserID | 被发送人touserID
     * @param integer $msgbody | 被发送人msgbody
     */
    public function qqh_add($touserID,$msgbody){
        $obj_account_qqh=load("account_qqh");
        $obj_account_qqh_post=load("account_qqh_post");
        
        //查看是否开启对话框
        $check_account_qqh=$obj_account_qqh->getOne("*",['SQL'=>"(userID={$touserID} and touserID={$_SESSION['id']}) OR (userID={$_SESSION['id']} and touserID={$touserID})"]);
        if(empty($check_account_qqh)){
            $check_account_qqh=['userID'=>$_SESSION['id'],'touserID'=>$touserID,'new_message'=>0,'to_new_message'=>0];
            $check_account_qqh['id']=$obj_account_qqh->insert(['userID'=>$_SESSION['id'],'touserID'=>$touserID]);
        }else{
            $update_account_qqh_fields=[
                'last_message_dateline'=>times::getTime()
            ];
            
            //自己发起的对话
            if($check_account_qqh['userID']==$_SESSION['id']){
                $update_account_qqh_fields['to_new_message']=$check_account_qqh['to_new_message']+1;
            }
            //别人发起的对话
            else{
                $update_account_qqh_fields['new_message']=$check_account_qqh['new_message']+1;
            }
            
            $obj_account_qqh->update($update_account_qqh_fields,['id'=>$check_account_qqh['id']]);
        }
        
        $obj_account_qqh_post->insert(['userID'=>$_SESSION['id'],'touserID'=>$touserID,'qqhID'=>$check_account_qqh['id'],'msgbody'=>$msgbody,'dateline'=>times::getTime()]);
        return "悄悄话已发送";
    }
    
    /**
     * 悄悄话页
     * 悄悄话 列表
     */
    public function qqh_list(){
        $obj_account_qqh=load("account_qqh");
        $obj_account_user=load("account_user");
        
        $rs_account_qqh=$obj_account_qqh->getAll("*",['SQL'=>"userID={$_SESSION['id']} OR touserID={$_SESSION['id']}",'order'=>["last_message_dateline"=>'DESC']]);
        
        //查询联系人
        $rs_account_qqh=$obj_account_user->get_basic_userinfo($rs_account_qqh,"userID");
        $rs_account_qqh=$obj_account_user->get_basic_userinfo($rs_account_qqh,"touserID");
        
        return $rs_account_qqh;
    }
    
    /**
     * 悄悄话详情页
     * 悄悄话 详情
     * @param integer $qqhID | 悄悄话对话框ID
     */
    public function qqh_view($qqhID){
        $obj_account_qqh=load("account_qqh");
        $obj_account_user=load("account_user");
        $obj_account_qqh_post=load("account_qqh_post");
        
        $rs_account_qqh_post=$obj_account_qqh_post->getAll("*",['qqhID'=>$qqhID,'visible'=>1,'order'=>['id'=>'DESC']]);
        if(empty($rs_account_qqh_post)){
            $this->error="此对话不存在";
            $this->status=false;
            return false;
        }
        
        //查询联系人
        $rs_account_qqh_post=$obj_account_user->get_basic_userinfo($rs_account_qqh_post,"userID");
        
        return $rs_account_qqh_post;
    }
    
    /**
     * 用户
     * 赞 添加
     * 
     */
    public function buzz_add(){
        
    }
    
    /**
     * 用户
     * 赞 取消
     */
    public function buzz_delete(){
        
    }
    
    /**
     * "很多"页面
     * 关注 添加
     */
    public function follower_add(){
        
    }
    
    /**
     * "很多"页面
     * 关注 取消
     */
    public function follower_delete(){
        
    }
    
    /**
     * 小铃铛页
     * 文章 列表 赞 “我点的”
     */
    public function article_list_buzz_mine(){
        
    }
    
    /**
     * 小铃铛页
     * 评论 列表 最新 “我发的”
     */
    public function comment_list_recent_mine(){
        
    }
}





































