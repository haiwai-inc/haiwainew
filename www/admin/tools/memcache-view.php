<?php
include "../../inc.comm.php";

echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

if(empty($_GET['ip'])||empty($_GET['id'])) exit("ip or id is empty!");

$memObj = func_initMemcached($_GET['ip']);

echo "缓存中的数据内容是：<hr>";
debug::d($memObj->get($_GET['id']));
