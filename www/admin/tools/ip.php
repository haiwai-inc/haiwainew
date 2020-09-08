<?php
include '../../inc.comm.php';
func_initSession();

$ip = isset($_GET['ip'])?$_GET['ip']:http::getIP();

$info = ($ip=='127.0.0.1')?array():geoip_record_by_name($ip);
$info = array_merge(array('ip'=>$ip),$info);

debug::d($info);
if(!empty($_SESSION['UserLevel'])&&isset($_GET['Server'])) debug::D($_SERVER);