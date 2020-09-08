<?php
return array(
	'prefix'=>'mini',//数据库前缀
	'mysqlMasterSlave'=>false,//是否启用mysql主从分离功能

	'database'=>array(
		//默认的主数据库，
		'main'=>array(
			//mysql主从结构中的主库
			'master'=>'localhost',

			//mysql主从结构中的从库，可以指定多个，mysqlMasterSlave设置为tru时生效
			'slave'=>array('localhost','localhost'),

			//可以指定数据库，或参考下面示例自动加载前缀、自动调用键名等方式调用指定的数据库
			'db'=>'mini_main',

			//是否默认使用分表功能,0/1
			'multiTb'=>0,
		),
		
		'article'=>array(
			'master'=>'localhost',
			'slave'=>array('localhost','localhost','localhost'),
			'db'=>'_article'//根据加载前缀自动生成的数据库
		),
		
		'blog'=>array(
			'master'=>'localhost',
			'slave'=>array('localhost','localhost','localhost'),
			'db'=>''//根据键名自动生成的数据库
		),
			
		'news' => array (
				'master' => 'localmongo',
				'db' => '_news'
		),
	),
	
	'server'=>array(
		//指定一组服务器
		'localhost'=>array(
			'dbdriver'=> 'mysql',
			'server'=> '127.0.0.1',
			'user'=> 'root',
			'password'=> '***',
			'charset'=> 'utf8',
			'pconnect'=> 0,
		),
			
		//Mongo 数据库
		'localmongo' => array (
				'dbdriver' => 'mongo',
				'user'=> 'root',
				'password'=> '***',
				'server' => 'localhost',
		),
	),
);
?>