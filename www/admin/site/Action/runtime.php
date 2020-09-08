<?php
require DOCUROOT."/admin/site/Action/_base.php";
final class runtime extends baseAction{
	private $tmpAll=array();
	
	function __construct() {
		parent :: __construct();
		$_GET["tab"]="runtime";
		$this->assign("page_nav",$this->tmp['menu'][$_GET["tab"]]['name']);
		$this->assign("incmenu",conf('admin.site.incmenu.runtime'));
	}
	
	function ACT_index(){
		if(!empty($_POST)){
			$pid=$_POST['pid'];
		
			$result=array();
			foreach($_POST as $k=>$v){
				if(substr($k,0,6)!='item__'||empty($v)) continue;
				
				$config=explode('__',$k);
				$result[$config[1]][$config[2]]=$v;
			}
			
			$this->obj->Update(array('structs'=>serialize($result)),array('id'=>$pid));
			
			//更新系统配置
			$obj=load("site_configure");
			$obj->clearConfig( $pid );
			
			go("./runtime.php?pid={$pid}&tab=runtime&status=Successful");
		}
		$config=$this->doRunTimeConfig();
		$this->assign('value',$config);
	}
	
	private function doRunTimeConfig(){
		$default=include DOCUROOT."/admin/site/Config/default.php";
		unset($default['uid']);
		
		$rs=$this->obj->getResult($_GET['pid']);
		if(empty($rs['structs'])){
			$config=array();
		}else{
			$config=unserialize($rs['structs']);
		}
		
		foreach($default as $k=>$v){
			if(!isset($config[$k])) {
				$config[$k]=$v;
				continue;
			}
			
			if(is_array($v)){
				foreach($v as $x=>$y){
					if(!isset($config[$k][$x]))$config[$k][$x]=$y;
				}
			}
		}
		
		return $config;
	}
	
	function ACT_email(){
		$obj = load('site_email');
		$siteid = intval($_GET['pid']);
		
		if(!empty($_POST)){
			$data=array();

			foreach($_POST['key'] as $k=>$v){
				if( !empty($_POST['title'][$k]) )
					$data[$v]=array(
						'title'=>$_POST['title'][$k],
						"body"=>$_POST['body'][$k],
						"text"=>$_POST['text'][$k],
					);
			}
			$data=serialize($data);
			
			if($rs=$obj->getOne('*',array('id'=>$siteid))){
				$obj->Update(array('mailbody'=>$data),array('id'=>$siteid));
			}else{
				$obj->Insert(array('mailbody'=>$data,'id'=>$siteid));
			}
			go("./runtime.php?act=email&pid={$siteid}&tab=runtime&status=Successful");
		}
		
		$config = array();
		if( $rs = $obj->getOne( '*', array('id'=>$siteid) ) ) $config = unserialize($rs['mailbody']);
		
		$default = conf("admin.site.emailbody");
		foreach($default as $key=>$value){
			$value=isset($config[$key])?$config[$key]:$value;
			$default[$key]=$value;
		}
	
		$this->assign('value',$default);
	}
	
	function ACT_user(){
		$catelist=array();
		
		$cateObj = load('profile_categorise');
		$tmp = $cateObj->getAll('*',array('status'=>'Y','order'=>array('order'=>'ASC')));
		if(!empty($tmp)) foreach($tmp as $val) $catelist[$val['id']]=$val;
		
		$pdObj = load('profile_define');
		$pdList = $pdObj->getAll('*',array('status'=>'Y','order'=>array('order'=>'ASC')));
		if(!empty($pdList)){
			$typelist = conf('admin.member.defineValueType');
			$pdObj->makeCache($pdList);
			foreach($pdList as $key => $val){
				$val['typename'] = $typelist[$val['type']];
				$val['typelist'] = ($val['type']=='select'||$val['type']=="checkbox")?$pdObj->getArrVal($val['id']):null;
				$val['checklist'] = ($val['type']=="checkbox")?$pdObj->getCheckList($val['defaultValue']):null;
				if(isset($catelist[$val['categorise']]))$catelist[$val['categorise']]['define'][] = $val;
			}
		}
		
		//加载系统内置配置
		$catelist[] = conf('admin.site.userConf');
		
		$this->assign( "catelist", $catelist);
		$this->assign( "rs", $this->getCurrentConfig( $_GET['pid'] ) );
	}
	
	private function getCurrentConfig($siteID){
		
		$obj = load("profile_siteIndex");
		$rs=$obj->getAll('*',array('siteID'=>$siteID));
		$result=array();
		if(!empty($rs)){
			foreach($rs as $val){
				$result[$val['profileID']]=$val;
			}
		}
		
		return $result;
	}	
	
	function ACT_userPost(){
		$pid=$_POST['pid'];
		$rs=$this->getCurrentConfig( $_GET['pid'] );
		
		$iList=$this->getInsertList($rs);
		$dList=$this->getDelList($rs);//删除
		$result=$this->getUpdateList($rs, $iList, $dList);
		
		$iList=$result[0];//新增
		$uList=$result[1];//更新
		
		
		$obj = load("profile_siteIndex");
		
		if(!empty($dList))
			$obj->Remove(array('siteID'=>$_GET['pid'],'SQL'=>'profileID='.implode(' OR profileID=',$dList)));
		
		if(!empty($iList)){
			$data=array('key'=>array('siteID','profileID','isNull'));
			foreach($iList as $val){
				$data['valuelist'][]=array($_GET['pid'], $val['profileID'], $val['isNull']);
			}
			$obj->Insert($data);
		}
		
		if(!empty($uList)){
			foreach($uList as $val){
				$obj->Update( array('isNull'=>$val['isNull']), array('siteID'=>$_GET['pid'],'profileID'=>$val['profileID']) );
			}
		}
		
		//清除memcache中相关站点的tabs缓存
		$cateObj = load("profile_categorise");
		$cateObj->clearTabCache($_GET['pid']);
		
		go("./runtime.php?act=user&pid={$pid}&tab=runtime&status=Successful");
	}
	
	private function getInsertList($rs){
		$iList=array();
		
		if(!empty($_POST['id'])){
			foreach($_POST['id'] as $val){
				$this->tmpAll[]=$val;//记录全部添加的profileID
				if(!isset($rs[$val])) $iList[$val]=array('profileID'=>$val,'isNull'=>'N');//新增的profileID
			}
		}
		
		return $iList;
	}
	
	private function getDelList($rs){
		$dList = array();
		
		if(!empty($rs)){
			foreach($rs as $profileID=>$val){
				if(!in_array($profileID,$this->tmpAll)) $dList[]=$profileID;
			}
		}
		
		return $dList;
	}
	
	private function getUpdateList($rs, $iList, $dList ){
		$uList = array();
		
		if(!empty($_POST['isNull'])){
			$tmpList=array();
			foreach($_POST['isNull'] as $val){
				if(!isset($rs[$val])) {
					$iList[$val]['isNull']='Y';//更新新增项目的必选状态
				}else{
					$tmpList[]=$val;
				}
			}
			
			//对修改了必填项属性的记录更新
			foreach($rs as $profileID=>$val){
				//排除要删除的项目
				if(in_array($profileID,$dList) ) continue;
				
				if(in_array($profileID,$tmpList)){
					//原来不是必选，本次提交时选了必选
					if($val['isNull']=='N') $uList[]=array('profileID'=>$profileID,'isNull'=>'Y');
				}else{
					//原来是必选,本次提交时没选
					if($val['isNull']=='Y') $uList[]=array('profileID'=>$profileID,'isNull'=>'N');
				}
				
			}
		}
		
		return array($iList, $uList);
	}
	
	function ACT_config(){
		global $runConfig;
		$runConfig = $this->doRunTimeConfig();
	}
}
?>