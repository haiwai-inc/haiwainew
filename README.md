https://docs.google.com/spreadsheets/d/1gwQAMukqeHd4c8VjvfS5bUGke7yYPwQgoXez1_LOZT8/edit#gid=220659139
https://docs.google.com/spreadsheets/d/1lgSyIDChfk1zHacQMlKRGh7-GeImpIyPah79WeG5Sjs/edit#gid=0

# haiwainew
blog

# run vue
npm run serve 
npm install  
npm run build

# cronjob
# account
# 发送用户邮件
*/1 * * * * root /usr/bin/php /pub/www/haiwainew.com/www/account/Cron/send_email.php

# blog
# 同步文学城博客推荐
*/5 * * * * root /usr/bin/php /pub/www/haiwainew.com/www/blog/Cron/sync_recommend_post.php
#生成最热博主
0 */12 * * * root /usr/bin/php /pub/www/haiwainew.com/www/blog/Cron/generate_hot_blogger.php
# 生成最热标签
0 */12 * * * root /usr/bin/php /pub/www/haiwainew.com/www/blog/Cron/generate_hot_tag.php

# search
#同步修改的文章到搜索引擎
*/1 * * * * root /usr/bin/php /pub/www/haiwainew.com/www/search/Cron/update_search.php
#同步博客名和用户名到搜索引擎
*/5 * * * * root /usr/bin/php /pub/www/haiwainew.com/www/search/Cron/update_blogger.php
#同步标签和文集到搜索引擎
*/5 * * * * root /usr/bin/php /pub/www/haiwainew.com/www/search/Cron/update_category_tag.php

# article
# 发布定时文章
*/1 * * * * root /usr/bin/php /pub/www/haiwainew.com/www/article/Cron/publish_article_timer.php
# 抓取文章图片
# */5 * * * * root /usr/bin/php /pub/www/haiwainew.com/www/article/Cron/extract_article_pic.php

# count
# 同步文章计数到数据库
*/5 * * * * root /usr/bin/php /pub/www/haiwainew.com/www/count/Cron/sync_article_count_read.php
# 同步博主计数到数据库
0 */12 * * * root /usr/bin/php /pub/www/haiwainew.com/www/count/Cron/sync_blogger_count_read.php

# tmp mpdified file
blog/Cron/generate_hot_blogger.php   ->  file_domain

# elastic search
search/Cron/init_indexes.php // create and initialize search index for article and article pool
search/Cron/update_search.php [all] [iteration_number]// update new article from database to es, [all] ignore 15 min limit, [iteration_number] iteration_number*200 = total number indexed
search/Cron/update_blogger.php  //update blogger and username
search/Cron/update_category_tag.php  //update category and tag names
password: haiwai2020





# 清洗数据
php www/article/Cron/clear_blog_data.php wxc123456
php www/search/Cron/init_indexes.php haiwai2020

php www/blog/Cron/sync_recommend_post.php
php www/search/Cron/update_search.php all
php www/search/Cron/update_blogger.php 
php www/search/Cron/update_category_tag.php 














