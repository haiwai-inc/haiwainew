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
     * @param obj $module_data | 博客的数据
     * @post article_data,module_data
     */
    public function article_add($article_data,$module_data){
        /*
        $article_data=[
            'title'=>"用户3主贴标题",
            'msgbody'=>"用户3主贴内容",
            'tagname'=>[
                "测试用户1",
                "测试用户2"
            ],
            "typeID"=>1,
        ];
        $module_data=[
            "add"=>true,
            "bloggerID"=>3,
            "categoryID"=>1,
        ];
        */
        
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
        if(!empty($article_data['tagname'])){
            $obj_article_tag=load("article_tag");
            $obj_article_post_tag=load("article_post_tag");
            
            $post_tag_tbn=substr('0'.$article_data['postID'],-1);
            foreach($article_data['tagname'] as $v){
                $check_article_tag=$obj_article_tag->getOne("*",['name'=>$v]);
                if(empty($check_article_tag)){
                    $check_article_tag['id']=$obj_article_tag->insert(['name'=>$v]);
                }else{
                    $obj_article_tag->update(['count_article'=>$check_article_tag['count_article']+1],['id'=>$check_article_tag['id']]);
                }
                
                $post_tagID=$obj_article_post_tag->get_id();
                $fields_post_tag=[
                    "id"=>$post_tagID,
                    "postID"=>$article_data['postID'],
                    "tagID"=>$check_article_tag['id'],
                ];
                $obj_article_post_tag->insert($fields_post_tag,"post_tag_".$post_tag_tbn);
            }
        }
        
        //转文章为博客类型
        if($article_data['typeID']==1){
            $obj_blog_blogger=load("blog_blogger");
            $obj_blog_blogger->to_blog_article($module_data);
        }
        
        //同步ES索引
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_noindex->fetch_and_insert([$article_data['postID']]);
        
        return true;
    }
    
    /**
     * 编辑器页
     * 文章 修改
     * @param obj $article_data | 文章的数据
     * @param obj $module_data | 博客的数据
     * @post article_data,module_data
     */
    public function article_update($article_data,$module_data){
        /*
        $article_data=[
            'title'=>"用户3主贴标题修改",
            'msgbody'=>"用户3主贴内容修改",
            'tagname'=>[
                "测试用户1",
                "测试用户3"
            ],
            "postID"=>144816,
            "typeID"=>1,
        ];
        $module_data=[
            "edit"=>true,
            "bloggerID"=>3,
            "categoryID"=>1,
        ];
        */
        
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
        
        //修改文章 tag
        if(!empty($article_data['tagname'])){
            $obj_article_tag=load("article_tag");
            $obj_article_post_tag=load("article_post_tag");
            
            $post_tag_tbn=substr('0'.$article_data['postID'],-1);
            $obj_article_post_tag->remove(['postID'=>$rs_article_post['postID']],"post_tag_".$post_tag_tbn);
            foreach($article_data['tagname'] as $v){
                $check_article_tag=$obj_article_tag->getOne("*",['name'=>$v]);
                if(empty($check_article_tag)){
                    $check_article_tag['id']=$obj_article_tag->insert(['name'=>$v]);
                }else{
                    $obj_article_tag->update(['count_article'=>$check_article_tag['count_article']+1],['id'=>$check_article_tag['id']]);
                }
                
                $post_tagID=$obj_article_post_tag->get_id();
                $fields_post_tag=[
                    "id"=>$post_tagID,
                    "postID"=>$article_data['postID'],
                    "tagID"=>$check_article_tag['id'],
                ];
                $obj_article_post_tag->insert($fields_post_tag,"post_tag_".$post_tag_tbn);
            }
        }
        
        //修改博客类型文章
        if($article_data['typeID']==1){
            $obj_blog_blogger=load("blog_blogger");
            $obj_blog_blogger->to_blog_article($module_data);
        }
        
        //同步ES索引
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_noindex->fetch_and_insert([$rs_article_post['postID']]);
        
        return true;
    }
    
    /**
     * 文章详情页
     * 文章 回复
     * @param obj $article_data | 文章的数据
     * @post article_data
     */
    public function article_reply($article_data){
        /*
         $article_data=[
             'msgbody'=>"用户6回复回复内容",
             'postID'=>144819,
             "typeID"=>1,
         ];
         */
         
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





































