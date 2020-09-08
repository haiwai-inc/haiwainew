<?php
include "inc.config.php";

$app = new Factory();
$app->admin=true;
$app->sess=true;
$app->run("menu");
?>