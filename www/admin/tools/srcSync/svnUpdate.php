<?php
/*每分钟自动执行，使用svn同步同步源码*/
define( 'ROOT', str_replace("/admin/tools/srcSync","",dirname( __FILE__ )));
include ROOT.'/inc.comm.php';
func_checkCliEnv();

$obj_repository=new repository();
echo $obj_repository->update_svn();

//更新ads.txt文件
$obj = load('ad_adsfile');
$obj->init_ads_file();

//生成首页静态文件
$app = load("pages_homepage");
$app->mkhtml(DOCUROOT.'/index.html');
?>