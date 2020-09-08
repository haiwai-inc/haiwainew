<?php

/*
 * Created on 2008-4-1 By Weiqi
 */
class panel extends Action {
	protected $mod;
	protected $config=array();

	function __construct(){
		parent::__construct();
		$this->assign("returnurl",rawurlencode("/"));
	}

	//首页
	function ACT_index() {
		$this->checkDeveloper();
		
		$this->assign( "info",conf('global','global'));
		if(file_exists(DOCUROOT."/admin/dashboard/Tpl/{$_SERVER['HTTP_HOST']}")){
			$this->tpl="{$_SERVER['HTTP_HOST']}/index.html";
		}
	}
	
	//非技术开发人员, 不提供beta网站后台访问入口
	private function checkDeveloper(){
		if($_SERVER['HTTP_HOST']=='www.wenxuecity.com') return;
		if(in_array($_SESSION['NickName'], array('weiqi','admin','jerry','david','zhuli','chengdi'))) return;
		
		go('http://www.wenxuecity.com/admin/');
	}
	
	//框架
	function ACT_frame() {
		if (!empty($_GET['firstlogin'])){ $this->assign( "firstlogin","1"); }else{ $this->assign( "firstlogin","0"); }
		$this->assign( "mess",$this->lang["mess"]);
	}

	//菜单
	function ACT_menu() {
		$this->mod=load("dashboard_menu");
		$this->assign("menulist",$this->mod->getAdminMenu());
	}

	//控制栏
	function ACT_ctrl() {
	}

	//桌面
	function ACT_desktop() {
	}

	//皮肤切换
	function ACT_changeSkin(){
		$this->assign('config',$_COOKIE['Cookie_CfgID']);
	}
	
	//站点切换
	function ACT_changeSite(){
		$authzObj=load('site_cfg_authz');
		
		if(!empty($_GET['siteid'])){
			$_GET['siteid']=intval($_GET['siteid']);
			
			$where=array('pid'=>$_GET['siteid']);
			if(!func_checkAuth('superadmin')) $where['uid']=$_SESSION['UserID'];
			$authzObj->Update(array('updatetime'=>time()),$where);
			
			//对用户重新授权
			$obj=load('passport_power');
			$_SESSION['UserPower']=$obj->getPower($_SESSION['UserID'],$_GET['siteid']);
			$_SESSION['UserPower']['siteid']=$_GET['siteid'];
			go('/admin/');
		}		
		
		$this->assign('page_title','站点切换');
		$this->assign('page_nav','已授权站点列表');
		
		$where=array(
		'SQL'=>"pid IN (SELECT id FROM page WHERE categorise='Portal')",
		'order'=>array('updatetime'=>'DESC')
		);
		if(!func_checkAuth('superadmin')) $where['uid'] = $_SESSION['UserID'];
		
		$rs=$authzObj->getList('*',$where);
		$rs=$this->formatRS($rs);
		
		$this->assign("result",$rs);
		
	}
	
	function formatRS($rs){
		if(empty($rs)) return;
		$ids=array();
		foreach($rs as $key=>$val){
			$ids[]=$val['pid'];
		}
		
		$obj=load("site_site");
		$rs=$obj->getAll('*',array('SQL'=>'id='.implode(' OR id=',$ids)));
		return $rs;
		
	}

}
?>