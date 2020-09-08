<?php
include DOCUROOT.'/admin/page/Action/_base.php';
include DOCUROOT.'/article/Action/baseAction/_articleItem.php';

class composeItem extends articleItemAction{

	function __construct() {
		parent :: __construct();
		
		//初始化文章级别类
		if(!in_array($_GET['act'],array('post','del','order'))) {
			//索引页导航参数
			$this->menuStr='admin.page.incmenu.setup_composeItem';
			
			//当前项目配置
			$this->config = $this->loadConfig('composelist');
			$this->config['label'] = $this->config['tpl'];
			
			//文章列表链接
			$this->config['articlelistURL']='composeArticle.php?pid='.$_GET['pid'].'&appid='.$_GET['appid'].'&tab='.$_GET['tab'];
			//debug::d($this->config);
			
			//导航信息
			$link=array(
				'item'=>$this->tmp['menu'][$_GET["tab"]]['name'],
				'subjectName'=>$this->config['name'],
				'subjectURL'=>"?appid={$_GET['appid']}&pid={$_GET['pid']}&tab={$_GET['tab']}&mid=0&rid=0",
			);
			
			$this->init('composeItem',$link);//init(运行文件名,导航信息数组);
		}
	}
}