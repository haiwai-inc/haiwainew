<?php
//用户界面
if(!defined("USERFACE")){
	if (isset ($_COOKIE["Cookie_CfgID"])) {
		define( "USERFACE",$_COOKIE["Cookie_CfgID"]);
	} else {
		setcookie("Cookie_CfgID", "green", time() + 2592000, "/",conf('global','session.sessiondomain'));
		define( "USERFACE","blue");
	}
}
?>