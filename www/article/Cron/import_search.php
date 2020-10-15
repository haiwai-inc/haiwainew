<?php
set_time_limit(0);
define( 'DOCUROOT',str_replace("/article/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';

$article_search = load("article_search");
$article_search_pool = load("article_search_pool");
$index_setting = $article_search->get_index_setting();
if(!empty($index_setting) || !empty($index_setting['error'] )){
    $article_search -> initialize_index();
}
$index_setting = $article_search->get_index_setting();
if(!empty($index_setting) || !empty($index_setting['error'] )){
    $article_search_pool -> initialize_index();
}

// Get first 1000 article
$index_obj = load("article_indexing");
$articles = $index_obj -> getAll(["id","userID", "postID", "visible"], ["visible"=>1, "limit"=>[0,1000], "treelevel"=>0]);
$id_list = [];
$post_id_list = [];
$article_post_map = [];
$article_tag_map = [];
foreach(range(0, 9) as $number){
    $id_list[] = [];
    $post_id_list[] = [];
}


foreach($articles as $article){
    $table_id = $article['userID']%10;
    $tag_table_id = $article['postID']%10;
    $id_list[$table_id][] = $article['postID'];
    $post_id_list[$tag_table_id][] = $article['postID'];

}


$article_post_obj = load("article_post");
$article_tag_obj = load("article_post_tag");
$tag_obj = load("article_tag");
$tag_id_list = [];

//Get article title, content and tagIDs
foreach(range(0, 9) as $i){
    $ids = $id_list[$i];
    $tag_post_ids = $post_id_list[$i];
    $article_posts = $article_post_obj->getAll("*", ["OR"=>["id"=>$ids]], "post_{$i}");
    $article_tags = [];
    if(!empty($tag_post_ids))
        $article_tags = $article_tag_obj->getAll("*", ["OR"=>["postID"=>$tag_post_ids]], "post_tag_{$i}");
    foreach($article_posts as $post){
        $article_post_map[$post['id']] = $post;
    }
    foreach($article_tags as $tag){
        if(empty($article_tag_map[$tag['postID']])){
            $article_tag_map[$tag['postID']] = [];
        }
        $article_tag_map[$tag['postID']][] = $tag["tagID"];
        $tag_id_list[] = $tag["tagID"];
    }
}

//Get tag info
$tag_id_map = [];
$tags = $tag_obj -> getAll(["id", "name"], ["OR"=>['ID'=>$tag_id_list]]);
foreach($tags as $tag){
    $tag_id_map[$tag['id']] = $tag['name'];
}

$final_list = [];
foreach(range(0, count($articles)-1) as $i){
    $postID = $articles[$i]['postID'];
    if(empty($article_post_map[$postID]))
        continue;
    $post_info = $article_post_map[$postID];
    $articles[$i]['title'] = $post_info['title']; 
    $msgbody = strip_tags($post_info['msgbody']);
    $articles[$i]['msgbody_origin'] = $post_info['msgbody'];
    $articles[$i]['msgbody'] = strip_tags($post_info['msgbody']);
    $articles[$i]['pic'] = strip_tags($post_info['pic']);
    $tag_list = [];
    if(!empty($article_tag_map[$postID])){
        foreach($article_tag_map[$postID] as $tag){
            if(!empty($tag_id_map[$tag])){
                $tag_list[] = $tag_id_map[$tag];
            }
        }
    }
    
    $articles[$i]['tags'] = $tag_list;
    $final_list[] = $articles[$i];
}
// // debug::d($articles);
// exit;

// debug::D("haha");
$test = $article_search -> add_new_articles($final_list);
$test2 = $article_search_pool -> add_new_articles($final_list);
// debug::D($test);

