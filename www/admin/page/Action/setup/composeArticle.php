<?php
include DOCUROOT.'/admin/page/Action/_base.php';
include DOCUROOT.'/article/Action/baseAction/_article.php';

class composeArticle extends articleAction{
	protected $editorCate='pageArticle';//用于编辑器内文件上传时，记录上传文件所用的数据库标识
	
	function __construct() {
		parent :: __construct();
		$this->mod = load("article_article");
		$this->isshow=true;
		
		//初始化文章级别类
		if(!in_array($_GET['act'],array('post','del','order'))) {
			//索引页导航参数
			$this->menuStr='admin.page.incmenu.setup_composeArticle';
			//当前项目配置
			$this->config=$this->loadConfig('composelist');
			//文章列表链接
			$this->config['articlelistURL']='';
			//导航信息
			$link=array(
				'item'=>$this->tmp['menu'][$_GET["tab"]]['name'],
				'subjectName'=>$this->config['name'],
				'subjectURL'=>"?appid={$_GET['appid']}&pid={$_GET['pid']}&tab={$_GET['tab']}&mid=0&rid=0",
			);
			$this->init('composeItem',$link);//init(分类地址,导航信息数组);
		}
		
		if(!empty($this->pageinfo['mid'])){
			if(!func_checkAuth("article_".$this->config['tpl'].'_'.$this->pageinfo['id'])) {
				$tailstr="redirect=".rawurlencode($_SERVER["REQUEST_URI"]);
				go( "/passport/index.php?act=admin&errmsg=accessdeny&checkAuth=true&".$tailstr);
			}
		}
	}
}