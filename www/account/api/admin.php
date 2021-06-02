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
     * @param integer $visible | 1开 0关
     */
    public function user_delete($userID,$visible){
        //用户
        $obj_account_user=load("account_user");
        $obj_account_user->update(['status'=>$visible],['id'=>$userID]);
        
        //博客
        $obj_blog_blogger=load("blog_blogger");
        $obj_blog_blogger->update(['status'=>$visible],['userID'=>$userID]);
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





































