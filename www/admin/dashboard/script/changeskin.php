<?php
require_once ("../../../inc.comm.php");

func_initSession();

//设置在线
function dochangeskin($val) {
	setcookie("Cookie_CfgID",$val,time()+31536000,"/",conf('global','session.sessiondomain'));
	return $val;
}

$ajax=new Ajax();
$ajax->export("dochangeskin");
?>