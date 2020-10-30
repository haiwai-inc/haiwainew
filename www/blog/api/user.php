<?php
class user extends Api {

    public $space = true;
    
    public function __construct() {
        parent::__construct();
        
        //认证博主
        $bloggerID="1";
        $this->userAuthz($bloggerID);
    }
    
    /**
     * “我的”博主信息页
     * 博主 信息
     */
    public function blogger_info(){
        
    }
    
    /**
     * 博主设置页
     * 博主 修改
     */
    public function blogger_update(){
        
    }
    
    /**
     * 博主设置页
     * 博主 名字 拍重
     */
    public function blogger_name_check(){
        
    }
    
    /**
     * 博客主页 编辑器页 
     * 文集 添加
     */
    public function category_add(){
        
    }
    
    /**
     * 编辑器页 
     * 文集 修改
     */
    public function category_update(){
        
    }
    
    /**
     * 编辑器页 
     * 文集 删除
     */
    public function category_delete(){
        
    }
    
    /**
     * 编辑器页
     * 文集 名字 拍重
     */
    public function category_name_check(){
        
    }
    
    /**
     * 小铃铛页
     * 关注 "关注我的"
     */
    public function follower_list_mine(){
        
    }
    
    /**
     * 二级页面
     * 关注 博主 列表
     */
    public function follower_blogger_list(){
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}





































