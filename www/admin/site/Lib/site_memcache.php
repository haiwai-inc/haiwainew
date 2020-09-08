<?php
class site_memcache{
	
	//内置方法，获取缓存配置信息
	static function getConf( $pos ){
		$configstr = conf('global',"memcached.$pos");
		if(empty($configstr)) return;
		
		$config=array();
		$arr = explode(",",$configstr);
		foreach($arr as $val){
			$tmp=parse_url($val);
			unset($tmp['path']);
			
			$config[]=$tmp;
		}
		
		return $config;
	}
	
	static function debug($msg){
		echo $msg."\n";
		flush();
	}
}