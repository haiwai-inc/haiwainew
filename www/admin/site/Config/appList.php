<?php
return array(
	'BBS'=>array(
		'name'=>'论坛',
		'config'=>array(
		),
		'admin'=>array(
			'1'=>array('title'=>'字词过滤', 'link'=>'/bbs/admin/badword.php?modid=$itemid&pageid=$pid'),
			'2'=>array('title'=>'内容监控', 'link'=>'/bbs/admin/monitor.php?modid=$itemid&pageid=$pid'),
			'3'=>array('title'=>'论坛管理', 'link'=>'/bbs/admin/index.php?modid=$itemid&pageid=$pid'),
		),
	),
	
	'blog'=>array(
		'name'=>'博客',
		'config'=>array(
		),
		'admin'=>array(
			'1'=>array('title'=>'博客管理', 'link'=>'/blog/admin/index.php?modid=$itemid&pageid=$pid'),
		),
	),
	
	'download'=>array(
		'name'=>'数据下载',
		'config'=>array(
			'howToCheckUser'=>array(
				'name'=>'下载验证方式',
				'type'=>'select',
				'init'=>array(
					array('name'=>'直接下载','value'=>'directDown'),
					array('name'=>'验证登录','value'=>'checkLogin'),
					array('name'=>'激活验证','value'=>'checkLogin'),
					//array('name'=>'后台发送','value'=>'sendmail'),
					//array('name'=>'填写信息','value'=>'infoRequest'),
				),
				'defaultValue'=>'directDown',
			),
		),
		'admin'=>array(
			'1'=>array('title'=>'文档管理', 'link'=>'/download/admin/index.php?modid=$itemid&pageid=$pid'),
		),
	),
);