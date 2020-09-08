<?php
include "../../../inc.comm.php";

func_initSession();

function levelInit($id,$level) {
	$obj=load("site_admin");

	$rs=$obj->getAll(array("name","id","pid"),array("cate"=>0,"mid"=>$id));
	$sign=empty($rs)?false:true;

	$rs=array(
	$level+1,//当前级别
	getList($rs),
	$id,//上级标识
	$sign,
	);
	return $rs;
}

function getList($rs){
	$list=array();
	if(!empty($rs)){
		foreach($rs as $val){
			$list[]=array($val["name"],$val["id"]);
		}
	}
	return $list;
}

$ajax=new Ajax();
$ajax->export("levelInit");
?>