<?php
/**
 * 初始化多级联动标签
 * @param string $val　　 如地域标签　"all.67.149"  67 149 为标签ID
 * @param string $label  如地域标签  region
 * @return array
 */
function initLabel($val,$label){
	$arr=explode('.',$val);
	
	$key="";
	$list=array();
	$i=0;//记录arr的键值
	
	foreach($arr as $id){
		$i++;
		if($id=='all'||$id==0){
			//第一级取全部
			$rs=label($label );
		}else{
			//取子级内容
			$obj=load('label_label');
			$rs=$obj->getAll('*',array('mid'=>$id));
		}
		
		if(!empty($rs)){
			$tmpresult=array();
			foreach($rs as $val){
				//判断当前输出是否已经选中
				$select = empty($arr[$i])?false:(($val['id']==$arr[$i])?true:false);
				$tmpresult[]=array($val['id'],$val['name'],$select);
			}
			
			$list[]=array($tmpresult,$key);
		}
	}
	
	$result=empty($list)?0:array($label,$list);
	return $result;
}

/**
 * 获得单一下级菜单的数据
 * @param string $val  要读取的下级菜单的ID
 * @param string $label  标签ID  
 * @param int $i 上级标识
 * @param string $itemkey　上级值的范围, 在cate.php中这个设置没用
 * @return array
 */
function getLabel($id,$label,$i,$itemkey){
	//取子级内容
	$obj=load('label_label');
	$rs=$obj->getAll('*',array('mid'=>$id));
	
	$list=array();
	if(!empty($rs)){
		$tmpresult=array();
		foreach($rs as $val){
			$tmpresult[]=array($val['id'],$val['name'],false);
		}
		
		//$tmpresult是options值的列表；$key是options值的读取条件，传递后用于下一级的内容再读取
		$list=array($tmpresult);
	}
		
	$result=empty($list)?0:array($label,$list,$i);//label是当前级联标签的ID; $i是当前级联标签隶属的上级位置，包括容器div和select菜单
	return $result;
}