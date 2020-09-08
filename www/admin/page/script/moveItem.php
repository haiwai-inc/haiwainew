<?php
require_once ("../../inc.comm.php");

func_initSession();

function levelInit($pid,$mid,$appid,$appcate,$level) {
	$obj=load("article_level");

	$rs=$obj->getAll(array("name","id"),array("pid"=>$pid,"mid"=>$mid,'app'=>$appid,'cate'=>$appcate));
	$sign=empty($rs)?false:true;

	$rs=array(
		$level+1,//当前级别 [0]
		getList($rs),//子级数组 [1]
		$mid,//上级标识 [2]
		$sign,//是否有子级 [3]
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