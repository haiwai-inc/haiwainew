<?php
final class site extends Action{
	var $obj;

	function __construct(){
		parent::__construct();
		$this->obj=load("site_site");
		if(!isset($_GET['sort'])) $_GET['sort']=0;
	}

	function ACT_index(){
		$where = array("categorise"=>'Portal','order'=>array('order'=>'asc'));
		if(!empty($_GET['sort'])){
			$where['sort']=intval($_GET['sort']);
		}
		if(!empty($_GET['domain'])){
			$domain=dbtools::escape($_GET['domain']);
			$where['SQL']="id in(SELECT pid as id FROM cfg_domain WHERE domain like '{$domain}%')";
		}
		
		$rs=$this->obj->getList('*',$where,25);
		$this->assign("result",$rs);
		
		//输出导航
		$title=lang("admin.site");
		$this->assign("page_title",$title);
		$this->assign( 'typelist', conf('admin.site.siteType') );
	}

	function ACT_add(){
		//输出导航
		$title=lang("admin.site");
		$this->assign("page_title",$title);
		$this->assign("statustype",'site');
		$this->assign("includeTpl",DOCUROOT.'/admin/page/Tpl/config/_add.html');
	}

	function ACT_post(){
		if(!empty($_POST["url"])) $_POST["url"]=substr($_POST["url"],0,20);
		
		if( !$this->obj->sub_page_exists($_POST["url"],0,'Portal') ){
			$_POST['categorise']='Portal';
			$pid=$this->obj->Insert($_POST);
			$this->obj->setOrder($pid);
			
			$obj=load("site_configure");
			$obj->makeConfigByPid( $pid );
		}
		go("./index.php?sort=".$_POST["sort"]);
	}

	function ACT_del(){
		$id=page::getIDs("I","pid");
		
		//获得站点首页及栏目页面的全部ID
		$pages=$this->obj->getAll(array('id'),array('SQL'=>"id={$id} OR mid={$id}"));
		if( !empty($pages) ){
			$ids=array();
			foreach($pages as $val){
				$ids[]=$val['id'];
			}
			
			//主记录及相关栏目
			$this->obj->Remove(array("SQL"=>"id=".implode(" OR id=",$ids)));
			
			//模块 cfg_mod
			$o=load('site_cfg_mod');
			$o->Remove(array("SQL"=>"pid=".implode(" OR pid=",$ids)));
			
			//应用 cfg_app
			$o=load('page_cfg_app');
			$o->Remove(array("SQL"=>"pid=".implode(" OR pid=",$ids)));
			
			//相关内容 content
			$o=load('article_content');
			$o->Remove(array("SQL"=>"pid=".implode(" OR pid=",$ids)));
			
			//域名
			$o=load('site_cfg_domain');
			$o->Remove(array("SQL"=>"pid=".implode(" OR pid=",$ids)));
			
			//授权
			$o=load('site_cfg_authz');
			$o->Remove(array("SQL"=>"pid=".implode(" OR pid=",$ids)));
			
			//首页页面内容
			$o=load('page_data_unit');
			$o->Remove(array("SQL"=>"pageid=".implode(" OR pageid=",$ids)));
			
			//全局菜单
			$o=load('site_admin');
			$o->Remove(array("SQL"=>"pid=".implode(" OR pid=",$ids)));
		}
		
		go($_SERVER["HTTP_REFERER"]);
	}
	
	function ACT_copy(){
		$id=page::getIDs("I","pid");
		$pagepair=array();//源与目标对应序列
		$obj=$this->obj;
		
		//page，主记录
		$rs=$obj->getOne('*',array('id'=>$id));
		unset($rs['id']);
		unset($data['order']);
		$rs['name']='new'.$rs['name'];
		$rs['url']='new'.$rs['url'];
		$newsiteid=$obj->Insert($rs);
		$pagepair[$id]=$newsiteid;
		
		//相关栏目
		$rs=$obj->getAll('*',array('mid'=>$id));
		foreach($rs as $val){
			$data=$val;
			unset($data['id']);
			unset($data['order']);
			$data['mid']=$newsiteid;
			$pagepair[$val['id']]=$obj->Insert($data);
		}
		
		$obj->exec('Update page set `order`=id where `order` is null');
		//===========================================
		
		//模块 cfg_mod
		$o=load('site_cfg_mod');
		$modpair=array();
		$rs=$o->getAll('*',array('pid'=>$id));
		foreach($rs as $val){
			$data=$val;
			unset($data['id']);
			unset($data['order']);
			$data['pid']=$pagepair[$data['pid']];
			$modpair[$val['id']]=$o->Insert($data);
		}
		$o->exec('Update cfg_mod set `order`=id where `order` is null');
		
		//应用 cfg_app
		$ids=array();
		foreach( $pagepair as $key=>$val ){
			$ids[]=$key;
		}
		
		$o=load('page_cfg_app');
		$apppair=array();
		$rs=$o->getAll('*',array('SQL'=>'pid='.implode(' or pid=',$ids)));
		foreach($rs as $val){
			$data=$val;
			unset($data['id']);
			unset($data['order']);
			$data['pid']=$pagepair[$data['pid']];
			$apppair[$val['id']]=$o->Insert($data);
		}
		$o->exec('Update cfg_app set `order`=id where `order` is null');
		
		
		//相关内容 content
		$o=load('article_content');
		$rs=$o->getAll('*',array('pid'=>$id));
		foreach($rs as $val){
			$data=$val;
			unset($data['id']);
			unset($data['order']);
			$data['pid']=$pagepair[$data['pid']];
			$data['mid']=$modpair[$data['mid']];
			$o->Insert($data);
		}
		$o->exec('Update content set `order`=id where `order` is null');
		
		//全局菜单
		$o = load('site_admin');
		$rs = $o->getAll('*',array('pid'=>$id,'order'=>array('id'=>"ASC")));
		
		$menupair=array();
		foreach($rs as $val){
			$data=$val;
			unset($data['id']);
			unset($data['order']);
			if($val['mid']!=0) $data['mid']=$menupair[$val['mid']];
			$data['pid']=$pagepair[$val['pid']];
			$menupair[$val['id']]=$o->Insert($data);
		}
		
		$o->exec('Update admin set `order`=id where `order` is null');
		
		go($_SERVER['HTTP_REFERER']);
	}
}
?>