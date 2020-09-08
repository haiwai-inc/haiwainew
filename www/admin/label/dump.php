<?php
require_once("../../inc.comm.php");
define( 'AppName', 'admin/label' );
if (empty($_POST["id"])) die("Parameter Error!");

$result=array();
$name='';
$obj = load('label_label');

//全部要读取的标签
$ids=page::getIDs('M');

//标签位置读取函数
function get( $id,$record=true ){
	global $obj,$name,$result;
	$rs = $obj->getOne( '*', array('id'=>intval($id)));
	if($rs['mid']==0){
		$str=$rs['idname'];
	}else{
		$str=get($rs['mid'],false).'.'.$rs['idname'];
	}
	
	if($record && $rs['isshow']=='1') $result[$id] = $rs;//当前第一级标签的内容到导出结果中
	
	if($record && empty($name)){ //获得导出文件的名称，仅一次
		if($rs['mid']==0){
			$name="Global_Label";
		}else{
			$rs = $obj->getOne( '*', array('id'=>$rs['mid']));
			$name = $rs['name'];
		}
		
	}
	
	return $str;
}

//是全局还是站内标签
$siteid = empty($_GET['cate'])?0:intval($_GET['cate']);

//遍历所有内容
foreach($ids as $id){
	$label=get($id);
	$conf=label($label,$siteid);
	
	if(!empty($conf)){
		$pos=strpos($label,'.');
		if(!empty($pos)){
			//子级标签的导出
			$result[$id]=$conf;
		}else{
			//顶级标签的导出
			$result[$id]['sublist']=$conf;
		}
	}
	
	
}

//序列化相关结果
$content=serialize($result);

header('Content-Encoding: utf-8');
header('Content-Disposition: inline; filename="'.$name.'.ldb"');
header("Content-type:application/octet-stream");

//输出下载
echo $content;