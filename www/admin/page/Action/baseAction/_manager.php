<?php
//设置各类页面具体内容，对最后一级分类下的页面内容进行操作
abstract class managerAction extends baseAction{
	protected $nav;

	function __construct() {
		parent :: __construct();
		
		$this->obj=load( $this->AppPrefix.'_page' );
		
		if(empty($_GET['mid'])||empty($_GET['sid'])) alert('404');

		$this->initNav();

		$this->tpl=DOCUROOT.'/admin/page/Tpl/manager/index.html';
		
		if(isset($_SESSION['siteAdmin'])) unset($_SESSION['siteAdmin']);
	}

	function initNav(){
		$act=empty($_GET['act'])?'index':$_GET['act'];
		if(in_array($act,array('post','del','order','status','merge','move'))) return;

		$obj = load( $this->AppPrefix.'_page_level' );
		$this->nav = $obj->getNav($_GET["mid"]);

		$this->assign("nav", $this->nav);
		
		$this->assign("page_title",conf('admin.page.cateList',$this->AppPrefix.'.title'));
		$this->assign("page_nav","内容分类");
		
		$this->assign("itempic",conf('admin.page.cateList',$this->AppPrefix.'.pic'));
		$this->assign('parentURL','catelist?sid='.$this->nav[0]['pid'].'&mid='.$this->nav[0]['mid']);
	}

	function ACT_index(){
		$this->assign("incmenu",conf('admin.page.incmenu.manager'));

		//默认站点可以访问全部专题
		if(conf('global','uid')=='default'){
			$sql="SELECT p.* FROM page p WHERE p.categorise='{$this->AppPrefix}' ORDER BY p.`order` DESC";
		}else{
			$sql="SELECT p.* FROM page p LEFT JOIN cfg_domain cd ON p.id=cd.pid WHERE cd.domain='{$_SERVER['HTTP_HOST']}' AND p.categorise='{$this->AppPrefix}' ORDER BY p.`order` DESC";
		}		
		$page = new page($this->obj->conn,Action::$tplobj);
		$rs=$page->getList($sql);

		$rs=$this->_formatdata($rs);

		$this->assign("includeTpl",DOCUROOT.'/admin/page/Tpl/manager/_index.html');
		$this->assign("result",$rs);
	}

	function ACT_add(){
		$this->assign("categorise",$this->AppPrefix);
		$this->assign("includeTpl",DOCUROOT.'/admin/page/Tpl/manager/_add.html');
	}

	function ACT_post(){
		if(!empty($_POST["url"])) $_POST["url"]=substr($_POST["url"],0,20);
		
		if( !$this->obj->sub_page_exists($_POST["url"],0,$this->AppPrefix) ){
			$_POST["updatetime"]=time();
			$_POST["pid"]=$_GET['sid'];
			$_POST['categorise']=$this->AppPrefix;
			
			$pid=$this->obj->Insert($_POST);
			$this->obj->setOrder($pid);
			
			//自动添加当前域的域名
			$domain=load($this->AppPrefix.'_cfg_domain');
			$data=array(
				'pid'=>$pid,
				'domain'=>$_SERVER['HTTP_HOST'],
				'type'=>'alias' 
			);
			$id=$domain->Insert($data);
			$domain->setOrder($id);
		}
		
		go($_POST['reurl']);
	}
	
	function ACT_order(){
		$this->obj->ord();
		go($_SERVER["HTTP_REFERER"]);
	}

	function ACT_del(){
		$ids=page::getIDs('M');
		if(!empty($ids)){
			$where=array("mid"=>$_GET["mid"],"categorise"=>$this->AppPrefix,'SQL'=>'id='.implode(' or id=',$ids));
			$rs=$this->obj->getAll('*',$where);
			foreach($rs as $k=>$v){
				//执行删除检查
				if($this->obj->checkDel($v['id'])==0)
					$this->obj->Remove(array('id'=>$v['id']));
			}
		}
		go($_SERVER['HTTP_REFERER']);
	}

	function ACT_status(){
		$ids=page::getIDs('M');
		
		$rs=$this->obj->getAll(array('id','status'), array("mid"=>$_GET["mid"],"categorise"=>$this->AppPrefix,'SQL'=>'id='.implode(' or id=',$ids) ) );
		
		$n=$y=array();
		foreach($rs as $k=>$v){
			if($v['status']=='Y'){
				$y[]=$v['id'];
			}else{
				$n[]=$v['id'];
			}
		}
		if(!empty($y))
			$this->obj->Update(array('status'=>'N'),array('SQL'=>'id='.implode(' or id=',$y)));
		if(!empty($n))
			$this->obj->Update(array('status'=>'Y'),array('SQL'=>'id='.implode(' or id=',$n)));
		
		
		go($_SERVER['HTTP_REFERER']);
	}

	function _formatdata($rs){
		if( empty($rs) ) return ;
		
		//page单元id
		$ids=array();
		foreach($rs as $k=>$v){
			$ids[]=$v['id'];
		}
		
		//封面
		$facearr=$this->getFacePic($ids);
		
		//最新留言
		$commarr=$this->getComment($ids);
		
		//格式化输出
		foreach($rs as $k=>$v){
			$v['updatetime']=times::getTime($v['updatetime'],true);
			if(isset($facearr[$v['id']])) $v['face']=$facearr[$v['id']];
			if(isset($commarr[$v['id']])) $v['comment']=$commarr[$v['id']];
			
			//各单元页面的链接地址
			$v['url']='/'.$v['categorise'].'/'.$v['url'].'/';
			
			//当前状态
			$v['statusarr']=$this->getStatus($v['status']);
			
			$rs[$k]=$v;
		}
		return $rs;
	}
	
	private function getFacePic($ids){
		$facearr=array();		
		
		//全部封面图片
		$obj=load($this->AppPrefix.'_data_unit');
		$sql="SELECT du.* FROM data_unit du LEFT JOIN cfg_app ca ON du.mid=ca.id WHERE (du.pageid=".implode(' or du.pageid=',$ids).") AND ca.tpl='face'";
		$rs=$obj->getAll($sql);
		
		if(!empty($rs)){
			foreach($rs as $k=>$v){
				$facearr[$v['pageid']]=$v;
			}
		}
		
		return $facearr;
	}
	
	private function getComment($ids,$num=1){
		$commarr=array();		
		$obj=load('article_comment');
		
		//仅调用一个评论时使用一次查询获得结果
		if($num==1){
			$rs = $obj->getAll("SELECT c.* FROM `".$obj->tableName."` c WHERE (`mid`=".implode(' or `mid`=',$ids).") AND id=(SELECT max(id) FROM `".$obj->tableName."` WHERE `mid`=c.mid) ");
			if(!empty($rs)){
				foreach($rs as $v){
					$v['updatetime']=times::getTime($v['updatetime'],true);
					$commarr[$v['mid']]=$v;
				}
			}
			return $commarr;
		}
		
		//当调用多条评论时必须使用多次查询实现
		foreach($ids as $k=>$v){
			$commarr[$v]=$obj->getAll('*',array('mid'=>$v,'item'=>$this->AppPrefix,'order'=>array('filltime'=>'DESC'),'limit'=>$num));
		}
		
		return $commarr;
	}
	
	private function getStatus($k){
		$tpl=array(
			'Y'=>array('name'=>'发布中','color'=>'green'),
			'N'=>array('name'=>'已关闭','color'=>'red'),
			'GO'=>array('name'=>'跳转...','color'=>'blue'),
			'V'=>array('name'=>'验证中','color'=>'brown'),
		);
		$v=isset($tpl[$k])?$tpl[$k]:$tpl['V'];
		return $v;
	}
}
?>