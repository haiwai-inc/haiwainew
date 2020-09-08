<?php
/*每分钟自动执行，使用svn同步同步源码*/
define( 'DOCUROOT', dirname( __FILE__ ) );
define( 'systemVersion', 'wenxuecity.com' );

if(!is_file( DOCUROOT."/inc.updateid.php" ))exit();
$localIP=include DOCUROOT."/inc.updateid.php";

$obj=@memcache_connect("sourceNode", "11211");	
$val = $obj->get(systemVersion."svn_".$localIP);
$version = $obj->get(systemVersion."svn_ver_".$localIP);

if(!empty($val)){
	$obj->delete(systemVersion."svn_".$localIP);
	if(!empty($version))$obj->delete(systemVersion."svn_ver_".$localIP);
	
	if($val=='root'){
		$path = DOCUROOT;
	}else{
		if(substr($val,0,1)!='/') $val = "/".$val;
		if(substr($val,0,strlen(DOCUROOT))!=DOCUROOT) $val = DOCUROOT . $val;
		$path = $val;
	}
	
	$log = "";
	if(empty($version)){
		$log.= "/usr/bin/svn update {$path}\n";
		$log.= shell_exec("/usr/bin/svn update {$path}");
	}else{
		$log.= "/usr/bin/svn -r {$version} update {$path}\n";
		$log.= shell_exec("/usr/bin/svn -r {$version} update {$path}");
	}
	
	if(!empty($log)) file_put_contents("/var/log/svnUpdate.log", $log);
}
?>