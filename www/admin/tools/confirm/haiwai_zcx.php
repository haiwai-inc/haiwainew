<?php
//####  /admin/tools/confirm.php?item=haiwai_zcx


//分类广告view page测试
return array(
		'title'=>'标签&分类广告',
		'admin'=>'tag&classifiedinfo',
		'func'=>array(
				//-----------分类广告前台----------------
				//前台发布
				"<span style='color:red'>分类广告发布（step3）</span>",
				"<span style='color:blue'>http://beta.haiwai.com/classifiedinfo/publish.php?tag=xxxxxxxxx&category=xxxxxx</span>",
				"分类广告发布（step3） - 对于不同的主标签显示正确的动态字段",
				"分类广告发布（step3） - 预填正确的区域信息",
				"分类广告发布（step3） - 对于不同的主标签显示相应的副标签（其它关键字）",
				"分类广告发布（step3） - 勾选不同的关键字，显示在其它关键字按钮下方",
				"分类广告发布（step3） - 动态字段单选，多选，下拉显示正确的选项",
				"分类广告发布（step3） - 介绍部分输入特殊字符及html tag 或 javascript 代码 提交后在view页面不被执行",
				"分类广告发布（step3） - 一次可上传最多十张图片",
				"分类广告发布（step3） - 测试上传非图片类型文件",
				"分类广告发布（step3） - 测试上传大于20MB文件",
				"分类广告发布（step3） - 点击发布按钮后 validate所有固定字段和动态字段中的 必填项 数字项 电话号码 email",
				"分类广告发布（step3） - 正确显示发布成功页面",
				"",
				
				//前台listing
				"<span style='color:red'>分类广告listing page</span>",
				"<span style='color:blue'>http://beta.haiwai.com/classifiedinfo/listing.php?tag=17</span>",
				"分类广告listing page - 默认显示该标签类的所有分类广告",
				"分类广告listing page - 弹出筛选条件，选择不同分类，显示正确数据",
				"分类广告listing page - 如果当前区域有subregions，正确显示所有subregions",
				"分类广告listing page - 模板中所有的单选，多选，下拉 都在筛选条件中出现，并显示正确选项值",
				"分类广告listing page - 模板中所有数字字段作为范围查询条件",
				"分类广告listing page - 选择个人／商家／全部，显示正确数据",
				"分类广告listing page - 模板中定义的显示列显示在列表中",
				"分类广告listing page - 列表中所有分类广告的数据都正确，有图的标明（图）",
				"",
				
				//前台view
				"<span style='color:red'>分类广告view page</span>",
				"<span style='color:blue'>http://beta.haiwai.com/classifiedinfo/view.php?id=123</span>",
				"分类广告view page - ad gallery图片显示",
				"分类广告view page - ad gallery 图片显示 - 左右箭头滚动图片",
				"分类广告view page - ad gallery 图片显示 - 点击下面小图显示在上面大图上",
				"分类广告view page - ad gallery 图片显示 - 图片显示比例正常",
				"分类广告view page - ad gallery 图片显示 - 其它，当图片选中，highlight 显示正确",
				"分类广告view page - ad gallery 图片显示 - 没有图的情况下，格式显示正常，文字浮动到左边",
				"分类广告view page - 信息显示",
				"分类广告view page - 信息显示 - 固定字段信息显示正确，标题，发布时间，点击量 等...",
				"分类广告view page - 信息显示 - 固定字段信息显示正确，是否显示电话或email信息必须正确，可以编辑改变选择然后查看信息是否被显示",
				"分类广告view page - 信息显示 - 动态字段信息（基本信息）显示正确，＊＊＊请测试所有模版下分类广告动态字段",
				"分类广告view page - 信息显示 - 动态字段信息 格式显示正常",
				"分类广告view page - 信息显示 - 说明部分信息显示正确",
				"分类广告view page - 信息显示 - 说明部分信息显示，请测试特殊字符，html tag（html tag不应该被执行，应该原样输出，可以插入一段javascript代码，看是否存在安全隐患。）",
				"分类广告view page - 大图片",
				"分类广告view page - 大图片 - 没有图片情况不影响显示格式",
				"分类广告view page - 大图片 - 正确显示所有图片",
				"分类广告view page - 大图片 - 图片比例正常",			
				"分类广告view page - 相关推荐",
				"分类广告view page - 相关推荐 - 正确显示此主标签下六个最新分类广告",
				"分类广告view page - 相关推荐 - 检查有图片和没有图片情况下的格式",
				"分类广告view page - 相关推荐 - 正确显示标题等信息",
				"分类广告view page - 相关推荐 - 可以正确链接到推荐的分类广告",
				"分类广告view page - 相关推荐 - 图片比例正常",
				"",
				
				//前台编辑
				"<span style='color:red'>分类广告编辑</span>",
				"<span style='color:blue'>http://beta.haiwai.com/classifiedinfo/publish.php?id=3469</span>",
				"分类广告编辑 - 对于不同的主标签显示正确的动态字段",
				"分类广告编辑 - 去除所有字段的前后trailing space characters",
				"分类广告编辑 - 预填所有字段数据",
				"分类广告编辑 - 介绍栏数据显示格式正确，html tag 或script代码不被执行，作为文本输出",
				"分类广告编辑 - 删除图片",
				"分类广告编辑 - 更改关键字",
				"分类广告编辑 - 更改所有字段，数据被正确更新",
				"分类广告编辑 - 提交时检测 必选 数字 电话 email 字段",
				"分类广告编辑 - 提交后回到用户中心页面",
				"",
				"",
				
				
				//-----------分类广告后台----------------
				//分类广告后台查询&listing
				"<span style='color:red'>分类广告后台查询&listing</span>",
				"<span style='color:blue'>http://beta.haiwai.com/classifiedinfo/index.php</span>",
				"分类广告后台查询&listing - 默认显示所有数据",
				"分类广告后台查询&listing - 按不同字段查询",
				"分类广告后台查询&listing - 按不同字段联查",
				"",
				
				//分类广告后台发布
				"<span style='color:red'>分类广告后台发布</span>",
				"<span style='color:blue'>http://beta.haiwai.com/classifiedinfo/?act=add</span>",
				"分类广告后台发布 - 对于不同的主标签显示正确的动态字段",
				"分类广告后台发布 - 可一次选择多个图片，只允许图片类型文件，文件不能超过20MB",
				"分类广告后台发布 - 提交时检测固定字段的 必选 数字 电话 email 字段",
				"",
			
				//分类广告后台view
				"<span style='color:red'>分类广告后台view</span>",
				"<span style='color:blue'>http://beta.haiwai.com/classifiedinfo/?act=classifiedinfo&id=11212</span>",
				"分类广告后台view - 显示所有固定／动态字段 图片的值",
				"分类广告后台view - 编辑按钮进行编辑",
				"分类广告后台view - 删除按钮删除此条分类广告",
				"",
				
				//分类广告后台编辑
				"<span style='color:red'>分类广告后台编辑</span>",
				"<span style='color:blue'>http://beta.haiwai.com/classifiedinfo/?act=add_update&id=11212</span>",
				"分类广告后台编辑 - 固定字段 动态字段已有值预先填好",
				"分类广告后台编辑 - 可以更改字段值",
				"分类广告后台编辑 - 提交时对固定字段的 必填项／电话／email进行检测",
				"分类广告后台编辑 - 提交成功回到view页面",
				"",
				
				//分类广告后台删除
				"<span style='color:red'>分类广告后台删除</span>",
				"分类广告后台删除 - 在view page 点击删除按钮删除此分类广告",
				"",
				"",
				
				//-----------标签后台----------------
				//标签后台查询&listing
				"<span style='color:red'>标签后台查询&listing</span>",
				"<span style='color:blue'>http://beta.haiwai.com/tag/index.php</span>",				
				"标签后台查询&listing - 默认列出所有标签",
				"标签后台查询&listing - 按标签名称搜索",
				"标签后台查询&listing - 按模版名称搜索",
				"标签后台查询&listing - 创建标签按纽",
				"标签后台查询&listing - 分页",
				"",
				
				//标签后台创建
				"<span style='color:red'>标签后台创建</span>",
				"<span style='color:blue'>http://beta.haiwai.com/tag/index.php?act=add_update</span>",
				"标签后台创建 - 标签名称必填",
				"标签后台创建 - 标签名称前后的trailing space被去除",
				"标签后台创建 - 标签名称unique",
				"标签后台创建 - 主标签必填模版",
				"",
				
				//标签后台view
				"<span style='color:red'>标签后台view</span>",
				"<span style='color:blue'>http://beta.haiwai.com/tag/index.php?act=tag&id=628</span>",
				"标签后台view - 标签各项数据",
				"标签后台view - 编辑按钮",
				"标签后台view - 删除标签（主标签已被使用不可被删除，副标签如被使用会让用户选择是否继续删除操作）",
				"标签后台view - 合并标签，只对副标签（暂时最好不要使用， 涉及到其它分类广告和商铺方面的数据逻辑比较复杂）",
				"标签后台view - 加副标签，添加主标签和副标签的所属关系，只对主标签",
				"",
				
				//标签后台编辑
				"<span style='color:red'>标签后台编辑</span>",
				"<span style='color:blue'>http://beta.haiwai.com/tag/index.php?act=add_update&id=628</span>",
				"标签后台编辑 - 标签名称必填",
				"标签后台编辑 - 标签已被使用，不能更改是否主标签属性。",
				"标签后台编辑 - 标签已被使用，不能更改模版。",
				"标签后台编辑 - 标签名称unique",
				"标签后台编辑 - 其它validation同创建页面",
				"",
				
				//标签后台合并
				"<span style='color:red'>标签后台合并</span>",
				"<span style='color:blue'>http://beta.haiwai.com/tag/index.php?act=combine&id=628</span>",
				"标签后台合并 - 只对副标签适用",
				"标签后台合并 - 标签A合并到标签B，A被删除，所有使用B标签的分类广告／商铺改为使用标签B",
				"标签后台合并 - <span style='color:red'>A B所属不同副标签的情况？？？ 此功能的逻辑在这一版本做的不完善，是否应该考虑在下一版本优化后再适用？？？</span>",
				"",
				
				//标签后台添加副标签
				"<span style='color:red'>标签后台添加副标签</span>",
				"<span style='color:blue'>http://beta.haiwai.com/tag/index.php?act=sectag&id=627</span>",
				"标签后台添加副标签 - 只有主标签可以添加副标签",
				"标签后台添加副标签 - 可以删除／添加副标签",
				"标签后台添加副标签 - 选择的副标签会出现在前台的发布和编辑页面",
				"",
				
				//标签后台删除
				"<span style='color:red'>标签后台删除</span>",
				"标签后台删除 - 从view page删除标签，主标签已被使用不可被删除，副标签如被使用会让用户选择是否继续删除操作",
				"",				
				"",
		),

		"other"=>array(
				"在各个主标签插入新的分类广告 - 信息显示都正确",
				"更改已有的分类广告 - 信息更新显示都正确",
				"导入的数据各个主标签 - 信息显示与旧系统一致",
				"所有编辑字段 － 去除所有字段的前后trailing space characters",
		),
);

