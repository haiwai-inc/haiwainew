<?php
include( "../inc.comm.php" );
define( 'AppName', "passport" );

$app = new Factory();
if(!empty($_GET['act'])){
	if($_GET['act']=='admin') $app->admin=true;;
}
$app->sess=true;
$app->run("login","logout");