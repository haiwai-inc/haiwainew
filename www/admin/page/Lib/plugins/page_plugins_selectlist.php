<?php
class page_plugins_selectlist{
	
	function getObjValue($pid,$app){
		//推荐源
		$pageapp = empty($this->AppPrefix)?'page':$this->AppPrefix;
		
		//根据配置信息读取推荐内容及备选内容
		$obj = load("{$app['param']['app']}_page_selectlist");
		$app['param']['param']=strings::configStrDecode($app['param']['param']);
		
		if(empty($obj)) func_throwException("无法加载 {$app['param']['app']}_page_selectlist !");
		$result = $obj->getPageList($pid,$app,$pageapp);
		
		return $result;
	}

	
}