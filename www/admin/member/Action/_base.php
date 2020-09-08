<?php
class baseAction extends Action{
	var $obj;
	var $infoObj;
	
	function __construct(){
		parent::__construct();

		$this->tpl='admin.html';
		
		$this->assign('page_title','会员管理');
		$this->obj = load('member_user');
		$this->infoObj = load('member_userinfo');
		
		//获取返回地址
		$this->getParentURL();
	}
	
	private function getParentURL(){
		//记录从其它应用模块过来的路径,列表页面仅记录搜索条件，不被其它模块调用
		if(isset($_GET['from'])&&$_GET['act']!='index') $_SESSION['parentUrl']=$_GET['from'];
		
		if(isset($_SESSION['parentUrl'])){
			$url=rawurldecode($_SESSION['parentUrl']);
		}else{
			$url='/admin/member/';
		}
		$this->assign( 'parentURL' , $url );
		
	}
	
}