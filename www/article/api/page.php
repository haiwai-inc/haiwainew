<?php
class page extends Api {
    
    public function __construct() {
        parent::__construct();
        $this->sess = true;
    }
    
    /**
     * 查看文章点赞人
     * 文章 点赞人 
     * @param integer $id | 主贴postID
     */
    public function article_buzz_list($id){
        $obj_article_indexing=load("article_indexing");
        $rs_article_indexing=$obj_article_indexing->getOne(['postID','basecode','userID','bloggerID'],['visible'=>1,'postID'=>$id]);
        if(empty($rs_article_indexing)){$this->error="此文章不存在";$this->status=false;return false;}
        
        //ES补全postID信息
        $obj_article_noindex=load("search_article_noindex");
        $rs_article_indexing=$obj_article_noindex->get_postInfo([$rs_article_indexing],'postID',true);
        $rs_article_indexing=empty($rs_article_indexing)?[]:$rs_article_indexing[0];
        
        if(empty($rs_article_indexing['postInfo_postID']['buzz'])){
            return [];
        }
        $buzz_user=[];
        foreach($rs_article_indexing['postInfo_postID']['buzz'] as $v){
            $buzz_user[]=['userID'=>$v];
        }
        
        //添加用户信息
        $obj_account_user=load("account_user");
        $buzz_user=$obj_account_user->get_basic_userinfo($buzz_user,"userID");
        return $buzz_user;
    }
    
    /**
     * 分享文章
     * @param string url | 分享链接
     * @param integer $id | 主贴postID
     */
    public function article_share($url,$id){
        $obj_article_indexing=load("article_indexing");
        $rs_article_indexing=$obj_article_indexing->getOne(['postID','basecode','userID','bloggerID'],['visible'=>1,'postID'=>$id]);
        if(empty($rs_article_indexing)){$this->error="此文章不存在";$this->status=false;return false;}
        
        $obj_search_article_noindex=load("search_article_noindex");
        $rs_search_article_noindex=$obj_search_article_noindex->get_postInfo([$rs_article_indexing]);
        $rs_search_article_noindex=empty($rs_search_article_noindex)?[]:$rs_search_article_noindex[0];
        
        $rs=['qr_code'=>picture::QRCode($url, 200),'article_data'=>$rs_search_article_noindex];
        return $rs;
    }
    
    
    
    
    
    
    
}






































































