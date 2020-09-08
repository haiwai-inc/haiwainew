<?php
abstract class pagePlugins{
	protected $paramAll = null;
	protected $paramUnit = null;

	//生成where in 的字串
	protected function makein($rs){
		if(empty($rs)) return null;

		$ids=array();
		foreach($rs as $val){
			$ids[]=$val["mid"];
		}
		$idstr=implode(",",$ids);

		return $idstr;
	}

	//获取已经推荐的id
	protected function getCommendIDs($page_id){
		$DR=func_loadModel("page_data_relation","page");

		//获得有序的数据单元列表
		$rs=$DR->getOutList($page_id,$this->paramUnit);
		if(empty($rs))return null;

		return $rs;
	}

	//根据推荐ID和数据单元的详细信息，按序重新生成完整的目标数据
	protected function getCommendList($rs,$pos){
		$list=array();
		if(!empty($rs)){
			foreach($rs as $val){
				if(!empty($pos[$val["mid"]])){
					$list[]=$pos[$val["mid"]];
				}
			}
		}
		return $list;
	}
}
?>