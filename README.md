# haiwainew
blog

# run vue
npm run serve 
npm install  

# cronjob
*/5 * * * * root /usr/bin/php www/blog/Cron/sync_recommend_post.php 
*/5 * * * * root /usr/bin/php www/article/Cron/extract_article_pic.php
0 */12 * * * root /usr/bin/php www/blog/Cron/generate_hot_blogger.php
0 */12 * * * root /usr/bin/php www/blog/Cron/generate_hot_tag.php
0 0 */1 * * root /usr/bin/php www/article/Cron/sync_article_count.php   //runing now

# testing userID
1


# tmp mpdified file
blog/Cron/generate_hot_blogger.php   ->  file_domain

# elastic search
search/Cron/import_search.php // create and initialize search index for article and article pool
search/Cron/update_search.php // update new article from database to es