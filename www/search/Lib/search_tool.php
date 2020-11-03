<?php
class search_tool{



    public function search_article($keyword){
        $search_article_obj = load("search_article_index");
        $articles = $search_article_obj->search_by_keyword($keyword);
        $userIDs = [];
        foreach($articles as $article){
            $userIDs[] = $article['userID'];
        }
        $user_obj = load("account_user");
        $users = $user_obj->getAll(["id", "username", "avatar"], ["OR"=>['id'=>$userIDs]]);
        $user_map = [];
        foreach($users as $user){
            $user_map[$user['id']] = $user;
        }
        $rs = [];
        foreach($articles as $article){
            if(!empty($user_map[$article['userID']])){
                $article['user'] = $user_map[$article['userID']];
            }
            $rs[] = $article;
        }
        return $rs;
    }
}