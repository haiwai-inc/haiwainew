<?php
return array(
	array('name'=>"chkall"),
	array('name'=>"order",'link'=>'catelist?act=order&'),
	array('name'=>"loop",'link'=>'catelist?',),
    array('name'=>"添加",'link'=>'catelist?act=add&mid='.$_GET['mid'].'&rid='.$_GET['rid'].'&',),
    array('name'=>"状态",'link'=>'catelist?act=status&mid='.$_GET['mid'].'&rid='.$_GET['rid'].'&','jsLink'=>true),
	array('name'=>"修改",'link'=>'catelist?act=update&mid='.$_GET['mid'].'&rid='.$_GET['rid'].'&','jsLink'=>true),
	array('name'=>"删除",'link'=>'catelist?act=del&','delmsg'=>'你确认删除么？','jsLink'=>true),
);
?>