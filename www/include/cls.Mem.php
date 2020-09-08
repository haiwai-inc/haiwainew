<?php
/**
* 重新封装php的memcache对象，方便不同版本的php进行调用
* @author weiqiwang
*
*/
class Mem{

    private $memObj;
    private $driver='memcache';

    function __construct($host, $port){
        if(class_exists('Memcached')){
            //兼容php7
            $this->memObj = new Memcached();
            $this->memObj->addServer($host, $port);
            $this->driver = 'memcached';
        }else{
            $this->memObj=memcache_connect($host, $port);
        }
    }

    function get($key){
        // if(defined("CACHE_MODE") && CACHE_MODE == "sandbox"){
        //     $key = $key."_sb";
        // }
        return $this->memObj->get($key);
    }

    function add($key, $value, $expire=3600 ){
        // if(defined("CACHE_MODE") && CACHE_MODE == "sandbox")
        // $key = $key."_sb";
        if($this->driver=='memcache'){
            return $this->memObj->add( $key, $value, false, $expire );
        }else{
            return $this->memObj->add( $key, $value, $expire );
        }
    }

    function set($key, $value, $expire=3600){
        // if(defined("CACHE_MODE") && CACHE_MODE == "sandbox")
        // $key = $key."_sb";
        if($this->driver=='memcache'){
            return $this->memObj->set( $key, $value, false, $expire );
        }else{
            return $this->memObj->set( $key, $value, $expire );
        }
    }

    function delete($key,$timeout=0){
        // if(defined("CACHE_MODE") && CACHE_MODE == "sandbox")
        // $key = $key."_sb";
        return $this->memObj->delete($key,$timeout);
    }

    function increment($key,$offset=1){
        // if(defined("CACHE_MODE") && CACHE_MODE == "sandbox")
        // $key = $key."_sb";
        return $this->memObj->increment($key,$offset);
    }

}