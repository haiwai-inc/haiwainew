<?php
/**
 代码同步 包含svn github
 
 本机单节点
 inc.updateid.php    inc.webnodes.php
 */

class repository
{
	//报错信息
	public $msg =array(
			'fail'=>"同步失败！",
			'lock'=>"<h1 style='color:red'>服务器同步锁定中 请稍候在发布!</h1>\n",
			'unlock'=>": 解除锁定\n",
			'unlock_error'=>"所有服务器已经全部解锁\n",
			'auth'=>'权限检测失败，请以管理员身份在当前站点重新登录!',
			'parent'=>"请选择父目录\n",
			'parentroot'=>'无法同步根目录',
			'syncPath'=>": 目录与文件位置 \n\n",
			'syncServer'=>"同步服务器中 ...\n",
			'removeServer_token'=>"错误 ...\n",
	);
	
	function __construct($repo_type='svn'){
		$this->memCache=func_initMemcached("sourceNode");
		
		//服务器信息
		$this->servername=empty($_SERVER["SERVER_NAME"])?"":$_SERVER["SERVER_NAME"];
		$this->server_root=defined('REPOROOT')?REPOROOT:DOCUROOT;
		$this->log=$this->server_root."/cache/log.txt";
	}
	
	//生成同步模板
	function init_sync()
	{
		//检查权限
		if(!defined('SKIP_SVN_ADMIN')) {
			if(!$this->checkAuth())	go("/admin/");
		}
		
		$smartyObj=func_getSmarty('include');
		$tpl=DOCUROOT."/include/template/sync_git.html";
		
		$smartyObj->assign("servername",$_SERVER["SERVER_NAME"]);
		$smartyObj->assign("scriptname",$_SERVER["SCRIPT_NAME"]);
		
		//查看日志
		if(!empty($_GET['log'])){
			$rs=$this->get_log();
			$smartyObj->assign("rs",$rs);
		}
		
		//解除锁定
		if(!empty($_GET['unlock'])){
			$rs=$this->unlock();
			$smartyObj->assign("rs",$rs);
		}
		
		//同步
		if(!empty($_POST)){
			$rs=$this->sync();
			$smartyObj->assign("rs",$rs);
		}
		
		echo $smartyObj->fetch($tpl);
	}
	
	//同步
	function sync()
	{
		//检查权限
		if(!defined('SKIP_SVN_ADMIN')){
			if(!$this->checkAuth())	return $this->msg['auth'];
		}
		
		//同步程序
		$sync_data=[
				"publish"=>true,
				"data"=>[]
		];
		
		//获取所有同步服务器IP
		if(!is_file( DOCUROOT."/inc.syncnodes.php" ))exit();
		$syncnodes=include DOCUROOT."/inc.syncnodes.php";
		
		$lock=false;
		if(!empty($_POST['publish'])){
			foreach($_POST['publish'] as $server){
				foreach($syncnodes[$server] as $v){
					$check=$this->memCache->get(systemVersion."_git_".$v);
					if(empty($check)){
						$this->memCache->add(systemVersion."_git_".$v,serialize($sync_data),false,0);
					}else{
						$lock=true;
						break;
					}
				}
			}
		}
		
		if(!empty($lock)){
			$msg=$this->msg['lock'];
		}else{
			$msg=$this->msg['syncServer'];
		}
		
		return $msg;
	}
	
	//权限认证
	private function checkAuth($level=1,$ids=array(0,1)){
		return true;
		
		//是否登录
		if(empty($_SESSION ["UserID"])){
			return false;
		}
		
		if($level==1){
			return true;
		}
		
		//是否为管理员
		if( $_SESSION["UserLevel"]!=1 ){
			return false;
		}
		
		if($level==2){
			return true;
		}
		
		if( !in_array($_SESSION ["UserID"], $ids)){
			return false;
		}
		
		return true;
	}
	
	//解除服务器锁定
	function unlock(){
		if(!is_file( DOCUROOT."/inc.syncnodes.php" ))exit();
		$syncnodes=include DOCUROOT."/inc.syncnodes.php";
		
		foreach($syncnodes as $server){
			foreach($server as $v){
				//解锁服务器lock
				$this->memCache->delete(systemVersion."_git_".$v);
				
				//解锁pythonlock
				$this->memCache->delete(systemVersion."_git_".$v."_lock");
			}
		}
		
		//解锁gitlock
		$memCache=func_initMemcached("sourceNode");
		$memCache->delete(systemVersion."cron_lock");
		
		return $this->msg['unlock'];
	}
	
	//查看提交日志
	function get_log(){
		if(file_exists($this->log)){
			$rs=file_get_contents($this->log);
			return $rs;
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
