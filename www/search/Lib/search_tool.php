<?php
class search_tool{



    public function search_article($keyword, $last_score = 0){
        $search_article_obj = load("search_article_index");
        $articles = $search_article_obj->search_by_keyword($keyword, $last_score);
        $user_obj = load("account_user");
        $articles = $user_obj->get_basic_userinfo($articles, "userID");
        return $articles;
    }

    public function search_blogger($keyword, $type, $last_score = 0){
        $search_blogger_obj = load("search_blogger");
        $bloggers = $search_blogger_obj->search_by_keyword($keyword, $last_score, $type);
        $user_obj = load("account_user");
        $blogger_obj = load("blogger_user");
        $articles = $user_obj->get_basic_userinfo($bloggers, "userID");
        $articles = $blogger_obj->get_basic_bloggerinfo($bloggers, "bloggerID");
        return $articles;
    }

    
}