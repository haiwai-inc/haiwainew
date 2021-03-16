<?php
class user extends Api {

    public $space = true;
    
    public function __construct() {
        parent::__construct();
        $this->sess = true;
    }
    
    /**
     * 博客设置页
     * 博主 信息
     */
    public function blogger_profile(){
        $obj_blog_blogger=load("blog_blogger");
        $rs_blog_blogger=$obj_blog_blogger->getOne(['id','name','description','background'],['id'=>$_SESSION['id']]);
        $rs_blog_blogger['first_letter']=substr(strings::subString($rs_blog_blogger['name'],1), 0, -3);
        
        return $rs_blog_blogger;
    }
    
    /**
     * 博主设置页
     * 博主 信息 修改
     * @param string $name|博主名
     * @param integer $description|博主简介
     */
    public function blogger_profile_update($name,$description=NULL){
        $obj_blog_blogger=load("blog_blogger");
        $check_blog_blogger=$obj_blog_blogger->getOne("*",['name'=>$name]);
        if(!empty($check_blog_blogger)) {$this->error="此博主名已经被占用";$this->status=false;return false;}
        
        $fields=[
            "name"=>empty($name)?"":$name,
            "description"=>empty($description)?"":$description,
        ];
        $obj_blog_blogger->update($fields,['userID'=>$_SESSION['id']]);
        return true;
    }
    
    /**
     * 博主设置页
     * 博主 背景 修改
     * @param string $background
     * @post background
     */
    public function blogger_background_update($background){
        if(substr($background, 0, 4) === "data"){
            $file = file_get_contents($background);
        }
        else{
            $file = base64_decode($background);
        }
        
        //路径
        $dir="/upload/blog/background/".substr('0000'.$_SESSION['id'],-2)."/".substr('0000'.$_SESSION['id'],-4,-2);
        $path=DOCUROOT.$dir;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        
        //文件名
        $filename=$_SESSION['id']."_background";
        $extension = explode('/', mime_content_type($background))[1];
        $rs_image=$filename.".".$extension;
        
        //保存
        file_put_contents($path."/".$rs_image, $file);
        
        //小图处理
        $obj_account_user=load("account_user");
        $obj_account_user->cutPic("{$path}/{$rs_image}","{$filename}_750_420",750,420);
        
        $obj_blog_blogger=load("blog_blogger");
        $obj_blog_blogger->update(['background'=>"{$dir}/{$rs_image}"],['userID'=>$_SESSION['id']]);
        
        return "{$dir}/{$rs_image}";
    }
    
    /**
     * 博客主页 编辑器页 
     * 文集 添加
     * @param integer $name|文集名
     */
    public function category_add($name){
        $obj_blog_blogger=load("blog_blogger");
        $check_blog_blogger=$obj_blog_blogger->getOne("*",['userID'=>$_SESSION['id']]);
        if(empty($check_blog_blogger))  {$this->error="此博主不存在";$this->status=false;return false;}
        
        $obj_blog_category=load("blog_category");
        $check_blog_category=$obj_blog_category->getOne("*",['bloggerID'=>$check_blog_blogger['id'],'name'=>$name]);
        if(!empty($check_blog_category))    {$this->error="此文集名称已存在";$this->status=false;return false;}
        
        $obj_blog_category->insert(['bloggerID'=>$_SESSION['id'],'name'=>$name]);
        return true;
    }
    
    /**
     * 编辑器页 
     * 文集 修改
     * @param integer $name|文集名
     * @param integer $id|文集id
     */
    public function category_update($name,$id){
        $obj_blog_blogger=load("blog_blogger");
        $check_blog_blogger=$obj_blog_blogger->getOne("*",['userID'=>$_SESSION['id']]);
        if(empty($check_blog_blogger))  {$this->error="此博主不存在";$this->status=false;return false;}
        
        $obj_blog_category=load("blog_category");
        $check_blog_category=$obj_blog_category->getOne("*",['id,!='=>$id,'bloggerID'=>$check_blog_blogger['id'],'name'=>$name]);
        if(!empty($check_blog_category))    {$this->error="此文集名称已存在";$this->status=false;return false;}
        
        $obj_blog_category->update(['name'=>$name],['bloggerID'=>$check_blog_blogger['id'],"id"=>$id]);
        return true;
    }
    
    /**
     * 编辑器页 
     * 文集 删除
     * @param integer $id|文集id
     */
    public function category_delete($id){
        $obj_blog_blogger=load("blog_blogger");
        $check_blog_blogger=$obj_blog_blogger->getOne("*",['userID'=>$_SESSION['id']]);
        if(empty($check_blog_blogger))  {$this->error="此博主不存在";$this->status=false;return false;}
        
        $obj_blog_category=load("blog_category");
        $obj_blog_category->remove(['bloggerID'=>$check_blog_blogger['id'],"id"=>$id]);
        return true;
    }
    
    /**
     * 编辑器页
     * 文集 名字 拍重
     * @param integer $name|文集名
     */
    public function category_name_check($name){
        $obj_blog_blogger=load("blog_blogger");
        $check_blog_blogger=$obj_blog_blogger->getOne("*",['userID'=>$_SESSION['id']]);
        if(empty($check_blog_blogger))  {$this->error="此博主不存在";$this->status=false;return false;}
        
        $obj_blog_category=load("blog_category");
        $check_blog_category=$obj_blog_category->getOne("*",['bloggerID'=>$check_blog_blogger['id'],'name'=>$name]);
        if(!empty($check_blog_category))    {$this->error="此文集名称已存在";$this->status=false;return false;}
        
        return true;
    }
    
    /**
     * 二级页面
     * 我关注的人 列表 
     * @param integer $lastID | 分页id
     */
    public function my_followering_list($lastID=0){
        $obj_account_follow=load("account_follow");
        
        $fields=['order'=>["id"=>"DESC"],'followerID'=>$_SESSION['id']];
        if(!empty($lastID)){
            $fields['id,<']=$lastID;
        }
        $rs_account_follow=$obj_account_follow->getAll("*",$fields);
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $rs_account_follow=$obj_account_user->get_basic_userinfo($rs_account_follow,"followingID");
        
        return $rs_account_follow;
    }
    
    /**
     * 二级页面
     * 关注 文章 列表
     * @param integer $followingID | 关注人的ID
     */
    public function following_article_list($followingID=0){
        $obj_account_follow=load("account_follow");
        $obj_account_user=load("account_user");
        
        if(!empty($followingID)){
            $check_account_user=$obj_account_user->getOne(['id','username'],["id"=>$followingID,"status"=>1]);
            if(empty($check_account_user))  {$this->error="此关注用户已不存在";$this->status=false;return false;}
            
            $check_account_follow=$obj_account_follow->getOne(['id'],['followerID'=>$_SESSION['id'],'followingID'=>$followingID]);
            if(empty($check_account_follow)) {$this->error="此用户未在您的关注列表";$this->status=false;return false;}
            $followingID_account_follow[]=$followingID;
        }else{
            $check_account_follow=$obj_account_follow->getAll("*",['order'=>['id'=>'DESC'],'limit'=>50,'followerID'=>$_SESSION['id']]);
            if(empty($check_account_follow))  {$this->error="你还未关注任何用户";$this->status=false;return false;}
            foreach($check_account_follow as $v){
                $followingID_account_follow[]=$v['followingID'];
            }
        }
        
        //索引表
        $obj_article_indexing=load("article_indexing");
        $rs_article_indexing=$obj_article_indexing->getAll(["id","postID","userID","blogID"],['order'=>['id'=>'DESC'],'treelevel'=>0,'visible'=>1,'OR'=>['userID'=>$followingID_account_follow]]);
        
        //添加用户信息
        $rs_article_indexing=$obj_account_user->get_basic_userinfo($rs_article_indexing,"userID");
        
        //添加ES信息
        $obj_article_noindex=load("search_article_noindex");
        $rs_article_indexing=$obj_article_noindex->get_postInfo($rs_article_indexing);
        
        //添加文章计数信息
        $rs_article_indexing=$obj_article_indexing->get_article_count($rs_article_indexing);
        
        return $rs_article_indexing;
    }
    
    /**
     * 编辑器页
     * 文章 列表
     * @param integer $id | 文集ID
     * @param String $lastID|普通文章postID,草稿文章id
     */
    public function article_list($id,$lastID=""){
        $obj_blog_category=load("blog_category");
        $rs_blog_category=$obj_blog_category->getOne(['id','bloggerID'],['id'=>$id]);
        if(empty($rs_blog_category)) {$this->error="此文集不存在";$this->status=false;return false;}
        
        //分页
        if(!empty($lastID)){
            $lastID=explode(",",$lastID);
            $article_lastID=$lastID[0];
            $draft_lastID=$lastID[1];
        }
        
        //文章
        $obj_article_indexing=load("article_indexing");
        $fields=[
            'bloggerID'=>$rs_blog_category['bloggerID'],
            'categoryID'=>$id,
            'treelevel'=>0,
            "order"=>['is_sticky'=>'DESC','id'=>"DESC"],
            "limit"=>30
        ];
        if(!empty($article_lastID)){
            $fields['id,<']=$article_lastID;
        }
        $rs_article_indexing=$obj_article_indexing->getAll('*',$fields);
        
        //移除草稿重合
        $obj_article_draft=load("article_draft");
        $rs_article_indexing=$obj_article_draft->remove_article_indexing($rs_article_indexing);
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $rs_article_indexing=$obj_account_user->get_basic_userinfo($rs_article_indexing,"userID");
        
        //添加ES信息
        $obj_search_article_noindex=load("search_article_noindex");
        $rs_article_indexing=$obj_search_article_noindex->get_postInfo($rs_article_indexing);
        
        //草稿
        $fields=[
            'bloggerID'=>$rs_blog_category['bloggerID'],
            'categoryID'=>$id,
            "order"=>['id'=>"DESC"],
            "limit"=>30
        ];
        if(!empty($draft_lastID)){
            $fields['id,<']=$draft_lastID;
        }
        $rs_article_draft=$obj_article_draft->getAll("*",$fields);
        
        //添加用户信息
        $rs_article_draft=$obj_account_user->get_basic_userinfo($rs_article_draft,"userID");
        
        //格式化草稿
        $rs_article_draft=$obj_article_draft->format_draft($rs_article_draft);
        
        //合并
        $rs_article_list=array_merge($rs_article_indexing,$rs_article_draft);
        if(!empty($rs_article_list)){
            //拆分sticky
            $rs_article_sticky=[];
            foreach($rs_article_list as $k=>$v){
                if(!empty($v['is_sticky'])){
                    unset($rs_article_list[$k]);
                    $rs_article_sticky[]=$v;
                }
            }
            //排序
            if(!empty($rs_article_sticky)){
                usort($rs_article_sticky, function($a, $b) {
                    return $b['create_date'] <=> $a['create_date'];
                });
            }
            if(!empty($rs_article_list)){
                usort($rs_article_list, function($a, $b) {
                    return $b['create_date'] <=> $a['create_date'];
                });
            }
            $rs_article_list=array_merge($rs_article_sticky,$rs_article_list);
            $rs_article_list = array_slice($rs_article_list, 0, 30);
        }
        
        return $rs_article_list;
    }
    
    /**
     * 二级页面
     * 关注 文章 列表
     * @param integer $followerID | 关注人的ID
     */
    public function group_list(){
        
    }
        
    /**
     * 群博管理页面
     * 群博 添加
     */
    public function group_add(){
        
    }
    
    /**
     * 群博管理页面
     * 群博 删除
     */
    public function group_delete(){
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}





































