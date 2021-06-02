<?php
class article extends Api {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 通用页
     * 文章 添加
     */
    function article_add($id){
        $obj_count_tool=load("count_tool");
        $rs_count=$obj_count_tool->add_article($id);
        
        $rs=['status'=>true,'msg'=>"",'data'=>$rs_count];
        return $rs;
    }
    
    /**
     * 通用页
     * 文章 显示
     */
    function article_view($id){
        $obj_count_tool=load("count_tool");
        $rs_count=$obj_count_tool->view_article($id);
        $rs=['status'=>true,'msg'=>"",'data'=>$rs_count];
        return $rs;
    }
}





































