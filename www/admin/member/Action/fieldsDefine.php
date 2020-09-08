<?php
class fieldsDefine extends Action{

	protected $obj;
	protected $cateObj;
	protected $cateInfo;
	protected $profileFieldInfo;

	function __construct(){
		parent::__construct();

		$this->tpl='admin.html';
		$this->assign('page_title','会员管理');
		$this->obj = load('profile_fields');

		$this->getProfileInfo();
	}

	private function getProfileInfo(){
		if(empty($_GET['profileID']))alert('errorID');
		
		$profileDefineObj=load('profile_define');
		$this->profileFieldInfo=$profileDefineObj->getOne('*',array('id'=>$_GET['profileID']));
		
		
		$this->cateObj=load('profile_categorise');
		$this->cateInfo=$this->cateObj->getOne('*',array('id'=>$this->profileFieldInfo['categorise']));
		
		$navstr = '<a href="/admin/member/cateDefine.php" class="tishilink">信息定义</a> >> ';
		$navstr .= '<a href="/admin/member/profile.php?cateid='.$this->profileFieldInfo['categorise'].'" class="tishilink">'.$this->cateInfo['name'].'</a> >> ';
		$navstr .= $this->profileFieldInfo['name'];
		
		$this->assign('page_nav',$navstr);
		$this->assign( 'parentURL' , "/admin/member/fieldsDefine.php?profileID=".$_GET['profileID'] );
	}

	function ACT_index(){
		$this->assign('incmenu',conf('admin.member.incmenu.fields'));
		$this->assign('includeTpl','fieldsDefine/_index.html');
		
		$result=$this->obj->getList("*",array('profileID'=>$_GET['profileID'],'order'=>array('order'=>'ASC')));
		$this->assign('result', $result);
	}

	function ACT_add(){
		$this->assign('includeTpl','fieldsDefine/_add.html');
	}
	
	function ACT_update(){
		$this->assign('includeTpl','fieldsDefine/_add.html');
		
		$id=page::getIDs();
		$rs=$this->obj->getOne("*",array('id'=>$id));
		$this->assign('rs',$rs);
	}

	function ACT_post(){
		if(empty($_POST['eid'])){
			$_POST['profileID']=$_GET['profileID'];
			$id=$this->obj->Insert($_POST);
			$this->obj->setOrder($id);
		}else{
			$id=intval($_POST['eid']);
			$this->obj->Update($_POST,array('id'=>$id));
		}
		$this->cateObj->setProfileUpdateSign();
		
		go($_POST['reurl']);
	}

	function ACT_del(){
		$id=page::getIDs('M');
		$this->obj->Remove(array('SQL'=>'id='.implode(' or id=',$id)));
		$this->cateObj->setProfileUpdateSign();
		
		go($_SERVER['HTTP_REFERER']);
	}
	
	function ACT_order(){
		$this->obj->ord();
		$this->cateObj->setProfileUpdateSign();
		
		go($_SERVER["HTTP_REFERER"]);
	}

}