<?php
return array(
	array('name'=>"chkall"),
	array('name'=>"order",'link'=>'pictext.php?act=order&appid='.$_GET['appid'].'&'),
	array('name'=>"返回",'link'=>'../index.php?',),
    array('name'=>"添加",'link'=>'pictext.php?act=add&appid='.$_GET['appid'].'&itemid='.$_GET['itemid'].'&',),
	array('name'=>"修改",'link'=>'pictext.php?act=update&appid='.$_GET['appid'].'&itemid='.$_GET['itemid'].'&','jsLink'=>true),
	array('name'=>"状态",'link'=>'pictext.php?act=status&appid='.$_GET['appid'].'&','jsLink'=>true),
	array('name'=>"删除",'link'=>'pictext.php?act=del&appid='.$_GET['appid'].'&','delmsg'=>'你确认删除么？','jsLink'=>true),
);
?>