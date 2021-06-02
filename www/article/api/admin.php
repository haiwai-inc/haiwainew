<?php
class admin extends Api {
    public $space = true;
    public $admin = true;
    
    public function __construct() {
        parent::__construct();
        $this->sess = true;
    }

    /**
     * 通用页
     * 文章 删除
     * @param integer $postID | 文章的postID
     * @param integer $visible | 1开 0关
     */
    public function article_delete($postID,$visible){
        $obj_article_indexing=load("article_indexing");
        $rs_article_indexing=$obj_article_indexing->getOne("*",['visible'=>1,'postID'=>$postID]);
        if(empty($rs_article_indexing)){$this->error="此文章不存在";$this->status=false;return false;}
        
        $time=times::gettime();
        $obj_article_indexing->update(['visible'=>!empty($visible)?1:0,"edit_date"=>$time],['postID'=>$postID]);
        
        //同步博客数据
        if($rs_article_indexing['typeID']==1){
            $obj_blog_blogger=load("blog_blogger");
            $obj_blog_blogger->delete_blog_article($rs_article_indexing);
        }
        
        //同步ES索引
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_noindex->fetch_and_insert([$postID]);
        
        return true;
    }
    
    /**
     * 通用页
     * 回复 删除
     * @param int $id | 回复的postID
     */
    public function reply_delete($id){
        //检查修改帖子
        $obj_article_indexing=load("article_indexing");
        $check_article_indexing=$obj_article_indexing->getOne(['id','postID','treelevel','userID','basecode','treelevel'],['postID'=>$id]);
        if(empty($check_article_indexing)) {$this->error="删除的帖子不存在";$this->status=false;return false;}
        
        //更新帖子状态
        $obj_article_indexing->update(['visible'=>0],['postID'=>$check_article_indexing['postID']]);
        
        //更新主贴
        $check_main_article_indexing=$obj_article_indexing->getOne(['postID','count_comment','treelevel','userID'],['postID'=>$check_article_indexing['basecode']]);
        if($check_main_article_indexing['treelevel']==2){
            $check_main_article_indexing=$obj_article_indexing->getOne(['postID','count_comment','treelevel','userID'],['postID'=>$check_article_indexing['basecode']]);
        }
        $rs_count_delete=$obj_article_indexing->count(['visible'=>1,'basecode'=>$id]);
        $obj_article_indexing->update(['count_comment'=>$check_main_article_indexing['count_comment']-$rs_count_delete-1],['postID'=>$check_article_indexing['basecode']]);
        
        //同步ES索引
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_noindex->fetch_and_insert([$id,$check_main_article_indexing['postID']]);
        
        //删除记录消息
        $obj_account_notification=load("account_notification");
        $obj_account_notification->remove(['type'=>'reply','typeID'=>$id,'from_userID'=>$check_article_indexing['userID']],"notification_".substr('0'.$check_main_article_indexing['userID'],-1));
        
        return true;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}





































