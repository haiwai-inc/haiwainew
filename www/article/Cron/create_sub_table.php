<?php
//后台操作添加
set_time_limit(0);
define( 'DOCUROOT',str_replace("/article/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

/**

CREATE TABLE post_0 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
title text NOT NULL,
msgbody longtext NOT NULL
);
CREATE TABLE post_1 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
title text NOT NULL,
msgbody longtext NOT NULL
);
CREATE TABLE post_2 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
title text NOT NULL,
msgbody longtext NOT NULL
);
CREATE TABLE post_3 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
title text NOT NULL,
msgbody longtext NOT NULL
);
CREATE TABLE post_4 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
title text NOT NULL,
msgbody longtext NOT NULL
);
CREATE TABLE post_5 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
title text NOT NULL,
msgbody longtext NOT NULL
);
CREATE TABLE post_6 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
title text NOT NULL,
msgbody longtext NOT NULL
);
CREATE TABLE post_7 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
title text NOT NULL,
msgbody longtext NOT NULL
);
CREATE TABLE post_8 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
title text NOT NULL,
msgbody longtext NOT NULL
);
CREATE TABLE post_9 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
title text NOT NULL,
msgbody longtext NOT NULL
);



CREATE TABLE post_tag_0 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
postID INT(11) NOT NULL,
tagID INT(11) NOT NULL
);
CREATE TABLE post_tag_1 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
postID INT(11) NOT NULL,
tagID INT(11) NOT NULL
);
CREATE TABLE post_tag_2 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
postID INT(11) NOT NULL,
tagID INT(11) NOT NULL
);
CREATE TABLE post_tag_3 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
postID INT(11) NOT NULL,
tagID INT(11) NOT NULL
);
CREATE TABLE post_tag_4 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
postID INT(11) NOT NULL,
tagID INT(11) NOT NULL
);
CREATE TABLE post_tag_5 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
postID INT(11) NOT NULL,
tagID INT(11) NOT NULL
);
CREATE TABLE post_tag_6 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
postID INT(11) NOT NULL,
tagID INT(11) NOT NULL
);
CREATE TABLE post_tag_7 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
postID INT(11) NOT NULL,
tagID INT(11) NOT NULL
);
CREATE TABLE post_tag_8 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
postID INT(11) NOT NULL,
tagID INT(11) NOT NULL
);
CREATE TABLE post_tag_9 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
postID INT(11) NOT NULL,
tagID INT(11) NOT NULL
);



CREATE TABLE post_upload_0 (
id INT(11) AUTO_INCREMENT PRIMARY KEY,
postID INT(11) NOT NULL,
filename text NOT NULL,
path varchar(55) NOT NULL
);






































 */


//恢复被root修改过的文件
//shell_exec("/bin/chown -R www-data:www-data ".DOCUROOT . "/cache");

















