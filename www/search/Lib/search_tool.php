<?php
class search_tool{



    /**
     * Search for articles using keyword
     * @param string $keyword
     * @param double $last_score | 0 if first page
     * @param int $last_id | 0 if first page
     * @param int $highlight | Wether to highlight the result or not
     * @return array $rs
     */
    public function search_article($keyword, $last_score = 0, $last_id = 0, $highlight = 1){
        $search_article_obj = load("search_article_index");
        $articles = $search_article_obj->search_by_keyword($keyword, $last_score, $last_id);
        $user_obj = load("account_user");
        // Fill in user info and post Info
        $articles = $user_obj->get_basic_userinfo($articles, "userID");
        if(!empty($highlight)){
            $articles = $this->overwrite_highlight($articles);
        }
        return $this->fetch_article_info($articles);
    }

    /**
     * Search for bloggers using keyword
     * @param string $keyword
     * @param double $last_score | 0 if first page
     * @param int $last_id | 0 if first page
     * @param int $highlight | Wether to highlight the result or not
     * @return array $rs
     */
    public function search_blogger($keyword, $type, $last_score = 0, $last_id = 0){
        $search_blogger_obj = load("search_blogger");
        $bloggers = $search_blogger_obj->search_by_name($keyword, $last_score, $type, $last_id);
        $user_obj = load("account_user");
        $blogger_obj = load("blog_blogger");
        // Fill in other user and blogger info
        $bloggers = $user_obj->get_basic_userinfo($bloggers, "userID");
        $bloggers = $blogger_obj->get_basic_bloggerinfo($bloggers, "bloggerID");
        return $this->overwrite_highlight($bloggers);
    }

    /**
     * Overwrite orignal title/blogger name/username/msgbody with highlighted part
     * @param array $rs | List of search results to convert
     * @return array $rs | Converted results
     */
    public function overwrite_highlight($rs){
        foreach($rs as $k=>$doc){
            if(!empty($doc['__highlight'])){
                foreach($doc['__highlight'] as $key=>$highlights){
                    $rs[$k][$key] = implode("...", $highlights);
                }
                unset($rs[$k]["__highlight"]);
            }
        }
        return $rs;
    }


    /**
     * Fill in necessary information about the article
     * @param array $articles | Articles to fill in information
     * @return array $articles 
     */
    public function fetch_article_info($articles){
        $user_obj = load("account_user");
        $articles = $user_obj -> get_basic_userinfo($articles, "userID");
        $search_noindex = load("search_article_noindex");
        $articles = $search_noindex->get_postInfo($articles);
        // foreach($articles as $k=>$v){
        //     $articles[$k]['postInfo_postID']['msgbody'] = $v['msgbody'];
        // }
        $obj_article_indexing=load("article_indexing");
        $articles=$obj_article_indexing->get_article_count($articles);
        return $articles;
    }


    public function extract_user_id($items, $field_name){
        
        foreach($items as $k => $item){
            $items[$k]['userID'] = $item[$field_name]['userID'];
        }
        return $items;
    }
}