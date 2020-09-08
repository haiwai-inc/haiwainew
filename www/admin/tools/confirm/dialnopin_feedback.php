<?php
//http://beta.wenxuecity.com/admin/tools/confirm.php?item=dialnopin_sida

//用户后台测试
return array(
		'title'=>'dialnopin_客户服务',
		'admin'=>'注册用户',
		'func'=>array(
				"http://beta.dialnopin.com/feedback",
				"用户反馈(用户登录)",
				"用户反馈 - 客户服务问题解答",
				"用户反馈 - 提交客服问题",
				"用户反馈 - 查看客户服务问题",
				"",
				"用户反馈(用户未登录)",
				"用户反馈 - 客户服务",
				"",
				"http://beta.dialnopin.com/feedback/admin",
				"后台管理",
				"后台管理 - 查看所有客服问题",
				"后台管理 - 翻页查看所有客服问题(open closed)",
				"后台管理 - 查看回复单个的客服问题(open)",
				"后台管理 - 查看回复单个的客服问题(closed)",
		),

		"other"=>array(
				"未注册用户发送客服问题使用email",
				"后台处理未注册用户使用email",
				"注册用户发送客服问题使用内部客服系统",
				"后台处理未注册用户使用内部客服系统",
		),
);