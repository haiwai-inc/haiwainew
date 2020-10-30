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
     */
    public function blacklist_add(){
        
    }
    
    /**
     * 很多页面
     * 黑名单 删除
     */
    public function blacklist_delete(){
        
    }
    
    /**
     * 个人设置黑名单页
     * 黑名单 列表
     */
    public function blacklist_list(){
        
    }
    
    /**
     * 文章详情页
     * 书签 添加
     */
    public function bookmark_add(){
        
    }
    
    /**
     * 文章详情页
     * 书签 删除
     */
    public function bookmark_delete(){
        
    }
    
    /**
     * 收藏页
     * 书签 列表
     */
    public function bookmark_list(){
        
    }
    
    /**
     * 很多页面
     * 悄悄话 发送
     */
    public function qqh_add(){
        
    }
    
    /**
     * 悄悄话页
     * 悄悄话 列表
     */
    public function qqh_list(){
        
    }
    
    /**
     * 悄悄话详情页
     * 悄悄话 详情
     */
    public function qqh_view(){
        
    }
    
    /**
     * 用户
     * 赞 添加
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





































