<?php
abstract class domainAction extends baseAction{
	
	function __construct() {
		parent :: __construct();
		$_GET["tab"]="domain";
		$this->assign("page_nav",$this->tmp['menu'][$_GET["tab"]]['name']);
		
		//只有网站首页可以设置域名类型为域名，其它情况全是别名
		$this->assign('domainType',($this->pageinfo['categorise']=='Portal')?'domain':'alias');
	}
	
	function ACT_index(){
		$mod=load($this->AppPrefix.'_cfg_domain');
		$rs=$mod->getAll("*",array("pid"=>$_GET["pid"],'order'=>array("order"=>"ASC")));

		$this->assign("result",$rs);
	}

	function ACT_add(){
	}

	function ACT_update(){
		$mod=load($this->AppPrefix.'_cfg_domain');
		$rs=$mod->getOne("*",array('id'=>$this->_getIDs(),"pid"=>$_GET["pid"]));

		$this->assign("rs",$rs);
	}

	function ACT_post(){
		$pid=$_POST['pid'];
		
		$mod=load($this->AppPrefix.'_cfg_domain');
		if(empty($_POST["eid"])){
			$id=$mod->Insert($_POST);
			$mod->setOrder($id);
		}else{
			$id=intval($_POST["eid"]);
			$mod->Update($_POST,array("id"=>$id,'pid'=>$pid));
		}
		
		//更新系统配置文件
		if(!empty($_POST['domain']) && $this->AppPrefix=='page'){
			//更新系统配置
			$obj=load("site_configure");
			$obj->makeConfigByPid( $pid );
		}
		go("./domain.php?tab=domain&cate={$_GET['cate']}&pid={$pid}");
	}
	
	function ACT_unique(){
		$id=$this->_getIDs();
		
		$mod=load($this->AppPrefix.'_cfg_domain');
		
		//关闭未操作的其它项目
		$rs=$mod->getOne(array('id'), array('pid'=>$_GET['pid'],'unique'=>'Y','id,!='=>$id) );
		if(!empty($rs))$mod->Update(array('unique'=>'N'),array('id'=>$rs['id']));
		
		//根据当前项目值，执行更新
		$rs=$mod->getOne(array('id','unique'), array('pid'=>$_GET['pid'],'id'=>$id) );
		$mod->Update(array('unique'=>$rs['unique']=='N'?'Y':'N'),array('pid'=>$_GET['pid'],'id'=>$id));
		
		//更新系统配置
		if($this->AppPrefix=='page'){
			$obj=load("site_configure");
			$obj->makeConfigByPid( $_GET['pid'] );
		}
		
		go($_SERVER['HTTP_REFERER']);
	}

	function ACT_del(){
		$ids=$this->_getIDs('M');
		if(!empty($ids)){
			$mod=load($this->AppPrefix.'_cfg_domain');
			$mod->Remove(array('pid'=>$_GET['pid'],'SQL'=>'id='.implode(' or id=',$ids)));
			
			//更新系统配置
			if($this->AppPrefix=='page'){
				$obj=load("site_configure");
				$obj->makeConfigByPid( $_GET['pid'] );
			}
		}
		go($_SERVER['HTTP_REFERER']);
	}
	
	function ACT_order(){
		$mod=load($this->AppPrefix.'_cfg_domain');
		$mod->ord();
		go($_SERVER["HTTP_REFERER"]);
	}
}