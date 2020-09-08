<?php
return array(
	array('name'=>"chkall"),
	array('name'=>"order",'link'=>"index.php?act=order&mid={$_GET['mid']}&cate={$_GET['cate']}"),
	array('name'=>"导入",'link'=>"index.php?act=import&mid={$_GET['mid']}&rootid={$_GET['rootid']}&cate={$_GET['cate']}",),
	array('name'=>"导出",'link'=>"dump.php?cate={$_GET['cate']}",'jsLink'=>true),
    array('name'=>"新建",'link'=>"index.php?act=add&mid={$_GET['mid']}&rootid={$_GET['rootid']}&cate={$_GET['cate']}",),
	array('name'=>"修改",'link'=>"index.php?act=update&mid={$_GET['mid']}&rootid={$_GET['rootid']}&cate={$_GET['cate']}",'jsLink'=>true),
	array('name'=>"移动",'link'=>"index.php?act=move&mid={$_GET['mid']}&rootid={$_GET['rootid']}&cate={$_GET['cate']}",'jsLink'=>true),
	array('name'=>"状态",'link'=>"index.php?act=status&mid={$_GET['mid']}&rootid={$_GET['rootid']}&cate={$_GET['cate']}",'jsLink'=>true),
	array('name'=>"删除",'link'=>"index.php?act=del&mid={$_GET['mid']}&rootid={$_GET['rootid']}&cate={$_GET['cate']}",'delmsg'=>'你确认删除么？','jsLink'=>true),
);
?>