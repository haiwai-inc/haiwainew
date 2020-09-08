<?php
/**
 * 
 * 
 *  处理相关厂商
   
    调用
    $labelObj = load('page_data_label');
	$this->assign('mfrlist', $labelObj->loadVendor( $id, 'unit' ));
	
	保存
	$labelObj = load('page_data_label');
	$labelObj->saveLabels($id,'mfr','unit',$_POST['vendorDataValue']);
 * 
 * @author weiqi
 *
 */
class page_data_label extends Model{
	static public $rs=null;

	protected $tableName = 'data_label';
	protected $dbinfo=array("config"=>"main","type"=>"MySQL");

	//加载属性标签
	function loadLabels( $uid, $category ){
		$list=array();
		foreach(label('applications') as $key=>$val){
			$list[$val['id']]=$val['name'];
		}

		$labels=$this->getAll('*',array('uid'=>$uid,'type'=>'label','category'=>$category,'order'=>array('lid'=>'ASC')));

		$multiData=array();
		$html='';
		if(!empty($labels)){
			$i=0;
			foreach($labels as $val){
				$i++;
				$tmp=empty($list[$val['lid']])?'':$list[$val['lid']];
				$html.=$tmp."&nbsp;&nbsp;";
				if($i%3==0) $html.="<br>";

				$multiData[$val['lid']]=$tmp;
			}
		}

		$data=array('multiData'=>$multiData,'list'=>$list);
		return $data;
	}
	
	//加载关联厂商
	function loadVendor( $uid, $category ){
		
		$rs = $this->getAll('*',array('uid'=>$uid,'type'=>'mfr','category'=>$category,'order'=>array('lid'=>'ASC')));
		$ids=array();
		foreach($rs as $val){
			$ids[] = $val['lid'];
		}
		
		$list = array();
		
		if(!empty($ids)){
			$mfr = load('download_mfr');
			$list = $mfr->getAll(array('id','name'),array('SQL'=>'id='.implode(' OR id=',$ids),'order'=>array('name'=>'ASC')));
		}
		
		return $list;
	}
	
	//保存应用属性标签
	function saveLabels($id,$type,$category,$pid,$arr){
		if(!empty($arr)){
			//现在已经有的标签
			$labels=$this->getAll('*',array('uid'=>$id,'type'=>$type,'category'=>$category, 'pid'=>$pid));
			$pos=$delids=array();
			if(!empty($labels)){
				foreach($labels as $val){
					$pos[$val['lid']]=$val['id'];
					if(!in_array($val['lid'],$arr)) $delids[]=$val['lid'];//记录要删除的标签
				}
			}

			//添加标签
			$multiData=array();
			$multiData['key']=array('uid','lid','type','category','pid');
			$i=0;
			foreach($arr as $val){
				//要插入的新标签
				if(!empty($val)) {
					if(!isset($pos[$val])) $multiData['valuelist'][]=array($id,$val,$type,$category,$pid);
				}
			}
			if(!empty($multiData['valuelist'])) $this->Insert($multiData);

			//删除标签
			if(!empty($delids)) $this->Remove(array('uid'=>$id, 'type'=>$type, 'category'=>$category, 'pid'=>$pid, 'SQL'=>'lid='.implode(' or lid=',$delids)));
		}
	}
}