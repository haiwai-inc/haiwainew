<?php

set_time_limit(600);
define( 'DOCUROOT',str_replace("/search/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';

$search_article = load("search_article_index");

$search_article_noindex = load("search_article_noindex");
// $search_article_noindex->fetch_and_insert([13316, 9438]);
// debug::d($search_article_noindex -> get_by_postIDs([13316, 9438]));

// debug::d($search_article_noindex -> get_by_postIDs([1331326, 943128]));
// $result = $search_article -> search_tags([1,2,3,4,5,6,7,8,9,10]);
$result = $search_article -> get_postInfo([["postID"=>13316],["postID"=>13316]], "postID");
debug::d($result);
// debug::d(count($result));
// debug::d(json_decode(json_encode($search_article_noindex->get([13316, 9438])),true));
// debug::d(json_decode(json_encode($search_article_noindex->get(13316)),true));
// $search_article_noindex->fetch_and_insert([13316, 9438]);
