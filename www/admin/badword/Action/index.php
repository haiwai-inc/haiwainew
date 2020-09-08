<?php
class index extends Action{
	protected $obj;
	
	function __construct(){
		parent::__construct();
		$this->obj=load('badword_badword');
		
		$this->assign('page_title','系统管理');
		$this->assign('page_nav','字词过滤');
		
		$this->tpl="index/index.html";
	}
	
	function ACT_index(){
		$where = array(	'id,!='=>0,'order'=>array('addtime'=>'DESC'));
		$rs=$this->obj->getList("*", $where );
		
		if(!empty($rs)){
			
			$userObj = load('passport_user');
			$uids=array();
			foreach($rs as $val){if(!in_array($val['userid'],$uids))$uids[]=$val['userid'];}
			$users = $userObj->getUser($uids,array('nickname','avatar','aboutme','point'));
			
			foreach($rs as $key=>$val){
				$val['word']=strings::subString($val['word'],100);
				$val['userinfo']=$users[$val['userid']];
				
				$rs[$key]=$val;
			}
		}
		
		$this->assign('result',$rs);
		$this->assign('incmenu', conf('admin.badword.incmenu'));
		$this->assign('includeTpl', 'index/_index.html');
	}
	
	function ACT_add(){
		$this->assign('includeTpl', 'index/_add.html');
	}
	
	function ACT_update(){
		$this->assign('includeTpl', 'index/_add.html');
		$rs=$this->obj->getOne("*",array('id'=>page::getIDs()));
		
		$this->assign("rs",$rs);
	}
	
	function ACT_post(){
		$_POST['userid'] = $_SESSION['UserID'];
		
		if( empty($_POST['eid']) ){
			$_POST['addtime'] = time();
			$this->obj->Insert( $_POST );
		}else{
			$this->obj->Update( $_POST, array('id'=>$_POST['eid']) );
		}
		
		go($_POST['reurl']);
	}
	
	function ACT_del(){
		$ids=page::getIDs('M');
		$this->obj->Remove(array ("SQL" => "id=".implode(' or id=',$ids)));
		
		go($_SERVER["HTTP_REFERER"]);
	}
	
}