<?php
set_time_limit(600);
define('DOCUROOT', str_replace("/search/Cron", "", dirname(__FILE__)));
include DOCUROOT . '/inc.comm.php';
if (count($argv) < 2) {
    echo ("Password needed\n");
    exit;
} else {
    if ($argv[1] != "haiwai2020") {

        echo ("Wrong password\n");
        exit;
    }
}
$target = "all";
if (count($argv) > 2) {
    $target = $argv[2];
}

if ($target == "all" || $target == "article_index") {
    $search_article = load("search_article_index");
    $index_setting  = json_decode(json_encode($search_article->get_index_setting()), true);
    if (!empty($index_setting) && empty($index_setting['error'])) {
        $search_article->indexdel();
    }
    $search_article->initialize_index();
}

if ($target == "all" || $target == "article_noindex") {
    $search_article_noindex = load("search_article_noindex");
    $index_setting          = json_decode(json_encode($search_article_noindex->get_index_setting()), true);
    if (!empty($index_setting) && empty($index_setting['error'])) {
        $search_article_noindex->indexdel();
    }
    $search_article_noindex->initialize_index();
}

if ($target == "all" || $target == "blogger") {
    $search_blogger = load("search_blogger");
    $index_setting  = json_decode(json_encode($search_blogger->get_index_setting()), true);
    if (!empty($index_setting) && empty($index_setting['error'])) {
        $search_blogger->indexdel();
    }
    $search_blogger->initialize_index();
}
