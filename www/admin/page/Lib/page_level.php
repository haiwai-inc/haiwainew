<?php
class page_level extends Model{
	protected $tableName = 'page_level';
	protected $dbinfo=array("config"=>"main","type"=>"MySQL");
	
	protected $LoopTitle=array();
	protected $MenuTemp=array();

	function formatNum($result){
		if(empty($result)) return NULL;

		$ids=array();
		foreach($result as $val){
			$ids[]=$val['id'];
		}
		$sql="SELECT COUNT(mid)AS num,mid FROM {$this->tableName} WHERE mid=".implode(' or mid=',$ids)." GROUP BY mid";
		$rs=$this->getAll($sql);
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

	function getNav( $id ){
		$this->getLoopTitle($id);
		krsort($this->LoopTitle);

		return $this->LoopTitle;
	}

	/**
	 * 循环生成上面函数调用的导航数据
	 * @param int $id
	 */
	private function getLoopTitle( $id ){
		if( empty($id) ){
			return false;
		}
		$rs=$this->getOne( array('name','url','mid','id','pid'), array( 'id'=>intval($id) ) );
		$this->LoopTitle[count($this->LoopTitle)] = $rs;

		if( !empty($rs['mid']) ){
			$this->getLoopTitle($rs['mid']);
		}

	}

	
}