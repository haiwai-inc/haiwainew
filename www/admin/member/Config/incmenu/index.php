<?php
return array(
	array('name'=>"chkall"),
	array('name'=>"搜索",'link'=>'index.php?act=search',),
    array('name'=>"添加",'link'=>'index.php?act=add',),
    array('name'=>"状态",'link'=>'index.php?act=status','jsLink'=>true),
	array('name'=>"踢人",'link'=>'index.php?act=kick','jsLink'=>true),
	array('name'=>"修改",'link'=>'index.php?act=security','jsLink'=>true),
	array('name'=>"删除",'link'=>'index.php?act=del','delmsg'=>'你确认删除么？','jsLink'=>true),
);
?>