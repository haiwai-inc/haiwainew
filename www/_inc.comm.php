<?php
// 系统版本号,根据实际部署实例的不同，分别定义
define( 'systemVersion', 'local.haiwainew.com' );

//设置用于命令行调用的服务器变量
if(empty($_SERVER['HTTP_HOST']))$_SERVER['HTTP_HOST']="local.haiwainew.com";

// 字体路径
define( 'SRCROOT', "/Home/sida/source" );

// 定义根路径
if(!defined('DOCUROOT')) define( 'DOCUROOT', dirname( __FILE__ ) );

// 定义CDN配置
define( 'CDNSERVER', 'local.haiwainew.com' );

// 定义数据库配置
define( 'DBSETTING', DOCUROOT.'/inc.db.php' );

// 加载公共系统函数
require_once( DOCUROOT."/vendor/autoload.php" );
include( DOCUROOT."/include/_functions.php" );

// 定义当前服务器默认时区
define( 'TIMEZONE',  'UTC-0800');//TODO PHP daylight saving time detection

//设置调试IP
define( 'DEBUGIP', '127.0.0.1' );

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



















?>

