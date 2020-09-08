<?php
//####  /admin/tools/confirm.php?item=haiwai_michael


return array(
		'title'=>'首页，模板和黄页商铺',
		'admin'=>'moban&biz',
		'func'=>array(
				//-----------首页----------------
				"<span style='color:red'>搜索栏下方热词推送</span>",
				"<span style='color:blue'>http://beta.haiwai.com</span>",
				"搜索栏下方热词推送 - 测试首页每个热词是否能正确连接到相应的网页",
				"搜索栏下方热词推送 - 测试在不同页面热词是否能正确显示和连接是否正确",
				"",
				//-----------前台----------------
				//免费信息发布 
				"<span style='color:red'>免费信息发布-step1&step2</span>",
				"<span style='color:blue'>http://beta.haiwai.com/page/index.php?act=publish</span>",
				"免费信息发布 - 显示所有大类的信息",
				"免费信息发布 - 鼠标移动到任何一个大类上面，都会显示小类的主要信息，并且点击更多会显示所有相应的所有小类，点击小类会进入发布页面",
				"免费信息发布 - 点击任何一个大类，会显示改类别下面所有小类，然后点击小类就进入发布页面",
				"免费信息发布 - 头部的三个步骤，会根据当前所在页面自动变换，并且可以点击跳转到前面的步骤",
				"",
				
				"<span style='color:red'>免费信息发布(黄页商铺)-step3</span>",
				"<span style='color:blue'>http://beta.haiwai.com/biz/publish.php?tag=XXX&category=XXXX_category</span>",
				"免费信息发布(黄页商铺)-step3 - 对于不同的主标签显示正确的动态字段",
				"免费信息发布(黄页商铺)-step3 - 预填正确的区域信息",
				"免费信息发布(黄页商铺)-step3 - 对于不同的主标签显示相应的副标签(其它关键字)",
				"免费信息发布(黄页商铺)-step3 - 勾选不同的关键字，显示在其它关键字按钮下方",
				"免费信息发布(黄页商铺)-step3 - 动态字段单选，多选，下拉显示正确的选项",
				"免费信息发布(黄页商铺)-step3 - 介绍部分输入特殊字符及html tag 或 javascript 代码 提交后在view页面不被执行",
				"免费信息发布(黄页商铺)-step3 - 一次可上传最多十张图片",
				"免费信息发布(黄页商铺)-step3 - 测试上传非图片类型文件",
				"免费信息发布(黄页商铺)-step3 - 测试上传大于20MB文件",
				"免费信息发布(黄页商铺)-step3 - 点击发布按钮后 validate所有固定字段和动态字段中的 必填项 数字项 电话号码 email",
				"免费信息发布(黄页商铺)-step3 - 正确显示发布成功页面",
				"",
				
				
				//前台listing
				"<span style='color:red'>黄页商铺listing page</span>",
				"<span style='color:blue'>http://beta.haiwai.com/biz/listing.php?tag=16&region=192</span>",
				"黄页商铺listing page - 默认显示该标签类的，在本地的所有分类广告",
				"黄页商铺listing page - 如果当前区域有subregions，正确显示所有subregions",
				"黄页商铺listing page - 模板中所有的单选，多选，下拉 都在筛选条件中出现，并显示正确选项值",
				"黄页商铺listing page - 模板中所有数字字段作为范围查询条件",
				"黄页商铺listing page - 选择“浏览人气／只看认证／只看有图”，显示正确数据",
				"黄页商铺listing page - 模板中定义的显示列显示在列表中",
				"黄页商铺listing page - 列表中所有黄页商铺的数据都正确，有图的标明（图），未认证的显示未认证，认证的显示认证过",
				"",
				
				//前台view
				"<span style='color:red'>黄页商铺view page</span>",
				"<span style='color:blue'>http://beta.haiwai.com/biz/view.php?id=828</span>",
				"黄页商铺view page - 信息显示 -  固定字段信息显示正确",
				"黄页商铺view page - 信息显示 -  点击“更多信息”后，动态字段信息显示正确，",
				"黄页商铺view page - 信息显示 -  动态字段信息 格式显示正常",
				"黄页商铺view page - 信息显示 -  简介部分信息显示正确",
				"黄页商铺view page - 信息显示 -  可以点赞",
				"黄页商铺view page - 信息显示 -  分享到微信",
				"黄页商铺view page - 信息显示 -  商铺下面的图片能正常显示",
				"黄页商铺view page - 商铺认证 -  如果当前用户未经过认证，则跳转到用户认证页面； 如果是认证用户，进入商铺认证界面",
				"",
				
				//黄页商铺认证
				"<span style='color:red'>黄页商铺认证</span>",
				"<span style='color:blue'>http://beta.haiwai.com/biz/view.php?id=828</span>",
				"黄页商铺认证 - 在为空的状态下，必填项在提交后，能正确提示错误信息",
				"黄页商铺认证 - 预填州的字段",
				"黄页商铺认证 - 认证提交后，可在后台查看等待认证的商铺",
				"",
				
				//前台编辑
				"<span style='color:red'>黄页商铺编辑</span>",
				"<span style='color:blue'>http://beta.haiwai.com/biz/publish.php?id=XXXX</span>",
				"黄页商铺编辑 - 对于不同的主标签显示正确的动态字段",
				"黄页商铺编辑 - 预填所有字段数据",
				"黄页商铺编辑 - 简介栏数据显示格式正确，html tag 或script代码不被执行，作为文本输出",
				"黄页商铺编辑 - 删除图片",
				"黄页商铺编辑 - 更改关键字",
				"黄页商铺编辑 - 更改所有字段，数据被正确更新",
				"黄页商铺编辑 - 提交时检测 必选 数字 电话 email 字段",
				"黄页商铺编辑 - 提交后回到用户中心页面",
				"",
				
				
				//-----------黄页商铺后台----------------
				//黄页商铺后台查询&listing
				"<span style='color:red'>黄页商铺后台&listing</span>",
				"<span style='color:blue'>http://beta.haiwai.com/biz/</span>",
				"黄页商铺后台&listing - 点击‘显示所有黄页商铺’显示所有数据",
				"黄页商铺后台&listing - 按不同字段查询",
				"黄页商铺后台&listing - 按不同字段联查",
				"",
				
				//黄页商铺后台发布
				"<span style='color:red'>黄页商铺后台创建</span>",
				"<span style='color:blue'>http://beta.haiwai.com/biz/?act=add</span>",
				"黄页商铺后台创建 - 对于不同的主标签显示正确的动态字段",
				"黄页商铺后台创建 - 只允许图片类型文件，文件不能超过20MB",
				"黄页商铺后台创建 - 提交时检测固定字段",
				"",
			
				//黄页商铺后台view
				"<span style='color:red'>黄页商铺后台view</span>",
				"<span style='color:blue'>http://beta.haiwai.com/biz/?act=biz&id=45146</span>",
				"黄页商铺后台view - 显示所有固定／动态字段 和 图片",
				"黄页商铺后台view - 编辑按钮进行编辑",
				"黄页商铺后台view - 删除按钮删除此条黄页商铺",
				"",
				
				//黄页商铺后台编辑
				"<span style='color:red'>黄页商铺后台编辑</span>",
				"<span style='color:blue'>http://beta.haiwai.com/biz/?act=add_update&id=45146</span>",
				"黄页商铺后台编辑 - 固定字段 动态字段已有值预先填好",
				"黄页商铺后台编辑 - 可以更改字段值",
				"黄页商铺后台编辑 - 提交时对固定字段的 必填项／电话／email进行检测",
				"黄页商铺后台编辑 - 提交成功回到view页面",
				"",
				
				//黄页商铺后台删除
				"<span style='color:red'>黄页商铺后台删除</span>",
				"黄页商铺后台删除 - 在view page 点击删除按钮删除此黄页商铺",
				"",
				"",
				
				//-----------模板后台----------------
				//模板后台查询&listing
				"<span style='color:red'>模板后台查询&listing</span>",
				"<span style='color:blue'>http://beta.haiwai.com/moban/</span>",				
				"模板后台查询&listing - 点击显示“所有模板”列出所有模板",
				"模板后台查询&listing - 按模板名称搜索",
				"模板后台查询&listing - 按主标签名称搜索",
				"模板后台查询&listing - 创建模板按纽",
				"模板后台查询&listing - 分页",
				"",
				
				//模板后台创建
				"<span style='color:red'>模板后台创建</span>",
				"<span style='color:blue'>http://beta.haiwai.com/moban/?act=create</span>",
				"模板后台创建 - 模板名称必填",
				"模板后台创建 - 模板名称前后的多余空格被去除",
				"模板后台创建 - 模板名称unique",
				"",
				
				//模板后台view
				"<span style='color:red'>模板后台view</span>",
				"<span style='color:blue'>http://beta.haiwai.com/moban/?act=moban&id=89</span>",
				"模板后台view - 模板各项数据",
				"模板后台view - 编辑按钮",
				"模板后台view - 删除模板（如果该模板关联到主标签，则不可被删除）",
				"",
				
				//模板后台编辑
				"<span style='color:red'>模板后台编辑</span>",
				"<span style='color:blue'>http://beta.haiwai.com/moban/?act=edit_moban&id=89</span>",
				"模板后台编辑 - 自动带出所有字段",
				"模板后台编辑 - 可以添加或者删除动态参数",
				"模板后台编辑 - 点击“保存”所有修改的数据都能正确被保存",
				"",
				
		),

		"other"=>array(
				"更改已有的黄页商铺 - 信息更新显示都正确",
				"导入的所有商铺信息（除餐馆外） - 信息显示都正确",
				"所有编辑字段 － 去除所有字段的前后多余空格",
		),
);

