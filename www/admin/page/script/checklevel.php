<?php
include "../../../inc.comm.php";
func_initSession();
function checklevel($url,$pid,$cate,$id) {
	$obj = load($cate.'_page_level');
	$rs=$obj->getOne(array('id'),array('url'=>$url,'pid'=>$pid,'id,!='=>$id));
	$status=empty($rs)?1:0;
	return $status;
}

$ajax=new Ajax();
$ajax->export("checklevel");
?>