<?php

/**
 * @author sida
 * ['data'=>[],'msg'=>[],'status'=>true]
 */
class passport extends Api {

    public function __construct() {
        parent::__construct();
        $this->sess = true;
    }

    /**
     * 所有博客页面
     * 博主 认证
     */
    public function login_status($userID) {
        $obj_blog_blogger=load("blog_blogger");
        $rs_blog_blogger=$obj_blog_blogger->getOne('*',['userID'=>$userID]);
        
        if(!empty($rs_blog_blogger)){
            $rs=['status'=>true,'msg'=>"",'data'=>$rs_blog_blogger];
        }else{
            $rs=['status'=>false,'msg'=>"no blogger",'data'=>""];
        }
        
        return $rs;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}





































