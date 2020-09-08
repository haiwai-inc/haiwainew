<?php
class page_cfg_app extends Model{
	protected $tableName = 'cfg_app';
	protected $dbinfo=array("config"=>"main","type"=>"MySQL");
	
	//传递page的类型，用于数据调用时读取不同的数据源
	protected $pagetype='page';
	
	//页面生成时缓存全部app
	static $apps;
	
	//为站点建立全局菜单
	function initGlobalMenu($sid,$str){
		$config=strings::configStrDecode($str);//输入参数为  page,islist
		$pageinfo=$this->_getPage($sid,$config['page']);
		
		$nav=$this->getNav($pageinfo);
		
		$list=array();
		foreach($nav as $val){
			$sortinfo=$val;
			
			$tmp=array();
			$tmp['id']=$val['id'];
			$tmp['name']=$val['name'];
			$tmp['link']=empty($config['first'])?$val['link']:'';
			
			if(!empty($config['second'])){
				//针对二级文章生成下属链接
				$obj=load('article_content');
				$rs=$obj->getAll(array('title','cate','url','islink',),array('pid'=>$val['pid'],'mid'=>$val['id'],'status'=>'Y','order'=>array('order'=>'DESC')));
				
				$tmp['sublist']=array();
				foreach($rs as $k=>$v){
					$tmp['sublist'][]=array('name'=>$v['title'],'link'=>$obj->formatUrl($v,$sortinfo,'',$pageinfo));
				}
			}
			
			$list[]=$tmp;
		}
		$result=array("key"=>$config['page'],'val'=>$list);
		return $result;
	}
	
	//only for initGlobalMenu
	private function _getPage($sid,$page){
		$obj=load('page_page');
		$pageinfo=($page=='html')?$obj->getResult($sid):$obj->getPage($sid,$page);
		
		return $pageinfo;
	}
	
	//only for initGlobalMenu
	private function _getArticleList($sid,$page){
		$obj=load('page_page');
		$pageinfo=($page=='html')?$obj->getResult($sid):$obj->getPage($sid,$page);
		
		return $pageinfo;
	}
	
	//为页面单元建立导航
	public function getNav($pageinfo,$sortID,$cateID){
		$list=array();
		$rs=$this->getAll('*',array('pid'=>$pageinfo['id'],'status'=>'Y','order'=>array('order'=>'ASC')));
		
		$tail=$this->_navTailName($pageinfo);//根据page的类型判断结尾
		foreach($rs as $val){
			$val=$this->loadParam($val);
			
			//不作为导航显示
			if( !isset($val['param']['islist']) ) continue;
			if( $val['param']['islist']=='N' ) continue;
			
			$pagename=$this->_navPageName($pageinfo);
			$catename=$this->_navCateName($pageinfo,$val);
			
			$tailstr=($val['app']=='pictext')?'htm':$tail; //对于pictext类型的更多页，结尾使用.htm
			$val['link']="/{$pagename}{$catename}/more.{$tailstr}";
			
			//文章是否是多级
			if($val['app']=='composelist'){
				$val=$this->getMultiArticleNav($val,$pageinfo,$tailstr,$cateID);
			}
			
			//是否为当前页面
			$val['isCurrent']=($sortID==$val['id'])?true:false;
			
			$list[]=$val;
		}
		
		//debug::d($list);
		return $list;
	}
	
	private function _navPageName($pageinfo){
		if($pageinfo['categorise']=='Portal'){
				$pagename='html';
		}else{
			if(in_array($pageinfo['categorise'],array('Folder','Alias'))){
				$pagename = $pageinfo['url'];
			}else{
				$pagename = $pageinfo['categorise'].'/'.$pageinfo['url'];
			}
		}
		return 	$pagename;
	}
	
	private function _navCateName($pageinfo,$appinfo){
		if($pageinfo['url']==$appinfo['tpl']){
			$catename='';
		}else{
			$catename='/'.$appinfo['tpl'];
		}
		return $catename;
	}
	
	private function _navTailName($pageinfo){
		if($pageinfo['categorise']==='Folder'){
			$tail='shtml'; //当页面为实体一级目录时，为避免与其下一级的page文章内容冲突，启用shtml后缀
		}else{
			$tail='html';
		}
		
		return $tail;
	}
	
	private function getMultiArticleNav($val,$pageinfo,$tail,$cateID){
		if($val['param']['multi']>1){
			$obj=load('article_level');
			$where=array(
				'pid'=>$pageinfo['id'],
				'app'=>$val['id'],
				'cate'=>'app',
				'status'=>'Y',
				'order'=>array('order'=>'DESC'),
			);
			$level=$obj->getAll('*',$where);
			
			if(!empty($level)){
				$tmp=array();
				foreach($level as $v){
					if(empty($v['url']))$v['url']=$v['id'];//对于没有设置cate分类名称的，使用其ID作为分类名称
					
					$link=str_replace('/more.'.$tail, '/'.$v['url'].'/more.'.$tail, $val['link']);
					$tmp[]=array(
						'name'=>$v['name'],
						'link'=>$link,
						'isCurrent'=>($cateID==$v['id'])?true:false, //是否为当前页面
					);
				}
				
				$val['sublist']=$tmp;
			}
			
			//对于多章节内容列表，不对第一级提供列表链接
			//TODO,增加此功能
			$val['link']='';
		}
		
		return $val;
	}

	//有缓存的调用
	public function getApp($pid,$tpl){
		static $cache;
		$id=$pid.$tpl;
		if(empty($cache[$id])){
			$where=array(
				'pid'=>$pid,
				'tpl'=>$tpl,
				'status'=>'Y',
			);
			$cache[$id]=$this->getOne('*',$where);
		}
		return $cache[$id];
	}

	/**
	 * 生成每个页面单元的数据
	 * @param int $pid  要加载的页面ID
	 * @param boolean $incOnly  是否只加载共享调用的App
	 * @return array
	 */
	public function loadPageUint($pid,$incOnly=false){
		$where=array('pid'=>$pid,'status'=>'Y');
		if($incOnly) $where['include']='Y';
		$this->apps=$this->getAll('*',$where);
		
		if(empty($this->apps)) return null;

		//提前处理app参数，方便后面的插件程序调用
		foreach($this->apps as $key=>$app){
			//传递page的类型，用于数据调用时读取不同的数据源
			$app['pagetype']=$this->pagetype;
			
			$app=$this->loadParam($app);
				
			$this->apps[$key]=$app;
		}
		
		//显示
		if(!empty($_GET['debug'])){
			func_initSession();
			if($_GET['debug']=='pageAppConf'&&!empty($_SESSION['UserLevel'])) {
				debug::d($this->apps);
				echo "<hr>";
			}
		}

		$list=array();
		foreach($this->apps as $key=>$app){
			$obj=load('plugins/page_plugins_'.$app["app"], 'admin/page');
			$value = $obj->getObjValue($app["pid"],$app);

			//输出当前单元的模板呈现数据
			$list[$app["tpl"]]=array(
				'title'=>$app["name"],
				'app'=>$app["app"],
				'url'=>$this->getAppMoreURL($pid,$app), 
				'description'=>$app["description"],
				'obj'=>$value, 
				'num'=>is_array($value)?count($value):0
			);
		}

		return $list;
	}
	
	function loadParam($rs){
		if(empty($rs)) return;
		
		$rs['param'] = empty($rs['param'])?array():unserialize($rs['param']);
		$rs['appconfig'] = $this->loadConfig($rs['param'],$rs['app']);
		unset($rs['param']);
		
		return $rs;
	}

	function loadConfig($config,$type){
		static $defaultconfig;
		if(empty($defaultconfig)) $defaultconfig=conf('admin.page.modList');
		
		$config = configure::getValue($config,$defaultconfig[$type]['config']);
		return $config;
	}
	
	private function getAppMoreURL($pid,$appinfo){
		if(!empty($appinfo['url'])){
			$url=($appinfo['url']=='#')?"":$appinfo["url"];
			return $url;
		}
		
		$page = load('page_page');
		$pageinfo = $page->getResult($pid);
		
		$tail = $this->_navTailName($pageinfo); //根据page的类型判断结尾
		$tailstr = ($appinfo['app']=='pictext')?'htm':$tail; //对于pictext类型的更多页，结尾使用.htm
		
		$pagename = $this->_navPageName($pageinfo);
		$catename = $this->_navCateName($pageinfo,$appinfo);
		
		$url="/{$pagename}{$catename}/more.{$tailstr}";
		
		return $url;
	}
}
?>