<?php
return array(
	array('name'=>"chkall"),
	array('name'=>"返回分类",'link'=>"/admin/member/cateDefine.php",),
	array('name'=>"order",'link'=>"profile.php?act=order&cateid={$_GET['cateid']}"),
    array('name'=>"添加",'link'=>"profile.php?act=add&cateid={$_GET['cateid']}",),
    array('name'=>"修改",'link'=>"profile.php?act=update&cateid={$_GET['cateid']}",'jsLink'=>true),
	array('name'=>"状态",'link'=>"profile.php?act=status&cateid={$_GET['cateid']}",'jsLink'=>true),
	array('name'=>"删除",'link'=>"profile.php?act=del&cateid={$_GET['cateid']}",'delmsg'=>'你确认删除么？','jsLink'=>true),
);
?>