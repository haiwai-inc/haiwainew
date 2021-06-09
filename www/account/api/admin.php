<?php
class admin extends Api {
    public $space = true;
    public $admin = true;

    public function __construct() {
        parent::__construct();
        $this->sess = true;
    }

    /**
     * 用户 删除
     * @param integer $userID | 用户ID
     * @param integer $status | 1开 0关
     */
    public function user_delete($userID,$status){
        //用户
        $obj_account_user=load("account_user");
        $obj_account_user->update(['status'=>$status],['id'=>$userID]);
        
        //踢人下线
        $obj_account_user_login=load("account_user_login");
        $obj_account_user_login->update(['']);
        
        //博客
        $obj_blog_blogger=load("blog_blogger");
        $obj_blog_blogger->update(['status'=>$status],['userID'=>$userID]);
        return true;
    }
    
    /**
     * 搜索用户名
     * @param string $keyword
     */
    public function user_search($keyword){
        $obj_account_user=load("account_user");
        $rs_account_user=$obj_account_user->getAll("*", ['limit'=>100,'username, LIKE' => "%$keyword%"]);
        return $rs_account_user;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}





































