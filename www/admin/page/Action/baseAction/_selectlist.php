<?php
abstract class selectlistAction extends baseAction{
	public $debug=false;
	
	function __construct(){
		parent::__construct();
		//开启调试
		if(debug::check('showappconfig')) {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
			$this->debug=true;
		}
		
		//推荐源
		$this->poolsObj = load($this->AppPrefix."_data_relation");
		$this->debug($this->AppPrefix."_data_relation");
		
		//程序配置信息
		$this->config = $this->loadConfig('selectlist');
		$this->config['param']['param']=strings::configStrDecode($this->config['param']['param']);
		
		//加载当前的列表状态：list/select
		$type = empty($_GET['listtype'])?'list':$_GET['listtype'];
		$this->config['param']['listtype']=$type;//当前数据类型
		
		$this->debug( $this->config );
		
		//导航信息
		$this->assign( "page_nav", $this->tmp['menu'][$_GET["tab"]]['name']." >> ".$this->config['name'] );
	}
	
	function ACT_index(){
		$incmenu = conf('admin.page.incmenu.setup_selectlist',$this->config['param']['listtype']);
		$this->assign("incmenu",$incmenu);
		
		//根据配置信息读取推荐内容及备选内容
		$obj = load("{$this->config['param']['app']}_page_selectlist");
		$result = $obj->initItemList($this->poolsObj, $this->config);
		
		//输出结果
		$this->assign('result',$result);
		
		//根据推荐的不同类型选择相关模板
		$this->assign( "includeTpl", DOCUROOT.'/admin/page/Tpl/selectlist/'.$this->getTpl($this->config['param']['app']).'.html' );
	}
	
	function ACT_save(){
		//debug::d($_POST);
		//debug::d($_GET);
		//debug::d($this->config);
		
		$ids=page::getIDs('M');
		$key=array('pid','mid','aid');
		$valuelist=array();
		foreach($ids as $id){
			$valuelist[]=array($_GET['pid'],$id,$_GET['appid']);
		}
		
		$this->poolsObj->Insert(array('key'=>$key,'valuelist'=>$valuelist));
		$this->poolsObj->exec('update data_relation set ord=id where ord is null');
		$this->setShow();
		
		go($_SERVER['HTTP_REFERER']);
	}

	function ACT_order(){
		$this->poolsObj->ord(array('order'=>'ord','id'=>'id'));
		$this->setShow();
		go($_SERVER["HTTP_REFERER"]);
	}
	
	function ACT_del(){
		$ids=page::getIDs('M');
		if(!empty($ids)){
			$where=array('pid'=>$_GET['pid'],'aid'=>$_GET['appid'],'SQL'=>'id='.implode(' or id=',$ids));
			$this->poolsObj->Remove( $where );
			$this->setShow();
		}
		go($_SERVER['HTTP_REFERER']);
	}
	
	function setShow(){
		//取消之前显示的记录
		$this->poolsObj->Update(array('status'=>'N'),array('pid'=>$_GET['pid'],'aid'=>$_GET['appid'],'status'=>'Y'));
		
		//重新指定要显示的记录
		$this->poolsObj->Update(array('status'=>'Y'),array('pid'=>$_GET['pid'],'aid'=>$_GET['appid'],'order'=>array('ord'=>'DESC'),'limit'=>$this->config['param']['itemnum']));
	}
	
	function getTpl($appname){
		$conf= conf('admin.page.modList',"selectlist.config.app.init.{$appname}");
		$tpl = empty($conf['tpl'])?'asdfasd':$conf['tpl'];
		$tpl = '_'.$tpl;
		
		return $tpl;
	}
	
	function debug($val){
		if(!$this->debug) return;
		debug::d($val);
	}
}