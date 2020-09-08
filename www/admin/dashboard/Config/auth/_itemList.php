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
	"title"=>"管理授权",
	"content"=>array(
		"defautpower"=>array("name"=>"默认授权","note"=>""),
		"sys"=>array("name"=>"系统管理","note"=>""),
		"sync"=>array("name"=>"版本同步","note"=>""),
		"label"=>array("name"=>"全局标签","note"=>""),
		"member"=>array("name"=>"用户管理","note"=>""),
		"sys_page_app"=>array("name"=>"系统页面设置","note"=>""),
		"data_page_app"=>array("name"=>"内容页面设置","note"=>""),
	)
);
?>