<?php
class user extends Api {

    public $space = true;
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * 编辑器页
     * 文章 查看
     * @param integer $id | 主贴postID
     */
    public function article_view($id){
        $obj_article_indexing=load("article_indexing");
        
        $rs_article_indexing=$obj_article_indexing->getOne("*",['visible'=>1,'postID'=>$id]);
        if(empty($rs_article_indexing)){$this->error="此文章不存在";$this->status=false;return false;}
        
        //ES补全postID信息
        $obj_article_noindex=load("search_article_noindex");
        $rs_article_indexing=$obj_article_noindex->get_postInfo([$rs_article_indexing],'postID',true);
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $rs_article_indexing=$obj_account_user->get_basic_userinfo($rs_article_indexing,"userID")[0];
        
        return $rs_article_indexing;
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
        if(!$obj_article_indexing->article_add_validation(array_merge($article_data+$module_data)))   {$this->error="发帖验证未通过";$this->status=false;return false;}
        
        //添加文章 post
        $obj_article_post=load("article_post");
        $article_data['postID']=$obj_article_post->get_id();
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
            $obj_blog_blogger->to_blog_article($article_data['postID'],$module_data);
        }
        
        //同步ES索引
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_noindex->fetch_and_insert([$article_data['postID']]);
        
        //删除草稿
        if(!empty($article_data['draftID'])){
            $obj_article_draft=load("article_draft");
            $obj_article_draft->remove(['id'=>$article_data['draftID']]);
        }
        
        //显示当前插入信息
        return $this->article_view($article_data['postID']);
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
            $obj_blog_blogger->to_blog_article($article_data['postID'],$module_data);
        }
        
        //同步ES索引
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_noindex->fetch_and_insert([$rs_article_post['postID']]);
        
        //删除草稿
        if(!empty($article_data['draftID'])){
            $obj_article_draft=load("article_draft");
            $obj_article_draft->remove(['id'=>$article_data['draftID']]);
        }
        
        return $this->article_view($article_data['postID']);
    }
    
    /**
     * 编辑器页
     * 文章 草稿 添加
     * @param integer $id | 编辑帖子id
     */
    public function article_to_draft_by_postID($id){
        $obj_article_indexing=load("article_indexing");
        
        $rs_article_indexing=$obj_article_indexing->getOne(['postID','categoryID','userID','bloggerID','create_date','edit_date','typeID'],['visible'=>1,'postID'=>$id]);
        if(empty($rs_article_indexing)) {$this->error="此文章不存在";$this->status=false;return false;}
        
        //ES补全postID信息
        $obj_article_noindex=load("search_article_noindex");
        $rs_article_indexing=$obj_article_noindex->get_postInfo([$rs_article_indexing],'postID',true)[0];
        
        $obj_article_draft=load("article_draft");
        $check_article_draft=$obj_article_draft->getOne(['id'],['postID'=>$id]);
        if(!empty($check_article_draft)) {$this->error="此草稿已经存在";$this->status=false;return false;}
        
        //字符串tagID
        if(!empty($rs_article_indexing['postInfo_postID']['tags'])){
            foreach($rs_article_indexing['postInfo_postID']['tags'] as $v){
                $tagID[]=$v['id'];
            }
            $tagID=implode(",",$tagID);
        }
        
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
            "visible"=>-2
        ];
        $obj_article_draft->insert($fields);
        
        return $this->article_view($rs_article_indexing['postID']);
    }
    
    /**
     * 编辑器页
     * 文章 草稿 显示
     * @param integer $id | 草稿id
     */
    public function draft_view($id){
        $obj_article_draft=load("article_draft");
        $check_article_draft=$obj_article_draft->getOne("*",['id'=>$id]);
        if(empty($check_article_draft)) {$this->error="此文章草稿不存在";$this->status=false;return false;}
        
        //格式化草稿结构
        $rs_article_draft=$obj_article_draft->format_draft([$check_article_draft]);
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $rs_article_draft=$obj_account_user->get_basic_userinfo($rs_article_draft,"userID")[0];
        return $rs_article_draft;
    }
    
    /**
     * 编辑器页
     * 文章 草稿 添加
     * @param obj $article_data | 文章的数据
     * @param obj $module_data | 组件的数据
     * @post article_data,module_data
     * @response /article/api_response/draft_add.txt
     */
    public function draft_add($article_data="",$module_data=""){
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
            "visible"=>-1
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
     * @response /article/api_response/draft_update.txt
     */
    public function draft_update($article_data,$module_data){
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
    public function draft_delete($id){
        $obj_article_draft=load("article_draft");
        $obj_article_draft->remove(['id'=>$id]);
        
        return true;
    }
    
    /**
     * 文章详情页
     * 文章 回复 添加
     * @param obj $article_data | 文章的数据
     * @post article_data
     * @response /article/api_response/reply_add.txt
     */
    public function reply_add($article_data){
        //检查主贴
        $obj_article_indexing=load("article_indexing");
        $check_article_indexing=$obj_article_indexing->getOne(['id','postID','treelevel','userID','basecode'],['postID'=>$article_data['postID']]);
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
        $id=$obj_article_post->insert($fields_post,"post_{$post_tbn}");
         
        //更新主贴
        $check_main_article_indexing=$obj_article_indexing->getOne(['postID','count_comment'],['postID'=>$check_article_indexing['basecode']]);
        $obj_article_indexing->update(['comment_date'=>$time,'count_comment'=>$check_main_article_indexing['count_comment']+1],['postID'=>$check_article_indexing['basecode']]);
        
        //同步ES索引
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_noindex->fetch_and_insert([$postID,$check_main_article_indexing['postID']]);
        
        //添加消息列表
        $obj_account_notification=load("account_notification");
        $obj_account_notification->notification_add($check_article_indexing['userID'],'reply',$id,"add");
        
        return true;
    }
    
    /**
     * 文章详情页
     * 文章 回复 修改
     * @param obj $article_data | 文章的数据
     * @post article_data
     * @response /article/api_response/reply_update.txt
     */
    public function reply_update($article_data){
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
        
        return true;
    }
    
    /**
     * 文章详情页
     * 文章 回复 删除
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
        $check_main_article_indexing=$obj_article_indexing->getOne(['postID','count_comment','treelevel'],['postID'=>$check_article_indexing['basecode']]);
        if($check_main_article_indexing['treelevel']==2){
            $check_main_article_indexing=$obj_article_indexing->getOne(['postID','count_comment','treelevel'],['postID'=>$check_article_indexing['basecode']]);
        }
        $obj_article_indexing->update(['count_comment'=>$check_main_article_indexing['count_comment']-1],['postID'=>$check_article_indexing['basecode']]);
        
        //同步ES索引
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_noindex->fetch_and_insert([$id,$check_main_article_indexing['postID']]);
        
        return true;
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
     * 文章 删除
     * @param integer $postID | 文章的postID
     * @param integer $visible | 1开 0关
     */
    public function article_delete($postID,$visible){
        $obj_article_indexing=load("article_indexing");
        $time=times::gettime();
        $obj_article_indexing->update(['visible'=>!empty($visible)?1:0,"edit_date"=>$time],['postID'=>$postID]);
        
        //同步ES索引
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_noindex->fetch_and_insert([$postID]);
        
        return true;
    }
    
    /**
     * 编辑器页
     * 文章 发布 
     * @param integer $draftID | 文章的draftID
     */
    public function draft_to_article_by_draftID($draftID){
        $obj_article_draft=load("article_draft");
        $rs_article_draft=$obj_article_draft->getOne("*",['userID'=>$_SESSION['id'],'id'=>$draftID]);
        if(empty($rs_article_draft)) {$this->error="发布的文章不存在";$this->status=false;return false;}
        
        //获取标签名字 "1,2" -> ["标签1","标签2"]
        $obj_article_tag=load("article_tag");
        $tagname=$obj_article_tag->get_article_tag_name($rs_article_draft);
        $article_data=[
            'title'=>$rs_article_draft['title'],
            'msgbody'=>$rs_article_draft['msgbody'],
            'tagname'=>$tagname,
            "typeID"=>$rs_article_draft['typeID'],
            "draftID"=>$rs_article_draft['id'],
        ];
        $module_data=[
            "add"=>true,
            "bloggerID"=>$rs_article_draft['bloggerID'],
            "categoryID"=>$rs_article_draft['categoryID'],
        ];
        
        //添加文章
        if($rs_article_draft['visible']==-1){
            $this->article_add($article_data,$module_data);
        }
        if($rs_article_draft['visible']==-2){
            $article_data['postID']=$rs_article_draft['postID'];
            $this->article_update($article_data,$module_data);
        }
        return true;
    }
    
    /**
     * 编辑器页
     * 文章 定时 添加
     * @param integer $draftID | 文章的draftID
     * @param integer $is_timer | 1开 0关
     * @param integer $time | 延时 3600秒
     */
    public function article_timer($draftID,$is_timer,$time){
        $obj_article_draft=load("article_draft");
        $rs_article_draft=$obj_article_draft->getOne("*",['userID'=>$_SESSION['id'],'id'=>$draftID]);
        if(empty($rs_article_draft)) {$this->error="发布的文章不存在";$this->status=false;return false;}
        
        $obj_article_timer=load("article_timer");
        $publish_date=times::gettime()+$time;
        $obj_article_draft->update(['publish_date'=>$publish_date,'is_timer'=>!empty($is_timer)?1:0],['id'=>$draftID]);
        return true;
    }
    
    /**
     * 编辑器页
     * 文章 置顶 添加
     * @param integer $postID | 文章的postID
     * @param integer $is_sticky | 1开 0关
     */
    public function article_sticky($postID,$is_sticky){
        $obj_article_indexing=load("article_indexing");
        $check_article_indexing=$obj_article_indexing->getOne(['id'],['userID'=>$_SESSION['id'],'postID'=>$postID]);
        if(empty($check_article_indexing)) {$this->error="置顶的文章不存在";$this->status=false;return false;}
        
        $obj_article_indexing->update(['is_sticky'=>!empty($is_sticky)?1:0],['id'=>$check_article_indexing['id']]);
        return true;
    }
    
    /**
     * 编辑器页
     * 文章 移动 文集
     * @param integer $postID | 文章的postID
     * @param integer $categoryID | 文集的id
     */
    public function article_shift_category($postID,$categoryID){
        $obj_article_indexing=load("article_indexing");
        $check_article_indexing=$obj_article_indexing->getOne(['id'],['userID'=>$_SESSION['id'],'postID'=>$postID]);
        if(empty($check_article_indexing)) {$this->error="移动的文章不存在";$this->status=false;return false;}
        
        $obj_blog_category=load("blog_category");
        $check_blog_category=$obj_blog_category->getOne(["id","count_article"],['id'=>$categoryID]);
        if(empty($check_article_indexing)) {$this->error="移动的文集不存在";$this->status=false;return false;}
        
        $obj_article_indexing->update(['categoryID'=>$categoryID],['postID'=>$postID]);
        $obj_blog_category->update(['count_article'=>$check_blog_category['count_article']+1]);
        
        //同步ES索引
        $obj_article_noindex=load("search_article_noindex");
        $obj_article_noindex->fetch_and_insert([$postID]);
        
        return true;
    }
    
    /**
     * 编辑器页
     * 草稿 移动 文集
     * @param integer $draftID | 草稿的id
     * @param integer $categoryID | 文集的id
     */
    public function draft_shift_category($draftID,$categoryID){
        $obj_article_draft=load("article_draft");
        $check_article_draft=$obj_article_draft->getOne(['id'],['userID'=>$_SESSION['id'],'id'=>$draftID]);
        if(empty($check_article_draft)) {$this->error="移动的草稿不存在";$this->status=false;return false;}
        
        $obj_blog_category=load("blog_category");
        $check_blog_category=$obj_blog_category->getOne(["id"],['id'=>$categoryID]);
        if(empty($check_article_indexing)) {$this->error="移动的文集不存在";$this->status=false;return false;}
        
        $obj_article_draft->update(['categoryID'=>$categoryID],['id'=>$draftID]);
        return true;
    }
    
    /**
     * 编辑器页
     * 文章 私密
     * @param integer $postID | 文章的postID
     * @param integer $is_publish | 1开 0关
     */
    public function article_publish($postID,$is_publish){
        $obj_article_indexing=load("article_indexing");
        $check_article_indexing=$obj_article_indexing->getOne(['id'],['userID'=>$_SESSION['id'],'postID'=>$postID]);
        if(empty($check_article_indexing)) {$this->error="设置私密文章不存在";$this->status=false;return false;}
        
        $obj_article_indexing->update(['is_publish'=>!empty($is_publish)?1:0],['id'=>$check_article_indexing['id']]);
        return true;
    }
    
    /**
     * 编辑器页
     * 文章 禁止 评论
     * @param integer $postID | 文章的postID
     * @param integer $is_comment | 1开 0关
     */
    public function article_comment($postID,$is_comment){
        $obj_article_indexing=load("article_indexing");
        $check_article_indexing=$obj_article_indexing->getOne(['id'],['userID'=>$_SESSION['id'],'postID'=>$postID]);
        if(empty($check_article_indexing)) {$this->error="禁止评论的文章不存在";$this->status=false;return false;}
        
        $obj_article_indexing->update(['is_comment'=>!empty($is_comment)?1:0],['id'=>$check_article_indexing['id']]);
        return true;
    }
    
    /**
     * 编辑器页
     * 文章 禁止 转载
     * @param integer $postID | 文章的postID
     * @param integer $is_share | 1开 0关
     */
    public function article_share($postID,$is_share){
        $obj_article_indexing=load("article_indexing");
        $check_article_indexing=$obj_article_indexing->getOne(['id'],['userID'=>$_SESSION['id'],'postID'=>$postID]);
        if(empty($check_article_indexing)) {$this->error="禁止转载的文章不存在";$this->status=false;return false;}
        
        $obj_article_indexing->update(['is_share'=>!empty($is_share)?1:0],['id'=>$check_article_indexing['id']]);
        return true;
    }
    
    /**
     * @param string $type
     * @param string $file
     * @post file
     */
    public function upload_file($type, $file){
        if($type == "pic"){
            $pic_obj = load("article_pic");
            $url = $pic_obj -> save_picture($file);
        }
        elseif($type == 'media'){
            $pic_obj = load("article_pic");
            $url = $pic_obj -> save_media($file);
        }
        return $url;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}





































