<?php
class common extends Api{
    /**
    * Search by keywords for articles
    *
    * @param string $keyword
    * @response 搜索结果
    *
    */
    public function articles($keyword){
        $tool_obj = load("search_tool");
        $articles = $tool_obj -> search_article($keyword);
        
        $user_obj = load("account_user");
        $articles = $user_obj -> get_basic_userinfo($articles, "userID");
        return $articles;
    }

    /**
     * Search by name for users
     *
     * @param string $keyword
     * @param string $type
     * @param boolean $with_article
     * @response 搜索结果
     *
     */
    public function users($keyword, $type = "all", $with_article = false)
    {
        $rs       = [];
        if($type == "all" || $type == "user"){
            $user_obj = load("account_user");
            $users = $user_obj->search_by_name($keyword);
            $rs['users'] = $users;
        }

        if($type == "all" || $type == "blogger"){
            $blogger_obj = load("blog_blogger");
            $bloggers = $blogger_obj->search_by_name($keyword, $with_article);
            $rs['bloggers'] = $bloggers;
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
     * @response 搜索结果 list of articles
     *
     */
    public function tag_articles($tags){
        if(empty($tags)) return [];
        if(!is_array($tags)){
            $tags = [$tags];
        }
        $tag_list = [];
        foreach($tags as $tag){
            $tag_list[] = intval($tag);
        }
        $article_index_obj = load("search_article_index");
        $articles = $article_index_obj->search_tags($tag_list);

        $user_obj = load("account_user");
        $articles = $user_obj -> get_basic_userinfo($articles, "userID");
        return $articles;
    }
}