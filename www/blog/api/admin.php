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
     * @param string $type
     * @param integer $lastID
     */
    public function article_list($type="recent",$lastID=0){
        $obj_article_indexing=load("article_indexing");
        //最新
        if($type=="recent"){
            $fields=['limit'=>40,'treelevel'=>0,'order'=>['postID'=>"DESC"]];
            if(!empty($lastID)){
                $fields['postID,<']=$lastID;
            }
            $rs_article_indexing=$obj_article_indexing->getAll("*",$fields);
        }
        
        //推荐
        if($type=="recommand"){
            $obj_blog_recommend=load("blog_recommend");
            $fields=["limit"=>40,'order'=>['id'=>'DESC']];
            if(!empty($lastID)){
                $fields['postID,<']=$lastID;
            }
            $rs_article_indexing=$obj_blog_recommend->getAll("*",$fields);
        }
        
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
    
    /**
     * 文章详情页
     * 文章 评论
     * @param integer $id | 主贴postID
     * @param integer $lastID | 评论最后一个postID
     */
    public function article_view_comment($id,$lastID=0){
        $obj_article_indexing=load("article_indexing");
        $check_article_indexing=$obj_article_indexing->getOne(['postID','basecode','userID','bloggerID','create_date','edit_date','treelevel'],['visible'=>1,'postID'=>$id]);
        if(empty($check_article_indexing)){$this->error="此文章不存在";$this->status=false;return false;}
        
        //评论
        $fields=[
            'treelevel,!='=>0,
            'order'=>['postID'=>'DESC'],
            'basecode'=>$check_article_indexing['postID'],
            'limit'=>20,
        ];
        
        if(!empty($lastID)){
            $fields['postID,<']=$lastID;
        }
        $rs_article_indexing=$obj_article_indexing->getAll(['postID','basecode','userID','bloggerID','create_date','edit_date','treelevel','visible'],$fields);
        if(empty($rs_article_indexing)){
            return $rs_article_indexing;
        }
        
        //补全二层评论
        foreach($rs_article_indexing as $k=>$v){
            $basecode_article_indexing[$v['postID']]=$v['postID'];
        }
        $rs_article_reply=$obj_article_indexing->getAll(['postID','basecode','userID','bloggerID','create_date','edit_date','treelevel','visible'],['treelevel'=>2,'OR'=>['basecode'=>$basecode_article_indexing],'order'=>['postID'=>'DESC']]);
        if(!empty($rs_article_reply)){
            //ES补全postID信息
            $obj_article_noindex=load("search_article_noindex");
            $rs_article_reply=$obj_article_noindex->get_postInfo($rs_article_reply,'postID',true);
            
            //添加用户信息
            $obj_account_user=load("account_user");
            $rs_article_reply=$obj_account_user->get_basic_userinfo($rs_article_reply,"userID");
            
            //添加计数信息
            $rs_article_reply=$obj_article_indexing->get_article_count($rs_article_reply);
            foreach($rs_article_reply as $v){
                $hash_article_reply[$v['basecode']][]=$v;
            }
            foreach($rs_article_indexing as $k=>$v){
                $rs_article_indexing[$k]['reply']=empty($hash_article_reply[$v['postID']])?[]:$hash_article_reply[$v['postID']];
            }
        }
        
        //ES补全postID信息
        $obj_article_noindex=load("search_article_noindex");
        $rs_article_indexing=$obj_article_noindex->get_postInfo($rs_article_indexing,'postID',true);
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $rs_article_indexing=$obj_account_user->get_basic_userinfo($rs_article_indexing,"userID");
        
        //添加计数信息
        $rs_article_indexing=$obj_article_indexing->get_article_count($rs_article_indexing);
        
        return $rs_article_indexing;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}





































