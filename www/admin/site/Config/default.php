<?php
## 配置与当前服务器相关的运行参数，被所有程序所加载
return array(
	#系统模板
	'uid'=>'default',
		
	#系统配置参数
	'system'=>array(
		//运行环境调用
		'langbase'=>'',//网站语言系统
		'multiPageTpl'=>'cn',//多页面翻页时使用的模板 
		'cliDomain'=>$_SERVER['HTTP_HOST'],
		'uploadDomain'=>"/",
		'centreModel'=>0,
		'timezone'=>'UTC+0800',//时区
		'loginPasswdCode'=>'MD5',//密码加密方式，DES,MD5
	
		//程序环境调用
		'serverid'=>'',//系统标识
		'mailboxroot'=>'',//本地邮件服务器，用户根目录
		'MailServer'=>'',//默认的邮件发送服务器
		'MailUser'=>'webmaster@',//默认的邮件发送帐户
		'MailPasswd'=>'',//默认的邮件发送密码
		'MailName'=>'系统管理员',//默认的系统管理员名称
		'md5key'=>'iuO4#',//全站干扰码
		'vendor'=>'',//是否调用厂商[0/1]
	),

	#域设置
	'session'=>array(
		'sessiondomain'=>'',//有效的域名
		'sessionpath'=>'/',//在服务器端的有效路径。
	),

	#搜索服务器的地址及端口,使用逗号分隔多台服务器 10.0.186.1:8983/solr/, 10.0.186.2:8983/solr/, 10.0.186.3:8983/solr/
	'search'=>array(
		'news'=>'n/a',
	  	'blog'=>'n/a',	
		'bbs'=>'n/a',
		"book"=>"n/a",
		'video'=>'n/a',
		'classified'=>'n/a',
		"ads"=>"n/a",
		
		"job"=>"n/a",
		"wiki"=>"n/a",
		"deals"=>"n/a",
	),
	
	#内存映射服务器
	'memcached'=>array(
		'ad'=>'n/a',//广告缓存
	    'adword'=>'n/a',//ad word的分词
		'cnword'=>'n/a',//中文词库
		'html'=>'127.0.0.1:11211,127.0.0.1:11211,127.0.0.1:11211',//框架中html页面用到的缓存
		'bbs'=>'127.0.0.1:11211',//论坛相关缓存主机
		'count'=>'127.0.0.1:11211',//计数器
		'cache'=>'127.0.0.1:11211',//用户登录数据或其它少量但要同步的缓存
	),
	
	#内嵌的词典标签位置
	'dict'=>array(
		'hotkey'=>'/search/hotkey.php',//文章内建标签显示调用位置
		'search'=>'/search/forsearch.php',//文章内建标签搜索位置
		'common'=>'dict.abc.com',	//通用词库调用位置
		'wav'=>'/home/WyabdcRealPeopleTTS/', //发音文件地址
	),

	#用于svn同步的运行身份
	'svn'=>array(
		'os'=>'linux',//服务器操作系统
		'svnuser'=>'svnadmin',//svn管理员
		'svnpass'=>'123456'//svn使用密码
	),

	#统计相关
	'statistics'=>array(
		'logroot'=>'/server/apache/accesslog/root',//统计日志文件位置
		'logformat'=>'%t-_-%h-_-%>s-_-%r-_-%{Referer}i-_-%{User-agent}i-_-%b-_-%{X-Forwarded-For}i-_-%V-_-%{UserID}C-_-%{cookie}n',//日志格式
		'GAID'=>'', //Google统计ID
	    'checkpage'=>'', //是否记录非页面日志
		'gap'=>'-_-',//日志分隔
		'checkpage'=>'',//原始日志是否记录非页面访问,0/1
		'maxIpNum'=>'500',//合法ip单日访问中上限
		'prefix'=>'', //日志前缀,access.log.
		'adDomain'=>'', //记录广告统计的域名
	),
	
	#积分相关
	'score'=>array(
		'min_down'=>'5',//最低的下载积分
		'min_bbs'=>'0',//最低的下载积分
		'user_active'=>'20',//用户激活赠与的积分
		'user_inviteRegister'=>'10',//邀请注册后赠与的积分
		'user_inviteActive'=>'50',//邀请激活后赠与的积分
	
		'min_admin'=>'-1000',//管理员积分操作下限
		'max_admin'=>'1000',//管理员积分操作上限
	),
	
	#网站入口
	"sitemap"=>array(
		//演示介绍入口	
		"tour"=>"/html/tour/tour-step1.shtml",	
		//用户注册协议
		"agreement"=>"/html/about/agreement.shtml",
		//首页	
		"homepage"=>"/",
		//管理入口	
		"admin"=>"/admin/",
	),
	
	#默认图片
	"images"=>array(
		'talk'=>'',
		'blog'=>'',
		'member'=>'',
		'topic'=>'',
		'waterFile'=>'',
		'waterFilePos'=>'',
	),
	
	#默认通用广告
	"ad"=>array(
		'serviceDomain'=>'http://analytics.domain.com/',
		'Leaderboard_946_90'=>'',
		'Leaderboard_728_90'=>'',
		'Medium_Rectangle_350_250'=>'',
		'Small_Square_200_200'=>'',
		'Square_250_250'=>'',
		'Vertical_Banner_120_240'=>'',
		'Skyscraper_120_600'=>'',
		'Banner_468_60'=>'',
		'Smart_Text'=>'',
	),
	
	#分布部署的服务器
	"server"=>array(
		'worknode'=>'',	//所有服务器的全局配置文件都设置在各自的物理server上	
	),
);
?>
