<?php
include '../../inc.comm.php';
define( 'AppName', "admin/page" );

$app = new Factory();
$app->sess=true;
$app->run("view");