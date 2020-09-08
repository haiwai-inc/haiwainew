<?php
abstract class basicPageAction extends Action{
	protected $obj;
	protected $pageinfo;
	protected $tmp;
	
	function __construct(){
		parent :: __construct();

		if($_GET["act"]!='post'&&$_GET["act"]!='order'){
			//定义PID
			$_GET['pid']=$pid=page::getIDs("I","pid");
			
			//加载页面信息
			if($this->AppPrefix=='site'){
				$this->pageinfo=($pid==conf('global','global.id'))?conf('global','global'):$this->obj->getResult($pid);
			}else{
				$this->pageinfo=$this->obj->getResult($pid);
			}
			
			//输出导航
			$this->assign("page_title",$this->pageinfo["name"]);
			
			//判断管理菜单的设置
			$this->_loadPageInterFace();
			
			//加载导航
			$this->_loadmenu();
		}
	}
	
	//加载导航
	protected function _loadmenu(){
		$this->tmp['menu']=$menu=@include(DOCUROOT."/".AppName."/Config/menu.php");
		
		if(empty($_GET["tab"])) $_GET["tab"]="home";
		$this->assign("menulist",$menu);

		$this->_loadtpl();
	}

	//加载模板
	protected function _loadtpl(){
		$this->tpl=$this->_loadTplFile('admin.html');
		
		$act=str_replace("update","add",$_GET["act"]);
		if($act=='suc'){
			$tpl=$this->_loadTplFile('config/_suc.html');
		}elseif($act=='error'){
			$tpl=$this->_loadTplFile("config/_error.html");
		}else{
			$tpl=$this->_loadTplFile(get_class($this)."/_".$act.".html");
		}
		
		$this->assign("includeTpl",$tpl);
	}
	
	//判断并输出模板文件路径
	protected function _loadTplFile($filename){
		if(is_file(DOCUROOT.'/'.AppName.'/Tpl/'.$filename))
			return DOCUROOT.'/'.AppName.'/Tpl/'.$filename;
		else
			return DOCUROOT.'/admin/page/Tpl/'.$filename;
	}

	//处理id
	protected function _getIDs($type='I',$field='id'){
		$result = page::getIDs($type,$field);
		return $result;
	}
	
	//加载管理菜单的设置
	private function _loadPageInterFace(){
		if( func_checkAuth('loadPageInterFace')=='superadmin' ){
			$loadPageMenu = $backHomePage = true;
		}else{
			$obj=load('site_cfg_authz');
			$rs = $obj->getAuthzInfo($_GET['pid'],$_SESSION['UserID']);
			
			if($rs['level']=='owner'){
				$loadPageMenu = $backHomePage = true;
			}else{
				$loadPageMenu = $backHomePage = false;
			}
		}
		
		$this->assign("backHomePage",$backHomePage);
		$this->assign("loadPageMenu",$loadPageMenu);
	}
}
?>