<?php
class user extends Api {

    public $space = true;
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * 编辑器页 
     * 文章 添加
     * @param integer $bloggerID
     * @param object $data
     */
    public function article_add($title,$msg,$data){
        //主题
        
        //副属性
        $data=['typeID'=>1,'blogID'=>'1','categoryID'=>1];
    }
    
    /**
     * 编辑器页
     * 文章 添加 图片
     */
    public function article_add_pic(){
        
    }
    
    /**
     * 编辑器页
     * 文章 添加 视频
     */
    public function article_add_video(){
        
    }
    
    /**
     * 编辑器页
     * 文章 添加 音频
     */
    public function article_add_audio(){
        
    }
    
    /**
     * 编辑器页 
     * 文章 保存 自动
     * @param integer $bloggerID
     * @param object $data
     */
    public function article_save_auto($bloggerID,$data){
        
    }
    
    /**
     * 编辑器页 
     * 文章 修改
     * @param integer $bloggerID
     * @param object $data| data array.
     */
    public function article_update($bloggerID,$article){
        $this->common->updateAll(['activity_report'=>$activity_report],['student_id'=>$studentID]);
        if($activity_report==0){
            $obj=load('student_activity');
            $obj->remove(["student_id"=>$studentID]);
        }
        return  [];
    }
    
    /**
     * 编辑器页 
     * 文章 删除
     * @param integer $bloggerID
     */
    public function article_delete($bloggerID){
        
    }
    
    /**
     * 编辑器页
     * 文章 发布 
     */
    public function article_publish(){
        
    }
    
    /**
     * 编辑器页
     * 文章 发布 定时
     */
    public function article_publish_time(){
        
    }
    
    /**
     * 编辑器页
     * 文章 置顶
     */
    public function article_sticky(){
        
    }
    
    /**
     * 编辑器页
     * 文章 移动
     */
    public function article_shift(){
        
    }
    
    /**
     * 编辑器页
     * 文章 私密
     */
    public function article_private(){
        
    }
    
    /**
     * 编辑器页
     * 文章 禁止 评论
     */
    public function article_forbit_comment(){
        
    }
    
    /**
     * 编辑器页
     * 文章 禁止 转载
     */
    public function article_forbit_share(){
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}





































