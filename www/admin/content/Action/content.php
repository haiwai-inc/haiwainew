<?php
include DOCUROOT.'/cms/Action/_base.php';
include DOCUROOT.'/cms/Action/baseAction/_content.php';

class content extends contentAction{
	
	function __construct() {
		parent :: __construct();
		$this->mod = load("cms_content");
		
		//初始化文章级别类
		if(!in_array($_GET['act'],array('post','del','order'))) {
			//索引页导航参数
			$this->menuStr='admin.content.menustr';
			//当前项目配置
			$this->config=$this->loadArticleConfig('introduce');
			//文章列表链接
			$this->config['articlelistURL']='';
			//导航信息
			$menuinfo=conf('admin.site.menu');
			$link=array(
				'item'=>$menuinfo['app']['name'],
				'subjectName'=>$this->config['name'],
				'subjectURL'=>"?appid={$_GET['appid']}&pid={$_GET['pid']}&tab=app&mid=0&rid=0",
			);
			$this->init($link);//init(分类地址,导航信息数组);
			
			
			//根据content的环境输出相关变量
			$this->assign('inctitle',$this->config['name']);
			$this->tpl = DOCUROOT.'/admin/content/Tpl/admin.html';
		}
		
		if(!func_checkAuth("article_".$this->config['label'].'_'.$this->pageinfo['id'])) {
			$tailstr="redirect=".rawurlencode($_SERVER["REQUEST_URI"]);
			go( "/passport/index.php?act=admin&errmsg=accessdeny&checkAuth=true&".$tailstr);
		}
	}
}