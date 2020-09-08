<?php
return array(
	array('name'=>"chkall"),
	array('name'=>"搜索",'link'=>'index.php?act=search',),
	array('name'=>"order",'link'=>"cateDefine.php?act=order"),
    array('name'=>"添加",'link'=>'cateDefine.php?act=add',),
    array('name'=>"修改",'link'=>'cateDefine.php?act=update','jsLink'=>true),
	array('name'=>"状态",'link'=>'cateDefine.php?act=status','jsLink'=>true),
	array('name'=>"删除",'link'=>'cateDefine.php?act=del','delmsg'=>'你确认删除么？','jsLink'=>true),
);
?>