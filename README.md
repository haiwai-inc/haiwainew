# haiwainew
blog

# run vue
npm run serve 
npm install  

# cronjob
*/5 * * * * root /usr/bin/php www/blog/sync_recommend_post.php 
*/5 * * * * root /usr/bin/php www/article/extract_article_pic.php
0 */12 * * * root /usr/bin/php www/blog/generate_hot_blogger.php
0 */12 * * * root /usr/bin/php www/blog/generate_hot_tag.php
0 0 */1 * * root /usr/bin/php www/article/sync_article_count.php

# testing userID
1
