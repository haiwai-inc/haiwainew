<?php
set_time_limit(600);
define('DOCUROOT', str_replace("/search/Cron", "", dirname(__FILE__)));
include DOCUROOT . '/inc.comm.php';
$first_update_time = time() - 5*60;

if(count($argv)>1){
    if($argv[1] == "all"){
        $first_update_time = 0;
    }
}

$search_article      = load("search_article_index");
$search_article_noindex = load("search_article_noindex");
$article_post_obj = load("article_post");
$article_tag_obj  = load("article_post_tag");
$article_buzz_obj  = load("article_post_buzz");
$tag_obj          = load("article_tag");
// Get first 1000 article
$index_obj    = load("article_indexing");
$group_number = 0;
$iterations_max = 0;
if(count($argv)>2){
    if(is_numeric($argv[2])){
        $iterations_max = intval($argv[2]);
    }
}
$indexed_article_number = 0;
$not_indexed_article_number = 0;
while ($iterations_max==0 || $group_number < $iterations_max) {
    try{
        
    //主表查询
    $final_list = $index_obj -> get_article_info(["visible" => 1, "limit" => [$group_number * 200, 200], "edit_date,>=" => $first_update_time]);
    debug::d(count($final_list));
    exit;
    
    
    
    $group_number++;
    if(count($final_list) < 1){
        break;
    }

    $indexed_article_number += $search_article->add_new_articles($final_list);
    $not_indexed_article_number += $search_article_noindex->add_new_articles($final_list);
    echo("<br>Total indexed article: ".$indexed_article_number."\n");
    echo("<br>Total not indexed article pool: ".$not_indexed_article_number."\n");
    }
    catch(Exception $e){
        debug::d($e);
        continue;
    }
}




























