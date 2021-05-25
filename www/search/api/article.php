<?php
class article extends Api{


    /**
    * For debug purpose, 
    * 带index文章: indexing, 不带index文章: noindexing
    * 博主：blogger
    * 文集：category
    * 标签：tag
    *
    * @param string $id
    * @param string $table
    * @response 搜索结果
    *
    * @response 用户未登录，返回错误信息
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
}