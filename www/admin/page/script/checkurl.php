<?php
include "../../../inc.comm.php";
func_initSession();
function checkurl($url,$id,$categorise,$mid=0) {
	//检查page表中是否已经存在同类站点
	$obj = load("page_page");
	$status=$obj->page_exists($url,$id,$categorise,$mid)?0:1;
	return $status;
}

$ajax=new Ajax();
$ajax->export("checkurl");
?>