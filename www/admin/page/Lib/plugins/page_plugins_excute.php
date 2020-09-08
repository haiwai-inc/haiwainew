<?php
class page_plugins_excute{
	function getObjValue($pid,$app){
		$config=array();
		$result=array();
		$str=$app['param']['param'];//x:1,y:2,z:3,....
		
		$arr=explode(',',$str);
		if(empty($arr)) return;
		
		foreach($arr as $val){
			$p=explode(':',$val);
			if(count($p)==2)$config[$p[0]]=$p[1];
		}
		
		$obj=load($app['param']['app'].'_'.$app['param']['class']);// article_page_plugins
		if(method_exists($obj,$app['param']['method'])){
			$result=$obj->$app['param']['method']($pid,$config);
		}
		
		return $result;
	}
	
}