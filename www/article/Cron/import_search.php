<?php
set_time_limit(0);
define( 'DOCUROOT',str_replace("/article/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';

$article_search = load("article_search");
$index_setting = $article_search->get_index_setting();
if(!empty($index_setting) || !empty($index_setting['error'])){
    $article_search -> initialize_index();
}

