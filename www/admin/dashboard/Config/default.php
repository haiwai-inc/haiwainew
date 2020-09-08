<?php
return array(

	//'site'=>由程序自动调用,
	
	//'数字ID'=>菜单管理程序自动生成
	
	'admin'=>array(
			'id'=>'admin',
			'name'=>'系统管理',
			'sublist'=>array(
				array('id'=>1,'name'=>'版本同步','link'=>'/admin/sync.php','icon'=>'ti_line'),
				array('id'=>3,'name'=>'全局标签','link'=>'/admin/label/index.php?cate=0','icon'=>'ti_line'),
				array('id'=>5,'name'=>'广告管理','link'=>'/service/ad/dealslevel.php','icon'=>'ti_line'),
				array('id'=>3,'name'=>'字词过滤','link'=>'/admin/badword/index.php','icon'=>'ti_line'),
				array('id'=>15,'name'=>'评论管理','link'=>'/comment/admin.php','icon'=>'ti_line'),
				array('id'=>5,'name'=>'开发文档','link'=>'/admin/document/','icon'=>'ti_line'),
				array('id'=>12,'name'=>'用户管理','link'=>'/admin/member/','icon'=>'ti_line'),
				array('id'=>13,'name'=>'界面设置','link'=>'/admin/?act=changeSkin','icon'=>'ti_line'),
				array('id'=>14,'name'=>'站点管理','link'=>'/admin/site/','icon'=>'ender'),
			),
			'switch'=>false,
		)
);
?>