<?php
if(!class_exists("site_admin")) include DOCUROOT."/admin/site/Lib/site_admin.php";
class dashboard_menu extends site_admin{
	private $menutype;
	private $deepN=3;
	private $userPowerPools=array();
	
	private $menuPowerList=array();
	
	function __construct(){
		parent::__construct();
	}
	
	//获得前台全局导航菜单
	function getGloablMenu($siteID){
		$result=array();
		$list = $this->getMenuList( $siteID, 'global' );
		
		if(!empty($list)){
			foreach($list as $val){
				//直接录入的菜单
				if(empty($val['cate'])){
					$result[$val['link']]=$val['sublist'];
					continue;
				}
				
				/**
				 * 定义数据库标识，用于在站点管理后台操作非当前站点时，切换数据标识
				 * 使用查询数据的方式，而非调用conf，避免在初始conf时陷入死循环
				 */
				$DbLabel=Cache::getValue( "DbLabel" );
				if(empty($DbLabel)){
					$site=load('site_site');
					$rs=$site->getOne(array('url'),array('id'=>$siteID));
					Cache::setValue( "DbLabel", $rs['url'] );
				}
					
				//调用其它模块程序的菜单
				$typemenu = conf( 'admin.site.menuType' );
				$app=$typemenu[$val['cate']]['app'];//debug::d($app);
				$obj=load($app['obj']);
				$menu=$obj->$app['act']($siteID,$val['link']);
			
				//如调用程序的同时还有录入的内容，此处调出并进行合并
				$val['cate']=0;
				$menu['val']=empty($val['sublist'])?$menu['val']:array_merge($menu['val'],$val['sublist']);
				
				//输出
				$result[$menu['key']] = $menu['val'];
				
			}
		}
		return $result;
		
	}

	/**
	 * 获取管理菜单
	 * @param int $siteID
	 * @param int $uid
	 * @return array
	 */
	function getAdminMenu($siteid=0,$uid=0){
		$menulist=array();
		$siteid=empty($siteid)?$this->siteID():$siteid;
		$uid=empty($uid)?$this->userID():$uid;
		
		//加载默认站点管理中的内容
		$siteMenuList = $this->getSiteMenu($siteid);
		if( !empty($siteMenuList) ) $menulist['site']=$siteMenuList;
		
		//动态定义的菜单内容
		$dynamicMenu=$this->getMenuList($siteid,'admin');
		if(!empty($dynamicMenu)) {
			foreach($dynamicMenu as $val){
				$menulist[]=$val;
			}
		}
		
		//加入内置管理功能
		$defaultMenu=conf('admin.dashboard.default');
		foreach($defaultMenu as $key=>$val){
			$menulist[$key]=$val;
		}
		
		//初始化用户权限
		$this->initUserPower($siteid,$uid);
	
		//按照登录用户的授权信息，检查菜单
		$menulist=$this->confirmPowerSet($menulist,$uid);

		//设置默认展开项
		$menulist=$this->confirmSwitchSet($menulist);
		
		//设置结尾
		$menulist=$this->confirmEndallSet($menulist);
		
		return array('list'=>$menulist,'type'=>'Multi');
	}
	
	function getAdminMenuPower($siteid=0,$uid=0){
		
		$data=$this->getAdminMenu($siteid,$uid);
		$menulist=$data['list'];
		
		/* *
		 * 返回key=>value 格式的授权数据
		 * array(
		 * 		'site.default_0.home_homepage.31'=>'page_app',
		 * 		.....
		 * );
		 */
		$this->getPowerList($menulist);
		
		return $this->menuPowerList;
	}
	
	//获得网站管理菜单
	private function getSiteMenu($siteID){
		$menu = array(
			'id'=>'site',
			'name'=>lang('admin.site'),
			'sublist'=>array(),
			'switch'=>false,
			"end"=>false,
		);
		
		$sitemod=load('site_cfg_mod');
		$func = conf('admin.site.menu');
		foreach($func as $key=>$val){
			//没有入口的项目
			if( $key=='domain'||$key=='runtime' ) continue;
			
			$sublist=$sitemod->getMenuList($siteID,$key);
			$switch=($key=='home')?true:false;
			$end=($key=='config')?true:false;
			
			$link='/admin/site/'.$val['url']."from=admin&pid=".$siteID."&tab=".$key;
			$tmp=array(
			  "id" => $key,
              "name" => $val['name'],
			  //站点一级菜单，关闭返回站点首页的功能，关闭加载页面tab菜单功能
              "link" => $link,
			  "switch"=>$switch,
			  "appact"=> 'site_app,site_'.$key,
              "sublist"=>$this->formatSublist($sublist),
              "icon" => $this->getICON($sublist,$end,$switch),
			  "end"=>$end,
			);
			
			$menu['sublist'][]=$tmp;
		}
		
		//加载站点内置标签
		$menu=$this->getSiteLabel($menu);
		
		//判断并加载站点切换功能
		$menu=$this->getSubSite($menu);
		
		//没有任何操作选项
		if( empty($menu['sublist']) ) return false;
		
		return $menu; 
	}
	
	private function formatSublist($sublist){
		if(empty($sublist)) return;
		$i=0;
		foreach($sublist as $key=>$val){
			$i++;
			
			$sl=empty($val['sublist'])?false:true;
			$end=($i==count($sublist))?true:false;
			$switch=empty($val['switch'])?false:true;
			
			$val['icon']=$this->getICON($sl,$end,$switch);
			$val['end']=$end;
			
			if($sl) $val['sublist']=$this->formatSublist($val['sublist']);
			
			$sublist[$key]=$val;
		}
		
		return $sublist;
	}
	
	private function getSiteLabel($menu){
		//当有其它功能在前时，修改“设置”的显示方式
		if(!empty($menu['sublist'])){
			$n=count($menu['sublist'])-1;
			$menu['sublist'][$n]['icon']='t_line';
			$menu['sublist'][$n]['end']=false;
		}
		//站点内置标签链接
		$tmp=array(
			  "id" => 'sitelabel',
              "name" => "标签",
              "link" => '/admin/label/',
			  "switch"=>false,
              "sublist"=>null,
              "icon" => 'ender',
			  "end"=>true,
			);
			
		$menu['sublist'][]=$tmp;
		
		return $menu;
	}
	
	private function getSubSite($menu){
		if (!func_checkAuth('superadmin')){
			$obj=load('site_cfg_authz');
			$rs=$obj->getOne("SELECT COUNT(*) AS num FROM cfg_authz WHERE uid={$_SESSION['UserID']} AND pid IN(SELECT id FROM page WHERE mid=0)");
			if($rs['num']<2) return $menu;
		}
		
		//当有其它功能在前时，修改“设置”的显示方式
		if(!empty($menu['sublist'])){
			$n=count($menu['sublist'])-1;
			$menu['sublist'][$n]['icon']='t_line';
			$menu['sublist'][$n]['end']=false;
		}
		
		//生成站点切换的菜单项
		$tmp=array(
			  "id" => 'changesite',
              "name" => "切换",
              "link" => '/admin/?act=changeSite',
			  "switch"=>false,
              "sublist"=>null,
              "icon" => 'ender',
			  "end"=>true,
			);
			
		$menu['sublist'][]=$tmp;
		
		return $menu;
	}
	
	//处理ICON
	private function getICON($sublist,$end,$switch){
		if(!empty($sublist)){
			if($end)
			$icon= $switch?"Tminus":"Tplus";
			else
			$icon= $switch?"minus":"plus";
		}else{
			$icon= $end?"ender":"t_line";
		}
		
		return $icon;
	}
	
	//检查用户的权限设置
	private function confirmPowerSet($menu,$uid,$prefix=''){
		$result=array();
		foreach($menu as $key=>$val){
			$val['authzkey'] = empty($prefix)? $val['id'] : $prefix.".".$val['id'] ;
			
			if( isset( $val['sublist'] ) ){
				$val['sublist'] = $this->confirmPowerSet($val['sublist'],$uid,$val['authzkey']);
				if(!empty($val['sublist'])) $val=$this->confirmEndSet($val);//判断所有项目是否有正确的结尾
			}
			
			//验证当前项目是否有授权
			if( $this->isAllowed($prefix,$val['authzkey'],$uid) ) $result[$key] = $val;
			
		}
		
		//作为第一级菜单，检查是否有子级内容
		if(empty($prefix)){
			foreach($result as $key=>$val){
				if(empty($val['sublist'])) unset($result[$key]);
			}
		}
		
		return $result;
	}
	
	//是否在权限允许范围
	private function isAllowed($prefix,$authzKey,$uid){
		//循环第一级
		if( empty($prefix) ) return true; 
		
		//有授权的项目
		if( isset( $this->userPowerPools[$authzKey] ))  return true;
		
		//当前用户为超管
		$obj=load('passport_passport');
		if( $obj->checkAuth('superadmin') && $_SESSION['UserID']==$uid ) return true;
		
		return false;
	}
	
	//处理默认开启
	private function confirmSwitchSet($menu){
		if(empty($menu)) return;
		
		$sign=false;
		$k='';
		foreach($menu as $key=>$val){
			if( empty($k) ) $k=$key; //记录第一次出现的key
			if( $val['switch'] ) {
				$sign=true;
				break;
			}
		}
		
		if( !$sign ) $menu[$k]['switch']=true;
		
		return $menu;
	}
	
	//处理子级结尾
	private function confirmEndSet($menu){
		if(empty($menu)) return ;
		
		$num = count($menu['sublist']);
		if( $num >0 ){
			$i=0;
			foreach($menu['sublist'] as $key=>$val){
				$i++;
				
				if($i==$num && empty($val['end'])){
					$sublist = empty($val['sublist'])?false : true;
					$end = $val['end'] = true;
					$switch = empty($val['switch'])?false:true;
					$val['icon'] = $this->getICON($sublist,$end,$switch);
					
					$menu['sublist'][$key]=$val;
				}
				
				
			}
		}
		
		return $menu;
	}
	
	//处理根目录菜单结尾
	private function confirmEndallSet($menu){
		if(empty($menu)) return ;
		
		$num=count($menu);
		if( $num >0 ){
			$i=0;
			foreach($menu as $key=>$val){
				$i++;
				if($i==$num && empty($val['end'])){
					$sublist = empty($val['sublist'])? false:true;
					$end = empty($val['end'])? false:true;
					$switch = empty($val['switch'])? false:true;
					
					$val['icon']= $this->getICON($sublist,$end,$switch);
					$menu[$key]=$val;
				}
			}
		}
		return $menu;
	}
	
	//判断用户当前管理的网站ID
	private function siteID(){
		$siteid=empty($_SESSION['UserPower']['siteid'])?conf('global','global.id'):$_SESSION['UserPower']['siteid'];
		return $siteid;
	}
	
	//判断用户ID
	private function userID(){
		$uid = empty($_SESSION['UserID'])?0:$_SESSION['UserID'];
		return $uid;
	}
	
	//循环生成授权信息映射表
	private function getPowerList($menulist){
		if(empty($menulist)) return ;
		
		foreach($menulist as $val){
			if( isset($val['authzkey']) && !empty($val['appact']) ) $this->menuPowerList[$val['authzkey']]=$val['appact'];
			if(!empty($val['sublist'])) $this->getPowerList($val['sublist']);
		}
	}
	
	//初始化用户的菜单授权信息
	private function initUserPower($siteID,$uid){
		$obj=load('site_cfg_authz');
		$rs=$obj->getOne(array('authinfo'),array('pid'=>$siteID,'uid'=>$uid));
		
		$result=empty($rs['authinfo'])?array():unserialize($rs['authinfo']);
		
		//所有人都有的授权
		$result['site.changesite']=true;
		
		$this->userPowerPools=$result;
	}
	
	/**
	 * 获取指定站点下，指定类型的菜单内容
	 * @param int $siteID
	 * @param string $type  有几个特殊类型的菜单 admin,global...
	 * @return array
	 */
	 function getMenuList($siteID,$type){
		$menulist=array();
		$this->siteID=$siteID;
		$this->menuType=$type;
		
		//当前站点下的全部菜单内容
		$menuall=$this->getAll("*", array ('pid' => $siteID,'status'=>'Y' ,'order'=>array("order"=>"ASC")));
		
		//将所有级别进行归类
		foreach( $menuall as $val ){
			$this->MenuTemp[$val['mid']][] = $val;
		}		
		
		if(!empty($this->MenuTemp)){
			//根据type传递过来的值从顶级开始获取级别数组
			$siteMenuID=$this->MenuTemp[0][0]['id'];
			$menulist = $this->getMenuStruct($siteMenuID);
		}
		
		if(empty($menulist)) return $menulist;
		
		//仅取第一级标识为$type的内容作为菜单
		$list=array();
		foreach($menulist as $val){
			if($val['link']==$type){
				$list=$val['sublist'];
				break;
			}
		}
		
		return $list;
	}

	/**
	 * 用于循环处理级别数组
	 * @param int $level
	 * @param int $n 级别深度定义
	 * @return array $list
	 */
	private function getMenuStruct($level,$n=0){
		if(empty($this->MenuTemp[$level])) return;

		$tmplist=array();
		$list = $this->MenuTemp[$level];

		foreach($list as $key=>$val){
			//循环获取子级菜单
			$sublist = $n<$this->deepN ?$this->getMenuStruct( $val['id'], $n+1 ):null; //自循环类型
			if(substr($val['link'],0,4)=='app|') $sublist = $this->getDefinedMenu( substr($val['link'],4) ); //app类型
			
			//判断结尾
			$end=(empty($list[$key+1]))?true:false;
				
			//判断是否开启
			$switch = ($val["sign"]=="on")?true:false;
				
			//判断连接图标
			$icon=$this->getICON($sublist,$end,$switch);
				
			//输出菜单
			$tmplist[]=array(
				'id'=>$val['id'],
				'mid'=>$val['mid'],
				'name'=>$val['name'],
				'link'=>$val['link'],
				'appact'=>empty($val['configure'])?'':$val['configure'],//授权标识，用于框架识别用户权限
				'cate'=>$val['cate'],
				'switch'=>$switch,
				'icon'=>$icon,
				'end'=>$end,
				'sublist'=>$sublist
			);
		}
		return $tmplist;
	}
	
	//获取应用定制的菜单信息，如/office/CRM
	private function getDefinedMenu($configFile){
		$list = conf($configFile);
		
		foreach( $list as $k=>$v ){
			$v['icon'] = $this->getICON($v['sublist'],$v['end'],$v['switch']);
			$list[$k] = $v;
		}
		
		return $list;
	}
}
?>