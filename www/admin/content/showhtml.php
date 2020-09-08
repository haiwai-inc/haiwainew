<?php
include 'inc.config.php';

$app = new Factory();

//$app->cache=3600*4;
//$app->cacheDeep=3;

$app->run("articleContentView");