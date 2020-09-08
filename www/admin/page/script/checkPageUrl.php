<?php
/**
 * 此程序仅 /admin/page/manager add时在调用，用于子级项目下的page单元创建时检查是否已经存在相关页面
 */
include "../../../inc.comm.php";
func_initSession();
function checkurl($url,$id,$categorise) {
	//检查page表中是否已经存在同类站点
	$obj = load($categorise."_page");
	$status=$obj->page_exists($url,$id,$categorise,'all')?0:1;
	return $status;
}

$ajax=new Ajax();
$ajax->export("checkurl");
?>