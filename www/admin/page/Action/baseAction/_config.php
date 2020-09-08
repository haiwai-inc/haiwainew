<?php
abstract class configAction extends baseAction{
	function __construct() {
		parent :: __construct();
		$_GET["tab"]="config";
		$this->assign("page_nav",$this->tmp['menu'][$_GET["tab"]]['name']);
	}

	function ACT_update(){
		$this->assign("incmenu",'none');

		$this->assign("statustype",$this->AppPrefix);
		$this->assign("rs",$this->pageinfo);
	}

	function ACT_post(){
		//模板截取20个字符
		if(!empty($_POST["url"])) $_POST["url"]=substr($_POST["url"],0,20);
		
		//没有其它的page使用输入的访问标识
		$pid=$_POST['eid'];
		if( !$this->obj->page_exists($_POST["url"],$pid,$_GET['cate'])){
			$_POST["updatetime"]=time();
			
			//保存更改
			$this->obj->Update($_POST,array('id'=>$pid));
			
			//只有更新站点栏目页面时，更新系统配置
			if($this->AppPrefix=='site'){
				$obj=load("site_configure");
				$obj->makeConfigByPid( $pid );
			}
		}

		go("./config.php?act=suc&pid={$pid}&tab=config&cate={$_GET['cate']}");
	}
	
	function ACT_suc(){
	}

	function ACT_error(){
	}

};
?>
