<?php
class page extends Api {
    
    public function __construct() {
        parent::__construct();
        $this->sess = true;
    }
    
    /**
     * “别人的” 用户信息页
     * 用户 信息
     * @param integer $userID
     */
    public function user_info($userID){
        $obj_account_user=load("account_user");
        $rs_account_user=$obj_account_user->getOne(['id','username','description','avatar'],['id'=>$userID]);
        
        //添加用户信息
        $rs_account_user=$obj_account_user->get_basic_userinfo([$rs_account_user],"id")[0];
        
        //添加博客信息
        $obj_blog_blogger=load("blog_blogger");
        $rs_account_user=$obj_blog_blogger->get_basic_bloggerinfo([$rs_account_user],"userinfo_id",true)[0];
        
        return $rs_account_user;
    }
    
    
}






































































