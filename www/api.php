<?php
include 'inc.comm.php';


//检查及初始化参数
if( empty($_GET['app']) || empty($_GET['class']) || empty($_GET['func']) ) {
	$obj = new Api();
	$obj->notfound();
}

$app = conf('appname',$_GET['app']);
$class = $_GET['class'];
$func = $_GET['func'];

//定义app
define( 'AppName', $app );

//加载api文件
$filename = DOCUROOT."/{$app}/api/{$class}.php";
if( file_exists($filename) ) require_once($filename);

//执行API
$obj=new $class();
$obj->run($func);

//test1