<?php
set_time_limit(0);
define( 'DOCUROOT',str_replace("/admin/page/Cron","",dirname( __FILE__ )));
include DOCUROOT.'/inc.comm.php';
func_checkCliEnv();

/**
 * demo:
 * 同步页面数据
 * php sync.php 1 63 data
 * 
 * 同步页面下的单分类文章
 * php sync.php 44 99 article
 */


$sid=empty($argv[1])?0:intval($argv[1]);
$tid=empty($argv[2])?0:intval($argv[2]);
$type=empty($argv[3])?0:$argv[3];

if(empty($sid)) exit("source page cann't empty!\n");
if(empty($tid)) exit("object page cann't empty!\n");
if(empty($type)) exit("sync type cann't empty!\n");

$obj=load('page_sync');
if($type=='data')$obj->syncData($sid,$tid);
if($type=='article')$obj->syncArticle($sid,$tid);