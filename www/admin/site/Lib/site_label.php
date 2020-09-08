<?php
class site_label{
	
	/**
	 * 获取系统标签值,
	 * 标签系统是根据网站$langbase来定义的，
	 * 即一个或多个网站对应一套相同语言的label系统
	 */
	function getConfig($path,$langbase=null){
		static $config;
		$langbase=empty($langbase)?LANGBASE:$langbase;//设置语言基础路径
		
		// 第一级全局标签调用标识
		$item=empty($path[1])?'all':$path[1];
		$cacheID=$langbase.$item;
		
		if( !isset($config[$cacheID]) ){
			
			//启用valueCache,初始化相关变量
			$filename = DOCUROOT.'/cache/application/label/'.$langbase.$item.'.php';
			$cacheID='siteLabel_'.$langbase.$item;
			$obj=func_initValueCache($filename,$cacheID);
			
			//读取
			$config[$cacheID]=$obj->get($item);

			if( $this->checkClear()||empty($config[$cacheID])){
				$labels=load('label_label',array('langbase'=>$langbase));
				$config[$cacheID]=$labels->setLabel($item);
			}
		}
		
		return $config[$cacheID];
	}
	
	//判断是否要强制清除缓存
	private function checkClear(){
		if( !empty($_GET['clear']) ){
			if( $_GET['clear']=='label' ){
				$_GET['clear']='labelOK';
				echo 'Rebuild system label cache OK!<br>';
				return true;
			}
		}

		return false;
	}
	
}