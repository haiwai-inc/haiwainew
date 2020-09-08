<?php
require DOCUROOT.'/admin/page/Action/baseAction/__basicPageAction.php';
class baseAction extends basicPageAction{
	protected $AppPrefix='site';
	
	function __construct(){
		$this->obj=load("site_site");
		parent :: __construct();
		
		if(empty($_GET['pid']))go('./index.php');
		
		$this->assign('parentURL','/admin/site/?sort='.$this->pageinfo["sort"]);
	}

	//授权检测,框架权限接口函数
	public function checkAuth($method,$authinfo){
		$obj=load('site_cfg_authz');
		$rs = $obj->getAuthzInfo($_GET['pid'],$_SESSION['UserID']);

		if(empty($rs)){
			//是authz自己时返回框架错误处理
			if(get_class($this)=='authz') return false;
			go('./authz.php?act=denied&&tab=authz&pid='.$_GET['pid']);
		}
		return true;
	}

}
?>