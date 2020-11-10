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
     * @param integer $lastID|最后黑名单的ID
     */
    public function blacklist_list($lastID=0){
        $obj_account_blacklist=load("account_blacklist");
        $fields=[
            'userID'=>$_SESSION['id']
        ];
        if(!empty($lastID)){
            $fields['id,<']=$lastID;
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
     * @param integer $lastID|最后书签的ID
     */
    public function bookmark_list($lastID=0){
        $obj_account_bookmark=load("account_bookmark");
        $obj_article_post=load("article_post");
        $obj_account_qqh=load("account_qqh");
        
        $fields=[
            'userID'=>$_SESSION['id']
        ];
        if(!empty($lastID)){
            $fields['id,<']=$lastID;
        }
        $rs_account_bookmark=$obj_account_bookmark->getAll("*",$fields);
        $rs_account_bookmark=$obj_article_post->get_basic_userinfo($rs_account_bookmark,"postID");
        
        return $rs_account_bookmark;
    }
    
    /**
     * 小铃铛页
     * 悄悄话 未读 计数
     */
    public function qqh_unread_count(){
        $obj_account_qqh=load("account_qqh");
        
        $rs_account_qqh_from=$obj_account_qqh->getOne("SELECT SUM(new_message) as total_new_message FROM qqh WHERE userID={$_SESSION['id']}");
        $rs_account_qqh_to=$obj_account_qqh->getOne("SELECT SUM(to_new_message) as total_to_new_message FROM qqh WHERE touserID={$_SESSION['id']}");
        
        $count=$rs_account_qqh_from['total_new_message']+$rs_account_qqh_to['total_to_new_message'];
        return $count;
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
        
        //不可以给自己发悄悄话
        if($_SESSION['id']==$touserID){
            $this->error="悄悄话的人就是自己";
            $this->status=false;
            return false;
        }
        
        //查看是否开启对话框
        $check_account_qqh=$obj_account_qqh->getOne("*",['SQL'=>"(userID={$touserID} and touserID={$_SESSION['id']}) OR (userID={$_SESSION['id']} and touserID={$touserID})"]);
        if(empty($check_account_qqh)){
            //未开启对话框直接插入
            $check_account_qqh['id']=$obj_account_qqh->insert(['userID'=>$_SESSION['id'],'to_new_message'=>1,'touserID'=>$touserID]);
        }else{
            //自己发起的对话
            if($check_account_qqh['userID']==$_SESSION['id']){
                $update_account_qqh_fields['to_new_message']=$check_account_qqh['to_new_message']+1;
            }
            //别人发起的对话
            else{
                $update_account_qqh_fields['new_message']=$check_account_qqh['new_message']+1;
            }
            
            //更新未读悄悄话计数
            $obj_account_qqh->update($update_account_qqh_fields,['id'=>$check_account_qqh['id']]);
        }
        
        $qqh_postID=$obj_account_qqh_post->insert(['userID'=>$_SESSION['id'],'touserID'=>$touserID,'qqhID'=>$check_account_qqh['id'],'msgbody'=>$msgbody,'dateline'=>times::getTime()]);
        
        //更新最后一条信息ID
        $obj_account_qqh->update(['last_messageID'=>$qqh_postID],['id'=>$check_account_qqh['id']]);
        
        return "悄悄话已发送";
    }
    
    /**
     * 悄悄话页
     * 悄悄话 列表
     * @param integer $lastID | 最后一个悄悄话信息对话框的message_dateline
     */
    public function qqh_list($lastID=0){
        $obj_account_qqh=load("account_qqh");
        $obj_account_qqh_post=load("account_qqh_post");
        $obj_account_user=load("account_user");
        
        $where_account_qqh_post=[
            'limit'=>20,
            'SQL'=>"userID={$_SESSION['id']} OR touserID={$_SESSION['id']}",
            'order'=>["last_message_dateline"=>'DESC']
        ];
        if(!empty($lastID)){
            $where_account_qqh_post['last_message_dateline,<']=$lastID;
        }
        $rs_account_qqh=$obj_account_qqh->getAll("*",$where_account_qqh_post);
        
        if(!empty($rs_account_qqh)){
            //查询联系人信息
            $rs_account_qqh=$obj_account_user->get_basic_userinfo($rs_account_qqh,"userID");
            $rs_account_qqh=$obj_account_user->get_basic_userinfo($rs_account_qqh,"touserID");
            
            //查询最后一条信息
            foreach($rs_account_qqh as $v){
                $last_messageID_account_qqh[]=$v['last_messageID'];
            }
            $rs_account_qqh_post=$obj_account_qqh_post->getAll("*",['OR'=>['id'=>$last_messageID_account_qqh]]);
            if(!empty($rs_account_qqh_post)){
                foreach($rs_account_qqh_post as $v){
                    $hash_account_qqh_post[$v['qqhID']]=$v;
                }
                foreach($rs_account_qqh as $k=>$v){
                    $rs_account_qqh[$k]['last_messageinfo']=$hash_account_qqh_post[$v['id']];
                }
            }
        }
        
        return $rs_account_qqh;
    }
    
    /**
     * 悄悄话详情页
     * 悄悄话 详情
     * @param integer $qqhID | 悄悄话对话框ID
     * @param integer $lastID | 最后一个悄悄话信息id
     */
    public function qqh_view($qqhID,$lastID=0){
        $obj_account_qqh=load("account_qqh");
        $obj_account_user=load("account_user");
        $obj_account_qqh_post=load("account_qqh_post");
        
        $where_account_qqh_post=[
            'limit'=>20,
            'qqhID'=>$qqhID,
            'visible'=>1,
            'order'=>['id'=>'DESC']
        ];
        if(!empty($lastID)){
            $where_account_qqh_post['id,<']=$lastID;
        }
        $rs_account_qqh_post=$obj_account_qqh_post->getAll("*",$where_account_qqh_post);
        
        if(empty($rs_account_qqh_post)){
            $this->error="此对话不存在";
            $this->status=false;
            return false;
        }
        
        //查询联系人信息
        $rs_account_qqh_post=$obj_account_user->get_basic_userinfo($rs_account_qqh_post,"userID");
        
        //清空悄悄话读数
        $rs_account_qqh=$obj_account_qqh->getOne("*",['id'=>$qqhID]);
        if($rs_account_qqh['userID']==$_SESSION['id']){
            $where_account_qqh=["new_message"=>0];
        }else{
            $where_account_qqh=["to_new_message"=>0];
        }
        $obj_account_qqh->update($where_account_qqh,['id'=>$qqhID]);
        
        return $rs_account_qqh_post;
    }
    
    /**
     * 用户
     * 赞 添加
     * @param integer $postID | 文章的postID
     */
    public function buzz_add($postID){
        $obj_article_indexing=load("article_indexing");
        $obj_article_post_buzz=load("article_post_buzz");
        
        $check_article_indexing=$obj_article_indexing->getOne(['id','postID','typeID','count_buzz','bloggerID'],['postID'=>$postID]);
        
        if(empty($check_article_indexing)){
            $this->error="此文章不存在";
            $this->status=false;
            return false;
        }
        
        $tbn=substr('0'.$postID,-1);
        $check_article_post_buzz=$obj_article_post_buzz->getOne(['id'],['userID'=>$_SESSION['id'],'postID'=>$postID],"post_buzz_{$tbn}");
        if(!empty($check_article_post_buzz)){
            $this->error="此文章您已经赞过了";
            $this->status=false;
            return false;
        }else{
            //添加人和帖子映射
            $obj_article_post_buzz->insert(['postID'=>$postID,'userID'=>$_SESSION['id']],"post_buzz_{$tbn}");
            
            //帖子赞+1
            $obj_article_indexing->update(['count_buzz'=>$check_article_indexing['count_buzz']+1],['postID'=>$postID]);
            
            //博主赞+1
            if($check_article_indexing['typeID']==1){
                $obj_blog_blogger=load("blog_blogger");
                $check_blog_blogger=$obj_blog_blogger->getOne(['id','count_buzz'],['id'=>$check_article_indexing['bloggerID']]);
                if(!empty($check_blog_blogger)){
                    $obj_blog_blogger->update(['count_buzz'=>$check_blog_blogger['count_buzz']+1],['id'=>$check_blog_blogger['id']]);
                }
            }
        }
        
        return "已赞";
    }
    
    /**
     * 用户
     * 赞 取消
     */
    public function buzz_delete(){
        $obj_article_indexing=load("article_indexing");
        $obj_article_post_buzz=load("article_post_buzz");
        
        $check_article_indexing=$obj_article_indexing->getOne(['id','postID','typeID','count_buzz','bloggerID'],['postID'=>$postID]);
        
        if(empty($check_article_indexing)){
            $this->error="此文章不存在";
            $this->status=false;
            return false;
        }
        
        $tbn=substr('0'.$postID,-1);
        $check_article_post_buzz=$obj_article_post_buzz->getOne(['id'],['userID'=>$_SESSION['id'],'postID'=>$postID],"post_buzz_{$tbn}");
        if(!empty($check_article_post_buzz)){
            $this->error="此文章您已经赞过了";
            $this->status=false;
            return false;
        }else{
            //添加人和帖子映射
            $obj_article_post_buzz->insert(['postID'=>$postID,'userID'=>$_SESSION['id']],"post_buzz_{$tbn}");
            
            //帖子赞+1
            $obj_article_indexing->update(['count_buzz'=>$check_article_indexing['count_buzz']+1],['postID'=>$postID]);
            
            //博主赞+1
            if($check_article_indexing['typeID']==1){
                $obj_blog_blogger=load("blog_blogger");
                $check_blog_blogger=$obj_blog_blogger->getOne(['id','count_buzz'],['id'=>$check_article_indexing['bloggerID']]);
                if(!empty($check_blog_blogger)){
                    $obj_blog_blogger->update(['count_buzz'=>$check_blog_blogger['count_buzz']+1],['id'=>$check_blog_blogger['id']]);
                }
            }
        }
        
        return "已取消赞";
    }
    
    /**
     * 用户
     * 赞 列表
     */
    public function buzz_list(){
        
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





































