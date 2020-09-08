<?php
include("../../inc.comm.php");
$ip = http::getIP();
if(!in_array($ip,array('96.90.222.145','96.90.222.146'))) exit("<h1>It works!");

func_initSession();

$config=conf();
debug::d($config);