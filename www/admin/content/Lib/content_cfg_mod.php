<?php
class content_cfg_mod extends site_cfg_mod{
	function _loadModList($key=null){
		static $list;

		if(!isset($list)) $list=conf('admin.content.modList');
		if(empty($key))return $list;
		$result = empty($list[$key])? null : $list[$key] ;

		return $result;
	}
}
