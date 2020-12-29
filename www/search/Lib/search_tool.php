<?php
class search_tool{



    public function search_article($keyword, $last_score = 0, $last_id = 0){
        $search_article_obj = load("search_article_index");
        $articles = $search_article_obj->search_by_keyword($keyword, $last_score, $last_id);
        $user_obj = load("account_user");
        $articles = $user_obj->get_basic_userinfo($articles, "userID");
        $articles = $this->overwrite_highlight($articles);
        return $this->fetch_article_info($articles);
    }

    public function search_blogger($keyword, $type, $last_score = 0, $last_id = 0){
        $search_blogger_obj = load("search_blogger");
        $bloggers = $search_blogger_obj->search_by_name($keyword, $last_score, $type, $last_id);
        $user_obj = load("account_user");
        $blogger_obj = load("blog_blogger");
        $bloggers = $user_obj->get_basic_userinfo($bloggers, "userID");
        $bloggers = $blogger_obj->get_basic_bloggerinfo($bloggers, "bloggerID");
        return $this->overwrite_highlight($bloggers);
        // return $articles;
    }

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


    public function fetch_article_info($articles){
        $user_obj = load("account_user");
        $articles = $user_obj -> get_basic_userinfo($articles, "userID");
        $search_noindex = load("search_article_noindex");
        $articles = $search_noindex->get_postInfo($articles);
        $obj_article_indexing=load("article_indexing");
        $articles=$obj_article_indexing->get_article_count($articles);
        return $articles;
    }
}