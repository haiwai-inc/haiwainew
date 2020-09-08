<?php
include "../../inc.comm.php";
func_initSession();
//http://cn1.rigol.com/admin/dashboard/tools.php?id=1&type=global

$siteid=empty($_GET['id'])?1:$_GET['id'];
$menutype=empty($_GET['type'])?'global':$_GET['type'];
$only=empty($_GET['only'])?false:true;

$obj=load('dashboard_menu');

if( !$only ) $menulist=$obj->getMenuList($siteid,$menutype);

if( $only && $menutype=='global') $menulist=$obj->getGloablMenu($siteid);

if( $only && $menutype=='admin') $menulist=$obj->getAdminMenu(0);

debug::d($menulist);
?>