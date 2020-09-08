<?php
set_time_limit(0);
define( 'DOCUROOT',str_replace("/admin/site/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

//定义AppName
define( 'AppName', 'admin/site');

//调试输出
define( 'CliDebug', true );

//SQL错误输出
$_GET['debug'] = 1;

$pid=empty($argv[1])?conf("global","global.id"):$argv[1];

$obj=load('site_configure');
$obj->clearConfig($pid,true);
