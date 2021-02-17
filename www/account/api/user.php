<?php
/**
 * @author sida
 * [
 *  'data'=>[],
 *  'error'=>[
 *      ['name'=>'name1','msg'=>'msg1'],
 *      ['name'=>'name1','msg'=>'msg1']
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
     * 用户 帐号
     */
    public function user_profile(){
        $obj_account_user=load("account_user");
        $rs_account_user=$obj_account_user->getOne(['id','email','username','description','avatar'],['id'=>$_SESSION['id']]);
        $rs_account_user['first_letter']=substr(strings::subString($rs_account_user['username'],1), 0, -3);
        
        return $rs_account_user;
    }
    
    /**
     * 用户设置页
     * 用户 帐号 修改
     * @param integer $username|笔名
     * @param integer $description|个人简介
     */
    public function user_profile_update($username,$description=NULL){
        $obj_account_user=load("account_user");
        $check_account_user=$obj_account_user->getOne("*",['username'=>$username]);
        if(!empty($check_account_user)) {$this->error="此笔名已经被占用";$this->status=false;return false;}
        
        $fields=[
            "username"=>empty($username)?"":$username,
            "description"=>empty($description)?"":$description,
        ];
        $obj_account_user->update($fields,['id'=>$_SESSION['id']]);
        return true;
    }
    
    /**
     * 用户设置页
     * 用户 密码 修改
     * @param integer $password|密码
     */
    public function user_password_update($password){
        $obj_account_user=load("account_user");
        $rs_account_user=$obj_account_user->check_password($password);
        if(empty($rs_account_user['status']))   {$this->error=$rs_account_user['error'];$this->status=false;return false;}
        
        $rs_account_user=$obj_account_user->update(['password'=>md5($password)],['id'=>$_SESSION['id']]);
        return $rs_account_user;
    }
    
    /**
     * 用户设置页
     * 用户 修改 头像
     * 
     */
    public function user_avatar_update(){
        
    }

    /**
     * 很多页面
     * 黑名单 添加
     * @param integer $blockID|屏蔽人的ID
     */
    public function blacklist_add($blockID){
        $obj_account_user=load("account_user");
        $check_account_user=$obj_account_user->getOne(['id'],['status'=>1,'id'=>$blockID]);
        if(empty($check_account_user))  {$this->error="不是有效的屏蔽用户";$this->status=false;return false;}
            
        $obj_account_blacklist=load("account_blacklist");
        if(!empty($check_account_blacklist))    {$this->error="此用户已经被屏蔽";$this->status=false;return false;}
            
        $obj_account_blacklist->insert(['userID'=>$_SESSION['id'],'blockID'=>$blockID]);
        
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
        if(empty($check_account_user))  {$this->error="不是有效的屏蔽用户";$this->status=false;return false;}
        
        $obj_account_blacklist=load("account_blacklist");
        $check_account_blacklist=$obj_account_blacklist->getOne(['id'],['userID'=>$_SESSION['id'],'blockID'=>$blockID]);
        if(empty($check_account_blacklist)) {$this->error="此用户未被列入黑名单";$this->status=false;return false;}
        
        $obj_account_blacklist->remove(['userID'=>$_SESSION['id'],'blockID'=>$blockID]);
        
        return "已取消屏蔽此用户";
    }
    
    /**
     * 个人设置黑名单页
     * 黑名单 列表
     * @param integer $lastID|最后黑名单的ID
     */
    public function blacklist_list($lastID=0){
        $obj_account_blacklist=load("account_blacklist");
        $fields=['userID'=>$_SESSION['id'],'limit'=>20];
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
        if(empty($check_article_indexing))  {$this->error="此文章不存在";$this->status=false;return false;}
        
        $obj_account_bookmark=load("account_bookmark");
        $check_account_book=$obj_account_bookmark->getOne("*",['userID'=>$_SESSION['id'],'postID'=>$postID]);
        if(!empty($check_account_book)) {$this->error="此文章已经加入到了书签";$this->status=false;return false;}
            
        $obj_account_bookmark->insert(['userID'=>$_SESSION['id'],'postID'=>$postID]);
        
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
        if(empty($check_account_book))  {$this->error="书签里没有此文章";$this->status=false;return false;}
        
        $obj_account_bookmark->remove(['userID'=>$_SESSION['id'],'postID'=>$postID]);
        
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
        
        $fields=['userID'=>$_SESSION['id'],'limit'=>20];
        if(!empty($lastID)){
            $fields['id,<']=$lastID;
        }
        $rs_account_bookmark=$obj_account_bookmark->getAll("*",$fields);
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $rs_account_bookmark=$obj_account_user->get_basic_userinfo($rs_account_bookmark,"userID");
        
        //添加ES信息
        $obj_search_article_noindex=load("search_article_noindex");
        $rs_account_bookmark=$obj_search_article_noindex->get_postInfo($rs_account_bookmark);
        
        //添加文章计数信息
        $obj_article_indexing=load("article_indexing");
        $rs_account_bookmark=$obj_article_indexing->get_article_count($rs_account_bookmark);
        
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
        
        if($_SESSION['id']==$touserID)  {$this->error="悄悄话的人就是自己";$this->status=false;return false;}
        
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
     * @response /account/api_response/qqh_list.txt
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
        
        if(empty($rs_account_qqh_post)) {$this->error="此对话不存在";$this->status=false;return false;}
        
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
        $check_article_indexing=$obj_article_indexing->getOne(['id','postID','typeID','count_buzz','bloggerID'],['postID'=>$postID]);
        if(empty($check_article_indexing))  {$this->error="此文章不存在";$this->status=false;return false;}
        
        $obj_article_post_buzz=load("article_post_buzz");
        $tbn=substr('0'.$postID,-1);
        $check_article_post_buzz=$obj_article_post_buzz->getOne(['id'],['userID'=>$_SESSION['id'],'postID'=>$postID],"post_buzz_{$tbn}");
        if(!empty($check_article_post_buzz))    {$this->error="此文章您已经赞过了";$this->status=false;return false;}
        
        //添加人
        $obj_article_post_buzz->insert(['postID'=>$postID,'userID'=>$_SESSION['id']],"post_buzz_{$tbn}");
        
        //帖子赞+1 修改时间
        $obj_article_indexing->update(['buzz_date'=>times::getTime(),'count_buzz'=>$check_article_indexing['count_buzz']+1],['postID'=>$postID]);
        
        //博主赞+1
        if($check_article_indexing['typeID']==1){
            $obj_blog_blogger=load("blog_blogger");
            $check_blog_blogger=$obj_blog_blogger->getOne(['id','count_buzz'],['id'=>$check_article_indexing['bloggerID']]);
            if(!empty($check_blog_blogger)){
                $obj_blog_blogger->update(['count_buzz'=>$check_blog_blogger['count_buzz']+1],['id'=>$check_blog_blogger['id']]);
            }
        }
        
        //同步ES索引
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_noindex->fetch_and_insert([$postID]);
        
        return "已赞";
    }
    
    /**
     * 用户
     * 赞 取消
     * @param integer $postID | 文章的postID
     */
    public function buzz_delete($postID){
        $obj_article_indexing=load("article_indexing");
        $check_article_indexing=$obj_article_indexing->getOne(['id','postID','typeID','count_buzz','bloggerID'],['postID'=>$postID]);
        if(empty($check_article_indexing))  {$this->error="此文章不存在";$this->status=false;return false;}
        
        $obj_article_post_buzz=load("article_post_buzz");
        $tbn=substr('0'.$postID,-1);
        $check_article_post_buzz=$obj_article_post_buzz->getOne(['id'],['userID'=>$_SESSION['id'],'postID'=>$postID],"post_buzz_{$tbn}");
        if(empty($check_article_post_buzz))    {$this->error="此文章您已经取消点赞了";$this->status=false;return false;}
        
        //取消添加人
        $obj_article_post_buzz->remove(['postID'=>$postID,'userID'=>$_SESSION['id']],"post_buzz_{$tbn}");
        
        //帖子赞-1
        $obj_article_indexing->update(['count_buzz'=>$check_article_indexing['count_buzz']-1],['postID'=>$postID]);
        
        //博主赞-1
        if($check_article_indexing['typeID']==1){
            $obj_blog_blogger=load("blog_blogger");
            $check_blog_blogger=$obj_blog_blogger->getOne(['id','count_buzz'],['id'=>$check_article_indexing['bloggerID']]);
            if(!empty($check_blog_blogger)){
                $obj_blog_blogger->update(['count_buzz'=>$check_blog_blogger['count_buzz']-1],['id'=>$check_blog_blogger['id']]);
            }
        }
        
        //同步ES索引
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_noindex->fetch_and_insert([$postID]);
        
        return "已取消赞";
    }
    
    
    /**
     * "很多"页面
     * 关注 添加
     * @param integer followerID | 添加关注人ID
     */
    public function follower_add($followerID){
        $obj_account_user=load("account_user");
        $check_account_user=$obj_account_user->getOne(['id','username'],["id"=>$followerID,"status"=>1]);
        if(empty($check_account_user))  {$this->error="此用户不存在";$this->status=false;return false;}
        if($check_account_user['id']==$_SESSION['id'])  {$this->error="请不要自己关注自己！";$this->status=false;return false;}
        
        $obj_account_follower=load("account_follower");
        $check_account_follower=$obj_account_follower->getOne(['id'],['userID'=>$_SESSION['id'],'followerID'=>$followerID]);
        if(!empty($check_account_follower)) {$this->error="此用户您已经关注过了";$this->status=false;return false;}
        
        //添加关注列表
        $id=$obj_account_follower->insert(['userID'=>$_SESSION['id'],'followerID'=>$followerID]);
        
        //添加消息列表
        $obj_account_notification=load("account_notification");
        $tbn=substr('0'.$followerID,-1);
        $msgbody="{$_SESSION['username']} 关注了您";
        $obj_account_notification->insert(['userID'=>$followerID,'type'=>"follower",'typeID'=>$id,'msgbody'=>$msgbody],"notification_".$tbn);  
        return true;
    }
    
    /**
     * "很多"页面
     * 关注 取消
     * @param integer followerID | 取消关注人ID
     */
    public function follower_delete($followerID){
        $obj_account_user=load("account_user");
        $check_account_user=$obj_account_user->getOne(['id','username'],["id"=>$followerID,"status"=>1]);
        if(empty($check_account_user))  {$this->error="此用户不存在";$this->status=false;return false;}
        if($check_account_user['id']==$_SESSION['id'])  {$this->error="请不要自己取消关注自己！";$this->status=false;return false;}
        
        $obj_account_follower=load("account_follower");
        $check_account_follower=$obj_account_follower->getOne(['id'],['userID'=>$_SESSION['id'],'followerID'=>$followerID]);
        if(empty($check_account_follower)) {$this->error="此用户不在您的关注列表";$this->status=false;return false;}
        
        //移除关注列表
        $id=$obj_account_follower->remove(['userID'=>$_SESSION['id'],'followerID'=>$followerID]);
        
        //添加消息列表
        $obj_account_notification=load("account_notification");
        $tbn=substr('0'.$followerID,-1);
        $msgbody="{$_SESSION['username']} 取消了对您的关注";
        $obj_account_notification->insert(['userID'=>$followerID,'type'=>"follower",'typeID'=>$id,'msgbody'=>$msgbody],"notification_".$tbn);
        return true;
    }
    
    /**
     * 我的 新评论 列表 
     * @param integer $lastID | 最后id
     */
    public function my_comment_list($lastID=0){
        $obj_account_notification=load("account_notification");
        $tbn=substr('0'.$_SESSION['id'],-1);
        $where=[
            'limit'=>20,
            "userID"=>$_SESSION['id'],
            "type"=>"blog_comment",
            "order"=>['id'=>"ASC"]
        ];
        if(!empty($lastID)){
            $where['id,<']=$lastID;
        }
        $rs_account_notification=$obj_account_notification->getAll("*",$where,"notification_".$tbn);
        
        //由跟帖补全主贴信息 并且group
        $obj_article_indexing=load("article_indexing");
        $rs_account_notification=$obj_article_indexing->get_article_info_by_comment($rs_account_notification);
        
        return $rs_account_notification;
    }
    
    /**
     * 小铃铛页
     * 我的 点赞人 列表
     * @param integer $lastID | 最后的文章ID
     */
    public function my_buzz_article_list($lastID=0){
        $obj_article_indexing=load("article_indexing");
        $fields=['limit'=>20,'userID'=>$_SESSION['id'],'order'=>['buzz_date'=>'DESC'],'count_buzz,!='=>0];
        if(!empty($lastID)){
            $fields['id,<']=$lastID;
        }
        $rs_article_indexing=$obj_article_indexing->getAll(['id','postID','userID','blogID'],$fields);
        if(empty($rs_article_indexing)){
            return [];
        }
        
        //ES补全postID信息
        $obj_article_noindex=load("search_article_noindex");
        $rs_article_indexing=$obj_article_noindex->get_postInfo($rs_article_indexing);
        return $rs_article_indexing;
    }
    
    /**
     * 我的粉丝
     * 我的 粉丝 列表
     * @param integer $lastID | 最后的id
     */
    public function my_follower_list($lastID=0){
        $obj_account_follower=load("account_follower");
        $fields=["followerID"=>$_SESSION['id'],'limit'=>20];
        if(!empty($lastID)){
            $fields['id,<']=$lastID;
        }
        $rs_account_follower=$obj_account_follower->getAll("*",$fields);
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $rs_account_follower=$obj_account_user->get_basic_userinfo($rs_account_follower,"userID");
        
        return $rs_account_follower;
    }
    
    /**
     * 小铃铛页
     * 消息 未读 计数
     */
    public function notification_unread_count(){
        $obj_account_notification=load("account_notification");
        $tbn=substr('0'.$_SESSION['id'],-1);
        $rs['blog_comment']=$obj_account_notification->count(["userID"=>$_SESSION['id'],'is_read'=>0,'type'=>'blog_comment'],"notification_".$tbn);
        $rs['qqh']=$obj_account_notification->count(["userID"=>$_SESSION['id'],'is_read'=>0,'type'=>'qqh'],"notification_".$tbn);
        $rs['follower']=$obj_account_notification->count(["userID"=>$_SESSION['id'],'is_read'=>0,'type'=>'follower'],"notification_".$tbn);
        $rs['buzz']=$obj_account_notification->count(["userID"=>$_SESSION['id'],'is_read'=>0,'type'=>'buzz'],"notification_".$tbn);
        $rs['totall']=$rs['blog_comment']+$rs['qqh']+$rs['follower']+$rs['totall'];
        return $rs;
    }
    
    /**
     * 小铃铛页
     * 消息 清空 计数
     * @param integer $type | 小铃铛类型 (blog_comment,qqh,buzz,follower)
     */
    public function notification_unread_clear($type='blog_comment'){
        $obj_account_notification=load("account_notification");
        $tbn=substr('0'.$_SESSION['id'],-1);
        
        $obj_account_notification->update(['is_read'=>1],['userID'=>$_SESSION['id'],'is_read'=>0,'type'=>$type],"notification_".$tbn);
        
        return true;
    }
    
    /**
     * 小铃铛页
     * 消息 列表
     * @param integer $lastID | 最后消息的id
     */
    public function notification_list($lastID=0){
        $obj_account_notification=load("account_notification");
        $tbn=substr('0'.$_SESSION['id'],-1);
        
        $fields=['userID'=>$_SESSION['id'],'limit'=>20];
        if(!empty($lastID)){
            $fields['id,<']=$lastID;
        }
        
        $rs_account_notification=$obj_account_notification->getAll("*",$fields,"notification_".$tbn);
        return $rs_account_notification;
    }
    
    public function init_sse(){
        
    }
    
    
    
}





































