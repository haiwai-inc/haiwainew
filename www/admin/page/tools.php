<?php
include( "./inc.config.php" );

func_initSession();
if( empty($_SESSION['UserType'])||empty($_GET['pid'])||empty($_GET['app']) )go('/');

$pid=$_GET['pid'];
$app=$_GET['app'];

//获得相关模块
$appObj=load('page_cfg_app');
$rs=$appObj->getOne('*',array('pid'=>$pid,'status'=>'Y','tpl'=>$app));
if(empty($rs)) exit('There is not a app which was named '.$app);

//加载配置信息
$rs['pagetype']='page';
$rs['param']=empty($rs['param'])?array():unserialize($rs["param"]);
$rs['param']=$appObj->loadConfig($rs['param'],$rs['app']);
configure::setValue(

//加载模块
include_once DOCUROOT.'/admin/page/Lib/plugins/_base.php'; 
$obj=load('plugins/page_plugins_'.$rs["app"], 'admin/page');
$value = $obj->getObjValue($rs["pid"],$rs);

//调用参数
debug::d($rs['param']);

//生成结果
$result=array(
	'title'=>$rs["name"],
	'app'=>$rs["app"],
	'url'=>$rs["url"], 
	'description'=>$rs["description"],
	'obj'=>$value, 
	'num'=>is_array($value)?count($value):0
);

debug::d($result);
?>