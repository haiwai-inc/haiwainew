<?php
/**
 * view负责生成下面所有类型的页面，是核心功能之一
 * 以下为page支持类型:
 *
 * 1、站点首页
 * 入口：www.abc.com
 * 参数：domain,url=null
 * 标识：cate=portal
 *
 * 2、站点栏目首页
 * 入口：www.abc.com/group/
 * 参数：domain,url=group
 * 标识：cate=folder
 *
 * 3、实体单元首页，单元指 个人，公司，团体，组织等
 * 入口：www.abc.com/Coca-Cola/  或  www.abc.com/biz/Coca-Cola/
 * 参数：domain,url=Coca-Cola
 * 标识：cate=space
 *
 * 4、虚拟单元首页，单元指栏目命名等，如 /newspaper/  /morning/
 * 入口：www.abc.com/Coca-Cola/ 或  www.abc.com/space/Coca-Cola/
 * 参数：domain,url=Coca-Cola
 * 标识：cate=alias
 *
 * page需要检查的参数 根目录是否有物理文件夹，  是否有page设置，  是否注册用户
 */

class view extends Action{
	protected $pageinfo;
	protected $obj;

	function __construct(){
		parent::__construct();

		//加载页面对象
		$this->obj=load("page_page");

		//加载页面信息及判断页面是否可以访问
		$this->_loadPageInfo();
		
		//处理模板
		$this->tpl=$this->_getTplPath();

		//输出主导航
		$this->_setMainNavTabLabel();
		
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
		$list=array();
		$pid=$this->pageinfo["id"];

		//读取功能列表,生成每个页面单元的数据
		$app=load("page_cfg_app");
		$list['obj']=$app->loadPageUint($pid);

		//输出页面调用的各模块内容
		$this->assign("obj",$list['obj']);

		//针对某类page的自动调用程序;加 ‘_page’是为了避免使用系统关键字
		$plugin_class=str_replace("-", "_", "page_".$this->pageinfo["url"]);
		$plugin_file=DOCUROOT."/plugins/".conf('global','tpl').'/'.$plugin_class.".php";

		//如有设置则加载用于相应的模块
		if( file_exists( $plugin_file ) ){
			include( $plugin_file );
			$extraObj = new $plugin_class();			
			$extraVal=$extraObj->getList($pid);			
			$this->assign("extraObj",$extraVal);
			
			//有额外加载的内容时，合并输出信息到list中进行调试
			$list['extraObj'] = $extraVal ;
		}
		
		//生成通用文件
		$this->_makeCommonIncFile();
		$this->_makeCommonIncFile('homepage');
	}
	
	//加载page的信息
	private function _loadPageInfo(){
		if(empty($_GET["url"])){
			//网站首页
			$where=array(
				'id'=>conf('global','global.id'),
				'categorise'=>'Portal',
				'status'=>'Y',
			);
		}else{
			//内容页面
			$where=array(
				'url'=>dbtools::escape($_GET["url"]),
				'mid'=>conf('global','global.id'),
			);
		}
		
		$this->pageinfo=$this->obj->pageinfo=$this->obj->getOne('*',$where);
		$this->obj->cache[$this->pageinfo['id']]=$this->pageinfo;
		
		//没有内容
		if(empty($this->pageinfo)) alert("NonePage"); 
		
		//针对转向
		if($this->pageinfo["status"]=="GO")	go($this->pageinfo["keyword"]);
		
		//针对关闭
		if($this->pageinfo["status"]=="N") alert("ClosePage"); 
		
		//验证权限
		if($this->pageinfo["authz"]=="user"&&empty($_SESSION['UserID'])) $this->goback(); 
		if($this->pageinfo["authz"]=="member"&&!$this->isMember()) $this->goback(); 
		
		//检查page的访问域名是否合法
		$domain = load('site_cfg_domain');
		
		//当前页面为站点首页或为当前站点下的隶属页面
		$pid=empty($this->pageinfo['mid'])?$this->pageinfo['id']:$this->pageinfo['mid'];
		$rs=$domain->getOne('*',array('pid'=>$pid,'domain'=>$_SERVER["HTTP_HOST"],'type'=>'domain'));
		
		if( empty($rs) && !empty($this->pageinfo['mid']) ){
			//如果隶属站点没有这个域名，检查当前页面是否有独立的域名授权
			$rs=$domain->getOne('*',array('pid'=>$this->pageinfo['id'],'domain'=>$_SERVER["HTTP_HOST"],'type'=>'alias'));
		}
		
		if(empty($rs)) alert("ErrorDomain");
		
		//找到域名信息后在domain中做静态缓存,方便后面生成文章链接时调用
		$domain->cache[$this->pageinfo['id']]=$rs;
	}
	
	//输出主导航tab标签
	private function _setMainNavTabLabel(){
		$label=($this->pageinfo["categorise"]=='Portal')?'homepage':$this->pageinfo['url'];
		$this->assign("mainNavTabLabel",$label);
	}
	
	/**
	 * 根据公共调用模板的设置情况生成相应的公共调用文件
	 * 每个独立page皆可生成公共调用文件
	 * 
	 * inc/abcd.html  生成供page abcd 下面的页面调用的公共部分
	 * inc/homepage_abcd.html 生成供站点首页调用abcd的缓存
	 */
	private function _makeCommonIncFile($FileType=''){
		$prefix=empty($FileType)?'':$FileType."_";
		$cacheType=empty($FileType)?'page':$FileType;
		
		$name=basename($this->tpl);
		$tpl=str_replace($name,'inc/'.$prefix.$name,$this->tpl);
		
		if(!is_file($tpl)) return;
		
		$id=conf('global','uid').'_'.$this->pageinfo['url'];
		$filename = DOCUROOT."/data/application/{$cacheType}/{$id}.html";
		$cacheID="IncFile_{$cacheType}_{$id}";
		$obj=func_initValueCache($filename,$cacheID);
			
		$content=$this->fetch( $tpl );
		$obj->set($content);
		
		if(debug::check('showIncData')){
			echo "<hr>";
			echo "<strong>{$id}</strong>";
			echo "<hr>";
			echo $obj->get( 'test' );
			echo "<hr>";
		}
	}

	//读取模板路径
	private function _getTplPath(){
		
		//确定模板文件类型及位置
		switch ($this->pageinfo["categorise"]){
			case 'Portal':
				$method="_getPortalTpl";
				break;
			default:
				if(is_dir(DOCUROOT.'/'.$this->pageinfo["url"])){
					$method="_getFolderTpl";
				}else{
					$method="_getAliasTpl";
				}
				break;
		}

		//调用相应的模板分类处理函数
		$tpl=$this->$method();
		
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

	// 站点首页
	private function _getPortalTpl(){
		if($this->pageinfo["tpl"]=='default'){
			return DOCUROOT."/template/{$this->pageinfo["url"]}/index.html";
		}else{
			return DOCUROOT."/template/{$this->pageinfo["tpl"]}/index.html";
		}
	}
	
	// 栏目首页
	private function _getFolderTpl(){
		$folder = DOCUROOT.$this->getTypeBaseTpl($this->pageinfo["url"],'view',true)."/index.html";
		$default = DOCUROOT."/template/".conf('global','uid')."/{$this->pageinfo["url"]}.html";
		$tpl = file_exists($folder)?$folder:$default;
		
		return $tpl;
	}
	
	// 虚拟单元首页，单元指栏目命名等，如 /newspaper/  /morning/
	private function _getAliasTpl(){
		$dirPath = DOCUROOT."/template/".conf('global','uid')."/pages/";
		$default = "{$dirPath}{$this->pageinfo["tpl"]}.html";
		$site = "{$dirPath}{$this->pageinfo["url"]}.html";
		$tpl = file_exists($site)?$site:$default;
		
		return $tpl;
	}
	
	//检查用户是符合会员要求
	private function isMember(){
		if(empty($_SESSION['UserID'])) $this->goback(); 
		
		//TODO 加入判断用户是否符合访问该页面的会员条件
		return true;
	}
	
	private function goback(){
		go('/passport/?redirect='.rawurlencode($_SERVER['REQUEST_URI']));
	}
}
?>