<?php
define('DOCUROOT', str_replace("/search/Cron", "", dirname(__FILE__)));
include DOCUROOT . '/inc.comm.php';
$search_blogger = load("search_blogger");
$blogger_obj = load ("blog_blogger");
$user_obj = load("account_user");
// $first_update_time = time() - 5*60;
$first_update_time = 0;
$iter = 0;
$total = 0;
while(true){    
    $bloggers = $blogger_obj->getAll(["id", "userID", "name"], ["update_date, >="=>$first_update_time, "limit"=>[$iter*200,200]]);
    if(count($bloggers)==0){
        break;
    }
    $total += count($bloggers);
    $bloggers = $user_obj->get_basic_userinfo($bloggers, "userID");
    $search_blogger->add_new_bloggers($bloggers);
    echo("$total blogger updated\n");
    $iter++;
}