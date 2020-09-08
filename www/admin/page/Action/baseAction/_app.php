<?php
class appAction extends baseAction{
	var $mod;

	function __construct() {
		parent :: __construct();
		$this->mod=load($this->AppPrefix."_cfg_app");
		
		//加载导航
		$this->assign("page_nav",$this->tmp['menu'][$_GET["tab"]]['name']);
		
		//当直接从page列表页post ID 过来后,对非索引页面要重置referer,这样这些页面处理返回时才不会出错
		if( $_GET['act'] != 'index' ) $_SERVER['HTTP_REFERER']="./app.php?pid={$_GET['pid']}&tab=home";
	}

	function ACT_index(){
		if($this->pageinfo['tpl']=='HTML'){
			//针对使用自定义模板的
			$this->_loadEditor($this->pageinfo['structs'],'/upload/'.$this->AppPrefix.'/'.$this->pageinfo['id'].'/', array( 'cate'=>$this->AppPrefix ) );
			
			//预定义模板
			$this->assign('htmltpl',conf($this->AppPrefix.'.htmltpl'));
			
			//操作界面
			$tpl=$this->_loadTplFile("app/_html.html");
			$this->assign("includeTpl",$tpl);
		}else{
			$this->assign("incmenu",conf('admin.page.incmenu.app'));
			$rs=$this->mod->getAll('*',array('pid'=>$_GET['pid'],"order"=>array("order"=>"ASC")));
			
			$rs=$this->_loadTplData($rs);
			$rs=$this->_formatdata($rs);
			
			$this->assign('result',$rs);
		}
	}

	function ACT_add(){
		$this->assign("modlist",$this->_loadModList());
	}
	
	//自动创建模板中指定的App
	function ACT_create(){
		//debug::d($_GET);exit;
		
		$tplApp=include DOCUROOT.'/'.$this->AppPrefix.'/Config/tpl/'.$this->pageinfo['tpl'].'.php';
		$data=$tplApp[$_GET['tpl']];
		$data['pid']=$_GET['pid'];
		unset($data['id']);
		
		$appid=$this->mod->Insert($data);
		$this->mod->setOrder($appid);
		$url = str_replace('appid=0','appid='.$appid,rawurldecode($_GET['forward']));

		go($url);
	}
	
	/**
	 * 根据传递过来的pid&appid转换地址到page app 管理页面
	 * @return void
	 */
	function ACT_goto(){
		$config=conf('admin.page.modList');
		
		$rs=$this->mod->getOne(array('id','app','pid','tpl','param'),array('pid'=>$_GET['pid'],'id'=>$_GET['appid']));
		
		if(empty($rs['app']))  alert(404);
		if(empty($config[$rs['app']]['admin'][0]['link'])) alert(404);
		
		$patterns=array('/\$pid/','/\$itemid/','/\$tpl/');
		$replacements=array($rs['pid'],$rs['id'],$rs['tpl']);
		$link = preg_replace($patterns,$replacements,$config[$rs['app']]['admin'][0]['link']);
		
		//处理‘录入文章’数据单元
		$rs['param'] = configure::getValue( $rs['param'], $config[$rs['app']]['config']);
		if($rs['app']=='composelist' && !empty($rs['param']['multi'])){
			//多组列表时，定向到文章分类
			if($rs['param']['multi']>1) {
				$link=str_replace('composeContent','composeItem',$link);
			}
		}
		
		//echo $link;exit;
		go($link);
	}

	function ACT_update(){
		$this->assign("modlist",$this->_loadModList());
		$rs=$this->mod->getOne("*",array('id'=>$this->_getIDs(),"pid"=>$_GET["pid"]));
		$this->assign("rs",$rs);
	}
	
	function ACT_config(){
		//读取模块
		$id=$this->_getIDs();
		$rs=$this->mod->getOne("*",array('id'=>$id,"pid"=>$_GET["pid"]));
		if(empty($rs)) {echo "参数错误"; exit;}
		
		//配置信息
		$appCfgList=$this->_loadModList();
		$this->assign("config", configure::init($rs['param'], $appCfgList[$rs['app']]['config']) );
		$this->assign("ModName", $appCfgList[$rs['app']]['name'] );
		
		$this->assign("rs",$rs);
	}
	
	function ACT_status(){
		$ids=$this->_getIDs('M');
		
		$rs=$this->mod->getAll(array('id','status'), array('pid'=>$_GET['pid'],'SQL'=>'id='.implode(' or id=',$ids)) );
		
		$n=$y=array();
		foreach($rs as $k=>$v){
			if($v['status']=='Y'){
				$y[]=$v['id'];
			}else{
				$n[]=$v['id'];
			}
		}
		if(!empty($y))
			$this->mod->Update(array('status'=>'N'),array('SQL'=>'id='.implode(' or id=',$y)));
		if(!empty($n))
			$this->mod->Update(array('status'=>'Y'),array('SQL'=>'id='.implode(' or id=',$n)));
		
		
		go($_SERVER['HTTP_REFERER']);
	}

	function ACT_del(){
		$ids=$this->_getIDs('M');
		if(!empty($ids)){
			$where=array('pid'=>$_GET['pid'],'SQL'=>'id='.implode(' or id=',$ids));
			$rs=$this->mod->getAll('*',$where);
			foreach($rs as $k=>$v){
				//TODO 执行删除检查
			}
			
			$this->mod->Remove($where);
		}
		go($_SERVER['HTTP_REFERER']);
	}

	function ACT_post(){
		if( empty($_POST['eid']) ){
			$rs=$this->mod->getOne('*',array('tpl'=>$_POST['tpl'],'pid'=>$_GET['pid']));
			if(empty($rs)){
				$id=$this->mod->Insert($_POST);
				$this->mod->setOrder($id);
			} //TODO 模板标识重复提示
		}else{
			if(!empty($_POST['doConfigure'])){
				$appCfgList=$this->_loadModList();
				$_POST['param'] = configure::setValue($appCfgList[$_POST['app']]['config']);
			} 
			$id=$_POST['eid'];
			$this->mod->Update($_POST,array('id'=>$id));
		}
		go( $_POST['reurl'] );
	}
	
	function ACT_order(){
		$this->mod->ord();
		go($_SERVER['HTTP_REFERER']);
	}
	
	function ACT_editor(){
		//保存更改
		if(isset($_POST['structs'])) {
			$data=array('structs'=>$_POST['structs']);
			$this->obj->Update($data,array('id'=>intval($_GET['pid'])));
		}

		go("./config.php?act=suc&pid={$_GET['pid']}&tab=config&cate={$_GET['cate']}");
	}

	//格式化应用程序显示
	private function _formatdata($rs){
		$mod=$this->_loadModList();
		$patterns=array('/\$pid/','/\$itemid/','/\$tpl/');
		
		$labels=array();//所有的标签,page编辑回来的链接的判断
		foreach($rs as $k=>$v){
			$item=$mod[$v['app']];
			$replacements=array($_GET['pid'],$v['id'],$v['tpl']);

			if(!empty($item['admin'])){
				foreach($item['admin'] as $kk=>$vv){
					$vv['link'] = '/'.AppName.'/'.$vv['link'];
					$vv['link'] = preg_replace($patterns,$replacements,$vv['link']);
					
					//处理‘录入文章’数据单元
					if($v['app']=='composelist'&&!empty($v['param'])){
						//多组列表时，定向到文章分类
						if($v['param']['multi']>1) {
							$item['name']=str_replace('单组','多组',$item['name']);
							$vv['link']=str_replace('composeContent','composeItem',$vv['link']);
						}
					}
					
					//处理‘图文’数据单元
					if($v['app']=='pictext'&&!empty($v['param'])){
						//视频内容
						if($v['param']['type']=='video') {
							$item['name']=str_replace('图文','视频',$item['name']);
						}
					}
					
					//对于id=0即从模板加载后，还没有初始化的App
					if(empty($v['id'])){
						$vv['link']="app.php?act=create&tpl={$v['tpl']}&pid={$_GET['pid']}&tab={$_GET['tab']}&forward=".rawurlencode($vv['link']);
					}
					
					$item['admin'][$kk]=$vv;
				}
			}
			
			//对标识为显示的项目增加预览列表链接
			if(!empty($v['param']['islist'])){
				if($v['param']['islist'] == 'Y'){
					$item['admin']['listPreview'] = array('title'=>'查看', 'link'=>$this->_getMoreLink($v), 'target'=>'_blank');
				}
			}
			
			$rs[$k]['cpanel']=$item;
		}

		return $rs;
	}

	private function _loadModList(){
		static $list;
		if(empty($list)) $list=conf('admin.page.modList');
		
		return $list;
	}
	
	private function _getMoreLink($appinfo){
		//优先使用用户设置的链接地址
		if(!empty($appinfo['url'])) return $appinfo['url'];
		
		//加入首页判断
		$pagePrefix=empty($this->pageinfo['mid'])?'html':$this->pageinfo['url'];
		
		//使用内容链接
		$moreLink = "/{$pagePrefix}/{$appinfo['tpl']}/more.htm";
		
		return $moreLink;
	}
	
	//加载预定义的模板选项
	private function _loadTplData($rs){
		if(empty($this->pageinfo['tpl']) && !in_array($this->pageinfo['categorise'],array('Portal','Folder'))){
			if($this->AppPrefix=='page'){
				go("/admin/page/tpl.php?pid={$this->pageinfo['id']}&tab=tpl");
			}else{
				go("/{$this->AppPrefix}/admin/tpl.php?pid={$this->pageinfo['id']}&tab=tpl");
			}
		}
		$filename=DOCUROOT."/{$this->AppPrefix}/Config/tpl/".$this->pageinfo['tpl'].'.php';
		$tplApp=file_exists($filename)?include $filename:array();
		
		//当前数据库中存在的项目
		$list = array();
		if( !empty($rs) ){
			foreach($rs as $val){
				$val['param']=configure::getValue( $val['param'], conf('admin.page.modList',$val['app'].'.config') );
				$list[$val['tpl']]=$val;
			}
		}
		
		//更新模板内容
		foreach($tplApp as $k=>$v){
			if(isset($list[$k])){
				$tplApp[$k]=$list[$k];
				unset($list[$k]);
			}
		}
		
		$result=array_merge($tplApp,$list);
		//debug::d($result);
		return $result;
	}
	
	
	## 加载编辑器,$item=array('cate'=>'','mid'=>0);
	private function _loadEditor($content="",$path=null,$item=null){
		$editorConfig=array(
			"name"=>"structs",
			"height"=>600,
			"path"=>$path,
			"item"=>$item,
		);
		$this->assign("structs",editor::get($editorConfig,$content));
	}
}