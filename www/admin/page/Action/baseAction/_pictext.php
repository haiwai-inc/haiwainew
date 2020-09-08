<?php
abstract class pictextAction extends baseAction{
	var $mod;
	var $dataunit;
	var $config;
	
	protected $editorCate='pagePic';

	function __construct() {
		parent :: __construct();
		$this->mod=load($this->AppPrefix."_cfg_app");
		$this->dataunit=load($this->AppPrefix."_data_unit");
		$this->config=$this->loadConfig('pictext');
		
		if(!in_array($_GET['act'],array('post','del'))) $this->assign('config',$this->config);
		$this->assign("page_nav",$this->tmp['menu'][$_GET["tab"]]['name']." >> ".$this->config['name']);
		$this->assign('uploadpath','/upload/'.$this->AppPrefix);
		$this->assign('AppPrefix',$this->AppPrefix);
	}

	function ACT_index(){
		$where=array(
			'pageid'=>$this->config['pid'],
			'mid'=>$this->config['id'],
			"order"=>array("order"=>"DESC")
		);
		if(!empty($_GET['itemid'])) $where['itemid']=$_GET['itemid'];
		
		$rs = $this->dataunit->getList( '*', $where );
		if(!empty($rs))
			$rs = $this->dataunit->formatValue( $rs, $this->config, $this->pageinfo );
			
		$this->assign("result",$rs);
		$this->assign("random",strings::getRandom(6));
	}

	function ACT_add(){
		$datatime=time();
		
		//默认的上传文件名
		$filename=md5($this->config['tpl'].'_'.$_SESSION['UserID'].'_'.$datatime);
		$filename=substr($filename,8,8);
		$this->assign('filename',$filename);
		
		//加载编辑器
		$this->_loadEditor('','/upload/'.strftime("%y-%m/%d/", $datatime),array('cate'=>$this->editorCate));
		
		//发布时间
		$this->_loadDatetime();
		
		//加载标题颜色
		$this->_loadColor();
		
		//加载大图
		$rs = $this->_loadBigPic();
		
		$this->assign("result",$rs);
	}

	function ACT_update(){
		$id=$this->_getIDs();
		if(empty($id)){
			$rs=$this->dataunit->getResult($this->config['pid'],$this->config['id']);;
		}else{
			$rs=$this->dataunit->getOne("*",array('id'=>$id,"pageid"=>$this->config['pid'],'mid'=>$this->config['id']));
		}
		
		//默认的上传文件名
		$filename=empty($rs['pic'])?'none':files::getCleanFilename($rs['pic']);
		if( strlen($filename)< 6 ){
			$filename=md5($this->config['tpl'].'_'.$_SESSION['UserID'].'_'.time());
			$filename=substr($filename,8,8);
		}
		$this->assign('filename',$filename);
		
		//加载编辑器
		$this->_loadEditor($rs['text'],'/upload/'.strftime("%y-%m/%d/", time()),array('mid'=>$id,'cate'=>$this->editorCate));
		
		//发布时间
		$this->_loadDatetime($rs['updatetime']);
		
		//设置用户配置信息
		$rs=$this->_setConfigure($rs);
		
		//加载标题颜色
		$this->_loadColor();
		
		//加载大图
		$rs = $this->_loadBigPic($rs);
		
		//加载预览区尺寸
		$info = array( 
			'w'=> $this->config['param']['width'],
			'h'=> $this->config['param']['height'] 
		);
		$info = picture::getImgWH( $info, 200, 200 );
		$rs['picPW'] = $info['w'];
		$rs['picPH'] = $info['h'];
			
		$this->assign("result",$rs);
		$this->assign("randomStr",strings::getRandom(5));
	}

	function ACT_del(){
		$ids=$this->_getIDs('M');
		if(!empty($ids)){
			$where=array('pageid'=>$_GET['pid'],'mid'=>$this->config['id'],'SQL'=>'id='.implode(' or id=',$ids));
			$rs=$this->dataunit->getAll('*',$where);
			foreach($rs as $k=>$v){
				if( substr($v['pic'],0,1)!='/' ) continue;
				$orifile = dirname($v['pic'])."/".'ori_'.basename($v['pic']); //原始上传图像地址
				
				if( file_exists(DOCUROOT.$v['pic']) ) unlink(DOCUROOT.$v['pic']);
				if( file_exists(DOCUROOT.$orifile) ) unlink(DOCUROOT.$orifile);
			}
			$this->dataunit->Remove($where);
		}
		$this->_setShow();
		go($_SERVER['HTTP_REFERER']);
	}
	
	function ACT_suc(){
		$this->assign("includeTpl",DOCUROOT.'/admin/page/Tpl/config/_suc.html');
	}

	function ACT_post(){
		//初始化POST
		$this->_getConfigure();
		
		//数据容器
		$tmp=array();
		
		// 处理上传图片内容
		$obj=load('page_pictext');
		$tmp=$obj->getFileData($tmp,"uploadfile",$this->AppPrefix);
		
		// 记录页面配置信息
		$tmp=$this->_getTmpData($tmp);
		
		if(empty($tmp["eid"])){
			$id=$this->dataunit->Insert($tmp);
			$this->dataunit->setOrder($id);
			
			//更新使用编辑器上传的文件记录
			editor::set($id,'text',$this->editorCate);
		}else{
			$this->dataunit->Update($tmp,array("id"=>$tmp["eid"],'pageid'=>$_GET['pid']));
		}
		
		$url='./pictext.php?pid='.$_GET['pid'].'&appid='.$_GET['appid'].'&tab='.$_GET['tab'].'&itemid='.$_GET['itemid'];
		if($this->config['param']['multi']=='single')$url.='&act=suc';
		if(!empty($_GET['page']))  $url.='&page='.$_GET['page'];
		
		$this->_setShow();
		go($url);
	}
	
	function ACT_status(){
		$ids=page::getIDs('M');
		
		$rs=$this->dataunit->getAll(array('id','status'), array('pageid'=>$_GET['pid'],'mid'=>$this->config['id'],'SQL'=>'id='.implode(' or id=',$ids)) );
		
		$n=$y=array();
		foreach($rs as $k=>$v){
			if($v['status']=='Y'){
				$y[]=$v['id'];
			}else{
				$n[]=$v['id'];
			}
		}
		if(!empty($y)){
			$this->dataunit->Update(array('status'=>'N'),array('SQL'=>'id='.implode(' or id=',$y)));
		}
		if(!empty($n)){
			$this->dataunit->Update(array('status'=>'Y'),array('SQL'=>'id='.implode(' or id=',$n)));
		}
		$this->_setShow();
		go($_SERVER['HTTP_REFERER']);
	}
	
	function ACT_order(){
		$this->dataunit->ord();
		$this->_setShow();
		go($_SERVER["HTTP_REFERER"]);
	}
	
	private function _setShow(){
		//把原来有效的模块全部关闭
		$this->dataunit->Update(array('show'=>'N'),array('pageid'=>$this->config['pid'],'mid'=>$this->config['id'],'show'=>'Y'));
		
		//根据排序及序数重新设置有效的模块
		$where=array(
			'status'=>'Y',
			'pageid'=>$this->config['pid'],
			'mid'=>$this->config['id'],
			'order'=>array('order'=>'DESC'),
			'limit'=>intval($this->config['param']['picnums'])
		);
		$this->dataunit->Update(array('show'=>'Y'),$where);
		
	}
	
	// 加载编辑器,$item=array('cate'=>'','mid'=>0);
	private function _loadEditor($content="",$path=null,$item=null){
		$editorConfig=array(
			"name"=>"text",
			"height"=>450,
			"path"=>$path,
			"item"=>$item,
		);
		$this->assign("text",editor::get($editorConfig,$content));
	}
	
	//　设置发布时间
	private function _loadDatetime($time=null){
		$time=empty($time)?time():$time;
		$this->assign("updatetime",times::getTime($time));
	}
	
	//加载标题颜色
	private function _loadColor(){
		$this->assign('colorlist', conf('admin.page.color')) ;
	}
	
	//加载大图
	private function _loadBigPic($rs=null){
		$rs['picEditorType'] = 'none';
		
		if(empty($rs['pic'])){
			$rs['pic'] =  "";
			$rs['pic_big'] =  "";
			return $rs;
		}
		
		//判断是否为网络图片
		$picpath= strtolower( $rs['pic'] );
		if(substr($picpath,0,7)=='http://'){
			$rs['pic_big']=$rs['pic'];
			$rs['picEditorType'] = 'http';//图片类型网络
			return $rs;
		}
		
		//判断是否有原图
		$dirpath = dirname($rs['pic']);
		$filename = basename($rs['pic']);
		$picname = $dirpath.'/ori_'.$filename;
		
		if( file_exists( DOCUROOT.$picname ) ){
			$rs['pic_big'] = $picname;
			$rs['picEditorType'] = 'file';
			
			$info = picture::getImgWH( picture::getImageInfo(DOCUROOT.$picname), 600, 600 );
			$rs['picW'] = $info['w'];
			$rs['picH'] = $info['h'];
			$rs['picifrH'] = $info['h']+60;//编辑区域的高度
		}
		
		return $rs;
	}
	
	//  设置用户配置信息
	private function _setConfigure($rs){
		if(!empty($rs['configure']))$rs['cfg']=unserialize($rs['configure']);
		unset($rs['configure']);
		
		return $rs;
	}
	
	// 获取用户配置信息
	private function _getConfigure(){
		$result=array();
		foreach($_POST as $k=>$v){
			if(substr($k,0,4)!='cfg_'||empty($v)) continue;
			$key=substr($k,4);
			$result[$key]=$v;
		}
		
		//加载截图坐标
		$result['coordsarr']=array($_POST['pic_x'],$_POST['pic_y'],$_POST['pic_x2'],$_POST['pic_y2']);
		
		$_POST['configure']=serialize($result);
		$_POST['updatetime']=empty($_POST['updatetime'])?time():times::getTimeStamp($_POST['updatetime']);
	}
	
	// 验证临时数据
	private function _getTmpData($tmp){
		$tmp["title"]=empty($_POST["title"])?null:$_POST["title"];
		$tmp["highlight"]=($_POST["highlight"]=='000000'||$_POST["highlight"]=='ffffff')?null:$_POST["highlight"];
		$tmp["url"]=empty($_POST["url"])?null:$_POST["url"];
		$tmp["link"]=empty($_POST["link"])?null:$_POST["link"];
		$tmp["status"]=empty($_POST["status"])?null:$_POST["status"];
		$tmp["summary"]=empty($_POST["summary"])?null:$_POST["summary"];
		$tmp["text"]=empty($_POST["text"])?null:$_POST["text"];
		$tmp["configure"]=empty($_POST["configure"])?null:$_POST["configure"];
		$tmp["width"]=empty($_POST["width"])?null:$_POST["width"];
		$tmp["height"]=empty($_POST["height"])?null:$_POST["height"];
		$tmp["eid"]=empty($_POST["eid"])?null:$_POST["eid"];
		$tmp["pageid"]=$_POST["pageid"];
		$tmp["position"]=$_POST["position"];
		$tmp["itemid"]=$_POST["itemid"];
		$tmp["mid"]=$_POST["mid"];
		
		$tmp["updatetime"]=$_POST['updatetime'];
		$tmp["user"]=empty($_SESSION["UserID"])?0:$_SESSION["UserID"];
		//debug::d($tmp);exit;
		
		return $tmp;
	}
	
}