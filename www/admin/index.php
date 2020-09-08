<?php
include "../inc.comm.php";

if($_SERVER['HTTP_HOST']=='cp.wenxuecity.com') go('/');

define( 'AppName', 'bbs' );

$app = new Factory();
$app->sess = true;
$app->run('manager','admin');
?>