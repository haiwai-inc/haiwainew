<?php
/**
 * 标准单元
 * 'page'=>array(
 *     'name'=>'独立页面',//项目名称
 *     'admin'=>'管理',//管理入口名称
 *     'path'=>'/admin/page/?',//管理链接
 *     'setup'=>'设置',//管理入口名称
 *     'setuppath'=>'/admin/page/?',//管理链接
 *     'view'=>'查看',//管理入口名称
 *     'viewpath'=>'/admin/page/?',//管理链接
 *     'config'=>array(...),//默认配置数组
 * );
 */
return array(
	'home'=>array(
		'page'=>array(
			'name'=>'独立页面',
			'config'=>array(

				'isSubPage'=>array(
					'name'=>'是否有子级页面',
					'type'=>'radio',
					'init'=>array(
						array('name'=>'有','value'=>'Y'),
						array('name'=>'没有','value'=>'N'),
					),
					'defaultValue'=>'N',
				),
				
				'cacheTime'=>array(
					'name'=>'页面缓存时间',
					'type'=>'input',
					'defaultValue'=>0,
				),
				
				'appact'=>array(
					'name'=>'页面授权标识',
					'type'=>'input',
					'defaultValue'=>'page_app',
				),
				
			),
			'admin'=>array(
				'manager'=>array('title'=>'页面管理','link'=>'/admin/page/?mid=$pid&cate=$cate&label=$itemlabel'),
				'view'=>array('title'=>'预览查看','link'=>'/$itemlabel/','target'=>'_blank'),
			),
		),
		'link'=>array(
			'name'=>'链接',
			'config'=>array(
				'linktype'=>array(
					'name'=>'打开页面类型',
					'type'=>'radio',
					'init'=>array(
						array('name'=>'当前页','value'=>'_self'),
						array('name'=>'新建页','value'=>'_blank'),
					),
					'defaultValue'=>'_self',
				),
			),
		),
	),
	'app'=>array(
		'introduce'=>array(
			'name'=>'内容介绍',
			'path'=>'/cms/admin/',
			'config'=>array(
				'titleLength'=>array(
					'name'=>'标题长度',
					'type'=>'input',
					'defaultValue'=>20,
				),
				'sumaryLength'=>array(
					'name'=>'描述长度',
					'type'=>'input',
					'defaultValue'=>100,
				),
				'isDatetime'=>array(
					'name'=>'是否显示日期',
					'type'=>'radio',
					'init'=>array(
						array('name'=>'显示','value'=>1),
						array('name'=>'不显示','value'=>0),
					),
					'defaultValue'=>1,
				),
				'appact'=>array(
					'name'=>'授权标识',
					'type'=>'input',
					'defaultValue'=>'article_content',
				),
				'template'=>array(
					'name'=>'模板',
					'type'=>'input',
					'defaultValue'=>'',
				),
				
				'htmlroot'=>array(
					'name'=>'地址的根路径',
					'type'=>'input',
					'defaultValue'=>'html/',
				),
			),
			'admin'=>array(
				'manager'=>array('title'=>'查看管理','link'=>'/cms/admin/content.php?pid=$pid&appid=$itemid'),
			),
		),
		'article'=>array(
			'name'=>'信息发布',
			'path'=>'/cms/admin/',
			'config'=>array(
				'titleLength'=>array(
					'name'=>'标题长度',
					'type'=>'input',
					'defaultValue'=>20,
				),
				'sumaryLength'=>array(
					'name'=>'描述长度',
					'type'=>'input',
					'defaultValue'=>100,
				),
				'isDatetime'=>array(
					'name'=>'是否显示日期',
					'type'=>'radio',
					'init'=>array(
						array('name'=>'显示','value'=>1),
						array('name'=>'不显示','value'=>0),
					),
					'defaultValue'=>1,
				),
				'itemnum'=>array(
					'name'=>'单元数量',
					'type'=>'input',
					'defaultValue'=>50,
				),
				'template'=>array(
					'name'=>'模板',
					'type'=>'input',
					'defaultValue'=>'',
				),
				
				'htmlroot'=>array(
					'name'=>'地址的根路径',
					'type'=>'input',
					'defaultValue'=>'html/',
				),
			),
			'admin'=>array(
				'allarticle'=>array('title'=>'全部文章','link'=>'/cms/admin/article.php?pid=$pid&appid=$itemid'),
				'manager'=>array('title'=>'分类管理','link'=>'/cms/admin/index.php?pid=$pid&appid=$itemid'),
			),
		),
		'page'=>array(
			'name'=>'独立页面',
			'config'=>array(

				'isSubPage'=>array(
					'name'=>'是否有子级页面',
					'type'=>'radio',
					'init'=>array(
						array('name'=>'有','value'=>'Y'),
						array('name'=>'没有','value'=>'N'),
					),
					'defaultValue'=>'N',
				),
				
				'cacheTime'=>array(
					'name'=>'页面缓存时间',
					'type'=>'input',
					'defaultValue'=>0,
				),
				
				'appact'=>array(
					'name'=>'页面授权标识',
					'type'=>'input',
					'defaultValue'=>'page_app',
				),
				
			),
			'admin'=>array(
				'manager'=>array('title'=>'页面管理','link'=>'/admin/page/?mid=$pid&cate=$cate&label=$itemlabel'),
				'view'=>array('title'=>'预览查看','link'=>'/$itemlabel/','target'=>'_blank'),
			),
		),
		/*
		'member'=>array(
			'name'=>'会员管理',
			'path'=>'/admin/member/',
		),
		
		'blog'=>array(
			'name'=>'会员管理',
			'path'=>'/admin/member/',
		),
		
		'bbs'=>array(
			'name'=>'会员管理',
			'path'=>'/admin/member/',
		),

		//service
		'activity'=>array(
			'name'=>'统计分析',
			'path'=>'/admin/analytics/',
		),
		
		'ad'=>array(
			'name'=>'广告管理',
			'path'=>'/admin/ad/',
		),
		
		'analytics'=>array(
			'name'=>'统计分析',
			'path'=>'/admin/analytics/',
		),
		
		'quiz'=>array(
			'name'=>'统计分析',
			'path'=>'/admin/analytics/',
		),
		
		'survey'=>array(
			'name'=>'统计分析',
			'path'=>'/admin/analytics/',
		),
		
		'newsletter'=>array(
			'name'=>'邮件群发',
			'config'=>array(
				'awsdf'=>'',
				'asdf'=>'',
				'aswdf'=>'',
				'asdd'=>'',
				'd'=>'',
			),
			'admin'=>array(
				'manager'=>array('title'=>'管理','link'=>'/service/newsletter/?mid=$pid&cate=$cate&label=$itemlabel'),
			),
		),
		
		//system
		'fetch'=>array(
			'name'=>'统计分析',
			'path'=>'/admin/analytics/',
		),
		
		'backup'=>array(
			'name'=>'统计分析',
			'path'=>'/admin/analytics/',
		),
		
		'monitor'=>array(
			'name'=>'统计分析',
			'path'=>'/admin/analytics/',
		),*/
	),
	
	//仅用特殊页面
	'page'=>array(
		'page'=>array(
			'name'=>'独立页面',
			'config'=>array(

				'isSubPage'=>array(
					'name'=>'是否有子级页面',
					'type'=>'radio',
					'init'=>array(
						array('name'=>'有','value'=>'Y'),
						array('name'=>'没有','value'=>'N'),
					),
					'defaultValue'=>'N',
				),
				
				'cacheTime'=>array(
					'name'=>'页面缓存时间',
					'type'=>'input',
					'defaultValue'=>0,
				),
				
				'appact'=>array(
					'name'=>'页面授权标识',
					'type'=>'input',
					'defaultValue'=>'page_app',
				),
				
			),
			'admin'=>array(
				'manager'=>array('title'=>'页面管理','link'=>'/admin/page/?mid=$pid&cate=$cate&label=$itemlabel'),
				'view'=>array('title'=>'预览查看','link'=>'/$itemlabel/','target'=>'_blank'),
			),
		),
	)
);