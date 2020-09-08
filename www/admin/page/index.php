<?php
include( "./inc.config.php" );

$app = new Factory();
$app->sess=true;
$app->admin=true;
$app->run("app");
?>