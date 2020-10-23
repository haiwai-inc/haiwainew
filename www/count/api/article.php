<?php
class article extends Api {

    public function __construct() {
        parent::__construct();
    }

    function add_article($id){
        $obj_count_tool=load("count_tool");
        $rs_count=$obj_count_tool->add_article($id);
        
        $rs=['status'=>true,'msg'=>"",'data'=>$rs_count];
        return $rs;
    }
    
    function view_article($id){
        $obj_count_tool=load("count_tool");
        $rs_count=$obj_count_tool->view_article($id);
        $rs=['status'=>true,'msg'=>"",'data'=>$rs_count];
        return $rs;
    }
}





































