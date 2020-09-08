<?php
require_once("../../inc.comm.php");
define( 'AppName', 'admin/label' );

$app = new Factory();
$app->sess	=true;
$app->admin	=true;
$app->lang = array('cmd');
$app->run('manager');
?>