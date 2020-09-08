<?php
include( "../../inc.comm.php" );
define( 'AppName', "admin/badword" );

$app = new Factory();
$app->sess=true;
$app->admin=true;
$app->run("index");