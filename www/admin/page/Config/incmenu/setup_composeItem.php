<?php
return array(
	array('name'=>"chkall"),
	array('name'=>"order",'link'=>'composeItem.php?act=order&appid='.$_GET['appid'].'&'),
	array('name'=>"返回",'link'=>'../index.php?',),
	array('name'=>"loop",'link'=>'composeItem.php?appid='.$_GET['appid'].'&',),
    array('name'=>"添加",'link'=>'composeItem.php?act=add&mid='.$_GET['mid'].'&rid='.$_GET['rid'].'&appid='.$_GET['appid'].'&',),
    array('name'=>"状态",'link'=>'composeItem.php?act=status&mid='.$_GET['mid'].'&rid='.$_GET['rid'].'&appid='.$_GET['appid'].'&','jsLink'=>true),
    array('name'=>"移动",'link'=>'composeItem.php?act=move&mid='.$_GET['mid'].'&rid='.$_GET['rid'].'&appid='.$_GET['appid'].'&','jsLink'=>true),
    array('name'=>"合并",'link'=>'composeItem.php?act=merge&mid='.$_GET['mid'].'&rid='.$_GET['rid'].'&appid='.$_GET['appid'].'&','jsLink'=>true),
	array('name'=>"修改",'link'=>'composeItem.php?act=update&mid='.$_GET['mid'].'&rid='.$_GET['rid'].'&appid='.$_GET['appid'].'&','jsLink'=>true),
	array('name'=>"删除",'link'=>'composeItem.php?act=del&appid='.$_GET['appid'].'&','delmsg'=>'你确认删除么？','jsLink'=>true),
);
?>