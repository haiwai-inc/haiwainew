<?php
/**
 * 全局标签基础类，逻辑复杂
 *
 */
class label_label extends Model{
	protected $tableName="label";
	protected $dbinfo=array("config"=>"main","type"=>"MySQL");

	private $LoopTitle=array();
	private $MenuTemp=array();

	private $labelPool=array();

	/**
	 * 获取当前编辑记录的内容
	 * @param int $id
	 * @return arr 编辑记录
	 */
	function getRecord($id){
		static $rs;
		if(empty($rs[$id])){
			$rs[$id]= $this->getOne( '*', array('id'=>intval($id)));
		}
		return $rs[$id];
	}

	/**
	 * 获取导航信息
	 * @param int $id
	 */
	function getNav( $id ){
		$this->getLoopTitle($id);
		krsort($this->LoopTitle);

		return $this->LoopTitle;
	}

	/**
	 * 验证要删除的数据是否可以操作
	 * @param int $id
	 */
	function checkDel($id){
		return $this->Count(  array( 'mid'=>intval($id) ));
	}
	
	/**
	 * 获得指定范围的全部标签信息
	 * @param array $rs 目标数组
	 * @param string $field 标签标识字段
	 */
	function getAllLabels($rs,$field){
		if(empty($rs)) return;
		
		$labels=$this->getAll('*',array('SQL'=>strings::idstr($rs, $field, 'id')));
		$result=array();
		
		if(empty($labels)) return;
		foreach($labels as $l){
			$result[$l['id']]=$l;
		}
		
		return $result;
	}
	
	/**
	 * 标签调用
	 * 
	 * @param string $key  document.list.xyz
	 * @param int $siteid  0、1、2、3
	 */
	function getLabel($key,$siteid){
		$obj = load("label_common");
		return $obj->getLabel($key,$siteid);
	}

	
	/**
	 * 根据参数更新标签缓存，返回缓存数据
	 *
	 * @param int or string $param
	 * @param int $cate 标签分类,0为系统标签，其它为对应站点ID下的标签
	 */
	function setLabel($param,$cate=0){ 
		if(is_numeric($param)){
			if($param==0)$param='all';
		}

		$cache =array();
		if( $param =='all'){
			//顶级标签数据
			$rs = $this->getAll('*',array('rootid'=>0,'cate'=>$cate));
			if(!empty($rs)){
				foreach($rs as $k=>$v){
					if(!empty($v['idname']))$cache[$v['idname']]=$v;
				}
			}

			$idname='all';
		}else{
			//子级标签数据
			$where=(is_numeric($param))?array('id'=>$param):array('idname'=>$param,'rootid'=>0);
			$where['cate']=$cate;
			$rs=$this->getOne(array('id','idname','rootid'),$where);

			if(!empty($rs)){
				//如当前更新的不是顶级标签，则找到顶级，更新之
				if($rs['rootid']!=0){
					$rs=$this->getOne(array('id','idname'),array('id'=>$rs['rootid']));
				}
	
				//循环处理标签数据
				$this->getLabelList($rs['id'],$cate);
				
				$idname=$rs['idname'];
				$cache=$this->getLooplist($rs['id']);
			}else{
				//仅用于前台调用 
				$idname=$param;
			}
		}
		
		//对没有值的缓存，为避免重复计算，设定值为n/a
		if(empty($cache)) $cache='n/a';

		//将标签内容写入缓存, $cate为0时为系统标签，为其它时是站点标签
		$filename=DOCUROOT."/cache/application/label/{$cate}_{$idname}.php";
		$cacheID="siteLabel_{$cate}_{$idname}";
		$obj=func_initValueCache($filename,$cacheID);
		$obj->set( $cache );
			
		return $cache;
	}


	/**
	 * 预处理,一次读取全部标签数据，对所有标签不分隶属关系进行分组
	 *
	 * @param int $rootid
	 */
	private function getLabelList( $rootid, $cate ){
		$list=array();
		$rs=$this->getAll('*',array('rootid'=>$rootid,'isshow'=>'1','cate'=>$cate,'order'=>array("order"=>"ASC")));
		foreach($rs as $k=>$v){
			$v['idname']=trim($v['idname']);
			$list[$v['mid']][]=$v;
		}

		$this->labelPool=$list;
	}

	/**
	 * 在分组标签的基础上，循环获取各级数据，返回分级后的数据及结构
	 * @param int $id
	 */
	private function getLooplist( $id ){
		if( empty($id) ) return false;
		if( empty($this->labelPool[$id]) ) return false;

		$list=array();
		foreach($this->labelPool[$id] as $value){
			if(!empty($value['idname'])){
				$value['sublist']=$this->getLooplist($value["id"]);
				$list[$value['idname']] = $value;
			}
		}
		return $list;
	}


	/**
	 * 循环生成上面函数调用的导航数据
	 * @param int $id
	 */
	private function getLoopTitle( $id ){
		if( empty($id) ){
			return false;
		}
		$rs=$this->getOne( array("name","idname","mid","id"), array( 'id'=>intval($id) ) );
		$this->LoopTitle[count($this->LoopTitle)] = $rs;

		if( !empty($rs["mid"]) ){
			$this->getLoopTitle($rs["mid"]);
		}

	}

}
?>