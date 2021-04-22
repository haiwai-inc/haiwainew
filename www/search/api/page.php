<?php
class page extends Api{
    /**
    * Search by keywords for articles
    *
    * @param string $keyword
    * @param string $last_score
    * @param string $last_id
    * @response 搜索结果
    *
    */
    public function articles($keyword, $last_score = 0, $last_id = 0){
        $tool_obj = load("search_tool");
        $articles = $tool_obj -> search_article($keyword, $last_score, $last_id);
        return $articles;
    }

    /**
     * Search by name for bloggers
     *
     * @param string $keyword
     * @param string $type
     * @param boolean $with_article
     * @param string $last_score
     * @param string $last_id
     * @response 搜索结果
     *
     */
    public function bloggers($keyword, $type = "all", $with_article = false, $last_score = 0, $last_id = 0)
    {
        $tool_obj = load("search_tool");
        $rs = $tool_obj->search_blogger($keyword, $type, $last_score, $last_id);
        if(!empty($with_article)){
            $rs = array_slice($rs, 0 , 3);
        }
        return $rs;
    }


    /**
     * Search by name for category
     *
     * @param string $keyword
     * @param string $last_score
     * @param string $last_id
     * @response 搜索结果
     *
     */
    public function categories($keyword, $last_score = 0, $last_id = 0){
        $tool_obj = load("search_tool");

        $category_obj = load("search_category");
        $categories = $category_obj->search_by_name($keyword,$last_score, $last_id);
        $user_obj = load("account_user");
        $blogger_obj = load("blog_blogger");

        $categories = $blogger_obj->get_basic_bloggerinfo($categories, "bloggerID");
        $categories = $tool_obj -> extract_user_id($categories, "bloggerinfo_bloggerID");
        $categories = $user_obj->get_basic_userinfo($categories, "userID");

        return $tool_obj->overwrite_highlight($categories);
    }

    /**
     * Search by name for tag
     *
     * @param string $keyword
     * @response 搜索结果 list of tags
     *
     */
    public function tags($keyword){
        $tag_obj = load("search_tag");
        
        if(empty($keyword)){
            $default_tag=conf("search.default_tag");
            return $default_tag;
        }
        
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
            if(is_int($tags))
                $tags = [$tags];
            else if (is_string($tags)){
                 $tags = explode(',', $tags);
            }
        }
        $tag_list = [];
        foreach($tags as $tag){
            $tag_list[] = intval($tag);
        }
        $article_index_obj = load("search_article_index");
        $articles = $article_index_obj->search_tags($tag_list, $last_id, array("postID"=>array("order"=>"desc")));

        $tool_obj = load("search_tool");
        return $tool_obj->fetch_article_info($articles);
    }



    /**
     * Search by name for tag
     *
     * @param string $keyword
     * @response 搜索结果 list of tags
     *
     */
    public function tags_article_combined($keyword){
        $rs = ["tags"=>[], "articles"=>[]];
        $tag_obj = load("search_tag");
        $tags = $tag_obj->search_by_name($keyword);
        $rs['tags'] = $tags; 
        if(empty($tags)) return $rs;
        $tag_ids = [];

        foreach($tags as $tag){
            $tag_ids[] = $tag['id'];
        }

        $rs['tags'] = $tags; 
        $rs['articles'] = $this->tag_articles($tag_ids);
        return $rs;
    }


}