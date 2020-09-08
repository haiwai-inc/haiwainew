<?php
class manager extends Action{
	protected $mod;
	protected $param;
	protected $config;
	protected $nav;
	
	function __construct() {
		parent :: __construct();
		$this->mod = load("label_label");
		
		//标签类别
		$_GET['cate'] = isset($_GET['cate'])?$_GET['cate']:conf('global','lid');

		//处理页面参数
		$_GET["mid"] = empty ($_GET["mid"]) ? 0 : intval($_GET["mid"]);
		$_GET["rid"] = empty ($_GET["rid"]) ? 0 : intval($_GET["rid"]);
		$_GET["rootid"] = empty ($_GET["rootid"]) ? 0 : intval($_GET["rootid"]);

		//按指定顺序，输出当前分类导航
		$this->nav = $this->mod->getNav($_GET["mid"]);
		$this->assign("nav", $this->nav);//导航数组
		$this->assign("cate", $_GET["cate"]);//根级内容ID
		$this->assign("mid", $_GET["rid"]);//上一级内容ID
		$this->assign("rid",  empty ($nav[1]["mid"]) ? 0 : $nav[1]["mid"]);//用于返回的上上一级内容ID
		$this->assign("rootid", $_GET["rootid"]);//根级内容ID

		//输出基本信息
		$this->assign("page_title", empty($_GET['cate'])?lang("admin.label"):lang("admin.sitelabel") );
		$this->assign("parentURL", empty($_SERVER['HTTP_REFERER'])?'./':$_SERVER['HTTP_REFERER'] );
		
		$this->tpl="manager/index.html";
	}

	function ACT_index() {
		//是否显示空数据提示
		if (empty ($_SESSION["labelalert"])) {
			$alert = false;
		} else {
			$alert = $_SESSION["labelalert"];
			unset ($_SESSION["labelalert"]);
		}
		$this->assign("checkalert", $alert);
	  
		$this->assign('includeTpl','manager/_index.html');
		$this->assign('incmenu',conf('admin.label.menu'));
				
		//输出数据
		$result = $this->mod->getAll('*',array ('mid' =>$_GET["mid"],'cate'=>$_GET['cate'],'order'=>array("order"=>"ASC")) );
		$result=$this->formatNum($result);

		//输出位置信息
		$pos=array();
		foreach($this->nav as $k=>$v){
			$pos[]=$v['idname'];
		}
		$this->assign("navpos", implode('.',$pos));
		$this->assign("result", $result);
	}

	function ACT_add() {
		$this->assign('includeTpl',"manager/_add.html");
	}

	function ACT_update() {
		$this->assign('includeTpl',"manager/_add.html");
		$this->initRs();
	}

	function ACT_move(){
		if(!empty($_POST['eid'])){
			$label=$_POST['home'];
			
			if($label=='all'){//根路径
				$new=$mid=0;
			}else{
				$rs=$this->mod->getOne('*',array('idname'=>$label,'rootid'=>0,'cate'=>$_GET['cate']));
				$new=$mid=$rs['id'];//要移动到的目标路径的顶级标签
				
				if(!empty($_POST[$label])){
					$list=array();
					foreach($_POST[$label] as $key=>$val){
						if(!empty($val)) $list[]=$val;
					}
					if(!empty($list)){
						$key= implode('.',$list);
						$value=conf('label.'.$label,$key);
						$mid=$value['id'];//要移动到的目标路径
					}
				}
			}
			//echo $mid;
			//debug::d($_POST);exit;
			
			$idstr=$_POST['eid'];
			$allid=explode(',',$idstr);
			
			//对项目逐个更新
			foreach($allid as $id){
				$ids=array($id);
				$updateids=$ids;
				do{//批量逐级读取标签菜单
					$rs=$this->mod->getAll(array('id'),array('SQL'=>'mid='.implode(' OR mid=',$ids)));
					$ids=array();
					if(!empty($rs)){
						foreach($rs as $val){
							$ids[]=$val['id'];
							$updateids[]=$val['id'];
						}
					}
				}while(!empty($ids));
				
				//更新全部子级内容
				$rootid=empty($new)?$id:$new;
				$this->mod->Update(array('rootid'=>$rootid),array("SQL"=>"mid=".implode(" or mid=",$updateids)));
				
				//更新当前操作项的内容
				$this->mod->Update(array('mid'=>$mid,'rootid'=>$new),array("id"=>$id));
			}
			
			//更新原所在系列标签的缓存
			if( $_POST['rootid']!=$new ) $this->mod->setLabel($_POST['rootid'],$_GET['cate']);
			
			//更新当前所在系列标签的缓存
			$this->mod->setLabel($new,$_GET['cate']);
			
			go( $_POST['reurl'] );
		}
		
		$this->assign('includeTpl',"manager/_move.html");
		$this->assign('ids',implode(',',page::getIDs('M')));		
		$this->assign('root',label('all',$_GET['cate']));
		
	}

	function ACT_show(){
		$this->assign('includeTpl',"manager/_show.html");
		$value=empty($_GET['pos'])?conf('label.'.$_GET['key']):conf('label.'.$_GET['pos'],$_GET['key']);
		$this->assign('value',$value);
	}

	function ACT_order(){
		$this->mod->ord();

		//排序后需要更新所在级别的缓存
		$this->mod->setLabel($_GET['mid'],$_GET['cate']);
			
		go($_SERVER["HTTP_REFERER"]);
	}

	function ACT_status(){
		$ids=page::getIDs('M');
		if(!is_array($ids))$ids=array($ids);
		
		$rs=$this->mod->getAll(array('id','isshow'), array("mid"=>$_GET["mid"],'SQL'=>'id='.implode(' or id=',$ids) ) );

		$n=$y=array();
		foreach($rs as $k=>$v){
			if($v['isshow']=='1'){
				$y[]=$v['id'];
			}else{
				$n[]=$v['id'];
			}
		}
		if(!empty($y))
		$this->mod->Update(array('isshow'=>'0'),array('SQL'=>'id='.implode(' or id=',$y)));
		if(!empty($n))
		$this->mod->Update(array('isshow'=>'1'),array('SQL'=>'id='.implode(' or id=',$n)));
		
		$this->mod->setLabel($_GET["mid"],$_GET['cate']);

		go($_SERVER['HTTP_REFERER']);
	}

	function ACT_del() {
		$ids=page::getIDs('M');
		if(!empty($ids)){
			$delids=$ids;
			
			do{//批量逐级读取标签菜单
				$rs=$this->mod->getAll(array('id'),array('SQL'=>'mid='.implode(' OR mid=',$ids)));
				$ids=array();
				if(!empty($rs)){
					foreach($rs as $val){
						$ids[]=$val['id'];
						$delids[]=$val['id'];
					}
				}
			}while(!empty($ids));		
	
			//删除
			$this->mod->Remove(array('SQL'=>'id='.implode(' or id=',$delids)));
			
			//更新标签缓存
			$this->mod->setLabel($_GET["mid"],$_GET['cate']);
		}
		go("index.php?mid=" . $_GET["mid"]."&rid=". $_GET["rid"]."&rootid=". $_GET["rootid"]."&cate=". $_GET["cate"]);
	}

	function ACT_post(){
		$data = $_POST;
		$data['idname']=trim($data['idname']);
		
		if (!empty ($data["eid"])) { 
			$id=$data["eid"];
			$this->mod->Update($data,array("id"=>$data["eid"]));
		} else { 
			if($data['idname']=='all'){
				$check=true;//all是保留字
			}else{
				//检查同级中是否有同名标签
				$check=$this->mod->getOne(array('id'),array('idname'=>$data['idname'],'mid'=>$data['mid'], 'cate'=>$_GET['cate']));
			}
			
			if(!empty($check)) {
				$_SESSION["labelalert"] = "2";
			}else{
				$data['cate']=$_GET['cate'];
				$id=$this->mod->Insert($data);
				$this->mod->setOrder($id);
			}
		}
		
		//改动顶级标签后，删除原来的缓存
		if($data['mid']==0) {
			$this->mod->setLabel(0,$_GET['cate']);
			$this->mod->setLabel($id,$_GET['cate']);
		}

		//更新原所在系列标签的缓存
		if($data['rootid']!=0) $this->mod->setLabel($id,$_GET['cate']);

		go( $data['reurl'] );
	}

	function ACT_import(){
		if(!empty($_POST)){
			if(!empty($_FILES["import_file"])){
				$f=$_FILES["import_file"];
				if($f["size"]>0){
					$data = file_get_contents( $f['tmp_name'] );
					//TODO 加入对压缩文件的支持
					unlink( $f['tmp_name'] );
					
					$status = $this->doImport(unserialize($data),$_POST['mid'],$_POST['rootid']);
					if($status) {
						$this->mod->Execute( "UPDATE label SET `order`=id WHERE `order` is null" );
						$this->mod->setLabel($_POST['rootid'],$_GET['cate']);
					}
					
					$info = array(
						'name'=>$f['name'],
						'size'=>files::setupSize($f['size']),
						'status'=>$status
					);
					$this->assign( 'post_info',$info );
					$this->assign('parentURL',$_POST['reurl']);
				}
			}
		}else{
			$this->assign('post_max_size',ini_get('post_max_size'));
			$this->assign('parentURL',$_SERVER['HTTP_REFERER']);
		}
		
		$this->assign('includeTpl',"manager/_import.html");
	}

	function initRs(){
		$id=page::getIDs();
		if (empty ($id)) {
			go("index.php?mid=" . $_GET["mid"]);
		}
		$rs=$this->mod->getRecord($id);
		if(!empty($rs)){
			$this->assign("rs",$rs );
		}
		return $rs;
	}

	private function doImport($data,$mid,$rootid){
		foreach($data as $val){
			$target=array();
			foreach($val as $k=>$v){
				if(in_array($k,array('idname','name','note','isshow','createuser','updateuser'))){
					$target[$k]=$v;
				}
			}
			$target['mid']=$mid;
			$target['rootid']=$rootid;
			$target['cate']=$_GET['cate'];
				
			$id=$this->mod->Insert($target);
			if(empty($id)) return false;
							
			//根目录导入时改变rootid
			$newrootid=empty($rootid)?$id:$rootid;
			
			if(!empty($val['sublist'])) $this->doImport($val['sublist'],$id,$newrootid);
		}
		return true;
	}

	private function formatNum($result){
		if(empty($result)) return NULL;

		$ids=array();
		foreach($result as $val){
			$ids[]=$val['id'];
		}
		$sql="SELECT COUNT(mid)AS num,mid FROM label WHERE mid=".implode(' or mid=',$ids)." GROUP BY mid";
		$rs=$this->mod->getAll($sql);
		$pos=array();
		if(!empty($rs)){
			foreach($rs as $val){
				$pos[$val['mid']]=$val['num'];
			}
		}

		foreach($result as $key=>$val){
			$val['num']=empty($pos[$val['id']])?0:$pos[$val['id']];
			$result[$key]=$val;
		}
		return $result;
	}
}
?>