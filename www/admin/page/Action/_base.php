<?php
include DOCUROOT.'/admin/page/Action/baseAction/__basicPageAction.php';

abstract class baseAction extends basicPageAction{
	protected $AppPrefix='page';
	protected $appcate='app';//调用page base的应用都是来自app的文章分类

	/**
	 * 标准的page输入参数为：
	 * mid,所在分类的id
	 * cate,类型
	 * label,page访问地址
	 */
	function __construct(){
		//加page对象
		$this->obj=load("page_page");
	
		//获取 page ID
		if(empty($_GET['pid'])) $this->getPageID();
	
		//加载基本设置
		parent :: __construct();
	
		//如果是主页或是项目首页禁止模板功能
		if(is_dir(DOCUROOT.'/'.$this->pageinfo['url'])||$this->pageinfo['categorise']=='Portal'){
			$this->unsign('menulist.tpl');
		}
		
		//获取返回地址
		$this->getParentURL();
	}

	//授权检测,框架权限接口函数
	public function checkAuth($method,$authinfo){
		//授权提示页面，所有用户都可见
		if( in_array( $method,array('denied','suc') ) ) return true;
		
		$obj=load('page_cfg_authz');
		$rs = $obj->getAuthzInfo($_GET['pid'],$_SESSION['UserID']);

		if(empty($rs)){
			//是authz自己时返回框架错误处理
			if(get_class($this)=='authz') return false;
			if(substr(AppName,0,5)=='admin'){
				go('/'.AppName.'/authz.php?act=denied&tab=authz&pid='.$_GET['pid']);
			}else{
				go('/'.AppName.'/admin/authz.php?act=denied&tab=authz&pid='.$_GET['pid']);
			}
		}
		return true;
	}

	protected function getPageID(){
		if(empty($_GET['mid']))	alert("errorID");
		$_GET['label']=empty($_GET['label'])?'homepage':$_GET['label'];

		if( isset($_POST['id']) ){
			$_GET['pid']=page::getIDs();//各项目下的page
		}else if($_GET['cate']=='Portal'){
			$_GET['pid']=$_GET['mid'];//站点首页
		}else{
			//站点二级页面
			$where=array(
				'categorise'=>$_GET['cate'],
				'mid'=>$_GET['mid'],
				'url'=>$_GET['label']
			);
			$rs=$this->obj->getOne(array('id'),$where);
			if(!empty($rs)){
				$_GET['pid']=$rs['id'];
			}else{
				alert('errorID');
			}
		}
	}
	
	protected function getParentURL(){
		if($this->pageinfo['mid']==0){
			$previewURL='/';
			$parentURL='/admin/site/app.php?pid='.$this->pageinfo['id'].'&label=homepage';
		}else{
			$previewURL='/'.$this->pageinfo['url'].'/';
			$parentURL='/admin/site/app.php?pid='.$this->pageinfo['mid'].'&label='.$this->pageinfo['url'];
		}
		
		$this->assign('previewURL',$previewURL);
		$this->assign('parentURL',$parentURL);
	}

	//处理setup目录中的action在运行时调用的配置,根据 tpl,pid及app的类型，返回当前应用的配置信息
	protected function loadConfig($type){
		static $rs;
		if(empty($rs)){
			$appConfigObj=load("page_cfg_app");
			$rs=$appConfigObj->getOne('*',array('id'=>$_GET['appid'],'pid'=>$_GET['pid']));
			$rs['param']=configure::getValue($rs['param'],conf('admin.page.modList',$rs['app'].'.config'));
			
			//传递page的类型，用于数据调用时读取不同的数据源
			$rs['pagetype']=$this->AppPrefix;
		}
		
		//输出应用程序
		$this->assign("appinfo",$rs);
			
		return $rs;
	}

}
?>