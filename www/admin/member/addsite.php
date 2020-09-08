<?php
/**
 * 向用户授权新的网站
 */
include( "inc.config.php" );

$app = new Factory();
$app->sess=true;
$app->admin=true;
$app->lang[]='cmd';
$app->run("addsite");
?>