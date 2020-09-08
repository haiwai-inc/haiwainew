<?php
abstract class basicContentAction extends Action{
	
	//处理article显示时调用的配置
	protected function loadArticleConfig($type,$appconfig){
		static $cache;
		
		if(empty($cache)){
			
			$config = conf('admin.page.modList');
			foreach($config[$type]['config'] as $key=>$val){
				if( !isset($appconfig[$key]) )$appconfig[$key]=$val['defaultValue'];
				
				//对于类型为checkbox的配置，为方便smarty中判断，将其转换成数组
				if( $val['type'] == 'checkbox' ) {
					$val=explode(',',$appconfig[$key]);
					if( !empty($val) ){
						$tmp=array();
						foreach($val as $v){
							$tmp[$v] = true;
						}
						$appconfig[$key]=$tmp;
					}
				}
			}
			$cache=$appconfig;
		}
		
		return $cache;
	}
}