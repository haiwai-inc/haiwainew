<?php
set_time_limit(0);

//备份保存目录
define('DOCUROOT', '/pub/bak/db');
define('SHELLROOT', '/etc/cronbash');//此目录下有 db.config.php, tar.sh, db_backup.php

$obj = new dbBackup();
$obj->init( SHELLROOT."/db.config.php");

class dbBackup{
	private $config;
	
	function init($filename){
		$this->config = include $filename;
		//循环备份所有配置中的数据库
		foreach($this->config['db'] as $val){
			$info=$this->config['info'][$val[0]];
			$info['db']=$val[1];
			$this->dump($info);
		}
		
		system ("date");
		$this->debug("===================================\nDone!\n");
	}
	
	private function dump($config){
		$time = time();
		$today = date("Ymd",$time);
		$filename = DOCUROOT."/{$today}/{$config["host"]}_{$config["db"]}.tgz";
		if( file_exists($filename) ) return;

		//当日执行目录
		$path=dirname($filename);
		if( !file_exists($path) ) mkdir($path,0755);
		if( !file_exists($path."/".$config["db"]) ) mkdir($path."/".$config["db"],0755);
		
		//备份每个独立的数据表
		mysql_connect($config["host"], $config["user"], $config["pass"]);
		$result = mysql_list_tables( $config["db"] );
		while ($row = mysql_fetch_row($result)) {
			$cmd = "mysqldump --host {$config["host"]} {$config["db"]} {$row[0]} -u{$config["user"]} -p{$config["pass"]} > {$path}/{$config["db"]}/{$row[0]}.sql";
			system ($cmd);
			$this->debug($cmd);
		}
		mysql_free_result($result);

		//调用tar压缩,保存备份文件
		$cmd = SHELLROOT."/tar.sh {$filename} {$config["db"]} {$path}";
		system( $cmd );
		$this->debug($cmd);
		
		//删除临时文件
		$this->delfolder("{$path}/{$config["db"]}");

		//删除7天前的文件
		$daypath = DOCUROOT."/".date("Ymd",$time-86400*7);
		if($this->delfolder($daypath)){
			$this->debug("Deleted {$daypath}!");
		}else{
			$this->debug("NOT Deleted {$daypath}!");
		}
		
		$this->debug("{$config["host"]}:{$config["db"]} is completed!\n");
	}
	
	private function delfolder($dir){
		if(!is_dir($dir)) return false;
		if( $handle = opendir( $dir ) ){
			while( ( $file = readdir( $handle ) ) !== false ){
				if( $file != "." && $file != ".." ){
					$filepath = $dir."/".$file;
					if( is_file( $filepath ) ){
						unlink( $filepath );
					}elseif( is_dir( $filepath ) ){
						$this->delfolder($filepath);
					}
				}
			}
			closedir( $handle );
		}
		if( is_dir($dir) ) rmdir($dir);
		
		return true;
	}

	private function debug($msg){
		flush();
		echo $msg."\n";
	}
}
?>