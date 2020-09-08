<?php
set_time_limit(0);
define( 'DOCUROOT',"/pub/www/www.wenxuecity.com");
include DOCUROOT.'/inc.comm.php';

func_checkCliEnv();

/**
 * 部署说明：
 * 
 * 参考示例文件，设置
 * /etc/cronbash/mysql-server-list.php
 * /etc/cronbash/alert-user-list.php
 * /etc/cronbash/alert_mailconfig.php
 * 
 * 设置当前文件的 DOCUROOT
 * 设置crontab自动运行当前文件
 */


$lockfileroot = "/tmp/";
$serverlist = include '/etc/cronbash/mysql-server-list.php'; //示例参见 serverlist/mysql-server-list.php
$userlist = include '/etc/cronbash/alert-user-list.php'; //示例参见 serverlist/alert-user-list.php

$title='==* 数据库系统监控警报 '.date('Y/m/d H:i:s',times::getTime()).' *==';
$node=array();

//逐个检测数据库是否能正常连接
foreach($serverlist as $server) {
	$lockfile=$lockfileroot.'db.'.$server['host'].'.log';
	
	//已经发生异常，跳过检测
	if(file_exists($lockfile)) continue;

	if(!$conn=mysql_connect($server['host'],$server['user'],$server['pass'],true)) {
		$node[]=$server;
	}else{
		//检测从属服务器的同步状态
		if($server['type']=='slave'){
			
			$query = mysql_query('show slave status',$conn);
			$result=array();
			while( $row = mysql_fetch_array( $query,MYSQL_ASSOC ) ) {
				$result[]=$row;
			}
			mysql_free_result($query);
			
			$result=$result[0];
			
			if($result['Slave_IO_Running']=='No'||$result['Slave_SQL_Running']=='No'){
				$server['slavefail']=true;
				$node[]=$server;
			}
		}
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
	$slave=array();
	foreach($node as $server){
		$lockfile=$lockfileroot.'db.'.$server['host'].'.log';
		touch($lockfile);
		$host[]=$server['name'].'['.$server['host'].']';
		
		if($server['slavefail'])$slave[]=$server['host'];
	}
	
	$content = "以下数据库出现异常，请立即处理！ \n".implode('; ', $host);
	if(!empty($slave)) $content.="\n\n以下从属服务器同步出现问题，请立即处理！ \n".implode('; ', $slave);
	
	//向用户发送警报
	foreach($userlist as $user) func_sendMail($title,$content,$content,$user['address'],$user['name'],$config);
	
	echo $title."\n";
	echo $content."\n";
}