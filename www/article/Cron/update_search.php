<?php

set_time_limit(0);
define( 'DOCUROOT',str_replace("/article/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';

$article_search = load("article_search");

$article_search_pool = load("article_search_pool");
// debug::d($article_search -> search_tags(["历史怀旧", "秘密档案"]));
debug::d($article_search_pool->get([13316, 9438]));
