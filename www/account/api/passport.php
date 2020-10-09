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
     * 检查登录状态
     */
    public function login_status($userID) {
        $obj_account_user=load("account_user");
        $rs_account_user=$obj_account_user->getOne(['id','name','description','background','avatar'],['id'=>$userID]);
        
        $obj_blog_blogger=load("blog_blogger");
        $rs_blog_blogger=$obj_blog_blogger->getOne(['id','userID','description','background'],['userID'=>$userID]);
        
        $rs=[
            'status'=>true,
            'msg'=>"",
            'data'=>['account_user'=>$rs_account_user,'blog_blogger'=>$rs_blog_blogger],
        ];
        
        return $rs;
    }
    
    

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}





































