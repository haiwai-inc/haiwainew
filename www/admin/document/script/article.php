<?php
include 'inc.config.php';

function doUp($id){
	if(!func_checkAuth('document_manager','admin')) return false;
	order($id,">");
	return $id;
}
function doDown($id){debug::g();
	if(!func_checkAuth('document_manager','admin')) return false;
	order($id,"<");
	return $id;
}
function order($id,$item){
	$obj=load('document_document');
	$id=intval($id);
	$source=$obj->getOne(array('id','sort','order'),array('id'=>$id));
	if(!empty($source)) {
		$target=$obj->getOne(array('id','order'),array('sort'=>$source['sort'],'order,'.$item =>$source['order'],'order'=>array('order'=>'DESC')));
		if(!empty($target)){
			$obj->Update(array('order'=>$source['order']),array('id'=>$target['id']));
			$obj->Update(array('order'=>$target['order']),array('id'=>$source['id']));
		}
	}
}

$ajax=new Ajax();
$ajax->export("doUp","doDown");
?>