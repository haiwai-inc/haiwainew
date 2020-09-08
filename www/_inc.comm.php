<?php
// 系统版本号,根据实际部署实例的不同，分别定义
define( 'systemVersion', 'www.domain.com' );

//定义是否为生产环境
define( 'PRODUCTION', '0');

//设置用于命令行调用的服务器变量
if(empty($_SERVER['HTTP_HOST']))$_SERVER['HTTP_HOST']="www.domain.com";

// 字体路径
define( 'SRCROOT', "/Your/Location/framework/source" );

// 定义根路径
if(!defined('DOCUROOT')) define( 'DOCUROOT', dirname( __FILE__ ) );

// 定义CDN配置
define( 'CDNSERVER', 'cdn.domain.com' );

// 定义数据库配置
define( 'DBSETTING', DOCUROOT.'/inc.db.php' );

// 加载公共系统函数
require_once( DOCUROOT."/vendor/autoload.php" );
include( DOCUROOT."/include/_functions.php" );

// 定义当前服务器默认时区
define( 'TIMEZONE',  'America/Los_Angeles'); 

//设置调试IP
define( 'DEBUGIP', '127.0.0.1' );

// 测试用户信息
define( 'USERSESS', "_inc.sess.php" );

// 全局变量存储参数
//$_GlobalConfig = array( 'host' => '127.0.0.1', 'port' => '11211' );

//加载多项目子目录
//$_GlobalSystem = array( 'admin','space','service','office' );

// 定义全局配置文件,不使用数据库定义多站模块
//define( 'GLOBALCONF', DOCUROOT.'/inc.conf.php' );

//使用集中式模板
//define( 'centreModel', true);

//启用语言支持
//define( 'LANGBASE', isset($_COOKIE['lang'])?$_COOKIE['lang']:conf('global','lid') );

//数据库查询限量
//define( 'SqlMaxMemorySize', 16777216 );

// 加载程序配置文件
include( DOCUROOT."/include/_init.php" );

//用于调试
debug::g();
define( 'AppDebug','1');
//define( 'SmartyDebug','1');
?>