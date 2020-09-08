<?php
class site_cfg_mod extends Model{
	protected $tableName = 'cfg_mod';
	protected $dbinfo=array("config"=>"main","type"=>"MySQL");

	//输出站点管理模块
	function formatModData($rs,$pid,$tab){
		if(empty($rs)) return ;

		$mod=$this->_loadModList($tab);
		$patterns=array('/\$pid/','/\$itemid/','/\$itemlabel/','/\$cate/');

		foreach($rs as $k=>$v){
			//初始化应用单元
			$item = $mod[$v['appname']];
			$replacements = array($pid,$v['id'],$v['label'],$this->_getPageCate($v['label']));

			//加载用户配置信息
			$item = $this->_init_AppConfig($v,$item);

			//加载管理选项
			$item = $this->_init_admin($item,$patterns,$replacements);

			//加载应用程序的管理链接
			$item = $this->_init_AdminApp($v,$item,$patterns,$replacements);

			//对于是page的应用项目判断是否加载子级page
			$item = $this->_init_SubPage($v,$pid,$item);

			$rs[$k]['cpanel'] = $item;
		}

		return $rs;
	}

	private function _init_AppConfig($v,$item){
		$tmp=empty($v['appconfig'])?array():unserialize($v['appconfig']);
		
		foreach($item['config'] as $key=>$val){
			$item['config'][$key]=(isset($tmp[$key]))?$tmp[$key]:$val['defaultValue'];
		}

		return $item;
	}

	private function _init_admin($item,$patterns,$replacements){
		if( !empty($item['admin']) ){
			foreach($item['admin'] as $kk=>$vv){
				//检查是否设置了禁止管理
				if( !empty( $item['config']['AdminLink'] ) && $kk=='manager' ) {
					if($item['config']['AdminLink']=='no'){
						unset($item['admin'][$kk]);//取消管理选项
						continue;
					}else{
						//设置为指定的特殊管理地址
						$vv['link']=$item['config']['AdminLink'];
					}
				}else{
					//按配置文件的地址设定管理位置
					$vv['link']=preg_replace($patterns,$replacements,$vv['link']);
					if($vv['link']=='/homepage/') $vv['link']='/'; //排除首页的特殊情况

				}
				$item['admin'][$kk]=$vv;
			}
		}
		
		return $item;
	}
	
	private function _init_AdminApp($v,$item,$patterns,$replacements){
		$id = $v['apptype'];	
		$app = conf("admin.site.appList");
		if( !empty( $app[$id]['admin'] ) ) {
			
			foreach($app[$id]['admin'] as $key=>$val){
				$val['link']=preg_replace($patterns,$replacements,$val['link']);
				$item['admin'][$id.$key]=$val;
			}
		}
		return $item;
	}

	private function _init_SubPage($v, $pid, $item){
		if($v['appname']=='page'){
			if(!empty($item['config']['isSubPage'])){
				$pagetpl=conf('admin.page.cateList');
				
				//参数只对有定义的page单元生效
				if(isset($pagetpl[$v['label']])){
					if($item['config']['isSubPage']=='S') //下级页面无分类,直接管理页面
					$link=array('title'=>conf('admin.page.cateList',$v['label'].'.title'),'link'=>"/{$v['label']}/admin/index.php?sid=".$pid."&mid=0" );

					if($item['config']['isSubPage']=='M') //下级页面有分类，先管理分类再管理页面
					$link=array('title'=>conf('admin.page.cateList',$v['label'].'.title'),'link'=>"/{$v['label']}/admin/catelist.php?sid=".$pid );

					if(isset($link))$item['admin']['list']=$link;
				}
			}
		}
		
		return $item;
	}


	/**
	 * 根据当前记录，结合默认配置输出默认设置
	 * 
	 * @param $name  应用程序名称: page,introduce,link...
	 * @param $type  应用程序类型: bbs,blog,article....
	 * @param $cate  功能隶属分类: home,app,page
	 * 
	 * @return array $default
	 */
	function getDefaultConfig($name,$type,$cate){
		$appList=$this->_loadModList($cate);
		$default = $appList[$name]['config'];
		
		//加载功能设置的app设置，并合并到默认项目中
		if( !empty($type) && $type!='None' ){
			$appConfig=conf('admin.site.appList',"{$type}.config");
			if( !empty($appConfig) ) $default = array_merge( $default, $appConfig );
		}
		
		//系统默认的配置信息
		return $default;
	}
	
	//加载站点管理模块
	function _loadModList($key=null){
		static $list;

		if(!isset($list)) $list=conf('admin.site.modList');
		if(empty($key))return $list;
		$result = empty($list[$key])? null : $list[$key] ;

		return $result;
	}

	//判断页面类型
	function _getPageCate($label){
		$cate='Alias';
		if( $label=='homepage' ) $cate ='Portal';
		if( is_dir(DOCUROOT.'/'.$label) ) $cate ='Folder';

		return $cate;
	}

	//读取站点功能菜单
	function getMenuList($siteID,$app){
		if(!in_array($app,array('home','app','page'))) return ;

		static $cache;
		if(!isset($cache)){
			//得到站点下的全部功能
			$pid = $siteID;
			$fields="*";
			$where=array(
				"pid"=>$pid,
				"order"=>array("order"=>"ASC")
			);
			$rs=$this->getAll($fields,$where);

			//对内容分组
			$list=array();
			foreach($rs as $val){
				$list[$val['categorise']][] = $val;
			}

			//对分组内容格式化链接
			foreach($list as $key=>$val){
				$cache[$key]=$this->formatModData($val,$pid,$key );
			}
		}

		if( empty($cache[$app]) ) return null;

		$result=array();
		foreach($cache[$app] as $key=>$val){
			//跳过没有管理入口的功能
			if(empty($val['cpanel']['admin']['manager']['link'])) continue;

			//对于有下一级内容管理的功能，加载下一级的管理菜单
			$sublist=array();
			if(!empty($val['cpanel']['admin']['list'])) $sublist=$this->_getSublist($val);

			//加载page下的项目，此处加载到sublist中
			$pageSublist=$this->_getPageSublist($val,$siteID);
			if(!empty($pageSublist)) $sublist=array_merge($sublist,$pageSublist);

			//加载有应用程序的项目
			$appSublist=$this->_getAppSublist($val,$siteID);
			if(!empty($appSublist)) $sublist=array_merge($sublist,$appSublist);
			
			$switch=empty($val['cpanel']['config']['isOpen'])?false:true;//是否默认展开
			$appact=empty($val['cpanel']['config']['appact'])?'':$val['cpanel']['config']['appact'];//授权标识

			$tmp=array(
			  "id" => $val['id'],
              "name" => $val['name'],
			//站点二级管理页面，关闭返回站点首页的功能，开启加载页面tab菜单功能
              "link" => $val['cpanel']['admin']['manager']['link'],
              "switch"=>$switch,
              "appact"=> $appact,
              "sublist"=>$sublist,
			);

			$result[$val['label']]=$tmp;
		}

		return $result;
	}

	//获取子级栏目
	private function _getSublist($item){
		$result=array();

		//加载已经定义的管理选项
		foreach($item['cpanel']['admin'] as $key=>$val){
			if( $key=='view' ) continue; //跳过预览的链接
			$result[]=array(
			  "id" => $key,
              "name" => $val['title'],
			  "appact" => empty($val['appact'])?'':$val['appact'],
              "link" => $val['link'],
			);
		}

		return $result;
	}

	//获取页面下的栏目
	private function _getPageSublist($appinfo,$siteID){
		static $idp,$apps;

		if(!isset($idp)){
			$idp['homepage']=$appinfo['pid'];

			$obj=load('page_page');
			$rs=$obj->getAll(array('id','url'),array('mid'=>$appinfo['pid']));
			foreach($rs as $val){
				$idp[$val['url']]=$val['id'];
			}
		}

		if(!isset($apps)){
			$obj=load('page_cfg_app');
			$rs=$obj->getAll('*',array( 'SQL'=>'pid='.implode(' OR pid=',$idp)) );

			foreach($rs as $key=>$val){
				//不输出没有管理内容的项目
				$conf=conf('admin.page.modList');
				if(!isset($conf[$val['app']]['admin'][0]['link']))continue;

				//授权标识
				$appact = ($val['app']=='composelist')?'article_'.$val['tpl'].'_'.$val['pid']:$val['app'].'_'.$val['tpl'].'_'.$val['pid'];

				//在授权标识后加上类似 ',page_pictext'之类的系统识别标识,值由page下的配置文件设定
				if(!empty($conf[$val['app']]['appact'])) $appact.= ',' . $conf[$val['app']]['appact'];

				//增加首页的权限标识
				if( $val['pid']==$siteID ){
					$appact.= ',dashboard_homepage';
				}

				//管理单元
				$data=array(
				  "id" => $val['id'],
	              "name" => $val['name'],
				  "appact"=> $appact,
	              "link" => "/admin/page/app.php?act=goto&pid={$val['pid']}&appid={$val['id']}",
				);
				$apps[$val['pid']][$val['tpl']]=$data;
			}
		}

		$pid = empty($idp[ $appinfo['label'] ])?0:$idp[ $appinfo['label'] ];
		$result = empty($apps[$pid])?array():$apps[$pid];

		return $result;
	}
	
	//获取应用程序管理选项
	private function _getAppSublist($appinfo,$siteID){
		$status = false;
		$result = array();
		if( empty($appinfo['cpanel']['admin']) ) return $result;
		
		$appact=empty($appinfo['cpanel']['config']['appact'])?'':$appinfo['cpanel']['config']['appact'];//授权标识
		foreach($appinfo['cpanel']['admin'] as $key=>$val){
			if(in_array($key,array('view','list'))) continue;
			
			$result[]=array(
				"id" => $appinfo['id'],
				"name" => $val['title'],
				"appact"=> $appact,
				"link" => $val['link'],
			);
		}
		return $result;
	}


	function loadParam($rs){
		if(empty($rs)) return;
		
		$rs['param'] = empty($rs['appconfig'])?array():unserialize($rs['appconfig']);
		$rs['appconfig'] = $this->loadConfig($rs['param'],$rs['categorise'],$rs['appname']);
		unset($rs['param']);
		
		return $rs;
	}

	function loadConfig($config,$cate,$type){
		static $defaultconfig;
		if(empty($defaultconfig)) $defaultconfig=conf('admin.site.modList');

		foreach($defaultconfig[$cate][$type]['config'] as $k=>$v){
			if(!isset($config[$k])) $config[$k]=$v['defaultValue'];
		}

		return $config;
	}
}
?>