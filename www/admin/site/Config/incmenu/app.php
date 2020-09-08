<?php
return array(
	array('name'=>"chkall"),
	array('name'=>"order",'link'=>'app.php?act=order&'),
    array('name'=>"添加",'link'=>'app.php?act=add&',),
    array('name'=>"状态",'link'=>'app.php?act=status&','jsLink'=>true),
	array('name'=>"修改",'link'=>'app.php?act=update&','jsLink'=>true),
	array('name'=>"设置",'link'=>'app.php?act=config&','jsLink'=>true),
	array('name'=>"删除",'link'=>'app.php?act=del&','delmsg'=>'你确认删除么？','jsLink'=>true),
);
?>