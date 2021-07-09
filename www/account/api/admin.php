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
        $rs_account_user_login=$obj_account_user_login->getOne("*",['userID'=>$userID]);
        if(!empty($rs_account_user_login)){
            $obj_memcache=func_initMemcached('memSession');
            foreach($rs_account_user_login as $k=>$v){
                if($k=="id" || $k=="userID" || $k=="pointer"){
                    continue;
                }
                if(!empty($v)){
                    $obj_memcache->delete($v);
                }
            }
        }
        $fields=["pointer"=>0,"index0"=>"","index1"=>"","index2"=>"","index3"=>"","index4"=>"","index5"=>"","index6"=>"","index7"=>"","index8"=>"","index9"=>""];
        $obj_account_user_login->update($fields,["userID"=>$userID]);
        
        //博客
        $obj_blog_blogger=load("blog_blogger");
        $time=times::gettime();
        $obj_blog_blogger->update(['status'=>$status,'update_date'=>$time,'update_type'=>'admin'],['userID'=>$userID]);
        
        //删除文章
        $obj_article_indexing=load("article_indexing");
        $obj_article_indexing->update(['visible'=>$status,'edit_date'=>$time],['userID'=>$userID],NULL,true);
        
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
    
    /**
     * 快速登录
     * 用户 认证
     */
    public function login_status($userID=0) {
        $obj_account_user=load("account_user");
        $obj_account_user_login=load("account_user_login");
        
        $rs_account_user=$obj_account_user->getOne("*",['id'=>$userID]);
        $_SESSION=$obj_account_user_login->set_user_session($rs_account_user);
        if(empty($rs_account_user)){
            $this->error='此用户不存在';
            $this->status=false;
            return false;
        }
        
        return $rs_account_user;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}





































