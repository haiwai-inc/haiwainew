<?php
include("../../inc.comm.php");
$ip = http::getIP();
if(!in_array($ip,array('96.90.222.145','96.90.222.146','127.0.0.1'))) exit("<h1>It works!");

phpinfo();