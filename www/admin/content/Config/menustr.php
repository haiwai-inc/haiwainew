<?php
return array(
	array('name'=>"chkall"),
	array('name'=>"order",'link'=>'content.php?act=order&appid='.$_GET['appid'].'&'),
    array('name'=>"添加",'link'=>'content.php?act=add&appid='.$_GET['appid'].'&',),
	array('name'=>"链接",'link'=>'content.php?act=link&appid='.$_GET['appid'].'&',),
    array('name'=>"修改",'link'=>'content.php?act=update&appid='.$_GET['appid'].'&','jsLink'=>true),
	array('name'=>"状态",'link'=>'content.php?act=status&appid='.$_GET['appid'].'&','jsLink'=>true),
	array('name'=>"删除",'link'=>'content.php?act=del&appid='.$_GET['appid'].'&','delmsg'=>'你确认删除么？','jsLink'=>true),
);
?>