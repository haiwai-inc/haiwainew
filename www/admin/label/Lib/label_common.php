<?php
class label_common{
	/**
	 * 标签调用
	 * 
	 * @param string $key  document.list.xyz
	 * @param int $siteid  0、1、2、3
	 */
	function getLabel($key,$siteid){
		static $config;
		
		if( empty($key) ){
			$item = 'all';
		}else{
			$path = explode('.', $key); //键值
			$item = $path[0];
		}

		//缓存标识
		$cacheID="siteLabel_{$siteid}_{$item}";

		if( !isset($config[$cacheID]) ){
			//启用valueCache,初始化相关变量
			$filename = DOCUROOT."/cache/application/label/{$siteid}_{$item}.php";
			$cacheObj = func_initValueCache($filename,$cacheID);
				
			//读取
			$config[$cacheID] = $cacheObj->get($cacheID);

			//由于$config是静态变量，所以在程序生命周期内item只会被设置一次
			if( $this->checkClear($item) || empty($config[$cacheID]) ){
				$dataObj=load("label_label");
				
				//重置站点标签
				$config[$cacheID] = $dataObj->setLabel($item,$siteid);
				
				//重置系统标签
				if($siteid!=0) $dataObj->setLabel($item,0);
				
				//重置指定标签模板
				if($siteid != conf('global','lid')) $dataObj->setLabel($item,conf('global','lid'));
				
				//写入缓存
				//由于需要多次操作写缓存的动作, 所以相关操作放在 $dataObj->setLabel() 中完成
			}
		}
		
		//返回结果
		$result = $config[$cacheID];
		
		//如果有进一步取值的键设置
		if( !empty($path[1]) && is_array($result) ) {
			//重置key
			unset($path[0]);
			$q=implode('.', $path);
			
			//对于全局标签来说每一子级都是在sublist下面的
			$q=str_replace('.','.sublist.',$q);
	
			//获取最终结果
			$result=func_getKey(explode('.',$q),$result);
		}
		
		//对于站点标签，如没有取到值，尝试从全局标签中获取
		if( !is_array($result) && !empty($siteid) ) {
			if($result=='n/a') $result = $this->getLabel($key,0);
		}
		
		return $result;
	}
	
	//判断是否要强制清除缓存
	private function checkClear($item){
		if( !empty($_GET['clear']) ){
			if( $_GET['clear']=='label' ){
				echo 'Rebuild system label <strong>"'.$item.'"</strong> cache OK!<br>';
				return true;
			}
		}

		return false;
	}
}