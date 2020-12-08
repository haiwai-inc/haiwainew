# haiwainew
blog

# run vue
npm run serve 
npm install  

# cronjob
*/5 * * * * root /usr/bin/php www/blog/Cron/sync_recommend_post.php 
*/5 * * * * root /usr/bin/php www/article/Cron/extract_article_pic.php
*/5 * * * * root /usr/bin/php www/count/Cron/sync_article_count_read.php

0 */12 * * * root /usr/bin/php www/count/Cron/sync_blogger_count_read.php  
0 */12 * * * root /usr/bin/php www/blog/Cron/generate_hot_blogger.php
0 */12 * * * root /usr/bin/php www/blog/Cron/generate_hot_tag.php


# tmp mpdified file
blog/Cron/generate_hot_blogger.php   ->  file_domain

# elastic search
search/Cron/init_indexes.php // create and initialize search index for article and article pool
search/Cron/update_search.php [all] [iteration_number]// update new article from database to es, [all] ignore 15 min limit, [iteration_number] iteration_number*200 = total number indexed
search/Cron/update_blogger.php  //update blogger and username
search/Cron/update_category_tag.php  //update category and tag names

password: haiwai2020
