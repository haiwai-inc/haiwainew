<?php
class article extends Api{


    /**
    * Search by keywords
    *
    * @param string $keyword
    * @response 搜索结果
    *
    * @response 用户未登录，返回错误信息
    */
    function all($keyword){
        $tool_obj = load("search_tool");
        $articles = $tool_obj -> search_article($keyword);
        return $articles;
    }
}