<?php
class user extends Api {

    public $space = true;
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * 编辑器页 
     * 文章 添加
     * @param obj $article_data | 文章的数据
     * @param obj $module_data | 组件的数据
     * @post article_data,module_data
     * @response /article/api_response/article_add.txt
     */
    public function article_add($article_data,$module_data){
        //验证用户发帖信息
        $obj_article_indexing=load("article_indexing");
        if(!$obj_article_indexing->article_add_validation($article_data+$module_data))   {$this->error="发帖验证未通过";$this->status=false;return false;}
        
        //添加文章 post
        $obj_article_post=load("article_post");
        $article_data['postID']=$module_data['postID']=$obj_article_post->get_id();
        $time=times::getTime();
        $fields_indexing=[
            "postID"=>$article_data['postID'],
            "typeID"=>$article_data['typeID'],
            "basecode"=>$article_data['postID'],
            "userID"=>$_SESSION['id'],
            "treelevel"=>0,
            "create_date"=>$time,
            "edit_date"=>$time,
        ];
        $obj_article_indexing->insert($fields_indexing);
        $post_tbn=substr('0'.$_SESSION['id'],-1);
        $fields_post=[
            "id"=>$article_data['postID'],
            "title"=>$article_data['title'],
            "msgbody"=>$article_data['msgbody'],
        ];
        $obj_article_post->insert($fields_post,"post_{$post_tbn}");
        
        //添加文章 tag
        $obj_article_tag=load("article_tag");
        $obj_article_tag->article_tag_add($article_data);
        
        //转文章为博客类型
        if($article_data['typeID']==1){
            $obj_blog_blogger=load("blog_blogger");
            $obj_blog_blogger->to_blog_article($module_data);
        }
        
        //同步ES索引
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_noindex->fetch_and_insert([$article_data['postID']]);
        
        //删除草稿
        if(!empty($article_data['draftID'])){
            $obj_article_draft=load("article_draft");
            $obj_article_draft->remove(['id'=>$article_data['draftID']]);
        }
        
        return true;
    }
    
    /**
     * 编辑器页
     * 文章 修改
     * @param obj $article_data | 文章的数据
     * @param obj $module_data | 组件的数据
     * @post article_data,module_data
     * @response /article/api_response/article_update.txt
     */
    public function article_update($article_data,$module_data){
        //验证用户修改帖子信息
        $obj_article_indexing=load("article_indexing");
        if(!$obj_article_indexing->article_update_validation($article_data+$module_data))   {$this->error="修改帖子验证未通过";$this->status=false;return false;}
        
        //主贴信息
        $rs_article_post=$obj_article_indexing->getOne(['postID','userID'],['postID'=>$article_data['postID']]);
        
        //修改文章 post
        $obj_article_post=load("article_post");
        $time=times::getTime();
        $fields_indexing=[
            "is_pic"=>0,
            "edit_date"=>$time,
        ];
        $obj_article_indexing->update($fields_indexing,['postID'=>$article_data['postID']]);
        $post_tbn=substr('0'.$rs_article_post['userID'],-1);
        $fields_post=[
            "title"=>$article_data['title'],
            "msgbody"=>$article_data['msgbody'],
        ];
        $obj_article_post->update($fields_post,['id'=>$rs_article_post['postID']],"post_{$post_tbn}");
        
        //添加文章 tag
        $obj_article_tag=load("article_tag");
        $obj_article_tag->article_tag_add($article_data);
        
        //修改博客类型文章
        if($article_data['typeID']==1){
            $obj_blog_blogger=load("blog_blogger");
            $obj_blog_blogger->to_blog_article($module_data);
        }
        
        //同步ES索引
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_noindex->fetch_and_insert([$rs_article_post['postID']]);
        
        //删除草稿
        if(!empty($article_data['draftID'])){
            $obj_article_draft=load("article_draft");
            $obj_article_draft->remove(['id'=>$article_data['draftID']]);
        }
        
        return true;
    }
    
    /**
     * 编辑器页
     * 文章 草稿 添加
     * @param integer $id | 编辑帖子id
     */
    public function article_draft_add_by_postID($id){
        $obj_article_indexing=load("article_indexing");
        
        $rs_article_indexing=$obj_article_indexing->getOne(['postID','categoryID','userID','bloggerID','create_date','edit_date','typeID'],['visible'=>1,'postID'=>$id]);
        if(empty($rs_article_indexing)) {$this->error="此文章不存在";$this->status=false;return false;}
        
        //ES补全postID信息
        $obj_article_noindex=load("search_article_noindex");
        $rs_article_indexing=$obj_article_noindex->get_postInfo([$rs_article_indexing],'postID',true)[0];
        
        $obj_article_draft=load("article_draft");
        $check_article_draft=$obj_article_draft->getOne(['id'],['postID'=>$id]);
        if(!empty($check_article_draft)) {$this->error="此草稿已经存在";$this->status=false;return false;}
        
        $tagID=implode(",",$rs_article_indexing['postInfo_postID']['tags']);
        $fields=[
            "postID"=>$rs_article_indexing['postID'],
            "typeID"=>empty($rs_article_indexing['typeID'])?"":$rs_article_indexing['typeID'],
            "userID"=>$rs_article_indexing['userID'],
            "bloggerID"=>empty($rs_article_indexing['bloggerID'])?0:$rs_article_indexing['bloggerID'],
            "categoryID"=>empty($rs_article_indexing['categoryID'])?0:$rs_article_indexing['categoryID'],
            "tagID"=>empty($tagID)?"":$tagID,
            "create_date"=>$rs_article_indexing['create_date'],
            "edit_date"=>$rs_article_indexing['edit_date'],
            "title"=>empty($rs_article_indexing['postInfo_postID']['title'])?"":$rs_article_indexing['postInfo_postID']['title'],
            "msgbody"=>empty($rs_article_indexing['postInfo_postID']['msgbody'])?"":$rs_article_indexing['postInfo_postID']['msgbody'],
        ];
        $obj_article_draft->insert($fields);
        
        return true;
    }
    
    /**
     * 编辑器页
     * 文章 草稿 添加
     * @param obj $article_data | 文章的数据
     * @param obj $module_data | 组件的数据
     * @post article_data,module_data
     * @response /article/api_response/article_draft_add.txt
     */
    public function article_draft_add($article_data="",$module_data=""){
        //添加草稿 tag
        $obj_article_tag=load("article_tag");
        $tagID=$obj_article_tag->draft_tag_add($article_data);
        
        $obj_article_draft=load("article_draft");
        $time=times::getTime();
        $fields=[
            "typeID"=>empty($article_data['typeID'])?"":$article_data['typeID'],
            "userID"=>$_SESSION['id'],
            "bloggerID"=>empty($module_data['bloggerID'])?0:$module_data['bloggerID'],
            "categoryID"=>empty($module_data['categoryID'])?0:$module_data['categoryID'],
            "tagID"=>empty($tagID)?"":$tagID,
            "create_date"=>$time,
            "edit_date"=>$time,
            "title"=>empty($article_data['title'])?"":$article_data['title'],
            "msgbody"=>empty($article_data['msgbody'])?"":$article_data['msgbody'],
        ];
        $obj_article_draft->insert($fields);
        return true;
    }
    
    /**
     * 编辑器页
     * 文章 草稿 修改
     * @param obj $article_data | 文章的数据
     * @param obj $module_data | 组件的数据
     * @post article_data,module_data
     * @response /article/api_response/article_draft_update.txt
     */
    public function article_draft_update($article_data,$module_data){
        //添加草稿 tag
        $obj_article_tag=load("article_tag");
        $tagID=$obj_article_tag->draft_tag_add($article_data);
        
        $obj_article_draft=load("article_draft");
        $time=times::getTime();
        $fields=[
            "categoryID"=>empty($module_data['categoryID'])?0:$module_data['categoryID'],
            "tagID"=>empty($tagID)?"":$tagID,
            "edit_date"=>$time,
            "title"=>empty($article_data['title'])?"":$article_data['title'],
            "msgbody"=>empty($article_data['msgbody'])?"":$article_data['msgbody'],
        ];
        $obj_article_draft->update($fields,['bloggerID'=>$module_data['bloggerID'],'userID'=>$_SESSION['id'],'id'=>$article_data['draftID']]);
        return true;
    }
    
    /**
     * 编辑器页
     * 文章 草稿 删除
     * @param obj $id | 草稿的id
    */
    public function article_draft_delete($id){
        $obj_article_draft=load("article_draft");
        $obj_article_draft->remove(['id'=>$id]);
        
        return true;
    }
    
    /**
     * 文章详情页
     * 文章 回复 添加
     * @param obj $article_data | 文章的数据
     * @post article_data
     * @response /article/api_response/article_reply.txt
     */
    public function article_reply_add($article_data){
         //检查主贴
         $obj_article_indexing=load("article_indexing");
         $check_article_indexing=$obj_article_indexing->getOne(['id','postID','treelevel'],['postID'=>$article_data['postID']]);
         if(empty($check_article_indexing)) {$this->error="回复的主帖不存在";$this->status=false;return false;}
         
         //添加回复 post
         $obj_article_post=load("article_post");
         $postID=$obj_article_post->get_id();
         $time=times::getTime();
         $fields_indexing=[
             "postID"=>$postID,
             "typeID"=>$article_data['typeID'],
             "basecode"=>$check_article_indexing['postID'],
             "userID"=>$_SESSION['id'],
             "treelevel"=>$check_article_indexing['treelevel']+1,
             "create_date"=>$time,
             "edit_date"=>$time,
         ];
         $obj_article_indexing->insert($fields_indexing);
         $post_tbn=substr('0'.$_SESSION['id'],-1);
         $fields_post=[
             "id"=>$postID,
             "title"=>"回复 {$check_article_indexing['postID']}",
             "msgbody"=>$article_data['msgbody'],
         ];
         $obj_article_post->insert($fields_post,"post_{$post_tbn}");
         
         //同步ES索引
         $obj_article_noindex=load("search_article_noindex");
         $obj_article_noindex->fetch_and_insert([$postID]);
         
         return true;
    }
    
    /**
     * 文章详情页
     * 文章 回复 修改
     * @param obj $article_data | 文章的数据
     * @post article_data
     * @response /article/api_response/article_reply.txt
     */
    public function article_reply_update($article_data){
        //检查修改帖子
        $obj_article_indexing=load("article_indexing");
        $check_article_indexing=$obj_article_indexing->getOne(['id','postID','treelevel','userID'],['postID'=>$article_data['postID']]);
        if(empty($check_article_indexing)) {$this->error="修改的帖子不存在";$this->status=false;return false;}
        
        //更新帖子时间
        $time=times::getTime();
        $obj_article_indexing->update(['edit_date'=>$time],['postID'=>$article_data['postID']]);
        
        //更新内容
        $obj_article_post=load("article_post");
        $post_tbn=substr('0'.$check_article_indexing['userID'],-1);
        $obj_article_post->update(['msgbody'=>$article_data['msgbody']],['postID'=>$check_article_indexing['postID']],"post_{$post_tbn}");
        
        //同步ES索引
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_noindex->fetch_and_insert([$article_data['postID']]);
    }
    
    /**
     * 文章详情页
     * 文章 回复 删除
     * @param int $id | 回复的postID
     */
    public function article_reply_delete($id){
        //检查修改帖子
        $obj_article_indexing=load("article_indexing");
        $check_article_indexing=$obj_article_indexing->getOne(['id','postID','treelevel','userID'],['postID'=>$id]);
        if(empty($check_article_indexing)) {$this->error="删除的帖子不存在";$this->status=false;return false;}
        
        //更新帖子状态
        $obj_article_indexing->update(['visible'=>0],['postID'=>$check_article_indexing['postID']]);
        
        //同步ES索引
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_noindex->fetch_and_insert([$article_data['postID']]);
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
     * 文章 删除
     * @param integer $postID | 文章的postID
     */
    public function article_delete($postID){
        $obj_article_indexing=load("article_indexing");
        $time=times::gettime();
        $obj_article_indexing->update(['visible'=>0,"edit_date"=>$time],['postID'=>$postID]);
        
        //清除ES
        
        
        return true;
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
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    //   /vue/vue.config.js
    
    
    
    
    
    
    
    
    
    
    
}





































