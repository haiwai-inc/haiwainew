<?php
class manager extends Action{
	
	function ACT_index(){
		$siteID = conf('global','global.id');
		$obj=load("content_cfg_mod");
		
		$fields="*";
		$where=array(
			"pid"=>$siteID,
			'appname'=>'introduce',
			"categorise"=>'app',
			"order"=>array("order"=>"ASC")
		);

		$rs=$obj->getAll($fields,$where);
		$rs=$obj->formatModData($rs,$siteID,'app');
		$this->assign("result",$rs);
	}
	
	function ACT_passwd(){
		if(!empty($_POST['new'])){
			$obj=load('passport_user');
			$filed=array('password');
			$where=array('id'=>$_SESSION['UserID']);
			$rs=$obj->getOne($filed,$where);
			
			if( $rs['password']!=$obj->passwd($_POST['old']) ){
				$this->assign('msg','passerror');
			}else{
				$data=array( 'password'=>$obj->passwd($_POST['new']) );
				$obj->Update($data,$where);
			}
		}
	}
}
