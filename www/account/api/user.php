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
        $check_account_user=$obj_account_user->getOne("*",['id'=>$_SESSION['id']]);
        if($check_account_user['username']!=$username){
            $check_name=$obj_account_user->getOne("*",['username'=>$username]);
            if(!empty($check_name)) {$this->error="此笔名已经被占用";$this->status=false;return false;}
        }
        
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
     * 用户 头像 修改
     * @param string $avatar
     * @post avatar
     */
    public function user_avatar_update($avatar){
	    if(substr($avatar, 0, 4) === "data"){
            $file = file_get_contents($avatar);
        }
        else{
            $file = base64_decode($avatar);
        }

        //路径
        $dir="/upload/user/avatar/".substr('0000'.$_SESSION['id'],-2)."/".substr('0000'.$_SESSION['id'],-4,-2);
        $path=DOCUROOT.$dir;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        
        //文件名
        $filename=$_SESSION['id']."_avatar";
        $extension = explode('/', mime_content_type($avatar))[1];
        $rs_image=$filename.".".$extension;
        
        //保存
        file_put_contents($path."/".$rs_image, $file);
        
        //小图处理
        $obj_account_user=load("account_user");
        $obj_account_user->update(['avatar'=>"{$dir}/{$rs_image}"],['id'=>$_SESSION['id']]);
        $obj_account_user->cutPic("{$path}/{$rs_image}","{$filename}_100_100",100,100);
        
        return "{$dir}/{$rs_image}";
    }
    
    /**
     * 通用页
     * 黑名单 添加
     * @param integer $blockID|屏蔽人的ID
     */
    public function blacklist_add($blockID){
        $obj_account_user=load("account_user");
        $check_account_user=$obj_account_user->getOne(['id'],['status'=>1,'id'=>$blockID]);
        if(empty($check_account_user))  {$this->error="不是有效的屏蔽用户";$this->status=false;return false;}
            
        $obj_account_blacklist=load("account_blacklist");
        $check_account_blacklist=$obj_account_blacklist->getOne(['id'],['userID'=>$_SESSION['id'],'blockID'=>$blockID]);
        if(!empty($check_account_blacklist))    {$this->error="此用户已经被屏蔽";$this->status=false;return false;}
            
        $obj_account_blacklist->insert(['userID'=>$_SESSION['id'],'blockID'=>$blockID]);
        return true;
    }
    
    /**
     * 通用页
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
        
        return true;
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
     * 通用页
     * 举报 添加
     * @param integer $userID|举报用户的userID
     * @param string $msgbody|举报用户的msgbody
     */
    public function report_add($userID,$msgbody){
        $obj_account_user_report=load("account_user_report");
        $check_account_user_report=$obj_account_user_report->getOne("*",['userID'=>$userID,'from_userID'=>$_SESSION['id']]);
        if(!empty($check_account_user_report))  {$this->error="您已经举报过次用户了";$this->status=false;return false;}
        
        $obj_account_user_report->insert(['userID'=>$userID,'from_userID'=>$_SESSION['id'],'msgbody'=>$msgbody]);
        return true;
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
        
        return true;
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
        
        return true;
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
     * 通用页
     * 悄悄话 发送
     * @param integer $touserID | 被发送人touserID
     * @param integer $msgbody | 被发送人msgbody
     */
    public function qqh_add($touserID,$msgbody){
        $obj_account_qqh=load("account_qqh");
        $obj_account_qqh_post=load("account_qqh_post");
        
        if($_SESSION['id']==$touserID)  {$this->error="悄悄话的人就是自己";$this->status=false;return false;}
        
        $obj_account_user=load("account_user");
        $check_account_user=$obj_account_user->getOne(['id','username'],["id"=>$touserID,"status"=>1]);
        if(empty($check_account_user))  {$this->error="此用户不存在";$this->status=false;return false;}
        
        //查看黑名单
        $obj_account_blacklist=load("account_blacklist");
        $check_account_blacklist=$obj_account_blacklist->getOne(['id'],['userID'=>$touserID,'blockID'=>$_SESSION['id']]);
        if(!empty($check_account_blacklist)) {$this->error="此用户已经将您加入黑名单，无法发送悄悄话";$this->status=false;return false;}
        
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
        
        //添加消息列表
        $obj_account_notification=load("account_notification");
        $obj_account_notification->notification_add($check_account_user['id'],'qqh',$check_account_qqh['id'],"add");
        
        return true;
    }
    
    /**
     * 悄悄话页
     * 悄悄话 列表
     * @param integer $lastID | 最后一个悄悄话信息对话框的last_messageID
     * @response /account/api_response/qqh_list.txt
     */
    public function qqh_list($lastID=0){
        $obj_account_qqh=load("account_qqh");
        $obj_account_qqh_post=load("account_qqh_post");
        $obj_account_user=load("account_user");
        
        $where_account_qqh_post=[
            'limit'=>20,
            'SQL'=>"userID={$_SESSION['id']} OR touserID={$_SESSION['id']}",
            'order'=>["last_messageID"=>'DESC']
        ];
        if(!empty($lastID)){
            $where_account_qqh_post['last_messageID,<']=$lastID;
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
     * 悄悄话页
     * 悄悄话 删除对话
     * @param integer $qqhID | 悄悄话对话框ID
     */
    public function qqh_delete($qqhID){
        $obj_account_qqh=load("account_qqh");
        $obj_account_qqh_post=load("account_qqh_post");
        
        $check_account_qqh=$obj_account_qqh->getOne("*",['id'=>$qqhID]);
        if(empty($check_account_qqh))   {$this->error="此对话不存在";$this->status=false;return false;}
        if($check_account_qqh['userID']!=$_SESSION['id'] && $check_account_qqh['touserID']!=$_SESSION['id'])    {$this->error="此对话不存在";$this->status=false;return false;}
        
        //删除悄悄话
        $obj_account_qqh->remove(['id'=>$qqhID]);
        $obj_account_qqh_post->remove(['qqhID'=>$qqhID]);
        
        //删除消息列表
        $obj_account_notification=load("account_notification");
        $obj_account_notification->remove(['type'=>'qqh','userID'=>$check_account_qqh['userID'],'from_userID'=>$check_account_qqh['touserID']],"notification_".substr('0'.$check_account_qqh['userID'],-1));
        $obj_account_notification->remove(['type'=>'qqh','userID'=>$check_account_qqh['touserID'],'from_userID'=>$check_account_qqh['userID']],"notification_".substr('0'.$check_account_qqh['touserID'],-1));
        
        return true;
    }
    
    /**
     * 用户
     * 赞 添加
     * @param integer $postID | 文章的postID
     */
    public function buzz_add($postID){
        $obj_article_indexing=load("article_indexing");
        $check_article_indexing=$obj_article_indexing->getOne(['id','postID','typeID','count_buzz','bloggerID','userID'],['postID'=>$postID]);
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
        
        //添加消息列表
        $obj_account_notification=load("account_notification");
        $obj_account_notification->notification_add($check_article_indexing['userID'],'buzz',$postID,"add");
        return true;
    }
    
    /**
     * 用户
     * 赞 取消
     * @param integer $postID | 文章的postID
     */
    public function buzz_delete($postID){
        $obj_article_indexing=load("article_indexing");
        $check_article_indexing=$obj_article_indexing->getOne(['id','postID','typeID','count_buzz','bloggerID','userID'],['postID'=>$postID]);
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
        
        return true;
    }
    
    
    /**
     * "很多"页面
     * 我的关注人 添加
     * @param integer $followingID | 添加关注人ID
     */
    public function following_add($followingID){
        $obj_account_user=load("account_user");
        $check_account_user=$obj_account_user->getOne(['id','username','count_follower'],["id"=>$followingID,"status"=>1]);
        if(empty($check_account_user))  {$this->error="此用户不存在";$this->status=false;return false;}
        if($check_account_user['id']==$_SESSION['id'])  {$this->error="请不要自己关注自己！";$this->status=false;return false;}
        
        $obj_account_follow=load("account_follow");
        $check_account_follow=$obj_account_follow->getOne(['id'],['followerID'=>$_SESSION['id'],'followingID'=>$followingID]);
        if(!empty($check_account_follow)) {$this->error="此用户您已经关注过了";$this->status=false;return false;}
        
        //添加粉丝数
        $obj_account_user->update(['count_follower'=>$check_account_user['count_follower']+1],["id"=>$followingID]);
        
        //添加关注列表
        $id=$obj_account_follow->insert(['followerID'=>$_SESSION['id'],'followingID'=>$followingID]);
        
        //添加消息列表
        $obj_account_notification=load("account_notification");
        $obj_account_notification->notification_add($followingID,'follow',$id,"add");
        return true;
    }
    
    /**
     * "很多"页面
     * 关注 取消
     * @param integer $userID | 取消关注人ID
     */
    public function following_delete($followingID){
        $obj_account_user=load("account_user");
        $check_account_user=$obj_account_user->getOne(['id','username','count_follower'],["id"=>$followingID,"status"=>1]);
        if(empty($check_account_user))  {$this->error="此用户不存在";$this->status=false;return false;}
        if($check_account_user['id']==$_SESSION['id'])  {$this->error="请不要自己取消关注自己！";$this->status=false;return false;}
        
        $obj_account_follow=load("account_follow");
        $check_account_follow=$obj_account_follow->getOne(['id'],['followerID'=>$_SESSION['id'],'followingID'=>$followingID]);
        if(empty($check_account_follow)) {$this->error="此用户不在您的关注列表";$this->status=false;return false;}
        
        //移除粉丝数
        $obj_account_user->update(['count_follower'=>$check_account_user['count_follower']-1],["id"=>$followingID]);
        
        //移除关注列表
        $obj_account_follow->remove(['followerID'=>$_SESSION['id'],'followingID'=>$followingID]);
        
        //添加消息列表
        $obj_account_notification=load("account_notification");
        $obj_account_notification->notification_add($followingID,'follow',0,"delete");
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
            "type"=>"reply",
            "order"=>['id'=>"DESC"]
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
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $rs_article_indexing=$obj_account_user->get_basic_userinfo($rs_article_indexing,"userID");
        
        //添加ES信息
        $obj_search_article_noindex=load("search_article_noindex");
        $rs_article_indexing=$obj_search_article_noindex->get_postInfo($rs_article_indexing);
        
        //添加文章计数信息
        $rs_article_indexing=$obj_article_indexing->get_article_count($rs_article_indexing);
        
        return $rs_article_indexing;
    }
    
    /**
     * 我的粉丝
     * 我的 粉丝 列表
     * @param integer $lastID | 最后的id
     */
    public function my_follower_list($lastID=0){
        $obj_account_follow=load("account_follow");
        $fields=["followingID"=>$_SESSION['id'],'limit'=>20];
        if(!empty($lastID)){
            $fields['id,<']=$lastID;
        }
        $rs_account_follow=$obj_account_follow->getAll("*",$fields);
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $rs_account_follow=$obj_account_user->get_basic_userinfo($rs_account_follow,"followerID");
        return $rs_account_follow;
    }
    
    /**
     * 小铃铛页
     * 消息 未读 计数
     */
    public function notification_unread_count(){
        
        //缓存
        $obj_memcache=func_initMemcached('cache01');
        $rs=$obj_memcache->get(FILE_DOMAIN."{$_SESSION['id']}_notification_unread_count");
        
        if(empty($rs)){
            $obj_account_notification=load("account_notification");
            $tbn=substr('0'.$_SESSION['id'],-1);
            $rs['reply']=$obj_account_notification->count(["userID"=>$_SESSION['id'],'is_read'=>0,'type'=>'reply'],"notification_".$tbn);
            $rs['qqh']=$obj_account_notification->count(["userID"=>$_SESSION['id'],'is_read'=>0,'type'=>'qqh'],"notification_".$tbn);
            $rs['follow']=$obj_account_notification->count(["userID"=>$_SESSION['id'],'is_read'=>0,'type'=>'follow'],"notification_".$tbn);
            $rs['buzz']=$obj_account_notification->count(["userID"=>$_SESSION['id'],'is_read'=>0,'type'=>'buzz'],"notification_".$tbn);
            $rs['totall']=$rs['reply']+$rs['qqh']+$rs['follow']+$rs['buzz'];
            $obj_memcache->set(FILE_DOMAIN."{$_SESSION['id']}_notification_unread_count",$rs,60);
        }
        
        return $rs;
    }
    
    /**
     * 小铃铛页
     * 消息 清空 计数
     * @param integer $type | 小铃铛类型 (blog_comment,qqh,buzz,follow)
     */
    public function notification_unread_clear($type=''){
        $obj_account_notification=load("account_notification");
        $tbn=substr('0'.$_SESSION['id'],-1);
        $fileds=['userID'=>$_SESSION['id'],'is_read'=>0];
        if(!empty($type)){
            $fileds['type']=$type;
        }
        
        $obj_account_notification->update(['is_read'=>1],$fileds,"notification_".$tbn);
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
        
        $fields=['userID'=>$_SESSION['id'],'limit'=>20,'order'=>['id'=>'DESC']];
        if(!empty($lastID)){
            $fields['id,<']=$lastID;
        }
        
        $rs_account_notification=$obj_account_notification->getAll("*",$fields,"notification_".$tbn);
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $rs_account_notification=$obj_account_user->get_basic_userinfo($rs_account_notification,"from_userID");
        return $rs_account_notification;
    }
    
    /**
     * 通用页
     * 用户 气泡
     */
    public function user_bubble() {
        $obj_account_user_login=load("account_user_login");
        $rs_user_bubble=$obj_account_user_login->user_bubble($_SESSION['id']);
        
        return $rs_user_bubble;
    }
    
    /**
     * 通用页
     * 用户 气泡
     * @param string $type | 气泡类型
     * @param integer $visible | 0,1
     */
    function user_bubble_update($type,$visible){
        //更新数据库
        $obj_account_user_bubble=load("account_user_bubble");
        $obj_account_user_bubble->update([$type=>$visible],['userID'=>$_SESSION['id']]);
        
        //更新SESSION
        if(!empty($_SESSION['bubble'])){
            $_SESSION['bubble']['user'][$type]=$visible;
        }
        
        //全部字段为0,取消bubble
        $check_account_user_bubble=$obj_account_user_bubble->getOne("*",['userID'=>$_SESSION['id']]);
        if(!empty($check_account_user_bubble)){
            $visible=0;
            foreach($check_account_user_bubble as $k=>$v){
                if($k=="id" || $k=="userID" || $k=="visible"){
                    continue;
                }
                if($v==1){
                    $visible=1;
                    break;
                }
            }
            if($visible==0){
                $obj_account_user_bubble->update(['visible'=>0],['userID'=>$_SESSION['id']]);
                $check_account_user_bubble['visible']=0;
            }
        }
        
        return $this->user_bubble();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}





































