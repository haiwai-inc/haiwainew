<?php
class page extends Api {
    
    public function __construct() {
        parent::__construct();
        $this->sess = true;
    }
    
    /**
     * “别人的”博主信息页
     * 博主 信息
     * @param integer $bloggerID
     */
    public function blogger_info($bloggerID){
        if(empty($bloggerID))   {$this->error="此博主不存在";$this->status=false;return false;}
        
        $obj_blog_blogger=load("blog_blogger");
        $rs_blog_blogger=$obj_blog_blogger->getAll(['id','userID'],['id'=>$bloggerID,'status'=>1]);
        if(empty($rs_blog_blogger)) {$this->error="此博主不存在";$this->status=false;return false;}
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $rs_blog_blogger=$obj_account_user->get_basic_userinfo($rs_blog_blogger,"userID");
        
        //添加博主信息
        $rs_blog_blogger=$obj_blog_blogger->get_basic_bloggerinfo($rs_blog_blogger,"id");
        
        return $rs_blog_blogger[0];
    }
    
    /**
     * 二级页面 
     * 推荐 文章
     * @param integer $lastID | 最后一个id
     */
    public function recommend_article($lastID=0){
        $obj_blog_recommend=load("blog_recommend");
        $obj_search_article_noindex=load("search_article_noindex");
        
        $fields=[
            "limit"=>30,
            'order'=>['id'=>'DESC']
        ];
        if(!empty($lastID)){
            $fields['id,<']=$lastID;
        }
        
        $rs_blog_recommend=$obj_blog_recommend->getAll("*",$fields);
        if(empty($rs_blog_recommend)){
            $rs=['status'=>false,'msg'=>"",'data'=>""];
            return $rs;
        }
        foreach($rs_blog_recommend as $k=>$v){
            $postID_blog_recommend[]=$v['postID'];
        }
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $rs_blog_recommend=$obj_account_user->get_basic_userinfo($rs_blog_recommend,"userID");
        
        //添加ES信息
        $rs_blog_recommend=$obj_search_article_noindex->get_postInfo($rs_blog_recommend);
        
        //添加文章计数信息
        $obj_article_indexing=load("article_indexing");
        $rs_blog_recommend=$obj_article_indexing->get_article_count($rs_blog_recommend);
        
        return $rs_blog_recommend;
    }
    
    /**
     * 二级页面 
     * 推荐 博主
     */
    public function recommand_blogger(){
        $obj_memcache = func_initMemcached('cache01');
        $rs_memcache = $obj_memcache->get("blog_hot_blogger");
        
        if(empty($rs_memcache)){
            $rs=['status'=>false,'msg'=>"",'data'=>""];
            return $rs;
        }
        
        $rs=['status'=>true,'error'=>"",'data'=>$rs_memcache];
        return $rs;
    }
    
    /**
     * 二级页面 
     * 热榜 标签
     */
    public function hot_tag(){
        $obj_memcache = func_initMemcached('cache01');
        $rs_memcache = $obj_memcache->get("blog_hot_tag");
        
        if(empty($rs_memcache)){
            $rs=['status'=>false,'msg'=>"",'data'=>""];
            return $rs;
        }
        
        //全部
        $rs_memcache[0]=["id"=>0,"name"=>"全部",'visible'=>1];
        $rs=['status'=>true,'error'=>"",'data'=>$rs_memcache];
        return $rs;
    }
    
    /**
     * 二级页面 
     * 热榜 文章
     * @param integer $tagID | 标签ID
     */
    public function hot_article($tagID=0){
        $obj_article_indexing=load("article_indexing");
        $obj_article_noindex=load("search_article_noindex");
        
        $obj_memcache = func_initMemcached('cache01');
        $rs_memcache=$obj_memcache->get("blog_hot_article");
        
        $rs=empty($rs_memcache[$tagID])?[]:$rs_memcache[$tagID];
        return $rs;
    }
    
    /**
     * 文章详情页侧栏
     * 文章 列表 标签
     * @param integer $tagID | 标签ID,标签ID,标签ID
     * @param integer $lastID | postID
     */
    public function article_list_tag($tagID,$lastID=0){
        //ES搜索tag
        $obj_article_index=load("search_article_index");
        $rs_article_index=$obj_article_index->search_tags([$tagID],$lastID,["postID"=>array("order"=>"desc")]);
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $rs_article_index=$obj_account_user->get_basic_userinfo($rs_article_index,"userID");
        
        //添加文章计数信息
        $obj_article_indexing=load("article_indexing");
        $rs_article_index=$obj_article_indexing->get_article_count($rs_article_index);
        
        return $rs_article_index;
    }
    
    /**
     * 博客主页 编辑器页
     * 文章 列表 最新
     * @param integer $bloggerID
     * @param integer $lastID | 最后一个postID
     */
    public function article_list_recent($bloggerID,$lastID=0){
        if(empty($bloggerID))   {$this->error="此博主不存在";$this->status=false;return false;}
        
        $obj_blog_blogger=load("blog_blogger");
        $rs_blog_blogger=$obj_blog_blogger->getOne(['id','userID'],['status'=>1]);
        if(empty($rs_blog_blogger)) {$this->error="此博主不存在";$this->status=false;return false;}
        
        $obj_article_indexing=load("article_indexing");
        $fields=[
            'limit'=>30,
            'bloggerID'=>$bloggerID,
            'order'=>['edit_date'=>'DESC']
        ];
        if(!empty($lastID)){
            $fields['postID,<']=$lastID;
        }
        $rs_article_indexing=$obj_article_indexing->getAll(["userID","postID","create_date"],$fields);
        
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
     * 博客主页 二级页面
     * 文章 列表 最热
     * @param integer $bloggerID
     * @param integer $lastID | 最后一个postID
     */
    public function article_list_hot($bloggerID,$lastID=0){
        if(empty($bloggerID))   {$this->error="此博主不存在";$this->status=false;return false;}
        
        $obj_blog_blogger=load("blog_blogger");
        $rs_blog_blogger=$obj_blog_blogger->getOne(['id','userID'],['status'=>1]);
        if(empty($rs_blog_blogger)) {$this->error="此博主不存在";$this->status=false;return false;}
        
        $obj_article_indexing=load("article_indexing");
        $fields=[
            'limit'=>30,
            'bloggerID'=>$bloggerID,
            'order'=>['count_read'=>'DESC']
        ];
        if(!empty($lastID)){
            $fields['postID,<']=$lastID;
        }
        $rs_article_indexing=$obj_article_indexing->getAll(["userID","postID"],$fields);
        
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
     * 博客主页 二级页面
     * 文章 列表 新评
     * @param integer $bloggerID
     * @param integer $lastID | 最后一个postID
     */
    public function article_list_comment($bloggerID,$lastID=0){
        if(empty($bloggerID))   {$this->error="此博主不存在";$this->status=false;return false;}
        
        $obj_blog_blogger=load("blog_blogger");
        $rs_blog_blogger=$obj_blog_blogger->getOne(['id','userID'],['status'=>1]);
        if(empty($rs_blog_blogger)) {$this->error="此博主不存在";$this->status=false;return false;}
        
        $obj_article_indexing=load("article_indexing");
        $fields=[
            'treelevel'=>1,
            'limit'=>30,
            'bloggerID'=>$bloggerID,
            'order'=>['comment_date'=>'DESC']
        ];
        if(!empty($lastID)){
            $fields['postID,<']=$lastID;
        }
        $rs_article_indexing=$obj_article_indexing->getAll(["userID","postID"],$fields);
        
        return $rs_article_indexing;
    }
    
    /**
     * 博客主页 二级页面
     * 新评 详情 最新
     */
    public function comment_view_recent(){
        
    }
    
    /**
     * 文章详情页 编辑器页
     * 文章 详情
     * @param integer $id | 主贴postID
     */
    public function article_view($id){
        $obj_article_indexing=load("article_indexing");
        
        $rs_article_indexing=$obj_article_indexing->getOne(['postID','basecode','userID','bloggerID','create_date','edit_date','treelevel'],['visible'=>1,'postID'=>$id]);
        if(empty($rs_article_indexing)){$this->error="此文章不存在";$this->status=false;return false;}
        
        //ES补全postID信息
        $obj_article_noindex=load("search_article_noindex");
        $rs_article_indexing=$obj_article_noindex->get_postInfo([$rs_article_indexing],'postID',true);
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $rs_article_indexing=$obj_account_user->get_basic_userinfo($rs_article_indexing,"userID");
        
        //添加文章计数信息
        $rs_article_indexing=$obj_article_indexing->get_article_count($rs_article_indexing)[0];
        
        //添加上下文章
        $rs_article_indexing['article_previous_next']=$this->article_previous_next($id);
        
        return $rs_article_indexing;
    }
    
    /**
     * 文章详情页 
     * 文章 前一篇 后一篇
     * @param integer $id | 主贴postID
     */
    public function article_previous_next($id){
        $rs=["category"=>[],"previous"=>[],"next"=>[]];
        
        //原帖信息
        $obj_article_indexing=load("article_indexing");
        $rs_article_indexing=$obj_article_indexing->getOne("*",['postID'=>$id]);
        if(empty($rs_article_indexing)){
            return $rs;
        }
        
        //文集信息
        $obj_blog_category=load("blog_category");
        $rs_blog_category=$obj_blog_category->getOne(['id','name'],['id'=>$rs_article_indexing['categoryID']]);
        if(empty($rs_blog_category)){
            return $rs;
        }
        
        $rs['category']=$rs_blog_category;
        
        //上一篇
        $obj_article_indexing=load("article_indexing");
        $fields['postID,<']=$rs_article_indexing['postID'];
        $fields['order']=['postID'=>'DESC'];
        $rs_article['previous']=$obj_article_indexing->getOne("*",['treelevel'=>0,'visible'=>1,'userID'=>$rs_article_indexing['userID'],'categoryID'=>$rs_blog_category['id'],'postID,<'=>$rs_article_indexing['postID'],"order"=>['postID'=>'DESC']]);
        
        //下一篇
        $fields['postID,>']=$rs_article_indexing['postID'];
        $fields['order']=['postID'=>'ASC'];
        $rs_article['next']=$obj_article_indexing->getOne("*",['treelevel'=>0,'visible'=>1,'userID'=>$rs_article_indexing['userID'],'categoryID'=>$rs_blog_category['id'],'postID,>'=>$rs_article_indexing['postID'],"order"=>['postID'=>'ASC']]);
        
        //上下文信息
        $obj_article_noindex=load("search_article_noindex");
        $rs_article=$obj_article_noindex->get_postInfo($rs_article,'postID',true);
        
        $rs['previous']=empty($rs_article['previous'])?[]:$rs_article['previous'];
        $rs['next']=empty($rs_article['next'])?[]:$rs_article['next'];
        
        return $rs;
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
            'visible'=>1,
            'basecode'=>$check_article_indexing['postID'],
            'limit'=>20,
        ];
        
        if(!empty($lastID)){
            $fields['postID,<']=$lastID;
        }
        $rs_article_indexing=$obj_article_indexing->getAll(['postID','basecode','userID','bloggerID','create_date','edit_date','treelevel'],$fields);
        if(empty($rs_article_indexing)){
            return $rs_article_indexing;
        }
        
        //补全二层评论
        foreach($rs_article_indexing as $k=>$v){
            $basecode_article_indexing[$v['postID']]=$v['postID'];
        }
        $rs_article_reply=$obj_article_indexing->getAll(['postID','basecode','userID','bloggerID','create_date','edit_date','treelevel'],['treelevel'=>2,'visible'=>1,'OR'=>['basecode'=>$basecode_article_indexing],'order'=>['postID'=>'DESC']]);
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
    
    /**
     * 文章详情页
     * 文章 评论 一条
     * @param integer $id | 1级回复postID
     */
    public function article_view_comment_one($id){
        $obj_article_indexing=load("article_indexing");
        $rs_article_indexing=$obj_article_indexing->getOne(['postID','basecode','userID','bloggerID','create_date','edit_date','treelevel'],['visible'=>1,'postID'=>$id]);
        if(empty($rs_article_indexing)){$this->error="此回复不存在";$this->status=false;return false;}
        
        //补全主贴
        $rs_article_indexing=$this->article_view($rs_article_indexing['postID']);
        
        //补全跟帖
        $rs_article_indexing['reply']=$this->article_view_comment($id);
        
        return $rs_article_indexing;
    }
    
    /**
     * 文章详情页
     * 文章 最新推荐
     */
    public function article_view_recent($id){
        $obj_article_indexing=load("article_indexing");
        $check_article_indexing=$obj_article_indexing->getOne("*",['visible'=>1,'treelevel'=>0,'postID'=>$id]);
        if(empty($check_article_indexing)) {$this->error="此文章不存在";$this->status=false;return false;}
        
        //作者最新文章
        $rs_article_indexing=$obj_article_indexing->getAll(['id','postID'],['postID,!='=>$check_article_indexing['postID'],'order'=>['postID'=>"DESC"],'limit'=>5,'visible'=>1,'treelevel'=>0,'userID'=>$check_article_indexing['userID']]);
        
        //ES补全postID信息
        $obj_article_noindex=load("search_article_noindex");
        $rs_article_indexing=$obj_article_noindex->get_postInfo($rs_article_indexing,'postID',true);
        
        return $rs_article_indexing;
    }
    
    /**
     * 文章详情页 
     * 文章 相关推荐
     */
    public function article_view_related($id){
        
    }
    
    /**
     * 博客主页 编辑器页
     * 文集 列表
     * @param integer $bloggerID | 博主ID
     */
    public function category_list($bloggerID){
        $obj_blog_blogger=load("blog_blogger");
        $rs_blog_blogger=$obj_blog_blogger->getOne(['id','userID'],['id'=>$bloggerID,'status'=>1]);
        if(empty($rs_blog_blogger)) {$this->error="此博主不存在";$this->status=false;return false;}
        
        $obj_blog_category=load("blog_category");
        $rs_blog_category=$obj_blog_category->getAll("*",['order'=>['id'=>'DESC'],'limit'=>50,"bloggerID"=>$bloggerID]);
        return $rs_blog_category;
    }
    
    /**
     * 博客主页 编辑器页
     * 文集 文章列表
     * @param integer $id | 文集ID
     * @param integer $lastID | 最后文章ID
     */
    public function category_article_list($id,$lastID=0){
        $obj_blog_category=load("blog_category");
        $rs_blog_category=$obj_blog_category->getOne(['id','bloggerID'],['id'=>$id]);
        if(empty($rs_blog_category)) {$this->error="此文集不存在";$this->status=false;return false;}
        
        $obj_article_indexing=load("article_indexing");
        $fields=[
            'bloggerID'=>$rs_blog_category['bloggerID'],
            'categoryID'=>$id,
            'treelevel'=>0,
            "order"=>['id'=>"DESC"],
        ];
        if(!empty($lastID)){
            $fields['id,<']=$lastID;
        }
        $rs_article_indexing=$obj_article_indexing->getAll(['postID','basecode','userID','bloggerID','categoryID','create_date','edit_date'],$fields);
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $rs_article_indexing=$obj_account_user->get_basic_userinfo($rs_article_indexing,"userID");
        
        //添加ES信息
        $obj_search_article_noindex=load("search_article_noindex");
        $rs_article_indexing=$obj_search_article_noindex->get_postInfo($rs_article_indexing);
        
        //添加文章计数信息
        $obj_article_indexing=load("article_indexing");
        $rs_article_indexing=$obj_article_indexing->get_article_count($rs_article_indexing);
        
        return $rs_article_indexing;
    }

    /**
     * 编辑器传图
     * @param object $file
     * @post file
     * 
     */
    function uploadImage($file){
        return "https://www.google.com/url?sa=i&url=https%3A%2F%2Fm.baike.com%2Fwiki%2F%25E8%25A5%25BF%25E9%2587%258E%25E4%25B8%2583%25E6%25BF%2591%2F4998337%3Fbaike_source%3Dinnerlink&psig=AOvVaw2dUI1U_kP25R_675f8g7Di&ust=1609893790389000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCNi72-_Hg-4CFQAAAAAdAAAAABAD";
    }
    
    
}






































































