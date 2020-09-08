<?php
class profile extends Action{

	protected $obj;
	protected $cateObj;
	protected $typelist;

	function __construct(){
		parent::__construct();

		if(empty($_GET['cateid'])) go('/admin/member/cateDefine.php');
		
		$this->tpl='admin.html';
		$this->obj = load('profile_define');
		$this->cateObj=load('profile_categorise');
		
		if(in_array($_GET['act'],array('index','add','update'))) $this->typelist = conf('admin.member.defineValueType');
		$rs=$this->cateObj->getOne('*',array('id'=>$_GET['cateid']));
		
		$this->assign( 'page_title' , '会员管理');
		$this->assign( 'parentURL' , '/admin/member/profile.php?cateid='.$_GET['cateid'] );
		$this->assign( 'page_nav' , '<a href="/admin/member/cateDefine.php" class="tishilink">信息定义</a> >> '.$rs['name']);
	}

	function ACT_index(){
		$this->assign('incmenu',conf('admin.member.incmenu.define'));
		$this->assign('includeTpl','profile/_index.html');
		
		$result=$this->obj->getList("*",array('categorise'=>$_GET['cateid'],'order'=>array('order'=>'ASC')));
		
		if(!empty($result)){
			$this->obj->makeCache($result);
			foreach($result as $key => $val){
				$val['typename'] = $this->typelist[$val['type']];
				$val['typelist'] = ($val['type']=='select'||$val['type']=="checkbox")?$this->obj->getArrVal($val['id']):null;
				$val['checklist'] = ($val['type']=="checkbox")?$this->obj->getCheckList($val['defaultValue']):null;
				
				$result[$key] = $val;
			}
		}
		
		$this->assign('result', $result);
	}
	
	function ACT_add(){
		$this->assign('includeTpl','profile/_add.html');
		$this->assign('typelist',$this->typelist);
	}
	
	function ACT_update(){
		$this->assign('includeTpl','profile/_add.html');
		$this->assign('typelist',$this->typelist);
		
		$id=page::getIDs();
		$rs=$this->obj->getOne("*",array('id'=>$id));
		$this->assign('rs',$rs);
	}
	
	function ACT_suc(){
		$this->assign('includeTpl','index/_suc.html');
		$this->assign('incmenu','none');

	}

	function ACT_post(){
		if(empty($_POST['eid'])){
			$_POST['status']='N';
			$_POST['categorise']=$_GET['cateid'];
			$id=$this->obj->Insert($_POST);
			$this->obj->setOrder($id);
		}else{
			$id=intval($_POST['eid']);
			$this->obj->Update($_POST, array('id'=>$id));
		}
		$this->cateObj->setProfileUpdateSign();
		
		go($_POST['reurl']);
	}

	function ACT_status(){
		$ids=page::getIDs('M');
		if(!is_array($ids))$ids=array($ids);

		$rs=$this->obj->getAll(array('id','status'), array('SQL'=>'id='.implode(' or id=',$ids) ) );

		$n=$y=array();
		foreach($rs as $k=>$v){
			if($v['status']=='Y'){
				$y[]=$v['id'];
			}else{
				$n[]=$v['id'];
			}
		}
		if(!empty($y))
			$this->obj->Update(array('status'=>'N'),array('SQL'=>'id='.implode(' or id=',$y)));
		if(!empty($n))
			$this->obj->Update(array('status'=>'Y'),array('SQL'=>'id='.implode(' or id=',$n)));
		$this->cateObj->setProfileUpdateSign();

		go($_SERVER['HTTP_REFERER']);
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
?>