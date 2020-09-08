<?php
include "../../../inc.comm.php";
func_initSession();
function checkapp($tpl,$pid,$cate,$id) {
	$cate=(in_array($cate,array('Portal','Alias','Folder')))?'page':$cate;
		
	//检查page表中是否已经存在同类站点
	$obj = load($cate.'_cfg_app');
	$rs=$obj->getOne(array('id'),array('tpl'=>$tpl,'pid'=>$pid,'id,!='=>$id));
	$status=empty($rs)?1:0;
	return $status;
}

$ajax=new Ajax();
$ajax->export("checkapp");
?>