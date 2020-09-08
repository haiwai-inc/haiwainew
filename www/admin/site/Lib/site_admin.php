<?php
class site_admin extends Model{
	protected $tableName="admin";
	protected $dbinfo=array("config"=>"main","type"=>"MySQL");
	protected $MenuTemp=array();
	
	private $Looplist=array();
	private $LoopTitle=array();	

	function __construct() {
		parent :: __construct();
	}

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
	 * 循环生成上面函数调用的导航数据
	 * @param int $id
	 */
	private function getLoopTitle( $id ){
		if( empty($id) ){
			return false;
		}
		$rs=$this->getOne( array("name","mid","id"), array( 'id'=>intval($id) ) );
		$this->LoopTitle[count($this->LoopTitle)] = $rs;

		if( !empty($rs["mid"]) ){
			$this->getLoopTitle($rs["mid"]);
		}

	}

	/**
	 * @param int $id
	 */
	function getmidlist( $id ){
		$this->getLooplist($id);
		krsort($this->Looplist);
		return $this->Looplist;
	}

	/**
	 * 循环子目录的数据
	 * @param int $id
	 */
	private function getLooplist( $id ){
		if( empty($id) ){
			return false;
		}
		$rs=$this->getAll( array("name","mid","id"), array( 'mid'=>intval($id) ) );
		
		if(!empty($rs)){
			$this->Looplist[count($this->Looplist)] = $rs;
			foreach($rs as $value){
				$this->getLooplist($value["id"]);
			}
		}
	}

	/**
	 * 验证要删除的数据是否可以操作
	 * @param int $id
	 */
	function checkDel($id){
		return $this->Count(  array( 'mid'=>intval($id) ));
	}

	/**
	 * 判断是否是父级菜单
	 * @param int $mid
	 * @param str $lb
	 * @return bool
	 */
	function getSwitch($mid){
		$rs=$this->getOne("*",array( 'mid'=>$mid ) );
		if(empty($rs)){
			return false;
		}
		return true;
	}
}
?>