<?php
/**
 * 初始化多级联动标签
 * @param string $val　　 如地域标签　"all.67.149" 67 149 为标签调用名称
 * @param string $label  如地域标签  region
 * @return array
 */
function initLabel($val,$label){
	$val=trim(strings::unescape($val));
	$label=trim(strings::unescape($label));
	
	$arr=explode('.',$val);
	
	$key="";
	$list=array();
	$i=0;//记录arr的键值
	
	foreach($arr as $id){
		$i++;
		if($id=='all'){
			$key="";//第一级取全部
		}else{
			$key=$key.$id.".";//递归级仅取子级内容
		}
	
		$tmplabel=conf('label.'.$label, $key );
		
		if(!empty($tmplabel)){
			$tmpresult=array();
			foreach($tmplabel as $val){
				//判断当前输出是否已经选中
				$select=empty($arr[$i])?false:(($val['idname']==$arr[$i])?true:false);
				$tmpresult[]=array($val['idname'],$val['name'],$select);
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
 * @param string $label   标签ID  
 * @param int $i 上级标识
 * @param string $itemkey　上级值的范围
 * @return array
 */
function getLabel($val,$label,$i,$itemkey){
	$val=trim(strings::unescape($val));
	$itemkey=trim(strings::unescape($itemkey));
	
	$key = $itemkey.$val.'.';
	$tmplabel=conf('label.'.$label, $key );
	
	$list=array();
	if(!empty($tmplabel)){
			$tmpresult=array();
			foreach($tmplabel as $val){
				$tmpresult[]=array($val['idname'],$val['name'],false);
			}
			
			//$tmpresult是options值的列表；$key是options值的读取条件，传递后用于下一级的内容再读取
			$list=array($tmpresult,$key);
	}
		
	$result=empty($list)?0:array($label,$list,$i);//label是当前级联标签的ID; $i是当前级联标签隶属的上级位置，包括容器div和select菜单
	return $result;
}
