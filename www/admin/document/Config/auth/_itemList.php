<?php
/**
 * 此处是对 Action中的方法进行命名解释
 * 为什么要设置这个文件？ 实际上由于Action方法名和物理文件名完全相同，可以通过目录文件遍历获得
 * 但在后台设置权限时为了有中文的说明和备注，必须有下面结构的说明文档才行
 * 
 * PS:
 * 		所有应用程序下Action不能重名，命名空间在同一个应用程序下要由程序员进行维护。
 * 		这个文件为示例，实际数据在数据库中存储
 */

return array(
	"title"=>"文档管理",
	"content"=>array(
		"manager"=>array("name"=>"文档管理","note"=>"--",'link'=>'http://www.baidu.com/'),
		"sortlist"=>array("name"=>"文档分类","note"=>"--"),
	)
);
?>