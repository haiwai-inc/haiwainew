<?php
define('DOCUROOT', str_replace("/search/Cron", "", dirname(__FILE__)));
include DOCUROOT . '/inc.comm.php';
$search_blogger = load("search_blogger");
$blogger_obj = load ("blog_blogger");
$user_obj = load("account_user");
$search_category = load("search_category");
$blog_category = load("blog_category");
if (count($argv) < 2) {
    $first_update_time = times::gettime() - 30*60;
} else {
    $first_update_time = times::gettime() - intval($argv[1])*60;
}

// $first_update_time = 0;
$iter = 0;
$total = 0;
$total_category = 0;
while(true){    
    $bloggers = $blogger_obj->getAll(["id", "userID", "name"], ["update_date, >="=>$first_update_time, "limit"=>[$iter*200,200]]);
    if(count($bloggers)==0){
        break;
    }
    $total += count($bloggers);
    $bloggerIDs = [];
    foreach($bloggers as $blogger){
        $bloggerIDs[] = $blogger['id'];
    }
    $bloggers = $user_obj->get_basic_userinfo($bloggers, "userID");
    $search_blogger->add_new_bloggers($bloggers);
    $categories = $blog_category->getAll("*", ["OR"=>['bloggerID'=>$bloggerIDs]]);
    $total_category += count($categories);
    $search_category->add_new_categories($categories);
    echo("$total blogger updated\n");
    echo("$total_category category updated\n");
    $iter++;
}
<<<<<<< HEAD

=======
>>>>>>> 319df08001019d93aa9258b99872029a8d1f25d6
