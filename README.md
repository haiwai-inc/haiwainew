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
