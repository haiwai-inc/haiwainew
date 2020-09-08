<?php
include DOCUROOT.'/admin/page/Action/_base.php';
abstract class tpl extends baseAction{
	var $mod;
	var $cate;

	function __construct() {
		parent :: __construct();
		$this->assign("page_nav",$this->tmp['menu'][$_GET["tab"]]['name']);
	}
	
	function ACT_index(){
		$this->assign("incmenu",conf('admin.page.incmenu.tpl'));
		$result=($this->AppPrefix=='page')?$this->siteTpl():$this->itemTpl();
		$this->assign('result',$result);
		//debug::d($result);
	}
	
	function ACT_save(){
		$this->obj->Update(array('tpl'=>$_POST['id']),array("id"=>$_GET['pid']));
		
		//根据所选模板的类型设置配置信息
		if( $this->AppPrefix != 'page' ) $this->initTpl($_POST['id']);
		
		go('./config.php?act=suc&cate='.$_GET['cate'].'&pid='.$_GET['pid']);
	}
	
	function initTpl($tpl){
		$config=include DOCUROOT.'/'. $this->AppPrefix .'/Config/tpl/'.$tpl.'.php';
		foreach($config as $k=>$v){
			
		}
	}
	
	private function siteTpl(){
		$tplfolder=DOCUROOT.'/template/'.$this->getBaseFolder();
		$tplpicfolder=str_replace('/template/','/images/tpl/',$tplfolder);
		
		$list=files::fileAll($tplfolder);
		$config=@include($tplfolder.'/config.php');
		
		$result=array();
		
		if(!$this->tplDefined()){
			foreach($list as $v){
				$tplfile=$tplfolder.'/'.$v;
				
				if(substr($v,0,1)=='.') continue; //隐藏文件
				if(substr($v,strlen($v)-5)!='.html') continue; //隐藏文件
				if($v=='index.html') continue; //隐藏文件
				
				if(is_file($tplfile)){
					$tmp=array('tpl'=>str_replace(DOCUROOT,'',$tplfile));
					
					//预览图
					$picfile=$tplpicfolder.'/'.str_replace('.html','.gif',$v);
					if(is_file($picfile)) $tmp['pic'] = str_replace(DOCUROOT,'',$picfile);
					
					//缩略图
					$picsfile=str_replace('.gif','s.gif',$picfile);
					if(is_file($picsfile)) $tmp['pics'] = str_replace(DOCUROOT,'',$picsfile);
					
					//模板说明
					$key=str_replace('.html','',$v);
					$tmp['key']=$key;
					$tmp['config']=empty($config[$key])?null:$config[$key];
					
					//当前模板设置
					$tmp['selected']=($this->pageinfo['tpl']==$key)?true:false;
					
					$result[]=$tmp;
				}
			}
		}
		return $result;
	}
	
	private function itemTpl(){
		$tpl=include DOCUROOT.'/'.$this->AppPrefix.'/Config/inc.tpl.php';
		
		$result=array();
		
		foreach($tpl as $k=>$v){
			$v['key']=$k;
			
			if( empty( $v['pic'] ) ) $v['pic']='/images/default/'.$this->AppPrefix.'.gif';
			if( empty( $v['pics'] ) ) $v['pics']='/images/default/'.$this->AppPrefix.'_s.gif';
			
			$v['selected']=($this->pageinfo['tpl']==$k)?true:false;
			$result[]=$v;
		}
		
		return $result;
	}

	
	//检查是否有专门供当前页面使用的模板
	private function tplDefined(){
		$tplFile = DOCUROOT."/template/".conf('global','uid')."/{$this->pageinfo["url"]}.html";
		return file_exists($tplFile);
	}

	
	private function getBaseFolder(){
		if(empty($this->pageinfo['mid'])) return $this->pageinfo['url'];
		
		$rs=$this->obj->getOne(array('url'),array('id'=>$this->pageinfo['mid']));
		return $rs['url'];
	}
	
}