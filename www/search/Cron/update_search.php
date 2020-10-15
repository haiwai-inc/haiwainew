<?php

set_time_limit(0);
define( 'DOCUROOT',str_replace("/article/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';

$search_article = load("search_article");

$search_article_pool = load("search_article_pool");
// debug::d($search_article -> search_tags(["历史怀旧", "秘密档案"]));
debug::d($search_article_pool->get([13316, 9438]));
