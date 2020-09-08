<?php
return array(
	array('name'=>"chkall"),
	array('name'=>"order",'link'=>'content.php?act=order&appid='.$_GET['appid'].'&'),
	array('name'=>"返回",'link'=>'app.php?',),
	array('name'=>"loop",'link'=>'content.php?appid='.$_GET['appid'].'&',),
    array('name'=>"添加",'link'=>'content.php?act=add&mid='.$_GET['mid'].'&rid='.$_GET['rid'].'&appid='.$_GET['appid'].'&',),
    array('name'=>"移动",'link'=>'content.php?act=move&mid='.$_GET['mid'].'&rid='.$_GET['rid'].'&appid='.$_GET['appid'].'&','jsLink'=>true),
    array('name'=>"合并",'link'=>'content.php?act=merge&mid='.$_GET['mid'].'&rid='.$_GET['rid'].'&appid='.$_GET['appid'].'&','jsLink'=>true),
	array('name'=>"修改",'link'=>'content.php?act=update&mid='.$_GET['mid'].'&rid='.$_GET['rid'].'&appid='.$_GET['appid'].'&','jsLink'=>true),
	array('name'=>"删除",'link'=>'content.php?act=del&appid='.$_GET['appid'].'&','delmsg'=>'你确认删除么？','jsLink'=>true),
);
?>