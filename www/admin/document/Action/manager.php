<?php
class manager extends Action{
	var $obj;
	var $item;

	function __construct(){
		parent::__construct();
		
		$this->obj=load("document_document");
		
		//页头
		$lang=lang('document');
		$this->assign('page_title',$lang['.']);
		
		//分类
		$this->item=$_GET['item']=empty($_GET['item'])?'develop':$_GET['item'];
		
		//属性列表
		$catelist=conf('label.document');
		if(empty($catelist)) $catelist= include DOCUROOT.'/admin/document/Config/catelist.php';
		$this->assign('catelist',$catelist);
	}

	function ACT_index(){
		$rs=$this->obj->getList(array('id','title','updatetime'),array('sort'=>$this->item,'order'=>array('order'=>'DESC')),25);
		if(!empty($rs)){
			foreach($rs as $k=>$v){
				$v['updatetime']=times::getTime($v['updatetime']);
				$rs[$k]=$v;
			}
		}
		$this->assign('result',$rs);
	}

	function ACT_add(){
		$this->assign('rs',array('sort'=>$this->item));
		$this->_loadEditor('','/data/application/document/'.$this->item.'/' . Cache::getDeepFolder( strings::getRandom(6),3) , array('cate'=>'document'));
	}

	function ACT_update(){
		$this->tpl='manager/add.html';
		$id=page::getIDs();
		$rs=$this->obj->getOne("*",array('id'=>$id));
		$this->item=$_GET['item']=$rs['sort'];
		$this->_loadEditor($rs['content'],'/data/application/document/'.$rs['sort'].'/'.Cache::getDeepFolder( strings::getRandom(6),3),array('mid'=>$id,'cate'=>'document'));
		$this->assign('rs',$rs);
	}

	function ACT_del(){
		$id=page::getIDs('M');
		$this->obj->Remove(array('SQL'=>'id='.implode(' or id=',$id)));
		go($_SERVER['HTTP_REFERER']);
	}

	function ACT_view(){
		$user=load('passport_user');
		$id=page::getIDs();
		$rs=$this->obj->getOne("*",array('id'=>$id));
		$rs['updatetime']=times::getTime($rs['updatetime']);
		$rs['user']=$user->getUser($rs['uid']);
		$this->assign('rs',$rs);
	}
	
	function ACT_post(){
		$data=$_POST;
		$data['updatetime']=time();
		$data['uid']=$_SESSION['UserID'];
		
		//保存编辑器中的引用图片
		if(!empty($data['isSaveRemoteImg'])) $data['content'] = editor::saveImg($data['content'],'/data/application/document/'.$this->item,empty($_POST['refe'])?"":$_POST['refe']);
		
		if(empty($data['eid'])){
			$id=$this->obj->Insert($data);
			$this->obj->setOrder($id);
			
			//更新使用编辑器上传的文件记录
			editor::set($id,'content','document');
		}else{
			$id=intval($data['eid']);
			$this->obj->Update($data,array('id'=>$id));
		}		
		
		go($_POST['reurl']);
	}
	
	## 加载编辑器,$item=array('cate'=>'','mid'=>0);
	private function _loadEditor($content="",$path=null,$item=null){
		$editorConfig=array(
			"name"=>"content",
			"height"=>600,
			"path"=>$path,
			"item"=>$item,
		);
		$this->assign("content",editor::get($editorConfig,$content));
	}
}
?>