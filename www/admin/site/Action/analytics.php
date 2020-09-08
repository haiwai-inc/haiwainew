<?php
require DOCUROOT.'/admin/site/Action/_base.php';

final class analytics extends baseAction{
	function __construct(){
		parent::__construct();
		$this->assign("page_nav",$this->tmp['menu'][$_GET["tab"]]['name']);
	}
	
	function ACT_index(){
		$this->assign("includeTpl",DOCUROOT.'/service/analytics/Tpl/index/_site.html');
		
		//域名列表
		$domainObj = load('site_cfg_domain');
		$rs = $domainObj->getAll("*",array('pid'=>$_GET['pid']));
		
		$domainList=array();
		foreach($rs as $val)$domainList[]=$val['domain'];
		
		//统计结果
		$obj=load("analytics_data");
		
		$start = empty($_GET['starttime'])?null:$_GET['starttime'];
		$end = empty($_GET['endtime'])?null:$_GET['endtime'];
		$result = $obj->init($domainList,$start,$end);
		
		$this->assign('result',$result);
	}
}