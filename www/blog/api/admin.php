<?php
class admin extends Api {
    public $space = true;
    public $admin = true;
    
    public function __construct() {
        parent::__construct();
        $this->sess = true;
    }

    public function index(){
        
    }
    
    /**
     * 每日更新文章
     * @param integer $lastID
     */
    public function article_list($lastID=0){
        //最新帖子
        $obj_article_indexing=load("article_indexing");
        $fields=['limit'=>40,'treelevel'=>0,'visible'=>1,'order'=>['postID'=>"DESC"]];
        if(!empty($lastID)){
            $fields['postID,<']=$lastID;
        }
        $rs_article_indexing=$obj_article_indexing->getAll("*",$fields);
        
        //标注推荐
        if(!empty($rs_article_indexing)){
            $obj_blog_recommend=load("blog_recommend");
            foreach($rs_article_indexing as $v){
                $tmp_article_indexing[]=$v['postID'];
            }
            $rs_blog_recommend=$obj_blog_recommend->getAll("*",['OR'=>['postID'=>$tmp_article_indexing]]);
            if(!empty($rs_blog_recommend)){
                foreach($rs_blog_recommend as $v){
                    $hash_blog_recommend[$v['postID']]=$v;
                }
            }
            foreach($rs_article_indexing as $k=>$v){
                if(!empty($hash_blog_recommend[$v['postID']])){
                    $rs_article_indexing[$k]['recommend']=$hash_blog_recommend[$v['postID']];
                }
            }
        }
        
        //ES补全postID信息
        $obj_article_noindex=load("search_article_noindex");
        $rs_article_indexing=$obj_article_noindex->get_postInfo($rs_article_indexing);
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $rs_article_indexing=$obj_account_user->get_basic_userinfo($rs_article_indexing,"userID");
        
        //添加文章计数信息
        $rs_article_indexing=$obj_article_indexing->get_article_count($rs_article_indexing);
        return $rs_article_indexing;
    }
    
    
    /**
     * 文章 推荐 添加
     * @param integer $postID
     */
    public function article_recommand_add($postID){
        $obj_blog_recommend=load("blog_recommend");
        $check_blog_recommend=$obj_blog_recommend->getOne("*",['postID'=>$postID]);
        if(!empty($check_blog_recommend))   {$this->error="此文章已经推荐了";$this->status=false;return false;}
        
        $obj_article_indexing=load("article_indexing");
        $check_article_indexing=$obj_article_indexing->getOne("*",['postID'=>$postID]);
        if(empty($check_article_indexing))   {$this->error="此文章不存在";$this->status=false;return false;}
        
        //ES补全postID信息
        $obj_article_noindex=load("search_article_noindex");
        $rs_article_indexing=$obj_article_noindex->get_postInfo([$check_article_indexing]);
        $rs_article_indexing=empty($rs_article_indexing)?[]:$rs_article_indexing[0];
        
        $time=times::gettime();
        $obj_blog_recommend->insert([
            'create_date'=>$time,
            'postID'=>$rs_article_indexing['postID'],
            'userID'=>$rs_article_indexing['userID'],
            'title'=>$rs_article_indexing['postInfo_postID']['title']
        ]);
        
        return true;
    }
    
    /**
     * 文章 推荐 撤销
     * @param integer $postID
     */
    public function article_recommand_delete($postID){
        $obj_blog_recommend=load("blog_recommend");
        $check_blog_recommend=$obj_blog_recommend->remove(['postID'=>$postID]);
        return true;        
    }
    
    /**
     * 文章 推荐 修改
     * @param integer $postID
     * @param integer $title
     */
    public function article_recommand_update($postID,$title){
        $obj_blog_recommend=load("blog_recommend");
        $check_blog_recommend=$obj_blog_recommend->update(['title'=>$title],['postID'=>$postID]);
        return true;     
    }
    
    
    
    /**
     * 博主 热门
     */
    public function blogger_hot(){
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}





































