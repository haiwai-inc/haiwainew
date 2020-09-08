<?php
return array(
	array('name'=>"chkall"),
	array('name'=>"调用全局标签",'link'=>"fieldsDefine.php?act=global&profileID={$_GET['profileID']}",),
	array('name'=>"order",'link'=>"fieldsDefine.php?act=order&profileID={$_GET['profileID']}"),
    array('name'=>"添加",'link'=>"fieldsDefine.php?act=add&profileID={$_GET['profileID']}",),
    array('name'=>"修改",'link'=>"fieldsDefine.php?act=update&profileID={$_GET['profileID']}",'jsLink'=>true),
	array('name'=>"删除",'link'=>"fieldsDefine.php?act=del&profileID={$_GET['profileID']}",'delmsg'=>'你确认删除么？','jsLink'=>true),
);
?>