<?php
class page_plugins_pictext{
	private $appconfig;
	
	function getObjValue($pid,$app){
		$Units=load( $app['pagetype'].'_data_unit' );
		$tmp=$Units->getRecord($pid,$app);
		if(empty($tmp)) return array();
		
		//根据配置信息，截取指定数量的图文单元
		$i=0;
		$max=intval($app['param']['picnums']);
		foreach($tmp as $key=>$val){
			if($i==$max) break;
			$result[]=$val;
			$i++;
		}
		return $result;
	}
	
}