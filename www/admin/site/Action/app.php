<?php
require DOCUROOT."/admin/site/Action/_base.php";
final class app extends baseAction{
	var $mod;
	var $cate;

	function __construct() {
		parent :: __construct();
		$this->mod=load("site_cfg_mod");

		//如果是从page编辑回来的链接，根据label计算应该回到哪个tab
		if(!empty($_GET['label'])){
			$fields=array('categorise');
			$where=array(
				'appname'=>'page',
				'label'=>$_GET['label'],
				'pid'=>$_GET['pid']
			);
			$rs=$this->mod->getOne($fields,$where);
			if(!empty($rs)){
				if($rs['categorise']!='home') go('app.php?pid='.$_GET['pid'].'&tab='.$rs['categorise']);
			}
		}

		//home=栏目 app=功能 page=页面
		$_GET["tab"]=$this->cate=empty($_GET["tab"])?'home':$_GET["tab"];

		$this->assign('cate',$this->cate);
		$this->assign("page_nav",$this->tmp['menu'][$_GET["tab"]]['name']);
	}


	function ACT_index(){
		$this->assign("incmenu",conf('admin.site.incmenu.app'));

		$fields="*";
		$where=array(
			"pid"=>$_GET["pid"],
			"categorise"=>$this->cate,
			"order"=>array("order"=>"ASC")
		);

		//调用页面时使用分页
		$func=($_GET['tab']=='page')?'getList':'getAll';
		$rs=$this->mod->$func($fields,$where);

		$rs=$this->mod->formatModData($rs,$_GET['pid'],$_GET['tab']);
		$this->assign("result",$rs);
	}

	function ACT_add(){
		$this->assign("modlist",$this->mod->_loadModList($_GET['tab']));
		if($_GET['tab']=='home')$this->assign("apptypelist",conf('admin.site.appList'));
	}

	function ACT_update(){
		$this->assign("modlist",$this->mod->_loadModList($_GET['tab']));
		if($_GET['tab']=='home')$this->assign("apptypelist",conf('admin.site.appList'));
		
		$id=$this->_getIDs();
		$rs=$this->mod->getOne("*",array('id'=>$id,"pid"=>$_GET["pid"]));
		$this->assign("rs",$rs);
		
		//针对page模块输出相关标识
		if($rs['appname']=='page'){
			if($rs['label']=='homepage') {
				$pages=array('id'=>0);
			}else{
				$where=array('mid'=>$_GET['pid'],'url'=>$rs['label'],'categorise'=>$this->_getPageCate($rs['label']));
				$pages=$this->obj->getOne(array('id','categorise'),$where);
			}
			//$pages 是与mod模块对应的页面单元信息，用于后面的页面访问标识检测
			$this->assign("pages",$pages);
		}
	}

	function ACT_config(){
		//读取模块
		$id=$this->_getIDs();
		$rs=$this->mod->getOne("*",array('id'=>$id,"pid"=>$_GET["pid"]));
		if(empty($rs)) {echo "参数错误"; exit;}

		//模块的默认配置
		$default = $this->mod->getDefaultConfig($rs['appname'],$rs['apptype'],$_GET['tab']);
		$this->assign("config", configure::init($rs['appconfig'], $default) );
		
		//模块类型
		$this->assign("ModName", conf("admin.site.modList","{$_GET['tab']}.{$rs['appname']}.name") );
		
		$this->assign("rs",$rs);
	}

	function ACT_status(){
		$ids=$this->_getIDs('M');

		$rs=$this->mod->getAll(array('id','appname','pid','label','status'), array('pid'=>$_GET['pid'],'SQL'=>'id='.implode(' or id=',$ids)) );

		$n=$y=array();
		$pageUpdate=array();//如果更新的模块是page页面，记录相关状态
		foreach($rs as $k=>$v){
			if($v['status']=='Y'){
				$y[]=$v['id'];
				if($v['appname']=='page'){
					$v['status']='N';
					$pageUpdate[]=$v;
				}
			}else{
				$n[]=$v['id'];
				if($v['appname']=='page'){
					$v['status']='Y';
					$pageUpdate[]=$v;
				}
			}
		}
		if(!empty($y)){
			$this->mod->Update(array('status'=>'N'),array('SQL'=>'id='.implode(' or id=',$y)));
		}
		if(!empty($n)){
			$this->mod->Update(array('status'=>'Y'),array('SQL'=>'id='.implode(' or id=',$n)));
		}

		//循环更新page模块的状态
		if(!empty($pageUpdate)){
			foreach($pageUpdate as $k=>$v){
				$this->obj->Update(array('status'=>$v['status']),array('mid'=>$v['pid'],'url'=>$v['label'],'categorise'=>'Alias'));
			}
		}

		go($_SERVER['HTTP_REFERER']);
	}

	function ACT_del(){
		$ids=$this->_getIDs('M');
		//TODO
		//删除相关模块的附属数据
		$rs=$this->mod->getAll('*',array('pid'=>$_GET['pid'],'SQL'=>'(id='.implode(' or id=',$ids).')'));

		$ids=array();
		if(!empty($rs)){
			foreach($rs as $val){
				if($val['appname']=='page'){
					//对于page的app只有page成功删除后app才能删除
					if( $this->_doPageDelete($val) ) $ids[]=$val['id'];
				}else{
					$ids[]=$val['id'];
				}
			}
		}

		if(!empty($ids)){
			$this->mod->Remove(array('pid'=>$_GET['pid'],'SQL'=>'id='.implode(' or id=',$ids)));
		}
		go($_SERVER['HTTP_REFERER']);
	}

	function ACT_post(){
		if(empty($_POST["eid"])){
			//对于page执行新建操作
			if($_POST['appname']=='page') $this->_doPageCheck();
				
			$_POST['pid']=$_GET['pid'];
			$id=$this->mod->Insert($_POST);
			$this->mod->setOrder($id);
		}else{
			if(empty($_POST['doConfigure'])){
				
				if($_POST['appname']=='page'){
					if(empty($_POST['pageid'])){
						//对于page执行新建操作
						$this->_doPageCheck();
					}else{
						//对于page执行更新操作
						$this->_doPageUpdate();
					}
				}else{
					//对于page执行删除操作
					if($_POST['originAppName']=='page') $this->_doPageDelete(array('label'=>$_POST['originAppLabel']));
				}

				//判断是否可以执行分类修改
				if($_POST['categorise']!=$this->cate) $this->_doCheckAppCate();
			}else{
				//获取配置信息
				$default = $this->mod->getDefaultConfig($_POST['appname'],$_POST['apptype'],$_GET['tab']);
				$_POST['appconfig']=configure::setValue($default);
			}
			//执行更新
			$id=intval($_POST["eid"]);
			$this->mod->Update($_POST,array("id"=>$id,'pid'=>$_GET['pid']));
		}

		//更新系统配置
		$obj=load("site_configure");
		$obj->makeConfigByPid( $_GET["pid"] );
			
		go("./app.php?pid={$_GET["pid"]}&tab={$_GET['tab']}");
	}

	function ACT_order(){
		$this->mod->ord();
		//更新系统配置
		$obj=load("site_configure");
		$obj->makeConfigByPid( $_GET["pid"] );
		go($_SERVER["HTTP_REFERER"]);
	}

	function ACT_error(){

	}

	function ACT_msg(){
		$this->assign( 'pagename', $_GET['pagename'] );
		$this->assign( 'reurl', rawurldecode($_GET['reurl']) );
	}

	private function _doCheckAppCate(){
		$pool=$this->mod->_loadModList($_POST['categorise']);
		if( empty($pool[$_POST['appname']]) ) unset($_POST['categorise']);
	}

	private function _getPageCate($label){
		$cate='Alias';
		if( $label=='homepage' ) $cate ='Portal';
		if( is_dir(DOCUROOT.'/'.$label) ) $cate ='Folder';

		return $cate;
	}

	private function _doPageCheck(){
		if($_POST['label']=='homepage') return ;

		//没有对应的page时自动创建
		$checkStatus=$this->obj->sub_page_exists($_POST['label'],$_GET['pid'],$this->_getPageCate($_POST['label']));
		if( !$checkStatus ){
			$field=array(
				'name'=>$_POST['name'],
				'url'=>$_POST['label'],
				'mid'=>$_GET['pid'],
				'categorise'=>$this->_getPageCate($_POST['label']),
			);
			$pid=$this->obj->Insert($field);
			$this->obj->setOrder($pid);
		}else{
			go('./app?act=msg&msgtype='.$checkStatus.'&pagename='.$_POST['label'].'&pid='.$_GET['pid'].'&tab='.$_GET['tab'].'&reurl='.rawurlencode($_SERVER['HTTP_REFERER']));
		}
	}

	private function _doPageUpdate(){
		if($_POST['label']=='homepage') return ;

		//没有其它的page使用输入的访问标识
		$checkStatus=$this->obj->page_exists($_POST['label'],$_POST['pageid'],$this->_getPageCate($_POST['label']),$_GET['pid']) ;
		if( !$checkStatus){
			$field=array(
				'url'=>$_POST['label'],
				'name'=>$_POST['name'],
				'categorise'=>$this->_getPageCate($_POST['label'])
			);
			$where=array('mid'=>$_GET['pid'],'id'=>$_POST['pageid']);
				
			$this->obj->Update($field,$where);
		}else{
			go('./app?act=msg&msgtype='.$checkStatus.'&pagename='.$_POST['label'].'&pid='.$_GET['pid'].'&tab='.$_GET['tab'].'&reurl='.rawurlencode('./app.php?pid='.$_GET['pid'].'&tab='.$_GET['tab']));
		}
	}

	private function _doPageDelete($rs){
		//由于homepage不是首页的真正url标识，此处不用判断首页的情况
		$where=array('mid'=>$_GET['pid'],'url'=>$rs['label'],'categorise'=>$this->_getPageCate($rs['label']));
		$page=$this->obj->getOne('*',$where);
		if( empty($page) ) return true;

		//缓存已经查询的page,用于page_page->checkDel()中的判断
		page_page::$cache[$page['id']]=$page;

		//页面下面没有数据才能删除
		if($this->obj->checkDel($page['id'])==0){
			$this->obj->Remove($where);
			return true;
		}

		return false;
	}

}
?>