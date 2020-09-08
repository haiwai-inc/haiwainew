<?php
/**
 * show负责生成类似topic,book,news下面的单元页面
 */

class show extends baseAction{
	protected $pageinfo;
	protected $obj;

	function __construct(){
		parent::__construct();

		//加载页面对象
		$this->obj=load($this->AppPrefix."_page");

		//加载页面信息及判断页面是否可以访问
		$this->_loadPageInfo();
		
		//加载调用预定义的模板
		$this->tpl=$this->_getTplPath();

		//输出page内容和模板
		$this->assign("pageinfo",$this->pageinfo);
		
		//输出当前时间
		$this->assign("datetime",times::getTime());
	}

	/**
	 * 前台显示效果输出
	 * @return void;
	 */
	function ACT_index(){
		if($this->pageinfo["tpl"]=='HTML') {
			$list = $this->pageinfo;
		}else{
			$list=array();
			$pid=$this->pageinfo["id"];
	
			//读取功能列表,生成每个页面单元的数据
			$app=load($this->AppPrefix."_cfg_app");
			$list['obj']=$app->loadPageUint($pid);
	
			//输出页面调用的各模块内容
			if($this->pageinfo["tpl"]!='HTML') $this->assign("obj",$list['obj']);
			//debug::d($list);exit;
	
			//针对某类page的自动调用程序;加 ‘_page’是为了避免使用系统关键字
			$plugin_class = str_replace("-", "_",$this->pageinfo["url"]."_page");
			$plugin_file = DOCUROOT.'/'.$this->AppPrefix."/plugins/".$plugin_class.".php";
	
			//如有设置则加载用于相应的模块
			if( file_exists( $plugin_file ) ){
				include_once( $plugin_file );
				$extraObj = new $plugin_class();			
				if( $this->pageinfo["tpl"]!='HTML' ) $this->assign("extraObj",$extraObj->getList($pid));
				
				//有额外加载的内容时，合并输出信息到list中进行调试
				$list['extraObj'] = $extraObj->list ;
			}
		}
		
		//检查调试
		$this->_debug( $list );
		
	}
	
	//加载page的信息
	private function _loadPageInfo(){
		$where=array(
			'url'=>dbtools::escape($_GET["url"]),
			'status,!='=>"N",
			'categorise'=>$this->AppPrefix,
		);
		
		$this->pageinfo=$this->obj->pageinfo=$this->obj->getOne('*',$where);
		
		//没有内容
		if(empty($this->pageinfo)) alert("NonePage"); 
		
		//针对转向
		if($this->pageinfo["status"]=="GO")	go($this->pageinfo["keyword"]);
		
		//针对关闭
		if($this->pageinfo["status"]=="N") alert("ClosePage"); 
		
		//检查page的访问域名是否有独立的域名授权, 同一单元在不同的域名下其可访问性是不同的
		$domain = load($this->AppPrefix.'_cfg_domain');
		$rs=$domain->getOne('*',array('pid'=>$this->pageinfo['id'],'domain'=>$_SERVER["HTTP_HOST"],'type'=>'alias'));
		if(empty($rs)) alert("ErrorDomain");

		//针对验证过的页面设置域名缓存，方便后面生成文章链接时调用
		$domain->cache[$this->pageinfo['id']]=$rs;
	}

	//读取模板路径
	private function _getTplPath(){
		
		if($this->pageinfo["tpl"]=='HTML') {
			//根据编辑器录入的html代码生成最终页面
			$tpl=DOCUROOT.'/admin/page/Tpl/htmlTpl.html';
		}else{
			$tpl=DOCUROOT.'/'.$this->pageinfo["categorise"].'/Tpl/view/'.$this->pageinfo["tpl"].'.html';
		}
		
		//是否启用RSS模板
		if(defined('RSS')) {
			$tpl = str_replace( basename($tpl), RSS, $tpl);
			if(!file_exists($tpl)) alert("NonePageTpl");
		}
		
		//没有发现任何匹配条件的模板
		if(empty($tpl)) alert("NonePageTpl");
		if(!file_exists($tpl)) alert("NonePageTpl");

		return $tpl;
	}

	//检查调试
	private function _debug($list){
		if(isset($_SESSION['UserLevel']) && isset($_GET['debug'])){
			if($_GET['debug']=='showfields' && $_SESSION['UserLevel']==1){debug::d($list);exit;}
			if($_GET['debug']=='showconfig' && $_SESSION['UserLevel']==1){debug::d(conf());exit;}
		}
	}
}
?>