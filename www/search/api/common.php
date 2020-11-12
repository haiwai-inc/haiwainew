<?php
class common extends Api{
    /**
    * Search by keywords for articles
    *
    * @param string $keyword
    * @param string $last_score
    * @response 搜索结果
    *
    */
    public function articles($keyword, $last_score){
        $tool_obj = load("search_tool");
        $articles = $tool_obj -> search_article($keyword, $last_score);
        $user_obj = load("account_user");
        $articles = $user_obj -> get_basic_userinfo($articles, "userID");
        return $articles;
    }

    /**
     * Search by name for bloggers
     *
     * @param string $keyword
     * @param string $type
     * @param boolean $with_article
     * @param string $last_score
     * @response 搜索结果
     *
     */
    public function bloggers($keyword, $type = "all", $with_article = false, $last_score = 0)
    {
        $tool_obj = load("search_tool");
        $rs = $tool_obj->search_blogger($keyword, $type, $last_score);
        if(!empty($with_article)){
            $rs = array_slice($rs, 0 , 3);
        }
        return $rs;
    }


    /**
     * Search by name for category
     *
     * @param string $keyword
     * @response 搜索结果
     *
     */
    public function categories($keyword){
        $category_obj = load("blog_category");
        return $category_obj->search_by_name($keyword);
    }

    /**
     * Search by name for tag
     *
     * @param string $keyword
     * @response 搜索结果 list of tags
     *
     */
    public function tags($keyword){
        $tag_obj = load("article_tag");
        return $tag_obj->search_by_name($keyword);
    }

    /**
     * Search by id of tags 
     *
     * @param array $tags
     * @param string $last_id
     * @response 搜索结果 list of articles
     *
     */
    public function tag_articles($tags, $last_id = 0){
        if(empty($tags)) return [];
        if(!is_array($tags)){
            $tags = [$tags];
        }
        $tag_list = [];
        foreach($tags as $tag){
            $tag_list[] = intval($tag);
        }
        $article_index_obj = load("search_article_index");
        $articles = $article_index_obj->search_tags($tag_list, $last_id);

        $user_obj = load("account_user");
        $articles = $user_obj -> get_basic_userinfo($articles, "userID");
        return $articles;
    }
}