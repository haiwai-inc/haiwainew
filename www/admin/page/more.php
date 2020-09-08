<?php
include '../../inc.comm.php';

$objPage=load('page_page');
$pageinfo=$objPage->getPage(conf('global','global.id'),$_GET['page']);

$objPageApp=load('page_cfg_app');
$appinfo=$objPageApp->getApp($pageinfo['id'],$_GET['sort']);

//根据应用类型设定相应的AppName
$appPool=conf('admin.page.modlist');
define( 'AppName', $appPool[$appinfo['app']]['appname']);

$app = new Factory();
$app->run( $appPool[$appinfo['app']]['moreact'],'more' );
?>