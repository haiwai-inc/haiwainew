<?php

//https://scotch.io/tutorials/getting-started-with-redis-in-php
//https://www.sitepoint.com/an-introduction-to-redis-in-php-using-predis/
class count_tool{
    public $host="cache01";
    
    public $buffer_time=1;
    public $buffer_max=5;
    public $buffer_key="_buffer";
    
    public $sync_article_key="sync_article";
    public $sync_blogger_key="sync_blogger";
    
    function __construct(){
        $this->obj_redis=func_initRedis($this->host);
    }
    
    function add_article($id){
        //init cache
        $rs=$this->view_article($id);
        
        //buffer
        $rs_buffer=$this->obj_redis->incr($id.$this->buffer_key);
        $this->obj_redis->expire($id."_buffer",$this->buffer_time);
        
        //real counter
        if($rs_buffer<$this->buffer_max){
            $rs=$this->obj_redis->incr($id);
        }
        
        //add sync article key
        $this->obj_redis->sAdd($this->sync_article_key, $id); 
        
        return $rs;
    }
    
    function view_article($id){
        $rs=$this->obj_redis->get($id); 
        if(empty($rs)){
            $obj_article_indexing=load("article_indexing");
            $rs_article_indexing=$obj_article_indexing->getOne(['count_read'],['postID'=>$id]);
            $rs=$rs_article_indexing['count_read'];
            $this->obj_redis->incrby($id,$rs); 
        }
        return $rs;
    }
    
    
}
?>





































