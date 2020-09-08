<?php
return array(
	array('name'=>"chkall"),
	array('name'=>"order",'link'=>'composeContent.php?act=order&mid='.$_GET['mid'].'&appid='.$_GET['appid'].'&'),
	array('name'=>"返回",'link'=>'../app.php?',),
    array('name'=>"添加",'link'=>'composeContent.php?act=add&mid='.$_GET['mid'].'&appid='.$_GET['appid'].'&',),
	array('name'=>"链接",'link'=>'composeContent.php?act=link&mid='.$_GET['mid'].'&appid='.$_GET['appid'].'&',),
	array('name'=>"状态",'link'=>'composeContent.php?act=status&mid='.$_GET['mid'].'&appid='.$_GET['appid'].'&','jsLink'=>true),
    array('name'=>"修改",'link'=>'composeContent.php?act=update&mid='.$_GET['mid'].'&appid='.$_GET['appid'].'&','jsLink'=>true),
	array('name'=>"删除",'link'=>'composeContent.php?act=del&appid='.$_GET['appid'].'&','delmsg'=>'你确认删除么？','jsLink'=>true),
);
?>