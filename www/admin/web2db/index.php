<?php
set_time_limit(0);
include '../../inc.comm.php';
define( 'AppName', 'admin/web2db');

$app = new Factory();
$app->sess=true;
$app->run('web2db');