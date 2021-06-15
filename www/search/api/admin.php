<?php
class admin extends Api{

    function __construct(){
        if(!defined("SEARCHADMIN") || empty(SEARCHADMIN)) exit;
    }
    /**
    * For debug purpose, 
    * 带index文章: indexing, 不带index文章: noindexing
    * 博主：blogger
    * 文集：category
    * 标签：tag
    *
    * @param string $id
    * @param string $table
    * @response 打印内容
    *
    * @response 
    */
    public function checkId($id, $table){
        $obj = null;
        switch($table){
            case("indexing") : {
                $obj = load("search_article_index");
                break;
            }
            case("noindexing") : {
                $obj = load("search_article_noindex");
                break;
            }
            case("category") : {
                $obj = load("search_category");
                break;
            }
            case("blogger") : {
                $obj = load("search_blogger");
                break;
            }
            case("tag") : {
                $obj = load("search_tag");
                break;
            }
        }
        debug::D($obj -> get($id));
    }

    /**
    * For debug purpose, 重置index
    * 带index文章: indexing, 不带index文章: noindexing
    * 博主：blogger
    * 文集：category
    * 标签：tag
    *
    * @param string $table
    * @param string password
    * @response 搜索结果
    *
    * @response 
    */
    public function resetIndex($table, $password){
        if($password != "haiwai2020") return;
        switch($table){
            case("indexing") : {
                $obj = load("search_article_index");
                break;
            }
            case("noindexing") : {
                $obj = load("search_article_noindex");
                break;
            }
            case("category") : {
                $obj = load("search_category");
                break;
            }
            case("blogger") : {
                $obj = load("search_blogger");
                break;
            }
            case("tag") : {
                $obj = load("search_tag");
                break;
            }
        }
        $index_setting  = json_decode(json_encode($obj->get_index_setting()), true);
    if (!empty($index_setting) && empty($index_setting['error'])) {
        $obj->indexdel();
    }
        $obj->initialize_index();
    }

    /**
    * For debug purpose, 重新插入
    * 带index文章: indexing, 不带index文章: noindexing
    * 博主：blogger
    * 文集：category
    * 标签：tag
    *
    * @param string $table
    * @response 搜索结果
    *
    * @response 
    */
    public function updateData($table){
        switch($table){
            case("indexing") : {
                $obj = load("search_article_index");
                break;
            }
            case("noindexing") : {
                $obj = load("search_article_noindex");
                break;
            }
            case("category") : {
                $obj = load("search_category");
                break;
            }
            case("blogger") : {
                $obj = load("search_blogger");
                break;
            }
            case("tag") : {
                $obj = load("search_tag");
                break;
            }
        }
        $obj->update_data();
    }


}