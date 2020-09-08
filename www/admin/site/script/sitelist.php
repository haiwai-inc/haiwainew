<?php
include "../../../inc.comm.php";
func_initSession();

function doUp($id,$sort){
	if(!func_checkAuth('site_list','admin')) return false;
	order($id,$sort,"<");
	return $id;
}
function doDown($id,$sort){
	if(!func_checkAuth('site_list','admin')) return false;
	order($id,$sort,">");
	return $id;
}
function order($id,$sort,$item){
	$conn=func_getDB('main');
	$source=$conn->getOne('*',array('id'=>intval($id)),"page");
	if(!empty($source)) {
		$fields= array('id','order');
		$where= array('SQL'=>"`order` ".$item.intval($source['order']));
		if(!empty($sort)) $where['sort']=intval($sort);
		
		$rs=$conn->getOne($fields,$where,"page");
		if(!empty($rs)){
			$conn->Update(array('order'=>$source['order']),array('id'=>$rs['id']),"page");
			$conn->Update(array('order'=>$rs['order']),array('id'=>$source['id']),"page");
		}
	}
}

$ajax=new Ajax();
$ajax->export("doUp","doDown");
?>