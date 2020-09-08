<?php
return array(
	array('name'=>"chkall"),
	array('name'=>"order",'link'=>'composeArticle.php?act=order&mid='.$_GET['mid'].'&appid='.$_GET['appid'].'&'),
	array('name'=>"返回",'link'=>'composeItem.php?appid='.$_GET['appid'].'&mid='.$_GET["article_mid"].'&rid='.$_GET["article_rid"].'&',),
    array('name'=>"添加",'link'=>'composeArticle.php?act=add&mid='.$_GET['mid'].'&appid='.$_GET['appid'].'&',),
    array('name'=>"链接",'link'=>'composeArticle.php?act=link&mid='.$_GET['mid'].'&appid='.$_GET['appid'].'&',),
    array('name'=>"状态",'link'=>'composeArticle.php?act=status&mid='.$_GET['mid'].'&appid='.$_GET['appid'].'&','jsLink'=>true),
    array('name'=>"修改",'link'=>'composeArticle.php?act=update&mid='.$_GET['mid'].'&appid='.$_GET['appid'].'&','jsLink'=>true),
	array('name'=>"删除",'link'=>'composeArticle.php?act=del&appid='.$_GET['appid'].'&','delmsg'=>'你确认删除么？','jsLink'=>true),
);
?>