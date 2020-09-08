<?php
require DOCUROOT."/admin/site/Action/_base.php";
final class menu extends baseAction{
	protected $mod;
	protected $config;

	function __construct() {
		parent :: __construct();
		$_GET["tab"]="menu";
		$this->assign("page_nav",$this->tmp['menu'][$_GET["tab"]]['name']);
		
		$this->init();
	}
	
	function init(){
		$this->mod = load("site_admin");

		//当前隶属级别ID，如没有按站点根目录创建
		if(empty($_GET["mid"])){
			$rs = $this->mod->getOne('*',array ('pid' => $_GET['pid'], 'mid'=>0 ));
			//如没有相关站点选项信息，自动创建
			if(empty($rs)){
				$_GET["mid"]=$this->mod->Insert(array('pid'=>$_GET['pid'],'name'=>'siteroot'));
				$this->mod->setOrder($_GET["mid"]);
			}else{
				$_GET["mid"]=$rs['id'];
			}
		}
		
		//来自上一个级别的ID
		$_GET["rid"] = empty ($_GET["rid"]) ? 0 : intval($_GET["rid"]);

		//按指定顺序，输出当前分类导航
		$nav = $this->mod->getNav($_GET["mid"]);
		$this->assign("nav", $nav);
		$this->assign("mid", $_GET["rid"]);
		$this->assign("rid", empty ($nav[1]["mid"]) ? 0 : $nav[1]["mid"]);
		$this->assign("navbase", 'menu');
	}

	function ACT_index() {
		$this->assign("incmenu",conf('admin.site.incmenu.menu'));
		
		//是否显示空数据提示
		if (empty ($_SESSION["labelnoempty"])) {
			$alert = false;
		} else {
			unset ($_SESSION["labelnoempty"]);
			$alert = true;
		}
		
		//输出数据
		$result = $this->mod->getAll('*',array ('pid' => $_GET['pid'], 'mid' =>$_GET['mid'] ,'order'=>array("order"=>"ASC")) );
		$result=$this->formatNum($result);
		
		$this->assign("result", $result);
		$this->assign("checkalert", $alert);
	}

	function ACT_add() {
		//输出排序		
		$this->assign("switch", false);
		
		if($_GET['mid']==0){
			$pid=0;
		}else{
			$rs=$this->mod->getRecord($_GET['mid']);
			$pid=empty($rs)?0:$rs['pid'];
		}
		$this->assign("pid", $_GET['pid']);
	}
	
	function ACT_update() {
		$this->_initRs();
		$this->_initSwitch();
	}
	
	function ACT_config(){
		$this->assign('menus',conf('admin.site.menuType'));
		$rs=$this->_initRs();
		$this->_initSwitch();
	}

	function ACT_move(){
		$rs = $this->mod->getOne('*',array ('pid' => $_GET['pid'], 'mid'=>0 ));
		$this->assign('rootid',$rs['id']);
		$this->_initRs();
	}
	
	function ACT_status(){
		$ids=array($_POST["id"]);//TODO 改成支持多项操作的模式
		
		$rs=$this->mod->getAll(array('id','status'), array('pid'=>$_GET['pid'],'SQL'=>'id='.implode(' or id=',$ids)) );
		$n=$y=array();
		foreach($rs as $k=>$v){
			if($v['status']=='Y'){
				$y[]=$v['id'];
			}else{
				$n[]=$v['id'];
			}
		}
		if(!empty($y)){
			$this->mod->Update(array('status'=>'N'),array('SQL'=>'id='.implode(' or id=',$y)));
		}
		if(!empty($n)){
			$this->mod->Update(array('status'=>'Y'),array('SQL'=>'id='.implode(' or id=',$n)));
		}
		
		go($_SERVER['HTTP_REFERER']);
	}
	
	function ACT_order(){
		$this->mod->ord();		
		go($_SERVER["HTTP_REFERER"]);
	}

	function ACT_del() {
		if ($this->mod->checkDel($_POST["id"])<1) {
			//删除数据
			$this->mod->Remove(array ("id" => intval($_POST["id"])));
		} else {
			$_SESSION["labelnoempty"] = "1";
		}
		go($_SERVER["HTTP_REFERER"]);
	}
	
	function ACT_post() {
		$data = $_POST;

		if (!empty ($data["eid"])) { //修改
			$rs=$this->mod->getOne(array('pid'),array("id"=>$data["mid"]));
			if(!empty($rs)){
				//当前修改的是一个子级
				$data['pid']=$rs['pid'];
			}else{
				//当前修改的是一个顶级分类
				$alllevel = $this->mod->getmidlist($data["eid"]);
				$ids=array(0);
				foreach($alllevel as $levels){
				   foreach($levels as $value){
				      $ids[]=$value['id'];
				   }
				}
				
				//顶级的PID要与全部下级的PID保持一致
				$rs=$this->mod->getOne(array('pid'),array("id"=>$data["eid"]));
				$this->mod->Update(array("pid"=>$rs['pid']),array("SQL"=>"id=".implode(" or id=",$ids)));
				$data['pid']=$rs['pid'];
			}
			$this->mod->Update($data,array("id"=>$data["eid"]));
		} else { //添加
			$id=$this->mod->Insert($data);
			$this->mod->setOrder($id);
		}
		go( "menu.php?pid=" . $_GET['pid'] . "&mid=" . $_GET["mid"]. "&rid=" . $_GET["rid"] );
	}

	private function _initRs(){
		if (empty ($_POST["id"])) {
			go("menu.php?pid=" . $_GET['pid'] . "&mid=" . $_GET["mid"]);
		}
		$rs=$this->mod->getRecord($_POST["id"]);
		if(!empty($rs)){
			$this->assign("rs",$rs );
			$this->assign("pid", $rs['pid'] );
		}
		return $rs;
	}
	
	private function _initSwitch(){
		$switch=$this->mod->getSwitch($_POST["id"]);
		$this->assign("switch",$switch );
	}
		
	function formatNum($result){
		if(empty($result)) return NULL;
		
		$ids=array();
		foreach($result as $val){
			$ids[]=$val['id'];
		}
		$idsstr=implode(',',$ids);
		$sql="SELECT COUNT(mid)AS num,mid FROM admin WHERE mid IN({$idsstr}) GROUP BY mid";
		$rs=$this->mod->getAll($sql);
		
		$pos=array();
		if(!empty($rs)){
			foreach($rs as $val){
				$pos[$val['mid']]=$val['num'];
			}
		}
		
		foreach($result as $key=>$val){
			if($val['cate']!=0)$val['link']=null;
			$val['num']=empty($pos[$val['id']])?0:$pos[$val['id']];
			$result[$key]=$val;
		}
		return $result;
	}
}
?>