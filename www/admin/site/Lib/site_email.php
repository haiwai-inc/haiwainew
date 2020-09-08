<?php
class site_email extends Model{
	protected $tableName = 'site_email';
	protected $dbinfo=array("config"=>"main","type"=>"MySQL");
	
	function getTpl( $siteID, $tplID ){
		$rs = $this->getOne('*',array('id'=>intval($siteID)));
		
		$default=conf("admin.site.emailbody");
		$config = empty($rs) ? array() : unserialize($rs['mailbody']) ;
		foreach($default as $key=>$value) $default[$key] = isset($config[$key])?$config[$key]:$value;
		
		$result = isset( $default[$tplID] )? $default[$tplID] : null ;
		
		return $result;
	}
}