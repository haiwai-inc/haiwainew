<?php
class addsite extends Action{
	
	function ACT_index(){
		if(!empty($_POST)){
			$obj=load('site_cfg_authz');
			$this->siteExists($obj);
			
			$_POST['uid']=$_GET['id'];
			$_POST['updatetime']=time();
			
			$id=$obj->Insert($_POST);
			$this->assign('suc',true);
		}
	}
	
	function siteExists($obj){
		//错误处理，如果没有相关站点信息，返回
		$siteObj=load("site_site");
		$site=$siteObj->getOne(array('id'),array('mid'=>0,'url'=>$_POST["site"]));
		if(empty($site)) $this->goBack('notExists');
		
		//如果已经授权该站点，返回
		$authzRecord=$obj->getOne('*',array('pid'=>$site['id'],'uid'=>$_GET['id']));
		if(!empty($authzRecord)) $this->goBack('Exists');
		
		$_POST["pid"]=$site['id'];
	}
	
	function goBack($type){
		go("./addsite.php?id={$_GET['id']}&site={$_POST["site"]}&type={$type}");
	}
	
}