<?php
class site_configure{
	var $domain;
	var $memcacheConfig = null;

	function __construct(){
		global $_GlobalConfig;
		if(isset($_GlobalConfig)){
			$this->memcacheConfig = $_GlobalConfig;
		}
	}

	//使用域名获取当前站点配置
	function getConfig($domain=null,$clear=false){
		static $config;

		$this->domain=empty($domain)?$_SERVER['HTTP_HOST']:$domain;
		$this->domain=strtolower($this->domain);
		
		if($clear)$config[$this->domain]=null;

		if( !isset($config[$this->domain]) ){
			
			//启用valueCache,初始化相关变量
			$filename = $this->getFileName($this->domain);
			$cacheID = $this->getCacheID($this->domain);
			$obj=func_initValueCache($filename,$cacheID);
			
			//读取
			$config[$this->domain]=$obj->get($this->domain);
			
			if( empty($config[$this->domain]) || $this->checkClear()){
				$config[$this->domain]=$this->makeConfigByDomain($this->domain);
			}
		}
		
		return $config[$this->domain];
	}
	
	//清除各服务器的全局缓存
	function clearConfig($pid,$debug=false){
		$workNodeCfg = isset($_POST['item__server__worknode'])?$_POST['item__server__worknode']:'';
		
		//没有设置多服务器节点的情况
		if(empty($workNodeCfg)) {
			$this->makeConfigByPid( $pid );
			return;
		}
		
		//跨多服务器的情况
		$workNode = array();
		
		$obj=load("site_cfg_domain");
		$ds=$obj->getAll(array('domain'),array('type'=>'domain','pid'=>$pid) );
		if(empty($ds))return;
		
		$arr = explode(",", $workNodeCfg);
		foreach($arr as $val){
			if(strstr($val,":")){
				$cfg = explode(":", $val);
				$workNode[]=array("host"=>$val[0],"port"=>$val[1]);
			}else{
				$workNode[]=array("host"=>$val,"port"=>"11211");
			}
		}

		//遍历域名，然后删除所有节点下该域名的全局缓存
		foreach($ds as $val){
			$key = $this->getCacheID($val['domain']);
			foreach( $workNode as $memconf ){
				$obj = func_initMemcached( $memconf['host'],$memconf['port'] );
				if(!empty($obj)) {
					$obj->delete($key);
					if( $debug ) echo $val['domain']." =>".$memconf['host'].":".$memconf['port']." is OK!\n";
				}else{
					if( $debug ) echo $val['domain']." *****=> ".$memconf['host'].":".$memconf['port']." is False <=*****\n";
				}
			}
		}
	}

	//判断是否要强制清除缓存
	private function checkClear(){
		if( !empty($_GET['clear']) ){
			if( $_GET['clear']=='config' ){
				$_GET['clear']='configOK';//避免重置系统配置信息进入死循环，此处仅检测一次$_GET['clear']
				echo 'Rebuild system configure cache OK!<br>';
				return true;
			}
		}

		return false;
	}

	//获得配置信息的memcache存储位置
	private function getCacheID($domain){
		$id='site_' . str_replace('.','',$domain);
		return $id;
	}

	//获得配置文件存储位置
	private function getFileName($domain=null){
		$domain=empty($domain)?$this->domain:$domain;
		$cid=md5($domain);
		$filename=DOCUROOT."/cache/site/".$this->DeepFolder($cid,3).$cid;
		return $filename;
	}

	//解析目录结构
	private function DeepFolder($id,$n){
		$n=intval($n);
		$n=$n>4?4:$n;

		if($n>0){
			$strarr=array();
			for($c=0;$c<$n;$c++){
				$strarr[]=substr($id,$c*2,2);
			}
			$pathstr = implode("/",$strarr).'/';
			return $pathstr;
		}
		return "";
	}

	//使用域名获取或设置全站配置
	function makeConfigByDomain($domain){
		$config = array();

		$obj_page=load("page_page");
		$rs=$obj_page->getOne("select p.* from page p left join cfg_domain cd on p.id=cd.pid where cd.domain='{$domain}' and cd.type='domain' ");

		$config=empty($rs)?include DOCUROOT."/admin/site/Config/default.php":$this->getConfigValue($rs,$domain);

		return $config;
	}

	//使用PID设置全站配置
	function makeConfigByPid($pid){
		$config = array();

		$domainObj=load('site_cfg_domain');
		$rs=$domainObj->getOne(array('id'),array('pid'=>$pid,'type'=>'domain'));
		if(empty($rs)) return;

		$obj=load("page_page");
		$rs=$obj->getOne("*",array("id"=>$pid));

		$config=empty($rs)?include DOCUROOT."/admin/site/Config/default.php":$this->getConfigValue($rs);

		return $config;
	}

	//读取全部的配置信息
	private function getConfigValue($rs,$domain=null){
		//uniqueID
		$config['uid']=$rs['url'];
		
		//TplID
		$config['tpl']=($rs['tpl']=='default')?$rs['url']:$rs['tpl'];
		
		//updated Beijing date
		$config['updated']=date('Y-m-d H:i:s',times::getTime());
		
		//global info
		$config['global']=$rs;

		//config info
		$config=$this->getMainConfig($config);;

		//app info
		$config=$this->getAppConfig($config);

		//owner info
		$config=$this->getOwnerConfig($config);

		//labelID
		$config['lid']=$this->getLabelID($config);
		
		//domain extends save
		$value=$this->doWrite($config,$domain);

		//存在与当前域名匹配的结果，则格式化域名
		if(!empty($value)) $config=$value;

		return $config;
	}

	private function getMainConfig($config){
		//加载默认配置文件，修改过的还是系统文件默认的
		if(!empty($config['global']['structs']))
		//$structs是一个序列化信息，其中存储的['siteConfig']是当前站点运行时参数
		$structs=unserialize($config['global']['structs']);
			
		//最终输出时去除structs
		unset($config['global']['structs']);

		//站点或页面配置信息
		$siteinfo=empty($structs)?array():$structs;
		$default=include DOCUROOT."/admin/site/Config/default.php";

		foreach($default as $key=>$val){
			if(!isset($siteinfo[$key])) {
				$siteinfo[$key]=$val;
				continue;
			}

			if(is_array($val)){
				foreach($val as $k=>$v){
					if(!isset($siteinfo[$key][$k]))$siteinfo[$key][$k]=$v;
				}
			}
		}

		//对比默认配置文件，对用户没有设置内容的选项设置默认参数
		foreach($siteinfo as $key=>$val){
			if(empty($config[$key]))
			$config[$key]=$siteinfo[$key];
		}

		return $config;
	}

	//全部应用程序配置信息
	private function getAppConfig($config){
		//get all modules
		if(!empty($config['global']['id'])){
			$obj_mod=load('site_cfg_mod');
			$rs=$obj_mod->getAll('*',array("pid"=>$config['global']['id'],'status'=>'Y','categorise,!='=>'page','order'=>array('order'=>'ASC')));

			if(!empty($rs)){
				//获得用户设置的菜单
				$obj=load('dashboard_menu');
				$menu=$obj->getGloablMenu($config['global']['id'],'global');
				
				foreach($rs as $val){
					//get app
					$defaultConfig = $obj_mod->getDefaultConfig($val['appname'],$val['apptype'],$val['categorise']);
					$app = configure::getValue($val['appconfig'],$defaultConfig);
					
					$app['label'] = $val['label'];
					$config['app'][$val['categorise']][$val['label']]=$app;//栏目应用配置信息;
					
					//get nav
					if($val['categorise']=='home'){
						$config['navlist'][$val['label']]=array(
							'name'=>$val['name'],
							'link'=>$val['link'],
							'label'=>$val['label'],
							'cfg'=>$app,
							'sublist'=>isset( $menu[$val['label']] )?$menu[$val['label']]:array(),
						);
					}
					
					//get link
					if( $val['appname']=='introduce' && $val['link']=='footer' ){
						$config['linklist']=$this->getBottomLink($config['global'],$val);
					}
				}
			}
		}

		return $config;
	}

	//站点所有者信息
	private function getOwnerConfig($config){

		$obj_user=load("passport_user","passport");
		$obj_authz=load('site_cfg_authz');

		$rs=$obj_authz->getOne("*",array('pid'=>$config['global']['id'],'level'=>'owner'));
		if(empty($rs)) return $config;

		$userinfo=$obj_user->getOne(array('id','username','nickname','avatar','email','aboutme'),array('id'=>$rs['uid']));
		$config['owner']=$userinfo;
		return $config;
	}
	
	private function getLabelID($config){
		$labelID = $config['global']['id'];
		
		//是否设置语言模板
		if(!empty($config['system']['langbase'])){
			$obj=load('site_site');
			$rs=$obj->getOne(array('id'),array('url'=>$config['system']['langbase'],'mid'=>0));
			if(!empty($rs)) $labelID =$rs['id'];
		}
		
		return $labelID;
	}

	//底部通用链接信息
	private function getBottomLink($pageinfo,$appinfo){
		$obj = load('article_content');
		$appinfo=$this->_loadArticleConfig($appinfo);
		
		$where = array('pid'=>$pageinfo['id'],'mid'=>$appinfo['id'],'order'=>array('order'=>'DESC') );
		$fields = array("id","title","url","filltime","islink","updatetime","cate");

		$list= $obj->getAll($fields,$where);

		$result=array();
		foreach($list as $val){
			$url=$obj->formatUrl($val,$appinfo,null,$pageinfo);
			$result[]=array( 'title'=>$val['title'], 'url'=>$url );
		}

		return $result;
	}
	
	//为site下的bottom link 读取相关appconfig,与article/Action/_base.php中的loadArticleConfig作用一样，但参数不同
	private function _loadArticleConfig($rs){
		if(!empty($rs['appconfig'])){
			$rs['appconfig'] = unserialize($rs['param']);
		}else{
			$rs['appconfig'] = array();
			$config = include DOCUROOT.'/admin/site/Config/modList.php';
			foreach($config['app']['introduce']['config'] as $key=>$val){
				$rs['appconfig'][$key]=$val;
			}
		}
			
		//传递page的类型,
		$rs['pagetype']='site';
		
		return $rs;
	}

	//执行写操作
	private function doWrite($config,$domain=null){
		if(empty($config)){ echo "Empty Site Configure!"; exit;}

		$obj=load("site_cfg_domain");
		if(empty($domain)){
			$ds=$obj->getAll(array('domain'),array('type'=>'domain','pid'=>$config['global']['id']) );
		}else{
			$rs=$obj->getOne(array('pid'),array('type'=>'domain','domain'=>$domain));
			if(!empty($rs)){
				$ds=$obj->getAll(array('domain'),array('type'=>'domain','pid'=>$rs['pid']) );
			}else{
				$ds=array($domain);
			}
		}
		
		//默认的返回值
		$result=array();
		
		foreach($ds as $val){
			//统一为小写
			$val['domain']=strtolower($val['domain']);
			
			//memcache key
			$cacheID = $this->getCacheID($val['domain']);
		
			//文件缓存名
			$filename=$this->getFileName($val['domain']);

			//生成项目本身调用的基础模板，如 book.defaultdomain.org  => array('book'=>array('baseTplPath'=>'defaultdomain'));
			$item=$config['global']['tpl']=='default'?$config['global']['url']:$config['global']['tpl'];
			if(is_dir(DOCUROOT."/".$item)){
				//对于系统中存在的项目，强行绑定域名，TODO 根据域名读取配置变量
				$maindomain=$this->formatBaseDomain($val['domain']);
				$config[$item]['baseTplPath']=$maindomain;
				//标识此配置有关联了系统项目
				$config['sysAppStatus']=$config[$item]['baseTplPath'];
			}

			//格式化输出session域
			if(empty($config['session']['sessiondomain'])) $config=$this->formatSessionDomain($config,$val['domain']);

			//唯一域名
			$config['uniqueDomain']=$this->getUniqueDomain($config['global']['id']);

			//writen cache file
			$obj=func_initValueCache($filename,$cacheID);
			$obj->set($config);
			
			if($val['domain']==$_SERVER['HTTP_HOST']) $result=$config;
		}
		
		return $result;
	}

	/**
	 * 我们的系统仅支持自定义的二级域名，不支持二以上的域名解析
	 *
	 */

	//设定 session 域
	private function formatSessionDomain($config,$domain){
		$arr=explode('.',$domain);

		$p=false;
		foreach($arr as $n){
			if(intval($n)==0){$p=true;break;}
		}
		//如果请求域名为IP直接返回
		if(!$p) return $config;

		if(empty($arr[0]))unset($arr[0]);
		$config['session']['sessiondomain']=empty($arr)?'':'.'.implode('.',$arr);
		return $config;
	}

	//设定 基础域
	private function formatBaseDomain($domain){
		$d=explode(".",$domain);
		$b=empty($d[1])?null:$d[1];
		return $b;
	}

	//获得唯一域名
	private function getUniqueDomain($pid){
		static $result;
		if(!isset($result)){
			$obj=load("site_cfg_domain");
			$rs=$obj->getOne(array('domain'),array('pid'=>$pid,'type'=>'domain','unique'=>'Y',));
			$result=empty($rs)?'':$rs['domain'];
		}
		return $result;
	}
}
?>