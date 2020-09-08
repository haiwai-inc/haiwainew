<?php
set_time_limit(0);
define( 'DOCUROOT',"/pub/www/www.wenxuecity.com");
include DOCUROOT.'/inc.comm.php';

func_checkCliEnv();

$lockfileroot = "/tmp/";
$serverlist = include '/etc/cronbash/memcache-server-list.php'; //示例参见 inc/mysql-server-list.php
$userlist = include '/etc/cronbash/alert-user-list.php'; //示例参见 inc/alert-user-list.php

$title='==* 数据库系统监控警报 '.date('Y/m/d H:i:s',times::getTime()).' *==';
$node=array();

$data = time();

//逐个检测是否能正常连接
foreach($serverlist as $server) {
	//已经发生异常，跳过检测
	$lockfile=$lockfileroot.'memcache.'.$server['host'].'.log';
	if(file_exists($lockfile)) continue;
	
	$memcache=@memcache_connect($server['host'], $server['port']);
	
	if(!empty($memcache)){
		$memcache->set("crontest",$data,false, 0);
		$value = $memcache->get("crontest");
		if($value!=$data)$node[]=$server;
	}else{
		$node[]=$server;
	}
	
	echo $server['name']."...\n";
}

//发生异常
if(!empty($node)){
	/**
	$config=array(
		'host'=>'smtp.domain.com',
		'port'=>'25',
		'user'=>'username@domain.com',
		'pass'=>'****',
		'name'=>'数据库监控',
		'charset'=>'utf8',
	);*/
	$config = include "/etc/cronbash/alert_mailconfig.php";
	
	$host=array();
	foreach($node as $server){
		$lockfile=$lockfileroot.'memcache.'.$server['host'].'.log';
		touch($lockfile);
		$host[]=$server['name'].'['.$server['host'].']';
	}
	
	$content = "以下缓存服务出现异常，请立即处理！ \n".implode('; ', $host);
	
	//向用户发送警报
	foreach($userlist as $user) func_sendMail($title,$content,$content,$user['address'],$user['name'],$config);
	
	echo $title."\n";
	echo $content."\n";
}