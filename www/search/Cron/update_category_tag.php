<?php
define('DOCUROOT', str_replace("/search/Cron", "", dirname(__FILE__)));
include DOCUROOT . '/inc.comm.php'; 
$search_tag = load("search_tag");
$article_tag = load("article_tag");
$search_category = load("search_category");
$blog_category = load("blog_category");


$first_update_time = 0;
$iter = 0;
$total = 0;
while(true){    
    $tags = $article_tag->getAll("*", ["visible"=>1, "limit"=>[$iter*200,200]]);
    if(count($tags)==0){
        break;
    }
    $total += count($tags);
    $search_tag->add_new_tags($tags);
    echo("$total tags updated\n");
    $iter++;
}

$iter = 0;
$total = 0;
while(true){    
    $categories = $blog_category->getAll("*", ["visible"=>1, "limit"=>[$iter*200,200]]);
    if(count($categories)==0){
        break;
    }
    $total += count($categories);
    $search_category->add_new_categories($categories);
    echo("$total categories updated\n");
    $iter++;
}