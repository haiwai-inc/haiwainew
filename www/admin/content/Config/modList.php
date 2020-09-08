<?php
//简化引用 /admin/site/Config/modList.php的设置
return array(
	'app'=>array(
		'introduce'=>array(
			'name'=>'内容介绍',
			'path'=>'/cms/admin/',
			'config'=>array(),
			'admin'=>array(
				'manager'=>array('title'=>'查看管理','link'=>'/admin/content/content.php?pid=$pid&appid=$itemid'),
			),
		),
		'article'=>array(
			'name'=>'信息发布',
			'path'=>'/cms/admin/',
		    'config'=>array(),
			'admin'=>array(
				'allarticle'=>array('title'=>'全部文章','link'=>'/admin/content/article.php?pid=$pid&appid=$itemid'),
				'manager'=>array('title'=>'分类管理','link'=>'/admin/content/index.php?pid=$pid&appid=$itemid'),
			),
		),
	)
);