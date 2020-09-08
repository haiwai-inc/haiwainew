<?php
/**
 * 各类页面分类的维护程序
 * 
 * @author weiqi
 *
 */
abstract  class catelistAction extends baseAction{
	protected $mod;
	protected $config;
	protected $menuStr;//索引页导航参数
	
	function __construct(){
		if( empty($_GET['sid']) ) alert('errorID');
		$config=conf('admin.page.cateList',$this->AppPrefix);
		if( empty( $config ))  alert(null,array('title'=>'标识 '.$this->AppPrefix.' 下不能创建page!','content'=>'只能指定的项目下才能创建page！'));
		
		parent::__construct();
		$this->mod=load($this->AppPrefix."_page_level");
		
		$this->initNav();
		
		$this->tpl=DOCUROOT.'/admin/page/Tpl/catelist/index.html';
		$this->assign('parentURL','/admin/site/app.php?pid='.$_GET['sid']);
	}
	
	function initNav(){
		$_GET["mid"] = empty ($_GET["mid"]) ? 0 : intval($_GET["mid"]);
		$_GET["rid"] = empty ($_GET["rid"]) ? 0 : intval($_GET["rid"]);
		
		//按指定顺序，输出当前分类导航
		$nav = $this->mod->getNav($_GET["mid"]);
		
		//加上专有的URL参数
		$num=count($nav);
		$i=0;
		foreach($nav as $k=>$v){
			$i++;
			//记录循环导航的项目是不是到了末尾
			$v['end']=($i==$num)?true:false;
			
			$v['url']='&sid='.$_GET['sid'].'&';
			$nav[$k]=$v;
		}
		
		$this->assign("nav", $nav);
		$this->assign("mid", $_GET["rid"]);
		$this->assign("rid", empty ($nav[1]["mid"]) ? 0 : $nav[1]["mid"]);
		
		$this->assign("page_title",conf( 'admin.page.cateList',$this->AppPrefix.'.title'));
		$this->assign("page_nav","内容分类");
	}

	function ACT_index(){
		$this->assign("includeTpl",DOCUROOT.'/admin/page/Tpl/catelist/_index.html');
		$this->assign("incmenu",conf('admin.page.incmenu.catelist'));
		
		//是否显示空数据提示
		if (empty ($_SESSION["alert"])) {
			$alert = false;
		} else {
			$alert = $_SESSION["alert"];
			unset ($_SESSION["alert"]);
		}
		$this->assign("checkalert", $alert);
		
		
		$_GET['mid']=$mid=empty($_GET['mid'])?0:$_GET['mid'];
		$result = $this->mod->getList('*',array ('pid' =>$_GET['sid'],'mid' =>$mid,"order"=>array("order"=>"DESC") ),25 );
		$result = $this->mod->formatNum($result);
		$result = $this->formatLink($result);
		
		$this->assign("result", $result);
	}

	function ACT_add(){
		$this->assign("includeTpl",DOCUROOT.'/admin/page/Tpl/catelist/_add.html');
	}

	function ACT_update(){
		$this->assign("includeTpl",DOCUROOT.'/admin/page/Tpl/catelist/_add.html');
		
		$id=page::getIDs();
		$result = $this->mod->getOne('*',array('id'=>$id,'pid'=>$_GET['sid']));
		$this->assign('rs',$result);
	}

	

	function ACT_move(){
		$this->assign("includeTpl",DOCUROOT.'/admin/page/Tpl/catelist/_move.html');
		
		$id=page::getIDs();
		$result = $this->mod->getOne('*',array('id'=>$id,'pid'=>$_GET['sid']));
		$this->assign('rs',$result);
	}
	
	function ACT_merge(){
		$this->assign("includeTpl",DOCUROOT.'/admin/page/Tpl/catelist/_move.html');
		
		$id=page::getIDs();
		$result = $this->mod->getOne('*',array('id'=>$id,'pid'=>$_GET['sid']));
		$this->assign('rs',$result);
	}
	
	function ACT_status(){
		$ids=page::getIDs('M');
		
		$rs=$this->mod->getAll(array('id','status'), array('pid'=>$_GET['sid'],'SQL'=>'id='.implode(' or id=',$ids)) );
		
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
	
	function ACT_post(){
		//检查访问标签是否已经被使用
		if(!empty($_POST['url'])){
			$check=$this->mod->getOne(array('id'),array('pid'=>$_GET['sid'],'url'=>$_POST['url'],'id,!='=>$_POST["eid"]));
			if( !empty($check)) {
				$_SESSION["alert"] = "3";
				go($_POST['reurl']);
			}
			if(is_numeric($_POST['url'])) $_POST['url']='not-number-'.$_POST['url'];
		}
		
		if (!empty ($_POST["eid"])) { //修改
			//执行合并分类间内容的操作
			$sign=empty($_POST['doSign'])?'edit':$_POST['doSign'];
			if($sign=='merge'){
				$article = load($this->AppPrefix.'_page');
				$article->Update($_POST,array("mid"=>$_POST["eid"],'pid'=>$_POST['sid']));
			}else{//移动或修改分类
				$this->mod->Update($_POST,array("id"=>$_POST["eid"]));
			}
		} else { //添加
			//获取当前应用程序的类别
			$id=$this->mod->Insert($_POST);
			$this->mod->setOrder($id);
		}
		
		go($_POST['reurl']);
	}
	
	function ACT_del(){
		$ids=$_POST['id'];
		$where = array ('pid'=>$_GET['sid'],"SQL" => "mid=".implode(' or mid =',$ids));
		
		//有子分类的id
		$idsLevel = array();
		$levelrs = $this->mod->getAll(array('mid'),$where);
		foreach($levelrs as $k=>$v){
			$idsLevel[]=$v['mid'];
		}
		
		//有子级page的id
		$article = load($this->AppPrefix.'_page');
		$articlers = $article->getAll( array('mid'), $where);
		$idsArticle = array();
		foreach($articlers as $k=>$v){
			$idsArticle[]=$v['mid'];
		}
		
		//过滤目标ID
		$delid=array(0);
		$levelalert=$articlealert=false;
		foreach($ids as $k=>$v){
			if(in_array($v,$idsLevel)){$levelalert=true;continue;}
			if(in_array($v,$idsArticle)){$articlealert=true;continue;}
			$delid[]=$v;
		}
		
		if($levelalert)$_SESSION["alert"] = "2";
		if($articlealert)$_SESSION["alert"] = "1";
		if($levelalert&&$articlealert)$_SESSION["alert"] = "0";
		
		//删除数据
		$this->mod->Remove(array ("SQL" => "id=".implode(' or id=',$delid)));
		go($_SERVER["HTTP_REFERER"]);
	}
	
	function ACT_order(){
		$this->mod->ord();
		go($_SERVER["HTTP_REFERER"]);
	}
	
	private function formatLink($result){
		if(empty($result)) return null;
		
		foreach($result as $k=>$v){
			$v['sortURL']='catelist.php?sid='.$_GET['sid'].'&mid='.$v['id'].'&rid='.$_GET['mid'];
			$v['pageURL']='index.php?sid='.$_GET['sid'].'&mid='.$v['id'];
			$result[$k]=$v;
		}
		
		return $result;
	}
}
