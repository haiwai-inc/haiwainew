<?php
set_time_limit(600);
define('DOCUROOT', str_replace("/search/Cron", "", dirname(__FILE__)));
include DOCUROOT . '/inc.comm.php';
if(count($argv)<2){
    echo("Password needed\n");
    exit;
}
else {
    if($argv[1] != "haiwai2020") {

        echo("Wrong password\n");
        exit;
    }
}

$search_article      = load("search_article");
$search_article_pool = load("search_article_pool");
$article_post_obj = load("article_post");
$article_tag_obj  = load("article_post_tag");
$article_buzz_obj  = load("article_post_buzz");
$tag_obj          = load("article_tag");
$index_setting = json_decode(json_encode($search_article->get_index_setting()),true);
if (!empty($index_setting) && empty($index_setting['error'])) {
    $search_article->indexdel();
}
$search_article->initialize_index();
$index_setting = json_decode(json_encode($search_article_pool->get_index_setting()),true);
if (!empty($index_setting) && empty($index_setting['error'])) {
    $search_article_pool->indexdel();
}
$search_article_pool->initialize_index();
// Get first 1000 article
$index_obj    = load("article_indexing");
$group_number = 0;
$indexed_article_number = 0;
$indexed_article_pool_number = 0;
$first_update_time = time() - 5*60;
while (true) {
    try{
    $articles = $index_obj->getAll(["id", "userID", 'typeID', "postID", "visible", "create_date", "like_date", "edit_date", "treelevel"], ["visible" => 1, "limit" => [$group_number * 1000, 1000], "edit_date,>=" => $first_update_time]);
    $group_number++;
    while(count($articles) == 0){
        break;
    }
    $indexed_article_pool_number += count($articles);
    $index_list = [];
    $id_list          = [];
    $post_id_list     = [];
    $article_post_map = [];
    $article_tag_map  = [];
    $article_buzz_map = [];
    foreach (range(0, 9) as $number) {
        $id_list[]      = [];
        $post_id_list[] = [];
    }

    foreach ($articles as $article) {
        $table_id                      = $article['userID'] % 10;
        $tag_table_id                  = $article['postID'] % 10;
        $id_list[$table_id][]          = $article['postID'];
        $post_id_list[$tag_table_id][] = $article['postID'];
        $index_list[] = $article['postID'];
        unset($table_id);
        unset($tag_table_id);
    }

    $tag_id_list      = [];
    
    
//Get article title, content and tagIDs
    foreach (range(0, 9) as $i) {
        $ids           = $id_list[$i];
        $tag_post_ids  = $post_id_list[$i];
        $article_posts = $article_post_obj->getAll("*", ["OR" => ["id" => $ids]], "post_{$i}");
        $article_tags  = [];
        $article_buzz = [];
        if (!empty($tag_post_ids)) {
            $article_tags = $article_tag_obj->getAll("*", ["OR" => ["postID" => $tag_post_ids]], "post_tag_{$i}");
            $article_buzz = $article_buzz_obj->getAll("*", ["OR" => ["postID" => $tag_post_ids]], "post_buzz_{$i}");
        }

        foreach ($article_posts as $post) {
            $article_post_map[$post['id']] = $post;
        }

        foreach ($article_tags as $tag) {
            if (empty($article_tag_map[$tag['postID']])) {
                $article_tag_map[$tag['postID']] = [];
            }
            $article_tag_map[$tag['postID']][] = $tag["tagID"];
            $tag_id_list[]                     = $tag["tagID"];
        }

        foreach ($article_buzz as $buzz){
            if (empty($article_buzz_map[$buzz['postID']])) {
                $article_buzz_map[$buzz['postID']] = [];
            }
            $article_buzz_map[$buzz['postID']][] = $buzz['userID'];
        }

        unset($ids);
        unset($tag_post_ids);
        unset($article_posts);
        unset($article_tags);
    }

//Get tag info
    $tag_id_map = [];
    $tags       = $tag_obj->getAll(["id", "name"], ["OR" => ['ID' => $tag_id_list]]);
    foreach ($tags as $tag) {
        $tag_id_map[$tag['id']] = $tag['name'];
    }
    unset($tags);

    $final_list = [];
    $final_pool_list = [];

    foreach (range(0, count($articles) - 1) as $i) {
        $postID = $articles[$i]['postID'];
        if (empty($article_post_map[$postID])) {
            continue;
        }

        $post_info                      = $article_post_map[$postID];
        $articles[$i]['title']          = $post_info['title'];
        $msgbody                        = strip_tags($post_info['msgbody']);
        $articles[$i]['msgbody_origin'] = $post_info['msgbody'];
        $articles[$i]['msgbody']        = strip_tags($post_info['msgbody']);
        $articles[$i]['pic']            = strip_tags($post_info['pic']);
        $articles[$i]['buzz']  = [];
        if(!empty($article_buzz_map[$postID])){
            $articles[$i]['buzz']            = $article_buzz_map[$postID];
        }
        $tag_list                       = [];
        if (!empty($article_tag_map[$postID])) {
            $tag_list = $article_tag_map[$postID];
            // foreach ($article_tag_map[$postID] as $tag) {
            //     if (!empty($tag_id_map[$tag])) {
            //         $tag_list[] = $tag_id_map[$tag];
            //     }
            // }
        }

        $articles[$i]['tags'] = $tag_list;
        if ($articles[$i]['treelevel'] == 0) {
            $final_list[] = $articles[$i];
            $indexed_article_number++;
        }
        $final_pool_list[] = $articles[$i];
        unset($msgbody);
        unset($post_info);
        unset($tag_list);
        unset($postID);
    }

// // debug::d($articles);
    // exit;

// debug::D("haha");
    $test  = $search_article->add_new_articles($final_list);
    $test2 = $search_article_pool->add_new_articles($final_pool_list);
    debug::d($test);
    debug::d($test2); 
    echo("<br>Total indexed article: ".$indexed_article_number);
    echo("<br>Total article pool: ".$indexed_article_pool_number);

    unset($final_list);
    unset($final_pool_list);
    unset($test);
    unset($test2);
    unset($id_list);
    unset($post_id_list);
    unset($article_post_map);
    unset($article_tag_map);
    unset($articles);
    unset($tag_id_list);
    unset($tag_id_map);
    // $index_obj->update(['is_sync'=>1], ['OR'=>['id'=>$index_list]]);
    unset($index_list);
    // continue;
    }
    catch(Exception $e){
        debug::d($e);
        continue;
    }
// debug::D($test);
}


