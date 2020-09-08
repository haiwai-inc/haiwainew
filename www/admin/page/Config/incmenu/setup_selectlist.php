<?php
return array(
	'list'=>array(
		array('name'=>"chkall"),
		array('name'=>"order",'link'=>'selectlist.php?act=order&appid='.$_GET['appid'].'&'),
		array('name'=>'返回','link'=>'/admin/page/index.php?'),
		array('name'=>"备选列表",'link'=>'selectlist.php?listtype=select&appid='.$_GET['appid'].'&',),
		array('name'=>"删除",'link'=>'selectlist.php?act=del&appid='.$_GET['appid'].'&','delmsg'=>'你确认删除么？','jsLink'=>true),
	
	),
	'select'=>array(
		array('name'=>"chkall"),
		array('name'=>'返回','link'=>'/admin/page/index.php?'),
		array('name'=>"推荐列表",'link'=>'selectlist.php?listtype=list&appid='.$_GET['appid'].'&',),
	    array('name'=>"保存",'link'=>'selectlist.php?act=save&appid='.$_GET['appid'].'&','jsLink'=>true),
	),
);
?>