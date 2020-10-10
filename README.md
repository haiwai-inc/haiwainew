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
