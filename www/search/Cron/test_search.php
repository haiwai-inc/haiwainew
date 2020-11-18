<?php

set_time_limit(600);
define( 'DOCUROOT',str_replace("/search/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';

$search_article = load("search_article_index");
$search_tag = load("search_tag");
$rs = $search_tag -> search_by_name("财经");
$search_category = load("search_category");
$rs = $search_category -> search_by_name("财经");
debug::d($rs);
// $search_article_noindex = load("search_article_noindex");
// debug::d($search_article->search_by_keyword("华", "12.853972"));
// $search_article_noindex->fetch_and_insert([13316, 9438]);
// debug::d($search_article_noindex -> get_by_postIDs([13316, 58471]));

// debug::d($search_article_noindex -> get_by_postIDs([1331326, 943128]));
// $result = $search_article -> search_tags([1,2,3,4,5,6,7,8,9,10]);
// debug::d($result);
// $result = $search_article_noindex -> get_postInfo([["postID"=>13316],["postID"=>9438]], "postID", false);
// debug::d($result);
// debug::d(count($result));
// debug::d(json_decode(json_encode($search_article_noindex->get([13316, 9438])),true));
// debug::d(json_decode(json_encode($search_article_noindex->get(13316)),true));
// $search_article_noindex->fetch_and_insert([13316, 9438]);
