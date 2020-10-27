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

    public function login_status($userID) {

//$obj=load("search_article_pool");
//$test1=$obj->get_by_postIDs([9237]);


/*
$obj=load("search_article");
$test2=$obj->search_tags([2]);
debug::d($test2);
exit;
*/

/*
$obj=load("search_article_pool");
$obj->del(9237);
*/

/*
$obj=load("search_article_pool");
debug::d($obj->get(9237));
*/

/*
$obj=load("search_article_pool");
$test2=$obj->fetch_and_insert([9237]);
debug::d($test2);
exit;
*/




        $obj_account_user=load("account_user");
        $rs_account_user=$obj_account_user->getOne(['id','name','description','background','avatar'],['id'=>$userID]);
        $rs_account_user['avatar']=file_domain.$rs_account_user['avatar'];
        
        if(!empty($rs_account_user)){
            $rs=['status'=>true,'msg'=>"",'data'=>$rs_account_user];
        }else{
            $rs=['status'=>false,'msg'=>"User not logged in",'data'=>""];
        }
        
        return $rs;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}





































