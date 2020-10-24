<?php

set_time_limit(600);
define( 'DOCUROOT',str_replace("/search/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';

$search_article = load("search_article");

$search_article_pool = load("search_article_pool");
debug::d($search_article_pool -> get_by_postIDs([13316, 9438]));

// debug::d($search_article -> search_tags([1,2]));
// debug::d(json_decode(json_encode($search_article_pool->get([13316, 9438])),true));
// debug::d(json_decode(json_encode($search_article_pool->get(13316)),true));
// $search_article_pool->fetch_and_insert([13316, 9438]);
