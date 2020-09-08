<?php
class authzAction extends baseAction{
	function __construct() {
		parent :: __construct();
		$_GET["tab"]="authz";
		$this->assign("page_nav",$this->tmp['menu'][$_GET["tab"]]['name']);
	}
	
	function ACT_index(){
		$obj=load($this->AppPrefix."_cfg_authz");
		$rs=$obj->getAll("*",array("pid"=>$this->pageinfo["id"]));

		if(!empty($rs)){
			//根据UID，读取相应的用户名
			$ids=array();
			foreach($rs as $val){
				$ids[]=$val['uid'];
			}
			$userobj = load('passport_user');
			$userrs=$userobj->getAll(array('id','nickname'),array('SQL'=>'id='.implode(' OR id=',$ids)));
			$pos=array();
			foreach($userrs as $val){
				$pos[$val['id']]=$val['nickname'];
			}
			foreach($rs as $key=>$val){
				$rs[$key]['nickname']=$pos[$val['uid']];
			}
		}
		
		$this->assign("result",$rs);
		$this->assign("setAuthzFrom",rawurlencode($_SERVER['REQUEST_URI']));
		$this->assign("powerType",$this->AppPrefix);
	}

	function ACT_add(){
	}

	function ACT_update(){
		$id=$this->_getIDs();
		$domainmod=load($this->AppPrefix."_cfg_authz");
		$rs=$domainmod->getOne("*",array('id'=>$id,"pid"=>$this->pageinfo['id']));
		
		$userobj = load('passport_user');
		$userrs=$userobj->getOne(array('nickname'),array('id'=>$rs['uid']));
		$rs['nickname']=$userrs['nickname'];
		$this->assign("rs",$rs);
		
		$_SERVER['HTTP_REFERER']='./authz.php?pid='.$this->pageinfo['id'].'&tab=authz';
	}

	function ACT_post(){
		$pid=$_POST["pid"];
		$mod=load($this->AppPrefix."_cfg_authz");
		
		if(empty($_POST["eid"])){
			$this->userExists($mod);
			$id=$mod->Insert($_POST);
		}else{
			$id=$_POST["eid"];
			$this->userExists($mod,$_POST["uid"]);
			$mod->Update($_POST,array("id"=>$id,'pid'=>$pid));
		}
		
		go("./authz.php?tab=authz&pid={$pid}");
	}
	
	function ACT_del(){
		$ids=$this->_getIDs('M');
		if(!empty($ids)){
			$domainmod=load($this->AppPrefix."_cfg_authz");
			$domainmod->Remove(array('pid'=>$_GET['pid'],'SQL'=>'uid!='.$_SESSION['UserID'].' AND id='.implode(' or id=',$ids)));
		}
		go($_SERVER['HTTP_REFERER']);
	}
	
	function ACT_denied(){
	}

	function userExists($obj,$uid=0){
		//错误处理，如果没有相关用户信息，返回
		$userObj=load("passport_user");
		$user=$userObj->getOne(array('id'),array('nickname'=>$_POST["nickname"]));
		if(empty($user)) $this->userExistsGoBack('notExists');
		
		//对当前正在修改的用户ID不检测
		if($user['id']!=$uid){
			//如果已经授权该用户，返回
			$authzRecord=$obj->getOne('*',array('pid'=>$_POST['pid'],'uid'=>$user['id']));
			if(!empty($authzRecord)) $this->userExistsGoBack('Exists');
		}
		
		$_POST["uid"]=$user['id'];
	}
	
	function userExistsGoBack($type){
		if(empty($_POST["eid"])){
			$url="add";
		}else{
			$url="update&id={$_POST["eid"]}";
		}
		go("./authz.php?act=".$url."&tab=authz&pid={$_POST['pid']}&nickname={$_POST["nickname"]}&type={$type}");
	}
	
}
?>