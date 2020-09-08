<?php
include DOCUROOT."/admin/member/Action/_base.php";
class index extends baseAction{
	public $lang;
	public $userBasicInfo;
	
	function __construct(){
		parent::__construct();
		$this->assign('page_nav','用户管理');
		
		if(!in_array($_GET['act'],array('index','search','add','del','status'))){
			$_GET['tab']=empty($_GET['tab'])?'security':$_GET['tab'];
			$_GET['id']=empty($_GET['id'])?page::getIDs():$_GET['id'];
			if(empty($_GET['id'])) go('./index.php');
			
			$this->userBasicInfo=$this->obj->getOne("*",array('id'=>$_GET['id']));
			
			$menutab=conf('admin.member.menutab');
			$this->assign('menulist',$menutab);
		}
		
		$this->lang=array('Y'=>lang('cmd.open'),'N'=>'<span style="color:gray;">'.lang('cmd.close').'</span>');
		
	}

	function ACT_index(){
		//记录当前的
		$_SESSION['parentUrl']=$_SERVER['REQUEST_URI'];
		
		$this->assign('page_nav','<a href="'.$_SERVER['REQUEST_URI'].'" class="tishilink">用户管理</a>');

		$this->assign('incmenu',conf('admin.member.incmenu.index'));
		$this->assign('includeTpl','index/_index.html');

		$rs=$this->obj->getList("*",$this->_parseQuery());
		
		$result=$this->_formatRS($rs);
		
		$this->assign('result', $result);
	}
	
	function ACT_search(){
		if(isset($_SESSION['parentUrl'])) unset($_SESSION['parentUrl']);
		
		$this->assign('incmenu',conf('admin.member.incmenu.search'));
		$this->assign('includeTpl','index/_search.html');
		
		$this->assign('userlevel',conf('admin.member.userLevel'));
		$this->assign('query',conf('admin.member.searchType'));
		$this->assign('interest',conf('label.interest'));
		
		$obj=load('site_site');
		$rs=$obj->getAll(array('id','name'),array('categorise'=>'Portal','status'=>'Y'));
		$this->assign('comefrom',$rs);
	}
	
	//基本信息
	function ACT_add(){
		if(!empty($_POST)){
			$_POST['regcode']= 'SYSTEM';
			$_POST['isactive']= 'Y';
			$_POST["jointime"]=time();
			$_POST['password']=$this->obj->passwd($_POST['password'],'set');
			$this->obj->Insert($_POST);
			
			go($_POST['reurl']);
		}
		
		$this->assign('includeTpl','index/_add.html');
		$reg=load('passport_register');
		$this->assign('msg',$reg->initMsg());
	}
	
	function ACT_basic(){
		$this->assign('incmenu','none');
		$this->assign('includeTpl','index/_basic.html');
		
		$rs=$this->userBasicInfo;
		if(!is_file(DOCUROOT.$rs['avatar'])) $rs['avatar']='';//头像文件
		
		//职业
		if(!empty($rs['occupation']))$rs['occupationstr']=conf('label.occupation',$rs['occupation'].'_name');
		
		//地域
		$rs['region']="";
		if(!empty($rs['country']))$rs['region'].=conf('label.region',$rs['country'].'_name').', ';
		if(!empty($rs['province']))$rs['region'].=conf('label.region',$rs['country'].'.'.$rs['province'].'_name').', ';
		if(!empty($rs['city']))$rs['region'].=conf('label.region',$rs['country'].'.'.$rs['province'].'.'.$rs['city'].'_name');
			
		//状态
		$rs['isactive'] = $this->lang[$rs['isactive']];
		$rs['isminisite'] = $this->lang[$rs['isminisite']];
		$rs['isblog'] = $this->lang[$rs['isblog']];
		
		//时间
		$rs['jointime']=times::getTime($rs['jointime']);
		$rs['lastlogintime']=empty($rs['lastlogintime'])?null:times::getTime($rs['lastlogintime']);
		
		//来源
		$site=load('site_site');
		$info=$site->getOne(array('name'),array('id'=>$rs['from']));
		$rs['from']=empty($info['name'])?'--':$info['name'];
		
		$this->assign('rs',$rs);
	}

	//详细信息
	function ACT_info(){
		$this->assign('incmenu','none');
		$this->assign('includeTpl','index/_info.html');
		
		$obj=load('passport_userinfo');
		$rs=$obj->getOne("*",array('uid'=>$_GET['id']));
		
		$rs['birthday']=empty($rs['birthday'])?null:times::getTime($rs['birthday']);
		
		$this->assign('rs',$rs);
	}
	
	//财务信息
	function ACT_payment(){
		$this->assign('incmenu','none');
		$this->assign('includeTpl','index/_info.html');
		
	}

	//安全设置
	function ACT_security(){
		if(!empty($_POST['password'])){
			if($_POST['password']=='------'){
				unset($_POST['password']);
			}else{
				$_POST['password']=$this->obj->passwd($_POST['password'],'set');
			}
			$this->obj->Update($_POST,array('id'=>$_POST['eid']));
			$this->obj->kickUsers($_POST['eid']);
			go($_POST['reurl']);
		}
		
		$this->assign('incmenu','none');
		$this->assign('includeTpl','index/_security.html');
		
		$rs=$this->userBasicInfo;
		$rs['password']=$this->obj->passwd($rs['password'],'get');
		$rs['passwordx']="";
		if(!empty($rs['password'])){
			for($i=0;$i< strlen($rs['password']);$i++){
				$rs['passwordx'].='*';
			}
		}else{
			$rs['passwordx']="******";
		}
		
		$this->assign('rs',$rs);
		
		$reg=load('passport_register');
		$this->assign('msg',$reg->initMsg());
		
		$this->assign('reurl','/admin/member/index.php?act=suc&tab=security&id='.$_GET['id']);
		
	}
	
	//隐私查看
	function ACT_privacy(){
		$this->assign('incmenu','none');
		$this->assign('includeTpl','index/_privacy.html');
	}
	
	//积分操作
	function ACT_score(){
		$conf=conf('global','score');
		
		if(!empty($_POST)){
			$score=intval($_POST['point']);
			
			if(!empty($score)){
				$max=intval($conf['max_admin']);
				if($score>$max) $score = $max;
				
				$min=intval($conf['min_admin']);
				if($score<$min) $score = $min;
				
				func_setScore($_POST['eid'],$_POST['eid'],$score,'admin','admin',conf('global','uid'),true);
			}
			
			go( '/admin/member/index.php?act=suc&tab=score&id='.$_GET['id'] );
		}
		
		$this->assign('incmenu','none');
		$this->assign('includeTpl','index/_score.html');
		
		$rs=$this->userBasicInfo;
		
		$this->assign('score',$conf);
		$this->assign('rs',$rs);
	}
	
	//会员积分历史
	function ACT_scorelist(){
		$obj=load('score_score');
		$rs=$obj->getList("*",array('uid'=>$_GET['id']));
		
		$this->assign('includeTpl','index/_scorelist.html');
		$this->assign('result', $rs);
	}
	
	//权限设置
	function ACT_empower(){
		if($this->userBasicInfo['userlevel']==8){
			if(empty($_GET['siteid'])){
				$this->_getUserPower();
			}else{
				$this->_setUserPower();
			}
		}else{
			$this->_forNotAdmin();
		}
	}
	
	function ACT_suc(){
		$this->assign('includeTpl','index/_suc.html');
		$this->assign('incmenu','none');
		
	}

	function ACT_status(){
		$ids=page::getIDs('M');
		if(!is_array($ids))$ids=array($ids);
		
		$rs=$this->obj->getAll(array('id','isactive'), array('SQL'=>'id='.implode(' or id=',$ids) ) );

		$n=$y=array();
		foreach($rs as $k=>$v){
			if($v['isactive']=='Y'){
				$y[]=$v['id'];
			}else{
				$n[]=$v['id'];
			}
		}
		if(!empty($y))
		$this->obj->Update(array('isactive'=>'N'),array('SQL'=>'id='.implode(' or id=',$y)));
		if(!empty($n))
		$this->obj->Update(array('isactive'=>'Y'),array('SQL'=>'id='.implode(' or id=',$n)));
		
		//强制用户重登录
		$this->obj->kickUsers($ids);
		
		go($_SERVER['HTTP_REFERER']);
	}
	

	//踢除用户
	function ACT_kick(){
		$ids=page::getIDs('M');
		$this->obj->kickUsers($ids);
		go($_SERVER['HTTP_REFERER']);
	}

	function ACT_del(){
		$ids=page::getIDs('M');
		if(!is_array($ids))$ids=array($ids);
		
		//先把已经登录的用户全部踢掉
		$this->obj->kickUsers($ids);
		
		if(!empty($ids)){
			$where=array('SQL'=>'id='.implode(' or id=',$ids));
			$this->obj->Remove($where);
		}
		go($_SERVER['HTTP_REFERER']);
	}

	//全局权限
	private function _getUserPower(){
		$obj=load('site_cfg_authz');
		if(!empty($_POST)){
			$ids=$_POST['id'];
			if(!empty($ids)){
				$where=array('SQL'=>'id='.implode(' or id=',$ids));
				$obj->Remove($where);
			}
		}
		
		$menu=array(
			array('name'=>"添加站点",'link'=>'#','loadurl'=>"/admin/member/addsite.php?id={$_GET['id']}"),
			array('name'=>"删除",'link'=>"./index.php?act=empower&id={$_GET['id']}&tab=empower",'delmsg'=>'你确认删除么？','jsLink'=>true)
		);
		$this->assign('incmenu',$menu);
		$this->assign('includeTpl','index/_authz.html');
		
		$rs=$obj->getList('*',array('uid'=>$_GET['id'],'SQL'=>'pid in(select id from page where mid=0)'));
		if(!empty($rs)){
			$ids=array();
			foreach($rs as $val){
				$ids[]=$val['pid'];
			}			
			
			$siteObj = load("site_site");
			$siters = $siteObj->getAll("*",array("SQL"=>"id=".implode(" OR id=",$ids)));
			$pos=array();
			foreach($siters as $val){
				$pos[$val['id']]=$val;
			}
			
			//格式化站点信息
			foreach($rs as $key=>$val){
				$val['site']=$pos[$val['pid']];
				$rs[$key] = $val;
			}
		}
		$this->assign('result',$rs);
	}
	
	//分站权限
	private function _setUserPower(){
		$authz=load('site_cfg_authz');
		
		if(!empty($_POST)){
			$data=array();
			foreach($_POST as $k=>$v){
				if($k!='reurl') $data[$k]=true;
			}
			$authz->Update(array('authinfo'=>serialize($data)),array('pid'=>$_GET['siteid'],'uid'=>$_GET['id']));
			$this->obj->kickUsers($_GET['id']);
			
			go($_POST['reurl']);
		}
		$menu=array(
			array('name'=>"切换站点",'link'=>"./index.php?act=empower&id={$_GET['id']}&tab=empower"),
			array('name'=>"保存设置",'link'=>"./index.php?act=empower&id={$_GET['id']}&tab=empower&siteid={$_GET['siteid']}",'jsLink'=>true)
		);
		
		$this->assign('incmenu',$menu);
		$this->assign('includeTpl','index/_authzdetail.html');
		
		$rs=$authz->getOne('*',array('pid'=>$_GET['siteid'],'uid'=>$_GET['id']));
		$power=array();
		if(!empty($rs['authinfo'])){
			$power=unserialize($rs['authinfo']);
		}
		$apps=$this->_getAllApp($_GET['siteid']);
		
		$this->assign('apps',$apps);//debug::d($apps);
		$this->assign('powers',$power);//debug::d($power);
		$this->assign('label',conf('admin.member.authLevel'));
		$this->assign('reurl','/admin/member/index.php?act=suc&tab=empower&id='.$_GET['id']);
	}
	
	private function _forNotAdmin(){
		$this->assign('includeTpl','index/_admin.html');
		$this->assign('incmenu','none');
	}
	
	private function _parseQuery(){
		$where=array();
		$sql='';
		
		//用户组
		if(isset($_GET['userlevel'])){
			if($_GET['userlevel']!='') {
				$where['userlevel']=$_GET['userlevel'];
			}
		}
		
		//查询条件
		if(!empty($_GET['keyword'])){
			$begin=empty($sql)?'':' AND ';
			$sql.=$begin.'`'.$_GET['query']."` like '".dbtools::escape($_GET['keyword'])."%'";
		}
		
		//关注领域
		if(!empty($_GET['interest'])){
			$begin=empty($sql)?'':' AND ';
			$lid=intval($_GET['interest']);
			$sql.=$begin."id IN (SELECT uid FROM user_label WHERE lid={$lid} AND type='interest')";
		}
		
		//注册来源
		if(!empty($_GET['comefrom'])){
			$where['from']=$_GET['comefrom'];
		}
		
		//注册时间
		if(!empty($_GET['regstart'])&&!empty($_GET['regend'])){
			$begin=empty($sql)?'':' AND ';
			$tmp1=strtotime($_GET['regstart']);
			$tmp2=strtotime($_GET['regend']);
			
			$start=min($tmp1,$tmp2);
			$end=max($tmp1,$tmp2);
			
			$sql.=$begin."(jointime>={$start} AND jointime<{$end})";
		}
		
		//最后登录时间
		if(!empty($_GET['loginstart'])&&!empty($_GET['loginend'])){
			$begin=empty($sql)?'':' AND ';
			$tmp1=strtotime($_GET['loginstart']);
			$tmp2=strtotime($_GET['loginend']);
			
			$start=min($tmp1,$tmp2);
			$end=max($tmp1,$tmp2);
			
			$sql.=$begin."(lastlogintime>={$start} AND lastlogintime<{$end})";
		}
		
		if(!empty($sql)) $where['SQL']=$sql;//加载复合条件
		
		if(empty($where)) $where['id,!=']=0;//没有过滤条件时
		
		$where['order']=array('id'=>'DESC');//排序
		
		return $where;
	}
	
	private function _getAllApp($siteID){
		$app=conf('appname');
		
		$list=array();
		foreach($app as $k=>$v){
			if($k=='updated') continue;
			
			//获得分组
			if(!strstr($v,'/')) {
				$group='root';
			}else{
				$tmp=explode('/',$v);
				$group=$tmp[0];
			}
			
			$filename=DOCUROOT.'/'.$v.'/Config/auth/_itemList.php';
			if(is_file( $filename )) {
				$value=include $filename;
				foreach($value['content'] as $key=>$val){
					$val['key']=basename($v)."_".$key;//增加项目授权调用标识,如"talk_forum";
					$value['content'][$key]=$val;
				}
				$list[$group][$k]= $value;
			}
		}
		
		//对 admin/dashboard 下增加动态栏目的授权选项
		$obj=load('dashboard_menu');
		$rs=$obj->getOne(array('id'), array ('pid' => $siteID,'link'=>'admin','status'=>'Y' ));
		if(!empty($rs)){
			
			$rs=$obj->getAll("*", array ('pid' => $siteID,'mid'=>$rs['id'],'status'=>'Y' ,'order'=>array("order"=>"ASC")));
			if(!empty($rs)){
				foreach($rs as $val){
					$list['admin']['dashboard']['content'][$val['id']]=array('name'=>$val['name'],'note'=>'','key'=>'dashboard_'.$val['id'],);
				}
			}
			
		}
		
		return $list;
	}
	
	//格式化输出
	private function _formatRS($rs){
		if(empty($rs)) return;
		
		$rs=$this->infoObj->getUserInfos($rs);
		
		$userpos=conf('admin.member.userLevel');
		foreach($rs as $key=>$val){
			if(!empty($val['country']))$val['countrystr']=conf('label.region',$val['country'].'_name');
			if(!empty($val['province']))$val['provincestr']=conf('label.region',$val['country'].'.'.$val['province'].'_name');
			if(!empty($val['city']))$val['citystr']=conf('label.region',$val['country'].'.'.$val['province'].'.'.$val['city'].'_name');
			
			$val['userlevel']=$userpos[$val['userlevel']];
			$val['lastlogintime'] = empty($val['lastlogintime'])?0:times::getTime($val['lastlogintime']);
			$val['isactive']=isset($this->lang[$val['isactive']])?$this->lang[$val['isactive']]:null;
			$val['isonline']=empty($val['sessionid'])?0:$this->obj->isOnline($val['sessionid'],$val['nickname']);
			$rs[$key]=$val;
		}

		return $rs;
	}
	
}