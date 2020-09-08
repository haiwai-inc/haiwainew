<?php
include '../../inc.comm.php';
func_initSession();

$ip = isset($_GET['ip'])?$_GET['ip']:http::getIP();

$info = ($ip=='127.0.0.1')?array():geoip_record_by_name($ip);
$info = array_merge(array('ip'=>$ip),$info);

if( $_SESSION['UserLevel']==6 || $_SESSION['UserLevel']==7 ){
	$memcache = func_initMemcached('cache01');
	$val = $memcache->get('web-vpn-'.$ip);
	$memcache->set('web-vpn-'.$ip, 1 ,false , 3600);
	
	//记录log
	if(empty($val)){
		$data = array(
				'username'=>$_SESSION['UserName'],
				'post'=>serialize($_POST),
				'get'=>serialize($_GET),
				'server'=>serialize($_SERVER),
				'cookie'=>serialize($_COOKIE),
				'type'=>'ip=>'.$ip,
				'datetime'=>time()
		);
		$obj_error_log = load("members_error_log");
		$obj_error_log->Insert($data);
	}
	
	echo "{$ip} is OK!";
}