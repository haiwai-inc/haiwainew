<?php
class cateDefine extends Action{

	protected $obj;

	function __construct(){
		parent::__construct();

		$this->tpl='admin.html';
		$this->assign('page_title','会员管理');
		$this->obj = load('profile_categorise');

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

	function ACT_index(){
		$this->assign('incmenu',conf('admin.member.incmenu.cate'));
		$this->assign('includeTpl','cateDefine/_index.html');
		
		//记录当前的
		$_SESSION['parentUrl']=$_SERVER['REQUEST_URI'];
		$this->assign('page_nav','<a href="'.$_SERVER['REQUEST_URI'].'" class="tishilink">信息定义</a>');

		$result=$this->obj->getList("*",array('id,!='=>0,'order'=>array('order'=>'ASC')));
		
		if( !empty($result) ){
			$ids=array();
			foreach($result as $val){
				$ids[]=$val['id'];
			}
			
			//读取计数
			$mod=load('profile_define');
			$tmp=$mod->getAll("SELECT count(*)as num,`categorise` FROM `user_profile_define` WHERE `categorise`=".implode(" OR `categorise`=",$ids)." group by `categorise`");
			
			$nums=array();
			foreach($tmp as $val){
				$nums[$val['categorise']]=$val['num'];
			}
			
			foreach($result as $k=>$v){
				$v['num'] = empty( $nums[$v['id']] )? 0 : $nums[$v['id']];
				$result[$k]=$v;
			}
		
		}
		
		$this->assign('result', $result);
	}

	function ACT_add(){
		$this->assign('includeTpl','cateDefine/_add.html');
	}
	
	function ACT_update(){
		$this->assign('includeTpl','cateDefine/_add.html');
		
		$id=page::getIDs();
		$rs=$this->obj->getOne("*",array('id'=>$id));
		$this->assign('rs',$rs);
	}

	function ACT_post(){
		$data=$_POST;

		if(empty($data['eid'])){
			$data['status']='N';
			$id=$this->obj->Insert($data);
			$this->obj->setOrder($id);
		}else{
			$id=intval($data['eid']);
			$this->obj->Update($data,array('id'=>$id));
		}
		$this->obj->setProfileUpdateSign();
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

		$this->obj->setProfileUpdateSign();
		go($_SERVER['HTTP_REFERER']);
	}

	function ACT_del(){
		$id=page::getIDs('M');
		$this->obj->Remove(array('SQL'=>'id='.implode(' or id=',$id)));
		$this->obj->setProfileUpdateSign();
		go($_SERVER['HTTP_REFERER']);
	}
	
	function ACT_order(){
		$this->obj->ord();
		$this->obj->setProfileUpdateSign();
		go($_SERVER["HTTP_REFERER"]);
	}

	function ACT_suc(){
		$this->assign('includeTpl','index/_suc.html');
		$this->assign('incmenu','none');

	}


}