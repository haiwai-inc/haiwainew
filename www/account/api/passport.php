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





































