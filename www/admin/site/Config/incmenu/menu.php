<?php
return array(
	array('name'=>"chkall"),
	array('name'=>"order",'link'=>'menu.php?act=order&'),
	array('name'=>"loop",'link'=>'menu.php?',),
    array('name'=>"添加",'link'=>'menu.php?act=add&mid='.$_GET['mid'].'&rid='.$_GET['rid'].'&',),
	array('name'=>"修改",'link'=>'menu.php?act=update&mid='.$_GET['mid'].'&rid='.$_GET['rid'].'&','jsLink'=>true),
	array('name'=>"状态",'link'=>'menu.php?act=status&mid='.$_GET['mid'].'&rid='.$_GET['rid'].'&','jsLink'=>true),
	array('name'=>"移动",'link'=>'menu.php?act=move&mid='.$_GET['mid'].'&rid='.$_GET['rid'].'&','jsLink'=>true),
	array('name'=>"设置",'link'=>'menu.php?act=config&mid='.$_GET['mid'].'&rid='.$_GET['rid'].'&','jsLink'=>true),
	array('name'=>"删除",'link'=>'menu.php?act=del&','delmsg'=>'你确认删除么？','jsLink'=>true),
);
?>